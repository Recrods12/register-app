<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function users(Request $request): View
    {
        $search = trim($request->string('search')->value());

        $users = User::query()
            ->withCount('bookings')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('role', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->get();

        return view('admin_users', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['user', 'admin'])],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'user_updated',
            'message' => 'Admin memperbarui akun ' . $user->name . '.',
            'changes' => ['target_user_id' => $user->id],
        ]);

        return back()->with('success', 'Data user berhasil diperbarui.');
    }

    public function calendar(Request $request): View
    {
        $month = $request->string('month')->value() ?: now()->format('Y-m');
        $selectedRoomId = $request->string('room_id')->value();
        $startOfMonth = Carbon::createFromFormat('Y-m', $month, config('app.timezone'))->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $bookings = Booking::with(['room', 'user'])
            ->whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->when($selectedRoomId, fn ($query) => $query->where('room_id', (int) $selectedRoomId))
            ->orderBy('start_time')
            ->get();

        return view('admin_calendar', [
            'month' => $month,
            'rooms' => Room::orderBy('floor')->get(),
            'selectedRoomId' => $selectedRoomId,
            'bookingsByDate' => $bookings->groupBy(fn (Booking $booking) => $booking->start_time->toDateString()),
            'calendarDays' => collect(range(1, $endOfMonth->day))->map(fn (int $day) => $startOfMonth->copy()->day($day)),
        ]);
    }

    public function activityLogs(): View
    {
        return view('admin_activity_logs', [
            'logs' => ActivityLog::with(['user', 'booking.room'])->latest()->get(),
        ]);
    }

    public function backup(Request $request): StreamedResponse
    {
        $bookings = Booking::with(['room', 'user'])->orderBy('start_time')->get();
        $filename = 'backup-booking-' . now()->format('Y-m-d-His') . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Backup Booking');

        $headers = ['ID', 'Tanggal', 'Jam Mulai', 'Jam Selesai', 'Ruang', 'Lantai', 'Bidang', 'Nama Pemesan', 'Email', 'Perihal', 'Status', 'Catatan Kegiatan', 'Foto Kegiatan', 'Dibuat Pada'];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->freezePane('A2');
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1D4ED8'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getDefaultRowDimension()->setRowHeight(24);
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(22);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(28);
        $sheet->getColumnDimension('H')->setWidth(24);
        $sheet->getColumnDimension('I')->setWidth(24);
        $sheet->getColumnDimension('J')->setWidth(24);
        $sheet->getColumnDimension('K')->setWidth(14);
        $sheet->getColumnDimension('L')->setWidth(28);
        $sheet->getColumnDimension('M')->setWidth(16);
        $sheet->getColumnDimension('N')->setWidth(18);

        $row = 2;
        foreach ($bookings as $booking) {
            $sheet->fromArray([
                $booking->id,
                $booking->start_time->format('d-m-Y'),
                $booking->start_time->format('H:i'),
                $booking->end_time->format('H:i'),
                $booking->room->name,
                'Lantai ' . $booking->room->floor,
                $booking->title,
                $booking->user->name,
                $booking->user->email,
                $booking->description,
                $booking->isActive() ? 'Berlangsung' : ($booking->isEnded() ? 'Selesai' : 'Mendatang'),
                $booking->activity_note ?: '-',
                '',
                $booking->created_at?->format('d-m-Y H:i'),
            ], null, 'A' . $row);

            $sheet->getRowDimension($row)->setRowHeight(72);

            if ($booking->activity_photo_path) {
                $imagePath = storage_path('app/public/' . $booking->activity_photo_path);

                if (is_file($imagePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Foto Kegiatan');
                    $drawing->setDescription('Foto kegiatan booking');
                    $drawing->setPath($imagePath);
                    $drawing->setHeight(52);
                    $drawing->setCoordinates('M' . $row);
                    $drawing->setOffsetX(24);
                    $drawing->setOffsetY(8);
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue('M' . $row, 'File tidak ditemukan');
                }
            } else {
                $sheet->setCellValue('M' . $row, '-');
            }

            $row++;
        }

        $lastRow = max(2, $row - 1);
        $sheet->getStyle('A1:N' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K2:K' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L2:L' . $lastRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('M2:M' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('N2:N' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $tempFile = tempnam(sys_get_temp_dir(), 'booking-backup-');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->streamDownload(function () use ($tempFile): void {
            readfile($tempFile);
            @unlink($tempFile);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}

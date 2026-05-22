# 📅 Meeting Room Booking System

Sistem online booking ruang rapat dengan 4 lantai (1 ruang per lantai) yang dilengkapi dengan:

- Dashboard real-time dengan status warna (merah/hijau)
- User authentication (register/login)
- Booking management (create, view, edit, cancel)
- Automatic status update berdasarkan waktu booking

## 🎯 Fitur Utama

### 1️⃣ Dashboard Index (/)

```
✅ Menampilkan 4 ruang rapat (Lantai 1-4)
✅ Status real-time dengan warna:
   🟢 HIJAU = Ruang tersedia (tidak ada booking aktif)
   🔴 MERAH = Ruang sedang digunakan (booking aktif)
✅ Menampilkan detail booking yang sedang berlangsung
✅ Tombol "Booking Sekarang" untuk setiap ruang
✅ Auto-refresh setiap 30 detik
```

### 2️⃣ Authentication

```
✅ Register - Buat akun baru
✅ Login - Masuk dengan email & password
✅ Logout - Keluar dari aplikasi
✅ Protected routes - Hanya user login bisa booking
```

### 3️⃣ Booking Management

```
✅ Create Booking:
   - Pilih ruang, waktu mulai, waktu selesai
   - Max durasi 24 jam
   - Validasi conflict (tidak bisa overlap)

✅ View Bookings:
   - Lihat semua booking user
   - Tampil status (Mendatang, Sedang Berlangsung, Selesai)
   - Durasi otomatis terhitung

✅ Edit Booking:
   - Edit jadwal ke slot kosong
   - Validasi conflict saat edit
   - Max durasi tetap 24 jam

✅ Cancel Booking:
   - Batalkan booking dengan konfirmasi
   - Booking langsung dihapus
```

## 📁 File Structure

```
app/
├── Models/
│   ├── Room.php          ← Model ruang rapat
│   ├── Booking.php       ← Model booking
│   └── User.php          ← Updated dengan relationship
├── Http/Controllers/
│   ├── DashboardController.php  ← Dashboard logic
│   └── BookingController.php    ← Booking CRUD

database/
├── migrations/
│   ├── 2026_04_20_055300_create_rooms_table.php
│   └── 2026_04_20_055400_create_bookings_table.php
└── seeders/
    └── RoomSeeder.php    ← Seed 4 ruang rapat

resources/views/
├── index.blade.php                ← Dashboard utama
├── create_booking.blade.php       ← Form buat booking
├── list_bookings.blade.php        ← List booking user
└── edit_booking.blade.php         ← Form edit booking

routes/
└── web.php               ← Semua route sudah dikonfigurasi
```

## 🚀 Quick Start

### Prerequisites

- PHP 8.3+
- MySQL 5.7+
- Composer
- Node.js (optional, untuk assets)

### Installation

1. **Database Migration**

    ```bash
    cd c:\Users\MSI\register-app
    php artisan migrate --force
    php artisan db:seed
    ```

2. **Start Server**

    ```bash
    php artisan serve
    ```

3. **Open Browser**
    ```
    http://localhost:8000
    ```

### Test Account

```
Email: test@example.com
Password: password
```

## 💻 Database Schema

### rooms table

```sql
id (INT, Primary Key)
floor (INT) - 1, 2, 3, 4
name (VARCHAR) - Nama ruang
description (TEXT)
created_at, updated_at
```

### bookings table

```sql
id (INT, Primary Key)
user_id (INT, Foreign Key → users)
room_id (INT, Foreign Key → rooms)
start_time (DATETIME)
end_time (DATETIME)
title (VARCHAR)
description (TEXT)
created_at, updated_at
```

## 🎨 Color Logic

```javascript
Status MERAH 🔴:
- Jika start_time <= NOW && end_time > NOW
- Booking sedang berlangsung

Status HIJAU 🟢:
- Jika tidak ada booking aktif
- Ruang tersedia untuk di-booking

Auto-update:
- Dashboard refresh 30 detik
- Status otomatis berubah saat jam booking mulai/selesai
```

## 🔐 Validation Rules

```
Create/Edit Booking:
✅ Room harus dipilih
✅ Start time harus di masa depan (CREATE only)
✅ End time harus setelah start time
✅ Durasi maksimal 24 jam (86400 detik)
✅ Tidak ada conflict dengan booking lain
✅ Title & description optional
```

## 📝 API Routes

| Method | Path                  | Auth | Description                   |
| ------ | --------------------- | ---- | ----------------------------- |
| GET    | `/`                   | No   | Dashboard dengan status ruang |
| GET    | `/register`           | No   | Form register                 |
| POST   | `/register`           | No   | Proses register               |
| GET    | `/login`              | No   | Form login                    |
| POST   | `/login`              | No   | Proses login                  |
| POST   | `/logout`             | Yes  | Logout user                   |
| GET    | `/bookings`           | Yes  | List booking user             |
| GET    | `/bookings/create`    | Yes  | Form buat booking             |
| POST   | `/bookings`           | Yes  | Proses buat booking           |
| GET    | `/bookings/{id}/edit` | Yes  | Form edit booking             |
| PUT    | `/bookings/{id}`      | Yes  | Proses edit booking           |
| DELETE | `/bookings/{id}`      | Yes  | Proses cancel booking         |

## 🛠️ Customization

### Ubah Jumlah Lantai

1. Edit `RoomSeeder.php`
2. Tambah/kurangi room entries
3. Run: `php artisan db:seed --class=RoomSeeder`

### Ubah Durasi Maksimal

Edit di `BookingController.php`, method `store()`:

```php
if ($duration > 86400) {  // 86400 = 24 jam
    // Ubah 86400 ke angka lain (dalam detik)
}
```

### Ubah Refresh Interval

Edit di `index.blade.php`:

```javascript
setInterval(() => {
    location.reload();
}, 30000); // 30000 = 30 detik, ubah ke angka lain
```

### Ubah Timezone

Edit `.env`:

```
APP_TIMEZONE=Asia/Jakarta
```

## 🐛 Troubleshooting

### Database Error

```bash
# Buat database jika belum ada
mysql -u root -e "CREATE DATABASE register_db;"

# Reset database (HATI-HATI: AKAN HAPUS DATA!)
php artisan migrate:fresh --seed
```

### Session Error

```bash
php artisan session:table
php artisan migrate
```

### Cache Error

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Conflict Detection Not Working

```bash
php artisan migrate:fresh --seed
# Pastikan timezone di .env benar
```

## 📱 UI Components

### Status Badges

- 🟢 HIJAU - Border green, background green
- 🔴 MERAH - Border red, background red
- 🟦 MENDATANG - Border blue, background blue
- ⚪ SELESAI - Border slate, background slate

### Form Elements

- Input datetime-local untuk jam
- Select dropdown untuk pilih ruang
- Textarea untuk deskripsi
- Validation error messages

### Responsive Design

- Desktop: Grid 2 kolom untuk ruang
- Mobile: Grid 1 kolom untuk ruang
- Header responsive dengan navigation

## ✅ Testing Checklist

- [ ] Migration berhasil dan database punya 4 ruang
- [ ] Dashboard menampilkan 4 ruang dengan status HIJAU
- [ ] Bisa register user baru
- [ ] Bisa login dengan test account
- [ ] Bisa create booking
- [ ] Status berubah MERAH saat jam booking mulai
- [ ] Status kembali HIJAU saat booking selesai
- [ ] Bisa edit booking ke slot kosong
- [ ] Bisa cancel booking
- [ ] Conflict detection bekerja
- [ ] Max 24 jam validation bekerja

## 📚 Documentation Files

- `BOOKING_SETUP.md` - Setup guide lengkap
- `QUICK_START.md` - Quick start & testing checklist
- `README.md` - Dokumentasi ini

## 🎓 Learn More

- [Laravel Documentation](https://laravel.com/docs)
- [Blade Templates](https://laravel.com/docs/blade)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Tailwind CSS](https://tailwindcss.com)

## 📄 License

This project is open source and available under the MIT License.

---

**Built with ❤️ using Laravel 13 + Tailwind CSS**

Untuk support atau pertanyaan, silakan buat issue di repository.

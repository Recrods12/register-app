# 🚀 Quick Start - Meeting Room Booking System

## ⚡ 3 Langkah Setup Cepat

### Step 1: Jalankan Migration (5 menit)

```cmd
cd c:\Users\MSI\register-app
run-migration.bat
```

Atau via Command Prompt:

```cmd
cd c:\Users\MSI\register-app
php artisan migrate --force
php artisan db:seed
```

### Step 2: Start Server (2 menit)

```cmd
cd c:\Users\MSI\register-app
php artisan serve
```

### Step 3: Buka Browser

```
http://localhost:8000
```

## ✅ Testing Checklist

### Tanpa Login (Public Pages)

- [ ] Buka `/` - Lihat dashboard dengan 4 ruang rapat
- [ ] Ruang tampil dengan status HIJAU 🟢 (belum ada booking)
- [ ] Klik "Login untuk Booking" - Redirect ke login

### Register User

- [ ] Buka `/register`
- [ ] Isi form dengan data baru
- [ ] Klik "Register"
- [ ] Seharusnya auto-login dan redirect ke `/`

### Login

- [ ] Buka `/login`
- [ ] Email: `test@example.com`
- [ ] Password: `password`
- [ ] Klik "Login Sekarang"

### Dashboard (Setelah Login)

- [ ] Lihat 4 ruang dengan status HIJAU 🟢
- [ ] Tombol "Booking Sekarang" tersedia
- [ ] Tombol "Booking Saya" di header
- [ ] Tombol "Logout" di header

### Create Booking

- [ ] Klik "Booking Sekarang" pada salah satu ruang
- [ ] Pilih ruang rapat
- [ ] Isi:
    - Jam mulai: Hari ini, 10:00
    - Jam selesai: Hari ini, 11:00
    - Judul: "Test Meeting"
    - Deskripsi: "Testing booking system"
- [ ] Klik "Buat Booking"
- [ ] Seharusnya redirect ke `/bookings` dengan pesan sukses

### Dashboard Status Update

- [ ] Kembali ke `/`
- [ ] Status ruang yang sudah di-booking masih hijau (jika belum jam mulai)
- [ ] Tunggu sampai jam mulai booking
- [ ] Refresh halaman (atau tunggu 30 detik)
- [ ] Status seharusnya berubah MERAH 🔴 saat jam mulai
- [ ] Info booking terlihat (nama, durasi, waktu selesai)

### View Bookings

- [ ] Klik "Booking Saya"
- [ ] Lihat booking yang baru dibuat
- [ ] Status menunjukkan:
    - Jika belum dimulai: 🟦 MENDATANG
    - Jika sedang berlangsung: 🔴 SEDANG BERLANGSUNG
    - Jika sudah selesai: ⚪ SELESAI

### Edit Booking

- [ ] Klik tombol "✏️ Edit" pada booking yang belum selesai
- [ ] Ubah jam (misal 14:00 - 15:00)
- [ ] Klik "Simpan Perubahan"
- [ ] Booking seharusnya terupdate

### Conflict Testing

- [ ] Buat booking Lantai 1: 10:00 - 11:00
- [ ] Coba buat booking Lantai 1: 10:30 - 11:30
- [ ] Seharusnya error: "Ruang sudah ada booking pada waktu tersebut"

### Max Duration Testing

- [ ] Buat booking: 10:00 - 35:00 (25 jam)
- [ ] Seharusnya error: "Durasi booking maksimal 24 jam"

### Cancel Booking

- [ ] Buat booking baru
- [ ] Klik "🗑️ Batalkan"
- [ ] Konfirmasi pembatalan
- [ ] Booking seharusnya dihapus

### Logout

- [ ] Klik "Logout" di header
- [ ] Seharusnya redirect ke `/login`
- [ ] Session berakhir

## 🔴 Expected Errors (For Testing)

### Saat jam mulai booking

```
Ruang seharusnya BERUBAH WARNA dari HIJAU ke MERAH
```

### Saat jam selesai booking

```
Ruang seharusnya BERUBAH WARNA dari MERAH ke HIJAU
```

## 📊 Database Status

Setelah migration, Anda memiliki:

- **Tabel rooms**: 4 record (Lantai 1-4)
- **Tabel bookings**: Kosong (nanti terisi saat booking dibuat)
- **Tabel users**: 1 test user + user baru saat register

## 🎨 Color Codes

| Warna        | Status    | Makna                         |
| ------------ | --------- | ----------------------------- |
| 🟢 HIJAU     | Available | Ruang kosong, bisa di-booking |
| 🔴 MERAH     | Booked    | Ruang sedang digunakan        |
| ⚪ SELESAI   | Ended     | Booking sudah berakhir        |
| 🟦 MENDATANG | Upcoming  | Booking belum dimulai         |

## 💡 Tips

- Dashboard auto-refresh setiap 30 detik
- Jam menggunakan timezone server (sesuaikan di `.env` jika perlu)
- Durasi max 24 jam (86400 detik)
- Hanya bisa edit ke slot yang kosong
- Bisa booking 24 jam sebelum atau sesudah booking lain

## ❌ Troubleshooting

### Database error

```cmd
mysql -u root -e "CREATE DATABASE register_db;"
php artisan migrate --fresh --seed
```

### Cache issue

```cmd
php artisan cache:clear
php artisan config:clear
```

### Stuck on login

```cmd
php artisan session:table
php artisan migrate
```

---

**Selamat mencoba! 🎉**

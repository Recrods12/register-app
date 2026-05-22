# Meeting Room Booking System - Setup Guide

Sistem booking ruang rapat online telah dibuat dengan fitur lengkap. Ikuti langkah-langkah di bawah untuk menjalankan aplikasi.

## 📋 Langkah Setup

### 1. Jalankan Database Migration & Seeding

**Buka Command Prompt (cmd.exe) dan jalankan:**

```cmd
cd c:\Users\MSI\register-app
php artisan migrate --no-interaction --force
php artisan db:seed --no-interaction
```

Atau **double-click** file: `run-migration.bat`

Ini akan membuat:

- Tabel `rooms` dengan 4 ruang rapat (1 per lantai)
- Tabel `bookings` untuk menyimpan data booking
- User test dengan email: test@example.com, password: password

### 2. Jalankan Development Server

**Buka Command Prompt dan jalankan:**

```cmd
cd c:\Users\MSI\register-app
php artisan serve
```

Server akan berjalan di: **http://127.0.0.1:8000**

### 3. Buka Browser dan Test

```
http://localhost:8000/
```

## 🎯 Fitur Aplikasi

### Dashboard Utama (/)

- Menampilkan 4 ruang rapat (Lantai 1-4)
- Status real-time dengan warna:
    - 🟢 **HIJAU**: Ruang tersedia
    - 🔴 **MERAH**: Ruang sedang digunakan
- Tombol "Booking Sekarang" untuk setiap ruang (perlu login)

### Authentication

- **Register**: `/register` - Membuat akun baru
- **Login**: `/login` - Masuk dengan email & password
- **Logout**: Tombol logout di header

### Booking Management (/bookings)

- **Lihat Booking**: Lihat semua booking Anda
- **Buat Booking**: `/bookings/create` - Buat booking baru
- **Edit Booking**: Edit jadwal ke slot kosong
- **Batalkan Booking**: Hapus booking

### Status Warna (Real-time)

- **MERAH 🔴**: Booking sedang berlangsung (start_time <= now < end_time)
- **HIJAU 🟢**: Ruang kosong (tidak ada booking aktif)
- **AUTO-REFRESH**: Dashboard refresh setiap 30 detik

## 📝 File-File Penting

### Database

- Migrations: `database/migrations/`
    - `2026_04_20_055300_create_rooms_table.php`
    - `2026_04_20_055400_create_bookings_table.php`
- Seeders: `database/seeders/RoomSeeder.php`

### Models

- `app/Models/Room.php` - Model untuk ruang rapat
- `app/Models/Booking.php` - Model untuk booking
- `app/Models/User.php` - Model user (sudah diupdate)

### Controllers

- `app/Http/Controllers/DashboardController.php` - Dashboard utama
- `app/Http/Controllers/BookingController.php` - Logika booking

### Views

- `resources/views/index.blade.php` - Dashboard utama dengan status ruang
- `resources/views/create_booking.blade.php` - Form buat booking
- `resources/views/list_bookings.blade.php` - List booking user
- `resources/views/edit_booking.blade.php` - Form edit booking

### Routes

- `routes/web.php` - Semua route sudah dikonfigurasi

## 🔐 Test Account

**Email**: test@example.com  
**Password**: password

## ⚙️ Konfigurasi Database

File `.env` sudah dikonfigurasi untuk MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=register_db
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan MySQL server sudah berjalan dan database `register_db` sudah ada.

## 🚀 Troubleshooting

### Error: Database tidak ditemukan

```cmd
mysql -u root -e "CREATE DATABASE register_db;"
```

### Error: Migration failed

```cmd
php artisan migrate:fresh --seed
```

### Clear cache

```cmd
php artisan cache:clear
php artisan config:clear
```

## 📱 URL Penting

| Halaman        | URL                   | Status        |
| -------------- | --------------------- | ------------- |
| Dashboard      | `/`                   | Publik        |
| Register       | `/register`           | Publik        |
| Login          | `/login`              | Publik        |
| Booking List   | `/bookings`           | Auth Required |
| Create Booking | `/bookings/create`    | Auth Required |
| Edit Booking   | `/bookings/{id}/edit` | Auth Required |

## ✅ Fitur Lengkap

- ✅ 4 Ruang Rapat (Lantai 1-4)
- ✅ Status Real-time (Merah/Hijau)
- ✅ User Authentication (Register/Login)
- ✅ Create Booking (Max 24 jam)
- ✅ View Booking
- ✅ Edit Booking (Ke slot kosong)
- ✅ Cancel Booking
- ✅ Conflict Detection (Tidak bisa booking waktu yang sama)
- ✅ Auto-refresh Dashboard (30 detik)
- ✅ Professional UI (Tailwind CSS)

## 🛠 Next Steps (Opsional)

1. Customize styling di `resources/css/app.css`
2. Tambah notifikasi email saat booking
3. Tambah fitur admin untuk melihat semua booking
4. Tambah approval system jika diperlukan
5. Deploy ke production

---

**Setup selesai!** 🎉 Silakan jalankan migration dan server sesuai langkah di atas.

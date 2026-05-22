# 🎯 Quick Commands - Meeting Room Booking System

Simpan file ini untuk referensi command yang sering digunakan.

## 🚀 SETUP & RUN

### 1️⃣ First Time Setup

```bash
cd c:\Users\MSI\register-app

# Migrate database dan seed 4 ruang rapat
php artisan migrate --force
php artisan db:seed

# Start development server
php artisan serve
```

**Output yang diharapkan:**

```
Laravel development server started: http://127.0.0.1:8000
```

### 2️⃣ Buka Browser

```
http://localhost:8000
```

---

## 📋 DEVELOPMENT COMMANDS

### Database Commands

```bash
# Run migrations
php artisan migrate

# Run migrations + seed
php artisan migrate --seed

# Reset database (HATI-HATI: HAPUS DATA!)
php artisan migrate:fresh

# Reset database + seed
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback

# Seed database
php artisan db:seed

# Seed specific seeder
php artisan db:seed --class=RoomSeeder
```

### Server Commands

```bash
# Start development server (default: http://127.0.0.1:8000)
php artisan serve

# Start server di port berbeda
php artisan serve --port=8080

# Start server dengan IP tertentu
php artisan serve --host=0.0.0.0
```

### Cache & Config Commands

```bash
# Clear all cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# Clear route cache
php artisan route:clear

# Clear everything
php artisan optimize:clear
```

### Model & Migration Commands

```bash
# Make migration file
php artisan make:migration create_bookings_table

# Make model
php artisan make:model Booking

# Make controller
php artisan make:controller BookingController

# Make seeder
php artisan make:seeder RoomSeeder

# Make all at once
php artisan make:model Booking -mcr  # Model, Controller, Resource
```

---

## 🧪 TESTING COMMANDS

### Create Test Data

```bash
# Create test user
php artisan tinker
> App\Models\User::factory()->create(['email' => 'test@example.com', 'password' => bcrypt('password')])

# Create test booking
> App\Models\Booking::create(['user_id' => 1, 'room_id' => 1, 'start_time' => now()->addHours(1), 'end_time' => now()->addHours(2), 'title' => 'Test'])
```

### Run Tests

```bash
# Run all tests
php artisan test

# Run specific test class
php artisan test tests/Feature/BookingTest.php

# Run with coverage
php artisan test --coverage
```

---

## 🔍 DEBUGGING COMMANDS

### Tinker (Interactive Shell)

```bash
php artisan tinker

# List all rooms
> App\Models\Room::all()

# List all bookings for user 1
> App\Models\User::find(1)->bookings

# Check active bookings for room 1
> App\Models\Room::find(1)->getActiveBooking()

# Check room status
> App\Models\Room::find(1)->getCurrentStatus()

# Create a booking in tinker
> App\Models\Booking::create(['user_id' => 1, 'room_id' => 1, 'start_time' => now(), 'end_time' => now()->addHour(), 'title' => 'Meeting'])

# Delete a booking
> App\Models\Booking::find(1)->delete()

# Exit tinker
> exit
```

### Database Commands

```bash
# Check database connection
php artisan db

# Run raw SQL query
php artisan tinker
> DB::select('SELECT * FROM bookings')
```

### Log Viewer (tail logs)

```bash
# Watch application logs
tail -f storage/logs/laravel.log

# On Windows, use:
Get-Content storage/logs/laravel.log -Wait
```

---

## 🔧 MAINTENANCE COMMANDS

### Clear Sessions

```bash
# Create sessions table jika belum ada
php artisan session:table

# Migrate
php artisan migrate

# Clear all sessions
php artisan session:clear
```

### Optimize Application

```bash
# Optimize (cache routes, config, etc)
php artisan optimize

# Clear optimization
php artisan optimize:clear

# Clear & optimize
php artisan optimize:clear && php artisan optimize
```

### Backup & Reset

```bash
# Dump database untuk backup
mysqldump -u root register_db > backup.sql

# Restore database
mysql -u root register_db < backup.sql

# Reset everything
php artisan migrate:fresh --seed
```

---

## 📱 USEFUL SHORTCUTS

### Frequently Used

```bash
# Shortcut untuk migrate + seed
alias dbsetup="php artisan migrate:fresh --seed"

# Shortcut untuk clear cache
alias cache-clear="php artisan optimize:clear"

# Shortcut untuk start server
alias serve="php artisan serve"
```

### Create Usage Example:

```bash
# Add alias to ~/.bashrc (Linux/Mac) or use on Windows cmd
doskey dbsetup=php artisan migrate:fresh --seed
doskey cache-clear=php artisan optimize:clear
doskey serve=php artisan serve
```

---

## 🎯 COMMON WORKFLOWS

### Workflow 1: Setup Baru

```bash
cd c:\Users\MSI\register-app
php artisan migrate:fresh --seed
php artisan serve
# Buka http://localhost:8000
```

### Workflow 2: Fix Migration Error

```bash
php artisan migrate:rollback
# Fix migration file
php artisan migrate
php artisan db:seed
```

### Workflow 3: Reset Database

```bash
php artisan migrate:fresh --seed
# Atau jika ada error:
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

### Workflow 4: Debug Booking Issue

```bash
php artisan tinker
> $room = Room::find(1);
> $room->getActiveBooking();  // Cek booking aktif
> $room->getCurrentStatus();  // Cek status
> DB::select('SELECT * FROM bookings WHERE room_id = 1');  // Raw query
```

### Workflow 5: Create Test Data

```bash
php artisan tinker
> for ($i = 1; $i <= 3; $i++) { User::factory()->create(); }
> for ($i = 1; $i <= 10; $i++) { Booking::factory()->create(); }
```

---

## ⚙️ CONFIGURATION

### .env Variables untuk Booking System

```bash
# Timezone (penting untuk status merah/hijau!)
APP_TIMEZONE=UTC
# Ubah ke: APP_TIMEZONE=Asia/Jakarta

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=register_db
DB_USERNAME=root
DB_PASSWORD=
```

### Edit .env

```bash
# Pakai editor pilihan Anda
nano .env          # Linux/Mac
notepad .env       # Windows
code .env          # VS Code
```

---

## 📊 MONITORING

### Check Server Status

```bash
# Pada terminal lain
curl http://localhost:8000

# Check specific endpoint
curl http://localhost:8000/

# Check API response
curl -X GET http://localhost:8000/bookings -H "Accept: application/json"
```

### Check Database

```bash
# Connect ke MySQL
mysql -u root register_db

# Query bookings
SELECT * FROM bookings;
SELECT * FROM rooms;
SELECT * FROM users;

# Check conflict
SELECT * FROM bookings WHERE room_id = 1 AND start_time < '2026-04-20 15:00';

# Exit
exit
```

---

## 🆘 TROUBLESHOOTING QUICK FIX

### Error: "SQLSTATE[HY000] [2002] No such file or directory"

```bash
# Database not running, start MySQL server
# atau di .env ubah:
DB_SOCKET=/var/run/mysqld/mysqld.sock  # Linux
DB_SOCKET=/tmp/mysql.sock              # Mac
```

### Error: "Class 'Database\\Seeders\\RoomSeeder' not found"

```bash
php artisan db:seed --class=RoomSeeder
# atau fix namespace di DatabaseSeeder.php
```

### Error: "SQLSTATE[42S02]: Table 'register_db.bookings' doesn't exist"

```bash
php artisan migrate
```

### Status tidak berubah merah/hijau

```bash
# Check timezone
echo $APP_TIMEZONE

# Check booking times
php artisan tinker
> App\Models\Booking::find(1)
> now()  # Bandingkan dengan booking time

# Reload browser
```

---

## 📚 HELPFUL LINKS

### Documentation

- Laravel: https://laravel.com/docs
- MySQL: https://dev.mysql.com/doc/
- Blade: https://laravel.com/docs/blade
- Eloquent: https://laravel.com/docs/eloquent

### Commands Help

```bash
php artisan  # List all commands
php artisan help migrate  # Help untuk command tertentu
php artisan list  # List semua commands
```

---

**💡 Tip: Simpan file ini untuk referensi cepat command yang sering digunakan!**

# Booking Ruang Rapat - Quick Start dengan VS Code Terminal

## 1️⃣ BUKA TERMINAL DI VS CODE
- Press: `Ctrl + `` (backtick)` atau `Ctrl + J`
- Atau menu: Terminal → New Terminal

## 2️⃣ PASTIKAN SUDAH DI FOLDER PROJECT
```powershell
cd C:\Users\MSI\register-app
```

## 3️⃣ BUILD ASSETS (CSS/JS)
```powershell
npm run build
```

Tunggu sampai selesai, seharusnya output:
```
✓ 2 modules transformed.
build completed successfully
```

## 4️⃣ JALANKAN LARAVEL SERVER
```powershell
php artisan serve
```

Tunggu sampai muncul:
```
Starting Laravel development server: http://127.0.0.1:8000
```

## 5️⃣ BUKA BROWSER
Klik link atau ketik di browser:
```
http://localhost:8000
```

## 🧪 TESTING

### Scenario 1: Cek Panel Publik (Tidak Login)
- Semua ruang harus 🟢 TERSEDIA (hijau)
- Activity log kosong di kanan

### Scenario 2: Login & Booking
1. Klik "Login" → login dengan:
   ```
   Email: test@example.com
   Password: password
   ```
2. Klik "Booking Saya" di header
3. Klik "+ Buat Booking Baru"
4. Pilih ruang, set waktu SEKARANG (current time)
5. Isi judul & deskripsi
6. Submit

### Scenario 3: Lihat Status Berubah
1. Kembali ke halaman utama (`http://localhost:8000`)
2. Ruang yang dibooking harus BERUBAH JADI 🔴 MERAH
3. Activity log menampilkan booking aktif dengan nama & kegiatan

---

## ⏹️ UNTUK STOP SERVER
Di terminal, press: `Ctrl + C`

## 💡 TIPS
- Terminal auto-refresh page setiap 30 detik
- Kalau ada error, check terminal (akan muncul error message)
- Kalau tetap tidak berubah, coba hard refresh browser: `Ctrl + Shift + R`

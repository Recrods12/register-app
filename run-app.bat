@echo off
chcp 65001 > nul
cd /d C:\Users\MSI\register-app

echo.
echo ╔════════════════════════════════════════╗
echo ║  BOOKING RUANG RAPAT - BUILD & RUN    ║
echo ╚════════════════════════════════════════╝
echo.

echo [1/2] Building assets...
call npm run build

if errorlevel 1 (
    echo.
    echo ❌ Build failed!
    pause
    exit /b 1
)

echo.
echo [2/2] Starting Laravel server...
echo.
echo ✅ Server starting on http://localhost:8000
echo.
echo Press Ctrl+C to stop the server
echo.

call php artisan serve

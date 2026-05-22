@echo off
cd /d c:\Users\MSI\register-app

echo Clearing Laravel cache...
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear

echo.
echo Cache cleared successfully!
echo.
pause

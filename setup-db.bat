@echo off
cd /d c:\Users\MSI\register-app

echo Running migrations...
php artisan migrate

echo.
echo Running seeders...
php artisan db:seed

echo.
echo Done! Database is ready.
pause

@echo off
cd /d C:\Users\MSI\register-app

echo Building assets...
call npm run build

echo.
echo Build complete! Now run:
echo php artisan serve
pause

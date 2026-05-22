@echo off
chcp 65001 >nul
cd /d "%~dp0"
echo.
echo ========================================
echo   LARAVEL DATABASE MIGRATION & SEED
echo ========================================
echo.
php artisan migrate --no-interaction --force
echo.
echo ========================================
echo   RUNNING SEEDERS
echo ========================================
echo.
php artisan db:seed --no-interaction
echo.
echo ========================================
echo   SETUP COMPLETE!
echo ========================================
echo.
pause

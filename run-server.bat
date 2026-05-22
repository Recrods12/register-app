@echo off
chcp 65001 > nul
cd /d c:\Users\MSI\register-app

echo.
echo ============================================
echo STARTING LARAVEL APPLICATION
echo ============================================
echo.

REM Step 1: Build assets
echo [1/4] Building Tailwind CSS assets...
call npm run build > nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo ✗ Build failed, trying again...
    call npm install --legacy-peer-deps > nul 2>&1
    call npm run build > nul 2>&1
)
echo ✓ Assets built successfully

REM Step 2: Clear caches
echo [2/4] Clearing Laravel caches...
php artisan cache:clear > nul 2>&1
php artisan config:clear > nul 2>&1
echo ✓ Caches cleared

REM Step 3: Run migrations
echo [3/4] Running database migrations...
php artisan migrate --force > nul 2>&1
echo ✓ Migrations complete

REM Step 4: Start server
echo [4/4] Starting Laravel development server...
echo.
echo ============================================
echo ✓ LARAVEL SERVER STARTING!
echo ============================================
echo.
echo URL: http://localhost:8000
echo.
echo Tekan Ctrl+C untuk stop server
echo.

php artisan serve

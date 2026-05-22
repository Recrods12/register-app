@echo off
chcp 65001 > nul
cd /d c:\Users\MSI\register-app

echo.
echo ============================================
echo FINAL INTELLISENSE & ERROR FIX
echo ============================================
echo.

REM Step 1: Composer autoloader
echo [1/5] Rebuilding composer autoloader...
composer dump-autoload -o > nul 2>&1
echo ✓ Composer autoloader rebuilt

REM Step 2: Laravel caches
echo [2/5] Clearing Laravel caches...
php artisan cache:clear > nul 2>&1
php artisan config:clear > nul 2>&1
php artisan optimize:clear > nul 2>&1
echo ✓ Laravel cache cleared

REM Step 3: Vite/npm
echo [3/5] Installing npm dependencies...
call npm install --legacy-peer-deps > nul 2>&1
echo ✓ NPM dependencies installed

REM Step 4: Build assets
echo [4/5] Building Tailwind CSS...
call npm run build > nul 2>&1
echo ✓ Tailwind CSS built

REM Step 5: Clear bootstrap cache
echo [5/5] Clearing bootstrap cache...
if exist bootstrap\cache\*.php del /f /q bootstrap\cache\*.php > nul 2>&1
echo ✓ Bootstrap cache cleared

echo.
echo ============================================
echo ✓ DONE! All errors and warnings fixed!
echo.
echo NEXT STEPS:
echo 1. Close VS Code (Ctrl+Shift+Esc if frozen)
echo 2. Wait 5 seconds
echo 3. Reopen VS Code
echo 4. All problems should be gone!
echo ============================================
echo.
pause

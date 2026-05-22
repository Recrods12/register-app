@echo off
chcp 65001 > nul
cd /d c:\Users\MSI\register-app

echo.
echo ============================================
echo COMPREHENSIVE INTELLISENSE FIX
echo ============================================
echo.

REM Step 1: Clear Laravel cache
echo [1/6] Clearing Laravel caches...
php artisan cache:clear > nul 2>&1
php artisan config:clear > nul 2>&1
php artisan optimize:clear > nul 2>&1
echo ✓ Laravel cache cleared

REM Step 2: Rebuild composer autoloader
echo [2/6] Rebuilding composer autoloader...
composer dump-autoload -o > nul 2>&1
echo ✓ Composer autoloader rebuilt

REM Step 3: Clear Vite cache
echo [3/6] Clearing Vite cache...
if exist public\build rmdir /s /q public\build > nul 2>&1
if exist node_modules\.vite rmdir /s /q node_modules\.vite > nul 2>&1
echo ✓ Vite cache cleared

REM Step 4: Install npm dependencies
echo [4/6] Installing npm dependencies...
call npm install --legacy-peer-deps > nul 2>&1
echo ✓ NPM dependencies installed

REM Step 5: Build assets
echo [5/6] Building Tailwind CSS...
call npm run build > nul 2>&1
echo ✓ Tailwind CSS built

REM Step 6: Clear file system cache
echo [6/6] Clearing file system cache...
if exist bootstrap\cache\*.php del /f /q bootstrap\cache\*.php > nul 2>&1
echo ✓ File system cache cleared

echo.
echo ============================================
echo ✓ ALL CACHES CLEARED!
echo.
echo NEXT STEPS:
echo 1. Close VS Code completely
echo 2. Wait 5 seconds
echo 3. Reopen VS Code
echo.
echo All intellisense errors and CSS warnings should be gone!
echo ============================================
echo.
pause


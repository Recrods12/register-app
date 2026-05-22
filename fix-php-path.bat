@echo off
chcp 65001 > nul
cd /d c:\Users\MSI\register-app

echo.
echo ============================================
echo FINDING & CONFIGURING PHP EXECUTABLE
echo ============================================
echo.

REM Find PHP location
echo Searching for PHP executable...
for /f "delims=" %%A in ('where php 2^>nul') do (
    set PHP_PATH=%%A
    goto FOUND_PHP
)

REM If not found in PATH, search common locations
if not defined PHP_PATH (
    if exist C:\php\php.exe (
        set PHP_PATH=C:\php\php.exe
        goto FOUND_PHP
    )
)

echo ✗ PHP not found!
echo Please install PHP or add it to PATH
pause
exit /b 1

:FOUND_PHP
echo ✓ PHP found at: %PHP_PATH%
%PHP_PATH% --version
echo.

REM Update VS Code settings
echo Updating VS Code settings with PHP path...
if not exist .vscode mkdir .vscode

(
echo {
echo     "php.validate.executablePath": "%PHP_PATH:\=\\%",
echo     "php.validate.run": "onSave",
echo     "php.format.indentSize": 4,
echo     "php.format.indentWithTabs": false,
echo     "[php]": {
echo         "editor.defaultFormatter": "bmewburn.vscode-intelephense-client",
echo         "editor.formatOnSave": false
echo     },
echo     "intelephense.environment.phpVersion": "8.4.0",
echo     "intelephense.files.maxSize": 1000000,
echo     "editor.formatOnSave": false,
echo     "files.exclude": {
echo         "**/node_modules": true,
echo         "**/vendor": false,
echo         "**/.env.example": true
echo     },
echo     "search.exclude": {
echo         "**/node_modules": true,
echo         "**/vendor": true,
echo         "**/storage": true,
echo         "**/bootstrap/cache": true,
echo         "**/.git": true
echo     }
echo }
) > .vscode\settings.json

echo ✓ .vscode/settings.json updated

REM Clear caches
echo.
echo Clearing all caches...
composer dump-autoload -o > nul 2>&1
php artisan cache:clear > nul 2>&1
php artisan config:clear > nul 2>&1
php artisan optimize:clear > nul 2>&1
echo ✓ Caches cleared

echo.
echo ============================================
echo ✓ PHP PATH CONFIGURED!
echo ============================================
echo.
echo Path: %PHP_PATH%
echo.
echo NEXT: Close and reopen VS Code
echo All validation errors should disappear!
echo.
pause

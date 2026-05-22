@echo off
cd /d c:\Users\MSI\register-app

REM Create .vscode directory
if not exist .vscode mkdir .vscode

REM Create settings.json
(
echo {
echo     "php.validate.executablePath": "php",
echo     "php.validate.run": "onSave",
echo     "[php]": {
echo         "editor.defaultFormatter": "bmewburn.vscode-intelephense-client",
echo         "editor.formatOnSave": true
echo     },
echo     "intelephense.environment.phpVersion": "8.4.0",
echo     "intelephense.files.maxSize": 1000000,
echo     "editor.formatOnSave": true,
echo     "files.exclude": {
echo         "**/node_modules": true,
echo         "**/vendor": false
echo     },
echo     "search.exclude": {
echo         "**/node_modules": true,
echo         "**/vendor": true,
echo         "**/storage": true,
echo         "**/bootstrap/cache": true
echo     }
echo }
) > .vscode\settings.json

echo ✓ .vscode/settings.json created
echo.
echo Now run: fix-intellisense.bat
pause

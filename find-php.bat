@echo off
echo Finding PHP executable...
echo.

REM Check if php is in PATH
where php > nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo ✓ PHP found in PATH
    for /f "delims=" %%A in ('where php') do (
        echo Location: %%A
        php --version
    )
    goto :END
)

REM Check common locations
echo Checking common PHP locations...
if exist C:\php\php.exe (
    echo ✓ Found at C:\php\php.exe
    C:\php\php.exe --version
    echo Add this to VS Code settings: "C:\php\php.exe"
    goto :END
)

if exist C:\Program Files\php\php.exe (
    echo ✓ Found at C:\Program Files\php\php.exe
    "C:\Program Files\php\php.exe" --version
    echo Add this to VS Code settings: "C:\Program Files\php\php.exe"
    goto :END
)

if exist C:\Program Files ^(x86^)\php\php.exe (
    echo ✓ Found at C:\Program Files ^(x86^)\php\php.exe
    "C:\Program Files (x86)\php\php.exe" --version
    echo Add this to VS Code settings: "C:\Program Files (x86)\php\php.exe"
    goto :END
)

echo.
echo ✗ PHP not found!
echo Please install PHP or configure path manually

:END
echo.
pause

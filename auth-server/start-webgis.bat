@echo off
setlocal
cd /d "%~dp0"
set "PHP_BIN=C:\laragon\bin\php\php-8.3.36-Win32-vs16-x64\php.exe"

if not exist "%PHP_BIN%" (
    echo PHP Laragon not found: %PHP_BIN%
    echo Pastikan Laragon sudah terinstall dan folder PHP ada.
    pause
    exit /b 1
)

"%PHP_BIN%" artisan serve --host=127.0.0.1 --port=8000

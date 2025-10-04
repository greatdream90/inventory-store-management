@echo off
echo Starting Inventory Store Backend Server...
echo.
echo Backend will be available at:
echo - Local: http://localhost:8000
echo - Network: http://YOUR_IP_ADDRESS:8000
echo.
echo Press Ctrl+C to stop the server
echo.

cd /d "%~dp0"
php -S 0.0.0.0:8000 server.php
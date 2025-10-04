# Inventory Store Backend Server Launcher
Write-Host "=== Inventory Store Backend Server ===" -ForegroundColor Green
Write-Host ""

# Get local IP address
$LocalIP = (Get-NetIPAddress -AddressFamily IPv4 | Where-Object {$_.InterfaceAlias -notlike "*Loopback*" -and $_.InterfaceAlias -notlike "*VirtualBox*"} | Select-Object -First 1).IPAddress

Write-Host "Backend will be available at:" -ForegroundColor Yellow
Write-Host "- Local:   http://localhost:8000" -ForegroundColor Cyan
Write-Host "- Network: http://$LocalIP:8000" -ForegroundColor Cyan
Write-Host ""
Write-Host "Use the Network address for your online frontend!" -ForegroundColor Green
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Red
Write-Host ""

# Navigate to script directory
Set-Location $PSScriptRoot

# Start PHP server
php -S 0.0.0.0:8000 server.php
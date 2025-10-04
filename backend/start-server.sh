#!/bin/bash
echo "Starting Inventory Store Backend Server..."
echo ""
echo "Backend will be available at:"
echo "- Local: http://localhost:8000"
echo "- Network: http://$(hostname -I | awk '{print $1}'):8000"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

cd "$(dirname "$0")"
php -S 0.0.0.0:8000 server.php
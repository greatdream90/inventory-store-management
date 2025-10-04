# Frontend Online + Local Backend Setup

## 🎯 Overview
This setup allows you to:
- Deploy frontend to online hosting (Vercel/Netlify) 
- Keep backend running on your local machine
- Access your app from anywhere while keeping data local

## ⚙️ Setup Instructions

### 1. Start Local Backend Server

#### Windows:
```bash
# Run one of these commands in backend directory:
.\start-server.bat
# or
.\start-server.ps1
# or manually:
php -S 0.0.0.0:8000 server.php
```

#### Linux/Mac:
```bash
chmod +x start-server.sh
./start-server.sh
```

### 2. Get Your Network IP
Your backend will be available at:
- **Local**: http://localhost:8000
- **Network**: http://YOUR_IP_ADDRESS:8000

**Current IP**: http://26.155.188.255:8000

### 3. Deploy Frontend Online

#### Option A: Vercel (Recommended)
1. Push code to GitHub
2. Connect Vercel to your repository
3. Set these environment variables:
   ```
   VITE_API_URL=http://26.155.188.255:8000
   ```
4. Deploy!

#### Option B: Netlify
1. Build locally: `npm run build`
2. Upload `dist/` folder to Netlify
3. Set environment variables in Netlify dashboard

### 4. Router Configuration (Important!)
Make sure your hosting platform handles SPA routing:

**Vercel**: Create `vercel.json`:
```json
{
  "rewrites": [
    { "source": "/(.*)", "destination": "/index.html" }
  ]
}
```

**Netlify**: Create `_redirects` in `public/`:
```
/*    /index.html   200
```

## 🔒 Security Considerations

### Port Forwarding (If needed)
If you want to access from outside your network:
1. Open router admin panel
2. Forward port 8000 to your computer's IP
3. Use your public IP instead of local IP

### Firewall
Make sure Windows Firewall allows PHP on port 8000:
```powershell
# Run as Administrator
netsh advfirewall firewall add rule name="PHP Server" dir=in action=allow protocol=TCP localport=8000
```

## 🚀 Benefits
- ✅ Free frontend hosting
- ✅ Keep sensitive data local
- ✅ Easy development and testing
- ✅ No database hosting costs

## ⚠️ Limitations
- Backend must be running when someone uses the app
- Your computer needs stable internet connection
- Limited to your home network (unless port forwarded)

## 🔧 Troubleshooting

### CORS Issues
If you get CORS errors, add your frontend domain to `server.php`:
```php
$allowedOrigins = [
    'https://your-app.vercel.app',  // Add your actual domain
    // ... other origins
];
```

### Connection Issues
1. Check if backend server is running: http://localhost:8000/health
2. Verify your IP address hasn't changed
3. Check firewall settings
4. Test network connectivity: `ping 26.155.188.255`
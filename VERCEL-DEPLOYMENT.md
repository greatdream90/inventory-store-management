# 🚀 Vercel Deployment Guide

## Step-by-Step Deployment Instructions

### Prerequisites
1. ✅ Install Git: https://git-scm.com/download/win
2. ✅ Create GitHub account: https://github.com
3. ✅ Create Vercel account: https://vercel.com

### Step 1: Setup Git Repository

```bash
# 1. Open Command Prompt or PowerShell
cd C:\xampp\htdocs\inventoty_store_v1

# 2. Initialize Git repository
git init

# 3. Add all files
git add .

# 4. Create initial commit
git commit -m "Initial commit: Inventory Store Management System"
```

### Step 2: Push to GitHub

```bash
# 1. Create new repository on GitHub
#    - Go to https://github.com/new
#    - Name: "inventory-store-management"
#    - Select "Public" or "Private"
#    - DO NOT initialize with README (we already have files)

# 2. Connect local repo to GitHub
git remote add origin https://github.com/YOUR_USERNAME/inventory-store-management.git

# 3. Push code
git branch -M main
git push -u origin main
```

### Step 3: Deploy on Vercel

1. **Go to Vercel**: https://vercel.com
2. **Sign up** with your GitHub account
3. **Import Project**:
   - Click "New Project"
   - Select your GitHub repository
   - Click "Import"

4. **Project Settings**:
   ```
   Framework Preset: Vite
   Root Directory: frontend
   Build Command: npm run build
   Output Directory: dist
   Install Command: npm install
   ```

5. **Environment Variables**:
   ```
   VITE_API_URL = http://26.155.188.255:8000
   ```

6. **Deploy**: Click "Deploy"

### Step 4: Start Your Backend

Before testing, make sure your backend is running:

```bash
# Navigate to backend directory
cd C:\xampp\htdocs\inventoty_store_v1\backend

# Start server (accessible from network)
php -S 0.0.0.0:8000 server.php
```

### Step 5: Test Your Application

1. ✅ **Backend Health Check**: http://26.155.188.255:8000/health
2. ✅ **Vercel URL**: Your app will be available at `https://your-app.vercel.app`
3. ✅ **Login Test**: Use these credentials:
   - Admin: `admin@demo.com` / `admin123`
   - Staff: `staff@demo.com` / `staff123`
   - Viewer: `viewer@demo.com` / `viewer123`

## 🔧 Troubleshooting

### If CORS Error Occurs:
Your backend `server.php` already includes CORS headers for Vercel domains.

### If Backend Not Accessible:
1. Check if server is running: http://localhost:8000/health
2. Check Windows Firewall settings
3. Verify your IP address: `ipconfig | findstr "IPv4"`

### If Build Fails on Vercel:
- Make sure `package.json` has correct scripts
- Check if all dependencies are listed
- Verify `vercel.json` configuration

## 🎯 Final URLs

- **Frontend (Vercel)**: https://your-app.vercel.app
- **Backend (Local)**: http://26.155.188.255:8000
- **Health Check**: http://26.155.188.255:8000/health

## 💡 Tips

1. **Custom Domain**: You can add your own domain in Vercel settings
2. **Auto Deploy**: Any push to GitHub will auto-deploy to Vercel
3. **Environment Variables**: Update VITE_API_URL if your IP changes
4. **SSL**: Vercel provides HTTPS automatically

## 📱 Mobile Access

Your app will work on mobile devices as long as:
- Your computer is on the same network
- Backend server is running
- Mobile device can reach your computer's IP

---

**Next Steps**: After deployment, test thoroughly and consider setting up port forwarding for external access!
# Railway.app Deployment Guide

## Why Railway?
- ✅ รองรับ PHP + Laravel
- ✅ MySQL database built-in  
- ✅ GitHub integration
- ✅ Auto-deploy จาก GitHub
- ✅ Free tier available
- ✅ Easy configuration

## Setup สำหรับ Database Only:

1. **สมัคร Railway.app**
   - ไปที่ https://railway.app/
   - Login ด้วย GitHub account

2. **Create MySQL Database Only**
   - Create New Project
   - เลือก "Provision MySQL"
   - Railway จะสร้าง MySQL instance ให้

3. **Get Connection Details**
   - ใน MySQL service dashboard
   - ไปที่ Variables tab
   - Copy connection details

4. **Set ใน Netlify Environment Variables**
   ```env
   DB_HOST=containers-us-west-xxx.railway.app
   DB_PORT=6543
   DB_DATABASE=railway  
   DB_USERNAME=root
   DB_PASSWORD=generated-password
   ```

5. **Deploy Settings**
   - Root Directory: backend
   - Build Command: composer install --no-dev
   - Start Command: php -S 0.0.0.0:$PORT index.php
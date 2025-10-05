# Railway.app Deployment Guide

## Why Railway?
- ✅ รองรับ PHP + Laravel
- ✅ MySQL database built-in  
- ✅ GitHub integration
- ✅ Auto-deploy จาก GitHub
- ✅ Free tier available
- ✅ Easy configuration

## Setup Steps:

1. **สมัคร Railway.app**
   - ไปที่ https://railway.app/
   - Login ด้วย GitHub account

2. **Create New Project**
   - เลือก "Deploy from GitHub repo"
   - เลือก inventory-store-management repo
   - เลือก backend folder

3. **Add MySQL Database**
   - ในโปรเจค คลิก "+ New"
   - เลือก "Database" > "MySQL"
   - Railway จะสร้าง MySQL instance ให้

4. **Configure Environment Variables**
   ```env
   DB_HOST=${{MySQL.MYSQL_HOST}}
   DB_PORT=${{MySQL.MYSQL_PORT}}
   DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
   DB_USERNAME=${{MySQL.MYSQL_USERNAME}}
   DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}
   ```

5. **Deploy Settings**
   - Root Directory: backend
   - Build Command: composer install --no-dev
   - Start Command: php -S 0.0.0.0:$PORT index.php
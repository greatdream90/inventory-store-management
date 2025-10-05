# PlanetScale Database Setup

## Why PlanetScale?
- ✅ MySQL compatible
- ✅ Serverless & auto-scaling  
- ✅ Free tier 5GB
- ✅ No connection limits
- ✅ Built-in branching

## Setup Steps:

1. **สมัคร PlanetScale**
   - ไปที่ https://planetscale.com/
   - สร้าง account

2. **Create Database**
   - Create database: inventory-store
   - Region: แนะนำเลือก US East (closest to Netlify)

3. **Get Connection String**
   ```
   Settings > Passwords > New Password
   Copy connection string format: mysql://...
   ```

4. **Set Netlify Environment Variables**
   ```
   DB_HOST=aws.connect.psdb.cloud
   DB_USER=your-username
   DB_PASSWORD=your-password
   DB_DATABASE=inventory-store
   DB_PORT=3306
   ```

5. **Import Data**
   - Use PlanetScale CLI หรือ import via MySQL client
   - รัน create_tables.sql
   - รัน setup_production_db.php
# Deployment Guide - Shared Hosting

## For Backend (000webhost, InfinityFree, etc.)

1. **Upload Files:**
   - Upload all files from `backend/` folder to `public_html/api/`
   - Rename `production.php` to `index.php`

2. **Database Setup:**
   - Create MySQL database via hosting control panel
   - Import `create_tables.sql`
   - Update database credentials in code

3. **File Structure:**
   ```
   public_html/
   ├── api/
   │   ├── index.php (renamed from production.php)
   │   ├── server.php
   │   ├── create_tables.sql
   │   └── ...other files
   └── index.html (frontend files)
   ```

## For Frontend (Netlify, Vercel)

1. **Build Project:**
   ```bash
   cd frontend
   npm run build
   ```

2. **Upload dist/ folder** or connect GitHub for auto-deploy

3. **Configure redirects** (for SPA routing):
   Create `_redirects` file in `dist/`:
   ```
   /*    /index.html   200
   ```

## Environment Variables

### Backend (.env):
```
DB_HOST=your_database_host
DB_NAME=your_database_name  
DB_USER=your_database_user
DB_PASS=your_database_password
```

### Frontend:
Update `vite.config.js` with production API URL:
```js
VITE_API_URL=https://yourdomain.com/api
```
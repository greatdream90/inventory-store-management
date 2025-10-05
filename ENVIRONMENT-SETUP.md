# Environment Configuration Documentation

## สรุปการทำงาน

✅ **เสร็จแล้ว!** ระบบ Inventory Store Management ตอนนี้รองรับทั้ง 2 environment แล้ว:

### 1. Environment Configuration (app.js)
- **Auto-detection**: ตรวจจับ environment อัตโนมัติ
- **Development**: `localhost` - ใช้ Vite proxy หรือ direct API
- **Production**: Netlify - ใช้ Netlify Functions
- **Demo Mode**: Frontend-only mode สำหรับการ present

### 2. Demo Mode Features
- ✅ **Mock API Service**: จำลอง backend ทั้งหมดใน frontend
- ✅ **Dummy Users**: 3 บัญชีทดสอบ (Admin, Staff, Viewer)
- ✅ **Mock Data**: Categories, Products, Sales พร้อมใช้งาน
- ✅ **No Backend Required**: ทำงานได้โดยไม่ต้องมี database

### 3. การใช้งาน

#### สำหรับการ Present (Demo Mode):
```bash
# Set environment variable
export VITE_DEMO_MODE=true
# หรือจะเป็น localhost อัตโนมัติ

# Start frontend only
npm run dev
```

#### สำหรับ Development:
```bash
# Start backend (localhost)
cd backend && php -S localhost:8000 index.php

# Start frontend
cd frontend && npm run dev
```

#### สำหรับ Production:
- Deploy ไป Netlify อัตโนมัติ
- ใช้ Netlify Functions สำหรับ backend

### 4. Demo Accounts
1. **Admin Demo**: admin@demo.com / admin123
2. **Staff Demo**: staff@demo.com / staff123  
3. **Viewer Demo**: viewer@demo.com / viewer123

### 5. Auto Environment Detection
- `localhost` → Demo Mode (Mock API)
- `netlify.app` → Production (Netlify Functions)
- Manual override ด้วย `VITE_DEMO_MODE=true`

## การใช้งานปัจจุบัน
🎉 **Ready to Present!** สามารถใช้ Demo Mode ได้เลยโดยไม่ต้องเซ็ตอัปอะไรเพิ่มเติม

การแยก environment และ demo mode เสร็จสมบูรณ์แล้วครับ!
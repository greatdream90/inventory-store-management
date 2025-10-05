# 🎉 Demo Mode Instructions

## ✅ **Setup เสร็จแล้ว!** - ไม่ต้องใช้ Backend

ตอนนี้ระบบทำงานใน **Demo Mode** แล้ว ไม่ต้องเริ่ม backend server เลย!

### 🚀 การใช้งาน

1. **เริ่มระบบ**: `npm run dev` (frontend เท่านั้น)
2. **เปิดเว็บไซต์**: http://localhost:3001 
3. **กดปุ่ม Demo**: เลือกบัญชีที่ต้องการ

### 👥 Demo Accounts

- **🔑 Admin Demo**: admin@demo.com / admin123
- **📋 Staff Demo**: staff@demo.com / staff123  
- **👀 Viewer Demo**: viewer@demo.com / viewer123

### 🎯 Features ที่ใช้งานได้

✅ **Login System** - ทั้ง 3 roles  
✅ **Dashboard** - แสดงข้อมูลสถิติ  
✅ **Products** - จัดการสินค้า  
✅ **Categories** - จัดการหมวดหมู่  
✅ **Sales/POS** - ระบบขาย  
✅ **Reports** - รายงาน  
✅ **All UI Components** - ใช้งานได้ทั้งหมด  

### 🔧 การเปลี่ยนกลับไปใช้ Backend

หากต้องการใช้ backend จริง:

1. แก้ไข `.env.local`:
```env
VITE_DEMO_MODE=false
```

2. เริ่ม backend server:
```bash
cd backend && php -S localhost:8000 index.php
```

3. Restart frontend:
```bash
npm run dev
```

### 📝 หมายเหตุ

- **Demo Mode**: ข้อมูลไม่ถูกบันทึกจริง (frontend-only)
- **Production**: ใช้ Netlify Functions 
- **Development**: ใช้ PHP backend (เมื่อปิด demo mode)

---
🎊 **Ready for Presentation!** 🎊
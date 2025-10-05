# 🎯 Demo Mode Setup - ไม่ต้องใช้ Backend! 

## ✅ การใช้งาน Demo Mode (แนะนำ)

ระบบถูกตั้งค่าให้ทำงานใน **Demo Mode** เป็นหลัก ไม่ต้องใช้ Backend หรือ Database เลย!

### 🚀 วิธีเริ่มใช้งาน:

```bash
# เริ่มแอพใน Demo Mode (ไม่ต้อง backend)
cd frontend
npm run dev
```

**หรือ**

```bash 
# บังคับ Demo Mode
npm run demo
```

### 🎭 Demo Accounts พร้อมใช้งาน:

1. **Admin Demo**: admin@demo.com / admin123
2. **Staff Demo**: staff@demo.com / staff123  
3. **Viewer Demo**: viewer@demo.com / viewer123

### 📋 Features ที่ใช้งานได้ใน Demo Mode:

- ✅ **Login System**: ระบบเข้าสู่ระบบ 3 บทบาท
- ✅ **Dashboard**: หน้าแดชบอร์ดพร้อมข้อมูล
- ✅ **Products Management**: จัดการสินค้า (Mock Data)
- ✅ **Categories**: หมวดหมู่สินค้า  
- ✅ **Sales History**: ประวัติการขาย
- ✅ **Reports**: รายงานต่างๆ
- ✅ **User Management**: จัดการผู้ใช้

---

## 🔧 หากต้องการใช้ Backend จริง:

```bash
# เปิดใช้งาน Backend Mode
npm run backend

# หรือแก้ไข .env.local
VITE_DEMO_MODE=false
```

**แล้วเริ่ม Backend:**
```bash
cd ../backend  
php -S localhost:8000 index.php
```

---

## 🎉 สรุป:

**Demo Mode เหมาะสำหรับ:**
- 🎯 การนำเสนอ (Presentation)
- 🧪 การทดสอบ UI/UX  
- 📱 การแสดงฟีเจอร์
- 🚀 การใช้งานทันทีโดยไม่ต้องเซ็ตอัพอะไร

**Backend Mode เหมาะสำหรับ:**
- 💾 การเก็บข้อมูลจริง
- 🔐 ระบบรักษาความปลอดภัย
- 📊 การทำงานกับฐานข้อมูล
# 🏪 Inventory Store Management System

ระบบจัดการคลังสินค้าแบบครบครัน พัฒนาด้วย Laravel API + Vue.js Frontend

## 🚀 คุณสมบัติหลัก

### 🔹 ระบบสินค้า (Product Management)
- เพิ่ม/แก้ไข/ลบสินค้า
- จัดการหมวดหมู่สินค้า  
- ระบบ SKU และ Barcode
- อัปโหลดรูปภาพสินค้า
- กำหนดราคาขายและต้นทุน
- ตั้งค่าสต๊อกขั้นต่ำ

### 🔹 ระบบสต๊อก (Inventory Management)  
- ตัดสต๊อกอัตโนมัติเมื่อขาย
- ปรับยอดสต๊อกด้วยตนเอง
- ดูประวัติการเคลื่อนไหวสต๊อก
- แจ้งเตือนสินค้าเหลือน้อย

### 🔹 ระบบขาย (POS System)
- หน้าจอขายสินค้า (Point of Sale)
- สแกนบาร์โค้ด
- คำนวณภาษีและส่วนลด
- รองรับการชำระหลายรูปแบบ
- ออกใบเสร็จ/ใบกำกับภาษี

### 🔹 ระบบลูกค้า (Customer Management)
- ข้อมูลลูกค้า
- ประวัติการซื้อ
- ระบบคะแนนสะสม
- ลูกค้า VIP/ขายส่ง

### 🔹 ระบบผู้ใช้ (User Management)
- บทบาท: Admin / Staff / Viewer
- จัดการสิทธิ์การเข้าถึง
- ระบบ Login ด้วย JWT

### 🔹 ระบบรายงาน (Reports)
- ยอดขายรายวัน/เดือน/ปี
- สินค้าขายดี
- สินค้าคงเหลือ
- รายงานกำไร-ขาดทุน

### 🔹 ระบบแจ้งเตือน
- แจ้งเตือนสต๊อกน้อย
- การแจ้งเตือนผ่าน Web
- อีเมลแจ้งเตือน (อนาคต)

## 🛠️ เทคโนโลยีที่ใช้

### Backend (Laravel API)
- **Framework**: Laravel 10
- **Database**: MySQL
- **Authentication**: JWT (tymon/jwt-auth)
- **API**: RESTful API

### Frontend (Vue.js)
- **Framework**: Vue 3 + Composition API
- **State Management**: Pinia
- **Router**: Vue Router
- **UI Framework**: Bootstrap 5
- **Notifications**: SweetAlert2 + Vue-Toastification
- **HTTP Client**: Axios
- **Build Tool**: Vite

### Database
- **Primary**: MySQL
- **Tables**: users, products, categories, customers, sales, sale_items, inventory_transactions, notifications, settings

## 📋 ความต้องการระบบ

### Backend Requirements
- PHP 8.1+
- MySQL 5.7+ หรือ MariaDB 10.3+
- Composer
- Apache/Nginx

### Frontend Requirements  
- Node.js 16+
- npm หรือ yarn

## ⚡ การติดตั้ง

### 1. ติดตั้ง Backend (Laravel)

```bash
# เข้าไปในโฟลเดอร์ backend
cd backend

# ติดตั้ง dependencies ด้วย Composer
composer install

# คัดลอกและแก้ไขไฟล์ .env
cp .env.example .env

# สร้าง Application Key
php artisan key:generate

# สร้าง JWT Secret
php artisan jwt:secret

# แก้ไขการตั้งค่าฐานข้อมูลใน .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_store
DB_USERNAME=root
DB_PASSWORD=

# สร้างฐานข้อมูล
php artisan migrate

# สร้างข้อมูลตัวอย่าง (ถ้ามี)
php artisan db:seed

# เริ่มต้น Laravel Development Server
php artisan serve
```

### 2. ติดตั้ง Frontend (Vue.js)

```bash
# เข้าไปในโฟลเดอร์ frontend
cd frontend

# ติดตั้ง dependencies ด้วย npm
npm install

# เริ่มต้น Development Server
npm run dev
```

## 🔐 บัญชีทดสอบ

| บทบาท | อีเมล | รหัสผ่าน |
|--------|-------|----------|
| Admin | admin@demo.com | password |
| Staff | staff@demo.com | password |  
| Viewer | viewer@demo.com | password |

## 📱 หน้าจอและการใช้งาน

### หน้า Login
- ออกแบบสวยงาม responsive
- รองรับการจดจำการเข้าสู่ระบบ
- มีบัญชี demo สำหรับทดสอบ

### หน้า Dashboard  
- แสดงสถิติภาพรวม
- การขายล่าสุด
- สินค้าเหลือน้อย
- การดำเนินการด่วน

### หน้าจัดการสินค้า
- เพิ่ม/แก้ไข/ลบสินค้า
- จัดการหมวดหมู่
- อัปโหลดรูปภาพ
- จัดการสต๊อก

### หน้า POS (ขายสินค้า)
- เลือกสินค้าเพื่อขาย  
- คำนวณราคาและภาษี
- เลือกลูกค้า
- ออกใบเสร็จ

### หน้ารายงาน
- ยอดขายตามช่วงเวลา
- สินค้าขายดี
- สถิติต่างๆ

## 🔧 การปรับแต่ง

### การตั้งค่าระบบ
- ข้อมูลร้านค้า
- อัตราภาษี
- หน่วยสินค้า
- การแจ้งเตือน

### การสำรองข้อมูล
```bash
# สำรองฐานข้อมูล
mysqldump -u root -p inventory_store > backup.sql

# คืนค่าฐานข้อมูล  
mysql -u root -p inventory_store < backup.sql
```

## 🚀 การพัฒนาต่อ

### Features ที่วางแผนไว้
- [ ] ระบบ Import/Export Excel
- [ ] QR Code Payment
- [ ] Line Notify
- [ ] ระบบซื้อสินค้า (Purchase Order)
- [ ] รายงานแบบ PDF
- [ ] Multi-store Support
- [ ] Mobile App

### การ Deploy Production
1. ตั้งค่า Web Server (Apache/Nginx)
2. ติดตั้ง SSL Certificate
3. ตั้งค่า Environment Variables
4. Build Frontend สำหรับ Production
5. ตั้งค่า Cron Jobs สำหรับ Tasks

## 🤝 การสนับสนุน

หากพบปัญหาหรือต้องการความช่วยเหลือ:
- ติดต่อเจ้าของลิขสิทธิ์โดยตรง
- การใช้งานต้องได้รับอนุญาตจาก GReaTzA เท่านั้น

## 📄 ลิขสิทธิ์ (License)

**⚠️ ซอฟต์แวร์นี้เป็นลิขสิทธิ์เฉพาะ (Proprietary License)**

Copyright © 2025 **GReaTzA** สงวนสิทธิ์ทุกประการ

**ข้อจำกัดการใช้งาน:**
- 🚫 ห้ามคัดลอก ทำซ้ำ หรือจำหน่าย
- 🚫 ห้ามดัดแปลง หรือสร้างผลงานดัดแปลง  
- 🚫 ห้ามแจกจ่าย หรือให้เช่าต่อ
- 🚫 ห้ามใช้งานเชิงพาณิชย์โดยไม่ได้รับอนุญาต

**การใช้งานโดยไม่ได้รับอนุญาตถือเป็นการละเมิดลิขสิทธิ์**

สำหรับการขอใบอนุญาต กรุณาติดต่อ: **GReaTzA**

## 🙏 Third-Party Acknowledgments

ซอฟต์แวร์นี้ใช้ open source libraries และ frameworks หลายตัว เราขอขอบคุณและให้เครดิตแก่นักพัฒนาเหล่านั้น:

- **Vue.js** (MIT) - Frontend Framework
- **Laravel** (MIT) - Backend Framework  
- **Bootstrap** (MIT) - CSS Framework
- **PHP** (PHP License) - Programming Language
- **MySQL/MariaDB** (GPL/Commercial) - Database
- **Node.js** (MIT) - JavaScript Runtime

📋 **รายละเอียดครบถ้วน:** ดูไฟล์ [THIRD-PARTY-LICENSES.md](THIRD-PARTY-LICENSES.md)

**หมายเหตุ:** การใช้ open source libraries เหล่านี้ไม่ได้ส่งผลต่อลิขสิทธิ์เฉพาะของโค้ดหลักของเรา

---

**Inventory Store Management System** - ระบบจัดการคลังสินค้าที่ครบครันและใช้งานง่าย  
**พัฒนาโดย GReaTzA** | **ลิขสิทธิ์เฉพาะบุคคล**
<p align="center">
  <img src="https://raw.githubusercontent.com/Faris4166/Simple-Checklist-Application-in-Python/refs/heads/main/BG.jpg" width="600" />
</p>

# TalkFan 🚀

[TH] โปรเจกต์เว็บบอร์ด (Web Board) สำหรับการตั้งกระทู้ พูดคุย แสดงความคิดเห็น และแบ่งปันเรื่องราวต่างๆ ถูกออกแบบมาให้ใช้งานง่าย รวดเร็ว และมีหน้าตา (UI) ที่สวยงามทันสมัย
[EN] A Web Board project for creating threads, discussing, commenting, and sharing stories. Designed to be easy to use, fast, and features a modern, beautiful UI.

---

## 🌟 จุดเด่นและความง่ายในการใช้งาน (Features & Ease of Use)

### [TH] สำหรับผู้ใช้งานทั่วไป (For General Users)

- **ใช้งานง่าย สบายตา**: ดีไซน์ผ่าน Tailwind CSS และ DaisyUI ทำให้ปุ่มกด การ์ดบทความ และหน้าต่างแจ้งเตือนต่างๆ โดดเด่นและเข้าใจง่ายในทันที
- **รวดเร็วทันใจ ไร้รอยต่อ**: นำเทคโนโลยี AJAX มาใช้ เวลาคอมเมนต์ โพสต์ หรือลบกระทู้ หน้าเว็บจะไม่ต้องรีโหลดใหม่ ทำให้การใช้งานลื่นไหลและไม่ขาดตอน
- **รองรับทุกหน้าจอ (Responsive)**: ไม่ว่าจะเปิดด้วยคอมพิวเตอร์, แท็บเล็ต, หรือโทรศัพท์มือถือ หน้าเว็บจะปรับขนาดให้เข้ากับอุปกรณ์นั้นๆ อัตโนมัติ

### [EN] For General Users

- **Intuitive & Eye-Catching**: Designed with Tailwind CSS and DaisyUI, elements like buttons, cards, and modal pop-ups are visually appealing and instantly understandable.
- **Fast & Seamless**: Utilizing AJAX technology, actions like commenting, posting, or deleting threads don't require full page reloads, ensuring a smooth and uninterrupted experience.
- **Fully Responsive**: Whether accessed via desktop, tablet, or smartphone, the website automatically adjusts its layout to perfectly fit the device's screen.

---

## 🛠️ เครื่องมือและเทคโนโลยีที่ใช้ (Tech Stack)

### Frontend (ส่วนแสดงผลฝั่งผู้ใช้)

- **HTML5 / CSS3**: โครงสร้างและการตกแต่งเว็บพื้นฐาน (Basic web structure and styling)
- **Tailwind CSS**: Utility-first CSS Framework ช่วยให้เขียน UI ได้อย่างรวดเร็ว (Allows rapid UI development)
- **DaisyUI**: UI Components สำหรับ Tailwind CSS ช่วยให้สร้างส่วนประกอบของเว็บสวยงามแบบมืออาชีพ (Provides professional-looking web components)
- **jQuery / AJAX**: จัดการการโต้ตอบแบบเรียลไทม์ (Handles real-time user interactivity without page reloads)

### Backend (ส่วนจัดการระบบหลังบ้าน)

- **PHP (Vanilla PHP)**: จัดการลอจิกฝั่งเซิร์ฟเวอร์ เช่น ระบบล็อกอิน และอัปโหลดไฟล์ (Handles server-side logic like login and file uploads)
- **MySQL / MariaDB**: ระบบฐานข้อมูลสำหรับจัดเก็บสมาชิก, กระทู้, และคอมเมนต์ (Database system for users, posts, and comments)

### Tool (เครื่องมือพัฒนา)

- **XAMPP**: โปรแกรมจำลองรัน Web Server (Apache) และ Database Server ไว้บนเครื่องคอมพิวเตอร์ (Local server environment for development)

---

## ⚙️ วิธีการติดตั้งและใช้งาน (Installation & Setup)

### [TH] ขั้นตอนการติดตั้งง่ายๆ

1. **เตรียมโปรแกรม**: ตรวจสอบว่าในเครื่องมี XAMPP หรือซอฟต์แวร์จำลอง Web Server พร้อมใช้งาน
2. **วางโฟลเดอร์**: คัดลอกโฟลเดอร์โปรเจกต์ `Fanclub` ไปวางในโฟลเดอร์ Root ของเซิร์ฟเวอร์ (เช่น `C:\xampp\htdocs\Fanclub`)
3. **เตรียมฐานข้อมูล**:
   - เปิด XAMPP กด Start ที่ Apache และ MySQL
   - เข้าเว็บเบราว์เซอร์ไปที่ `http://localhost/phpmyadmin/`
   - สร้างฐานข้อมูลชื่อ `fanclub_db` เลือก Collation เป็น `utf8mb4_general_ci`
   - Import ไฟล์ `database.sql` ที่แถมมาในโปรเจกต์เข้าไป
4. **ตั้งค่า (ถ้าจำเป็น)**: ไปตรวจสอบไฟล์ `config/db.php` เพื่อให้มั่นใจว่ารหัสผ่านและชื่อฐานข้อมูลตรงกับเครื่องของคุณ
5. **เริ่มใช้งาน**: เปิดเบราว์เซอร์ เข้าไปที่ `http://localhost/Fanclub` เป็นอันเสร็จสิ้น!

### [EN] Simple Setup Guide

1. **Prerequisites**: Ensure you have XAMPP or a similar local web server software installed.
2. **Place Files**: Copy or clone the `Fanclub` project folder into your server's root directory (e.g., `C:\xampp\htdocs\Fanclub` for XAMPP).
3. **Database Setup**:
   - Open XAMPP Control Panel, Start Apache and MySQL.
   - Go to `http://localhost/phpmyadmin/` in your browser.
   - Create a new database named `fanclub_db` with `utf8mb4_general_ci` collation.
   - Import the provided `database.sql` file to format your tables automatically.
4. **Configuration (If needed)**: Check `config/db.php` to ensure your database credentials match your local setup.
5. **Launch**: Open your web browser and navigate to `http://localhost/Fanclub`. You are ready to go!

   ---

<div align="center">
  <img src = "https://safebooru.org//images/4142/04ed0afd739e7685e3a5b40d2d27e7ef.gif?6506570">
</div>


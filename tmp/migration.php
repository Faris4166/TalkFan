<?php
// [EN] Include database connection
// [TH] ดึงไฟล์เชื่อมต่อฐานข้อมูล
require_once '../config/db.php';

// [EN] SQL query to add 'last_active' column to users table for tracking online status
// [TH] คำสั่ง SQL สำหรับเพิ่มคอลัมน์ 'last_active' ในตาราง users เพื่อใช้จับเวลาออนไลน์ล่าสุด
$sql = "ALTER TABLE users ADD COLUMN last_active TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";

// [EN] Execute query and return result
// [TH] สั่งรันคำสั่ง SQL และแสดงผลลัพธ์
if ($conn->query($sql)) {
    echo "Successfully added last_active column.";
} else {
    echo "Error adding column: " . $conn->error;
}
?>
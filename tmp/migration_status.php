<?php
// [EN] Include database connection
// [TH] ดึงไฟล์เชื่อมต่อฐานข้อมูล
require_once '../config/db.php';

// [EN] SQL query to add 'status' column to posts table to support draft/published states
// [TH] คำสั่ง SQL สำหรับเพิ่มคอลัมน์ 'status' ลงในตาราง posts เพื่อแยกกระทู้ที่เผยแพร่แล้วกับแบบร่าง
$sql = "ALTER TABLE posts ADD COLUMN status VARCHAR(20) DEFAULT 'published'";

// [EN] Execute query and return result
// [TH] สั่งรันคำสั่ง SQL และแสดงผลลัพธ์
if ($conn->query($sql)) {
    echo "Successfully added status column to posts table.";
} else {
    echo "Error adding column: " . $conn->error;
}
?>
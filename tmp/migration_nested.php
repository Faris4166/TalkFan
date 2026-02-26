<?php
// [EN] Include database connection
// [TH] ดึงไฟล์เชื่อมต่อฐานข้อมูล
require_once '../config/db.php';

// [EN] SQL query to add 'parent_id' column to comments table to support nested replies
// [TH] คำสั่ง SQL สำหรับเพิ่มคอลัมน์ 'parent_id' เข้าตาราง comments เพื่อรองรับการตอบกลับคอมเมนต์
$sql = "ALTER TABLE comments ADD COLUMN parent_id INT DEFAULT NULL, ADD FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE";

// [EN] Execute query and return result
// [TH] สั่งรันคำสั่ง SQL และแสดงผลลัพธ์
if ($conn->query($sql)) {
    echo "Successfully updated comments table for nested replies.";
} else {
    echo "Error updating table: " . $conn->error;
}
?>
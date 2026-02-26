<?php
// [EN] Database connection configurations
// [TH] ตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$user = 'root';
// [EN] Default XAMPP password is empty
// [TH] รหัสผ่านเริ่มต้นของ XAMPP จะเป็นค่าว่าง
$pass = '';
$dbname = 'fanclub_db';

// [EN] Enable strict error reporting for MySQLi to easily catch errors
// [TH] เปิดโหมดแสดงข้อผิดพลาดของ MySQLi อย่างละเอียดเพื่อให้ตรวจสอบได้ง่าย
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // [EN] Create a new MySQL connection
    // [TH] เริ่มต้นสร้างการเชื่อมต่อไปยังฐานข้อมูล
    $conn = new mysqli($host, $user, $pass, $dbname);

    // [EN] Set character set to utf8mb4 to support full unicode, including emojis
    // [TH] กำหนดการเข้ารหัสตัวอักษรเป็น utf8mb4 เพื่อรองรับภาษาไทยและอีโมจิ
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    // [EN] Log the actual error to server error logs for debugging
    // [TH] บันทึกข้อผิดพลาด (Log) ลงในเซิร์ฟเวอร์เพื่อให้ผู้ดูแลระบบตรวจสอบ
    error_log($e->getMessage());

    // [EN] Show generic error to user to avoid exposing sensitive database info
    // [TH] แสดงข้อความผิดพลาดทั่วไปให้ผู้ใช้เห็น เพื่อป้องกันข้อมูลฐานข้อมูลรั่วไหล
    die("Database Connection Error. Please try again later.");
}
?>
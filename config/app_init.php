<?php
// [EN] Secure session configuration to protect against common session attacks
// [TH] การตั้งค่าและจัดการ Session ให้มีความปลอดภัยป้องกันการเจาะระบบ
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0, // [EN] Session cookie lasts until browser closes / [TH] คุกกี้ทำงานจนกว่าจะปิดเบราว์เซอร์
        'path' => '/Fanclub', // [EN] Restrict session to application directory / [TH] จำกัดการใช้ Session เฉพาะโฟลเดอร์แอปนี้
        'domain' => '',
        'secure' => false, // [EN] Set to true if using HTTPS / [TH] หากใช้ HTTPS (SSL) ให้ตั้งเป็น true
        'httponly' => true, // [EN] Prevent JS access to session cookie (XSS protection) / [TH] ป้องกันไม่ให้ JavaScript อ่านค่าคุกกี้นี้ได้ (กัน XSS)
        'samesite' => 'Strict' // [EN] Prevent cross-site request forgery (CSRF) / [TH] ป้องกันการขโมย Session ข้ามไซต์
    ]);
    session_start();
}

// [EN] Security Headers to instruct browser on security policies
// [TH] ส่วน Headers เพื่อสั่งให้เบราว์เซอร์ใช้ระบบความปลอดภัย
header("X-Frame-Options: DENY"); // [EN] Prevent Clickjacking / [TH] ป้องกันเบราว์เซอร์อื่นมาดึงหน้านี้ไปแสดงใน iframe
header("X-Content-Type-Options: nosniff"); // [EN] Prevent MIME type sniffing / [TH] ป้องกันการปลอมแปลงประเภทไฟล์
header("X-XSS-Protection: 1; mode=block"); // [EN] Enable browser XSS filtering / [TH] เปิดตัวกรองการโจมตีแบบ XSS
header("Referrer-Policy: strict-origin-when-cross-origin"); // [EN] Control referrer information sent / [TH] ไม่เปิดเผยที่มาหากข้ามโดเมนอื่น

// [EN] Include required files using absolute paths based on this file's directory
// [TH] นำเข้าไฟล์ฐานข้อมูลและอรรถประโยชน์ (Utils) ที่ต้องใช้งานในระบบ
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/utils.php';

// [EN] Sync user data if logged in (Optimized: Sync every 10 mins or if forced)
// [TH] ซิงค์ข้อมูลล่าสุดของผู้ใช้ที่ล็อกอินอยู่ (ปรับแต่ง: อัปเดตทุกๆ 10 นาที เพื่อไม่ให้ทำงานหนักเกินไป)
if (isset($_SESSION['user_id'])) {
    $current_user_id = $_SESSION['user_id'];
    $last_sync = $_SESSION['last_sync'] ?? 0;
    $now = time();

    // [EN] Update activity and sync data every 10 minutes (600 seconds) to save resources
    // [TH] อัปเดตข้อมูลกิจกรรมและซิงค์ข้อมูลทุกๆ 10 นาที (600 วินาที) ลดภาระเซิร์ฟเวอร์
    if ($now - $last_sync > 600) {
        // [EN] Update last active timestamp
        // [TH] อัปเดตเวลาใช้งานล่าสุด (last_active)
        $upd_stmt = $conn->prepare("UPDATE users SET last_active = CURRENT_TIMESTAMP WHERE id = ?");
        $upd_stmt->bind_param("i", $current_user_id);
        $upd_stmt->execute();
        $upd_stmt->close();

        // [EN] Sync vital info (username, email, profile image) in case it changed
        // [TH] อัปเดตข้อมูลสำคัญ (ชื่อ, อีเมล, รูปโปรไฟล์) เผื่อมีการเปลี่ยนแปลงที่อื่น
        $sync_stmt = $conn->prepare("SELECT username, email, profile_img FROM users WHERE id = ?");
        $sync_stmt->bind_param("i", $current_user_id);
        $sync_stmt->execute();
        $sync_result = $sync_stmt->get_result();
        if ($user_data = $sync_result->fetch_assoc()) {
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['email'] = $user_data['email'];
            $_SESSION['profile_img'] = $user_data['profile_img'];
            $_SESSION['last_sync'] = $now; // [EN] Update sync timestamp / [TH] อัปเดตเวลาที่ซิงค์รูทล่าสุด
        }
        $sync_stmt->close();
    }
}

// [EN] Generate CSRF token for forms to prevent cross-site request forgery
// [TH] สร้างตัวแปร CSRF Token ไว้สำหรับปกป้องฟอร์มต่างๆ ข้ามระบบ
if (function_exists('get_csrf_token')) {
    $csrf_token = get_csrf_token();
}
?>
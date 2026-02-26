<?php
// [EN] Include application initialization settings
// [TH] นำเข้าการตั้งค่าเริ่มต้นของแอปพลิเคชันเพื่อจัดการ Session
require_once '../config/app_init.php';

// [EN] Clear all session variables to start logout process
// [TH] ล้างค่าในตัวแปร Session ทั้งหมดเพื่อเริ่มต้นกระบวนการออกจากระบบ
$_SESSION = [];

// [EN] Destroy the session cookie if it exists to completely invalidate session
// [TH] ลบคุกกี้ Session ออกจากเบราว์เซอร์หากมีการใช้งานอยู่
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// [EN] Destroy the session data on the server side
// [TH] ทำลาย Session บนฝั่งเซิร์ฟเวอร์
session_destroy();

// [EN] Redirect user back to the login page after logging out
// [TH] เปลี่ยนเส้นทาง (Redirect) ไปยังหน้าเข้าสู่ระบบหลังจากออกจากระบบสำเร็จ
header("Location: /Fanclub/auth/login");
exit;
?>
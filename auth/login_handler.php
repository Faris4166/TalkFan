<?php
// [EN] Set response type to JSON for API communication
// [TH] กำหนดประเภทการตอบกลับเป็น JSON สำหรับการสื่อสารแบบ API
header('Content-Type: application/json');

// [EN] Include application initialization settings
// [TH] นำเข้าการตั้งค่าเริ่มต้นของแอปพลิเคชัน
require_once '../config/app_init.php';

// [EN] Process request only if the method is POST
// [TH] ประมวลผลเฉพาะเมื่อมีการส่งข้อมูลด้วยเมธอด POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // [EN] Get and verify CSRF token to prevent cross-site request forgery
    // [TH] รับและตรวจสอบ CSRF token เพื่อป้องกันการปลอมแปลงคำขอข้ามไซต์
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        echo json_encode(['status' => 'error', 'message' => 'CSRF verification failed']);
        exit;
    }

    // [EN] Get username/email and password from POST data
    // [TH] รับค่าชื่อผู้ใช้/อีเมล และรหัสผ่านจากข้อมูล POST
    $username = trim($_POST['username'] ?? ''); // Can be email or username based on login.php label
    $password = $_POST['password'] ?? '';

    // [EN] Check for missing inputs
    // [TH] ตรวจสอบว่ามีการกรอกข้อมูลครบถ้วนหรือไม่
    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบทุกช่อง']);
        exit;
    }

    // [EN] Prepare SQL statement to check if username or email exists
    // [TH] เตรียมคำสั่ง SQL เพื่อตรวจสอบว่ามีชื่อผู้ใช้หรืออีเมลนี้ในระบบหรือไม่
    $stmt = $conn->prepare("SELECT id, username, password, profile_img FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // [EN] If exactly one user is found
    // [TH] หากพบผู้ใช้งาน 1 รายการ
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // [EN] Verify the provided password with the hashed password in database
        // [TH] ตรวจสอบรหัสผ่านที่กรอกมากับรหัสผ่านแฮช (Hashed password) ในฐานข้อมูล
        if (password_verify($password, $user['password'])) {
            // [EN] Regenerate session ID to prevent Session Fixation attacks
            // [TH] สร้าง Session ID ใหม่เพื่อความปลอดภัย ป้องกันการโจมตีแบบ Session Fixation
            session_regenerate_id(true);

            // [EN] Store user details in session variables
            // [TH] เก็บข้อมูลผู้ใช้ไว้ใน Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['profile_img'] = $user['profile_img'];

            echo json_encode(['status' => 'success', 'message' => 'เข้าสู่ระบบสำเร็จ!']);
        } else {
            // [EN] Password does not match
            // [TH] รหัสผ่านไม่ถูกต้อง
            echo json_encode(['status' => 'error', 'message' => 'รหัสผ่านไม่ถูกต้อง']);
        }
    } else {
        // [EN] User not found in database
        // [TH] ไม่พบข้อมูลผู้ใช้งานในระบบ
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบผู้ใช้นี้ในระบบ']);
    }

    // [EN] Close the database statement
    // [TH] ปิดการเชื่อมต่อ Statement ฐานข้อมูล
    $stmt->close();
}
?>
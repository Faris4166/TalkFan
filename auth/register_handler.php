<?php
// [EN] Set response type to JSON for API communication
// [TH] กำหนดประเภทการตอบกลับเป็น JSON สำหรับการสื่อสารแบบ API
header('Content-Type: application/json');

// [EN] Include application initialization settings
// [TH] นำเข้าการตั้งค่าเริ่มต้นของแอปพลิเคชัน
require_once '../config/app_init.php';

// [EN] Process registration request only if the method is POST
// [TH] ตรวจสอบว่ารับข้อมูลผ่านรูปแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // [EN] Get and verify CSRF token for security against cross-site request forgery
    // [TH] รับและตรวจสอบ CSRF token เพื่อความปลอดภัยจากการขโมยเซสชันข้ามไซต์
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        echo json_encode(['status' => 'error', 'message' => 'CSRF verification failed']);
        exit;
    }

    // [EN] Retrieve and sanitize user input data
    // [TH] รับข้อมูลที่ผู้ใช้กรอกเข้ามาและตัดช่องว่างส่วนเกิน
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // [EN] Validate that all fields are filled
    // [TH] ตรวจสอบว่ากรอกข้อมูลครบทุกช่องหรือไม่
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบทุกช่อง']);
        exit;
    }

    // [EN] Prepare SQL statement to check if username or email already exists
    // [TH] ตรวจสอบในฐานข้อมูลว่ามีชื่อผู้ใช้หรืออีเมลนี้สมัครใช้งานไปแล้วหรือยัง
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // [EN] Return error if user already exists
    // [TH] ส่งข้อความผิดพลาดกลับไปหากมีชื่อผู้ใช้หรืออีเมลซ้ำในระบบ
    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'ชื่อผู้ใช้หรืออีเมลนี้มีอยู่ในระบบแล้ว']);
        exit;
    }

    // [EN] Hash the user's password securely before storing in database
    // [TH] เข้ารหัสผ่าน (Hash) ให้ปลอดภัยก่อนบันทึกลงในฐานข้อมูล
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // [EN] Prepare and execute SQL statement to insert new user record
    // [TH] เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูลผู้ใช้ใหม่ลงในฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        // [EN] Registration successful
        // [TH] บันทึกข้อมูลและสมัครสมาชิกสำเร็จ
        echo json_encode(['status' => 'success', 'message' => 'สมัครสมาชิกสำเร็จ!']);
    } else {
        // [EN] Handle database record insertion error
        // [TH] จัดการข้อผิดพลาดที่เกิดขึ้นเวลาบันทึกข้อมูล
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $conn->error]);
    }

    // [EN] Close the database statement
    // [TH] ปิด Statement ฐานข้อมูล
    $stmt->close();
}
?>
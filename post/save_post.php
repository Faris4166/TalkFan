<?php
// [EN] Define JSON response type
// [TH] ตั้งค่าประเภทข้อมูลตอบกลับให้เป็น JSON
header('Content-Type: application/json');
require_once '../config/app_init.php';

// [EN] Ensure user is logged in
// [TH] ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อน']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // [EN] Verify CSRF to prevent cross-site request forgery
    // [TH] ตรวจสอบ CSRF Token ป้องกันผู้ไม่หวังดีส่งฟอร์มปลอมมา
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        echo json_encode(['status' => 'error', 'message' => 'CSRF verification failed']);
        exit;
    }

    // [EN] Retrieve parameters from form data
    // [TH] ดึงข้อมูลจากฟอร์ม
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'];
    $status = trim($_POST['status'] ?? 'published');
    $image_name = null;

    if (empty($title) || empty($content)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกหัวข้อและเนื้อหา']);
        exit;
    }

    // [EN] Handle Image Upload logic
    // [TH] จัดการการอัปโหลดรูปภาพ
    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $validation = validate_image_upload($_FILES['image']);
        if (!$validation['valid']) {
            echo json_encode(['status' => 'error', 'message' => $validation['error']]);
            exit;
        }

        $upload_dir = '../asset/post/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $upload_dir . $image_name;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถอัปโหลดรูปภาพได้']);
            exit;
        }
    }

    // [EN] Insert the post into the 'posts' table
    // [TH] เพิ่มข้อมูลกระทู้ใหม่ลงในฐานข้อมูลตาราง 'posts'
    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, image, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $content, $image_name, $status);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'สร้างกระทู้สำเร็จ!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $conn->error]);
    }
    $stmt->close();
}
?>
<?php
// [EN] Return JSON format for AJAX 
// [TH] กำหนดรูปแบบข้อมูลที่ตอบกลับเป็น JSON
header('Content-Type: application/json');
require_once '../config/app_init.php';

// [EN] Accept only POST requests
// [TH] ยอมรับคำสั่งผ่าน POST เท่านั้น
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // [EN] Verify CSRF Token
    // [TH] ตรวจสอบโทเคนป้องกันความปลอดภัย (CSRF)
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        echo json_encode(['status' => 'error', 'message' => 'CSRF token validation failed']);
        exit;
    }

    // [EN] Check if user is logged in
    // [TH] ตรวจสอบสิทธิ์ว่าได้เข้าสู่ระบบหรือยัง
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อนแสดงความคิดเห็น']);
        exit;
    }

    // [EN] Get POST data
    // [TH] รับข้อมูลจากฟอร์ม
    $user_id = $_SESSION['user_id'];
    $post_id = intval($_POST['post_id'] ?? 0);
    // [EN] parent_id handles nested comments/replies
    // [TH] ค่า parent_id ใช้สำหรับระบุว่าเป็นคอมเมนต์ตอบกลับหรือไม่
    $parent_id = isset($_POST['parent_id']) && !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
    $comment = trim($_POST['comment'] ?? '');

    // [EN] Validate basic input
    // [TH] ตรวจสอบว่ากรอกข้อมูลครบหรือไม่
    if ($post_id <= 0 || empty($comment)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อความแสดงความคิดเห็น']);
        exit;
    }

    // [EN] Insert the comment directly into the database
    // [TH] บันทึกคอมเมนต์ลงฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, parent_id, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $post_id, $user_id, $parent_id, $comment);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'ส่งความคิดเห็นเรียบร้อยแล้ว']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $conn->error]);
    }
    $stmt->close();
}
?>
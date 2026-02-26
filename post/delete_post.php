<?php
// [EN] Set JSON content type for AJAX response
// [TH] กำหนดประเภทข้อมูลที่ตอบกลับเป็น JSON
header('Content-Type: application/json');
require_once '../config/app_init.php';

// [EN] Auth check
// [TH] ตรวจสอบว่าเข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อน']);
    exit;
}

// [EN] Process HTTP POST method only
// [TH] รอรับคำสั่งผ่าน HTTP POST เท่านั้น
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        echo json_encode(['status' => 'error', 'message' => 'CSRF token validation failed']);
        exit;
    }
    $post_id = intval($_POST['post_id'] ?? 0);
    $user_id = $_SESSION['user_id'];

    if ($post_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'รหัสกระทู้ไม่ถูกต้อง']);
        exit;
    }

    // Verify ownership
    $stmt = $conn->prepare("SELECT image FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์ลบกระทู้นี้']);
        exit;
    }

    $post = $result->fetch_assoc();
    $image = $post['image'];

    // [EN] Delete post from database
    // [TH] ลบกระทู้ออกจากฐานข้อมูล
    // Delete post
    $del_stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    $del_stmt->bind_param("ii", $post_id, $user_id);

    if ($del_stmt->execute()) {
        // [EN] Delete associated image file from disk if it exists
        // [TH] ลบไฟล์รูปภาพที่แนบมาด้วย (ถ้ามี) ออกจากเครื่องเซิร์ฟเวอร์
        // Delete associated image if exists
        if ($image && file_exists("../asset/post/" . $image)) {
            unlink("../asset/post/" . $image);
        }
        echo json_encode(['status' => 'success', 'message' => 'ลบกระทู้เรียบร้อยแล้ว']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการลบกระทู้']);
    }
}
?>
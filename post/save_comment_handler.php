<?php
header('Content-Type: application/json');
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อนแสดงความคิดเห็น']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $post_id = intval($_POST['post_id'] ?? 0);
    $parent_id = isset($_POST['parent_id']) && !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
    $comment = trim($_POST['comment'] ?? '');

    if ($post_id <= 0 || empty($comment)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อความแสดงความคิดเห็น']);
        exit;
    }

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
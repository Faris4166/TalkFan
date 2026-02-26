<?php
header('Content-Type: application/json');
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อน']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = intval($_POST['post_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = trim($_POST['status'] ?? 'published');
    $user_id = $_SESSION['user_id'];

    if ($post_id <= 0 || empty($title) || empty($content)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        exit;
    }

    // Verify ownership and time restriction (1 hour)
    $stmt = $conn->prepare("SELECT created_at, image FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบกระทู้หรือคุณไม่มีสิทธิ์แก้ไข']);
        exit;
    }

    $post = $result->fetch_assoc();
    $created_at = strtotime($post['created_at']);
    $current_time = time();
    $diff_seconds = $current_time - $created_at;

    if ($diff_seconds > 3600) { // 1 hour = 3600 seconds
        echo json_encode(['status' => 'error', 'message' => 'กระทู้นี้เก่าเกินกว่าจะแก้ไขได้ (เกิน 1 ชั่วโมง)']);
        exit;
    }

    // Handle Image Upload (Optional Update)
    $image_name = $post['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../asset/post/';
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image_name = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $upload_dir . $new_image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Delete old image if exists
            if ($image_name && file_exists($upload_dir . $image_name)) {
                unlink($upload_dir . $image_name);
            }
            $image_name = $new_image_name;
        }
    }

    $upd_stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, image = ?, status = ? WHERE id = ? AND user_id = ?");
    $upd_stmt->bind_param("ssssii", $title, $content, $image_name, $status, $post_id, $user_id);

    if ($upd_stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'อัปเดตกระทู้เรียบร้อยแล้ว']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการอัปเดตกระทู้']);
    }
}
?>
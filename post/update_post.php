<?php
// [EN] Output format is JSON
// [TH] กำหนดการตอบกลับในรูปแบบ JSON
header('Content-Type: application/json');
require_once '../config/app_init.php';

// [EN] Authenticate User
// [TH] ตรวจสอบว่าเข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อน']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // [EN] CSRF Token validation
    // [TH] ตรวจสอบความถูกต้องของโืทเคนป้องกัน CSRF
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        echo json_encode(['status' => 'error', 'message' => 'CSRF verification failed']);
        exit;
    }

    // [EN] Form input data
    // [TH] ข้อมูลที่รับมาจากฟอร์มกรอกข้อมูล
    $post_id = intval($_POST['post_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = trim($_POST['status'] ?? 'published');
    $user_id = $_SESSION['user_id'];

    if ($post_id <= 0 || empty($title) || empty($content)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        exit;
    }

    // [EN] Verify ownership and time restriction (1 hour edit limit)
    // [TH] ตรวจสอบสิทธิ์ความเป็นเจ้าของและจำกัดเวลาแก้ไขภายใน 1 ชั่วโมง
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

    // [EN] Disallow edit if more than 3600 seconds
    // [TH] ยกเลิกการแแก้ไขหากเวลาเกิน 3600 วินาที (1 ชั่วโมง)
    if ($diff_seconds > 3600) { // 1 hour = 3600 seconds
        echo json_encode(['status' => 'error', 'message' => 'กระทู้นี้เก่าเกินกว่าจะแก้ไขได้ (เกิน 1 ชั่วโมง)']);
        exit;
    }

    // [EN] Handle Image Upload (Delete old image if exists)
    // [TH] จัดการระบบตั้งเวลาลบรูปภาพเก่าทิ้ง หากมีการอัปโหลดใหม่
    // Handle Image Upload (Optional Update)
    $image_name = $post['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $validation = validate_image_upload($_FILES['image']);
        if (!$validation['valid']) {
            echo json_encode(['status' => 'error', 'message' => $validation['error']]);
            exit;
        }

        $upload_dir = '../asset/post/';
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image_name = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $upload_dir . $new_image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // [EN] Apply delete on previous image logic
            // [TH] ลบรูปภาพเก่าบนเซิร์ฟเวอร์
            // Delete old image if exists
            if ($image_name && file_exists($upload_dir . $image_name)) {
                unlink($upload_dir . $image_name);
            }
            $image_name = $new_image_name;
        }
    }

    // [EN] Update database record securely using prepared statement
    // [TH] อัปเดตข้อมูลกระทู้ด้วย Prepared Statement เพื่อป้องกัน SQL Injection
    $upd_stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, image = ?, status = ? WHERE id = ? AND user_id = ?");
    $upd_stmt->bind_param("ssssii", $title, $content, $image_name, $status, $post_id, $user_id);

    if ($upd_stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'อัปเดตกระทู้เรียบร้อยแล้ว']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการอัปเดตกระทู้']);
    }
}
?>
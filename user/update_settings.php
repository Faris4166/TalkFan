<?php
header('Content-Type: application/json');
require_once '../config/app_init.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อน']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        echo json_encode(['status' => 'error', 'message' => 'CSRF verification failed']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกชื่อผู้ใช้และอีเมล']);
        exit;
    }

    // Check if username already exists for other users
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->bind_param("si", $username, $user_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'ชื่อผู้ใช้นี้ถูกใช้งานแล้ว']);
        exit;
    }
    $stmt->close();

    // Handle Profile Image Upload
    $profile_img = $_SESSION['profile_img'] ?? 'default_profile.png';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Validate image
        $validation = validate_image_upload($_FILES['image']);
        if (!$validation['valid']) {
            echo json_encode(['status' => 'error', 'message' => $validation['error']]);
            exit;
        }

        $upload_dir = '../asset/avatar/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $new_image_name = 'user_' . $user_id . '_' . time() . '.' . $file_ext;
        $target_file = $upload_dir . $new_image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Delete old profile image if it's not the default one
            if ($profile_img !== 'default_profile.png' && file_exists($upload_dir . $profile_img)) {
                unlink($upload_dir . $profile_img);
            }
            $profile_img = $new_image_name;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถอัปโหลดรูปภาพได้']);
            exit;
        }
    }

    // Build update query
    $sql = "UPDATE users SET username = ?, email = ?, profile_img = ?";
    $params = [$username, $email, $profile_img];
    $types = "sss";

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = ?";
        $params[] = $hashed_password;
        $types .= "s";
    }

    $sql .= " WHERE id = ?";
    $params[] = $user_id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        // Update Session
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['profile_img'] = $profile_img;

        echo json_encode(['status' => 'success', 'message' => 'อัปเดตข้อมูลสำเร็จ!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการอัปเดต: ' . $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'ไม่อนุญาตให้เข้าถึงวิธีนี้']);
}
?>
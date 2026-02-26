<?php
header('Content-Type: application/json');
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อน']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกชื่อผู้ใช้และอีเมล']);
        exit;
    }

    // Update Basic Info
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $hashed_password, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $email, $user_id);
    }

    if ($stmt->execute()) {
        // Update Session
        $_SESSION['username'] = $username;

        // Handle Profile Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../asset/';
            $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = 'profile_' . $user_id . '_' . time() . '.' . $file_ext;
            $target_file = $upload_dir . $image_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $stmt_img = $conn->prepare("UPDATE users SET profile_img = ? WHERE id = ?");
                $stmt_img->bind_param("si", $image_name, $user_id);
                $stmt_img->execute();
                $_SESSION['profile_img'] = $image_name;
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'อัปเดตข้อมูลสำเร็จ!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $conn->error]);
    }
    $stmt->close();
}
?>
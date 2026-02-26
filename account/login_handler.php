<?php
header('Content-Type: application/json');
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? ''); // Can be email or username based on login.php label
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณากรอกข้อมูลให้ครบทุกช่อง']);
        exit;
    }

    // Check username or email
    $stmt = $conn->prepare("SELECT id, username, password, profile_img FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['profile_img'] = $user['profile_img'];

            echo json_encode(['status' => 'success', 'message' => 'เข้าสู่ระบบสำเร็จ!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'รหัสผ่านไม่ถูกต้อง']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่พบผู้ใช้นี้ในระบบ']);
    }
    $stmt->close();
}
?>
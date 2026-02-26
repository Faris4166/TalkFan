<?php
// Secure session configuration
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/Fanclub',
        'domain' => '',
        'secure' => false, // Set to true if using HTTPS
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

// Security Headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Include required files using absolute paths based on this file's directory
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/utils.php';

// Sync user data if logged in (Optimized: Sync every 10 mins or if forced)
if (isset($_SESSION['user_id'])) {
    $current_user_id = $_SESSION['user_id'];
    $last_sync = $_SESSION['last_sync'] ?? 0;
    $now = time();

    // Update activity and sync data every 10 minutes to save resources
    if ($now - $last_sync > 600) {
        // Update last active timestamp
        $upd_stmt = $conn->prepare("UPDATE users SET last_active = CURRENT_TIMESTAMP WHERE id = ?");
        $upd_stmt->bind_param("i", $current_user_id);
        $upd_stmt->execute();
        $upd_stmt->close();

        $sync_stmt = $conn->prepare("SELECT username, email, profile_img FROM users WHERE id = ?");
        $sync_stmt->bind_param("i", $current_user_id);
        $sync_stmt->execute();
        $sync_result = $sync_stmt->get_result();
        if ($user_data = $sync_result->fetch_assoc()) {
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['email'] = $user_data['email'];
            $_SESSION['profile_img'] = $user_data['profile_img'];
            $_SESSION['last_sync'] = $now;
        }
        $sync_stmt->close();
    }
}

// Generate CSRF token for forms
if (function_exists('get_csrf_token')) {
    $csrf_token = get_csrf_token();
}
?>
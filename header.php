<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/utils.php';

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
$csrf_token = get_csrf_token();
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Fanclub'; ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- CSS: daisyUI & Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="icon" href="/Fanclub/asset/logo.svg">

    <style>
        body {
            font-family: 'Inter', 'Outfit', sans-serif;
            scroll-behavior: smooth;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="bg-base-200 min-h-screen flex flex-col">
    <?php include __DIR__ . '/components/navbar.php'; ?>
    <main class="flex-grow">
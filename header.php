<?php
require_once __DIR__ . '/config/app_init.php';
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
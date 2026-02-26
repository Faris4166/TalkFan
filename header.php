<?php
// [EN] Initialize application (Session & Security) before rendering any HTML
// [TH] เรียกไฟล์ตั้งค่าเริ่มต้น (Session และตรวจสอบความปลอดภัย) ก่อนแสดงผลหน้าเว็บ
require_once __DIR__ . '/config/app_init.php';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Fanclub'; ?></title>

    <!-- [EN] Load external fonts from Google Fonts -->
    <!-- [TH] นำเข้าฟอนต์จาก Google Fonts -->
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- [EN] UI Frameworks: daisyUI & Tailwind CSS for styling -->
    <!-- [TH] เฟรมเวิร์ค UI: ใช้ daisyUI คู่กับ Tailwind CSS สำหรับตกแต่งหน้าเว็บ -->
    <!-- CSS: daisyUI & Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="icon" href="/Fanclub/asset/logo.svg">

    <style>
        /* [EN] Base global styles */
        /* [TH] กำหนดการแสดงผลพื้นฐานของหน้าเว็บ */
        body {
            font-family: 'Inter', 'Outfit', sans-serif;
            scroll-behavior: smooth;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }
    </style>

    <!-- [EN] jQuery library for easier DOM manipulation and AJAX -->
    <!-- [TH] ไลบรารี jQuery ช่วยให้เขียนโค้ดจัดการหน้าเว็บและเรียกใช้ AJAX ได้ง่ายขึ้น -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="bg-base-200 min-h-screen flex flex-col">
    <!-- [EN] Include Navigation Bar component -->
    <!-- [TH] นำเข้าส่วนแถบเมนูด้านบน (Navbar) มาแสดงในทุกหน้าเว็บ -->
    <?php include __DIR__ . '/components/navbar.php'; ?>
    <main class="flex-grow">
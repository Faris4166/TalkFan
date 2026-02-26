<?php
// [EN] Global utility functions for Fanclub application
// [TH] ฟังก์ชันอรรถประโยชน์สาธารณะ ที่เรียกใช้ได้จากทุกที่ในระบบ Fanclub

/**
 * [EN] Renders a profile avatar. If no custom image is set, returns a letter avatar.
 * [TH] สร้างรูปโปรไฟล์ (Avatar) หากผู้ใช้ไม่ได้อัปโหลดรูป จะใช้ตัวอักษรตัวแรกของชื่อแทน
 * 
 * @param string $username The username to get the first letter from. [TH] ชื่อผู้ใช้งาน สำหรับดึงตัวอักษรตัวแรกมาแสดง
 * @param string|null $img The profile image filename. [TH] ชื่อไฟล์รูปโปรไฟล์ (ถ้ามี)
 * @param string $class Additional CSS classes for the container. [TH] คลาส CSS เพิ่มเติมสำหรับปรับแต่งขนาดสไตล์
 * @return string HTML for the avatar. [TH] โค้ด HTML ที่ใช้แสดงรูปภาพ Avatar
 */
function getAvatar($username, $img = 'default_profile.png', $class = 'w-10 h-10')
{
    $img = $img ?: 'default_profile.png';

    // [EN] If user is using default profile, generate an avatar with random color and initial letter
    // [TH] หากใช้ภาพพื้นฐาน ระบบจะสร้างภาพโดยใช้ตัวอักษรตัวแรกและสีแบบสุ่มแต่คงที่ตามชื่อผู้ใช้
    if ($img === 'default_profile.png') {
        $firstLetter = mb_strtoupper(mb_substr($username, 0, 1)); // [EN] Extract first letter / [TH] ดึงตัวอักษรแรก

        // [EN] Generate a consistent color based on username derived from char code
        // [TH] เลือกสีพื้นหลังให้คงที่สำหรับผู้ใช้นั้นๆ โดยอาศัยรหัสตัวอักษรเป็นเกณฑ์
        $colors = [
            'bg-blue-500',
            'bg-red-500',
            'bg-green-500',
            'bg-amber-500',
            'bg-purple-500',
            'bg-pink-500',
            'bg-indigo-500',
            'bg-cyan-500'
        ];
        $colorIndex = ord(substr($username, 0, 1)) % count($colors);
        $bgColor = $colors[$colorIndex];

        return "
        <div class=\"avatar placeholder\">
            <div class=\"{$bgColor} text-neutral-content rounded-2xl {$class}\">
                <span class=\"text-xl font-black font-outfit\">{$firstLetter}</span>
            </div>
        </div>";
    }

    // [EN] If user has a custom image, return the regular img tag avatar
    // [TH] ถ้ามีรูปภาพของตัวเอง ให้นำรูปภาพมาแสดง
    return "
    <div class=\"avatar\">
        <div class=\"rounded-2xl {$class}\">
            <img src=\"/Fanclub/asset/avatar/{$img}\" 
                 onerror=\"this.src='https://www.w3schools.com/howto/img_avatar.png'\" />
        </div>
    </div>";
}

/**
 * [EN] Generates a CSRF token and stores it in the session.
 * [TH] สร้าง Token สำหรับป้องกัน CSRF และเก็บไว้ใน Session 
 * @return string CSRF token
 */
function get_csrf_token()
{
    if (session_status() === PHP_SESSION_NONE)
        session_start();

    // [EN] If token doesn't exist, create a new 32-byte random hex string
    // [TH] หากยังไม่มีรหัสนี้ ให้ทำการสุ่มขึ้นมาใหม่และบันทึกลงใน Session
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * [EN] Verifies a provided CSRF token against the one stored in the session.
 * [TH] ตรวจสอบว่า CSRF Token ที่ส่งมา (เช่นใน Form) ตรงกับใน Session หรือไม่ 
 * @param string $token [TH] ค่า Token ที่รับมาจากแบบฟอร์ม
 * @return bool [TH] คืนค่า true หากตรงกัน เท็จหากไม่ตรง
 */
function verify_csrf_token($token)
{
    if (session_status() === PHP_SESSION_NONE)
        session_start();

    // [EN] Use hash_equals for timing attack safe comparison
    // [TH] ตรวจสอบเทียบค่า โดยใช้ hash_equals เพื่อความปลอดภัยจากการโจมตี Timing Attack
    return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/**
 * [EN] Validates an uploaded image for security and file quality.
 * [TH] ตรวจสอบความถูกต้องและปลอดภัยของไฟล์รูปภาพที่มีการอัปโหลดขึ้นมา
 * @param array $file The element from $_FILES [TH] ข้อมูลไฟล์ที่ได้จากตัวแปร $_FILES
 * @param int $max_size Max size in bytes (default 5MB) [TH] ขนาดสูงสุดของไฟล์ที่ยอมรับ (ไบต์)
 * @return array ['valid' => bool, 'error' => string|null] [TH] อาเรย์บอกสถานะและข้อความผิดพลาด
 */
function validate_image_upload($file, $max_size = 5242880)
{
    // [EN] Check basic upload error codes provided by PHP
    // [TH] เช็กข้อผิดพลาดเบื้องต้นของการอัพโหลดไฟล์จากคลาส PHP 
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'error' => 'Upload error code: ' . $file['error']];
    }

    // [EN] Check if file size is within limits
    // [TH] ตรวจสอบขนาดของไฟล์ว่าเกินขนาดสูงสุดที่กำหนดไว้หรือไม่
    if ($file['size'] > $max_size) {
        return ['valid' => false, 'error' => 'File size exceeds limit (5MB)'];
    }

    // [EN] Verify strict MIME type rather than just trusting the file extension
    // [TH] ตรวจสอบประเภทไฟล์จากเนื้อหาที่แท้จริงแทนการใช้สกุลไฟล์ (MIME Type) เพื่อความแม่นยำและปลอดภัย
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    // [EN] Reject if not supported type
    // [TH] ปฏิเสธถ้าไม่ใช่รูปแบบไฟล์ภาพที่รองรับ
    if (!in_array($mime, $allowed_mimes)) {
        return ['valid' => false, 'error' => 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.'];
    }

    // [EN] Secondary check: ensure it's actually an image using GD functions
    // [TH] ตรวจสอบชั้นที่ 2 ให้แน่ใจว่าไฟล์ที่ถูกอัปโหลดมีพิกเซลหรือความเป็นรูปภาพจริงๆ โดยใช้ฟังก์ชัน GD
    if (!getimagesize($file['tmp_name'])) {
        return ['valid' => false, 'error' => 'Uploaded file is not a valid image content.'];
    }

    // [EN] Validation successful
    // [TH] ไฟล์ผ่านการตรวจสอบทุกข้อ
    return ['valid' => true, 'error' => null];
}
?>
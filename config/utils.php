<?php
/**
 * Global utility functions for Fanclub
 */

/**
 * Renders a profile avatar. If no custom image is set, returns a letter avatar.
 * 
 * @param string $username The username to get the first letter from.
 * @param string|null $img The profile image filename.
 * @param string $class Additional CSS classes for the container.
 * @return string HTML for the avatar.
 */
function getAvatar($username, $img = 'default_profile.png', $class = 'w-10 h-10')
{
    $img = $img ?: 'default_profile.png';

    if ($img === 'default_profile.png') {
        $firstLetter = mb_strtoupper(mb_substr($username, 0, 1));

        // Generate a consistent color based on username
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

    return "
    <div class=\"avatar\">
        <div class=\"rounded-2xl {$class}\">
            <img src=\"/Fanclub/asset/avatar/{$img}\" 
                 onerror=\"this.src='https://www.w3schools.com/howto/img_avatar.png'\" />
        </div>
    </div>";
}

/**
 * Generates a CSRF token and stores it in the session.
 */
function get_csrf_token()
{
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifies a provided CSRF token against the one stored in the session.
 */
function verify_csrf_token($token)
{
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/**
 * Validates an uploaded image for security.
 * @param array $file The element from $_FILES
 * @param int $max_size Max size in bytes (default 5MB)
 * @return array ['valid' => bool, 'error' => string|null]
 */
function validate_image_upload($file, $max_size = 5242880)
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'error' => 'Upload error code: ' . $file['error']];
    }

    if ($file['size'] > $max_size) {
        return ['valid' => false, 'error' => 'File size exceeds limit (5MB)'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($mime, $allowed_mimes)) {
        return ['valid' => false, 'error' => 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.'];
    }

    // Secondary check: ensure it's actually an image
    if (!getimagesize($file['tmp_name'])) {
        return ['valid' => false, 'error' => 'Uploaded file is not a valid image content.'];
    }

    return ['valid' => true, 'error' => null];
}
?>
<?php
include '../header.php';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Fanclub Project</title>
</head>

<body class="bg-base-200 min-h-screen flex items-center justify-center p-4">

    <div class="card bg-base-100 shadow-2xl w-full max-w-md border border-base-300">
        <div class="card-body p-8 md:p-10">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-primary mb-2">สร้างบัญชีใหม่</h1>
                <p class="text-base-content/60">ร่วมเป็นส่วนหนึ่งของครอบครัวเรา</p>
            </div>

            <form id="registerForm" class="space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">ชื่อผู้ใช้ (Username)</span></label>
                    <label
                        class="input input-bordered flex items-center gap-3 focus-within:input-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="h-4 w-4 opacity-70">
                            <path
                                d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47z" />
                        </svg>
                        <input type="text" name="username" class="grow" placeholder="yourname123" required />
                    </label>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">อีเมล (Email)</span></label>
                    <label
                        class="input input-bordered flex items-center gap-3 focus-within:input-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="h-4 w-4 opacity-70">
                            <path
                                d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
                            <path
                                d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
                        </svg>
                        <input type="email" name="email" class="grow" placeholder="name@example.com" required />
                    </label>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">รหัสผ่าน</span></label>
                    <label
                        class="input input-bordered flex items-center gap-3 focus-within:input-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="h-4 w-4 opacity-70">
                            <path fill-rule="evenodd"
                                d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                                clip-rule="evenodd" />
                        </svg>
                        <input type="password" name="password" class="grow" placeholder="••••••••" required />
                    </label>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary/20 text-lg">
                        สมัครสมาชิก
                    </button>
                </div>
            </form>

            <p class="text-center mt-8 text-sm">
                มีบัญชีอยู่แล้ว?
                <a href="../account/login" class="link link-primary font-bold">เข้าสู่ระบบที่นี่</a>
            </p>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#registerForm').on('submit', function (e) {
                e.preventDefault();
                const btn = $(this).find('button[type="submit"]');
                const originalText = btn.text();

                btn.prop('disabled', true).html('<span class="loading loading-spinner loading-sm"></span> กำลังสมัคร...');

                $.ajax({
                    url: 'register_handler.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (res) {
                        if (res.status === 'success') {
                            alert(res.message);
                            window.location.href = 'login.php';
                        } else {
                            alert(res.message);
                            btn.prop('disabled', false).text(originalText);
                        }
                    },
                    error: function () {
                        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                        btn.prop('disabled', false).text(originalText);
                    }
                });
            });
        });
    </script>
</body>

</html>
<?php
// [EN] Set page title and include the header component
// [TH] ตั้งค่าชื่อหน้าเว็บและนำเข้าส่วนหัว (Header) ของแอพ
$page_title = "Register | Fanclub";
include '../header.php';
?>

<!-- [EN] Main registration container with centered layout -->
<!-- [TH] กล่องหลักสำหรับหน้าสมัครสมาชิก จัดให้อยู่กึ่งกลางหน้าจอ -->
<div class="min-h-[85vh] flex items-center justify-center p-6 py-12">
    <div class="card bg-base-100 shadow-2xl w-full max-w-md border border-base-300 rounded-[3rem] overflow-hidden">
        <div class="card-body p-10 md:p-12">
            <!-- [EN] Header area with icon and welcome message -->
            <!-- [TH] ส่วนหัวฟอร์มพร้อมไอคอนและข้อความต้อนรับผู้ใช้งานใหม่ -->
            <div class="text-center mb-10">
                <div class="w-16 h-16 bg-primary/10 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h1 class="text-4xl font-black text-primary mb-2 font-outfit tracking-tight">สร้างบัญชีใหม่</h1>
                <p class="text-base-content/60 font-medium">ร่วมเป็นส่วนหนึ่งของครอบครัว Fanclub</p>
            </div>

            <!-- [EN] Registration Form -->
            <!-- [TH] ฟอร์มสำหรับสมัครสมาชิก -->
            <form id="registerForm" class="space-y-5">
                <!-- [EN] CSRF token for security -->
                <!-- [TH] โทเค็น CSRF สำหรับความปลอดภัยของการส่งข้อมูล -->
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <!-- [EN] Username input field -->
                <!-- [TH] ช่องกรอกชื่อผู้ใช้งาน -->
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold opacity-70">ชื่อผู้ใช้
                            (Username)</span></label>
                    <label
                        class="input input-bordered flex items-center gap-3 focus-within:input-primary transition-all rounded-2xl h-14">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="h-5 w-5 opacity-40">
                            <path
                                d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47z" />
                        </svg>
                        <input type="text" name="username" class="grow font-medium" placeholder="yourname123"
                            required />
                    </label>
                </div>

                <!-- [EN] Email input field -->
                <!-- [TH] ช่องกรอกอีเมล -->
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold opacity-70">อีเมล (Email)</span></label>
                    <label
                        class="input input-bordered flex items-center gap-3 focus-within:input-primary transition-all rounded-2xl h-14">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="h-5 w-5 opacity-40">
                            <path
                                d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
                            <path
                                d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
                        </svg>
                        <input type="email" name="email" class="grow font-medium" placeholder="name@example.com"
                            required />
                    </label>
                </div>

                <!-- [EN] Password input field -->
                <!-- [TH] ช่องกรอกรหัสผ่าน -->
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold opacity-70">รหัสผ่าน</span></label>
                    <label
                        class="input input-bordered flex items-center gap-3 focus-within:input-primary transition-all rounded-2xl h-14">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="h-5 w-5 opacity-40">
                            <path fill-rule="evenodd"
                                d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                                clip-rule="evenodd" />
                        </svg>
                        <input type="password" name="password" class="grow font-medium" placeholder="••••••••"
                            required />
                    </label>
                </div>

                <!-- [EN] Submit button -->
                <!-- [TH] ปุ่มยืนยันการสมัครสมาชิก -->
                <div class="pt-8">
                    <button type="submit"
                        class="btn btn-primary w-full h-14 rounded-2xl shadow-xl shadow-primary/20 text-lg font-black uppercase tracking-wider">
                        เริ่มเป็นสมาชิก
                    </button>
                </div>
            </form>

            <!-- [EN] Link to login page if user already has an account -->
            <!-- [TH] ลิงก์สำหรับไปหน้าเข้าสู่ระบบหากมีบัญชีอยู่แล้ว -->
            <p class="text-center font-medium mt-6">
                มีบัญชีอยู่แล้ว?
                <a href="login"
                    class="text-primary font-black hover:underline underline-offset-4 ml-1">เข้าสู่ระบบได้เลย</a>
            </p>
        </div>
    </div>
</div>

<!-- [EN] Modal for displaying success or error messages -->
<!-- [TH] หน้าต่างป๊อปอัป (Modal) เพื่อแสดงข้อความแจ้งเตือนความสำเร็จหรือผิดพลาด -->
<dialog id="alert_modal" class="modal">
    <div class="modal-box rounded-[2rem] p-8">
        <h3 id="modal_title" class="font-black text-2xl tracking-tight mb-4 font-outfit"></h3>
        <p id="modal_message" class="text-base-content/70 leading-relaxed"></p>
        <div class="modal-action">
            <button id="modal_close_btn" class="btn btn-block rounded-2xl font-bold">ปิดหน้าต่าง</button>
        </div>
    </div>
</dialog>

<script>
    // [EN] Initialize jQuery when document is ready
    // [TH] เริ่มทำงานสคริปต์หลังจากที่หน้าเว็บโหลดข้อมูลเสร็จ
    $(document).ready(function () {
        // [EN] Function to show a dynamic modal (Success or Error)
        // [TH] ฟังก์ชันสำหรับแสดงข้อความแจ้งเตือน สามารถแสดงได้ทั้งสำเร็จและผิดพลาด
        function showModal(title, message, isSuccess = false) {
            $('#modal_title').text(title).removeClass('text-error text-success').addClass(isSuccess ? 'text-success' : 'text-error');
            $('#modal_message').text(message);

            // [EN] Assign action to the modal's close button
            // [TH] กำหนดคำสั่งให้ปุ่มปิด ถ้าสำเร็จให้เปลี่ยนไปหน้าล็อคอิน
            $('#modal_close_btn').off('click').on('click', function () {
                alert_modal.close();
                if (isSuccess) window.location.href = 'login';
            });
            alert_modal.showModal();
        }

        // [EN] Handle form submission via AJAX
        // [TH] จัดการส่งฟอร์มผ่าน AJAX
        $('#registerForm').on('submit', function (e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            const originalText = btn.text();

            // [EN] Disable button during processing
            // [TH] ปิดการกดปุ่มและแสดงไอคอนกำลังโหลด
            btn.prop('disabled', true).html('<span class="loading loading-spinner loading-sm"></span>');

            // [EN] Send registration data to the backend
            // [TH] ส่งข้อมูลไปให้หลังบ้านตรวจสอบและบันทึก
            $.ajax({
                url: 'register_handler',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        // [EN] Registration success
                        // [TH] สมัครสมาชิกสำเร็จ นำไปสู่หน้าล็อคอิน
                        showModal('สร้างบัญชีสำเร็จ', res.message, true);
                    } else {
                        // [EN] Registration failed, show error
                        // [TH] เกิดข้อผิดพลาด แสดงข้อความให้ผู้ใช้ทราบ
                        showModal('เกิดข้อผิดพลาด', res.message, false);
                        btn.prop('disabled', false).text(originalText);
                    }
                },
                error: function () {
                    // [EN] AJAX connection error
                    // [TH] จัดการกรณีที่มีปัญหาการเชื่อมต่อ
                    showModal('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้ในขณะนี้', false);
                    btn.prop('disabled', false).text(originalText);
                }
            });
        });
    });
</script>

<!-- [EN] Include the footer component -->
<!-- [TH] นำเข้าส่วนท้าย (Footer) ของแอพ -->
<?php include '../components/footer.php'; ?>
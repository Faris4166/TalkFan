<?php
// [EN] Set page title and include the header component
// [TH] ตั้งค่าชื่อหน้าเว็บและนำเข้าส่วนหัว (Header) ของแอพ
$page_title = "Login | Fanclub";
include '../header.php';
?>

<!-- [EN] Main login container with centered layout -->
<!-- [TH] กล่องหลักสำหรับหน้าเข้าสู่ระบบ จัดให้อยู่กึ่งกลางหน้าจอ -->
<div class="min-h-[80vh] flex items-center justify-center p-6">
    <div class="card bg-base-100 shadow-2xl w-full max-w-md border border-base-300 rounded-[3rem] overflow-hidden">
        <div class="card-body p-10 md:p-12">
            <!-- [EN] Header area with icon and welcome message -->
            <!-- [TH] ส่วนหัวฟอร์มพร้อมไอคอนและข้อความต้อนรับ -->
            <div class="text-center mb-10">
                <div class="w-16 h-16 bg-primary/10 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-4xl font-black text-primary mb-2 font-outfit">ยินดีต้อนรับกลับมา</h1>
                <p class="text-base-content/60 font-medium">กรุณาเข้าสู่ระบบเพื่อใช้งานต่อ</p>
            </div>

            <!-- [EN] Login Form -->
            <!-- [TH] ฟอร์มสำหรับเข้าสู่ระบบ -->
            <form id="loginForm" class="space-y-6">
                <!-- [EN] Hide input for CSRF protect token -->
                <!-- [TH] ซ่อน Input ส่ง Token เพื่อป้องกัน CSRF -->
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <!-- [EN] Username / Email input field -->
                <!-- [TH] ช่องกรอกอีเมลหรือชื่อผู้ใช้ -->
                <div class="form-control">
                    <label class="label"><span
                            class="label-text font-bold opacity-70">อีเมลหรือชื่อผู้ใช้</span></label>
                    <label
                        class="input input-bordered flex items-center gap-3 focus-within:input-primary transition-all rounded-2xl h-14">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="h-5 w-5 opacity-40">
                            <path
                                d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47z" />
                        </svg>
                        <input type="text" name="username" class="grow font-medium" placeholder="username@mail.com"
                            required />
                    </label>
                </div>

                <!-- [EN] Password input field -->
                <!-- [TH] ช่องกรอกรหัสผ่าน -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-bold opacity-70">รหัสผ่าน</span>
                    </label>
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
                <!-- [TH] ปุ่มยืนยันการเข้าสู่ระบบ -->
                <div class="pt-6">
                    <button type="submit"
                        class="btn btn-primary w-full h-14 rounded-2xl shadow-xl shadow-primary/20 text-lg font-black uppercase tracking-wider">
                        เข้าสู่ระบบ
                    </button>
                </div>
            </form>

            <!-- [EN] Link to registration page -->
            <!-- [TH] ลิงก์สำหรับไปหน้าสมัครสมาชิก -->
            <p class="text-center font-medium mt-6">
                ยังไม่มีบัญชีใช่ไหม?
                <a href="register"
                    class="text-primary font-black hover:underline underline-offset-4 ml-1">สมัครสมาชิกฟรี</a>
            </p>
        </div>
    </div>
</div>

<!-- [EN] Modal for displaying error messages -->
<!-- [TH] หน้าต่างป๊อปอัป (Modal) เพื่อแสดงข้อความแจ้งเตือนข้อผิดพลาด -->
<dialog id="alert_modal" class="modal">
    <div class="modal-box rounded-[2rem] p-8">
        <h3 id="modal_title" class="font-black text-2xl text-error mb-4 font-outfit tracking-tight">เกิดข้อผิดพลาด</h3>
        <p id="modal_message" class="text-base-content/70 leading-relaxed"></p>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-block rounded-2xl">เข้าใจแล้ว</button>
            </form>
        </div>
    </div>
</dialog>

<script>
    // [EN] Initialize jQuery when document is ready
    // [TH] เริ่มทำงานสคริปต์หลังจากที่หน้าเว็บโหลดข้อมูลเสร็จ
    $(document).ready(function () {
        // [EN] Function to show error message in the modal
        // [TH] ฟังก์ชันสำหรับแสดงข้อความแจ้งเตือนในป๊อปอัป Modal
        function showError(message) {
            $('#modal_message').text(message);
            alert_modal.showModal();
        }

        // [EN] Handle form submission via AJAX to prevent page reload
        // [TH] ดักจับการกดปุ่ม Submit เพื่อส่งข้อมูลผ่าน AJAX แทนการรีเฟรชหน้าเว็บ
        $('#loginForm').on('submit', function (e) {
            e.preventDefault();
            const btn = $(this).find('button[type="submit"]');
            const originalText = btn.text();

            // [EN] Disable button and show a loading spinner
            // [TH] ปิดการกดปุ่มซ้ำและแสดงรูปไอคอนกำลังโหลด
            btn.prop('disabled', true).html('<span class="loading loading-spinner loading-sm"></span>');

            // [EN] Send AJAX request to login backend handler
            // [TH] ยิง Request AJAX ไปที่ไฟล์จัดการระบบชหลังบ้าน
            $.ajax({
                url: 'login_handler',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        // [EN] Redirect to the homepage on success
                        // [TH] หากสำเร็จ ให้พาผู้ใช้กลับไปที่หน้าแรก
                        window.location.href = '../index';
                    } else {
                        // [EN] Show error and revert button state
                        // [TH] แสดงข้อความผิดพลาดและคืนค่าปุ่มกดกลับมา
                        showError(res.message);
                        btn.prop('disabled', false).text(originalText);
                    }
                },
                error: function () {
                    // [EN] Handle server or connection errors
                    // [TH] จัดการกรณีที่ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้
                    showError('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
                    btn.prop('disabled', false).text(originalText);
                }
            });
        });
    });
</script>

<!-- [EN] Include the footer component -->
<!-- [TH] นำเข้าส่วนท้าย (Footer) ของแอพ -->
<?php include '../components/footer.php'; ?>
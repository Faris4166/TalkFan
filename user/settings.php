<?php
// [EN] Settings page setup with authentication
// [TH] กำหนดชื่อเว็บและตรวจสอบการเข้าสู่ระบบก่อนอนุญาตให้เข้าหน้าตั้งค่า
$page_title = "Account Settings | Fanclub";
include '../header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: /Fanclub/auth/login');
    exit;
}
?>

<div class="container mx-auto px-4 py-16 max-w-2xl">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-black font-outfit mb-2 text-primary tracking-tight">การตั้งค่าบัญชี</h1>
        <p class="text-base-content/50 font-medium">จัดการข้อมูลส่วนตัวของคุณให้เป็นปัจจุบัน</p>
    </div>

    <div class="card bg-base-100 shadow-xl border border-base-200 rounded-2xl overflow-hidden">
        <div class="card-body p-8 md:p-12">
            <!-- [EN] Settings form allowing file uploads (enctype="multipart/form-data") -->
            <!-- [TH] ฟอร์มตั้งค่าโปรไฟล์ รองรับการอัปโหลดไฟล์รูปภาพ -->
            <form id="settingsForm" enctype="multipart/form-data" class="space-y-10">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <!-- Avatar Section -->
                <!-- [EN] Displays current profile image and allows selecting a new one -->
                <!-- [TH] แสดงรูปโปรไฟล์ปัจจุบัน และปุ่มสำหรับเลือกรูปใหม่ -->
                <div class="flex flex-col items-center gap-6 pb-10 border-b border-base-200">
                    <div class="avatar">
                        <div
                            class="w-32 h-32 rounded-2xl ring-4 ring-primary ring-offset-base-100 ring-offset-4 shadow-xl overflow-hidden">
                            <img id="imgPreview"
                                src="/Fanclub/asset/avatar/<?php echo $_SESSION['profile_img'] ?? 'default_profile.png'; ?>"
                                style="object-fit: cover;"
                                onerror="this.src='https://www.w3schools.com/howto/img_avatar.png'" />
                        </div>
                    </div>

                    <div class="w-full max-w-xs">
                        <input type="file" name="image" id="imageInput"
                            class="file-input file-input-bordered file-input-primary file-input-sm w-full rounded-xl"
                            accept="image/*" />
                    </div>
                </div>

                <!-- Basic Fields -->
                <div class="space-y-8">
                    <div class="form-control grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-6 items-center">
                        <label class="md:col-span-3">
                            <span class="label-text font-black text-base opacity-50">ชื่อผู้ใช้</span>
                        </label>
                        <div class="md:col-span-9">
                            <input type="text" name="username"
                                class="input input-bordered w-full h-14 rounded-2xl font-bold focus:input-primary bg-base-200/50 border-none px-6"
                                value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" required />
                        </div>
                    </div>

                    <div class="form-control grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-6 items-center">
                        <label class="md:col-span-3">
                            <span class="label-text font-black text-base opacity-50">อีเมล</span>
                        </label>
                        <div class="md:col-span-9">
                            <input type="email" name="email"
                                class="input input-bordered w-full h-14 rounded-2xl font-bold focus:input-primary bg-base-200/50 border-none px-6"
                                value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required />
                        </div>
                    </div>

                    <div class="form-control grid grid-cols-1 md:grid-cols-12 gap-2 md:gap-6 items-start">
                        <label class="md:col-span-3 pt-4">
                            <span class="label-text font-black text-base opacity-50">รหัสผ่านใหม่</span>
                        </label>
                        <div class="md:col-span-9">
                            <input type="password" name="password"
                                class="input input-bordered w-full h-14 rounded-2xl font-bold focus:input-primary bg-base-200/50 border-none px-6"
                                minlength="8" placeholder="••••••••" />
                            <label class="label">
                                <span class="label-text-alt opacity-30 font-bold">เว้นว่างไว้หากไม่เปลี่ยน</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-center">
                    <button type="button"
                        class="btn btn-primary btn-wide h-14 rounded-2xl font-black shadow-lg shadow-primary/20"
                        onclick="confirm_modal.showModal()">
                        บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<dialog id="confirm_modal" class="modal">
    <div class="modal-box rounded-2xl p-8 text-center">
        <h3 class="font-black text-2xl font-outfit mb-4">บันทึกข้อมูล?</h3>
        <p class="font-medium opacity-60 mb-8">คุณแน่ใจหรือไม่ว่าต้องการอัปเดตข้อมูลบัญชีในขณะนี้?</p>
        <div class="modal-action justify-center gap-3">
            <button class="btn btn-ghost px-8 rounded-xl font-bold" onclick="confirm_modal.close()">ยกเลิก</button>
            <button id="btnSubmit" class="btn btn-primary px-10 rounded-xl font-black">ยืนยันบันทึก</button>
        </div>
    </div>
</dialog>

<dialog id="alert_modal" class="modal">
    <div class="modal-box rounded-2xl p-8 text-center">
        <div id="modal_icon" class="mb-4 flex justify-center"></div>
        <h3 id="modal_title" class="font-black text-2xl font-outfit mb-2"></h3>
        <p id="modal_message" class="font-medium opacity-60"></p>
        <div class="modal-action w-full mt-6">
            <button onclick="alert_modal.close()" class="btn btn-block rounded-xl font-bold">ปิด</button>
        </div>
    </div>
</dialog>

<script>
    $(document).ready(function () {
        // [EN] Preview local image instantly after user selects a file
        // [TH] พรีวิวรูปภาพโปรไฟล์ใหม่ทันทีเมื่อผู้ใช้เลือกไฟล์ (ยังไม่ถูกอัปโหลดขึ้นเซิร์ฟเวอร์)
        $('#imageInput').on('change', function () {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = e => $('#imgPreview').attr('src', e.target.result);
                reader.readAsDataURL(file);
            }
        });

        // [EN] Handle form submission securely via AJAX using FormData
        // [TH] ส่งข้อมูลการแก้เซตติ้ง พร้อมไฟล์รูปภาพผ่าน AJAX โดยไม่ใช้การรีโหลดหน้าเว็บ
        $('#btnSubmit').on('click', function () {
            confirm_modal.close();
            let formData = new FormData($('#settingsForm')[0]);
            $.ajax({
                url: 'update_settings',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        $('#modal_icon').html('<div class="text-success"><svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>');
                        $('#modal_title').text('บันทึกสำเร็จ').addClass('text-success');
                        $('#modal_message').text(res.message);
                        alert_modal.showModal();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        $('#modal_icon').html('<div class="text-error"><svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg></div>');
                        $('#modal_title').text('เกิดข้อผิดพลาด').addClass('text-error');
                        $('#modal_message').text(res.message);
                        alert_modal.showModal();
                    }
                },
                error: () => alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้')
            });
        });
    });
</script>

<?php include '../components/footer.php'; ?>
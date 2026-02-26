<?php include '../header.php'; ?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanclub | Settings</title>
    <style>
        .profile-preview-container {
            position: relative;
            transition: all 0.3s ease;
        }
        .profile-preview-container:hover {
            opacity: 0.9;
        }
        #imgPreview {
            object-fit: cover; /* จัดการให้รูปอยู่กึ่งกลางและไม่เบี้ยว */
            object-position: center;
        }
    </style>
</head>

<body class="bg-base-200 min-h-screen">
    <?php include '../components/navbar.php'; ?>

    <div class="container mx-auto max-w-2xl p-4 md:p-10">
        <div class="card bg-base-100 shadow-2xl border border-base-300">
            <div class="card-body gap-8">
                <div>
                    <h2 class="card-title text-3xl font-black text-primary">Account Settings</h2>
                    <p class="text-base-content/60">จัดการข้อมูลส่วนตัวและรหัสผ่านของคุณ</p>
                </div>

                <form id="settingsForm" enctype="multipart/form-data" class="space-y-8">
                    <div class="flex flex-col sm:flex-row items-center gap-8 p-4 bg-base-200/50 rounded-2xl">
                        <div class="avatar profile-preview-container">
                            <div class="w-28 h-28 rounded-full ring ring-primary ring-offset-base-100 ring-offset-4 shadow-lg">
                                <img id="imgPreview" src="https://img.daisyui.com/images/profile/demo/yellingcat@192.webp" alt="Avatar" />
                            </div>
                        </div>
                        
                        <div class="flex-1 space-y-2 text-center sm:text-left">
                            <h3 class="font-bold text-lg">รูปโปรไฟล์</h3>
                            <p class="text-sm opacity-60 mb-3">แนะนำขนาด 500x500 px (JPG, PNG)</p>
                            <input type="file" name="image" id="imageInput" 
                                   class="file-input file-input-bordered file-input-primary file-input-sm w-full max-w-xs" 
                                   accept="image/*" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold">Username</span>
                            </label>
                            <label class="input input-bordered flex items-center gap-3 focus-within:input-primary transition-all">
                                <svg class="h-5 w-5 opacity-40 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <input type="text" name="username" class="grow" required placeholder="ชื่อผู้ใช้" pattern="[A-Za-z][A-Za-z0-9\-]*" minlength="3" maxlength="30" />
                            </label>
                            <div class="label"><span class="label-text-alt opacity-50">A-Z, 0-9 และเครื่องหมายขีด (3-30 ตัวอักษร)</span></div>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold">อีเมล</span>
                            </label>
                            <label class="input input-bordered flex items-center gap-3 focus-within:input-primary transition-all">
                                <svg class="h-5 w-5 opacity-40 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                </svg>
                                <input type="email" name="email" class="grow" placeholder="email@example.com" required />
                            </label>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text font-bold">รหัสผ่านใหม่</span>
                            </label>
                            <label class="input input-bordered flex items-center gap-3 focus-within:input-primary transition-all">
                                <svg class="h-5 w-5 opacity-40 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"></path>
                                    <circle cx="16.5" cy="7.5" r=".5"></circle>
                                </svg>
                                <input type="password" name="password" class="grow" placeholder="เว้นว่างไว้หากไม่ต้องการเปลี่ยน" minlength="8" />
                            </label>
                            <div class="label"><span class="label-text-alt opacity-50 text-warning">ขั้นต่ำ 8 ตัวอักษร (ประกอบด้วยตัวใหญ่, ตัวเล็ก และตัวเลข)</span></div>
                        </div>
                    </div>

                    <div class="card-actions justify-end pt-6 border-t border-base-200">
                        <button type="reset" class="btn btn-ghost">รีเซ็ต</button>
                        <button type="button" class="btn btn-primary btn-wide shadow-lg shadow-primary/20" onclick="confirm_modal.showModal()">
                            บันทึกการเปลี่ยนแปลง
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <dialog id="confirm_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-base-100 border-t-4 border-warning">
            <h3 class="font-bold text-2xl text-warning flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                ยืนยันการแก้ไขข้อมูล?
            </h3>
            <p class="py-4 text-lg">การบันทึกจะอัปเดตข้อมูลของคุณในระบบทันที</p>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-ghost">ยกเลิก</button>
                </form>
                <button id="btnSubmit" class="btn btn-primary px-8">ยืนยันบันทึก</button>
            </div>
        </div>
    </dialog>

    <dialog id="loading_modal" class="modal">
        <div class="modal-box text-center py-12">
            <span class="loading loading-dots loading-lg text-primary"></span>
            <h3 class="font-bold text-xl mt-4">กำลังประมวลผล...</h3>
        </div>
    </dialog>

    <?php include '../components/footer.php'; ?>

    <script>
        $(document).ready(function() {
            // ฟังก์ชัน Image Preview
            $('#imageInput').on('change', function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#imgPreview').hide().attr('src', event.target.result).fadeIn(500);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // ฟังก์ชันบันทึกข้อมูล (คงเดิมแต่เพิ่ม Feedback)
            $('#btnSubmit').on('click', function() {
                confirm_modal.close();
                loading_modal.showModal();
                
                let formData = new FormData($('#settingsForm')[0]);

                $.ajax({
                    url: 'update_settings.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        setTimeout(function() {
                            $('#loading_modal .modal-box').html(`
                                <div class="text-success scale-125 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-black text-2xl">อัปเดตเรียบร้อย!</h3>
                                <div class="modal-action justify-center">
                                    <button class="btn btn-success text-white px-10" onclick="location.reload()">ตกลง</button>
                                </div>
                            `);
                        }, 1000);
                    },
                    error: function() {
                        $('#loading_modal .modal-box').html(`
                            <h3 class="font-bold text-xl text-error text-2xl">เกิดข้อผิดพลาด</h3>
                            <p class="mt-2 opacity-70">ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้</p>
                            <button class="btn btn-error btn-outline mt-6 px-10" onclick="loading_modal.close()">ลองใหม่</button>
                        `);
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php
include '../header.php';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanclub | Chart Edit</title>
</head>

<body>
    <?php include '../components/navbar.php'; ?>

    <div class="container mx-auto max-w-4xl p-4 space-y-6">
        <form id="postForm" enctype="multipart/form-data">
            <div class="flex flex-col justify-start items-start gap-4">
                <h1 class="text-2xl font-bold">หัวข้อเรื่อง</h1>
                <input type="text" name="title" class="input input-bordered w-full" placeholder="กรอกหัวข้อที่นี่..." required>
            </div>

            <div class="flex flex-col justify-start items-start gap-4">
                <h1 class="text-2xl font-bold">เนื้อหา</h1>
                <textarea name="content" class="textarea textarea-bordered w-full h-64" placeholder="เขียนเนื้อหาของคุณ..." required></textarea>
            </div>

            <div class="flex flex-col justify-start items-start gap-4">
                <h1 class="text-xl font-semibold">รูปภาพ (ถ้ามี)</h1>
                <input type="file" name="image" class="file-input file-input-bordered w-full" />
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" class="btn btn-primary px-8" onclick="confirm_modal.showModal()">
                    บันทึก
                </button>
            </div>
        </form>
    </div>

    <dialog id="confirm_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">ยืนยันการบันทึก</h3>
            <p class="py-4 text-gray-600">คุณแน่ใจหรือไม่ว่าข้อมูลถูกต้องและต้องการสร้างกระทู้ในขณะนี้?</p>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-ghost">ยกเลิก</button>
                </form>
                <button id="btnConfirmSubmit" class="btn btn-primary">ยืนยันส่งข้อมูล</button>
            </div>
        </div>
    </dialog>

    <dialog id="loading_modal" class="modal">
        <div class="modal-box text-center py-10" id="modal_status_content">
            <div id="loading_state">
                <span class="loading loading-spinner loading-lg text-primary"></span>
                <h3 class="font-bold text-xl mt-4">กำลังสร้างกระทู้...</h3>
                <p class="text-sm text-gray-400 mt-2">กรุณารอสักครู่ ระบบกำลังจัดการข้อมูลของคุณ</p>
            </div>
        </div>
    </dialog>

    <?php include '../components/footer.php'; ?>

    <script>
        $(document).ready(function() {
            $('#btnConfirmSubmit').on('click', function() {
                // 1. ปิดหน้าต่างยืนยัน และ เปิดหน้าต่างโหลด
                confirm_modal.close();
                loading_modal.showModal();

                // 2. ดึงข้อมูลจาก Form
                let formData = new FormData($('#postForm')[0]);

                // 3. ส่งข้อมูลด้วย AJAX
                $.ajax({
                    url: 'save_post.php', // *** อย่าลืมเปลี่ยนชื่อไฟล์ PHP ที่จะรับค่า ***
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // หน่วงเวลา 1.5 วินาทีเพื่อให้เห็น Spinner แป๊บหนึ่ง (เพื่อความสวยงาม)
                        setTimeout(function() {
                            $('#modal_status_content').html(`
                                <div class="flex flex-col items-center">
                                    <div class="text-success mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-2xl text-base-content">สำเร็จแล้ว!</h3>
                                    <p class="py-2 text-gray-500">กระทู้ของคุณถูกสร้างเรียบร้อยแล้ว</p>
                                    <div class="modal-action w-full">
                                        <button class="btn btn-block" onclick="location.reload()">ปิดหน้าต่างนี้</button>
                                    </div>
                                </div>
                            `);
                        }, 1500);
                    },
                    error: function() {
                        // กรณีเกิดข้อผิดพลาด
                        $('#modal_status_content').html(`
                            <h3 class="font-bold text-xl text-error">เกิดข้อผิดพลาด!</h3>
                            <p class="py-4">ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้</p>
                            <div class="modal-action">
                                <button class="btn" onclick="loading_modal.close()">ลองใหม่อีกครั้ง</button>
                            </div>
                        `);
                    }
                });
            });
        });
    </script>
</body>

</html>
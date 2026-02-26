<?php
$page_title = "Create New Post | Fanclub";
include '../header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: /Fanclub/auth/login');
    exit;
}
?>

<div class="container mx-auto max-w-4xl px-4 py-16">
    <div class="text-center mb-12">
        <h1 class="text-5xl font-black font-outfit mb-4 text-primary tracking-tight">สร้างกระทู้ใหม่</h1>
        <p class="text-lg opacity-60 font-medium">แบ่งปันเรื่องราวของคุณกับเพื่อนๆ ในคอมมูนิตี้</p>
    </div>

    <div class="card bg-base-100 shadow-2xl border border-base-300 rounded-2xl overflow-hidden">
        <div class="card-body p-8 md:p-12">
            <form id="postForm" enctype="multipart/form-data" class="space-y-10">
                <input type="hidden" name="status" id="postStatus" value="published">
                <!-- Title -->
                <div class="space-y-4">
                    <label
                        class="flex items-center gap-3 text-xl font-black font-outfit uppercase tracking-wider opacity-60">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                        หัวข้อเรื่อง
                    </label>
                    <input type="text" name="title"
                        class="input input-bordered w-full h-16 text-xl font-bold rounded-2xl focus:input-primary transition-all px-6"
                        placeholder="กรอกหัวข้อที่น่าสนใจที่นี่..." required>
                </div>

                <!-- Content -->
                <div class="space-y-4">
                    <label
                        class="flex items-center gap-3 text-xl font-black font-outfit uppercase tracking-wider opacity-60">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                        เนื้อหา
                    </label>
                    <textarea name="content"
                        class="textarea textarea-bordered w-full h-80 text-lg font-medium rounded-2xl focus:textarea-primary transition-all p-6"
                        placeholder="เขียนเรื่องราวของคุณในแบบที่ต้องการ..." required></textarea>
                </div>

                <!-- Image Upload -->
                <div class="space-y-4">
                    <label
                        class="flex items-center gap-3 text-xl font-black font-outfit uppercase tracking-wider opacity-60">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                        แนบรูปภาพ
                    </label>
                    <div
                        class="flex items-center justify-center border-4 border-dashed border-base-300 rounded-2xl p-10 hover:border-primary/50 transition-colors group">
                        <div class="text-center space-y-4">
                            <div
                                class="w-20 h-20 bg-base-200 rounded-2xl flex items-center justify-center mx-auto group-hover:bg-primary/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-10 w-10 opacity-30 group-hover:text-primary transition-colors" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="file" name="image" id="imageInput"
                                class="file-input file-input-bordered file-input-primary w-full max-w-xs rounded-xl" />
                            <p class="text-sm opacity-50 font-bold">JPG, PNG, GIF (Max 5MB)</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-base-200">
                    <button type="button" id="btnDraft"
                        class="btn btn-ghost px-10 h-16 rounded-2xl text-xl font-bold border border-base-300">
                        บันทึกแบบร่าง
                    </button>
                    <button type="button"
                        class="btn btn-primary px-16 h-16 rounded-2xl text-xl font-black shadow-xl shadow-primary/30"
                        onclick="confirm_modal.showModal()">
                        สร้างกระทู้ทันที
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<dialog id="confirm_modal" class="modal">
    <div class="modal-box rounded-2xl p-10">
        <h3 class="font-black text-3xl font-outfit mb-4">พร้อมแชร์หรือยัง?</h3>
        <p class="text-lg opacity-60 font-medium leading-relaxed mb-10">คุณแน่ใจแล้วใช่ไหมว่ากระทู้นี้พร้อมส่งออกสู่ชาว
            Fanclub แล้ว?</p>
        <div class="modal-action gap-4">
            <form method="dialog">
                <button class="btn btn-ghost px-8 rounded-2xl font-bold">ยังก่อน</button>
            </form>
            <button id="btnConfirmSubmit"
                class="btn btn-primary px-10 rounded-2xl font-black shadow-lg shadow-primary/20">ยืนยันส่งข้อมูล</button>
        </div>
    </div>
</dialog>

<dialog id="loading_modal" class="modal">
    <div class="modal-box text-center py-20 rounded-2xl" id="modal_status_content">
        <div id="loading_state">
            <span class="loading loading-spinner loading-lg text-primary scale-150 mb-8"></span>
            <h3 class="font-black text-3xl font-outfit mt-4">กำลังจัดการข้อมูล...</h3>
            <p class="text-sm opacity-50 mt-4 font-bold uppercase tracking-widest">Wait a second</p>
        </div>
    </div>
</dialog>

<script>
    $(document).ready(function () {
        function submitPost(status) {
            $('#postStatus').val(status);
            loading_modal.showModal();

            let formData = new FormData($('#postForm')[0]);

            $.ajax({
                url: 'save_post',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    const isDraft = status === 'draft';
                    const successMsg = isDraft ? 'บันทึกแบบร่างเรียบร้อยแล้ว' : 'กระทู้ของคุณถูกเผยแพร่เรียบร้อยแล้ว';

                    setTimeout(function () {
                        $('#modal_status_content').html(`
                            <div class="flex flex-col items-center">
                                <div class="text-success mb-8 scale-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-black text-4xl font-outfit">${isDraft ? 'บันทึกสำเร็จ' : 'สำเร็จแล้ว!'}</h3>
                                <p class="py-4 text-lg opacity-60 font-medium">${successMsg}</p>
                                <div class="modal-action w-full mt-10">
                                    <button class="btn btn-block h-16 rounded-2xl font-black text-xl" onclick="window.location.href='../index'">กลับหน้าหลัก</button>
                                </div>
                            </div>
                        `);
                    }, 1500);
                },
                error: function () {
                    $('#modal_status_content').html(`
                        <h3 class="font-black text-3xl text-error font-outfit">เกิดข้อผิดพลาด!</h3>
                        <p class="py-6 text-lg font-medium opacity-60">ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ในขณะนี้</p>
                        <div class="modal-action w-full">
                            <button class="btn btn-block rounded-2xl" onclick="loading_modal.close()">ลองใหม่อีกครั้ง</button>
                        </div>
                    `);
                }
            });
        }

        $('#btnDraft').on('click', function () {
            submitPost('draft');
        });

        $('#btnConfirmSubmit').on('click', function () {
            confirm_modal.close();
            submitPost('published');
        });
    });
</script>

<?php include '../components/footer.php'; ?>
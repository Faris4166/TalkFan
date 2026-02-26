<?php
$page_title = "Edit Post | Fanclub";
require_once '../config/db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /Fanclub/auth/login');
    exit;
}

$post_id = intval($_GET['id'] ?? 0);
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    header('Location: ../user/my_posts');
    exit;
}

// Check if editable (1 hour)
$created_at = strtotime($post['created_at']);
$current_time = time();
$diff_seconds = $current_time - $created_at;
$is_editable = $diff_seconds <= 3600;

if (!$is_editable) {
    echo "<div class='container mx-auto px-4 py-20 text-center'><h2 class='text-2xl font-black'>กระทู้นี้เก่าเกินกว่าจะแก้ไขได้แล้ว</h2><a href='../user/my_posts' class='btn btn-primary mt-6'>กลับไปหน้าของคุณ</a></div>";
    include '../components/footer.php';
    exit;
}
?>

<div class="container mx-auto max-w-4xl px-4 py-16">
    <div class="text-center mb-12">
        <h1 class="text-5xl font-black font-outfit mb-4 text-primary tracking-tight">แก้ไขกระทู้</h1>
        <p class="text-lg opacity-60 font-medium">ปรับปรุงเนื้อหาสักเล็กน้อยเพื่อให้สมบูรณ์แบบ</p>
    </div>

    <div class="card bg-base-100 shadow-2xl border border-base-300 rounded-2xl overflow-hidden">
        <div class="card-body p-8 md:p-12">
            <form id="editForm" enctype="multipart/form-data" class="space-y-10">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <input type="hidden" name="status" id="postStatus"
                    value="<?php echo htmlspecialchars($post['status']); ?>">

                <!-- Title -->
                <div class="space-y-4">
                    <label
                        class="flex items-center gap-3 text-xl font-black font-outfit uppercase tracking-wider opacity-60">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                        หัวข้อเรื่อง
                    </label>
                    <input type="text" name="title"
                        class="input input-bordered w-full h-16 text-xl font-bold rounded-2xl focus:input-primary transition-all px-6"
                        value="<?php echo htmlspecialchars($post['title']); ?>" required>
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
                        required><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>

                <!-- Image Upload -->
                <div class="space-y-4">
                    <label
                        class="flex items-center gap-3 text-xl font-black font-outfit uppercase tracking-wider opacity-60">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                        แนบรูปภาพใหม่ (ถ้ามี)
                    </label>
                    <div
                        class="flex items-center justify-center border-4 border-dashed border-base-300 rounded-2xl p-10 hover:border-primary/50 transition-colors group">
                        <div class="text-center space-y-4">
                            <input type="file" name="image" id="imageInput"
                                class="file-input file-input-bordered file-input-primary w-full max-w-xs rounded-xl" />
                            <p class="text-sm opacity-50 font-bold">JPG, PNG, GIF (Max 5MB)</p>
                            <?php if ($post['image']): ?>
                                <p class="text-xs text-primary font-bold">มีรูปเดิมอยู่แล้ว</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-base-200">
                    <button type="button" id="btnDraft"
                        class="btn btn-ghost px-10 h-16 rounded-2xl text-xl font-bold border border-base-300">
                        <?php echo $post['status'] === 'draft' ? 'บันทึกแบบร่างไว้ก่อน' : 'ย้ายไปเป็นแบบร่าง'; ?>
                    </button>
                    <button type="button"
                        class="btn btn-primary px-16 h-16 rounded-2xl text-xl font-black shadow-xl shadow-primary/30"
                        onclick="confirm_modal.showModal()">
                        บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<dialog id="confirm_modal" class="modal">
    <div class="modal-box rounded-2xl p-10">
        <h3 class="font-black text-3xl font-outfit mb-4">บันทึกการแก้ไขหรือไม่?</h3>
        <p class="text-lg opacity-60 font-medium leading-relaxed mb-10">การแก้ไขของคุณจะถูกนำไปเผยแพร่ทันที</p>
        <div class="modal-action gap-4">
            <form method="dialog">
                <button class="btn btn-ghost px-8 rounded-2xl font-bold">ยังก่อน</button>
            </form>
            <button id="btnConfirmSubmit"
                class="btn btn-primary px-10 rounded-2xl font-black shadow-lg shadow-primary/20">ยืนยันบันทึก</button>
        </div>
    </div>
</dialog>

<dialog id="loading_modal" class="modal">
    <div class="modal-box text-center py-20 rounded-2xl" id="modal_status_content">
        <div id="loading_state">
            <span class="loading loading-spinner loading-lg text-primary scale-150 mb-8"></span>
            <h3 class="font-black text-3xl font-outfit mt-4">กำลังอัปเดตข้อมูล...</h3>
        </div>
    </div>
</dialog>

<script>
    $(document).ready(function () {
        function updatePost(status) {
            $('#postStatus').val(status);
            loading_modal.showModal();

            let formData = new FormData($('#editForm')[0]);

            $.ajax({
                url: 'update_post',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    setTimeout(function () {
                        $('#modal_status_content').html(`
                            <div class="flex flex-col items-center">
                                <div class="text-success mb-8 scale-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-black text-4xl font-outfit">อัปเดตสำเร็จ!</h3>
                                <p class="py-4 text-lg opacity-60 font-medium">กระทู้ของคุณถูกแก้ไขเรียบร้อยแล้ว</p>
                                <div class="modal-action w-full mt-10">
                                    <button class="btn btn-block h-16 rounded-2xl font-black text-xl" onclick="window.location.href='../user/my_posts'">กลับหน้ากระทู้ของฉัน</button>
                                </div>
                            </div>
                        `);
                    }, 1500);
                },
                error: function () {
                    $('#modal_status_content').html(`
                        <h3 class="font-black text-3xl text-error font-outfit">เกิดข้อผิดพลาด!</h3>
                        <div class="modal-action w-full">
                            <button class="btn btn-block rounded-2xl" onclick="loading_modal.close()">ลองใหมีกครั้ง</button>
                        </div>
                    `);
                }
            });
        }

        $('#btnDraft').on('click', function () {
            updatePost('draft');
        });

        $('#btnConfirmSubmit').on('click', function () {
            confirm_modal.close();
            updatePost('published');
        });
    });
</script>

<?php include '../components/footer.php'; ?>
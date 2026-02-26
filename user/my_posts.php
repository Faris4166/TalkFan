<?php
$page_title = "My Posts | Fanclub";
require_once '../config/db.php';
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /Fanclub/auth/login');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user info and post count
$stmt = $conn->prepare("SELECT username, email, profile_img, (SELECT COUNT(*) FROM posts WHERE user_id = ?) as post_count FROM users WHERE id = ?");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$user_info = $stmt->get_result()->fetch_assoc();

// Get user posts
$tab = $_GET['tab'] ?? 'all';
$sql_posts = "SELECT * FROM posts WHERE user_id = ?";
if ($tab === 'drafts') {
    $sql_posts .= " AND status = 'draft'";
}
$sql_posts .= " ORDER BY created_at DESC";

$p_stmt = $conn->prepare($sql_posts);
$p_stmt->bind_param("i", $user_id);
$p_stmt->execute();
$posts_result = $p_stmt->get_result();
?>

<div class="container mx-auto px-4 py-16 max-w-6xl">
    <!-- Profile Card -->
    <div class="card bg-base-100 shadow-2xl rounded-2xl border border-base-300 overflow-hidden mb-16 relative">
        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-full h-32 bg-primary/10"></div>

        <div class="card-body p-8 md:p-12 relative z-10 pt-16">
            <div class="flex flex-col md:flex-row items-center md:items-end gap-8">
                <div class="avatar">
                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-2xl ring-8 ring-base-100 shadow-2xl overflow-hidden">
                        <?php echo getAvatar($user_info['username'], $user_info['profile_img'], 'w-full h-full'); ?>
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left space-y-2">
                    <h1 class="text-4xl md:text-5xl font-black font-outfit tracking-tight">
                        <?php echo htmlspecialchars($user_info['username']); ?>
                    </h1>
                    <p class="text-lg opacity-60 font-medium"><?php echo htmlspecialchars($user_info['email']); ?></p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                        <div class="badge badge-primary badge-lg py-4 px-6 rounded-2xl font-black gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" />
                            </svg>
                            <?php echo $user_info['post_count']; ?> กระทู้ของคุณ
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 w-full md:w-auto mt-6 md:mt-0">
                    <a href="../post/create"
                        class="btn btn-primary flex-1 md:flex-initial h-14 rounded-2xl font-black px-8 shadow-xl shadow-primary/20">
                        สร้างกระทู้ใหม่
                    </a>
                    <a href="settings" class="btn btn-ghost h-14 rounded-2xl font-bold px-6 border border-base-300">
                        แก้ไขโปรไฟล์
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats & Posts Grid -->
    <div class="space-y-10">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-2 h-8 bg-primary rounded-full"></span>
                <h2 class="text-3xl font-black font-outfit">ประวัติการสร้างกระทู้</h2>
            </div>

            <div class="tabs tabs-box rounded-2xl bg-base-100 border border-base-300">
                <a href="?tab=all"
                    class="tab <?php echo $tab !== 'drafts' ? 'tab-active' : ''; ?> font-bold">ทั้งหมด</a>
                <a href="?tab=drafts"
                    class="tab <?php echo $tab === 'drafts' ? 'tab-active' : ''; ?> font-medium opacity-50">แบบร่าง</a>
            </div>
        </div>

        <div id="posts-container">
            <!-- Skeleton for loading -->
            <div id="skeleton-grid" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php for ($i = 0; $i < 4; $i++): ?>
                    <div class="card bg-base-100 shadow-sm border border-base-300 rounded-[2.5rem] p-8">
                        <div class="space-y-6">
                            <div class="skeleton h-8 w-3/4 rounded-xl"></div>
                            <div class="skeleton h-32 w-full rounded-2xl"></div>
                            <div class="flex justify-between items-center pt-4">
                                <div class="skeleton h-6 w-32 rounded-lg"></div>
                                <div class="skeleton h-10 w-24 rounded-xl"></div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Real Content -->
            <div id="real-grid" class="hidden grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php if ($posts_result && $posts_result->num_rows > 0): ?>
                    <?php while ($post = $posts_result->fetch_assoc()): ?>
                        <div
                            class="card bg-base-100 shadow-sm border border-base-300 hover:border-primary hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 rounded-[2.5rem] group overflow-hidden">
                            <div class="card-body p-8 flex flex-col justify-between">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-start">
                                        <h3
                                            class="text-2xl font-black font-outfit line-clamp-1 group-hover:text-primary transition-colors">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </h3>
                                        <!-- No longer using dropdown-end here, handled in footer buttons -->
                                    </div>

                                    <p class="text-base text-base-content/60 font-medium line-clamp-2 leading-relaxed">
                                        <?php echo htmlspecialchars(mb_strimwidth($post['content'], 0, 150, "...")); ?>
                                    </p>

                                    <!-- Status Badge -->
                                    <div class="mt-2 text-[10px] font-black uppercase tracking-widest opacity-40">
                                        <?php if ($post['status'] === 'draft'): ?>
                                            <span
                                                class="badge badge-warning badge-sm rounded-lg"><?php echo $post['status']; ?></span>
                                        <?php else: ?>
                                            <span
                                                class="badge badge-success badge-sm rounded-lg text-white"><?php echo $post['status']; ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($post['image']): ?>
                                        <div class="w-full h-40 overflow-hidden rounded-2xl border border-base-200 mt-4">
                                            <img src="/Fanclub/asset/post/<?php echo $post['image']; ?>"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center justify-between mt-8 pt-6 border-t border-base-200">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold opacity-30 uppercase tracking-widest"><?php echo date('d M Y', strtotime($post['created_at'])); ?></span>
                                    </div>

                                    <div class="flex gap-2">
                                        <?php
                                        $can_edit = (time() - strtotime($post['created_at'])) <= 3600;
                                        if ($can_edit):
                                            ?>
                                            <a href="../post/edit?id=<?php echo $post['id']; ?>"
                                                class="btn btn-ghost btn-sm rounded-xl font-bold border border-base-200 h-10 px-4">แก้ไข</a>
                                        <?php endif; ?>

                                        <button onclick="confirmDelete(<?php echo $post['id']; ?>)"
                                            class="btn btn-error btn-outline btn-sm rounded-xl font-bold h-10 px-4">ลบ</button>
                                        <a href="../post/view?id=<?php echo $post['id']; ?>"
                                            class="btn btn-primary btn-sm rounded-xl px-6 font-black h-10">อ่านต่อ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div
                        class="col-span-1 md:col-span-2 text-center py-32 bg-base-100 rounded-2xl border-2 border-dashed border-base-300">
                        <div class="opacity-20 mb-6 scale-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black font-outfit mb-2">คุณยังไม่มีกระทู้เลย</h3>
                        <p class="font-medium opacity-50 mb-8">เริ่มแชร์เรื่องราวแรกของคุณให้ชาวโลกได้รับรู้!</p>
                        <a href="../post/create"
                            class="btn btn-primary btn-wide h-14 rounded-2xl font-black shadow-xl shadow-primary/20">สร้างกระทู้แรก</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<dialog id="delete_modal" class="modal">
    <div class="modal-box rounded-2xl p-8 text-center">
        <div class="text-error mb-4 flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <h3 class="font-black text-2xl font-outfit mb-4">ยืนยันการลบ?</h3>
        <p class="font-medium opacity-60 mb-8">คุณแน่ใจหรือไม่ว่าต้องการลบกระทู้นี้? การกระทำนี้ไม่สามารถย้อนกลับได้</p>
        <div class="modal-action justify-center gap-3">
            <form method="dialog">
                <button class="btn btn-ghost px-8 rounded-xl font-bold">ยกเลิก</button>
            </form>
            <button id="btnConfirmDelete" class="btn btn-error px-10 rounded-xl font-black text-white">ลบทันที</button>
        </div>
    </div>
</dialog>

<script>
    let postToDelete = null;

    function confirmDelete(id) {
        postToDelete = id;
        delete_modal.showModal();
    }

    $(document).ready(function () {
        $('#btnConfirmDelete').on('click', function () {
            if (postToDelete) {
                $.ajax({
                    url: '../post/delete_post',
                    type: 'POST',
                    data: { post_id: postToDelete },
                    success: function (response) {
                        location.reload();
                    },
                    error: function () {
                        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                    }
                });
            }
        });

        window.addEventListener('load', () => {
            setTimeout(() => {
                document.getElementById('skeleton-grid').classList.add('hidden');
                document.getElementById('real-grid').classList.remove('hidden');
                document.getElementById('real-grid').classList.add('animate-in', 'fade-in', 'slide-in-from-bottom-5', 'duration-700');
            }, 800);
        });
    });
</script>

<?php include '../components/footer.php'; ?>
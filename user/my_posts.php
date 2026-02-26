<?php
// [EN] Set page title, include DB connection and header
// [TH] กำหนดชื่อหน้าเว็บ ดึงไฟล์เชื่อมต่อฐานข้อมูลและส่วนหัวของเว็บ
$page_title = "My Posts | Fanclub";
require_once '../config/db.php';
include '../header.php';

// [EN] Require authentication
// [TH] ตรวจสอบสิทธิ์ว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header('Location: /Fanclub/auth/login');
    exit;
}

$user_id = $_SESSION['user_id'];

// [EN] Fetch user info and their total post count
// [TH] ดึงข้อมูลส่วนตัวของผู้ใช้ และนับจำนวนกระทู้ทั้งหมดที่เคยตั้ง
// Get user info and post count
$stmt = $conn->prepare("SELECT username, email, profile_img, (SELECT COUNT(*) FROM posts WHERE user_id = ?) as post_count FROM users WHERE id = ?");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$user_info = $stmt->get_result()->fetch_assoc();

// [EN] Fetch user posts based on selected tab ('all' or 'drafts')
// [TH] ดึงกระทู้ของผู้ใช้ตามหมวดหมู่แท็บที่เลือก (ส่งต่อค่าทั้งหมด หรือเฉพาะแบบร่าง)
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
    <!-- Profile Card (Modern Glassmorphism) -->
    <div
        class="card bg-base-100/50 backdrop-blur-xl shadow-2xl rounded-[2rem] border border-base-300/50 overflow-hidden mb-20 relative group">
        <!-- Animated Gradient Background -->
        <div
            class="absolute top-0 left-0 w-full h-40 bg-gradient-to-r from-primary/20 via-primary/5 to-transparent animate-pulse delay-700">
        </div>
        <div
            class="absolute -top-24 -right-24 w-64 h-64 bg-primary/10 rounded-full blur-3xl group-hover:bg-primary/20 transition-all duration-1000">
        </div>

        <div class="card-body p-8 md:p-14 relative z-10 pt-20">
            <div class="flex flex-col md:flex-row items-center md:items-end gap-10">
                <div class="avatar">
                    <div
                        class="w-36 h-36 md:w-48 md:h-48 rounded-[2rem] ring-[12px] ring-base-100 shadow-2xl overflow-hidden hover:scale-105 transition-transform duration-500">
                        <?php echo getAvatar($user_info['username'], $user_info['profile_img'], 'w-full h-full'); ?>
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left space-y-3">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full text-[10px] font-black uppercase tracking-widest mb-2">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                        </span>
                        Member Profile
                    </div>
                    <h1 class="text-5xl md:text-6xl font-black font-outfit tracking-tighter leading-none">
                        <?php echo htmlspecialchars($user_info['username']); ?>
                    </h1>
                    <p class="text-xl opacity-40 font-bold font-outfit">
                        <?php echo htmlspecialchars($user_info['email']); ?>
                    </p>

                    <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-6">
                        <div
                            class="px-6 py-3 bg-base-200/50 rounded-2xl font-black text-sm flex items-center gap-3 border border-base-300/50">
                            <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span><?php echo $user_info['post_count']; ?> Posts Published</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 w-full md:w-auto mt-8 md:mt-0">
                    <a href="../post/create"
                        class="btn btn-primary h-16 rounded-2xl font-black px-10 text-lg shadow-2xl shadow-primary/30 hover:scale-105 active:scale-95 transition-all">
                        Create New Thread
                    </a>
                    <a href="settings"
                        class="btn btn-ghost h-14 rounded-2xl font-black px-10 border-2 border-base-300 hover:bg-base-200 transition-all">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats & Posts Grid -->
    <div class="space-y-12">
        <div class="flex flex-col md:flex-row md:items-end justify-start gap-6 items-start">
            <div class="flex items-center gap-3">
                <span class="w-2 h-10 bg-primary rounded-full"></span>
                <h2 class="text-3xl md:text-4xl font-black font-outfit uppercase tracking-tight">ประวัติการสร้างกระทู้
                </h2>
            </div>

            <div role="tablist" class="tabs tabs-boxed bg-base-100 rounded-2xl p-1 gap-1">
                <a href="?tab=all" role="tab"
                    class="tab font-black text-sm px-8 <?php echo $tab !== 'drafts' ? 'tab-active' : ''; ?>">ทั้งหมด</a>
                <a href="?tab=drafts" role="tab"
                    class="tab font-black text-sm px-8 <?php echo $tab === 'drafts' ? 'tab-active' : ''; ?>">แบบร่าง</a>
            </div>
        </div>

        <!-- [EN] Posts feed logic & skeleton UI initially visible -->
        <!-- [TH] พื้นที่แสดงรายการกระทู้ และโครงสร้างโหลดข้อมูลหลอก ๆ (Skeleton) ก่อนข้อมูลจริงมา -->
        <div id="posts-container" class="relative">
            <!-- Global Loading Spinner (Initial) -->
            <div id="loading-overlay"
                class="absolute inset-0 z-50 flex items-center justify-center bg-base-100/20 backdrop-blur-sm rounded-3xl transition-opacity duration-500">
                <span class="loading loading-dots loading-lg text-primary"></span>
            </div>

            <!-- Enhanced Skeleton for loading -->
            <div id="skeleton-grid" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php for ($i = 0; $i < 4; $i++): ?>
                    <div
                        class="card bg-base-100 shadow-xl border border-base-300/50 rounded-3xl p-8 bg-gradient-to-b from-base-100 to-base-200/30">
                        <div class="card-body p-0 space-y-6">
                            <div class="flex justify-between items-start">
                                <div class="skeleton h-8 w-2/3 rounded-xl"></div>
                                <div class="skeleton h-6 w-16 rounded-lg"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="skeleton h-4 w-full opacity-60"></div>
                                <div class="skeleton h-4 w-full opacity-60"></div>
                                <div class="skeleton h-4 w-4/5 opacity-60"></div>
                            </div>
                            <div class="skeleton h-48 w-full rounded-2xl"></div>
                            <div class="flex justify-between items-center pt-4 border-t border-base-300/30">
                                <div class="skeleton h-4 w-24 opacity-40"></div>
                                <div class="flex gap-2">
                                    <div class="skeleton h-10 w-20 rounded-xl opacity-60"></div>
                                    <div class="skeleton h-10 w-28 rounded-xl"></div>
                                </div>
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
                            class="card bg-base-100 shadow-xl border border-base-300/50 hover:border-primary/50 hover:shadow-2xl transition-all duration-500 rounded-3xl group overflow-hidden flex flex-col h-full bg-gradient-to-b from-base-100 to-base-200/30">
                            <div class="card-body p-8 flex flex-col h-full">
                                <div class="space-y-5 flex-1">
                                    <div class="flex justify-between items-start gap-4">
                                        <h3
                                            class="text-2xl font-black font-outfit leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </h3>
                                        <div class="shrink-0">
                                            <?php if ($post['status'] === 'draft'): ?>
                                                <span
                                                    class="badge badge-warning font-black text-[10px] uppercase tracking-tighter px-3 py-3 rounded-lg border-none">Draft</span>
                                            <?php else: ?>
                                                <span
                                                    class="badge badge-success font-black text-[10px] uppercase tracking-tighter px-3 py-3 rounded-lg border-none text-white">Live</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <p class="text-base text-base-content/50 font-medium line-clamp-3 leading-relaxed">
                                        <?php echo htmlspecialchars(mb_strimwidth($post['content'], 0, 180, "...")); ?>
                                    </p>

                                    <?php if ($post['image']): ?>
                                        <div
                                            class="w-full h-48 overflow-hidden rounded-2xl border border-base-200/50 mt-2 relative">
                                            <div
                                                class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-500 z-10">
                                            </div>
                                            <img src="/Fanclub/asset/post/<?php echo $post['image']; ?>"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" />
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div
                                    class="mt-8 pt-6 border-t border-base-300/50 flex flex-wrap items-center justify-between gap-4">
                                    <div
                                        class="flex items-center gap-2 opacity-30 font-black text-[10px] uppercase tracking-widest">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <?php
                                        $can_edit = (time() - strtotime($post['created_at'])) <= 3600;
                                        if ($can_edit):
                                            ?>
                                            <a href="../post/edit?id=<?php echo $post['id']; ?>"
                                                class="btn btn-ghost btn-sm rounded-xl font-black text-[11px] uppercase border border-base-300 px-4 h-10 hover:bg-base-200">Edit</a>
                                        <?php endif; ?>

                                        <button onclick="confirmDelete(<?php echo $post['id']; ?>)"
                                            class="btn btn-ghost btn-sm rounded-xl font-black text-[11px] uppercase text-error px-4 h-10 border border-error/10 hover:bg-error/10">Delete</button>

                                        <a href="../post/view?id=<?php echo $post['id']; ?>"
                                            class="btn btn-primary btn-sm rounded-xl px-6 font-black text-[11px] uppercase h-10 shadow-lg shadow-primary/20">Read
                                            Post</a>
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

    // [EN] Show delete confirmation modal
    // [TH] แสดงหน้าต่างยืนยันการลบกระทู้
    function confirmDelete(id) {
        postToDelete = id;
        delete_modal.showModal();
    }

    $(document).ready(function () {
        // [EN] Handle post deletion via AJAX to prevent full page reload initially
        // [TH] เมื่อกดยืนยันลบ ให้ส่งคำสั่งผ่าน AJAX เพื่อลบกระทู้เบื้องหลัง
        $('#btnConfirmDelete').on('click', function () {
            if (postToDelete) {
                $.ajax({
                    url: '../post/delete_post',
                    type: 'POST',
                    data: {
                        post_id: postToDelete,
                        csrf_token: '<?php echo $csrf_token; ?>'
                    },
                    success: function (response) {
                        location.reload();
                    },
                    error: function () {
                        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                    }
                });
            }
        });

        // [EN] Add skeleton loading effect before rendering posts for better UX
        // [TH] ช่วยให้หน้าเว็บดูสมูทขึ้นโดยการแสดงโครงร่าง (Skeleton) และซ่อนเมื่อโหลดข้อมูลเสร็จ
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.getElementById('loading-overlay').style.opacity = '0';
                setTimeout(() => {
                    document.getElementById('loading-overlay').classList.add('hidden');
                    document.getElementById('skeleton-grid').classList.add('hidden');
                    document.getElementById('real-grid').classList.remove('hidden');
                    document.getElementById('real-grid').classList.add('animate-in', 'fade-in', 'slide-in-from-bottom-5', 'duration-700');
                }, 300);
            }, 800);
        });
    });
</script>

<?php include '../components/footer.php'; ?>
<?php
// [EN] Initialize app setup and include common header layout
// [TH] โหลดการตั้งค่าระบบ (Session/DB) และนำเข้าส่วนหัวหน้าเว็บ
require_once __DIR__ . '/config/app_init.php';
$page_title = "Fanclub | Home";
include 'header.php';

// [EN] Fetch posts with Search logic
// [TH] ดึงข้อมูลกระทู้ พร้อมตั้งค่าระบบค้นหาข้อมูล (ส่งผ่านค่า GET)
$search = trim($_GET['q'] ?? '');
$sql = "SELECT p.*, u.username, u.profile_img FROM posts p JOIN users u ON p.user_id = u.id WHERE p.status = 'published'";
if (!empty($search)) {
    // [EN] Add wildcards for search matching title or content
    // [TH] นำคำที่ค้นหามาหาชื่อหัวข้อและเนื้อหาที่ตรงกันบางส่วน (LIKE)
    $search_param = "%$search%";
    $sql .= " AND (p.title LIKE ? OR p.content LIKE ?)";
}
$sql .= " ORDER BY p.created_at DESC";

// [EN] Execute the query with prepared statements
// [TH] นำรันคำสั่ง SQL ด้วย Prepared Statement เพื่อป้องกัน SQL Injection
$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $stmt->bind_param("ss", $search_param, $search_param);
}
$stmt->execute();
$posts_result = $stmt->get_result();

// [EN] Fetch suggested posts (random 3 posts, only published)
// [TH] สุ่มข้อมูลกระทู้แนะนำ 3 อันดับเฉพาะที่เผยแพร่แล้ว
$sql_suggested = "SELECT p.*, u.username, u.profile_img FROM posts p JOIN users u ON p.user_id = u.id WHERE p.status = 'published' ORDER BY RAND() LIMIT 3";
$suggest_stmt = $conn->prepare($sql_suggested);
$suggest_stmt->execute();
$suggested_result = $suggest_stmt->get_result();
$suggest_stmt->close();

// [EN] Community Stats: Count currently active online users (within last 5 minutes)
// [TH] สถิติประชากรของชุมชน: นับจำนวนคนใช้งานออนไลน์ภายใน 5 นาทีล่าสุด
$online_stmt = $conn->prepare("SELECT COUNT(*) as online_count FROM users WHERE last_active > (NOW() - INTERVAL 5 MINUTE)");
$online_stmt->execute();
$online_count = $online_stmt->get_result()->fetch_assoc()['online_count'] ?? 0;
$online_stmt->close();

// [EN] Community Stats: Count total number of posts created
// [TH] สถิติชุมชน: แสดงจำนวนกระทู้ทั้งหมด
$posts_count_stmt = $conn->prepare("SELECT COUNT(*) as total_posts FROM posts");
$posts_count_stmt->execute();
$total_posts = $posts_count_stmt->get_result()->fetch_assoc()['total_posts'] ?? 0;
$posts_count_stmt->close();
?>

<!-- [EN] Main wrapper for the homepage content -->
<!-- [TH] กล่องครอบเนื้อหาหลักของหน้าแรก -->
<div class="container mx-auto px-4 py-12 max-w-6xl">

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- [EN] Show 'Create Post' button only if user is logged in -->
        <!-- [TH] ปุ่มสร้างกระทู้ใหม่ จะแสดงให้เห็นเฉพาะคนที่ล็อกอินแล้ว -->
        <div class="flex justify-end mb-8">
            <a href="post/create" class="btn btn-primary px-10 shadow-xl shadow-primary/30 rounded-full font-bold group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-90 transition-transform"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                สร้างกระทู้ใหม่
            </a>
        </div>
    <?php endif; ?>

    <!-- [EN] Hero Section Welcome Message -->
    <!-- [TH] ข้อความต้อนรับของระบบ (Hero Section) -->
    <header class="text-center mb-16 space-y-4">
        <h1 class="text-6xl font-black text-primary tracking-tight font-outfit">Welcome to Fanclub</h1>
        <p class="text-xl text-base-content/60 max-w-2xl mx-auto">แหล่งรวมความมันส์และการแชร์เรื่องราวที่คุณชื่นชอบ</p>
        <div class="w-24 h-1.5 bg-primary mx-auto rounded-full"></div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- [EN] Main Content (Recent Posts Feed) -->
        <!-- [TH] เนื้อหาหลัก (ฟีดแสดงกระทู้ใหม่ล่าสุด) -->
        <section class="lg:col-span-2">
            <div class="flex items-center gap-3 mb-8">
                <span class="w-2 h-8 bg-primary rounded-full"></span>
                <h2 class="text-3xl font-black font-outfit">กระทู้ล่าสุด</h2>
            </div>

            <div id="posts-container">
                <!-- [EN] Skeleton UI loader (shown initially before JS resolves) -->
                <!-- [TH] เส้นโหลดข้อมูลแบบ Skeleton ให้ผู้ใช้เห็นว่าหน้าเว็บกำลังโหลดทำงาน (ดีต่อ UX) -->
                <div id="skeleton-list" class="space-y-6">
                    <?php for ($i = 0; $i < 3; $i++): ?>
                        <div class="card bg-base-100 shadow-sm border border-base-300 p-6 rounded-2xl">
                            <div class="flex items-start gap-4">
                                <div class="skeleton h-14 w-14 shrink-0 rounded-2xl"></div>
                                <div class="flex-1 space-y-4">
                                    <div class="skeleton h-8 w-3/4"></div>
                                    <div class="skeleton h-4 w-full"></div>
                                    <div class="skeleton h-4 w-1/2"></div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- [EN] Actual Feed Content Container -->
                <!-- [TH] กล่องเนื้อหากระทู้ตามจริง -->
                <div id="real-content" class="hidden space-y-6">
                    <?php if ($posts_result && $posts_result->num_rows > 0): ?>
                        <?php while ($post = $posts_result->fetch_assoc()): ?>
                            <!-- [EN] Post Card Component -->
                            <!-- [TH] กล่องส่วนประกอบของแต่ละกระทู้ -->
                            <div
                                class="card bg-base-100 shadow-sm border border-base-300 hover:border-primary hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 rounded-2xl overflow-hidden group">
                                <div class="card-body p-6">
                                    <div class="flex items-start gap-5">
                                        <!-- [EN] Author Avatar -->
                                        <!-- [TH] รูปโปรไฟล์ของเจ้าของกระทู้ -->
                                        <?php echo getAvatar($post['username'], $post['profile_img'], 'w-14 h-14 ring-2 ring-primary/20 ring-offset-base-100 ring-offset-2'); ?>

                                        <div class="flex-1">
                                            <!-- [EN] Post Title linked to Post View Page -->
                                            <!-- [TH] หัวข้อกระทู้ จะลิงก์ไปหน้าดูรายละเอียดกระทู้ -->
                                            <a href="post/view?id=<?php echo $post['id']; ?>"
                                                class="group-hover:text-primary transition-colors">
                                                <h3 class="font-bold text-xl mb-2">
                                                    <?php echo htmlspecialchars($post['title']); ?>
                                                </h3>
                                            </a>
                                            <!-- [EN] Post Content Excerpt -->
                                            <!-- [TH] เนื้อหากระทู้ฉบับย่อให้คนเข้าใจคร่าวๆ -->
                                            <p class="text-base text-base-content/70 line-clamp-2 leading-relaxed">
                                                <?php echo htmlspecialchars(mb_strimwidth($post['content'], 0, 200, "...")); ?>
                                            </p>

                                            <!-- [EN] Post Meta Info (Author and Date) -->
                                            <!-- [TH] ข้อมูลประกอบกระทู้ (เช่นชื่อคนเขียนและวันที่เขียน) -->
                                            <div class="flex items-center gap-6 mt-4 opacity-50 text-sm font-medium">
                                                <span class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    <?php echo htmlspecialchars($post['username']); ?>
                                                </span>
                                                <span class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- [EN] Show image thumbnail if post contains an image -->
                                        <!-- [TH] แสดงรูปหน้าปกกระทู้ถ้าเกิดเจ้าของกระทู้เลือกอัปโหลดรูป -->
                                        <?php if ($post['image']): ?>
                                            <div class="hidden sm:block w-32 h-32 overflow-hidden rounded-2xl">
                                                <img src="/Fanclub/asset/post/<?php echo $post['image']; ?>"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <!-- [EN] Display this if no posts match search query or DB is empty -->
                        <!-- [TH] แสดงส่วนนี้เมื่อหากระทู้ที่ต้องการหาไม่เจอ -->
                        <div class="text-center py-32 bg-base-100 rounded-2xl border-2 border-dashed border-base-300">
                            <div class="opacity-30 mb-4 flex justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <p class="text-xl font-bold opacity-50">ไม่พบกระทู้ที่ตรงกับการค้นหา</p>
                            <a href="index" class="btn btn-ghost btn-sm mt-4">ล้างการค้นหา</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- [EN] Right Sidebar Area -->
        <!-- [TH] พื้นที่แถบข้างด้านขวา (Sidebar) -->
        <aside class="space-y-10">
            <!-- [EN] Suggested Topic Widget -->
            <!-- [TH] วิจิท (Widget) กล่องรวมกระทู้แนะนำ -->
            <div class="card bg-primary text-primary-content shadow-2xl p-6 rounded-2xl overflow-hidden relative group">
                <div class="relative z-10">
                    <h2 class="text-2xl font-black mb-2 font-outfit">🔥 แนะนำสำหรับคุณ</h2>
                    <p class="text-sm opacity-80 mb-6">กระทู้ที่น่าสนใจที่คุณอาจจะชอบงานของเรา</p>

                    <div class="space-y-4">
                        <?php if ($suggested_result && $suggested_result->num_rows > 0): ?>
                            <?php while ($s_post = $suggested_result->fetch_assoc()): ?>
                                <!-- [EN] Short link to suggested post -->
                                <!-- [TH] ลิงก์ย่อสำหรับเข้าดูกระทู้แนะนำแต่ละอัน -->
                                <a href="post/view?id=<?php echo $s_post['id']; ?>"
                                    class="flex items-center gap-4 p-3 bg-white/10 hover:bg-white/20 rounded-2xl transition-all border border-white/10">
                                    <?php echo getAvatar($s_post['username'], $s_post['profile_img'], 'w-10 h-10'); ?>
                                    <h4 class="font-bold text-sm line-clamp-1 flex-1">
                                        <?php echo htmlspecialchars($s_post['title']); ?>
                                    </h4>
                                </a>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- [EN] Abstract Background Patterns -->
                <!-- [TH] ลูกเล่นกราฟิกพื้นหลังให้ดูสวยงาม -->
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-12 -top-12 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
            </div>

            <!-- [EN] Community Information Stats Widget -->
            <!-- [TH] วิจิทแสดงภาพรวมสถานะสถิติชุมชน -->
            <div class="card bg-base-100 border border-base-300 shadow-sm p-6 rounded-2xl">
                <h3 class="font-black text-lg mb-4 font-outfit uppercase tracking-wider opacity-60">Status Community
                </h3>
                <div class="space-y-4">
                    <!-- [EN] Online users count -->
                    <!-- [TH] จำนวนคนกำลังใช้งานตอนนี้ -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">จำนวนผู้ใช้งาน</span>
                        <div class="badge badge-success badge-sm gap-1"><?php echo $online_count; ?> Active</div>
                    </div>
                    <!-- [EN] Total system posts -->
                    <!-- [TH] จำนวนกระทู้ทั้งหมดในระบบ -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">จำนวนกระทู้</span>
                        <span class="font-bold"><?php echo number_format($total_posts); ?></span>
                    </div>
                </div>
                <!-- [EN] Mock Discord Community link -->
                <!-- [TH] ปุ่มม็อกอัพ ชวนคนเข้า Discord ของแฟนคลับ -->
                <div class="divider"></div>
                <button class="btn btn-block btn-outline rounded-2xl">เข้าร่วม Discord</button>
            </div>
        </aside>
    </div>
</div>

<script>
    // [EN] Hide skeleton loader and show real content with smooth animation
    // [TH] เมื่อโหลดเนื้อหาเสร็จ หน่วงเวลาเล็กน้อยแล้วซ่อนกล่อง Skeleton เปลี่ยนเป็นเนื้อหาจริงพร้อมอนิเมชัน
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.getElementById('skeleton-list').classList.add('hidden');
            document.getElementById('real-content').classList.remove('hidden');
            document.getElementById('real-content').classList.add('animate-in', 'fade-in', 'slide-in-from-bottom-5', 'duration-700');
        }, 800);
    });
</script>

<?php include 'components/footer.php'; ?>
<?php
require_once 'config/db.php';
include 'header.php';

// Fetch posts
$sql = "SELECT p.*, u.username, u.profile_img FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC";
$posts_result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanclub</title>
</head>

<body>
    <!-- include Navbar มา -->
    <?php include 'components/navbar.php'; ?>
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="card-actions justify-end">
            <a href="account/edit.php">
                <button class="btn btn-primary px-8 shadow-lg shadow-primary/20">
                    <span class="text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        สร้างกระทู้ใหม่
                    </span>
                </button>
            </a>
        </div>
        <!-- หัวข้อกระทู้ -->
        <header class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-primary mb-2">Welcome to Fanclub</h1>
            <p class="text-base-content/60">แหล่งรวมเรื่องราวและกระทู้ที่คุณสนใจ</p>
        </header>
        <!-- กระทู้ -->
        <section class="mb-12">
            <div class="flex items-center mb-6">
                <div class="h-8 w-2 bg-primary rounded-full mr-3"></div>
                <h2 class="text-2xl font-bold">กระทู้แนะนำ</h2>
            </div>
            <!-- กรา์ดกระทู้แนะนำ -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                        <figure class="px-4 pt-4">
                            <!-- รูปผู้ใช้งาน -->
                            <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                                alt="Shoes" class="rounded-xl object-cover h-48 w-full" />
                        </figure>
                        <div class="card-body">
                            <!-- หัวข้อ -->
                            <h2 class="card-title text-secondary">Card Title!</h2>
                            <!-- เนื้อหาย่อ -->
                            <p class="text-sm text-base-content/70">เนื้อหากระทู้แบบย่อที่น่าสนใจ
                                ช่วยดึงดูดให้คนคลิกเข้ามาอ่านรายละเอียดเพิ่มเติม...</p>
                            <div class="card-actions justify-end mt-4">
                                <button class="btn btn-primary btn-sm">อ่านต่อ</button>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </section>
        <!-- กระทู้ล่าสุด -->
        <section>
            <div class="flex items-center mb-6">
                <div class="h-8 w-2 bg-secondary rounded-full mr-3"></div>
                <h2 class="text-2xl font-bold">กระทู้ล่าสุด</h2>
            </div>

            <div class="space-y-4">
                <?php if ($posts_result && $posts_result->num_rows > 0): ?>
                    <?php while ($post = $posts_result->fetch_assoc()): ?>
                        <div
                            class="card bg-base-100 shadow-sm border border-base-300 hover:border-primary transition-all duration-300 cursor-pointer group">
                            <div class="card-body p-5">
                                <div class="flex items-start gap-4">
                                    <div class="avatar">
                                        <div class="w-12 h-12 rounded-full ring ring-primary ring-offset-2">
                                            <img src="asset/<?php echo $post['profile_img'] ?? 'default_profile.png'; ?>"
                                                onerror="this.src='https://img.daisyui.com/images/profile/demo/superperson@192.webp'" />
                                        </div>
                                    </div>

                                    <div class="flex-1">
                                        <a href="chart.php?id=<?php echo $post['id']; ?>"
                                            class="group-hover:text-primary transition-colors">
                                            <h3 class="font-bold text-lg mb-1"><?php echo htmlspecialchars($post['title']); ?>
                                            </h3>
                                        </a>
                                        <p class="text-sm text-base-content/70 line-clamp-2">
                                            <?php echo htmlspecialchars(mb_strimwidth($post['content'], 0, 200, "...")); ?>
                                        </p>
                                        <div class="flex items-center gap-4 mt-3 text-xs text-base-content/50">
                                            <span class="flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <?php echo htmlspecialchars($post['username']); ?>
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <?php echo date('d M Y H:i', strtotime($post['created_at'])); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php if ($post['image']): ?>
                                        <div class="hidden sm:block w-24 h-24 overflow-hidden rounded-lg">
                                            <img src="asset/<?php echo $post['image']; ?>" class="w-full h-full object-cover" />
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-20 bg-base-100 rounded-box border-2 border-dashed border-base-300">
                        <p class="text-base-content/50">ยังไม่มีกระทู้ในขณะนี้ เริ่มต้นสร้างกระทู้แรกของคุณ!</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </div>
    <!-- include footer มา -->
    <?php include 'components/footer.php'; ?>
</body>

</html>
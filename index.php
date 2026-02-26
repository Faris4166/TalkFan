<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanclub</title>
</head>

<body>
    <?php include 'components/navbar.php'; ?>
    <div class="container mx-auto px-4 py-8 max-w-6xl">

        <header class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-primary mb-2">Welcome to Fanclub</h1>
            <p class="text-base-content/60">แหล่งรวมเรื่องราวและกระทู้ที่คุณสนใจ</p>
        </header>

        <section class="mb-12">
            <div class="flex items-center mb-6">
                <div class="h-8 w-2 bg-primary rounded-full mr-3"></div>
                <h2 class="text-2xl font-bold">กระทู้แนะนำ</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                        <figure class="px-4 pt-4">
                            <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                                alt="Shoes" class="rounded-xl object-cover h-48 w-full" />
                        </figure>
                        <div class="card-body">
                            <h2 class="card-title text-secondary">Card Title!</h2>
                            <p class="text-sm text-base-content/70">เนื้อหากระทู้แบบย่อที่น่าสนใจ ช่วยดึงดูดให้คนคลิกเข้ามาอ่านรายละเอียดเพิ่มเติม...</p>
                            <div class="card-actions justify-end mt-4">
                                <button class="btn btn-primary btn-sm">อ่านต่อ</button>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </section>

        <section>
            <div class="flex items-center mb-6">
                <div class="h-8 w-2 bg-secondary rounded-full mr-3"></div>
                <h2 class="text-2xl font-bold">กระทู้ล่าสุด</h2>
            </div>

            <div class="space-y-4">
                <?php for ($i = 0; $i < 4; $i++): ?>
                    <div class="card bg-base-100 shadow-sm border border-base-300 hover:border-primary transition-colors cursor-pointer">
                        <div class="card-body p-4">
                            <div class="flex items-start gap-4">
                                <div class="avatar">
                                    <div class="w-12 h-12 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                        <img src="https://img.daisyui.com/images/profile/demo/superperson@192.webp" />
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <a href="#" class="hover:text-primary transition-colors">
                                        <h3 class="font-bold text-lg mb-1">หัวข้อกระทู้: Lorem ipsum dolor sit amet consectetur?</h3>
                                    </a>
                                    <p class="text-sm text-base-content/70 line-clamp-1">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates officia quo, quibusdam vero quidem aperiam enim nemo suscipit...
                                    </p>
                                    <div class="flex gap-4 mt-2 text-xs text-base-content/50">
                                        <span>โดย: User007</span>
                                        <span>2 ชม. ที่แล้ว</span>
                                        <span class="badge badge-ghost badge-sm">Discussion</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </section>

    </div>
    <?php include 'components/footer.php'; ?>
</body>

</html>
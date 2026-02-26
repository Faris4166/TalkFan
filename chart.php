<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanclub Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- include Navbar มา -->
    <?php include 'components/navbar.php'; ?>
    <div class="container mx-auto max-w-4xl p-4 space-y-6">
        <!-- Card กระทู้ -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body gap-4">
                <div class="flex items-center gap-3">
                    <!-- รูปผู้ใช้งาน -->
                    <div class="avatar">
                        <div class="w-12 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                            <img src="https://img.daisyui.com/images/profile/demo/superperson@192.webp" />
                        </div>
                    </div>
                    <!-- ชื่อและเวลาที่โพสต์ -->
                    <div>
                        <h2 class="text-xl font-bold">John Doe</h2>
                        <span class="text-xs text-gray-400">Posted 2 hours ago</span>
                    </div>
                </div>

                <div class="prose max-w-none">
                    <!-- หัวข้อ -->
                    <h1 class="text-2xl font-bold ">Why do we use it?</h1>
                    <div class="divider"></div>
                    <!-- เนื้อหา -->
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Earum nesciunt similique, iste incidunt fuga ipsam, impedit
                        molestias consectetur quidem eligendi facilis repellendus non
                        saepe animi!
                    </p>
                    <figure class="mt-4">
                        <img src="../Fanclub/asset/img-1.jpg" alt="Content Image" class="rounded-box w-full object-cover max-h-96" />
                    </figure>
                </div>
            </div>
        </div>
        <!-- ผู้ใช้ตอบ -->
        <div class="card bg-base-100 shadow-md">
            <div class="card-body flex-row gap-4 items-start">
                <div class="flex flex-row justify-start items-start gap-4">
                    <!-- รูปผู้ใช้งาน -->
                    <div class="avatar hidden sm:block">
                        <div class="w-10 rounded-full">
                            <img src="https://img.daisyui.com/images/profile/demo/superperson@192.webp" />
                        </div>
                    </div>
                    <!-- ชื่อผู้ใช้งาน -->
                    <p>Faris</p>
                </div>
                <div class="form-control w-full gap-3">
                    <textarea class="textarea textarea-bordered focus:textarea-primary w-full h-24" placeholder="เขียนความคิดเห็นของคุณ..."></textarea>
                    <div class="card-actions justify-end">
                        <button class="btn btn-primary px-8">
                            <p class="text-white">
                                Submit
                            </p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Comments -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <!-- จำนวนความคิดเห็นทั้งหมด -->
                <h3 class="card-title mb-4">
                    Comments <div class="badge badge-secondary">3</div>
                </h3>
                <!-- กล่องแสดงความคิดเห็นแต่ละอัน -->
                <div class="space-y-4">
                    <div class="chat chat-start">
                        <!-- รูปผู้ใช้งาน -->
                        <div class="chat-image avatar">
                            <div class="w-10 rounded-full">
                                <img src="https://img.daisyui.com/images/profile/demo/kenobee@192.webp" />
                            </div>
                        </div>
                        <!-- ชื่อผู้ใช้งานและเนื้อหาความคิดเห็น -->
                        <div class="chat-header text-xs opacity-50 ml-1">Obi-Wan Kenobi</div>
                        <div class="chat-bubble chat-bubble-info">It was said that you would, destroy the Sith, not join them.</div>
                    </div>

                    <div class="chat chat-start">
                        <div class="chat-image avatar">
                            <div class="w-10 rounded-full">
                                <img src="https://img.daisyui.com/images/profile/demo/kenobee@192.webp" />
                            </div>
                        </div>
                        <div class="chat-bubble chat-bubble-info">It was you who would bring balance to the Force</div>
                    </div>

                    <div class="chat chat-start">
                        <div class="chat-image avatar">
                            <div class="w-10 rounded-full">
                                <img src="https://img.daisyui.com/images/profile/demo/kenobee@192.webp" />
                            </div>
                        </div>
                        <div class="chat-bubble chat-bubble-info">Not leave it in Darkness</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- include footer มา -->
    <?php include 'components/footer.php'; ?>
</body>

</html>
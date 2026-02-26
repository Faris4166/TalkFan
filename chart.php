<!-- ดึงข้อมูลUIจากdaisyui&tailwindcssมาใช้ในหน้าindex.php -->
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanclub Chart</title>
</head>

<body class="m-2 p-2">
    <!-- จัดการแสดงผลของกราฟในหน้านี้ -->
    <div class="flex flex-col justify-start items-start gap-4">

        <!-- เจ้าของกระทู้ -->
        <div class="flex flex-col justify-center items-start gap-4">
            <!-- Avatar+name -->
            <div class="flex flex-row justify-start items-start gap-4">
                <!-- รูปavatar -->
                <div class="avatar">
                    <div class="w-8 rounded">
                        <img
                            src="https://img.daisyui.com/images/profile/demo/superperson@192.webp"
                            alt="Tailwind-CSS-Avatar-component" />
                    </div>
                </div>
                <!-- ชื่อ -->
                <p class="text-lg font-semibold">John Doe</p>
            </div>
            <!-- กล่องเนื้อหาต่างๆ -->
            <div class="bg-blue-100 border border-blue-300 rounded p-3 w-full">
                <!-- หัวข้อ -->
                <h1 class="text-3xl font-bold">
                    Why do we use it?
                </h1>
                <hr>
                <!-- เนื้อหา -->
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Earum nesciunt similique, iste incidunt fuga ipsam, impedit
                    molestias consectetur quidem eligendi facilis repellendus non
                    saepe animi! Corrupti mollitia tempore nam unde?
                </p>
                <!-- รูปภาพประกอบ -->
                <img src="../Fanclub/asset/img-1.jpg" alt="คำอธิบายรูปภาพ" width="500" height="300">
            </div>
        </div>

        <!-- ตัวเราเอง -->
        <div class="bg-blue-100 border border-blue-300 rounded p-3 w-auto flex flex-row justify-start items-start gap-4">
            <div class="avatar">
                <div class="w-8 rounded">
                    <img
                        src="https://img.daisyui.com/images/profile/demo/superperson@192.webp"
                        alt="Tailwind-CSS-Avatar-component" />
                </div>
            </div>
            <div class="card-actions justify-end">
                <div class="w-full flex flex-col justify-start items-start gap-4">
                    <textarea class="textarea" placeholder="Bio" disabled></textarea>
                    <button class="btn">Default</button>
                </div>
            </div>
        </div>

        <!-- ความคิดเห็น -->
        <div class="bg-blue-100 border border-blue-300 rounded p-3 w-full flex flex-col justify-start items-start gap-4">
            <h1 class="text-3xl font-bold">
                Comment
            </h1>

            <div class="chat chat-start">
                <div class="chat-image avatar">
                    <div class="w-10 rounded-full">
                        <img
                            alt="Tailwind CSS chat bubble component"
                            src="https://img.daisyui.com/images/profile/demo/kenobee@192.webp" />
                    </div>
                </div>
                <div class="chat-bubble">It was said that you would, destroy the Sith, not join them.</div>
            </div>
            <div class="chat chat-start">
                <div class="chat-image avatar">
                    <div class="w-10 rounded-full">
                        <img
                            alt="Tailwind CSS chat bubble component"
                            src="https://img.daisyui.com/images/profile/demo/kenobee@192.webp" />
                    </div>
                </div>
                <div class="chat-bubble">It was you who would bring balance to the Force</div>
            </div>
            <div class="chat chat-start">
                <div class="chat-image avatar">
                    <div class="w-10 rounded-full">
                        <img
                            alt="Tailwind CSS chat bubble component"
                            src="https://img.daisyui.com/images/profile/demo/kenobee@192.webp" />
                    </div>
                </div>
                <div class="chat-bubble">Not leave it in Darkness</div>
            </div>
        </div>

    </div>


</body>

</html>
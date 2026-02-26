<?php
$page_title = "View Post | Fanclub";
require_once '../config/db.php';
include '../header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: ../index');
    exit;
}

// Fetch post with user info
$stmt = $conn->prepare("SELECT p.*, u.username, u.profile_img FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    echo "<div class='container mx-auto p-20 text-center'><h1 class='text-4xl font-black'>Post not found</h1><a href='../index' class='btn btn-primary mt-8'>Back to Home</a></div>";
    include '../components/footer.php';
    exit;
}

// Fetch comments
$c_stmt = $conn->prepare("SELECT c.*, u.username, u.profile_img FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at ASC");
$c_stmt->bind_param("i", $id);
$c_stmt->execute();
$comments_result = $c_stmt->get_result();
?>

<div class="container mx-auto px-4 py-12 max-w-4xl">
    <!-- Post Header -->
    <div class="mb-10 space-y-6">
        <a href="../index" class="btn btn-ghost btn-sm gap-2 opacity-50 hover:opacity-100 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to feed
        </a>
        <h1 class="text-4xl md:text-5xl font-black font-outfit leading-tight">
            <?php echo htmlspecialchars($post['title']); ?>
        </h1>

        <div class="flex items-center gap-4 p-4 bg-base-100 rounded-3xl border border-base-300">
            <?php echo getAvatar($post['username'], $post['profile_img'], 'w-12 h-12 ring-2 ring-primary/20'); ?>
            <div>
                <p class="font-black text-primary"><?php echo htmlspecialchars($post['username']); ?></p>
                <p class="text-xs opacity-50 font-bold uppercase tracking-wider">
                    <?php echo date('d F Y • H:i', strtotime($post['created_at'])); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Post Content -->
    <article class="card bg-base-100 shadow-xl rounded-[2.5rem] border border-base-300 overflow-hidden mb-12">
        <?php if ($post['image']): ?>
            <figure class="w-full">
                <img src="/Fanclub/asset/post/<?php echo $post['image']; ?>" class="w-full object-cover max-h-[600px]" />
            </figure>
        <?php endif; ?>
        <div class="card-body p-8 md:p-12">
            <div class="prose prose-lg max-w-none text-base-content/80 leading-relaxed font-medium">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>
        </div>
    </article>

    <!-- Comments Section -->
    <section class="space-y-8">
        <div class="flex items-center gap-3">
            <span class="w-2 h-8 bg-primary rounded-full"></span>
            <h2 class="text-3xl font-black font-outfit">ความคิดเห็น (<?php echo $comments_result->num_rows; ?>)</h2>
        </div>

        <!-- Comment Form -->
        <div class="card bg-base-100 shadow-xl border border-base-300 rounded-3xl overflow-hidden">
            <div class="card-body p-8">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form id="commentForm" class="space-y-4">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <div class="flex items-center gap-4 mb-2">
                            <?php echo getAvatar($_SESSION['username'], $_SESSION['profile_img'] ?? 'default_profile.png', 'w-10 h-10'); ?>
                            <span class="font-bold"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </div>
                        <textarea name="comment"
                            class="textarea textarea-bordered focus:textarea-primary w-full h-32 rounded-2xl text-base font-medium"
                            placeholder="มาร่วมพูดคุยกัน..." required></textarea>
                        <div class="flex justify-end">
                            <button type="submit" id="btnSubmitComment"
                                class="btn btn-primary px-10 rounded-2xl font-black shadow-lg shadow-primary/20">
                                ส่งความคิดเห็น
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="text-center py-8 bg-base-200/50 rounded-2xl border-2 border-dashed border-base-300">
                        <h3 class="font-black text-xl mb-3 font-outfit">เข้าสู่ระบบเพื่อร่วมคอมเมนต์</h3>
                        <div class="flex justify-center gap-3 mt-4">
                            <a href="/Fanclub/auth/login"
                                class="btn btn-primary rounded-xl px-8 shadow-lg shadow-primary/20">Login</a>
                            <a href="/Fanclub/auth/register" class="btn btn-ghost rounded-xl px-8">Sign Up</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Comments List -->
        <div class="space-y-6" id="comments-list">
            <?php
            if ($comments_result && $comments_result->num_rows > 0):
                $comments = [];
                while ($c = $comments_result->fetch_assoc()) {
                    $comments[] = $c;
                }

                function renderComment($comment, $all_comments, $depth = 0, $parent_username = null)
                {
                    $margin = $depth > 0 ? 'ml-6 md:ml-12 border-l-2 border-base-300/50 pl-4 md:pl-8' : '';

                    // Count children
                    $child_count = 0;
                    foreach ($all_comments as $child) {
                        if ($child['parent_id'] == $comment['id'])
                            $child_count++;
                    }
                    ?>
                    <div class="relative <?php echo $depth > 0 ? 'mt-4' : 'mt-8'; ?>"
                        id="comment-<?php echo $comment['id']; ?>">
                        <!-- Small dot for threaded visual -->
                        <?php if ($depth > 0): ?>
                            <div class="absolute -left-[1.1rem] top-7 w-4 h-0.5 bg-base-300/50"></div>
                        <?php endif; ?>

                        <div
                            class="card bg-base-100 shadow-sm border border-base-300 rounded-2xl group hover:shadow-md transition-all <?php echo $margin; ?>">
                            <div class="card-body p-6">
                                <div class="flex items-start gap-4">
                                    <div class="shrink-0">
                                        <?php echo getAvatar($comment['username'], $comment['profile_img'], 'w-10 h-10'); ?>
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <div class="flex flex-col">
                                                <h4 class="font-black text-sm text-primary flex items-center gap-2">
                                                    <?php echo htmlspecialchars($comment['username']); ?>
                                                    <?php if ($depth > 0 && $parent_username): ?>
                                                        <span class="badge badge-sm badge-ghost font-bold opacity-50 px-2 py-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="3" d="M3 10h10a8 8 0 018 8v2M3 10l5 5m-5-5l5-5" />
                                                            </svg>
                                                            <?php echo htmlspecialchars($parent_username); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </h4>
                                            </div>
                                            <span class="text-[10px] uppercase font-black opacity-30 tracking-widest">
                                                <?php echo date('d M Y • H:i', strtotime($comment['created_at'])); ?>
                                            </span>
                                        </div>
                                        <p class="text-base font-medium opacity-80 leading-relaxed">
                                            <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                                        </p>

                                        <div class="pt-2 flex items-center gap-3">
                                            <button
                                                onclick="showReplyForm(<?php echo $comment['id']; ?>, '<?php echo htmlspecialchars($comment['username']); ?>', '<?php echo $comment['profile_img']; ?>')"
                                                class="btn btn-ghost btn-xs h-8 rounded-xl font-bold opacity-40 hover:opacity-100 hover:bg-primary/10 hover:text-primary transition-all px-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M3 10h10a8 8 0 018 8v2M3 10l5 5m-5-5l5-5" />
                                                </svg>
                                                ตอบกลับ
                                            </button>

                                            <?php if ($child_count > 0): ?>
                                                <button onclick="toggleReplies(<?php echo $comment['id']; ?>)"
                                                    id="btn-toggle-<?php echo $comment['id']; ?>"
                                                    class="btn btn-ghost btn-xs h-8 rounded-xl font-bold text-primary bg-primary/5 hover:bg-primary/10 transition-all px-3">
                                                    <span class="flex items-center gap-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                        ดูการตอบกลับ (<?php echo $child_count; ?>)
                                                    </span>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($child_count > 0): ?>
                            <div id="replies-<?php echo $comment['id']; ?>" class="hidden">
                                <?php
                                // Render children
                                foreach ($all_comments as $child) {
                                    if ($child['parent_id'] == $comment['id']) {
                                        renderComment($child, $all_comments, $depth + 1, $comment['username']);
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                }

                // Render top-level comments first
                foreach ($comments as $comment) {
                    if ($comment['parent_id'] === null) {
                        renderComment($comment, $comments);
                    }
                }
                ?>
            <?php else: ?>
                <p class="text-center py-10 opacity-30 font-bold uppercase tracking-[0.2em]">ยังไม่มีคนมาเม้นเลย</p>
            <?php endif; ?>
        </div>
    </section>
</div>

<!-- Reply Modal Form (Hidden) -->
<dialog id="reply_modal" class="modal">
    <div class="modal-box rounded-3xl p-0 overflow-hidden max-w-lg border border-base-300 shadow-2xl">
        <!-- Header -->
        <div class="bg-base-200 p-6 flex items-center justify-between border-b border-base-300">
            <div class="flex items-center gap-4">
                <div id="reply_avatar_container"></div>
                <div>
                    <h3 class="font-black text-xl font-outfit leading-tight">เขียนคำตอบ</h3>
                    <p class="text-xs font-bold opacity-40 uppercase tracking-wider">ตอบกลับคุณ @<span
                            id="reply_to_name"></span></p>
                </div>
            </div>
            <form method="dialog">
                <button class="btn btn-ghost btn-circle btn-sm">✕</button>
            </form>
        </div>

        <form id="replyForm" class="p-8 space-y-6">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="parent_id" id="parent_id_input" value="">

            <div class="relative">
                <textarea name="comment"
                    class="textarea textarea-bordered focus:textarea-primary w-full h-48 rounded-2xl text-lg font-medium p-6 bg-base-100 transition-all border-2"
                    placeholder="แชร์ความคิดเห็นของคุณที่นี่..." required></textarea>
                <div class="absolute bottom-4 right-4 opacity-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="reply_modal.close()"
                    class="btn btn-ghost h-14 rounded-2xl font-bold border border-base-300">ยกเลิก</button>
                <button type="submit" id="btnSubmitReply"
                    class="btn btn-primary h-14 rounded-2xl font-black text-lg shadow-xl shadow-primary/20">
                    ส่งคำตอบ
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function showReplyForm(parentId, username, userImg) {
        <?php if (!isset($_SESSION['user_id'])): ?>
            window.location.href = '/Fanclub/auth/login';
            return;
        <?php endif; ?>
        $('#parent_id_input').val(parentId);
        $('#reply_to_name').text(username);

        // Use a simple JS implementation for the modal avatar to avoid extra requests
        $('#reply_avatar_container').html(`<div class="avatar placeholder"><div class="bg-primary text-primary-content rounded-xl w-12"><span class="text-xl font-black">${username.charAt(0).toUpperCase()}</span></div></div>`);

        reply_modal.showModal();
    }

    function toggleReplies(commentId) {
        const repliesDiv = $(`#replies-${commentId}`);
        const btn = $(`#btn-toggle-${commentId}`);
        const isHidden = repliesDiv.hasClass('hidden');

        if (isHidden) {
            repliesDiv.removeClass('hidden').addClass('animate-in fade-in slide-in-from-top-2 duration-300');
            btn.find('span').html(`
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                ปิดการตอบกลับ
            `);
        } else {
            repliesDiv.addClass('hidden');
            const count = repliesDiv.children().length;
            btn.find('span').html(`
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                ดูการตอบกลับ (${count})
            `);
        }
    }

    $(document).ready(function () {
        $('#commentForm, #replyForm').on('submit', function (e) {
            e.preventDefault();
            const form = $(this);
            const isReply = form.attr('id') === 'replyForm';
            const btn = isReply ? $('#btnSubmitReply') : $('#btnSubmitComment');

            btn.prop('disabled', true).html('<span class="loading loading-spinner loading-sm"></span>');

            $.ajax({
                url: 'save_comment_handler',
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        location.reload();
                    } else {
                        alert(res.message);
                        btn.prop('disabled', false).text(isReply ? 'ส่งคำตอบ' : 'ส่งความคิดเห็น');
                    }
                },
                error: function () {
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                    btn.prop('disabled', false).text(isReply ? 'ส่งคำตอบ' : 'ส่งความคิดเห็น');
                }
            });
        });
    });
</script>

<?php include '../components/footer.php'; ?>
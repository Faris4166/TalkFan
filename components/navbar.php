<nav class="sticky top-0 z-[100] bg-base-100/80 backdrop-blur-xl border-b border-base-200">
  <div class="navbar container mx-auto px-4 h-20">
    <div class="flex-1">
      <a href="/Fanclub/index" class="group flex items-center gap-3">
        <div class="w-10 h-10">
          <img src="/Fanclub/asset/logo.svg" alt="">
        </div>
        <span
          class="text-2xl font-black font-outfit tracking-tighter text-base-content group-hover:text-primary transition-colors">Fanclub</span>
      </a>
    </div>

    <div class="flex gap-6 items-center">
      <!-- Search -->
      <form action="/Fanclub/index" method="GET" class="hidden md:flex relative group">
        <input type="text" name="q" placeholder="ค้นหาอะไรบางอย่าง..."
          class="input input-bordered h-11 w-64 rounded-xl pl-11 focus:input-primary transition-all border-base-300 font-medium"
          value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" />
        <svg xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5 absolute left-4 top-1/2 -translate-y-1/2 opacity-30 group-focus-within:text-primary group-focus-within:opacity-100 transition-all"
          fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </form>

      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="flex items-center gap-4">
          <div class="hidden sm:flex flex-col items-end leading-none">
            <span class="text-sm font-black"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
          </div>

          <div class="dropdown dropdown-end">
            <div tabindex="0" role="button"
              class="btn btn-ghost btn-circle ring-2 ring-primary/20 ring-offset-2 ring-offset-base-100 transition-all hover:ring-primary/50">
              <?php echo getAvatar($_SESSION['username'], $_SESSION['profile_img'], 'w-10'); ?>
              </div>
              <ul tabindex="-1"
                class="menu dropdown-content bg-base-100 rounded-2xl mt-4 w-60 p-3 shadow-2xl border border-base-200 z-[110] animate-in fade-in zoom-in-95 duration-200">
                <li class="menu-title px-4 py-3 text-xs font-black uppercase tracking-widest opacity-40">Your Account</li>
                <li><a href="/Fanclub/user/my_posts" class="h-12 rounded-xl font-bold flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-50" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" />
                    </svg>
                    กระทู้ของฉัน
                  </a></li>
                <li><a href="/Fanclub/user/settings" class="h-12 rounded-xl font-bold flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-50" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    ตั้งค่าบัญชี
                  </a></li>
                <div class="divider my-1 opacity-10"></div>
                <li><a href="javascript:void(0)" onclick="logout_modal.showModal()"
                    class="h-12 rounded-xl font-black text-error flex items-center gap-3 hover:bg-error/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    ออกจากระบบ
                  </a></li>
              </ul>
            </div>
          </div>
      <?php else: ?>
          <div class="flex gap-2">
            <a href="/Fanclub/auth/login" class="btn btn-ghost rounded-xl font-bold">Sign In</a>
            <a href="/Fanclub/auth/register"
              class="btn btn-primary rounded-xl px-6 font-black shadow-lg shadow-primary/20">Sign Up</a>
          </div>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Logout Confirmation Modal -->
<dialog id="logout_modal" class="modal">
  <div class="modal-box rounded-2xl p-8">
    <h3 class="font-black text-2xl text-error font-outfit mb-4">ยืนยันการออกจากระบบ?</h3>
    <p class="text-base-content/60 font-medium leading-relaxed">
      ข้อมูลเซสชันของคุณจะถูกล้างและคุณต้องเข้าสู่ระบบใหม่อีกครั้งเพื่อใช้งานฟีเจอร์ต่างๆ</p>
    <div class="modal-action gap-3 mt-8">
      <form method="dialog" class="flex-1">
        <button class="btn btn-block rounded-2xl font-bold">ยกเลิก</button>
      </form>
      <a href="/Fanclub/auth/logout"
        class="btn btn-error flex-1 rounded-2xl font-black shadow-lg shadow-error/20">ยืนยันการออก</a>
    </div>
  </div>
</dialog>
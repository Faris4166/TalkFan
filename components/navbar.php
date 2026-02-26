<div class="navbar bg-base-100 shadow-sm px-4">
  <div class="flex-1">
    <a href="/Fanclub/index.php" class="btn btn-ghost text-xl font-black text-primary">Fanclub</a>
  </div>
  <div class="flex gap-4 items-center">
    <div class="hidden md:flex">
      <input type="text" placeholder="ค้นหากระทู้..." class="input input-bordered w-24 md:w-auto input-sm" />
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
      <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar border-2 border-primary">
          <div class="w-10 rounded-full">
            <img alt="Profile Image" src="/Fanclub/asset/<?php echo $_SESSION['profile_img'] ?? 'default_profile.png'; ?>"
              onerror="this.src='https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp'" />
          </div>
        </div>
        <ul tabindex="-1"
          class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow-xl border border-base-200">
          <li class="menu-title px-4 py-2 opacity-50">สวัสดี,
            <?php echo htmlspecialchars($_SESSION['username']); ?>
          </li>
          <li><a href="/Fanclub/account/settings.php">ตั้งค่าบัญชี</a></li>
          <li><a href="/Fanclub/logout.php" class="text-error">ออกจากระบบ</a></li>
        </ul>
      </div>
    <?php else: ?>
      <div class="flex gap-2">
        <a href="/Fanclub/account/login.php" class="btn btn-ghost btn-sm">เข้าสู่ระบบ</a>
        <a href="/Fanclub/account/register.php" class="btn btn-primary btn-sm">สมัครสมาชิก</a>
      </div>
    <?php endif; ?>
  </div>
</div>
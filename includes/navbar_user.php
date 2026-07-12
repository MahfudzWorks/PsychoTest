<?php
// Pastikan sesi sudah aktif
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Koneksi database (sama seperti yang dipakai di halaman lain)
include __DIR__ . "/../config/database.php";

// Ambil data dasar dari sesi
$fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
$user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

// Ambil foto profil dari database
$profile_pic = "";
if ($user_id > 0) {
  $query = mysqli_query($conn, "SELECT profile_pic FROM users WHERE id = '$user_id' LIMIT 1");
  $data = mysqli_fetch_assoc($query);
  if (!empty($data['profile_pic'])) {
    // Jalur foto: ada di folder /user/profiles/
    $profile_pic = "/user/profiles/" . $data['profile_pic'];
  }
}

// Tentukan link dasbor
$dashboardLink = ($role === 'admin') ? '/admin/dashboard.php' : '/user/dashboard.php';
?>

<nav class="bg-white/90 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-20">

      <!-- Logo & Area Aplikasi -->
      <div class="flex items-center gap-8">
        <a href="<?= $dashboardLink ?>" class="flex items-center gap-2.5 group">
          <div class="bg-indigo-600 text-white p-2.5 rounded-xl shadow-md shadow-indigo-100 group-hover:bg-indigo-700 transition-colors">
            <i class="fa-solid fa-brain text-xl"></i>
          </div>
          <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">
            PsychoTest <span class="text-xs font-semibold px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-md border border-indigo-100 ml-1.5 align-middle">App</span>
          </span>
        </a>

        <!-- Menu Internal Jalur Cepat (Desktop) -->
        <div class="hidden md:flex items-center gap-6 border-l border-slate-200 pl-8">
          <a href="<?= $dashboardLink ?>" class="text-sm font-semibold text-indigo-600">Halaman Test</a>
        </div>
      </div>

      <!-- Sisi Kanan: Dropdown Profile (Desktop) -->
      <div class="hidden md:flex items-center">
        <div class="relative">
          <button id="user-menu-btn" class="flex items-center gap-3 px-3 py-2 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-all focus:outline-none group">

            <!-- ✅ Tampilkan Foto Profil jika ada, jika tidak pakai huruf awal -->
            <?php if (!empty($profile_pic)): ?>
              <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-sm shadow-indigo-200 overflow-hidden">
                <img src="<?= htmlspecialchars($profile_pic) ?>" alt="Foto Profil" class="w-full h-full object-cover">
              </div>
            <?php else: ?>
              <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-sm shadow-indigo-200 uppercase">
                <?= substr(htmlspecialchars($fullname), 0, 1) ?>
              </div>
            <?php endif; ?>

            <div class="text-left hidden sm:block">
              <p class="text-xs font-medium text-slate-400 capitalize leading-none mb-0.5"><?= htmlspecialchars($role) ?></p>
              <p class="text-sm font-bold text-slate-700 leading-none"><?= htmlspecialchars($fullname) ?></p>
            </div>
            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200" id="user-arrow"></i>
          </button>

          <!-- Dropdown Panel -->
          <div id="user-dropdown" class="hidden absolute right-0 mt-3 w-52 bg-white border border-slate-100 rounded-2xl shadow-xl py-2 z-50 transform origin-top-right transition-all">
            <a href="/user/profile.php" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:bg-indigo-50/50 hover:text-indigo-600 transition-colors">
              <i class="fa-solid fa-circle-user text-slate-400 text-base"></i> Profil Saya
            </a>
            <a href="/user/riwayat.php" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:bg-indigo-50/50 hover:text-indigo-600 transition-colors">
              <i class="fa-solid fa-clock-rotate-left text-slate-400 text-base"></i> Riwayat Tes
            </a>
            <div class="border-t border-slate-100 my-1"></div>
            <a href="/auth/logout.php" class="flex items-center gap-3 px-4 py-3 text-sm text-rose-600 hover:bg-rose-50 transition-colors font-medium">
              <i class="fa-solid fa-right-from-bracket text-base"></i> Keluar Aplikasi
            </a>
          </div>
        </div>
      </div>

      <!-- Tombol Menu Mobile -->
      <div class="md:hidden flex items-center">
        <button id="app-mobile-btn" class="text-slate-500 p-2 rounded-xl hover:bg-slate-50">
          <i id="app-mobile-icon" class="fa-solid fa-bars text-xl"></i>
        </button>
      </div>

    </div>
  </div>

  <!-- Menu Mobile Internal -->
  <div id="app-mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white shadow-lg absolute w-full left-0">
    <div class="px-4 py-4 space-y-2">
      <div class="px-4 py-3 bg-slate-50 rounded-xl mb-2 flex items-center gap-3">
        <!-- ✅ Foto profil juga ditampilkan di menu HP -->
        <?php if (!empty($profile_pic)): ?>
          <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center overflow-hidden">
            <img src="<?= htmlspecialchars($profile_pic) ?>" alt="Foto Profil" class="w-full h-full object-cover">
          </div>
        <?php else: ?>
          <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center uppercase">
            <?= substr(htmlspecialchars($fullname), 0, 1) ?>
          </div>
        <?php endif; ?>
        <div>
          <p class="text-xs font-medium text-slate-400 uppercase"><?= htmlspecialchars($role) ?></p>
          <p class="text-base font-bold text-slate-800"><?= htmlspecialchars($fullname) ?></p>
        </div>
      </div>
      <a href="<?= $dashboardLink ?>" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">Dasbor Utama</a>
      <a href="/user/profile.php" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">Profil Saya</a>
      <a href="/user/riwayat.php" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">Riwayat Tes</a>
      <a href="/index.php" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-500 hover:bg-slate-50">Kembali ke Landing Page</a>
      <div class="border-t border-slate-100 pt-2">
        <a href="/auth/logout.php" class="w-full text-center inline-flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 py-3 rounded-xl text-base font-semibold">
          <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
        </a>
      </div>
    </div>
  </div>

  <script>
    // Script Dropdown Desktop
    const userBtn = document.getElementById('user-menu-btn');
    const userDropdown = document.getElementById('user-dropdown');
    const userArrow = document.getElementById('user-arrow');

    if (userBtn && userDropdown) {
      userBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdown.classList.toggle('hidden');
        userArrow.classList.toggle('rotate-180');
      });
      window.addEventListener('click', () => {
        userDropdown.classList.add('hidden');
        userArrow.classList.remove('rotate-180');
      });
    }

    // Script Mobile Menu
    const appMobileBtn = document.getElementById('app-mobile-btn');
    const appMobileMenu = document.getElementById('app-mobile-menu');
    const appMobileIcon = document.getElementById('app-mobile-icon');

    if (appMobileBtn) {
      appMobileBtn.addEventListener('click', () => {
        appMobileMenu.classList.toggle('hidden');
        appMobileIcon.className = appMobileMenu.classList.contains('hidden') ?
          'fa-solid fa-bars text-xl' : 'fa-solid fa-xmark text-xl';
      });
    }
  </script>
</nav>
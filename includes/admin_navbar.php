<nav class="fixed top-0 left-64 right-0 h-16 bg-white shadow-sm border-b border-gray-100 z-40 transition-all duration-300">
  <div class="flex items-center justify-between h-full px-8">

    <!-- Bagian Kiri -->
    <div class="flex items-center gap-6">
      <h2 class="text-xl md:text-2xl font-bold text-gray-800 tracking-tight">
        Dashboard
      </h2>

      <!-- Kotak Pencarian -->
      <div class="relative hidden md:block">
        <input
          type="text"
          placeholder="Cari sesuatu..."
          class="w-72 lg:w-80 pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all duration-200">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-gray-400"></i>
      </div>
    </div>

    <!-- Bagian Kanan -->
    <div class="flex items-center gap-4 lg:gap-6">
      <!-- Notifikasi -->
      <button class="relative w-10 h-10 rounded-xl hover:bg-gray-100 transition-all duration-200 flex items-center justify-center group">
        <i class="fa-regular fa-bell text-lg text-gray-600 group-hover:text-gray-800"></i>
        <span class="absolute -top-1 -right-1 bg-danger text-white text-[10px] font-medium w-5 h-5 rounded-full flex items-center justify-center shadow-sm">
          3
        </span>
      </button>

      <!-- Pesan -->
      <button class="relative w-10 h-10 rounded-xl hover:bg-gray-100 transition-all duration-200 flex items-center justify-center group">
        <i class="fa-regular fa-envelope text-lg text-gray-600 group-hover:text-gray-800"></i>
        <span class="absolute -top-1 -right-1 bg-secondary text-white text-[10px] font-medium w-5 h-5 rounded-full flex items-center justify-center shadow-sm">
          5
        </span>
      </button>

      <!-- Menu Profil -->
      <div class="relative group cursor-pointer">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition-all duration-200">
          <img
            src="../assets/images/default.png"
            alt="Profil"
            class="w-10 h-10 rounded-full border-2 border-gray-200 object-cover shadow-sm">
          <div class="hidden md:block text-left">
            <h4 class="font-semibold text-gray-800 text-sm">
              <?= $_SESSION['fullname'] ?? "Administrator"; ?>
            </h4>
            <p class="text-xs text-gray-500">
              Administrator
            </p>
          </div>
          <i class="fa-solid fa-chevron-down text-xs text-gray-400 group-hover:text-gray-600 transition-transform duration-200 group-hover:rotate-180"></i>
        </div>

        <!-- Dropdown Menu -->
        <div class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden">
          <a href="profile.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
            <i class="fa-regular fa-user text-primary"></i>
            <span class="text-gray-700 font-medium">Profil Saya</span>
          </a>
          <a href="settings.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
            <i class="fa-solid fa-gear text-success"></i>
            <span class="text-gray-700 font-medium">Pengaturan</span>
          </a>
          <div class="border-t border-gray-100 my-1"></div>
          <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-colors">
            <i class="fa-solid fa-right-from-bracket text-danger"></i>
            <span class="text-red-600 font-medium">Keluar</span>
          </a>
        </div>
      </div>
    </div>

  </div>
</nav>
<nav class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-20">

      <!-- Logo Aplikasi -->
      <a href="index.php" class="flex items-center gap-2.5 group">
        <div class="bg-indigo-600 text-white p-2.5 rounded-xl shadow-md shadow-indigo-100 group-hover:bg-indigo-700 transition-colors">
          <i class="fa-solid fa-brain text-xl"></i>
        </div>
        <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">
          PsychoTest
        </span>
      </a>

      <!-- Menu Utama (Desktop) -->
      <div class="hidden md:flex items-center gap-8">
        <a href="index.php" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Home</a>
        <a href="about.php" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">About</a>
        <a href="kontak.php" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Kontak</a>
        <a href="auth/login.php" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-all">
          Masuk <i class="fa-solid fa-right-to-bracket text-xs ml-1.5"></i>
        </a>
      </div>

      <!-- Tombol Hamburger (Mobile Only) -->
      <div class="md:hidden flex items-center">
        <button id="mobile-menu-button" type="button" class="text-slate-500 hover:text-slate-700 focus:outline-none p-2 rounded-xl hover:bg-slate-50 transition-colors" aria-label="Toggle Menu">
          <i id="menu-icon" class="fa-solid fa-bars text-xl"></i>
        </button>
      </div>

    </div>
  </div>

  <!-- Menu Drawer (Mobile Dropdown) -->
  <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white/95 backdrop-blur-md transition-all absolute w-full left-0 shadow-lg">
    <div class="px-4 pt-3 pb-6 space-y-3">
      <a href="index.php" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">Home</a>
      <a href="about.php" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">About</a>
      <a href="kontak.php" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">Kontak</a>
      <div class="pt-2 px-4">
        <a href="auth/login.php" class="w-full text-center inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl text-base font-semibold transition-colors shadow-sm">
          Masuk <i class="fa-solid fa-right-to-bracket text-xs ml-1.5"></i>
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- JavaScript Hamburger Menu -->
<script>
  const btn = document.getElementById('mobile-menu-button');
  const menu = document.getElementById('mobile-menu');
  const icon = document.getElementById('menu-icon');

  btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
    // Ganti ikon ikon bars/close (X) secara dinamis
    if (menu.classList.contains('hidden')) {
      icon.className = 'fa-solid fa-bars text-xl';
    } else {
      icon.className = 'fa-solid fa-xmark text-xl';
    }
  });
</script>
<?php
// Ambil jalur relatif dari folder admin
$currentPath = $_SERVER['PHP_SELF'];
$basePath = '/admin/';
$relativePath = '';

// Ambil bagian setelah /admin/
if (strpos($currentPath, $basePath) !== false) {
  $relativePath = substr($currentPath, strpos($currentPath, $basePath) + strlen($basePath));
}

// Fungsi cek menu aktif: menyala untuk satu folder penuh
function isActive($folder)
{
  global $relativePath;
  if ($folder === 'dashboard.php') {
    return $relativePath === 'dashboard.php'
      ? "bg-primary text-white shadow-md"
      : "text-gray-300 hover:bg-gray-800 hover:text-white";
  }
  // Menyala jika di dalam folder tersebut
  return str_starts_with($relativePath, $folder . '/')
    ? "bg-primary text-white shadow-md"
    : "text-gray-300 hover:bg-gray-800 hover:text-white";
}
?>

<!-- Sidebar -->
<aside class="fixed top-0 left-0 w-64 h-screen bg-gray-900 text-white shadow-xl z-50 flex flex-col">

  <!-- Logo & Judul -->
  <div class="h-16 flex items-center justify-center border-b border-gray-800/50 bg-gray-900">
    <!-- Jalur dari akar proyek, aman di semua posisi -->
    <a href="/PsychoTest/admin/dashboard.php" class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary to-secondary flex items-center justify-center shadow-md">
        <i class="fa-solid fa-brain text-lg"></i>
      </div>
      <div>
        <h2 class="font-bold text-base">PsychoTest</h2>
        <p class="text-xs text-gray-400">Panel Admin</p>
      </div>
    </a>
  </div>

  <!-- Daftar Menu -->
  <div class="py-6 px-3 overflow-y-auto sidebar-scroll flex-1">
    <p class="text-xs text-gray-500 uppercase tracking-wider px-3 mb-2 font-medium">
      Menu Utama
    </p>

    <ul class="space-y-1.5">
      <li>
        <a
          href="/PsychoTest/admin/dashboard.php"
          class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 <?= isActive('dashboard.php') ?>">
          <i class="fa-solid fa-chart-line w-5 text-center"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a
          href="/PsychoTest/admin/users/users.php"
          class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 <?= isActive('users') ?>">
          <i class="fa-solid fa-users w-5 text-center"></i>
          <span>Kelola Peserta</span>
        </a>
      </li>
      <li>
        <a
          href="/PsychoTest/admin/tests/tests.php"
          class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 <?= isActive('tests') ?>">
          <i class="fa-solid fa-file-circle-check w-5 text-center"></i>
          <span>Jenis Tes</span>
        </a>
      </li>
      <li>
        <a
          href="/PsychoTest/admin/questions/questions.php"
          class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 <?= isActive('questions') ?>">
          <i class="fa-solid fa-circle-question w-5 text-center"></i>
          <span>Bank Soal</span>
        </a>
      </li>
      <li>
        <a
          href="/PsychoTest/admin/payments/payments.php"
          class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 <?= isActive('payments') ?>">
          <i class="fa-solid fa-credit-card w-5 text-center"></i>
          <span>Pembayaran</span>
        </a>
      </li>
      <li>
        <a
          href="/PsychoTest/admin/results/results.php"
          class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 <?= isActive('results') ?>">
          <i class="fa-solid fa-square-poll-vertical w-5 text-center"></i>
          <span>Hasil Tes</span>
        </a>
      </li>
      <li>
        <a
          href="/PsychoTest/admin/reports/reports.php"
          class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 <?= isActive('reports') ?>">
          <i class="fa-solid fa-file-lines w-5 text-center"></i>
          <span>Laporan</span>
        </a>
      </li>
    </ul>

    <!-- Bagian Akun -->
    <div class="border-t border-gray-800/50 mt-8 pt-6">
      <p class="text-xs text-gray-500 uppercase tracking-wider px-3 mb-2 font-medium">
        Akun
      </p>
      <ul class="space-y-1.5">
        <li>
          <a
            href="/PsychoTest/admin/profile.php"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 <?= isActive('profile.php') ?>">
            <i class="fa-solid fa-user w-5 text-center"></i>
            <span>Profil</span>
          </a>
        </li>
        <li>
          <a
            href="/PsychoTest/auth/logout.php"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-400 hover:bg-red-600/10 hover:text-red-500 transition-all duration-200">
            <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
            <span>Keluar</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

</aside>
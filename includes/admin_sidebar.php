<?php
$currentPage = basename($_SERVER['PHP_SELF']);

function isActive($page)
{
  global $currentPage;
  return $currentPage == $page
    ? "bg-blue-600 text-white shadow-lg"
    : "text-gray-300 hover:bg-gray-700 hover:text-white";
}
?>

<!-- Sidebar -->
<aside class="fixed top-0 left-0 w-64 h-screen bg-gray-900 text-white shadow-xl z-50">

  <!-- Logo -->
  <div class="h-16 flex items-center justify-center border-b border-gray-700">

    <a href="dashboard.php" class="flex items-center gap-3">

      <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center">

        <i class="fa-solid fa-brain text-xl"></i>

      </div>

      <div>

        <h2 class="font-bold text-lg">
          PsychoTest
        </h2>

        <small class="text-gray-400">
          Admin Panel
        </small>

      </div>

    </a>

  </div>

  <!-- Menu -->
  <div class="py-6 overflow-y-auto h-[calc(100vh-64px)]">

    <p class="text-xs text-gray-500 uppercase px-6 mb-3 tracking-widest">
      Main Menu
    </p>

    <ul class="space-y-2 px-4">

      <li>
        <a href="dashboard.php"
          class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isActive('dashboard.php') ?>">

          <i class="fa-solid fa-chart-line w-5 text-center"></i>

          Dashboard

        </a>
      </li>

      <li>
        <a href="users.php"
          class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isActive('users.php') ?>">

          <i class="fa-solid fa-users w-5 text-center"></i>

          Kelola Peserta

        </a>
      </li>

      <li>
        <a href="tests.php"
          class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isActive('tests.php') ?>">

          <i class="fa-solid fa-file-circle-check w-5 text-center"></i>

          Jenis Tes

        </a>
      </li>

      <li>
        <a href="questions.php"
          class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isActive('questions.php') ?>">

          <i class="fa-solid fa-circle-question w-5 text-center"></i>

          Bank Soal

        </a>
      </li>

      <li>
        <a href="payments.php"
          class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isActive('payments.php') ?>">

          <i class="fa-solid fa-credit-card w-5 text-center"></i>

          Pembayaran

        </a>
      </li>

      <li>
        <a href="results.php"
          class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isActive('results.php') ?>">

          <i class="fa-solid fa-square-poll-vertical w-5 text-center"></i>

          Hasil Tes

        </a>
      </li>

      <li>
        <a href="reports.php"
          class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isActive('reports.php') ?>">

          <i class="fa-solid fa-file-lines w-5 text-center"></i>

          Laporan

        </a>
      </li>

    </ul>

    <div class="border-t border-gray-700 mt-8 pt-6">

      <p class="text-xs text-gray-500 uppercase px-6 mb-3 tracking-widest">
        Account
      </p>

      <ul class="space-y-2 px-4">

        <li>
          <a href="profile.php"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isActive('profile.php') ?>">

            <i class="fa-solid fa-user w-5 text-center"></i>

            Profil

          </a>
        </li>

        <li>

          <a href="../auth/logout.php"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-600 hover:text-white transition">

            <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>

            Logout

          </a>

        </li>

      </ul>

    </div>

  </div>

</aside>
<?php
include '../config/session.php';

if ($_SESSION['role'] != "admin") {

  header("Location: ../auth/login.php");

  exit;
}

include '../config/session.php';
include '../includes/header.php';

// nanti ambil dari session login admin
$admin = "Administrator";
?>

<div class="min-h-screen flex bg-gray-100">

  <!-- Sidebar -->
  <aside class="w-64 bg-slate-900 text-white">

    <div class="p-6 border-b border-slate-700">

      <h1 class="text-2xl font-bold">
        🧠 PsychoTest
      </h1>

      <p class="text-sm text-slate-400 mt-1">
        Admin Panel
      </p>

    </div>

    <nav class="mt-6">

      <a href="index.php"
        class="block px-6 py-3 bg-blue-600 hover:bg-blue-700">
        📊 Dashboard
      </a>

      <a href="users.php"
        class="block px-6 py-3 hover:bg-slate-800">
        👥 Data User
      </a>

      <a href="categories.php"
        class="block px-6 py-3 hover:bg-slate-800">
        📚 Jenis Tes
      </a>

      <a href="questions.php"
        class="block px-6 py-3 hover:bg-slate-800">
        ❓ Soal
      </a>

      <a href="options.php"
        class="block px-6 py-3 hover:bg-slate-800">
        ✅ Pilihan Jawaban
      </a>

      <a href="payments.php"
        class="block px-6 py-3 hover:bg-slate-800">
        💳 Pembayaran
      </a>

      <a href="results.php"
        class="block px-6 py-3 hover:bg-slate-800">
        📈 Hasil Tes
      </a>

      <a href="../auth/logout.php"
        class="block px-6 py-3 text-red-400 hover:bg-red-600 hover:text-white">
        🚪 Logout
      </a>

    </nav>

  </aside>

  <!-- Content -->
  <main class="flex-1">

    <!-- Header -->
    <div class="bg-white shadow px-8 py-5 flex justify-between items-center">

      <div>

        <h2 class="text-2xl font-bold">
          Dashboard
        </h2>

        <p class="text-gray-500">
          Selamat datang, <?= $admin ?>
        </p>

      </div>

      <div>

        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm">
          Online
        </span>

      </div>

    </div>

    <!-- Statistik -->
    <div class="p-8">

      <div class="grid md:grid-cols-4 gap-6">

        <div class="bg-white rounded-xl shadow p-6">

          <p class="text-gray-500">
            Total User
          </p>

          <h2 class="text-4xl font-bold mt-4 text-blue-600">
            25
          </h2>

        </div>

        <div class="bg-white rounded-xl shadow p-6">

          <p class="text-gray-500">
            Jenis Tes
          </p>

          <h2 class="text-4xl font-bold mt-4 text-green-600">
            5
          </h2>

        </div>

        <div class="bg-white rounded-xl shadow p-6">

          <p class="text-gray-500">
            Soal
          </p>

          <h2 class="text-4xl font-bold mt-4 text-yellow-500">
            250
          </h2>

        </div>

        <div class="bg-white rounded-xl shadow p-6">

          <p class="text-gray-500">
            Pembayaran Pending
          </p>

          <h2 class="text-4xl font-bold mt-4 text-red-500">
            8
          </h2>

        </div>

      </div>

      <!-- Menu Cepat -->
      <div class="mt-10">

        <h3 class="text-xl font-bold mb-5">
          Menu Cepat
        </h3>

        <div class="grid md:grid-cols-3 gap-6">

          <a href="categories.php"
            class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">

            <div class="text-5xl">
              📚
            </div>

            <h4 class="font-bold mt-4">
              Kelola Jenis Tes
            </h4>

          </a>

          <a href="questions.php"
            class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">

            <div class="text-5xl">
              ❓
            </div>

            <h4 class="font-bold mt-4">
              Kelola Soal
            </h4>

          </a>

          <a href="payments.php"
            class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">

            <div class="text-5xl">
              💳
            </div>

            <h4 class="font-bold mt-4">
              Verifikasi Pembayaran
            </h4>

          </a>

        </div>

      </div>

      <!-- Aktivitas -->
      <div class="mt-10 bg-white rounded-xl shadow">

        <div class="border-b px-6 py-4">

          <h3 class="font-bold">
            Aktivitas Terbaru
          </h3>

        </div>

        <table class="w-full">

          <thead class="bg-gray-50">

            <tr>

              <th class="text-left p-4">Nama</th>

              <th class="text-left p-4">Aktivitas</th>

              <th class="text-left p-4">Tanggal</th>

            </tr>

          </thead>

          <tbody>

            <tr class="border-t">

              <td class="p-4">Mahfudz</td>

              <td class="p-4">Mengerjakan DISC</td>

              <td class="p-4">04 Juli 2026</td>

            </tr>

            <tr class="border-t">

              <td class="p-4">Budi</td>

              <td class="p-4">Upload Pembayaran</td>

              <td class="p-4">04 Juli 2026</td>

            </tr>

          </tbody>

        </table>

      </div>

    </div>

  </main>

</div>

<?php include '../includes/footer.php'; ?>
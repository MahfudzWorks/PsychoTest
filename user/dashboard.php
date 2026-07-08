<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

$fullname = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard User | PsychoTest</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-gray-100 font-[Poppins]">

  <!-- Navbar -->
  <nav class="bg-white shadow">

    <div class="max-w-7xl mx-auto px-6">

      <div class="flex justify-between items-center h-20">

        <a href="#" class="text-3xl font-bold text-blue-600">
          🧠 PsychoTest
        </a>

        <div class="flex items-center gap-4">

          <span class="text-gray-700 font-medium">
            Halo, <b><?= htmlspecialchars($fullname) ?></b>
          </span>

          <a href="../auth/logout.php"
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
            Logout
          </a>

        </div>

      </div>

    </div>

  </nav>

  <!-- Hero -->
  <section class="max-w-7xl mx-auto px-6 py-10">

    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl p-10 text-white shadow-xl">

      <h1 class="text-4xl font-bold">

        Selamat Datang 👋

      </h1>

      <h2 class="text-2xl mt-2 font-semibold">

        <?= htmlspecialchars($username) ?>

      </h2>

      <p class="mt-5 text-blue-100 text-lg">

        Selamat datang di platform PsychoTest.
        Pastikan Anda berada di tempat yang nyaman dan memiliki koneksi internet yang stabil sebelum memulai tes.

      </p>

      <a href="start.php"
        class="inline-flex items-center gap-3 mt-8 bg-white text-blue-700 px-8 py-4 rounded-xl text-lg font-bold hover:scale-105 transition">

        <i class="fa-solid fa-play"></i>

        Mulai Tes Sekarang

      </a>

    </div>

  </section>

  <!-- Informasi -->
  <section class="max-w-7xl mx-auto px-6">

    <div class="grid md:grid-cols-4 gap-6">

      <div class="bg-white rounded-2xl shadow p-6">

        <i class="fa-solid fa-brain text-4xl text-blue-600"></i>

        <h3 class="font-bold mt-4 text-lg">
          Jenis Tes
        </h3>

        <p class="text-gray-500 mt-2">
          Psychological Test
        </p>

      </div>

      <div class="bg-white rounded-2xl shadow p-6">

        <i class="fa-solid fa-file-lines text-4xl text-green-600"></i>

        <h3 class="font-bold mt-4 text-lg">
          Jumlah Soal
        </h3>

        <p class="text-gray-500 mt-2">
          100 Soal
        </p>

      </div>

      <div class="bg-white rounded-2xl shadow p-6">

        <i class="fa-solid fa-clock text-4xl text-orange-500"></i>

        <h3 class="font-bold mt-4 text-lg">
          Durasi
        </h3>

        <p class="text-gray-500 mt-2">
          90 Menit
        </p>

      </div>

      <div class="bg-white rounded-2xl shadow p-6">

        <i class="fa-solid fa-circle-check text-4xl text-purple-600"></i>

        <h3 class="font-bold mt-4 text-lg">
          Kesempatan
        </h3>

        <p class="text-gray-500 mt-2">
          1 Kali Tes
        </p>

      </div>

    </div>

  </section>

  <!-- Petunjuk -->
  <section class="max-w-7xl mx-auto px-6 py-10">

    <div class="bg-white rounded-3xl shadow p-8">

      <h2 class="text-3xl font-bold mb-6">

        Petunjuk Sebelum Tes

      </h2>

      <ul class="space-y-4 text-gray-700 text-lg">

        <li>
          ✅ Pastikan koneksi internet stabil.
        </li>

        <li>
          ✅ Kerjakan di tempat yang tenang.
        </li>

        <li>
          ✅ Jangan menutup browser selama tes berlangsung.
        </li>

        <li>
          ✅ Jawaban akan tersimpan secara otomatis.
        </li>

        <li>
          ✅ Setelah tes selesai, lakukan pembayaran melalui QRIS.
        </li>

        <li>
          ✅ Hasil tes dapat dilihat setelah pembayaran diverifikasi Admin.
        </li>

      </ul>

      <div class="mt-10">

        <a href="start.php"
          class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl text-lg font-semibold">

          <i class="fa-solid fa-play mr-2"></i>

          Mulai Tes

        </a>

      </div>

    </div>

  </section>

  <!-- Footer -->
  <footer class="bg-white border-t mt-10">

    <div class="max-w-7xl mx-auto px-6 py-6 text-center text-gray-500">

      © <?= date('Y') ?> PsychoTest. All Rights Reserved.

    </div>

  </footer>

</body>

</html>
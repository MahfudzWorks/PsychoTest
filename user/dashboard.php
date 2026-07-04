<?php
include '../includes/header.php';
include '../includes/navbar.php';

// nanti diambil dari session
$nama = "Mahfudz";
?>

<section class="bg-gray-100 min-h-screen py-10">

  <div class="max-w-7xl mx-auto px-5">

    <div class="mb-8">

      <h1 class="text-3xl font-bold">

        Selamat Datang, <?= $nama ?> 👋

      </h1>

      <p class="text-gray-500 mt-2">

        Semoga sukses mengerjakan psikotes hari ini.

      </p>

    </div>

    <!-- Card -->

    <div class="grid md:grid-cols-4 gap-6">

      <div class="bg-white rounded-xl shadow p-6">

        <p class="text-gray-500">Status Tes</p>

        <h2 class="text-2xl font-bold mt-3 text-green-600">

          Belum Dikerjakan

        </h2>

      </div>

      <div class="bg-white rounded-xl shadow p-6">

        <p class="text-gray-500">

          Pembayaran

        </p>

        <h2 class="text-2xl font-bold mt-3 text-red-500">

          Belum Bayar

        </h2>

      </div>

      <div class="bg-white rounded-xl shadow p-6">

        <p class="text-gray-500">

          Verifikasi

        </p>

        <h2 class="text-2xl font-bold mt-3">

          Pending

        </h2>

      </div>

      <div class="bg-white rounded-xl shadow p-6">

        <p class="text-gray-500">

          Hasil Tes

        </p>

        <h2 class="text-2xl font-bold mt-3">

          Terkunci 🔒

        </h2>

      </div>

    </div>

    <!-- Menu -->

    <div class="grid md:grid-cols-3 gap-6 mt-10">

      <a href="tes.php"
        class="bg-white rounded-xl shadow p-8 hover:shadow-lg">

        <div class="text-5xl">

          📝

        </div>

        <h3 class="text-xl font-bold mt-4">

          Mulai Psikotes

        </h3>

        <p class="text-gray-500 mt-3">

          Kerjakan seluruh soal psikotes.

        </p>

      </a>

      <a href="pembayaran.php"
        class="bg-white rounded-xl shadow p-8">

        <div class="text-5xl">

          💳

        </div>

        <h3 class="text-xl font-bold mt-4">

          Pembayaran QRIS

        </h3>

        <p class="text-gray-500 mt-3">

          Upload bukti pembayaran.

        </p>

      </a>

      <a href="hasil.php"
        class="bg-white rounded-xl shadow p-8">

        <div class="text-5xl">

          📊

        </div>

        <h3 class="text-xl font-bold mt-4">

          Lihat Hasil

        </h3>

        <p class="text-gray-500 mt-3">

          Hasil aktif setelah diverifikasi admin.

        </p>

      </a>

    </div>

  </div>

</section>

<?php include '../includes/footer.php'; ?>
<?php

session_start();

require "../config/database.php";

if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

$fullname = $_SESSION['fullname'];

// sementara
$test_status = "Selesai";
$payment_status = "Belum Dibayar";
?>

<!DOCTYPE html>
<html lang="id">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Tes Selesai</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-gray-100 font-[Poppins]">

  <div class="min-h-screen flex items-center justify-center p-6">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-10">

      <div class="text-center">

        <div class="w-24 h-24 rounded-full bg-green-100 flex items-center justify-center mx-auto">

          <i class="fa-solid fa-circle-check text-5xl text-green-600"></i>

        </div>

        <h1 class="text-4xl font-bold mt-6">

          Tes Berhasil Diselesaikan

        </h1>

        <p class="text-gray-500 mt-3">

          Terima kasih,
          <b><?= htmlspecialchars($fullname) ?></b>

          telah menyelesaikan tes psikologi.

        </p>

      </div>

      <div class="mt-10 space-y-5">

        <!-- Status Tes -->

        <div class="border rounded-xl p-5 flex justify-between items-center">

          <div>

            <h3 class="font-semibold text-lg">

              Status Tes

            </h3>

            <p class="text-gray-500 text-sm">

              Proses pengerjaan tes.

            </p>

          </div>

          <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold">

            ✅ <?= $test_status ?>

          </span>

        </div>

        <!-- Status Pembayaran -->

        <div class="border rounded-xl p-5 flex justify-between items-center">

          <div>

            <h3 class="font-semibold text-lg">

              Status Pembayaran

            </h3>

            <p class="text-gray-500 text-sm">

              Pembayaran diperlukan untuk melihat hasil tes.

            </p>

          </div>

          <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full font-semibold">

            ❌ <?= $payment_status ?>

          </span>

        </div>

      </div>

      <div class="mt-10 bg-blue-50 border border-blue-200 rounded-xl p-5">

        <div class="flex gap-3">

          <i class="fa-solid fa-circle-info text-blue-600 text-2xl mt-1"></i>

          <div>

            <h3 class="font-semibold text-blue-700">

              Informasi

            </h3>

            <p class="text-gray-600 mt-2 leading-7">

              Untuk melihat hasil psikotes, silakan melakukan pembayaran terlebih dahulu.
              Setelah pembayaran diverifikasi oleh admin, tombol
              <b>Lihat Hasil</b> akan otomatis tersedia.

            </p>

          </div>

        </div>

      </div>

      <div class="mt-10 flex justify-center gap-4">

        <a href="dashboard.php"
          class="px-6 py-3 rounded-xl border hover:bg-gray-100">

          Kembali ke Dashboard

        </a>

        <a href="payment.php"
          class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold">

          Bayar Sekarang

        </a>

      </div>

    </div>

  </div>

</body>

</html>
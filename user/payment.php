<?php

session_start();

require "../config/database.php";

if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

$fullname = $_SESSION['fullname'];

// sementara
$price = 25000;

?>

<!DOCTYPE html>
<html lang="id">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Pembayaran</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-gray-100 font-[Poppins]">

  <div class="max-w-6xl mx-auto py-12 px-6">

    <div class="text-center mb-10">

      <h1 class="text-4xl font-bold text-gray-800">

        Pembayaran Tes Psikologi

      </h1>

      <p class="text-gray-500 mt-2">

        Silakan lakukan pembayaran untuk melihat hasil tes.

      </p>

    </div>

    <div class="grid md:grid-cols-2 gap-8">

      <!-- Detail Pembayaran -->

      <div class="bg-white rounded-2xl shadow-lg p-8">

        <h2 class="text-2xl font-bold mb-6">

          Detail Pembayaran

        </h2>

        <div class="space-y-5">

          <div class="flex justify-between">

            <span>Nama Peserta</span>

            <strong><?= htmlspecialchars($fullname) ?></strong>

          </div>

          <div class="flex justify-between">

            <span>Jenis Tes</span>

            <strong>Tes Kemampuan Dasar</strong>

          </div>

          <div class="flex justify-between">

            <span>Status</span>

            <span class="text-red-600 font-semibold">

              Belum Dibayar

            </span>

          </div>

          <hr>

          <div class="flex justify-between text-2xl font-bold">

            <span>Total</span>

            <span class="text-blue-600">

              Rp <?= number_format($price, 0, ",", ".") ?>

            </span>

          </div>

        </div>

      </div>

      <!-- QRIS -->

      <div class="bg-white rounded-2xl shadow-lg p-8">

        <h2 class="text-2xl font-bold text-center mb-6">

          Scan QRIS

        </h2>

        <div class="flex justify-center">

          <img src="../assets/images/Qris.jpeg"
            class="w-72 border rounded-xl">

        </div>

        <p class="text-center text-gray-500 mt-5">

          Scan menggunakan aplikasi
          Mobile Banking,
          DANA,
          OVO,
          GoPay,
          ShopeePay,
          atau aplikasi pembayaran lainnya.

        </p>

      </div>

    </div>

    <!-- Upload -->

    <div class="bg-white rounded-2xl shadow-lg mt-8 p-8">

      <h2 class="text-2xl font-bold mb-6">

        Upload Bukti Pembayaran

      </h2>

      <form
        action="../api/upload_payment.php"
        method="POST"
        enctype="multipart/form-data">

        <input
          type="file"
          name="payment_proof"
          accept=".jpg,.jpeg,.png,.pdf"
          required
          class="w-full border rounded-lg p-3">

        <p class="text-sm text-gray-500 mt-3">

          Format yang diperbolehkan:
          JPG, PNG, PDF
          (Maksimal 2MB)

        </p>

        <button
          class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl">

          <i class="fa-solid fa-upload mr-2"></i>

          Kirim Bukti Pembayaran

        </button>

      </form>

    </div>

  </div>

</body>

</html>
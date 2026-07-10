<?php

session_start();

require "../config/database.php";

if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

/* =====================================
   VALIDASI USER TEST
===================================== */

if (!isset($_GET['id'])) {
  header("Location: dashboard.php");
  exit;
}

$user_test_id = (int) $_GET['id'];

$query = mysqli_query($conn, "
SELECT

user_tests.*,
users.fullname,
tests.title,
tests.price

FROM user_tests

JOIN users
ON users.id=user_tests.user_id

JOIN tests
ON tests.id=user_tests.test_id

WHERE
user_tests.id='$user_test_id'
AND
user_tests.user_id='" . $_SESSION['id'] . "'
");

if (mysqli_num_rows($query) == 0) {

  echo "<script>
    alert('Data tidak ditemukan.');
    window.location='dashboard.php';
    </script>";
  exit;
}

$data = mysqli_fetch_assoc($query);

/* =====================================
   BADGE STATUS
===================================== */

$statusBadge = "";

switch ($data['payment_status']) {

  case 'paid':

    $statusBadge = '
        <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold">
            Lunas
        </span>';

    break;

  case 'rejected':

    $statusBadge = '
        <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold">
            Ditolak
        </span>';

    break;

  default:

    $statusBadge = '
        <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-semibold">
            Menunggu Pembayaran
        </span>';
}

?>

<!DOCTYPE html>

<html lang="id">

<head>

  <meta charset="UTF-8">

  <meta name="viewport"
    content="width=device-width, initial-scale=1">

  <title>Pembayaran Tes</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-gray-100 font-[Poppins]">

  <div class="max-w-6xl mx-auto py-10 px-6">

    <div class="text-center mb-10">

      <h1 class="text-4xl font-bold text-gray-800">

        Pembayaran Tes Psikologi

      </h1>

      <p class="text-gray-500 mt-2">

        Silakan selesaikan pembayaran agar hasil tes dapat diproses.

      </p>

    </div>

    <!-- Progress -->

    <div class="bg-white rounded-2xl shadow p-6 mb-8">

      <div class="grid grid-cols-4 text-center">

        <div>

          <div class="w-12 h-12 bg-green-500 rounded-full mx-auto flex items-center justify-center text-white">

            <i class="fa-solid fa-check"></i>

          </div>

          <p class="mt-3 font-medium">

            Tes

          </p>

        </div>

        <div>

          <div class="w-12 h-12 bg-blue-600 rounded-full mx-auto flex items-center justify-center text-white">

            <i class="fa-solid fa-money-bill"></i>

          </div>

          <p class="mt-3 font-medium">

            Pembayaran

          </p>

        </div>

        <div>

          <div class="w-12 h-12 bg-gray-300 rounded-full mx-auto flex items-center justify-center">

            <i class="fa-solid fa-user-check"></i>

          </div>

          <p class="mt-3 text-gray-500">

            Verifikasi

          </p>

        </div>

        <div>

          <div class="w-12 h-12 bg-gray-300 rounded-full mx-auto flex items-center justify-center">

            <i class="fa-solid fa-file-circle-check"></i>

          </div>

          <p class="mt-3 text-gray-500">

            Hasil

          </p>

        </div>

      </div>

    </div>

    <div class="grid md:grid-cols-2 gap-8">

      <!-- DETAIL -->

      <div class="bg-white rounded-2xl shadow-lg p-8">

        <h2 class="text-2xl font-bold mb-6">

          Detail Pembayaran

        </h2>

        <div class="space-y-5">

          <div class="flex justify-between">

            <span>Nama Peserta</span>

            <strong>

              <?= htmlspecialchars($data['fullname']) ?>

            </strong>

          </div>

          <div class="flex justify-between">

            <span>Jenis Tes</span>

            <strong>

              <?= htmlspecialchars($data['title']) ?>

            </strong>

          </div>

          <div class="flex justify-between">

            <span>Status</span>

            <?= $statusBadge ?>

          </div>

          <div class="flex justify-between">

            <span>Tanggal Tes</span>

            <strong>

              <?= date('d M Y H:i', strtotime($data['created_at'])) ?>

            </strong>

          </div>

          <hr>

          <div class="flex justify-between text-3xl font-bold">

            <span>Total</span>

            <span class="text-blue-600">

              Rp <?= number_format($data['price'], 0, ",", ".") ?>

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

          <img
            src="../assets/images/Qris.jpeg"
            alt="QRIS"
            class="w-72 border rounded-xl shadow">

        </div>

        <p class="text-center text-gray-500 mt-6 leading-7">

          Scan QRIS menggunakan Mobile Banking,
          DANA, OVO, GoPay, ShopeePay,
          atau aplikasi pembayaran lainnya.

        </p>

      </div>

    </div>

    <!-- Upload Pembayaran -->

    <div class="bg-white rounded-2xl shadow-lg mt-8 p-8">

      <h2 class="text-2xl font-bold mb-6">

        Bukti Pembayaran

      </h2>

      <?php if ($data['payment_status'] == 'pending' && empty($data['payment_proof'])) : ?>

        <!-- FORM UPLOAD -->

        <form
          action="../api/upload_payment.php"
          method="POST"
          enctype="multipart/form-data">

          <input
            type="hidden"
            name="user_test_id"
            value="<?= $data['id']; ?>">

          <input
            type="file"
            name="payment_proof"
            accept=".jpg,.jpeg,.png,.pdf"
            required
            class="w-full border rounded-lg p-3">

          <p class="text-sm text-gray-500 mt-3">

            Format yang diperbolehkan :
            JPG, JPEG, PNG, PDF
            (Maksimal 2 MB)

          </p>

          <button
            type="submit"
            class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl">

            <i class="fa-solid fa-upload mr-2"></i>

            Upload Bukti Pembayaran

          </button>

        </form>

      <?php elseif ($data['payment_status'] == 'pending') : ?>

        <!-- SUDAH UPLOAD -->

        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">

          <div class="flex items-center gap-3">

            <i class="fa-solid fa-clock text-yellow-600 text-2xl"></i>

            <div>

              <h3 class="font-bold text-yellow-700">

                Bukti pembayaran berhasil dikirim.

              </h3>

              <p class="text-gray-600 mt-1">

                Silakan menunggu verifikasi dari admin.

              </p>

            </div>

          </div>

        </div>

        <div class="mt-6">

          <?php
          $ext = strtolower(pathinfo($data['payment_proof'], PATHINFO_EXTENSION));
          ?>

          <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])) : ?>

            <img
              src="../<?= $data['payment_proof']; ?>"
              class="w-72 rounded-xl border shadow">

          <?php else : ?>

            <a
              href="../<?= $data['payment_proof']; ?>"
              target="_blank"
              class="text-blue-600 font-semibold">

              <i class="fa-solid fa-file-pdf mr-2"></i>

              Lihat File PDF

            </a>

          <?php endif; ?>

        </div>

      <?php elseif ($data['payment_status'] == 'paid') : ?>

        <!-- PEMBAYARAN BERHASIL -->

        <div class="bg-green-50 border border-green-200 rounded-xl p-6">

          <div class="flex items-center gap-3">

            <i class="fa-solid fa-circle-check text-green-600 text-3xl"></i>

            <div>

              <h3 class="font-bold text-green-700">

                Pembayaran telah diverifikasi.

              </h3>

              <p class="text-gray-600 mt-1">

                Terima kasih.
                Pembayaran Anda telah disetujui oleh admin.

              </p>

            </div>

          </div>

        </div>

        <div class="mt-8">

          <a
            href="result.php?id=<?= $data['id']; ?>"
            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl inline-block">

            <i class="fa-solid fa-file-circle-check mr-2"></i>

            Lihat Hasil Tes

          </a>

        </div>

      <?php elseif ($data['payment_status'] == 'rejected') : ?>

        <!-- DITOLAK -->

        <div class="bg-red-50 border border-red-200 rounded-xl p-6">

          <div class="flex items-center gap-3">

            <i class="fa-solid fa-circle-xmark text-red-600 text-3xl"></i>

            <div>

              <h3 class="font-bold text-red-700">

                Pembayaran ditolak.

              </h3>

              <p class="text-gray-600 mt-1">

                Silakan upload kembali bukti pembayaran
                yang benar.

              </p>

            </div>

          </div>

        </div>

        <form
          action="../api/upload_payment.php"
          method="POST"
          enctype="multipart/form-data"
          class="mt-6">

          <input
            type="hidden"
            name="user_test_id"
            value="<?= $data['id']; ?>">

          <input
            type="file"
            name="payment_proof"
            required
            accept=".jpg,.jpeg,.png,.pdf"
            class="w-full border rounded-lg p-3">

          <button
            class="mt-5 bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl">

            <i class="fa-solid fa-upload mr-2"></i>

            Upload Ulang Bukti

          </button>

        </form>

      <?php endif; ?>

    </div>

  </div>

</body>

</html>
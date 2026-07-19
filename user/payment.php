<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

if (!isset($_GET['id'])) {
  header("Location: dashboard.php");
  exit;
}

$user_test_id = (int) $_GET['id'];

$query = mysqli_query($conn, "
    SELECT user_tests.*, users.fullname, tests.title, tests.price 
    FROM user_tests 
    JOIN users ON users.id=user_tests.user_id 
    JOIN tests ON tests.id=user_tests.test_id 
    WHERE user_tests.id='$user_test_id' AND user_tests.user_id='" . $_SESSION['id'] . "'
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
   AUTO GRATIS
===================================== */

if ($data['price'] <= 0) {
  mysqli_query($conn, "
    UPDATE user_tests
    SET
    payment_status='paid',
    payment_date=NOW()
    WHERE id='$user_test_id'
    ");
  header("Location: result.php?id=" . $user_test_id);
  exit;
}

if ($data['status'] !== 'completed') {
  echo "<script>
    alert('Anda belum menyelesaikan tes ini. Silakan selesaikan tes terlebih dahulu.');
    window.location='test.php?user_test_id=" . $user_test_id . "'; 
  </script>";
  exit;
}

/* =====================================
   BADGE STATUS
===================================== */
$statusBadge = "";
switch ($data['payment_status']) {
  case 'unpaid':
    $statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">Belum Bayar</span>';
    break;
  case 'pending':
    $statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">Menunggu Verifikasi</span>';
    break;
  case 'paid':
    $statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">Lunas</span>';
    break;
  case 'rejected':
    $statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">Ditolak</span>';
    break;
  default:
    $statusBadge = '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">Tidak diketahui</span>';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran Tes - PsychoTest</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased min-h-screen">

  <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="text-center mb-10">
      <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl tracking-tight">
        Pembayaran Tes Psikologi
      </h1>
      <p class="text-sm sm:text-base text-gray-500 mt-2 max-w-xl mx-auto">
        Silakan selesaikan pembayaran agar lembar jawaban Anda dapat segera diverifikasi dan diproses oleh tim ahli kami.
      </p>
    </div>

    <!-- Progress Steps Tracker -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8 overflow-hidden">
      <div class="flex items-center justify-between max-w-3xl mx-auto relative">
        <!-- Progress Line Background -->
        <div class="absolute left-0 right-0 top-6 h-0.5 bg-gray-200 -z-10"></div>

        <!-- Step 1 -->
        <div class="flex flex-col items-center text-center bg-white px-2">
          <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white shadow-sm ring-4 ring-green-50">
            <i class="fa-solid fa-check text-sm"></i>
          </div>
          <p class="mt-2.5 text-xs font-semibold text-gray-900">Ujian Selesai</p>
        </div>

        <!-- Step 2 -->
        <div class="flex flex-col items-center text-center bg-white px-2">
          <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-md ring-4 ring-blue-50">
            <i class="fa-solid fa-receipt text-sm"></i>
          </div>
          <p class="mt-2.5 text-xs font-bold text-blue-600">Pembayaran</p>
        </div>

        <!-- Step 3 -->
        <div class="flex flex-col items-center text-center bg-white px-2">
          <div class="w-12 h-12 <?= $data['payment_status'] == 'paid' ? 'bg-green-500 ring-green-50 text-white' : 'bg-gray-100 text-gray-400' ?> rounded-full flex items-center justify-center shadow-sm ring-4 ring-transparent">
            <i class="fa-solid fa-user-check text-sm"></i>
          </div>
          <p class="mt-2.5 text-xs font-semibold <?= $data['payment_status'] == 'paid' ? 'text-gray-900' : 'text-gray-400' ?>">Verifikasi Admin</p>
        </div>

        <!-- Step 4 -->
        <div class="flex flex-col items-center text-center bg-white px-2">
          <div class="w-12 h-12 <?= $data['payment_status'] == 'paid' ? 'bg-blue-600 ring-blue-50 text-white' : 'bg-gray-100 text-gray-400' ?> rounded-full flex items-center justify-center shadow-sm ring-4 ring-transparent">
            <i class="fa-solid fa-file-invoice text-sm"></i>
          </div>
          <p class="mt-2.5 text-xs font-semibold <?= $data['payment_status'] == 'paid' ? 'text-gray-900' : 'text-gray-400' ?>">Hasil Keluar</p>
        </div>
      </div>
    </div>

    <!-- Grid Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

      <!-- SISI KIRI: DETAIL RINGKASAN -->
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
          <i class="fa-solid fa-circle-info text-blue-500"></i> Rincian Tagihan
        </h2>

        <div class="space-y-4">
          <div class="flex justify-between items-center py-2 border-b border-gray-50">
            <span class="text-sm text-gray-500">Nama Lengkap</span>
            <span class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($data['fullname']) ?></span>
          </div>

          <div class="flex justify-between items-center py-2 border-b border-gray-50">
            <span class="text-sm text-gray-500">Kategori Ujian</span>
            <span class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($data['title']) ?></span>
          </div>

          <div class="flex justify-between items-center py-2 border-b border-gray-50">
            <span class="text-sm text-gray-500">Status Invoice</span>
            <?= $statusBadge ?>
          </div>

          <div class="flex justify-between items-center py-2 border-b border-gray-50">
            <span class="text-sm text-gray-500">Waktu Transaksi</span>
            <span class="text-sm font-semibold text-gray-900"><?= date('d M Y, H:i', strtotime($data['created_at'])) ?> WAI</span>
          </div>

          <div class="pt-4 mt-2 flex justify-between items-baseline">
            <span class="text-base font-medium text-gray-900">Total Nominal</span>
            <span class="text-2xl sm:text-3xl font-black text-blue-600 tracking-tight">
              Rp <?= number_format($data['price'], 0, ",", ".") ?>
            </span>
          </div>
        </div>
      </div>

      <!-- SISI KANAN: QRIS GATEWAY -->
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 text-center">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Metode QRIS Dinamis</h2>
        <p class="text-xs text-gray-400 mb-6 font-medium uppercase tracking-wider">Scan untuk membayar cepat</p>

        <div class="inline-block p-4 bg-white border border-gray-200 rounded-2xl shadow-sm transition hover:shadow-md">
          <img src="../assets/images/Qris.jpeg" alt="QRIS Merchant" class="w-64 h-auto mx-auto rounded-xl">
        </div>

        <p class="text-xs sm:text-sm text-gray-500 mt-6 leading-relaxed max-w-sm mx-auto">
          Mendukung aplikasi <span class="font-semibold text-gray-700">Mobile Banking</span> (BCA, Mandiri, BRI, dll) serta <span class="font-semibold text-gray-700">E-Wallet</span> pilihan Anda (DANA, OVO, LinkAja, GoPay, ShopeePay).
        </p>
      </div>
    </div>

    <!-- AREA UPLOAD (FLOW KONDISIONAL) -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mt-8 p-6 sm:p-8">
      <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
        <i class="fa-solid fa-cloud-arrow-up text-blue-500"></i> Berkas Bukti Transaksi
      </h2>

      <?php if ($data['payment_status'] == 'pending' && empty($data['payment_proof'])) : ?>
        <!-- CASE 1: BELUM UPLOAD FORM -->
        <form action="../api/upload_payment.php" method="POST" enctype="multipart/form-data" id="uploadForm">
          <input type="hidden" name="user_test_id" value="<?= $data['id']; ?>">

          <!-- Area Dropzone Interaktif JS -->
          <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50/50 transition-all duration-200 group relative">
            <input type="file" name="payment_proof" id="fileInput" accept=".jpg,.jpeg,.png,.pdf" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

            <div class="space-y-2 pointer-events-none" id="dropzonePrompt">
              <i class="fa-solid fa-file-image text-4xl text-gray-400 group-hover:text-blue-500 transition-colors duration-200 mb-1"></i>
              <p class="text-sm font-semibold text-gray-700">Klik untuk mencari file atau seret kemari</p>
              <p class="text-xs text-gray-400 font-medium">Mendukung format JPG, JPEG, PNG, atau PDF (Maksimal berukuran 2 MB)</p>
            </div>

            <!-- Tampilan setelah file berhasil ditaruh (JS) -->
            <div id="filePreview" class="hidden space-y-2 pointer-events-none text-blue-600 font-semibold text-sm">
              <i class="fa-solid fa-file-circle-check text-4xl text-emerald-500 mb-1"></i>
              <p id="fileNameDisplay" class="text-gray-800 font-medium truncate max-w-md mx-auto"></p>
              <p class="text-xs text-emerald-600 font-normal">File siap untuk diunggah</p>
            </div>
          </div>

          <button type="submit" class="mt-6 inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3.5 rounded-xl transition shadow-sm active:scale-95 w-full sm:w-auto">
            <i class="fa-solid fa-paper-plane mr-2 text-sm"></i> Kirim Bukti Pembayaran
          </button>
        </form>

      <?php elseif ($data['payment_status'] == 'pending') : ?>
        <!-- CASE 2: SUDAH UPLOAD / MENUNGGU REVIEW -->
        <div class="bg-amber-50/60 border border-amber-200 rounded-xl p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4">
          <div class="p-3 bg-amber-100 rounded-xl text-amber-700">
            <i class="fa-solid fa-hourglass-half text-xl"></i>
          </div>
          <div>
            <h3 class="font-bold text-amber-800 text-base">File Anda Sedang Ditinjau Admin</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-0.5">Dokumen Anda telah masuk antrean sistem. Proses pengecekan memakan waktu sekitar 5-15 menit.</p>
          </div>
        </div>

        <div class="mt-6 p-4 border border-gray-200 rounded-2xl max-w-xs inline-block bg-gray-50 shadow-sm">
          <?php $ext = strtolower(pathinfo($data['payment_proof'], PATHINFO_EXTENSION)); ?>
          <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])) : ?>
            <img src="../<?= $data['payment_proof']; ?>" class="w-full h-auto rounded-xl object-cover">
          <?php else : ?>
            <a href="../<?= $data['payment_proof']; ?>" target="_blank" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 gap-2 p-2">
              <i class="fa-solid fa-file-pdf text-xl text-red-500"></i> Buka Dokumen Lampiran PDF
            </a>
          <?php endif; ?>
        </div>

      <?php elseif ($data['payment_status'] == 'paid') : ?>
        <!-- CASE 3: SUDAH DISETUJUI -->
        <div class="bg-emerald-50/60 border border-emerald-200 rounded-xl p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6">
          <div class="p-3 bg-emerald-100 rounded-xl text-emerald-700">
            <i class="fa-solid fa-circle-check text-xl"></i>
          </div>
          <div>
            <h3 class="font-bold text-emerald-800 text-base">Verifikasi Berhasil</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-0.5">Sistem mendeteksi mutasi dana masuk yang sah. Token pengerjaan atau hasil laporan Anda kini terbuka.</p>
          </div>
        </div>
        <a href="result.php?id=<?= $data['id']; ?>" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-3.5 rounded-xl transition shadow-sm active:scale-95 w-full sm:w-auto shadow-md">
          <i class="fa-solid fa-chart-pie mr-2"></i> Akses Hasil Analisis Tes
        </a>

      <?php elseif ($data['payment_status'] == 'rejected') : ?>
        <!-- CASE 4: DITOLAK -->
        <div class="bg-red-50/60 border border-red-200 rounded-xl p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6">
          <div class="p-3 bg-red-100 rounded-xl text-red-700">
            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
          </div>
          <div>
            <h3 class="font-bold text-red-800 text-base">Unggahan Ditolak Sistem</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-0.5">Bukti transfer buram, terpotong, atau tidak sesuai dengan tagihan nominal. Mohon kirimkan ulang struk resmi.</p>
          </div>
        </div>

        <form action="../api/upload_payment.php" method="POST" enctype="multipart/form-data" id="uploadForm">
          <input type="hidden" name="user_test_id" value="<?= $data['id']; ?>">

          <div id="dropzone" class="border-2 border-dashed border-red-200 rounded-2xl p-8 text-center cursor-pointer hover:border-red-400 hover:bg-red-50/30 transition-all duration-200 group relative">
            <input type="file" name="payment_proof" id="fileInput" accept=".jpg,.jpeg,.png,.pdf" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

            <div class="space-y-2 pointer-events-none" id="dropzonePrompt">
              <i class="fa-solid fa-rotate-left text-4xl text-gray-400 group-hover:text-red-500 transition-colors duration-200 mb-1"></i>
              <p class="text-sm font-semibold text-gray-700">Klik / Seret File baru untuk mengganti</p>
              <p class="text-xs text-gray-400 font-medium">Pastikan nominal transfer tertera jelas tanpa sensor</p>
            </div>

            <div id="filePreview" class="hidden space-y-2 pointer-events-none text-red-600 font-semibold text-sm">
              <i class="fa-solid fa-file-circle-check text-4xl text-emerald-500 mb-1"></i>
              <p id="fileNameDisplay" class="text-gray-800 font-medium truncate max-w-md mx-auto"></p>
              <p class="text-xs text-emerald-600 font-normal">File pengganti siap di-upload</p>
            </div>
          </div>

          <button type="submit" class="mt-6 inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3.5 rounded-xl transition shadow-sm active:scale-95 w-full sm:w-auto shadow-md">
            <i class="fa-solid fa-arrow-up-from-bracket mr-2"></i> Unggah Ulang Bukti Baru
          </button>
        </form>
      <?php endif; ?>

    </div>
  </div>

  <!-- INTERACTIVE JAVASCRIPT LOGIC -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const fileInput = document.getElementById("fileInput");
      const dropzone = document.getElementById("dropzone");
      const promptDiv = document.getElementById("dropzonePrompt");
      const previewDiv = document.getElementById("filePreview");
      const nameDisplay = document.getElementById("fileNameDisplay");

      if (fileInput && dropzone) {
        // Efek Visual saat file melayang di atas dropzone
        ['dragenter', 'dragover'].forEach(eventName => {
          dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.add('border-blue-500', 'bg-blue-50/70');
          }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
          dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-blue-500', 'bg-blue-50/70');
          }, false);
        });

        // Event handler ketika file terdeteksi
        fileInput.addEventListener('change', function(e) {
          if (this.files && this.files.length > 0) {
            const file = this.files[0];

            // Sembunyikan instruksi awal, tampilkan preview info berkas
            promptDiv.classList.add('hidden');
            previewDiv.classList.remove('hidden');
            nameDisplay.textContent = file.name + " (" + (file.size / (1024 * 1024)).toFixed(2) + " MB)";
          }
        });
      }
    });
  </script>
</body>

</html>
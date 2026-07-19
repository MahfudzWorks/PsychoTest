<?php
session_start();
require "../config/database.php";

// Cek login
if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

// Cek parameter id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: dashboard.php");
  exit;
}

$user_test_id = (int)$_GET['id'];
$user_id = (int)$_SESSION['id'];

// Gunakan prepared statement untuk mencegah SQL Injection
$stmt = mysqli_prepare($conn, "
  SELECT 
    user_tests.*,
    users.fullname,
    tests.title,
    tests.description,
    tests.price
  FROM user_tests
  JOIN users ON users.id = user_tests.user_id
  JOIN tests ON tests.id = user_tests.test_id
  WHERE user_tests.id = ?
    AND user_tests.user_id = ?
  LIMIT 1
");
mysqli_stmt_bind_param($stmt, "ii", $user_test_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
  echo "<script>alert('Data hasil tidak ditemukan'); window.location='dashboard.php';</script>";
  exit;
}

$data = mysqli_fetch_assoc($result);

// Cek status tes selesai
if ($data['status'] != 'completed') {
  echo "<script>alert('Tes belum selesai'); window.location='dashboard.php';</script>";
  exit;
}

// Cek status pembayaran
if ($data['price'] > 0 && $data['payment_status'] != 'paid') {
  echo "<script>alert('Silakan selesaikan pembayaran terlebih dahulu'); window.location='payment.php?id=$user_test_id';</script>";
  exit;
}

// Set nilai default jika kosong
$score = $data['score'] ?? 0;
$correct = $data['correct_answers'] ?? 0;
$wrong = $data['wrong_answers'] ?? 0;
$total_questions = $correct + $wrong;
$correct_percentage = $total_questions > 0 ? round(($correct / $total_questions) * 100) : 0;

$fullname = htmlspecialchars($data['fullname'] ?? 'Pengguna');
$test_title = htmlspecialchars($data['title'] ?? 'Tes Tidak Diketahui');
$end_time = $data['end_time'] ?? date("Y-m-d H:i:s");
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hasil Tes - PsychoTest</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    @media print {
      .no-print {
        display: none !important;
      }

      body {
        background: white !important;
        padding: 0;
      }

      .print-card {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
      }
    }
  </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen antialiased">
  <div class="max-w-4xl mx-auto px-4 py-12">

    <!-- Header Section (Hidden on Print) -->
    <div class="text-center mb-10 no-print">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 text-white rounded-2xl shadow-lg shadow-blue-200 mb-4 animate-bounce">
        <i class="fa-solid fa-square-poll-vertical text-2xl"></i>
      </div>
      <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Laporan Hasil Evaluasi</h1>
      <p class="text-slate-500 mt-2 font-medium">Terima kasih telah menyelesaikan tes evaluasi Anda di PsychoTest</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100/70 border border-slate-100 p-6 md:p-10 print-card">

      <!-- Top Profile Info -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-6">
        <div>
          <span class="text-xs font-bold text-blue-600 uppercase tracking-wider px-2.5 py-1 bg-blue-50 rounded-md">Peserta Tes</span>
          <h2 class="text-2xl font-bold text-slate-900 mt-2"><?= $fullname ?></h2>
          <p class="text-slate-500 font-medium text-sm mt-0.5"><i class="fa-solid fa-graduation-cap mr-1.5"></i> <?= $test_title ?></p>
        </div>
        <div class="sm:text-right flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-2">
          <?php if ($data['price'] == 0): ?>
            <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-4 py-2 rounded-xl text-sm font-semibold border border-amber-100">
              <i class="fa-solid fa-gift"></i> Akses Gratis
            </span>
          <?php else: ?>
            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-4 py-2 rounded-xl text-sm font-semibold border border-emerald-100">
              <i class="fa-solid fa-circle-check"></i> Terverifikasi Resmi
            </span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Score & Stats Grid -->
      <div class="grid md:grid-cols-3 gap-6 my-8">

        <!-- Score Box Utama -->
        <div class="bg-gradient-to-b from-blue-600 to-indigo-700 rounded-2xl p-6 text-center text-white shadow-lg shadow-blue-200 md:col-span-1 flex flex-col justify-center items-center">
          <p class="text-sm font-medium text-blue-100 opacity-90 uppercase tracking-wider">Skor Akhir</p>
          <div class="relative flex items-center justify-center my-4">
            <h2 class="text-6xl font-black tracking-tight"><?= round($score) ?></h2>
          </div>
          <p class="text-xs text-blue-100 bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm font-medium">Skala Maksimal 100</p>
        </div>

        <!-- Rincian Jawaban -->
        <div class="md:col-span-2 grid grid-cols-2 gap-4">
          <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-slate-500">Jawaban Benar</span>
              <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold"><i class="fa-solid fa-check"></i></span>
            </div>
            <div class="mt-4">
              <h3 class="text-3xl font-bold text-slate-900"><?= $correct ?></h3>
              <p class="text-xs text-slate-400 mt-1">Total soal terjawab benar</p>
            </div>
          </div>

          <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-slate-500">Jawaban Salah</span>
              <span class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-xs font-bold"><i class="fa-solid fa-xmark"></i></span>
            </div>
            <div class="mt-4">
              <h3 class="text-3xl font-bold text-slate-900"><?= $wrong ?></h3>
              <p class="text-xs text-slate-400 mt-1">Total soal kurang tepat</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Akurasi Progress Bar Visual -->
      <?php if ($total_questions > 0): ?>
        <div class="mb-8 p-5 bg-slate-50 border border-slate-100 rounded-2xl">
          <div class="flex justify-between items-center text-sm font-semibold mb-2">
            <span class="text-slate-600">Rasio Akurasi Jawaban</span>
            <span class="text-blue-600"><?= $correct_percentage ?>%</span>
          </div>
          <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: <?= $correct_percentage ?>%"></div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Info Tambahan -->
      <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5">
        <h4 class="font-bold text-slate-900 mb-3 flex items-center gap-2 text-sm">
          <i class="fa-solid fa-circle-info text-blue-600"></i> Metadata & Validasi Dokumen
        </h4>
        <div class="grid sm:grid-cols-2 gap-4 text-xs font-medium text-slate-600">
          <div class="flex justify-between sm:justify-start sm:gap-8 border-b sm:border-b-0 border-slate-200/60 pb-2 sm:pb-0">
            <span class="text-slate-400 w-28">Waktu Selesai</span>
            <span class="text-slate-900">: <?= date("d M Y - H:i", strtotime($end_time)) ?> WIB</span>
          </div>
          <div class="flex justify-between sm:justify-start sm:gap-8">
            <span class="text-slate-400 w-28">Status Ujian</span>
            <span class="text-emerald-600 font-bold">: SUKSES / SELESAI</span>
          </div>
        </div>
      </div>

      <!-- Action Buttons (Hidden on Print) -->
      <div class="mt-8 flex flex-col sm:flex-row gap-3 no-print">
        <a href="dashboard.php" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">
          <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <button onclick="window.print()" class="flex-1 inline-flex items-center justify-center gap-2 bg-slate-900 text-white px-5 py-3 rounded-xl font-semibold text-sm hover:bg-slate-800 shadow-md shadow-slate-200 transition-all active:scale-95">
          <i class="fa-solid fa-print"></i> Cetak / Simpan PDF Hasil Ujian
        </button>
      </div>

    </div>
  </div>
</body>

</html>
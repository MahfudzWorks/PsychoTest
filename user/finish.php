<?php
session_start();
require "../config/database.php";

/* =====================================
   LOGIN
===================================== */

if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

/* =====================================
   VALIDASI ID
===================================== */

if (!isset($_GET['id'])) {
  header("Location: dashboard.php");
  exit;
}

$user_test_id = (int) $_GET['id'];
$user_id      = (int) $_SESSION['id'];

/* =====================================
   AMBIL DATA USER TEST
===================================== */

$query = mysqli_query($conn, "
SELECT
    user_tests.*,
    users.fullname,
    tests.title,
    tests.price
FROM user_tests
JOIN users
ON users.id = user_tests.user_id
JOIN tests
ON tests.id = user_tests.test_id
WHERE
user_tests.id='$user_test_id'
AND
user_tests.user_id='$user_id'
LIMIT 1
");

if (mysqli_num_rows($query) == 0) {

  echo "
    <script>
    alert('Data tes tidak ditemukan.');
    window.location='dashboard.php';
    </script>
    ";

  exit;
}

$data = mysqli_fetch_assoc($query);

/* =====================================
   STATUS TES
===================================== */

if ($data['status'] != "completed") {

  echo "
    <script>
    alert('Tes belum selesai.');
    window.location='dashboard.php';
    </script>
    ";

  exit;
}

/* =====================================
   STATUS BADGE TES
===================================== */

$test_status = "Selesai";

/* =====================================
   STATUS PEMBAYARAN
===================================== */

switch ($data['payment_status']) {

  case "unpaid":

    $payment_status = "Belum Dibayar";

    $paymentBadge =
      '
        <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 px-3 py-1.5 rounded-xl text-xs font-semibold border border-rose-100">
            <i class="fa-solid fa-circle-xmark text-[10px]"></i>
            Belum Dibayar
        </span>
        ';

    break;

  case "pending":

    $payment_status = "Menunggu Verifikasi";

    $paymentBadge =
      '
        <span class="inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-700 px-3 py-1.5 rounded-xl text-xs font-semibold border border-yellow-200">
            <i class="fa-solid fa-clock text-[10px]"></i>
            Menunggu
        </span>
        ';

    break;

  case "paid":

    $payment_status = "Lunas";

    $paymentBadge =
      '
        <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-3 py-1.5 rounded-xl text-xs font-semibold border border-green-200">
            <i class="fa-solid fa-circle-check text-[10px]"></i>
            Lunas
        </span>
        ';

    break;

  case "rejected":

    $payment_status = "Ditolak";

    $paymentBadge =
      '
        <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-3 py-1.5 rounded-xl text-xs font-semibold border border-red-200">
            <i class="fa-solid fa-circle-xmark text-[10px]"></i>
            Ditolak
        </span>
        ';

    break;

  default:

    $payment_status = "Belum Dibayar";

    $paymentBadge =
      '
        <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 px-3 py-1.5 rounded-xl text-xs font-semibold border border-gray-200">
            Unknown
        </span>
        ';

    break;
}

/* =====================================
   TOMBOL SESUAI STATUS
===================================== */

$actionButton = "";

switch ($data['payment_status']) {

  case "unpaid":

    $actionButton = '
        <a href="payment.php?id=' . $data['id'] . '"
        class="w-full sm:w-1/2 order-1 sm:order-2 inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-md shadow-indigo-100 transition-all transform hover:-translate-y-0.5">
            Bayar Sekarang
            <i class="fa-solid fa-chevron-right text-xs ml-2"></i>
        </a>
        ';

    break;

  case "pending":

    $actionButton = '
        <a href="payment.php?id=' . $data['id'] . '"
        class="w-full sm:w-1/2 order-1 sm:order-2 inline-flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-md">
            Lihat Pembayaran
        </a>
        ';

    break;

  case "paid":

    $actionButton = '
        <a href="result.php?id=' . $data['id'] . '"
        class="w-full sm:w-1/2 order-1 sm:order-2 inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-md">
            Lihat Hasil
        </a>
        ';

    break;

  case "rejected":

    $actionButton = '
        <a href="payment.php?id=' . $data['id'] . '"
        class="w-full sm:w-1/2 order-1 sm:order-2 inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-md">
            Upload Ulang Bukti
        </a>
        ';

    break;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tes Selesai | PsychoTest</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    /* Animasi Masuk Halaman */
    .fade-in-up {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .fade-in-up.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* Animasi Ikon Pop-in */
    @keyframes popIn {
      0% {
        transform: scale(0);
        opacity: 0;
      }

      70% {
        transform: scale(1.1);
      }

      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    .animate-pop-in {
      animation: popIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
      animation-delay: 0.2s;
      opacity: 0;
    }

    /* Partikel Confetti */
    .confetti {
      position: absolute;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      pointer-events: none;
      z-index: 50;
    }
  </style>
</head>

<body class="bg-[#f8fafc] text-slate-800 antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

  <div id="confetti-container" class="absolute inset-0 pointer-events-none z-40"></div>

  <div class="fade-in-up bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 w-full max-w-xl p-8 sm:p-10 relative z-10">

    <div class="text-center relative">
      <div id="success-icon-trigger" class="animate-pop-in w-20 h-20 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center mx-auto shadow-sm shadow-emerald-100">
        <i class="fa-solid fa-circle-check text-4xl text-emerald-600"></i>
      </div>

      <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-6">
        Tes Berhasil Diselesaikan!
      </h1>
      <p class="text-slate-500 text-sm sm:text-base mt-2.5 max-w-md mx-auto leading-relaxed">
        Luar biasa, <span class="font-semibold text-indigo-600"><?= htmlspecialchars($data['fullname']) ?></span>. Anda telah berhasil merampungkan seluruh rangkaian instruksi ujian.
      </p>
    </div>

    <div class="mt-8 space-y-3.5">
      <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-4 flex justify-between items-center transition-all hover:bg-slate-50">
        <div class="flex items-center gap-3.5">
          <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
            <i class="fa-solid fa-graduation-cap"></i>
          </div>
          <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex justify-between">

            <div>

              <p class="text-xs text-gray-500">
                Jenis Tes
              </p>

              <h3 class="font-semibold">
                <?= htmlspecialchars($data['title']) ?>
              </h3>

            </div>

            <div class="text-right">

              <p class="text-xs text-gray-500">
                Biaya
              </p>

              <h3 class="font-bold text-indigo-600">
                Rp <?= number_format($data['price'], 0, ",", ".") ?>
              </h3>

            </div>

          </div>
          <div>
            <h3 class="font-semibold text-slate-900 text-sm sm:text-base">Status Progress</h3>
            <p class="text-slate-400 text-xs mt-0.5">Riwayat pengerjaan modul</p>
          </div>
        </div>
        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-xl text-xs font-semibold border border-emerald-100">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> <?= $test_status ?>
        </span>
      </div>

      <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-4 flex justify-between items-center transition-all hover:bg-slate-50">
        <div class="flex items-center gap-3.5">
          <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
            <i class="fa-solid fa-wallet"></i>
          </div>
          <div>
            <h3 class="font-semibold text-slate-900 text-sm sm:text-base">Status Pembayaran</h3>
            <p class="text-slate-400 text-xs mt-0.5">Akses buka invoice hasil</p>
          </div>
        </div>
        <?= $paymentBadge ?>
      </div>
    </div>

    <div class="mt-6 bg-blue-50/60 border border-blue-100 rounded-2xl p-4 sm:p-5">
      <div class="flex gap-3">
        <i class="fa-solid fa-circle-info text-blue-500 text-lg mt-0.5"></i>
        <div>
          <h3 class="font-semibold text-blue-900 text-sm">

            <?php
            switch ($data['payment_status']) {

              case "unpaid":
                echo "Silakan Lakukan Pembayaran";
                break;

              case "pending":
                echo "Pembayaran Sedang Diverifikasi";
                break;

              case "paid":
                echo "Pembayaran Berhasil";
                break;

              case "rejected":
                echo "Pembayaran Ditolak";
                break;
            }
            ?>

          </h3>

          <p class="text-slate-600 text-xs sm:text-sm mt-1.5 leading-relaxed">

            <?php

            switch ($data['payment_status']) {

              case "unpaid":

                echo "Silakan lakukan pembayaran terlebih dahulu agar hasil psikotes dapat diproses oleh admin.";

                break;

              case "pending":

                echo "Bukti pembayaran telah diterima dan sedang diverifikasi oleh admin.";

                break;

              case "paid":

                echo "Pembayaran telah disetujui. Anda sekarang dapat melihat hasil psikotes.";

                break;

              case "rejected":

                echo "Bukti pembayaran ditolak. Silakan upload ulang bukti pembayaran yang benar.";

                break;
            }

            ?>

          </p>
        </div>
      </div>
    </div>

    <div class="mt-8 flex flex-col sm:flex-row gap-3">
      <a href="dashboard.php"
        class="w-full sm:w-1/2 order-2 sm:order-1 inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 transition-colors">
        <i class="fa-solid fa-arrow-left text-xs mr-2"></i> Dashboard
      </a>
      <?= $actionButton ?>
    </div>

  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {

      // 1. Trigger Efek Fade-in Up saat halaman dimuat
      const mainCard = document.querySelector('.fade-in-up');
      setTimeout(() => {
        mainCard.classList.add('visible');
      }, 100);

      // 2. Custom JS Confetti Effect (Tanpa Libary Eksternal)
      const initConfetti = () => {
        const container = document.getElementById('confetti-container');
        const trigger = document.getElementById('success-icon-trigger');
        const colors = ['#10b981', '#3b82f6', '#6366f1', '#f59e0b', '#ec4899'];

        // Dapatkan koordinat pusat ikon sebagai titik ledakan confetti
        const rect = trigger.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        for (let i = 0; i < 50; i++) {
          const particle = document.createElement('div');
          particle.classList.add('confetti');
          particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
          particle.style.left = `${centerX}px`;
          particle.style.top = `${centerY}px`;

          // Ukuran acak bulat vs kotak
          if (Math.random() > 0.5) particle.style.borderRadius = '0%';

          container.appendChild(particle);

          // Kalkulasi sudut ledakan acak
          const angle = Math.random() * Math.PI * 2;
          const velocity = 3 + Math.random() * 6;
          const destinationX = Math.cos(angle) * (60 + Math.random() * 120);
          const destinationY = Math.sin(angle) * (60 + Math.random() * 120) + 150; // gravitasi jatuh ke bawah

          // Jalankan animasi mekanis menggunakan JS Keyframe alternatif (Web Animations API)
          particle.animate([{
              transform: 'translate(0, 0) scale(1) rotate(0deg)',
              opacity: 1
            },
            {
              transform: `translate(${destinationX}px, ${destinationY}px) scale(0) rotate(${Math.random() * 360}deg)`,
              opacity: 0
            }
          ], {
            duration: 1000 + Math.random() * 1000,
            easing: 'cubic-bezier(0.1, 0.8, 0.3, 1)',
            fill: 'forwards'
          });

          // Bersihkan elemen sampah DOM setelah selesai animasi
          setTimeout(() => {
            particle.remove();
          }, 2000);
        }
      };

      // Jalankan ledakan confetti pas setelah ikon pop selesai
      setTimeout(initConfetti, 700);
    });
  </script>
</body>

</html>
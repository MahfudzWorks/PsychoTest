<?php
session_start();
require "../config/database.php";

// Cek login
if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

// Cek parameter ID
if (!isset($_GET['id'])) {
  header("Location: dashboard.php");
  exit;
}

$test_id = (int)$_GET['id'];
$user_id = $_SESSION['id'];
$fullname = htmlspecialchars($_SESSION['fullname'] ?? 'Pengguna');

// Ambil data tes
$query = mysqli_query($conn, "SELECT * FROM tests WHERE id='$test_id' AND status='active'");

if (mysqli_num_rows($query) == 0) {
  echo "<script>alert('Tes tidak ditemukan.'); window.location='dashboard.php';</script>";
  exit;
}

$test = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Persiapan Tes | PsychoTest</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <!-- AOS Animation Library -->
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>

<body class="bg-[#f8fafc] text-slate-800 antialiased min-h-screen flex flex-col">

  <?php include "../includes/navbar_user.php"; ?>

  <main class="flex-grow max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6" data-aos="fade-right" data-aos-duration="600">
      <a href="dashboard.php" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Dashboard
      </a>
    </div>

    <!-- Card Utama dengan Efek Fade Up -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden" data-aos="fade-up" data-aos-duration="800">
      <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 text-white p-8 sm:p-10">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500 rounded-full blur-3xl opacity-30"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-blue-400 rounded-full blur-3xl opacity-30"></div>
        <div class="relative z-10">
          <span class="bg-indigo-500/30 text-indigo-100 text-xs px-3 py-1 rounded-full font-medium tracking-wide uppercase">Konfirmasi Modul</span>
          <h1 class="text-2xl sm:text-3xl font-bold tracking-tight mt-3"><?= htmlspecialchars($test['title']); ?></h1>
          <p class="mt-3 text-indigo-100/90 text-sm sm:text-base leading-relaxed"><?= htmlspecialchars($test['description']); ?></p>
        </div>
      </div>

      <div class="p-6 sm:p-10">
        <!-- Grid Informasi dengan Staggered Delay (bergantian muncul) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
          <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex items-center gap-4 transition-all duration-300 hover:scale-[1.02] hover:shadow-sm" data-aos="zoom-in" data-aos-delay="200">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-list-check text-xl"></i>
            </div>
            <div>
              <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Jumlah Soal</p>
              <p class="text-base font-semibold text-slate-800 mt-0.5"><?= $test['total_questions']; ?> Butir</p>
            </div>
          </div>
          <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex items-center gap-4 transition-all duration-300 hover:scale-[1.02] hover:shadow-sm" data-aos="zoom-in" data-aos-delay="300">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-stopwatch text-xl"></i>
            </div>
            <div>
              <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Durasi Tes</p>
              <p class="text-base font-semibold text-slate-800 mt-0.5"><?= $test['duration']; ?> Menit</p>
            </div>
          </div>
          <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex items-center gap-4 transition-all duration-300 hover:scale-[1.02] hover:shadow-sm" data-aos="zoom-in" data-aos-delay="400">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-tags text-xl"></i>
            </div>
            <div>
              <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Biaya Modul</p>
              <p class="text-base font-semibold text-slate-800 mt-0.5"><?= $test['price'] == 0 ? 'Gratis' : 'Rp ' . number_format($test['price'], 0, ",", "."); ?></p>
            </div>
          </div>
        </div>

        <div class="bg-amber-50/60 border border-amber-200/70 rounded-xl p-6 mb-8" data-aos="fade-up" data-aos-delay="500">
          <div class="flex items-center gap-2 mb-4 text-amber-800">
            <i class="fa-solid fa-triangle-exclamation animate-pulse"></i>
            <h2 class="text-base font-bold">Petunjuk Pengerjaan</h2>
          </div>
          <ul class="space-y-3 text-sm text-slate-600">
            <li class="flex items-start gap-2.5">
              <span class="text-amber-500 mt-0.5"><i class="fa-solid fa-circle-check text-xs"></i></span>
              <span>Pastikan koneksi internet stabil.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="text-amber-500 mt-0.5"><i class="fa-solid fa-circle-check text-xs"></i></span>
              <span>Kerjakan di tempat yang tenang dan fokus.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="text-amber-500 mt-0.5"><i class="fa-solid fa-circle-check text-xs"></i></span>
              <span><strong>Jangan tutup atau pindah halaman</strong> saat tes berlangsung.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="text-amber-500 mt-0.5"><i class="fa-solid fa-circle-check text-xs"></i></span>
              <span>Waktu berjalan otomatis setelah tes dimulai.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="text-amber-500 mt-0.5"><i class="fa-solid fa-circle-check text-xs"></i></span>
              <span>Setiap tes hanya bisa dikerjakan <strong>1 kali</strong>.</span>
            </li>
          </ul>
        </div>

        <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl mb-8 transition-colors duration-300" id="agreeContainer" data-aos="fade-up" data-aos-delay="500">
          <label class="flex items-start gap-3 cursor-pointer select-none">
            <input type="checkbox" id="agree" class="mt-1 w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 transition-transform duration-200 active:scale-95">
            <span class="text-xs sm:text-sm text-slate-600 leading-relaxed">
              Saya telah membaca dan memahami petunjuk, serta bersedia mengikuti tes dengan jujur dan bertanggung jawab.
            </span>
          </label>
        </div>

        <div class="flex justify-end" data-aos="fade-up" data-aos-delay="600">
          <button id="startBtn" disabled
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white opacity-40 cursor-not-allowed px-8 py-3 rounded-xl text-sm font-semibold shadow-md shadow-indigo-100 transition-all duration-300 transform active:scale-95">
            <i class="fa-solid fa-play text-xs"></i> Saya Siap, Mulai Tes
          </button>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-white border-t border-slate-100 py-6 mt-auto">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
      <p>&copy; <?= date('Y') ?> PsychoTest Application. All rights reserved.</p>
      <div class="flex gap-4">
        <a href="#" class="hover:text-slate-600 transition-colors">Bantuan</a>
        <a href="#" class="hover:text-slate-600 transition-colors">Syarat & Ketentuan</a>
      </div>
    </div>
  </footer>

  <!-- AOS JS Library -->
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    // Inisialisasi library AOS
    AOS.init({
      once: true // Animasi hanya berjalan satu kali saat halaman di-load
    });

    const agree = document.getElementById("agree");
    const startBtn = document.getElementById("startBtn");
    const agreeContainer = document.getElementById("agreeContainer");

    agree.addEventListener("change", function() {
      if (this.checked) {
        startBtn.disabled = false;
        // Transisi halus saat tombol menjadi aktif dan berdenyut perlahan (animate-pulse)
        startBtn.classList.remove("opacity-40", "cursor-not-allowed");
        startBtn.classList.add("hover:shadow-indigo-200", "shadow-lg", "animate-pulse");

        // Highlight background box persetujuan saat di-check
        agreeContainer.classList.remove("bg-slate-50", "border-slate-100");
        agreeContainer.classList.add("bg-indigo-50/40", "border-indigo-100");
      } else {
        startBtn.disabled = true;
        startBtn.classList.add("opacity-40", "cursor-not-allowed");
        startBtn.classList.remove("hover:shadow-indigo-200", "shadow-lg", "animate-pulse");

        agreeContainer.classList.add("bg-slate-50", "border-slate-100");
        agreeContainer.classList.remove("bg-indigo-50/40", "border-indigo-100");
      }
    });

    startBtn.addEventListener("click", function() {
      if (!confirm("Yakin ingin mulai tes? Waktu akan langsung berjalan.")) return;
      window.location.href = "start_test.php?id=<?= $test['id']; ?>";
    });
  </script>

</body>

</html>
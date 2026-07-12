<?php
session_start();
require "../config/database.php";

// Cek sesi login
if (!isset($_SESSION['login']) || empty($_SESSION['id'])) {
  header("Location: ../auth/login.php");
  exit;
}

// Ambil data sesi & pastikan tipe data benar
$user_id  = (int)$_SESSION['id'];
$fullname = htmlspecialchars($_SESSION['fullname'] ?? 'Pengguna');

// ✅ QUERY DIPERBAIKI & LEBIH AMAN
$query = "
SELECT 
  tests.*,
  ut.id AS user_test_id,
  ut.status AS test_status,
  ut.payment_status
FROM tests
LEFT JOIN user_tests ut 
  ON tests.id = ut.test_id AND ut.user_id = ?
WHERE tests.status = 'active'
ORDER BY tests.created_at DESC
";

// Gunakan prepared statement untuk menghindari error
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Cek jika ada kesalahan
if (!$result) {
  die("Kesalahan pada database: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard User | PsychoTest</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
  </style>
</head>

<body class="bg-[#f8fafc] text-slate-800 antialiased min-h-screen flex flex-col">

  <?php include "../includes/navbar_user.php"; ?>

  <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- HERO BANNER -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 rounded-2xl text-white p-6 sm:p-10 shadow-xl shadow-indigo-100 mb-10">
      <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500 rounded-full blur-3xl opacity-30"></div>
      <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-blue-400 rounded-full blur-3xl opacity-30"></div>
      <div class="relative z-10 max-w-2xl">
        <span class="bg-indigo-500/30 text-indigo-100 text-xs px-3 py-1 rounded-full font-medium tracking-wide uppercase">Area Tes</span>
        <h1 class="text-2xl sm:text-4xl font-bold tracking-tight mt-3">Selamat Datang, <?= $fullname ?> 👋</h1>
        <p class="mt-3 text-indigo-100/90 text-sm sm:text-base leading-relaxed">
          Pilih salah satu modul tes di bawah ini untuk memulai pengerjaan. Hasil akan tersimpan dan bisa dilihat kapan saja.
        </p>
      </div>
    </div>

    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Pilih Tes yang Tersedia</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Klik tombol di bawah kartu untuk memulai atau melanjutkan tes</p>
      </div>
    </div>

    <!-- ✅ AREA PEMILIHAN TES -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

      <?php if (mysqli_num_rows($result) > 0): ?>

        <?php while ($test = mysqli_fetch_assoc($result)): ?>

          <!-- HAPUS baris <pre> ini jika sudah normal, hanya untuk cek data saja -->
          <!-- <pre style="background:#000;color:#0f0;padding:10px;overflow:auto;max-height:200px;"><?php print_r($test); ?></pre> -->

          <div class="bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-200 transition-all duration-300 p-6 flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start gap-4 mb-4">
                <span class="text-xs font-semibold tracking-wider text-indigo-500 bg-indigo-50 px-2 py-1 rounded-md">MODUL</span>

                <div>
                  <?php if (empty($test['test_status']) || $test['test_status'] == "pending"): ?>
                    <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-600 px-2.5 py-1 rounded-md text-xs font-medium border border-slate-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Belum Dimulai
                    </span>
                  <?php elseif ($test['test_status'] == "on_progress"): ?>
                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-2.5 py-1 rounded-md text-xs font-medium border border-amber-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Sedang Berjalan
                    </span>
                  <?php elseif ($test['test_status'] == "completed"): ?>
                    <?php if ($test['price'] > 0 && $test['payment_status'] != "paid"): ?>
                      <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 px-2.5 py-1 rounded-md text-xs font-medium border border-rose-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Menunggu Pembayaran
                      </span>
                    <?php else: ?>
                      <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-md text-xs font-medium border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                      </span>
                    <?php endif; ?>
                  <?php elseif ($test['test_status'] == "expired"): ?>
                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-2.5 py-1 rounded-md text-xs font-medium border border-red-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Tes Berakhir
                    </span>
                  <?php endif; ?>
                </div>
              </div>

              <h3 class="text-lg font-bold text-slate-900 mb-2"><?= htmlspecialchars($test['title']) ?></h3>
              <p class="text-slate-500 text-sm mb-6 line-clamp-2"><?= htmlspecialchars($test['description']) ?></p>

              <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-3 text-center">
                  <i class="fa-solid fa-stopwatch text-indigo-500 text-base mb-1.5"></i>
                  <p class="text-[10px] text-slate-400 font-medium uppercase">Waktu</p>
                  <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $test['duration'] ?> Menit</p>
                </div>
                <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-3 text-center">
                  <i class="fa-solid fa-list-check text-emerald-500 text-base mb-1.5"></i>
                  <p class="text-[10px] text-slate-400 font-medium uppercase">Soal</p>
                  <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $test['total_questions'] ?> Butir</p>
                </div>
                <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-3 text-center">
                  <i class="fa-solid fa-tags text-blue-500 text-base mb-1.5"></i>
                  <p class="text-[10px] text-slate-400 font-medium uppercase">Biaya</p>
                  <p class="text-sm font-semibold text-slate-800 mt-0.5">
                    <?= $test['price'] == 0 ? 'Gratis' : 'Rp' . number_format($test['price'], 0, ",", ".") ?>
                  </p>
                </div>
              </div>
            </div>

            <div class="pt-4 border-t border-slate-50 mt-auto">
              <?php if (empty($test['test_status']) || $test['test_status'] == "pending"): ?>
                <a href="start.php?id=<?= $test['id'] ?>" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm shadow-indigo-100 transition-colors">
                  <i class="fa-solid fa-play text-xs"></i> Mulai Tes
                </a>
              <?php elseif ($test['test_status'] == "on_progress"): ?>
                <a href="test.php?user_test_id=<?= $test['user_test_id'] ?>" class="w-full inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                  <i class="fa-solid fa-forward text-xs"></i> Lanjutkan Tes
                </a>
              <?php elseif ($test['test_status'] == "completed" && $test['price'] > 0 && $test['payment_status'] == "unpaid"): ?>
                <a href="payment.php?id=<?= $test['user_test_id'] ?>" class="w-full inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                  <i class="fa-solid fa-wallet text-xs"></i> Lakukan Pembayaran
                </a>
              <?php elseif ($test['test_status'] == "completed" && $test['payment_status'] == "pending"): ?>
                <button class="w-full bg-yellow-500 text-white px-5 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed" disabled>
                  <i class="fa-solid fa-clock"></i> Menunggu Verifikasi
                </button>
              <?php elseif ($test['test_status'] == "completed" && ($test['payment_status'] == "paid" || $test['price'] == 0)): ?>
                <a href="result.php?id=<?= $test['user_test_id'] ?>" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                  <i class="fa-solid fa-square-poll-vertical text-xs"></i> Lihat Hasil Tes
                </a>
              <?php elseif ($test['test_status'] == "expired"): ?>
                <button class="w-full bg-red-500 text-white px-5 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed" disabled>
                  <i class="fa-solid fa-ban"></i> Tes Berakhir
                </button>
              <?php endif; ?>
            </div>
          </div>

        <?php endwhile; ?>

      <?php else: ?>
        <div class="col-span-full bg-white border border-slate-100 rounded-2xl p-12 text-center shadow-sm max-w-lg mx-auto w-full">
          <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
            <i class="fa-regular fa-folder-open text-2xl"></i>
          </div>
          <h3 class="text-lg font-bold text-slate-800">Belum Ada Tes Tersedia</h3>
          <p class="text-sm text-slate-500 max-w-md mx-auto mt-2 leading-relaxed">
            Saat ini belum ada tes yang aktif. Silakan cek kembali nanti atau hubungi admin jika ada pertanyaan.
          </p>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <footer class="bg-white border-t border-slate-100 py-6 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
      <p>&copy; <?= date('Y') ?> PsychoTest Application. All rights reserved.</p>
      <div class="flex gap-4">
        <a href="#" class="hover:text-slate-600 transition-colors">Bantuan</a>
        <a href="#" class="hover:text-slate-600 transition-colors">Syarat & Ketentuan</a>
      </div>
    </div>
  </footer>

</body>

</html>
<?php
session_start();
require "../config/database.php";

/* LOGIN */
if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

$user_id  = (int)$_SESSION['id'];
$fullname = $_SESSION['fullname'];

/* DATA TES */
$query = mysqli_query($conn, "
SELECT
tests.*,
ut.id AS user_test_id,
ut.status AS test_status,
ut.payment_status
FROM tests
LEFT JOIN user_tests ut
ON ut.id=(
SELECT id
FROM user_tests
WHERE
user_id='$user_id'
AND
test_id=tests.id
ORDER BY id DESC
LIMIT 1
)
WHERE tests.status='active'
ORDER BY tests.created_at DESC
");
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
  </style>
</head>

<body class="bg-[#f8fafc] text-slate-800 antialiased min-h-screen flex flex-col">

  <!-- ==========================
    NAVBAR
  ========================== -->
  <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="h-16 flex justify-between items-center">
        <div class="flex items-center gap-2">
          <div class="bg-indigo-600 text-white p-2 rounded-xl shadow-md shadow-indigo-200">
            <i class="fa-solid fa-brain text-xl"></i>
          </div>
          <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent">
            PsychoTest
          </span>
        </div>

        <div class="flex items-center gap-4 sm:gap-6">
          <span class="hidden sm:inline text-sm text-slate-600">
            Halo, <strong class="text-slate-900 font-semibold"><?= htmlspecialchars($fullname) ?></strong>
          </span>
          <a href="profile.php" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">
            <i class="fa-solid fa-circle-user text-lg"></i>
            <span class="hidden sm:inline">Profil</span>
          </a>
          <a href="../auth/logout.php" class="inline-flex items-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-600 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Keluar</span>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- ==========================
    MAIN CONTENT
  ========================== -->
  <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- HERO BANNER -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 rounded-2xl text-white p-6 sm:p-10 shadow-xl shadow-indigo-100 mb-10">
      <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500 rounded-full blur-3xl opacity-30"></div>
      <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-blue-400 rounded-full blur-3xl opacity-30"></div>

      <div class="relative z-10 max-w-2xl">
        <span class="bg-indigo-500/30 text-indigo-100 text-xs px-3 py-1 rounded-full font-medium tracking-wide uppercase">Workspace</span>
        <h1 class="text-2xl sm:text-4xl font-bold tracking-tight mt-3">
          Selamat Datang Kembali, <?= htmlspecialchars($fullname) ?> 👋
        </h1>
        <p class="mt-3 text-indigo-100/90 text-sm sm:text-base leading-relaxed">
          Temukan dan ukur potensi terbaikmu. Pilih modul psikotes aktif di bawah ini untuk memulai evaluasi dengan hasil terstandarisasi secara profesional.
        </p>
      </div>
    </div>

    <!-- SECTION TITLE -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Modul Tes Tersedia</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Silakan pilih dan ikuti instruksi pada masing-masing modul.</p>
      </div>
    </div>

    <!-- CARDS CONTAINER -->
    <div class="grid md:grid-cols-2 gap-6">
      <?php if (mysqli_num_rows($query) > 0): ?>
        <?php while ($test = mysqli_fetch_assoc($query)): ?>

          <div class="bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-6 flex flex-col justify-between">
            <div>
              <!-- CARD HEADER & BADGES -->
              <div class="flex justify-between items-start gap-4 mb-4">
                <span class="text-xs font-semibold tracking-wider text-slate-400 uppercase">MODUL</span>

                <div>
                  <?php if (!$test['test_status']): ?>
                    <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-600 px-2.5 py-1 rounded-md text-xs font-medium border border-slate-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Belum Mulai
                    </span>
                  <?php elseif ($test['test_status'] == "started"): ?>
                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-2.5 py-1 rounded-md text-xs font-medium border border-amber-200 animate-pulse">
                      <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Sedang Berjalan
                    </span>
                  <?php elseif ($test['payment_status'] == "verified"): ?>
                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-md text-xs font-medium border border-emerald-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                    </span>
                  <?php else: ?>
                    <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 px-2.5 py-1 rounded-md text-xs font-medium border border-rose-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Menunggu Pembayaran
                    </span>
                  <?php endif; ?>
                </div>
              </div>

              <!-- TITLE & DESCRIPTION -->
              <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                <?= htmlspecialchars($test['title']) ?>
              </h3>
              <p class="text-slate-500 text-sm mt-1.5 mb-6 line-clamp-2">
                <?= htmlspecialchars($test['description']) ?>
              </p>

              <!-- SPECS GRID -->
              <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-3 text-center">
                  <i class="fa-solid fa-stopwatch text-indigo-500 text-base mb-1.5"></i>
                  <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Durasi</p>
                  <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $test['duration'] ?> Min</p>
                </div>
                <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-3 text-center">
                  <i class="fa-solid fa-list-check text-emerald-500 text-base mb-1.5"></i>
                  <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Soal</p>
                  <p class="text-sm font-semibold text-slate-800 mt-0.5"><?= $test['total_questions'] ?> Butir</p>
                </div>
                <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-3 text-center">
                  <i class="fa-solid fa-tags text-blue-500 text-base mb-1.5"></i>
                  <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Biaya</p>
                  <p class="text-sm font-semibold text-slate-800 mt-0.5">
                    <?= $test['price'] == 0 ? 'Gratis' : 'Rp' . number_format($test['price'], 0, ",", ".") ?>
                  </p>
                </div>
              </div>
            </div>

            <!-- ACTION BUTTON -->
            <div class="pt-4 border-t border-slate-50 mt-auto flex justify-end">
              <?php if (!$test['test_status']): ?>
                <a href="start.php?id=<?= $test['id'] ?>"
                  class="w-full sm:w-auto text-center inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm shadow-indigo-100 transition-colors">
                  <i class="fa-solid fa-play text-xs"></i> Mulai Ujian
                </a>
              <?php elseif ($test['test_status'] == "started"): ?>
                <a href="test.php?user_test_id=<?= $test['user_test_id'] ?>"
                  class="w-full sm:w-auto text-center inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                  <i class="fa-solid fa-forward text-xs"></i> Lanjutkan
                </a>
              <?php elseif ($test['payment_status'] != "verified"): ?>
                <a href="payment.php?id=<?= $test['user_test_id'] ?>"
                  class="w-full sm:w-auto text-center inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                  <i class="fa-solid fa-wallet text-xs"></i> Bayar Sekarang
                </a>
              <?php else: ?>
                <a href="result.php?id=<?= $test['user_test_id'] ?>"
                  class="w-full sm:w-auto text-center inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                  <i class="fa-solid fa-square-poll-vertical text-xs"></i> Lihat Skor
                </a>
              <?php endif; ?>
            </div>
          </div>

        <?php endwhile; ?>
      <?php else: ?>

        <!-- EMPTY STATE -->
        <div class="col-span-2 bg-white border border-slate-100 rounded-2xl p-12 text-center shadow-sm max-w-lg mx-auto w-full">
          <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
            <i class="fa-regular fa-folder-open text-2xl"></i>
          </div>
          <h3 class="text-base font-bold text-slate-800">Modul Kosong</h3>
          <p class="text-xs text-slate-500 max-w-xs mx-auto mt-1 leading-relaxed">
            Saat ini belum ada lembar tes aktif yang dirilis oleh admin untuk akun Anda.
          </p>
        </div>

      <?php endif; ?>
    </div>
  </main>

  <!-- ==========================
    FOOTER
  ========================== -->
  <footer class="bg-white border-t border-slate-100 py-6 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
      <p>&copy; <?= date('Y') ?> PsychoTest Application. All rights reserved.</p>
      <div class="flex gap-4">
        <a href="#" class="hover:text-slate-600">Bantuan</a>
        <a href="#" class="hover:text-slate-600">Syarat & Ketentuan</a>
      </div>
    </div>
  </footer>

</body>

</html>
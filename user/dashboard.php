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

// QUERY UTAMA: Mengambil semua tes aktif beserta status pengerjaan user
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

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
  die("Kesalahan pada database: " . mysqli_error($conn));
}

// Menghitung statistik ringkas untuk komponen dashboard atas
$total_tests = 0;
$on_progress_count = 0;
$completed_count = 0;

$tests_data = [];
while ($row = mysqli_fetch_assoc($result)) {
  $tests_data[] = $row;
  $total_tests++;
  if (($row['test_status'] ?? '') === 'on_progress') $on_progress_count++;
  if (($row['test_status'] ?? '') === 'completed') $completed_count++;
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

    /* Animasi Tambahan */
    .fade-in-up {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }

    .fade-in-up.visible {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>

<body class="bg-[#f8fafc] text-slate-800 antialiased min-h-screen flex flex-col">

  <?php include "../includes/navbar_user.php"; ?>

  <!-- Container Utama dengan padding vertikal yang lebih lega -->
  <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- HERO BANNER -->
    <div class="fade-in-up relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 rounded-2xl text-white p-8 sm:p-12 shadow-xl shadow-indigo-100/50 mb-10">
      <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500 rounded-full blur-3xl opacity-30"></div>
      <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-blue-400 rounded-full blur-3xl opacity-30"></div>
      <div class="relative z-10 max-w-2xl">
        <span class="bg-indigo-500/40 text-white text-xs px-3 py-1 rounded-full font-semibold tracking-wide uppercase">Workspace</span>
        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight mt-4">Selamat Datang, <?= $fullname ?> 👋</h1>
        <p class="mt-3 text-indigo-100/90 text-sm sm:text-base leading-relaxed">
          Pilih salah satu modul tes psikologi di bawah ini untuk memulai pengerjaan. Hasil evaluasi Anda akan langsung tersimpan secara aman pada sistem kami.
        </p>
      </div>
    </div>

    <!-- UI BARU: KARTU STATISTIK RINGKAS -->
    <div class="fade-in-up grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10" style="transition-delay: 100ms;">
      <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 hover:scale-[1.02] transition-transform duration-300">
        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 text-lg">
          <i class="fa-solid fa-folder-open"></i>
        </div>
        <div>
          <p class="text-xs text-slate-400 font-medium">Total Tes Tersedia</p>
          <p class="text-xl font-bold text-slate-900 count-up" data-target="<?= $total_tests ?>">0</p>
        </div>
      </div>
      <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 hover:scale-[1.02] transition-transform duration-300">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 text-lg">
          <i class="fa-solid fa-spinner fa-spin-pulse"></i>
        </div>
        <div>
          <p class="text-xs text-slate-400 font-medium">Tes Sedang Berjalan</p>
          <p class="text-xl font-bold text-slate-900 count-up" data-target="<?= $on_progress_count ?>">0</p>
        </div>
      </div>
      <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 hover:scale-[1.02] transition-transform duration-300">
        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 text-lg">
          <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
          <p class="text-xs text-slate-400 font-medium">Tes Telah Selesai</p>
          <p class="text-xl font-bold text-slate-900 count-up" data-target="<?= $completed_count ?>">0</p>
        </div>
      </div>
    </div>

    <!-- KONTEN JUDUL & TAB FILTER -->
    <div class="fade-in-up flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8" style="transition-delay: 200ms;">
      <div>
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Modul Ujian Aktif</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Silakan pilih dan ikuti instruksi yang tertera pada masing-laki kartu tes</p>
      </div>

      <!-- UI BARU: Filter Tab Dinamis -->
      <div class="flex bg-slate-100 p-1 rounded-xl self-start sm:self-center">
        <button data-filter="all" class="filter-btn px-4 py-1.5 text-xs font-semibold rounded-lg bg-white text-slate-800 shadow-xs transition-all duration-200">Semua</button>
        <button data-filter="on_progress" class="filter-btn px-4 py-1.5 text-xs font-medium rounded-lg text-slate-500 hover:text-slate-800 transition-all duration-200">Berjalan</button>
        <button data-filter="completed" class="filter-btn px-4 py-1.5 text-xs font-medium rounded-lg text-slate-500 hover:text-slate-800 transition-all duration-200">Selesai</button>
      </div>
    </div>

    <!-- AREA GRID KARTU TES -->
    <div id="test-grid" class="fade-in-up grid md:grid-cols-2 lg:grid-cols-3 gap-6" style="transition-delay: 300ms;">

      <?php if ($total_tests > 0): ?>
        <?php foreach ($tests_data as $test):
          // Set atribut status untuk Javascript Filter
          $status_class = 'all';
          if (($test['test_status'] ?? '') === 'on_progress') {
            $status_class = 'on_progress';
          } elseif (($test['test_status'] ?? '') === 'completed') {
            $status_class = 'completed';
          }
        ?>

          <div data-status="<?= $status_class ?>" class="test-card bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-300 p-6 flex flex-col justify-between group">
            <div>
              <!-- Badge Atas & Status -->
              <div class="flex justify-between items-center gap-4 mb-4">
                <span class="text-[10px] font-bold tracking-wider text-indigo-600 bg-indigo-50/70 px-2.5 py-1 rounded-md uppercase border border-indigo-100/30">
                  <i class="fa-solid fa-graduation-cap mr-1"></i> Modul
                </span>

                <div>
                  <?php if (empty($test['test_status']) || $test['test_status'] == "pending"): ?>
                    <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-600 px-2.5 py-1 rounded-lg text-xs font-medium border border-slate-200">
                      <i class="fa-regular fa-circle text-[10px] text-slate-400"></i> Belum Dimulai
                    </span>
                  <?php elseif ($test['test_status'] == "on_progress"): ?>
                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-2.5 py-1 rounded-lg text-xs font-medium border border-amber-200">
                      <i class="fa-solid fa-circle-notch fa-spin text-[10px] text-amber-500"></i> Sedang Berjalan
                    </span>
                  <?php elseif ($test['test_status'] == "completed"): ?>
                    <?php if ($test['price'] > 0 && $test['payment_status'] != "paid"): ?>
                      <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 px-2.5 py-1 rounded-lg text-xs font-medium border border-rose-200">
                        <i class="fa-solid fa-receipt text-[10px] text-rose-500"></i> Menunggu Pembayaran
                      </span>
                    <?php else: ?>
                      <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-lg text-xs font-medium border border-emerald-200">
                        <i class="fa-solid fa-check-double text-[10px] text-emerald-500"></i> Selesai
                      </span>
                    <?php endif; ?>
                  <?php elseif ($test['test_status'] == "expired"): ?>
                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 px-2.5 py-1 rounded-lg text-xs font-medium border border-red-200">
                      <i class="fa-solid fa-clock-solid text-[10px] text-red-500"></i> Tes Berakhir
                    </span>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Judul & Deskripsi -->
              <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors mb-2"><?= htmlspecialchars($test['title']) ?></h3>
              <p class="text-slate-500 text-sm mb-6 line-clamp-2 leading-relaxed"><?= htmlspecialchars($test['description']) ?></p>

              <!-- Grid Detail Informasi Modul -->
              <div class="grid grid-cols-3 gap-2.5 mb-6">
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 text-center">
                  <i class="fa-solid fa-stopwatch text-indigo-500 text-sm mb-1"></i>
                  <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Waktu</p>
                  <p class="text-xs font-bold text-slate-800 mt-0.5"><?= $test['duration'] ?> Menit</p>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 text-center">
                  <i class="fa-solid fa-list-check text-emerald-500 text-sm mb-1"></i>
                  <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Soal</p>
                  <p class="text-xs font-bold text-slate-800 mt-0.5"><?= $test['total_questions'] ?> Butir</p>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 text-center">
                  <i class="fa-solid fa-tags text-blue-500 text-sm mb-1"></i>
                  <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Biaya</p>
                  <p class="text-xs font-bold text-slate-800 mt-0.5 whitespace-nowrap">
                    <?= $test['price'] == 0 ? 'Gratis' : 'Rp' . number_format($test['price'], 0, ",", ".") ?>
                  </p>
                </div>
              </div>
            </div>

            <!-- Tombol Aksi Bawah -->
            <div class="pt-4 border-t border-slate-100 mt-auto">
              <?php if (empty($test['test_status']) || $test['test_status'] == "pending"): ?>
                <a href="start.php?id=<?= $test['id'] ?>" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
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
                <button class="w-full inline-flex items-center justify-center gap-2 bg-slate-200 text-slate-500 px-5 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed" disabled>
                  <i class="fa-solid fa-clock-rotate-left text-xs"></i> Menunggu Verifikasi
                </button>
              <?php elseif ($test['test_status'] == "completed" && ($test['payment_status'] == "paid" || $test['price'] == 0)): ?>
                <a href="result.php?id=<?= $test['user_test_id'] ?>" class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                  <i class="fa-solid fa-chart-pie text-xs"></i> Lihat Hasil Tes
                </a>
              <?php elseif ($test['test_status'] == "expired"): ?>
                <button class="w-full inline-flex items-center justify-center gap-2 bg-red-100 text-red-500 px-5 py-2.5 rounded-xl text-sm font-semibold cursor-not-allowed" disabled>
                  <i class="fa-solid fa-ban text-xs"></i> Tes Berakhir
                </button>
              <?php endif; ?>
            </div>
          </div>

        <?php endforeach; ?>
      <?php else: ?>
        <!-- State Kosong jika tidak ada data -->
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

  <?php include "../includes/footer.php"; ?>

  <!-- ANIMASI JAVASCRIPT -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {

      // 1. Animasi Page Load Fade-in up
      const fadeElements = document.querySelectorAll('.fade-in-up');
      fadeElements.forEach((el) => {
        // Trigger class setelah render awal
        setTimeout(() => {
          el.classList.add('visible');
        }, 50);
      });

      // 2. Animasi Counter Angka Statistik
      const counters = document.querySelectorAll('.count-up');
      counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        if (target === 0) {
          counter.innerText = "0";
          return;
        }

        let count = 0;
        const speed = 40; // Semakin kecil semakin cepat
        const increment = Math.ceil(target / speed);

        const updateCount = () => {
          count += increment;
          if (count < target) {
            counter.innerText = count;
            setTimeout(updateCount, 20);
          } else {
            counter.innerText = target;
          }
        };
        updateCount();
      });

      // 3. Filter Tab Kartu Ujian Dinamis
      const filterButtons = document.querySelectorAll('.filter-btn');
      const testCards = document.querySelectorAll('.test-card');

      filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          // Atur styling tombol aktif
          filterButtons.forEach(b => {
            b.classList.remove('bg-white', 'text-slate-800', 'shadow-xs', 'font-semibold');
            b.classList.add('text-slate-500', 'font-medium');
          });
          btn.classList.add('bg-white', 'text-slate-800', 'shadow-xs', 'font-semibold');
          btn.classList.remove('text-slate-500', 'font-medium');

          // Logika Sembunyikan / Tampilkan dengan efek transisi mikro
          const filterValue = btn.getAttribute('data-filter');

          testCards.forEach(card => {
            card.style.transition = 'all 0.3s ease-out';
            if (filterValue === 'all' || card.getAttribute('data-status') === filterValue) {
              card.style.opacity = '1';
              card.style.transform = 'scale(1)';
              setTimeout(() => {
                card.style.display = 'flex';
              }, 50);
            } else {
              card.style.opacity = '0';
              card.style.transform = 'scale(0.95)';
              setTimeout(() => {
                card.style.display = 'none';
              }, 300);
            }
          });
        });
      });

    });
  </script>
</body>

</html>
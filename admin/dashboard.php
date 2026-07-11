<?php
session_start();

// Cek hak akses admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

// Koneksi database
include "../config/database.php";

$pageTitle = "Dashboard";

// Muat komponen halaman
include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";

/* =====================================
   STATISTIK SISTEM
===================================== */
$totalUsers     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'user'"))['total'] ?? 0;
$totalTests     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tests"))['total'] ?? 0;
$totalQuestions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM questions"))['total'] ?? 0;
$totalFinished  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user_tests WHERE status = 'finished'"))['total'] ?? 0;
$totalOngoing   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user_tests WHERE status = 'ongoing'"))['total'] ?? 0;
$totalPending   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user_tests WHERE payment_status = 'pending'"))['total'] ?? 0;
$totalPaid      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user_tests WHERE payment_status = 'paid'"))['total'] ?? 0;
$averageScore   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ROUND(AVG(score), 2) AS avg_score FROM user_tests WHERE score IS NOT NULL AND status = 'finished'"))['avg_score'] ?? 0;

/* =====================================
   DATA PESERTA & PEMBAYARAN
===================================== */
$users = mysqli_query($conn, "
    SELECT fullname, email, status, created_at
    FROM users WHERE role = 'user'
    ORDER BY created_at DESC LIMIT 5
");

$payments = mysqli_query($conn, "
    SELECT users.fullname, tests.title, user_tests.payment_status, user_tests.created_at
    FROM user_tests
    JOIN users ON users.id = user_tests.user_id
    JOIN tests ON tests.id = user_tests.test_id
    ORDER BY user_tests.created_at DESC LIMIT 5
");
?>

<div class="ml-64 mt-16 p-8 bg-gray-50 min-h-screen">

  <!-- Header Selamat Datang -->
  <div class="mb-8 bg-gradient-to-r from-indigo-600 to-blue-500 rounded-2xl p-6 text-white shadow-lg">
    <h1 class="text-3xl font-bold">Selamat Datang, <?= htmlspecialchars($_SESSION['fullname']) ?> 👋</h1>
    <p class="opacity-90 mt-2">Pantau dan kelola seluruh aktivitas sistem psikotes dari satu tempat.</p>
  </div>

  <!-- Kartu Statistik Baris 1 -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-medium">Total Peserta</p>
          <h3 class="text-3xl font-bold text-gray-800 mt-2"><?= $totalUsers ?></h3>
        </div>
        <div class="h-14 w-14 bg-blue-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-users text-blue-600 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-medium">Jenis Tes</p>
          <h3 class="text-3xl font-bold text-gray-800 mt-2"><?= $totalTests ?></h3>
        </div>
        <div class="h-14 w-14 bg-green-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-file-alt text-green-600 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-medium">Bank Soal</p>
          <h3 class="text-3xl font-bold text-gray-800 mt-2"><?= $totalQuestions ?></h3>
        </div>
        <div class="h-14 w-14 bg-yellow-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-question-circle text-yellow-600 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-medium">Tes Selesai</p>
          <h3 class="text-3xl font-bold text-gray-800 mt-2"><?= $totalFinished ?></h3>
        </div>
        <div class="h-14 w-14 bg-red-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-check-circle text-red-600 text-xl"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Kartu Statistik Baris 2 -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-medium">Tes Berlangsung</p>
          <h3 class="text-4xl font-bold text-blue-600 mt-2"><?= $totalOngoing ?></h3>
        </div>
        <div class="h-12 w-12 bg-blue-50 rounded-lg flex items-center justify-center">
          <i class="fa-solid fa-spinner text-blue-500"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-medium">Pembayaran Pending</p>
          <h3 class="text-4xl font-bold text-orange-500 mt-2"><?= $totalPending ?></h3>
        </div>
        <div class="h-12 w-12 bg-orange-50 rounded-lg flex items-center justify-center">
          <i class="fa-solid fa-clock text-orange-500"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-medium">Rata-rata Nilai</p>
          <h3 class="text-4xl font-bold text-green-600 mt-2"><?= $averageScore ?></h3>
        </div>
        <div class="h-12 w-12 bg-green-50 rounded-lg flex items-center justify-center">
          <i class="fa-solid fa-chart-line text-green-500"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Grafik Statistik -->
  <div class="bg-white rounded-2xl shadow-sm p-6 mt-8 border border-gray-100">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-bold text-gray-800">Ringkasan Aktivitas Sistem</h2>
      <span class="text-sm text-gray-400">Data terbaru</span>
    </div>
    <div class="h-80">
      <canvas id="statusChart"></canvas>
    </div>
  </div>

  <!-- Tabel Data Terbaru -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    <!-- Peserta Terbaru -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
      <div class="p-5 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-800">Peserta Terbaru</h3>
        <a href="users.php" class="text-sm text-indigo-600 hover:underline">Lihat Semua</a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
              <th class="p-4 text-left">Nama</th>
              <th class="p-4 text-left">Email</th>
              <th class="p-4 text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($users) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($users)): ?>
                <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors">
                  <td class="p-4 font-medium text-gray-800"><?= htmlspecialchars($row['fullname']) ?></td>
                  <td class="p-4 text-gray-500"><?= htmlspecialchars($row['email']) ?></td>
                  <td class="p-4 text-center">
                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-medium">
                      <?= htmlspecialchars($row['status']) ?>
                    </span>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="3" class="p-6 text-center text-gray-400">Belum ada data peserta</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pembayaran Terbaru -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
      <div class="p-5 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-800">Pembayaran Terbaru</h3>
        <a href="report.php" class="text-sm text-indigo-600 hover:underline">Lihat Semua</a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
              <th class="p-4 text-left">Nama</th>
              <th class="p-4 text-left">Tes</th>
              <th class="p-4 text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($payments) > 0): ?>
              <?php while ($pay = mysqli_fetch_assoc($payments)): ?>
                <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors">
                  <td class="p-4 font-medium text-gray-800"><?= htmlspecialchars($pay['fullname']) ?></td>
                  <td class="p-4 text-gray-600"><?= htmlspecialchars($pay['title']) ?></td>
                  <td class="p-4 text-center">
                    <?php if ($pay['payment_status'] === "paid"): ?>
                      <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Lunas</span>
                    <?php else: ?>
                      <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-medium">Menunggu</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="3" class="p-6 text-center text-gray-400">Belum ada data pembayaran</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script>
  window.dashboardData = {
    tesSelesai: <?= $totalFinished ?? 0 ?>,
    tesBerlangsung: <?= $totalOngoing ?? 0 ?>,
    pembayaranLunas: <?= $totalPaid ?? 0 ?>,
    pembayaranPending: <?= $totalPending ?? 0 ?>
  };
</script>

<script src="../assets/js/dashboard.js"></script>

<?php include "../includes/admin_footer.php"; ?>
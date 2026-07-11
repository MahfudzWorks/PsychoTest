<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

require "../../config/database.php";

$pageTitle = "Laporan Psikotes";

include "../../includes/admin_header.php";
include "../../includes/admin_sidebar.php";
include "../../includes/admin_navbar.php";

/* =====================================
   PENCARIAN & FILTER
===================================== */
$search = "";
$test = "";
$status = "";

if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if (isset($_GET['test'])) {
  $test = mysqli_real_escape_string($conn, $_GET['test']);
}

if (isset($_GET['status'])) {
  $status = mysqli_real_escape_string($conn, $_GET['status']);
}

$where = "WHERE user_tests.status = 'finished'";

if (!empty($search)) {
  $where .= " AND (
        users.fullname LIKE '%$search%'
        OR users.email LIKE '%$search%'
    )";
}

if (!empty($test)) {
  $where .= " AND user_tests.test_id = '$test'";
}

if (!empty($status)) {
  $where .= " AND user_tests.payment_status = '$status'";
}

/* =====================================
   AMBIL DATA LAPORAN
===================================== */
$query = mysqli_query($conn, "
  SELECT
    user_tests.*,
    users.fullname,
    users.email,
    tests.title
  FROM user_tests
  JOIN users ON users.id = user_tests.user_id
  JOIN tests ON tests.id = user_tests.test_id
  $where
  ORDER BY user_tests.created_at DESC
");

/* =====================================
   DAFTAR JENIS TES UNTUK FILTER
===================================== */
$listTest = mysqli_query($conn, "
  SELECT * FROM tests ORDER BY title ASC
");

/* =====================================
   STATISTIK
===================================== */
$totalParticipant = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(DISTINCT user_id) AS total FROM user_tests
"))['total'] ?? 0;

$totalFinished = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) AS total FROM user_tests WHERE status = 'finished'
"))['total'] ?? 0;

$totalIncome = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT SUM(tests.price) AS total
  FROM user_tests
  JOIN tests ON tests.id = user_tests.test_id
  WHERE payment_status = 'paid'
"))['total'] ?? 0;

$averageScore = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT AVG(score) AS avg_score FROM user_tests WHERE status = 'finished'
"))['avg_score'] ?? 0;
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">

  <!-- Judul & Tombol Export -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Laporan Psikotes</h1>
      <p class="text-gray-500 mt-1">Rekapitulasi seluruh hasil dan transaksi psikotes peserta</p>
    </div>
    <div class="flex gap-3">
      <a href="report_pdf.php?search=<?= urlencode($search) ?>&test=<?= urlencode($test) ?>&status=<?= urlencode($status) ?>"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2">
        <i class="fa-solid fa-file-pdf"></i> Export PDF
      </a>
      <a href="report_excel.php?search=<?= urlencode($search) ?>&test=<?= urlencode($test) ?>&status=<?= urlencode($status) ?>"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2">
        <i class="fa-solid fa-file-excel"></i> Export Excel
      </a>
    </div>
  </div>

  <!-- Kartu Statistik -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Total Peserta</p>
      <h2 class="text-3xl font-bold text-blue-600 mt-2"><?= $totalParticipant ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Tes Selesai</p>
      <h2 class="text-3xl font-bold text-green-600 mt-2"><?= $totalFinished ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Total Pendapatan</p>
      <h2 class="text-2xl font-bold text-purple-600 mt-2">Rp <?= number_format($totalIncome, 0, ",", ".") ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Nilai Rata-rata</p>
      <h2 class="text-3xl font-bold text-orange-500 mt-2"><?= round($averageScore) ?></h2>
    </div>
  </div>

  <!-- Form Pencarian & Filter -->
  <div class="bg-white rounded-xl shadow-md p-5 mb-6 border border-gray-100 card-animate">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
        placeholder="Cari nama atau email..."
        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200">

      <select name="test"
        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200">
        <option value="">Semua Jenis Tes</option>
        <?php while ($t = mysqli_fetch_assoc($listTest)): ?>
          <option value="<?= $t['id'] ?>" <?= ($test == $t['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($t['title']) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <select name="status"
        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200">
        <option value="">Semua Status Pembayaran</option>
        <option value="paid" <?= ($status == "paid") ? "selected" : "" ?>>Lunas</option>
        <option value="pending" <?= ($status == "pending") ? "selected" : "" ?>>Menunggu</option>
        <option value="rejected" <?= ($status == "rejected") ? "selected" : "" ?>>Ditolak</option>
      </select>

      <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition-all duration-200">
        <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari
      </button>
    </form>
  </div>

  <!-- Tabel Data Laporan -->
  <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 card-animate">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
        <tr>
          <th class="p-4 text-center w-16">No</th>
          <th class="p-4 text-left">Peserta</th>
          <th class="p-4 text-left">Jenis Tes</th>
          <th class="p-4 text-center">Nilai</th>
          <th class="p-4 text-center">Jawaban Benar</th>
          <th class="p-4 text-center">Jawaban Salah</th>
          <th class="p-4 text-center">Pembayaran</th>
          <th class="p-4 text-center">Tanggal Tes</th>
          <th class="p-4 text-center w-20">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if (mysqli_num_rows($query) > 0): ?>
          <?php $no = 1; ?>
          <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <?php
            // Warna nilai
            if ($row['score'] >= 80) {
              $scoreColor = "text-green-600";
            } elseif ($row['score'] >= 60) {
              $scoreColor = "text-yellow-600";
            } else {
              $scoreColor = "text-red-600";
            }

            // Status pembayaran
            switch ($row['payment_status']) {
              case "paid":
                $badge = "bg-green-100 text-green-700";
                $payText = "Lunas";
                break;
              case "pending":
                $badge = "bg-yellow-100 text-yellow-700";
                $payText = "Menunggu";
                break;
              default:
                $badge = "bg-red-100 text-red-700";
                $payText = "Ditolak";
                break;
            }
            ?>
            <tr class="hover:bg-blue-50/50 transition-colors duration-200">
              <td class="p-4 text-center text-gray-700"><?= $no++ ?></td>
              <td class="p-4">
                <div class="font-medium text-gray-800"><?= htmlspecialchars($row['fullname']) ?></div>
                <div class="text-xs text-gray-500"><?= htmlspecialchars($row['email']) ?></div>
              </td>
              <td class="p-4">
                <span class="font-medium text-blue-600"><?= htmlspecialchars($row['title']) ?></span>
              </td>
              <td class="p-4 text-center">
                <span class="font-bold text-lg <?= $scoreColor ?>"><?= $row['score'] ?></span>
              </td>
              <td class="p-4 text-center text-green-600 font-semibold"><?= $row['correct_answers'] ?></td>
              <td class="p-4 text-center text-red-600 font-semibold"><?= $row['wrong_answers'] ?></td>
              <td class="p-4 text-center">
                <span class="<?= $badge ?> px-2.5 py-1 rounded-full text-xs font-medium"><?= $payText ?></span>
              </td>
              <td class="p-4 text-center text-gray-600">
                <?= date("d M Y", strtotime($row['created_at'])) ?><br>
                <span class="text-xs"><?= date("H:i", strtotime($row['created_at'])) ?></span>
              </td>
              <td class="p-4 text-center">
                <a href="result_detail.php?id=<?= $row['id'] ?>"
                  class="bg-blue-600 hover:bg-blue-700 text-white w-8 h-8 rounded-lg inline-flex items-center justify-center transition-all duration-200 hover:scale-105"
                  title="Lihat Detail">
                  <i class="fa-solid fa-eye text-sm"></i>
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="9" class="py-12 text-center text-gray-400">
              <i class="fa-solid fa-file-chart-column text-4xl mb-3"></i>
              <p class="text-base">Belum ada data laporan yang tersedia</p>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

<!-- Efek Animasi -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card-animate');
    cards.forEach((card, index) => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      setTimeout(() => {
        card.style.transition = 'all 0.4s ease-out';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      }, index * 100);
    });
  });
</script>

<?php include "../../includes/admin_footer.php"; ?>
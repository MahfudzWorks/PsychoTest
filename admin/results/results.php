<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

require "../../config/database.php";

$pageTitle = "Hasil Psikotes";

include "../../includes/admin_header.php";
include "../../includes/admin_sidebar.php";
include "../../includes/admin_navbar.php";

$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';
$payment = $_GET['payment'] ?? '';
$test = $_GET['test'] ?? '';

$params = [];
$types = '';
$where = "WHERE 1=1";

if (!empty($search)) {
  $where .= " AND (users.fullname LIKE ? OR users.email LIKE ?)";
  $searchParam = "%$search%";
  $params[] = $searchParam;
  $params[] = $searchParam;
  $types .= 'ss';
}

if (!empty($status)) {
  $where .= " AND user_tests.status = ?";
  $params[] = $status;
  $types .= 's';
}

if (!empty($payment)) {
  $where .= " AND user_tests.payment_status = ?";
  $params[] = $payment;
  $types .= 's';
}

if (!empty($test)) {
  $where .= " AND user_tests.test_id = ?";
  $params[] = $test;
  $types .= 'i';
}

$stmt = mysqli_prepare($conn, "
    SELECT user_tests.*, users.fullname, users.email, tests.title, tests.price
    FROM user_tests
    JOIN users ON users.id = user_tests.user_id
    JOIN tests ON tests.id = user_tests.test_id
    $where
    ORDER BY user_tests.created_at DESC
");

if (!empty($params)) {
  mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);

$listTest = mysqli_query($conn, "SELECT * FROM tests ORDER BY title ASC");

$totalParticipant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT user_id) AS total FROM user_tests"))['total'] ?? 0;
$totalFinished = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user_tests WHERE status = 'completed'"))['total'] ?? 0;
$averageScore = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(score) AS avgscore FROM user_tests WHERE status = 'completed'"))['avgscore'] ?? 0;
$highestScore = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(score) AS highest FROM user_tests WHERE status = 'completed'"))['highest'] ?? 0;
$averageScore = round($averageScore);
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Hasil Psikotes</h1>
      <p class="text-gray-500 mt-1">Pantau seluruh hasil dan status tes peserta</p>
    </div>
  </div>

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
      <p class="text-gray-500 text-sm">Rata-rata Nilai</p>
      <h2 class="text-3xl font-bold text-yellow-500 mt-2"><?= $averageScore ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Nilai Tertinggi</p>
      <h2 class="text-3xl font-bold text-purple-600 mt-2"><?= $highestScore ?></h2>
    </div>
  </div>

  <div class="bg-white rounded-xl shadow-md p-5 mb-6 border border-gray-100 card-animate">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
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
        <option value="">Semua Status</option>
        <option value="pending" <?= ($status === "pending") ? "selected" : "" ?>>Belum Mulai</option>
        <option value="on_progress" <?= ($status === "on_progress") ? "selected" : "" ?>>Sedang Berjalan</option>
        <option value="completed" <?= ($status === "completed") ? "selected" : "" ?>>Selesai</option>
        <option value="expired" <?= ($status === "expired") ? "selected" : "" ?>>Kadaluarsa</option>
      </select>

      <select name="payment"
        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200">
        <option value="">Semua Pembayaran</option>
        <option value="unpaid" <?= ($payment === "unpaid") ? "selected" : "" ?>>Belum Bayar</option>
        <option value="pending" <?= ($payment === "pending") ? "selected" : "" ?>>Menunggu</option>
        <option value="paid" <?= ($payment === "paid") ? "selected" : "" ?>>Lunas</option>
        <option value="rejected" <?= ($payment === "rejected") ? "selected" : "" ?>>Ditolak</option>
      </select>

      <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition-all duration-200">
        <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari
      </button>
    </form>
  </div>

  <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 card-animate">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
        <tr>
          <th class="p-4 text-center w-16">No</th>
          <th class="p-4 text-left">Peserta</th>
          <th class="p-4 text-left">Jenis Tes</th>
          <th class="p-4 text-center">Waktu Mulai</th>
          <th class="p-4 text-center">Waktu Selesai</th>
          <th class="p-4 text-center">Nilai</th>
          <th class="p-4 text-center">Status Tes</th>
          <th class="p-4 text-center">Pembayaran</th>
          <th class="p-4 text-center w-24">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if (mysqli_num_rows($query) > 0): ?>
          <?php $no = 1; ?>
          <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <?php
            // Status Tes
            $statusDb = $row['status'] ?? 'pending';
            switch ($statusDb) {
              case 'completed':
                $statusText = 'Selesai';
                $statusBadge = 'bg-green-100 text-green-700';
                break;
              case 'on_progress':
                $statusText = 'Berjalan';
                $statusBadge = 'bg-yellow-100 text-yellow-700';
                break;
              case 'expired':
                $statusText = 'Kadaluarsa';
                $statusBadge = 'bg-red-100 text-red-700';
                break;
              default:
                $statusText = 'Belum Mulai';
                $statusBadge = 'bg-gray-100 text-gray-700';
            }

            // Status Pembayaran
            switch ($row['payment_status'] ?? 'unpaid') {
              case 'paid':
                $payText = 'Lunas';
                $payBadge = 'bg-green-100 text-green-700';
                break;
              case 'pending':
                $payText = 'Menunggu';
                $payBadge = 'bg-yellow-100 text-yellow-700';
                break;
              case 'rejected':
                $payText = 'Ditolak';
                $payBadge = 'bg-red-100 text-red-700';
                break;
              default:
                $payText = 'Belum Bayar';
                $payBadge = 'bg-gray-100 text-gray-700';
            }

            // Warna Nilai
            $score = $row['score'] ?? 0;
            if ($score >= 80) $scoreColor = 'bg-green-100 text-green-700';
            elseif ($score >= 60) $scoreColor = 'bg-blue-100 text-blue-700';
            elseif ($score >= 40) $scoreColor = 'bg-yellow-100 text-yellow-700';
            else $scoreColor = 'bg-red-100 text-red-700';
            ?>
            <tr class="hover:bg-blue-50/50 transition-colors duration-200">
              <td class="p-4 text-center text-gray-700"><?= $no++ ?></td>
              <td class="p-4">
                <div class="font-medium text-gray-800"><?= htmlspecialchars($row['fullname']) ?></div>
                <div class="text-xs text-gray-500"><?= htmlspecialchars($row['email']) ?></div>
              </td>
              <td class="p-4">
                <div class="font-medium text-blue-600"><?= htmlspecialchars($row['title']) ?></div>
                <div class="text-xs text-gray-500">Rp <?= number_format($row['price'], 0, ",", ".") ?></div>
              </td>
              <td class="p-4 text-center text-gray-600">
                <?php if (!empty($row['start_time'])): ?>
                  <?= date("d M Y", strtotime($row['start_time'])) ?><br>
                  <span class="text-xs"><?= date("H:i", strtotime($row['start_time'])) ?></span>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td class="p-4 text-center text-gray-600">
                <?php if (!empty($row['end_time'])): ?>
                  <?= date("d M Y", strtotime($row['end_time'])) ?><br>
                  <span class="text-xs"><?= date("H:i", strtotime($row['end_time'])) ?></span>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td class="p-4 text-center">
                <span class="<?= $scoreColor ?> px-2.5 py-1 rounded-full text-xs font-medium"><?= $score ?></span>
              </td>
              <td class="p-4 text-center">
                <span class="<?= $statusBadge ?> px-2.5 py-1 rounded-full text-xs font-medium"><?= $statusText ?></span>
              </td>
              <td class="p-4 text-center">
                <span class="<?= $payBadge ?> px-2.5 py-1 rounded-full text-xs font-medium"><?= $payText ?></span>
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
              <i class="fa-solid fa-folder-open text-4xl mb-3"></i>
              <p class="text-base">Belum ada data hasil tes yang tersedia</p>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.card-animate').forEach((card, index) => {
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
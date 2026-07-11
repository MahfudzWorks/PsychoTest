<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";

$pageTitle = "Verifikasi Pembayaran";

include "../../includes/admin_header.php";
include "../../includes/admin_sidebar.php";
include "../../includes/admin_navbar.php";

/* =====================================
   PENCARIAN & FILTER
===================================== */
$search = "";
$status = "";
$test = "";

if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if (isset($_GET['status'])) {
  $status = mysqli_real_escape_string($conn, $_GET['status']);
}

if (isset($_GET['test'])) {
  $test = mysqli_real_escape_string($conn, $_GET['test']);
}

$where = "WHERE 1=1";

if (!empty($search)) {
  $where .= " AND (
        users.fullname LIKE '%$search%'
        OR users.email LIKE '%$search%'
    )";
}

if (!empty($status)) {
  $where .= " AND user_tests.payment_status = '$status'";
}

if (!empty($test)) {
  $where .= " AND user_tests.test_id = '$test'";
}

/* =====================================
   DATA PEMBAYARAN
===================================== */
$query = mysqli_query($conn, "
  SELECT
    user_tests.*,
    users.fullname,
    users.email,
    tests.title,
    tests.price
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
$totalPending = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) AS total FROM user_tests WHERE payment_status = 'pending'
"))['total'] ?? 0;

$totalPaid = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) AS total FROM user_tests WHERE payment_status = 'paid'
"))['total'] ?? 0;

$totalRejected = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT COUNT(*) AS total FROM user_tests WHERE payment_status = 'rejected'
"))['total'] ?? 0;
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">

  <!-- Judul Halaman -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Verifikasi Pembayaran</h1>
      <p class="text-gray-500 mt-1">Kelola dan verifikasi bukti pembayaran peserta psikotes</p>
    </div>
  </div>

  <!-- Kartu Statistik -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Menunggu Verifikasi</p>
      <h2 class="text-3xl font-bold text-yellow-500 mt-2"><?= $totalPending ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Pembayaran Disetujui</p>
      <h2 class="text-3xl font-bold text-green-600 mt-2"><?= $totalPaid ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Pembayaran Ditolak</p>
      <h2 class="text-3xl font-bold text-red-600 mt-2"><?= $totalRejected ?></h2>
    </div>
  </div>

  <!-- Form Pencarian & Filter -->
  <div class="bg-white rounded-xl shadow-md p-5 mb-6 border border-gray-100 card-animate">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
        placeholder="Cari nama atau email peserta..."
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
        <option value="pending" <?= ($status == "pending") ? "selected" : "" ?>>Menunggu</option>
        <option value="paid" <?= ($status == "paid") ? "selected" : "" ?>>Lunas</option>
        <option value="rejected" <?= ($status == "rejected") ? "selected" : "" ?>>Ditolak</option>
      </select>

      <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition-all duration-200">
        <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari
      </button>
    </form>
  </div>

  <!-- Tabel Data Pembayaran -->
  <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 card-animate">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
        <tr>
          <th class="p-4 text-center w-16">No</th>
          <th class="p-4 text-left">Peserta</th>
          <th class="p-4 text-left">Jenis Tes</th>
          <th class="p-4 text-center">Harga</th>
          <th class="p-4 text-center">Bukti Bayar</th>
          <th class="p-4 text-center">Tanggal Upload</th>
          <th class="p-4 text-center">Status</th>
          <th class="p-4 text-center w-24">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if (mysqli_num_rows($query) > 0): ?>
          <?php $no = 1; ?>
          <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <?php
            switch ($row['payment_status']) {
              case "paid":
                $badge = "bg-green-100 text-green-700";
                $statusText = "Lunas";
                break;
              case "pending":
                $badge = "bg-yellow-100 text-yellow-700";
                $statusText = "Menunggu";
                break;
              default:
                $badge = "bg-red-100 text-red-700";
                $statusText = "Ditolak";
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
                <div class="font-medium text-blue-600"><?= htmlspecialchars($row['title']) ?></div>
              </td>
              <td class="p-4 text-center font-medium text-green-600">
                Rp <?= number_format($row['price'], 0, ",", ".") ?>
              </td>
              <td class="p-4 text-center">
                <?php if (!empty($row['payment_proof'])): ?>
                  <a href="../../assets/uploads/payments/<?= htmlspecialchars($row['payment_proof']) ?>" target="_blank"
                    class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition-colors text-xs font-medium inline-flex items-center gap-1">
                    <i class="fa-solid fa-image"></i> Lihat
                  </a>
                <?php else: ?>
                  <span class="bg-gray-100 text-gray-500 px-3 py-1.5 rounded-lg text-xs font-medium">Belum Upload</span>
                <?php endif; ?>
              </td>
              <td class="p-4 text-center text-gray-600 text-xs">
                <?php if (!empty($row['payment_date'])): ?>
                  <?= date("d M Y", strtotime($row['payment_date'])) ?><br>
                  <?= date("H:i", strtotime($row['payment_date'])) ?>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td class="p-4 text-center">
                <span class="<?= $badge ?> px-2.5 py-1 rounded-full text-xs font-medium"><?= $statusText ?></span>
              </td>
              <td class="p-4 text-center">
                <div class="flex justify-center gap-2">
                  <?php if ($row['payment_status'] == "pending" && !empty($row['payment_proof'])): ?>
                    <a href="payment_approve.php?id=<?= $row['id'] ?>" onclick="return confirm('Setujui pembayaran ini?')"
                      class="bg-green-600 hover:bg-green-700 text-white w-7 h-7 rounded-lg inline-flex items-center justify-center transition-all hover:scale-105"
                      title="Setujui">
                      <i class="fa-solid fa-check text-sm"></i>
                    </a>
                    <a href="payment_reject.php?id=<?= $row['id'] ?>" onclick="return confirm('Tolak pembayaran ini?')"
                      class="bg-red-600 hover:bg-red-700 text-white w-7 h-7 rounded-lg inline-flex items-center justify-center transition-all hover:scale-105"
                      title="Tolak">
                      <i class="fa-solid fa-xmark text-sm"></i>
                    </a>
                  <?php else: ?>
                    <span class="text-gray-400">-</span>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="py-12 text-center text-gray-400">
              <i class="fa-solid fa-receipt text-4xl mb-3"></i>
              <p class="text-base">Belum ada data pembayaran yang tersedia</p>
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
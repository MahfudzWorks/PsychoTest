<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";

$pageTitle = "Kelola Jenis Tes";

include "../../includes/admin_header.php";
include "../../includes/admin_sidebar.php";
include "../../includes/admin_navbar.php";

/* ==========================
   PENCARIAN
========================== */
$search = "";
if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
}

/* ==========================
   AMBIL DATA TES
========================== */
$query = mysqli_query($conn, "
    SELECT *
    FROM tests
    WHERE title LIKE '%$search%'
    ORDER BY created_at DESC
");

/* ==========================
   STATISTIK
========================== */
$total = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total FROM tests
"))['total'] ?? 0;

$active = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total FROM tests WHERE status='active'
"))['total'] ?? 0;

$inactive = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total FROM tests WHERE status='inactive'
"))['total'] ?? 0;
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">

  <!-- Judul Halaman -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Kelola Jenis Tes</h1>
      <p class="text-gray-500 mt-1">Kelola seluruh paket psikotes yang tersedia</p>
    </div>
    <a href="test_create.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-md transition-all duration-300 hover:shadow-lg">
      <i class="fa-solid fa-plus mr-2"></i> Tambah Tes
    </a>
  </div>

  <!-- Kartu Statistik -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Total Tes</p>
      <h2 class="text-3xl font-bold text-blue-600 mt-2"><?= $total ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Tes Aktif</p>
      <h2 class="text-3xl font-bold text-green-600 mt-2"><?= $active ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Tes Tidak Aktif</p>
      <h2 class="text-3xl font-bold text-red-600 mt-2"><?= $inactive ?></h2>
    </div>
  </div>

  <!-- Form Pencarian -->
  <div class="bg-white rounded-xl shadow-md p-5 mb-6 border border-gray-100 card-animate">
    <form method="GET" class="flex flex-col md:flex-row gap-3">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
        placeholder="Cari nama atau deskripsi tes..."
        class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200">
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl shadow-sm transition-all duration-300 hover:shadow-md">
        <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari
      </button>
    </form>
  </div>

  <!-- Tabel Data -->
  <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 card-animate">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
        <tr>
          <th class="p-4 text-center w-16">No</th>
          <th class="p-4 text-left">Nama Tes</th>
          <th class="p-4 text-center">Durasi</th>
          <th class="p-4 text-center">Jumlah Soal</th>
          <th class="p-4 text-center">Status</th>
          <th class="p-4 text-center w-32">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if (mysqli_num_rows($query) > 0) : ?>
          <?php $no = 1; ?>
          <?php while ($row = mysqli_fetch_assoc($query)) : ?>
            <tr class="hover:bg-blue-50/50 transition-colors duration-200">
              <td class="p-4 text-center text-gray-700"><?= $no++ ?></td>
              <td class="p-4">
                <div class="font-medium text-gray-800"><?= htmlspecialchars($row['title']) ?></div>
                <small class="text-gray-500"><?= htmlspecialchars($row['description'] ?? '-') ?></small>
              </td>
              <td class="p-4 text-center text-gray-600"><?= $row['duration'] ?> Menit</td>
              <td class="p-4 text-center text-gray-600"><?= $row['total_questions'] ?? 0 ?></td>
              <td class="p-4 text-center">
                <?php if ($row['status'] == 'active') : ?>
                  <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">Aktif</span>
                <?php else : ?>
                  <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">Tidak Aktif</span>
                <?php endif; ?>
              </td>
              <td class="p-4">
                <div class="flex justify-center gap-2">
                  <a href="test_edit.php?id=<?= $row['id'] ?>" class="bg-amber-500 hover:bg-amber-600 text-white w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105" title="Edit">
                    <i class="fa-solid fa-pen text-sm"></i>
                  </a>
                  <a href="test_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus tes ini?')" class="bg-red-600 hover:bg-red-700 text-white w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105" title="Hapus">
                    <i class="fa-solid fa-trash text-sm"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else : ?>
          <tr>
            <td colspan="6" class="py-12 text-center text-gray-400">
              <i class="fa-solid fa-folder-open text-4xl mb-3"></i>
              <p class="text-base">Data tes belum tersedia</p>
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
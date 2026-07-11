<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";

$pageTitle = "Kelola Soal";

include "../../includes/admin_header.php";
include "../../includes/admin_sidebar.php";
include "../../includes/admin_navbar.php";

/* ==========================
   PENCARIAN & FILTER
========================== */
$search = "";
$test = "";

if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if (isset($_GET['test'])) {
  $test = mysqli_real_escape_string($conn, $_GET['test']);
}

$where = "WHERE 1=1";

if (!empty($search)) {
  $where .= " AND questions.question LIKE '%$search%'";
}

if (!empty($test)) {
  $where .= " AND questions.test_id = '$test'";
}

/* ==========================
   DATA SOAL
========================== */
$query = mysqli_query($conn, "
    SELECT
        questions.*,
        tests.title
    FROM questions
    JOIN tests ON tests.id = questions.test_id
    $where
    ORDER BY questions.created_at DESC
");

/* ==========================
   DAFTAR JENIS TES
========================== */
$listTest = mysqli_query($conn, "
    SELECT * FROM tests ORDER BY title ASC
");

/* ==========================
   STATISTIK
========================== */
$totalQuestion = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total FROM questions
"))['total'] ?? 0;

$totalTest = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total FROM tests
"))['total'] ?? 0;

$avgQuestion = ($totalTest > 0) ? round($totalQuestion / $totalTest) : 0;
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">

  <!-- Judul & Tombol Tambah -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Kelola Soal</h1>
      <p class="text-gray-500 mt-1">Tambah, ubah, dan hapus soal untuk setiap jenis psikotes</p>
    </div>
    <a href="question_create.php"
      class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2">
      <i class="fa-solid fa-plus"></i> Tambah Soal
    </a>
  </div>

  <!-- Kartu Statistik -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Total Soal</p>
      <h2 class="text-3xl font-bold text-blue-600 mt-2"><?= $totalQuestion ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Jenis Tes</p>
      <h2 class="text-3xl font-bold text-green-600 mt-2"><?= $totalTest ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Rata-rata per Tes</p>
      <h2 class="text-3xl font-bold text-purple-600 mt-2"><?= $avgQuestion ?></h2>
    </div>
  </div>

  <!-- Form Pencarian & Filter -->
  <div class="bg-white rounded-xl shadow-md p-5 mb-6 border border-gray-100 card-animate">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
        placeholder="Cari isi soal..."
        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200">

      <select name="test"
        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200">
        <option value="">Semua Jenis Tes</option>
        <?php while ($t = mysqli_fetch_assoc($listTest)) : ?>
          <option value="<?= $t['id'] ?>" <?= ($test == $t['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($t['title']) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition-all duration-200">
        <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari
      </button>
    </form>
  </div>

  <!-- Tabel Daftar Soal -->
  <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 card-animate">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
        <tr>
          <th class="p-4 text-center w-16">No</th>
          <th class="p-4 text-left">Jenis Tes</th>
          <th class="p-4 text-left">Isi Soal</th>
          <th class="p-4 text-center">Jawaban Benar</th>
          <th class="p-4 text-center">Tanggal Dibuat</th>
          <th class="p-4 text-center w-24">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if (mysqli_num_rows($query) > 0) : ?>
          <?php $no = 1; ?>
          <?php while ($row = mysqli_fetch_assoc($query)) : ?>
            <?php
            // Warna untuk jawaban
            $color = "bg-gray-100 text-gray-700";
            switch ($row['answer']) {
              case 'A':
                $color = "bg-green-100 text-green-700";
                break;
              case 'B':
                $color = "bg-blue-100 text-blue-700";
                break;
              case 'C':
                $color = "bg-yellow-100 text-yellow-700";
                break;
              case 'D':
                $color = "bg-purple-100 text-purple-700";
                break;
              case 'E':
                $color = "bg-pink-100 text-pink-700";
                break;
            }
            ?>
            <tr class="hover:bg-blue-50/50 transition-colors duration-200">
              <td class="p-4 text-center text-gray-700"><?= $no++ ?></td>
              <td class="p-4">
                <div class="font-medium text-blue-600"><?= htmlspecialchars($row['title']) ?></div>
              </td>
              <td class="p-4 text-gray-800">
                <?= htmlspecialchars(mb_strimwidth($row['question'], 0, 100, "...")) ?>
              </td>
              <td class="p-4 text-center">
                <span class="<?= $color ?> px-3 py-1 rounded-full text-xs font-medium">
                  <?= $row['answer'] ?>
                </span>
              </td>
              <td class="p-4 text-center text-gray-600 text-xs">
                <?= date('d M Y', strtotime($row['created_at'])) ?>
              </td>
              <td class="p-4 text-center">
                <div class="flex justify-center gap-2">
                  <a href="question_edit.php?id=<?= $row['id'] ?>"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white w-7 h-7 rounded-lg inline-flex items-center justify-center transition-all hover:scale-105"
                    title="Ubah Soal">
                    <i class="fa-solid fa-pen text-sm"></i>
                  </a>
                  <a href="question_delete.php?id=<?= $row['id'] ?>"
                    onclick="return confirm('Yakin ingin menghapus soal ini? Tindakan tidak dapat dibatalkan!')"
                    class="bg-red-600 hover:bg-red-700 text-white w-7 h-7 rounded-lg inline-flex items-center justify-center transition-all hover:scale-105"
                    title="Hapus Soal">
                    <i class="fa-solid fa-trash text-sm"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else : ?>
          <tr>
            <td colspan="6" class="py-12 text-center text-gray-400">
              <i class="fa-solid fa-question-circle text-4xl mb-3"></i>
              <p class="text-base">Belum ada data soal yang tersedia</p>
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
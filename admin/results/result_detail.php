<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

require "../../config/database.php";

$pageTitle = "Detail Hasil Tes";

include "../../includes/admin_header.php";
include "../../includes/admin_sidebar.php";
include "../../includes/admin_navbar.php";

/* =====================================
   VALIDASI ID
===================================== */
if (!isset($_GET['id'])) {
  header("Location: results.php");
  exit;
}

$user_test_id = (int)$_GET['id'];

/* =====================================
   AMBIL DATA PESERTA & TES
===================================== */
$query = mysqli_query($conn, "
  SELECT
    user_tests.*,
    users.fullname,
    users.email,
    tests.title,
    tests.duration,
    tests.total_questions
  FROM user_tests
  JOIN users ON users.id = user_tests.user_id
  JOIN tests ON tests.id = user_tests.test_id
  WHERE user_tests.id = '$user_test_id'
  LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
  echo "<script>alert('Data tidak ditemukan.'); window.location='results.php';</script>";
  exit;
}

$data = mysqli_fetch_assoc($query);

/* =====================================
   HITUNG LAMA PENGERJAAN
===================================== */
$duration = "-";
if (!empty($data['start_time']) && !empty($data['end_time'])) {
  $start = strtotime($data['start_time']);
  $end = strtotime($data['end_time']);
  $selisih = $end - $start;
  $menit = floor($selisih / 60);
  $detik = $selisih % 60;
  $duration = $menit . " Menit " . $detik . " Detik";
}

/* =====================================
   AMBIL DATA SOAL & JAWABAN
===================================== */
$answers = mysqli_query($conn, "
  SELECT
    questions.*,
    user_answers.answer AS user_answer
  FROM questions
  LEFT JOIN user_answers 
    ON user_answers.question_id = questions.id 
    AND user_answers.user_test_id = '$user_test_id'
  WHERE questions.test_id = '" . $data['test_id'] . "'
  ORDER BY questions.id ASC
");
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">

  <!-- Judul Halaman -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Detail Hasil Tes</h1>
      <p class="text-gray-500 mt-1">Lihat rincian lengkap pengerjaan tes peserta</p>
    </div>
    <a href="results.php" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl shadow-md transition-all duration-300 hover:shadow-lg flex items-center gap-2">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- Informasi Peserta & Tes -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Informasi Peserta -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <h3 class="text-lg font-bold mb-4 text-blue-600 border-b pb-2">Informasi Peserta</h3>
      <table class="w-full space-y-2">
        <tr>
          <td class="py-2 text-gray-500 w-32">Nama Lengkap</td>
          <td class="font-medium text-gray-800"><?= htmlspecialchars($data['fullname']) ?></td>
        </tr>
        <tr>
          <td class="py-2 text-gray-500">Email</td>
          <td class="font-medium text-gray-800"><?= htmlspecialchars($data['email']) ?></td>
        </tr>
        <tr>
          <td class="py-2 text-gray-500">Status Tes</td>
          <td>
            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
              <?= $data['status'] == 'finished' ? 'Selesai' : 'Berjalan' ?>
            </span>
          </td>
        </tr>
      </table>
    </div>

    <!-- Informasi Tes -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <h3 class="text-lg font-bold mb-4 text-purple-600 border-b pb-2">Informasi Tes</h3>
      <table class="w-full space-y-2">
        <tr>
          <td class="py-2 text-gray-500 w-32">Nama Tes</td>
          <td class="font-medium text-gray-800"><?= htmlspecialchars($data['title']) ?></td>
        </tr>
        <tr>
          <td class="py-2 text-gray-500">Jumlah Soal</td>
          <td class="font-medium text-gray-800"><?= $data['total_questions'] ?> Butir</td>
        </tr>
        <tr>
          <td class="py-2 text-gray-500">Durasi Tes</td>
          <td class="font-medium text-gray-800"><?= $data['duration'] ?> Menit</td>
        </tr>
        <tr>
          <td class="py-2 text-gray-500">Lama Pengerjaan</td>
          <td class="font-medium text-gray-800"><?= $duration ?></td>
        </tr>
      </table>
    </div>
  </div>

  <!-- Ringkasan Nilai -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Nilai Akhir</p>
      <h2 class="text-4xl font-bold text-blue-600 mt-2"><?= $data['score'] ?? 0 ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Jawaban Benar</p>
      <h2 class="text-4xl font-bold text-green-600 mt-2"><?= $data['correct_answers'] ?? 0 ?></h2>
    </div>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-100 card-animate">
      <p class="text-gray-500 text-sm">Jawaban Salah</p>
      <h2 class="text-4xl font-bold text-red-600 mt-2"><?= $data['wrong_answers'] ?? 0 ?></h2>
    </div>
  </div>

  <!-- Daftar Jawaban -->
  <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 card-animate">
    <div class="p-4 border-b border-gray-100 bg-gray-50">
      <h3 class="text-lg font-semibold text-gray-800">Rincian Jawaban Peserta</h3>
    </div>
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
        <tr>
          <th class="p-4 text-center w-16">No</th>
          <th class="p-4 text-left">Soal</th>
          <th class="p-4 text-center">Jawaban Peserta</th>
          <th class="p-4 text-center">Jawaban Benar</th>
          <th class="p-4 text-center">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if (mysqli_num_rows($answers) > 0): ?>
          <?php $no = 1; ?>
          <?php while ($row = mysqli_fetch_assoc($answers)): ?>
            <?php
            $status = "Belum Dijawab";
            $badge = "bg-gray-100 text-gray-700";

            if (!empty($row['user_answer'])) {
              if ($row['user_answer'] == $row['answer']) {
                $status = "Benar";
                $badge = "bg-green-100 text-green-700";
              } else {
                $status = "Salah";
                $badge = "bg-red-100 text-red-700";
              }
            }
            ?>
            <tr class="hover:bg-blue-50/50 transition-colors duration-200">
              <td class="p-4 text-center text-gray-700"><?= $no++ ?></td>
              <td class="p-4 text-gray-800"><?= htmlspecialchars($row['question']) ?></td>
              <td class="p-4 text-center">
                <?php if (!empty($row['user_answer'])): ?>
                  <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                    <?= htmlspecialchars($row['user_answer']) ?>
                  </span>
                <?php else: ?>
                  <span class="text-gray-400">-</span>
                <?php endif; ?>
              </td>
              <td class="p-4 text-center">
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                  <?= htmlspecialchars($row['answer']) ?>
                </span>
              </td>
              <td class="p-4 text-center">
                <span class="<?= $badge ?> px-3 py-1 rounded-full text-xs font-medium"><?= $status ?></span>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="py-12 text-center text-gray-400">
              <i class="fa-solid fa-clipboard-list text-4xl mb-3"></i>
              <p class="text-base">Belum ada data jawaban yang tersedia</p>
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
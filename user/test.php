<?php
session_start();
require "../config/database.php";

/* =====================================
   LOGIN
===================================== */
if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

/* =====================================
   VALIDASI USER TEST
===================================== */
if (!isset($_GET['user_test_id'])) {
  header("Location: dashboard.php");
  exit;
}

$user_test_id = (int) $_GET['user_test_id'];
$user_id = (int) $_SESSION['id'];

/* =====================================
   AMBIL DATA USER TEST
===================================== */
$query = mysqli_query($conn, "
    SELECT user_tests.*, tests.title, tests.description, tests.duration, tests.total_questions 
    FROM user_tests 
    JOIN tests ON tests.id=user_tests.test_id 
    WHERE user_tests.id='$user_test_id' AND user_tests.user_id='$user_id' 
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
  echo "<script>
    alert('Data tes tidak ditemukan.');
    window.location='dashboard.php';
  </script>";
  exit;
}

$userTest = mysqli_fetch_assoc($query);

/* =====================================
   STATUS
===================================== */
if ($userTest['status'] == 'completed') {
  header("Location: finish.php?id=" . $user_test_id);
  exit;
}

/* =====================================
   AMBIL SOAL
===================================== */
$questionQuery = mysqli_query($conn, "
    SELECT * FROM questions 
    WHERE test_id='" . $userTest['test_id'] . "' 
    ORDER BY id ASC
");

$questions = [];
while ($row = mysqli_fetch_assoc($questionQuery)) {
  $questions[] = $row;
}

$totalQuestions = count($questions);

if ($totalQuestions == 0) {
  echo "<script>
    alert('Soal belum tersedia.');
    window.location='dashboard.php';
  </script>";
  exit;
}

/* =====================================
   AMBIL JAWABAN YANG SUDAH DISIMPAN
===================================== */
$answers = [];
$answerQuery = mysqli_query($conn, "
    SELECT * FROM user_answers 
    WHERE user_test_id='$user_test_id'
");

while ($row = mysqli_fetch_assoc($answerQuery)) {
  $answers[$row['question_id']] = $row['answer'];
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($userTest['title']) ?></title>

  <!-- Tailwind CSS & Fonts -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">

  <script>
    /* ============================
       DATA DARI PHP
    ============================ */
    const userTestId = <?= $user_test_id ?>;
    const totalQuestions = <?= $totalQuestions ?>;
    const remainingTime = <?= (int)$userTest['remaining_time'] ?>;
    const questions = <?= json_encode($questions, JSON_UNESCAPED_UNICODE); ?>;
    const savedAnswers = <?= json_encode($answers, JSON_UNESCAPED_UNICODE); ?>;
  </script>

  <!-- Navbar -->
  <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="h-20 flex justify-between items-center">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-blue-600 tracking-tight flex items-center gap-2">
            <span>🧠</span> PsychoTest
          </h1>
          <p class="text-xs sm:text-sm text-gray-500 font-medium truncate max-w-[200px] sm:max-w-none">
            <?= htmlspecialchars($userTest['title']) ?>
          </p>
        </div>

        <div class="bg-red-50 border border-red-100 px-4 py-2 rounded-xl text-center shadow-sm">
          <p class="text-[10px] uppercase tracking-wider font-bold text-red-500">Sisa Waktu</p>
          <h2 id="timer" class="text-xl sm:text-2xl font-bold text-red-600 tabular-nums">--:--:--</h2>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content Container -->
  <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 my-6 flex-grow">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

      <!-- ==========================
           AREA SOAL (KIRI / ATAS)
      =========================== -->
      <div class="col-span-1 lg:col-span-9 space-y-4">
        <!-- Quick Link Navigation untuk Mobile -->
        <div class="lg:hidden flex justify-between items-center bg-blue-50 border border-blue-100 p-4 rounded-2xl">
          <span class="text-sm font-medium text-blue-800">Ujian sedang berjalan</span>
          <a href="#navigasi-soal" class="text-xs font-semibold bg-blue-600 text-white px-3 py-1.5 rounded-lg shadow-sm">
            <i class="fa-solid fa-list-numeric mr-1"></i> Lihat Semua Nomor
          </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 sm:p-8">
          <!-- Header Soal -->
          <div class="border-b border-gray-100 pb-4 mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <div>
              <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider">
                Lembar Ujian
              </span>
              <p class="text-sm text-gray-500 mt-1.5 font-medium">
                Pertanyaan <span id="currentNumber" class="text-gray-900 font-bold">1</span> dari <span class="font-bold"><?= $totalQuestions ?></span>
              </p>
            </div>
          </div>

          <!-- Wadah Soal JAVASCRIPT -->
          <div id="questionBox" class="prose max-w-none min-h-[150px] text-gray-700">
            <!-- Isi soal & pilihan ganda akan di-inject oleh test.js -->
          </div>

          <!-- Tombol Aksi Navigasi -->
          <div class="flex justify-between items-center border-t border-gray-100 pt-6 mt-8">
            <button id="prevBtn" class="inline-flex items-center justify-center bg-white hover:bg-gray-50 text-gray-700 font-medium px-5 py-3 border border-gray-300 rounded-xl transition shadow-sm active:scale-95 disabled:opacity-50 disabled:pointer-events-none">
              <i class="fa-solid fa-arrow-left mr-2 text-sm"></i> Sebelum
            </button>

            <button id="nextBtn" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition shadow-sm hover:shadow active:scale-95">
              Berikut <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- ==========================
           SIDEBAR NOMOR (KANAN / BAWAH)
      =========================== -->
      <div id="navigasi-soal" class="col-span-1 lg:col-span-3 lg:sticky lg:top-28">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
          <h3 class="font-bold text-gray-900 text-base mb-4 flex items-center gap-2">
            <i class="fa-solid fa-th-large text-blue-500"></i> Navigasi Soal
          </h3>

          <!-- Grid Nomor Soal -->
          <div id="numberContainer" class="grid grid-cols-5 gap-2 max-h-[280px] overflow-y-auto pr-1">
            <?php foreach ($questions as $index => $q): ?>
              <button class="numberBtn h-10 w-full rounded-xl border border-gray-200 text-sm font-semibold inline-flex items-center justify-center transition-all duration-200 hover:border-blue-500 hover:bg-blue-50 active:scale-95" data-index="<?= $index ?>">
                <?= $index + 1 ?>
              </button>
            <?php endforeach; ?>
          </div>

          <!-- Panel Informasi Progress -->
          <div class="mt-6 pt-6 border-t border-gray-100 space-y-3 text-xs font-medium text-gray-500">
            <div class="flex justify-between items-center">
              <span>Total Soal</span>
              <span class="text-gray-900 font-bold bg-gray-100 px-2 py-0.5 rounded-md"><?= $totalQuestions ?></span>
            </div>
            <div class="flex justify-between items-center">
              <span>Sudah Dijawab</span>
              <span id="answeredCount" class="text-green-700 font-bold bg-green-50 px-2 py-0.5 rounded-md">0</span>
            </div>
            <div class="flex justify-between items-center">
              <span>Belum Dijawab</span>
              <span id="unansweredCount" class="text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md"><?= $totalQuestions ?></span>
            </div>
          </div>

          <!-- Tombol Selesai -->
          <button id="finishBtn" class="mt-6 w-full inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3.5 px-4 rounded-xl transition shadow-sm hover:shadow active:scale-95">
            <i class="fa-solid fa-circle-check mr-2 text-base"></i> Selesaikan Tes
          </button>
        </div>
      </div>

    </div>
  </main>

  <script src="../assets/js/test.js"></script>
</body>

</html>
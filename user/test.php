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
SELECT

user_tests.*,
tests.title,
tests.description,
tests.duration,
tests.total_questions

FROM user_tests

JOIN tests
ON tests.id=user_tests.test_id

WHERE

user_tests.id='$user_test_id'
AND
user_tests.user_id='$user_id'

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

if ($userTest['status'] == 'finished') {

  header("Location: finish.php?id=" . $user_test_id);
  exit;
}

/* =====================================
   AMBIL SOAL
===================================== */

$questionQuery = mysqli_query($conn, "

SELECT *

FROM questions

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

SELECT *

FROM user_answers

WHERE user_test_id='$user_test_id'

");

while ($row = mysqli_fetch_assoc($answerQuery)) {

  $answers[$row['question_id']] = $row['answer'];
}

?>
<!DOCTYPE html>

<html lang="id">

<head>

  <meta charset="UTF-8">

  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

  <title>

    <?= htmlspecialchars($userTest['title']) ?>

  </title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-gray-100 font-[Poppins]">

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

  <nav class="bg-white shadow">

    <div class="max-w-7xl mx-auto px-6">

      <div class="h-20 flex justify-between items-center">

        <div>

          <h1 class="text-3xl font-bold text-blue-600">

            🧠 PsychoTest

          </h1>

          <p class="text-sm text-gray-500">

            <?= htmlspecialchars($userTest['title']) ?>

          </p>

        </div>

        <div class="text-right">

          <p class="text-sm text-gray-500">

            Sisa Waktu

          </p>

          <h2
            id="timer"
            class="text-3xl font-bold text-red-600">

            --:--:--

          </h2>

        </div>

      </div>

    </div>

  </nav>

  <div class="max-w-7xl mx-auto mt-8 px-6">

    <div class="grid grid-cols-12 gap-6">

      <!-- ==========================
             SOAL
        =========================== -->

      <div class="col-span-9">

        <div class="bg-white rounded-2xl shadow p-8">

          <div class="flex justify-between items-center mb-8">

            <div>

              <h2 class="text-2xl font-bold">

                <?= htmlspecialchars($userTest['title']) ?>

              </h2>

              <p class="text-gray-500 mt-1">

                Soal

                <span id="currentNumber">

                  1

                </span>

                dari

                <?= $totalQuestions ?>

              </p>

            </div>

          </div>

          <!-- Soal -->

          <div id="questionBox">

          </div>

          <!-- Navigasi -->

          <div class="flex justify-between mt-10">

            <button
              id="prevBtn"
              class="bg-gray-300 hover:bg-gray-400 px-6 py-3 rounded-lg">

              <i class="fa-solid fa-arrow-left mr-2"></i>

              Sebelumnya

            </button>

            <button
              id="nextBtn"
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

              Selanjutnya

              <i class="fa-solid fa-arrow-right ml-2"></i>

            </button>

          </div>

        </div>

      </div>

      <!-- ==========================
             SIDEBAR
        =========================== -->

      <div class="col-span-3">

        <div class="bg-white rounded-2xl shadow p-6 sticky top-6">

          <h3 class="font-bold text-lg mb-5">

            Nomor Soal

          </h3>

          <div
            id="numberContainer"
            class="grid grid-cols-5 gap-2">

            <?php foreach ($questions as $index => $q): ?>

              <button

                class="numberBtn h-11 rounded-lg border hover:bg-blue-100 transition"

                data-index="<?= $index ?>">

                <?= $index + 1 ?>

              </button>

            <?php endforeach; ?>

          </div>

          <!-- Statistik -->

          <div class="mt-8 space-y-3 text-sm">

            <div class="flex justify-between">

              <span>

                Total Soal

              </span>

              <strong>

                <?= $totalQuestions ?>

              </strong>

            </div>

            <div class="flex justify-between">

              <span>

                Sudah Dijawab

              </span>

              <strong id="answeredCount">

                0

              </strong>

            </div>

            <div class="flex justify-between">

              <span>

                Belum Dijawab

              </span>

              <strong id="unansweredCount">

                <?= $totalQuestions ?>

              </strong>

            </div>

          </div>

          <button

            id="finishBtn"

            class="mt-8 w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl">

            <i class="fa-solid fa-circle-check mr-2"></i>

            Selesaikan Tes

          </button>

        </div>

      </div>

    </div>

  </div>

  <script src="../assets/js/test.js"></script>

</body>

</html>
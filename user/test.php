<?php

session_start();

require "../config/database.php";

if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

$test_id = isset($_GET['test']) ? (int) $_GET['test'] : 1;

$stmt = mysqli_prepare($conn, "SELECT * FROM tests WHERE id=? AND status='active'");
mysqli_stmt_bind_param($stmt, "i", $test_id);
mysqli_stmt_execute($stmt);

$test = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$test) {
  die("Tes tidak ditemukan.");
}

$stmt = mysqli_prepare($conn, "SELECT * FROM questions WHERE test_id=? ORDER BY id ASC");
mysqli_stmt_bind_param($stmt, "i", $test_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$questions = [];

while ($row = mysqli_fetch_assoc($result)) {
  $questions[] = $row;
}

$total = count($questions);

?>
<!DOCTYPE html>
<html lang="id">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Tes TKD</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-gray-100 font-[Poppins]">

  <nav class="bg-white shadow">

    <div class="max-w-7xl mx-auto px-6">

      <div class="h-20 flex justify-between items-center">

        <h1 class="text-3xl font-bold text-blue-600">

          🧠 PsychoTest

        </h1>

        <div class="text-right">

          <div class="text-sm text-gray-500">

            Sisa Waktu

          </div>

          <div id="timer"
            class="text-3xl font-bold text-red-600">

            01:30:00

          </div>

        </div>

      </div>

    </div>

  </nav>

  <div class="max-w-7xl mx-auto mt-8 px-6">

    <div class="grid grid-cols-12 gap-6">

      <!-- Soal -->

      <div class="col-span-9">

        <div class="bg-white rounded-xl shadow p-8">

          <h2 class="text-2xl font-bold mb-2">

            <?= htmlspecialchars($test['title']) ?>

          </h2>

          <p class="text-gray-500 mb-8">

            Soal
            <span id="currentNumber">1</span>
            dari
            <?= $total ?>

          </p>

          <div id="questionBox">

          </div>

          <div class="flex justify-between mt-10">

            <button id="prevBtn"
              class="bg-gray-300 px-6 py-3 rounded-lg">

              ◀ Sebelumnya

            </button>

            <button id="nextBtn"
              class="bg-blue-600 text-white px-6 py-3 rounded-lg">

              Selanjutnya ▶

            </button>

          </div>

        </div>

      </div>

      <!-- Sidebar -->

      <div class="col-span-3">

        <div class="bg-white rounded-xl shadow p-6 sticky top-6">

          <h3 class="font-bold text-lg mb-4">

            Nomor Soal

          </h3>

          <div class="grid grid-cols-5 gap-2">

            <?php for ($i = 1; $i <= $total; $i++): ?>

              <button

                class="numberBtn h-10 rounded border"

                data-index="<?= $i - 1 ?>">

                <?= $i ?>

              </button>

            <?php endfor; ?>

          </div>

          <button

            class="mt-8 w-full bg-green-600 text-white py-3 rounded-lg">

            Selesai Tes

          </button>

        </div>

      </div>

    </div>

  </div>

  <script>
    const testId = <?= $test_id ?>;
    const totalQuestions = <?= $total ?>;
    const testDuration = <?= (int)$test['duration'] ?>;
  </script>

  <script src="../assets/js/test.js"></script>

</body>

</html>

</body>

</html>
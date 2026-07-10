<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

$pageTitle = "Kelola Soal";

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";

/* ==========================
   SEARCH & FILTER
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
  $where .= " AND questions.test_id='$test'";
}

/* ==========================
   DATA QUESTIONS
========================== */

$query = mysqli_query($conn, "
    SELECT
        questions.*,
        tests.title
    FROM questions
    JOIN tests
        ON tests.id = questions.test_id
    $where
    ORDER BY questions.created_at DESC
");

/* ==========================
   LIST TEST
========================== */

$listTest = mysqli_query($conn, "
    SELECT *
    FROM tests
    ORDER BY title ASC
");

/* ==========================
   STATISTIK
========================== */

$totalQuestion = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM questions
"))['total'];

$totalTest = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM tests
"))['total'];

$avgQuestion = ($totalTest > 0)
  ? round($totalQuestion / $totalTest)
  : 0;

?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-100">

  <!-- Header -->

  <div class="flex justify-between items-center mb-8">

    <div>

      <h1 class="text-3xl font-bold text-gray-800">
        Kelola Soal
      </h1>

      <p class="text-gray-500">
        Kelola seluruh soal psikotes.
      </p>

    </div>

    <a href="question_create.php"
      class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">

      <i class="fa-solid fa-plus mr-2"></i>

      Tambah Soal

    </a>

  </div>

  <!-- Statistik -->

  <div class="grid grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">
        Total Soal
      </p>

      <h2 class="text-4xl font-bold text-blue-600 mt-2">

        <?= $totalQuestion ?>

      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">
        Total Tes
      </p>

      <h2 class="text-4xl font-bold text-green-600 mt-2">

        <?= $totalTest ?>

      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">
        Rata-rata Soal / Tes
      </p>

      <h2 class="text-4xl font-bold text-purple-600 mt-2">

        <?= $avgQuestion ?>

      </h2>

    </div>

  </div>

  <!-- Search -->

  <div class="bg-white rounded-xl shadow p-5 mb-6">

    <form method="GET" class="grid grid-cols-3 gap-3">

      <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search) ?>"
        placeholder="Cari soal..."
        class="border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

      <select
        name="test"
        class="border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

        <option value="">
          Semua Tes
        </option>

        <?php while ($t = mysqli_fetch_assoc($listTest)) : ?>

          <option
            value="<?= $t['id'] ?>"
            <?= ($test == $t['id']) ? 'selected' : '' ?>>

            <?= htmlspecialchars($t['title']) ?>

          </option>

        <?php endwhile; ?>

      </select>

      <button
        class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg">

        <i class="fa-solid fa-magnifying-glass mr-2"></i>

        Cari

      </button>

    </form>

  </div>

  <!-- Table -->

  <div class="bg-white rounded-xl shadow overflow-hidden">

    <table class="w-full">

      <thead class="bg-gray-100">

        <tr>

          <th class="p-4 w-16">
            No
          </th>

          <th class="text-left">
            Nama Tes
          </th>

          <th class="text-left">
            Soal
          </th>

          <th class="text-center">
            Jawaban
          </th>

          <th class="text-center">
            Dibuat
          </th>

          <th class="text-center">
            Aksi
          </th>

        </tr>

      </thead>

      <tbody>
        <?php if (mysqli_num_rows($query) > 0) : ?>

          <?php $no = 1; ?>

          <?php while ($row = mysqli_fetch_assoc($query)) : ?>

            <tr class="border-t hover:bg-gray-50 transition">

              <!-- No -->

              <td class="text-center p-4">
                <?= $no++ ?>
              </td>

              <!-- Nama Tes -->

              <td>

                <div class="font-semibold text-blue-600">

                  <?= htmlspecialchars($row['title']) ?>

                </div>

              </td>

              <!-- Soal -->

              <td class="max-w-xl">

                <div class="font-medium text-gray-800">

                  <?= htmlspecialchars(mb_strimwidth($row['question'], 0, 100, "...")) ?>

                </div>

              </td>

              <!-- Jawaban -->

              <td class="text-center">

                <?php

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

                <span class="<?= $color ?> px-3 py-1 rounded-full text-sm font-semibold">

                  <?= $row['answer'] ?>

                </span>

              </td>

              <!-- Tanggal -->

              <td class="text-center text-gray-500 text-sm">

                <?= date('d M Y', strtotime($row['created_at'])) ?>

              </td>

              <!-- Aksi -->

              <td>

                <div class="flex justify-center gap-2">

                  <a href="question_edit.php?id=<?= $row['id'] ?>"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg">

                    <i class="fa-solid fa-pen"></i>

                  </a>

                  <a href="question_delete.php?id=<?= $row['id'] ?>"
                    onclick="return confirm('Yakin ingin menghapus soal ini?')"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg">

                    <i class="fa-solid fa-trash"></i>

                  </a>

                </div>

              </td>

            </tr>

          <?php endwhile; ?>

        <?php else : ?>

          <tr>

            <td colspan="6" class="text-center py-12 text-gray-500">

              <i class="fa-regular fa-folder-open text-5xl mb-3 block"></i>

              Belum ada data soal.

            </td>

          </tr>

        <?php endif; ?>

      </tbody>

    </table>

  </div>

</div>

<?php include "../includes/admin_footer.php"; ?>
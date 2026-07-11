<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {

  header("Location: ../auth/login.php");
  exit;
}

require "../config/database.php";

$pageTitle = "Detail Hasil Tes";

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";

/* =====================================
   VALIDASI ID
===================================== */

if (!isset($_GET['id'])) {

  header("Location: results.php");
  exit;
}

$user_test_id = (int) $_GET['id'];

/* =====================================
   DATA PESERTA
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

JOIN users
ON users.id=user_tests.user_id

JOIN tests
ON tests.id=user_tests.test_id

WHERE user_tests.id='$user_test_id'

LIMIT 1

");

if (mysqli_num_rows($query) == 0) {

  echo "<script>

    alert('Data tidak ditemukan.');

    window.location='results.php';

    </script>";

  exit;
}

$data = mysqli_fetch_assoc($query);

/* =====================================
   HITUNG LAMA PENGERJAAN
===================================== */

$duration = "-";

if (
  !empty($data['start_time']) &&
  !empty($data['end_time'])
) {

  $start = strtotime($data['start_time']);
  $end   = strtotime($data['end_time']);

  $selisih = $end - $start;

  $menit = floor($selisih / 60);
  $detik = $selisih % 60;

  $duration = $menit . " Menit " . $detik . " Detik";
}

/* =====================================
   DATA JAWABAN
===================================== */

$answers = mysqli_query($conn, "

SELECT

questions.*,

user_answers.answer AS user_answer

FROM questions

LEFT JOIN user_answers

ON user_answers.question_id=questions.id

AND user_answers.user_test_id='$user_test_id'

WHERE questions.test_id='" . $data['test_id'] . "'

ORDER BY questions.id ASC

");

?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-100">

  <!-- Header -->

  <div class="flex justify-between items-center mb-8">

    <div>

      <h1 class="text-3xl font-bold text-gray-800">

        Detail Hasil Tes

      </h1>

      <p class="text-gray-500">

        Detail hasil pengerjaan peserta.

      </p>

    </div>

    <a href="results.php"

      class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-lg">

      <i class="fa-solid fa-arrow-left mr-2"></i>

      Kembali

    </a>

  </div>

  <!-- Informasi -->

  <div class="grid grid-cols-2 gap-6 mb-8">

    <!-- Peserta -->

    <div class="bg-white rounded-xl shadow p-6">

      <h3 class="text-lg font-bold mb-4 text-blue-600">

        Informasi Peserta

      </h3>

      <table class="w-full">

        <tr>

          <td class="py-2 text-gray-500 w-40">

            Nama

          </td>

          <td class="font-semibold">

            <?= htmlspecialchars($data['fullname']) ?>

          </td>

        </tr>

        <tr>

          <td class="py-2 text-gray-500">

            Email

          </td>

          <td>

            <?= htmlspecialchars($data['email']) ?>

          </td>

        </tr>

        <tr>

          <td class="py-2 text-gray-500">

            Status

          </td>

          <td>

            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

              <?= ucfirst($data['status']) ?>

            </span>

          </td>

        </tr>

      </table>

    </div>

    <!-- Tes -->

    <div class="bg-white rounded-xl shadow p-6">

      <h3 class="text-lg font-bold mb-4 text-purple-600">

        Informasi Tes

      </h3>

      <table class="w-full">

        <tr>

          <td class="py-2 text-gray-500 w-40">

            Nama Tes

          </td>

          <td class="font-semibold">

            <?= htmlspecialchars($data['title']) ?>

          </td>

        </tr>

        <tr>

          <td class="py-2 text-gray-500">

            Jumlah Soal

          </td>

          <td>

            <?= $data['total_questions'] ?>

          </td>

        </tr>

        <tr>

          <td class="py-2 text-gray-500">

            Durasi Tes

          </td>

          <td>

            <?= $data['duration'] ?> Menit

          </td>

        </tr>

        <tr>

          <td class="py-2 text-gray-500">

            Lama Pengerjaan

          </td>

          <td>

            <?= $duration ?>

          </td>

        </tr>

      </table>

    </div>

  </div>

  <!-- Statistik -->

  <div class="grid grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Nilai

      </p>

      <h2 class="text-4xl font-bold text-blue-600 mt-2">

        <?= $data['score'] ?>

      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Jawaban Benar

      </p>

      <h2 class="text-4xl font-bold text-green-600 mt-2">

        <?= $data['correct_answers'] ?>

      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Jawaban Salah

      </p>

      <h2 class="text-4xl font-bold text-red-600 mt-2">

        <?= $data['wrong_answers'] ?>

      </h2>

    </div>

  </div>

  <!-- Detail Jawaban -->

  <div class="bg-white rounded-xl shadow overflow-hidden">

    <table class="w-full">

      <thead class="bg-gray-100">

        <tr>

          <th class="p-4 w-16">
            No
          </th>

          <th class="text-left">
            Soal
          </th>

          <th class="text-center">
            Jawaban Peserta
          </th>

          <th class="text-center">
            Jawaban Benar
          </th>

          <th class="text-center">
            Status
          </th>

        </tr>

      </thead>

      <tbody>

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

            <tr class="border-t hover:bg-gray-50 transition">

              <!-- No -->

              <td class="text-center p-4">

                <?= $no++ ?>

              </td>

              <!-- Soal -->

              <td class="max-w-2xl">

                <div class="font-medium text-gray-800">

                  <?= htmlspecialchars($row['question']) ?>

                </div>

              </td>

              <!-- Jawaban Peserta -->

              <td class="text-center">

                <?php if (!empty($row['user_answer'])): ?>

                  <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-semibold">

                    <?= $row['user_answer'] ?>

                  </span>

                <?php else: ?>

                  <span class="text-gray-400">

                    -

                  </span>

                <?php endif; ?>

              </td>

              <!-- Jawaban Benar -->

              <td class="text-center">

                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">

                  <?= $row['answer'] ?>

                </span>

              </td>

              <!-- Status -->

              <td class="text-center">

                <span class="<?= $badge ?> px-3 py-1 rounded-full text-sm font-semibold">

                  <?= $status ?>

                </span>

              </td>

            </tr>

          <?php endwhile; ?>

        <?php else: ?>

          <tr>

            <td colspan="5" class="text-center py-12 text-gray-500">

              <i class="fa-regular fa-folder-open text-5xl mb-4 block"></i>

              Belum ada data jawaban.

            </td>

          </tr>

        <?php endif; ?>

      </tbody>

    </table>

  </div>

</div>

<?php include "../includes/admin_footer.php"; ?>
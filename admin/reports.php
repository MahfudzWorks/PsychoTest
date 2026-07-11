<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {

  header("Location: ../auth/login.php");
  exit;
}

require "../config/database.php";

$pageTitle = "Laporan";

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";

/* =====================================
   SEARCH & FILTER
===================================== */

$search = "";
$test = "";
$status = "";

if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if (isset($_GET['test'])) {
  $test = mysqli_real_escape_string($conn, $_GET['test']);
}

if (isset($_GET['status'])) {
  $status = mysqli_real_escape_string($conn, $_GET['status']);
}

$where = "WHERE user_tests.status='finished'";

if (!empty($search)) {

  $where .= " AND (

        users.fullname LIKE '%$search%'

        OR users.email LIKE '%$search%'

    )";
}

if (!empty($test)) {

  $where .= " AND user_tests.test_id='$test'";
}

if (!empty($status)) {

  $where .= " AND user_tests.payment_status='$status'";
}

/* =====================================
   DATA LAPORAN
===================================== */

$query = mysqli_query($conn, "

SELECT

user_tests.*,

users.fullname,
users.email,

tests.title

FROM user_tests

JOIN users
ON users.id=user_tests.user_id

JOIN tests
ON tests.id=user_tests.test_id

$where

ORDER BY user_tests.created_at DESC

");

/* =====================================
   LIST TEST
===================================== */

$listTest = mysqli_query($conn, "

SELECT *

FROM tests

ORDER BY title ASC

");

/* =====================================
   STATISTIK
===================================== */

$totalParticipant = mysqli_fetch_assoc(mysqli_query($conn, "

SELECT COUNT(DISTINCT user_id) AS total

FROM user_tests

"))['total'];

$totalFinished = mysqli_fetch_assoc(mysqli_query($conn, "

SELECT COUNT(*) AS total

FROM user_tests

WHERE status='finished'

"))['total'];

$totalIncome = mysqli_fetch_assoc(mysqli_query($conn, "

SELECT SUM(tests.price) AS total

FROM user_tests

JOIN tests
ON tests.id=user_tests.test_id

WHERE payment_status='paid'

"))['total'];

$averageScore = mysqli_fetch_assoc(mysqli_query($conn, "

SELECT AVG(score) AS avg_score

FROM user_tests

WHERE status='finished'

"))['avg_score'];

?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-100">

  <!-- Header -->

  <div class="flex justify-between items-center mb-8">

    <div>

      <h1 class="text-3xl font-bold text-gray-800">

        Laporan Psikotes

      </h1>

      <p class="text-gray-500">

        Rekap seluruh hasil psikotes peserta.

      </p>

    </div>

    <div class="flex gap-3">

      <a href="report_pdf.php?search=<?= urlencode($search) ?>&test=<?= urlencode($test) ?>&status=<?= urlencode($status) ?>"
        class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-lg">

        <i class="fa-solid fa-file-pdf mr-2"></i>
        Export PDF

      </a>

      <a href="#"

        class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg">

        <i class="fa-solid fa-file-excel mr-2"></i>

        Export Excel

      </a>

    </div>

  </div>

  <!-- Statistik -->

  <div class="grid grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Total Peserta

      </p>

      <h2 class="text-4xl font-bold text-blue-600 mt-2">

        <?= $totalParticipant ?>

      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Tes Selesai

      </p>

      <h2 class="text-4xl font-bold text-green-600 mt-2">

        <?= $totalFinished ?>

      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Total Pendapatan

      </p>

      <h2 class="text-3xl font-bold text-purple-600 mt-2">

        Rp <?= number_format($totalIncome ?? 0, 0, ",", ".") ?>

      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Nilai Rata-rata

      </p>

      <h2 class="text-4xl font-bold text-orange-500 mt-2">

        <?= round($averageScore ?? 0) ?>

      </h2>

    </div>

  </div>

  <!-- Search -->

  <div class="bg-white rounded-xl shadow p-5 mb-6">

    <form method="GET" class="grid grid-cols-4 gap-3">

      <input

        type="text"

        name="search"

        value="<?= htmlspecialchars($search) ?>"

        placeholder="Cari peserta..."

        class="border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

      <select

        name="test"

        class="border rounded-lg px-4 py-3">

        <option value="">

          Semua Tes

        </option>

        <?php while ($t = mysqli_fetch_assoc($listTest)): ?>

          <option

            value="<?= $t['id'] ?>"

            <?= ($test == $t['id']) ? 'selected' : '' ?>>

            <?= htmlspecialchars($t['title']) ?>

          </option>

        <?php endwhile; ?>

      </select>

      <select

        name="status"

        class="border rounded-lg px-4 py-3">

        <option value="">

          Semua Pembayaran

        </option>

        <option value="paid" <?= ($status == "paid") ? "selected" : ""; ?>>

          Paid

        </option>

        <option value="pending" <?= ($status == "pending") ? "selected" : ""; ?>>

          Pending

        </option>

        <option value="rejected" <?= ($status == "rejected") ? "selected" : ""; ?>>

          Rejected

        </option>

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
            Peserta
          </th>

          <th class="text-left">
            Tes
          </th>

          <th class="text-center">
            Nilai
          </th>

          <th class="text-center">
            Benar
          </th>

          <th class="text-center">
            Salah
          </th>

          <th class="text-center">
            Pembayaran
          </th>

          <th class="text-center">
            Tanggal
          </th>

          <th class="text-center">
            Detail
          </th>

        </tr>

      </thead>

      <tbody>

        <?php if (mysqli_num_rows($query) > 0): ?>

          <?php $no = 1; ?>

          <?php while ($row = mysqli_fetch_assoc($query)): ?>

            <?php

            /* ==========================
                       WARNA NILAI
                    ========================== */

            if ($row['score'] >= 80) {

              $scoreColor = "text-green-600";
            } elseif ($row['score'] >= 60) {

              $scoreColor = "text-yellow-600";
            } else {

              $scoreColor = "text-red-600";
            }

            /* ==========================
                       STATUS PEMBAYARAN
                    ========================== */

            switch ($row['payment_status']) {

              case "paid":

                $badge = "bg-green-100 text-green-700";

                break;

              case "pending":

                $badge = "bg-yellow-100 text-yellow-700";

                break;

              default:

                $badge = "bg-red-100 text-red-700";

                break;
            }

            ?>

            <tr class="border-t hover:bg-gray-50 transition">

              <!-- No -->

              <td class="text-center p-4">

                <?= $no++ ?>

              </td>

              <!-- Peserta -->

              <td>

                <div class="font-semibold text-gray-800">

                  <?= htmlspecialchars($row['fullname']) ?>

                </div>

                <div class="text-sm text-gray-500">

                  <?= htmlspecialchars($row['email']) ?>

                </div>

              </td>

              <!-- Tes -->

              <td>

                <span class="font-semibold text-blue-600">

                  <?= htmlspecialchars($row['title']) ?>

                </span>

              </td>

              <!-- Nilai -->

              <td class="text-center">

                <span class="font-bold text-xl <?= $scoreColor ?>">

                  <?= $row['score'] ?>

                </span>

              </td>

              <!-- Benar -->

              <td class="text-center text-green-600 font-bold">

                <?= $row['correct_answers'] ?>

              </td>

              <!-- Salah -->

              <td class="text-center text-red-600 font-bold">

                <?= $row['wrong_answers'] ?>

              </td>

              <!-- Pembayaran -->

              <td class="text-center">

                <span class="<?= $badge ?> px-3 py-1 rounded-full text-sm font-semibold">

                  <?= ucfirst($row['payment_status']) ?>

                </span>

              </td>

              <!-- Tanggal -->

              <td class="text-center text-sm text-gray-500">

                <?= date("d M Y", strtotime($row['created_at'])) ?>

                <br>

                <?= date("H:i", strtotime($row['created_at'])) ?>

              </td>

              <!-- Detail -->

              <td>

                <div class="flex justify-center">

                  <a

                    href="result_detail.php?id=<?= $row['id'] ?>"

                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg">

                    <i class="fa-solid fa-eye"></i>

                  </a>

                </div>

              </td>

            </tr>

          <?php endwhile; ?>

        <?php else: ?>

          <tr>

            <td colspan="9" class="text-center py-12 text-gray-500">

              <i class="fa-regular fa-folder-open text-5xl mb-4 block"></i>

              Belum ada data laporan.

            </td>

          </tr>

        <?php endif; ?>

      </tbody>

    </table>

  </div>

</div>

<?php include "../includes/admin_footer.php"; ?>
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

$pageTitle = "Hasil Psikotes";

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";

/* =====================================
   SEARCH & FILTER
===================================== */

$search = "";
$status = "";
$payment = "";
$test = "";

if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if (isset($_GET['status'])) {
  $status = mysqli_real_escape_string($conn, $_GET['status']);
}

if (isset($_GET['payment'])) {
  $payment = mysqli_real_escape_string($conn, $_GET['payment']);
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
  $where .= " AND user_tests.status='$status'";
}

if (!empty($payment)) {
  $where .= " AND user_tests.payment_status='$payment'";
}

if (!empty($test)) {
  $where .= " AND user_tests.test_id='$test'";
}

/* =====================================
   DATA RESULT
===================================== */

$query = mysqli_query($conn, "

SELECT

user_tests.*,

users.fullname,
users.email,

tests.title,
tests.price

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

$totalParticipant = mysqli_fetch_assoc(

  mysqli_query($conn, "

    SELECT COUNT(DISTINCT user_id) AS total

    FROM user_tests

")

)['total'];

$totalFinished = mysqli_fetch_assoc(

  mysqli_query($conn, "

    SELECT COUNT(*) AS total

    FROM user_tests

    WHERE status='finished'

")

)['total'];

$averageScore = mysqli_fetch_assoc(

  mysqli_query($conn, "

    SELECT AVG(score) AS avgscore

    FROM user_tests

    WHERE status='finished'

")

)['avgscore'];

$highestScore = mysqli_fetch_assoc(

  mysqli_query($conn, "

    SELECT MAX(score) AS highest

    FROM user_tests

")

)['highest'];

$averageScore = round($averageScore);
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-100">

  <!-- Header -->

  <div class="flex justify-between items-center mb-8">

    <div>

      <h1 class="text-3xl font-bold text-gray-800">

        Hasil Psikotes

      </h1>

      <p class="text-gray-500 mt-1">

        Monitoring seluruh hasil tes peserta.

      </p>

    </div>

  </div>

  <!-- Statistik -->

  <div class="grid grid-cols-4 gap-6 mb-8">

    <!-- Peserta -->

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Total Peserta

      </p>

      <h2 class="text-4xl font-bold text-blue-600 mt-3">

        <?= $totalParticipant ?>

      </h2>

    </div>

    <!-- Selesai -->

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Tes Selesai

      </p>

      <h2 class="text-4xl font-bold text-green-600 mt-3">

        <?= $totalFinished ?>

      </h2>

    </div>

    <!-- Rata-rata -->

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Nilai Rata-rata

      </p>

      <h2 class="text-4xl font-bold text-yellow-500 mt-3">

        <?= $averageScore ?>

      </h2>

    </div>

    <!-- Nilai Tertinggi -->

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Nilai Tertinggi

      </p>

      <h2 class="text-4xl font-bold text-purple-600 mt-3">

        <?= $highestScore ?>

      </h2>

    </div>

  </div>

  <!-- Search -->

  <div class="bg-white rounded-xl shadow p-5 mb-6">

    <form
      method="GET"
      class="grid grid-cols-5 gap-3">

      <!-- Search -->

      <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search) ?>"
        placeholder="Cari nama / email..."
        class="border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

      <!-- Tes -->

      <select
        name="test"
        class="border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

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

      <!-- Status -->

      <select
        name="status"
        class="border rounded-lg px-4 py-3">

        <option value="">
          Semua Status
        </option>

        <option
          value="started"
          <?= ($status == "started") ? "selected" : "" ?>>

          Started

        </option>

        <option
          value="finished"
          <?= ($status == "finished") ? "selected" : "" ?>>

          Finished

        </option>

      </select>

      <!-- Payment -->

      <select
        name="payment"
        class="border rounded-lg px-4 py-3">

        <option value="">
          Semua Pembayaran
        </option>

        <option
          value="pending"
          <?= ($payment == "pending") ? "selected" : "" ?>>

          Pending

        </option>

        <option
          value="paid"
          <?= ($payment == "paid") ? "selected" : "" ?>>

          Paid

        </option>

        <option
          value="rejected"
          <?= ($payment == "rejected") ? "selected" : "" ?>>

          Rejected

        </option>

      </select>

      <!-- Button -->

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

          <th class="p-4 w-16">No</th>

          <th class="text-left">Peserta</th>

          <th class="text-left">Tes</th>

          <th class="text-center">Mulai</th>

          <th class="text-center">Selesai</th>

          <th class="text-center">Nilai</th>

          <th class="text-center">Status</th>

          <th class="text-center">Pembayaran</th>

          <th class="text-center">Aksi</th>

        </tr>

      </thead>

      <tbody>

        <?php if (mysqli_num_rows($query) > 0): ?>

          <?php $no = 1; ?>

          <?php while ($row = mysqli_fetch_assoc($query)): ?>

            <tr class="border-t hover:bg-gray-50 transition">

              <!-- No -->

              <td class="text-center p-4">

                <?= $no++ ?>

              </td>

              <!-- Peserta -->

              <td class="py-4">

                <div class="font-semibold text-gray-800">

                  <?= htmlspecialchars($row['fullname']) ?>

                </div>

                <div class="text-sm text-gray-500">

                  <?= htmlspecialchars($row['email']) ?>

                </div>

              </td>

              <!-- Tes -->

              <td>

                <div class="font-semibold text-blue-600">

                  <?= htmlspecialchars($row['title']) ?>

                </div>

                <div class="text-sm text-gray-500">

                  Rp <?= number_format($row['price'], 0, ",", ".") ?>

                </div>

              </td>

              <!-- Mulai -->

              <td class="text-center text-sm text-gray-600">

                <?= date("d M Y", strtotime($row['start_time'])) ?>

                <br>

                <?= date("H:i", strtotime($row['start_time'])) ?>

              </td>

              <!-- Selesai -->

              <td class="text-center text-sm text-gray-600">

                <?php if (!empty($row['end_time'])): ?>

                  <?= date("d M Y", strtotime($row['end_time'])) ?>

                  <br>

                  <?= date("H:i", strtotime($row['end_time'])) ?>

                <?php else: ?>

                  -

                <?php endif; ?>

              </td>

              <!-- Nilai -->

              <td class="text-center">

                <?php

                $scoreColor = "bg-red-100 text-red-700";

                if ($row['score'] >= 80) {

                  $scoreColor = "bg-green-100 text-green-700";
                } elseif ($row['score'] >= 60) {

                  $scoreColor = "bg-blue-100 text-blue-700";
                } elseif ($row['score'] >= 40) {

                  $scoreColor = "bg-yellow-100 text-yellow-700";
                }

                ?>

                <span class="<?= $scoreColor ?> px-3 py-1 rounded-full font-semibold">

                  <?= $row['score'] ?>

                </span>

              </td>

              <!-- Status -->

              <td class="text-center">

                <?php

                if ($row['status'] == "finished") {

                  echo '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">Finished</span>';
                } else {

                  echo '<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">Started</span>';
                }

                ?>

              </td>

              <!-- Payment -->

              <td class="text-center">

                <?php

                switch ($row['payment_status']) {

                  case "paid":

                    $pay = 'bg-green-100 text-green-700';

                    break;

                  case "pending":

                    $pay = 'bg-yellow-100 text-yellow-700';

                    break;

                  default:

                    $pay = 'bg-red-100 text-red-700';

                    break;
                }

                ?>

                <span class="<?= $pay ?> px-3 py-1 rounded-full text-sm font-semibold">

                  <?= ucfirst($row['payment_status']) ?>

                </span>

              </td>

              <!-- Aksi -->

              <td>

                <div class="flex justify-center">

                  <a
                    href="result_detail.php?id=<?= $row['id'] ?>"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

                    <i class="fa-solid fa-eye mr-2"></i>

                    Detail

                  </a>

                </div>

              </td>

            </tr>

          <?php endwhile; ?>

        <?php else: ?>

          <tr>

            <td colspan="9" class="py-14 text-center text-gray-500">

              <i class="fa-regular fa-folder-open text-5xl mb-4 block"></i>

              Belum ada hasil psikotes.

            </td>

          </tr>

        <?php endif; ?>

      </tbody>

    </table>

  </div>

</div>

<?php include "../includes/admin_footer.php"; ?>
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

$pageTitle = "Dashboard";

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";

/* ==========================
   STATISTIK
========================== */

$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM users
WHERE role='user'
"))['total'];

$totalTests = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM tests
"))['total'];

$totalQuestions = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM questions
"))['total'];

$totalFinished = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM user_tests
WHERE status='finished'
"))['total'];

$totalOngoing = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM user_tests
WHERE status='ongoing'
"))['total'];

$totalPending = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM user_tests
WHERE payment_status='pending'
"))['total'];

$totalPaid = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM user_tests
WHERE payment_status='paid'
"))['total'];

$averageScore = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT ROUND(AVG(score),2) avg_score
FROM user_tests
WHERE score IS NOT NULL
"))['avg_score'];

if ($averageScore == "") {
  $averageScore = 0;
}

/* ==========================
   PESERTA TERBARU
========================== */

$users = mysqli_query($conn, "
SELECT *
FROM users
WHERE role='user'
ORDER BY created_at DESC
LIMIT 5
");

/* ==========================
   PEMBAYARAN TERBARU
========================== */

$payments = mysqli_query($conn, "
SELECT
users.fullname,
tests.title,
user_tests.payment_status

FROM user_tests

JOIN users
ON users.id=user_tests.user_id

JOIN tests
ON tests.id=user_tests.test_id

ORDER BY user_tests.created_at DESC

LIMIT 5
");
?>

<div class="ml-64 mt-16 p-8 bg-gray-100 min-h-screen">

  <!-- Heading -->

  <div class="mb-8">

    <h1 class="text-3xl font-bold text-gray-800">
      Selamat Datang,
      <?= $_SESSION['fullname']; ?> 👋
    </h1>

    <p class="text-gray-500 mt-2">
      Kelola seluruh data sistem psikotes dari dashboard admin.
    </p>

  </div>

  <!-- CARD -->

  <div class="grid grid-cols-4 gap-6">

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">Total Peserta</p>

      <h2 class="text-4xl font-bold mt-3 text-blue-600">
        <?= $totalUsers ?>
      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">Jenis Tes</p>

      <h2 class="text-4xl font-bold mt-3 text-green-600">
        <?= $totalTests ?>
      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">Bank Soal</p>

      <h2 class="text-4xl font-bold mt-3 text-yellow-500">
        <?= $totalQuestions ?>
      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">Tes Selesai</p>

      <h2 class="text-4xl font-bold mt-3 text-red-500">
        <?= $totalFinished ?>
      </h2>

    </div>

  </div>

  <!-- BARIS 2 -->

  <div class="grid grid-cols-3 gap-6 mt-8">

    <div class="bg-white rounded-xl shadow p-6">

      <h3 class="font-semibold mb-3">
        Tes Berlangsung
      </h3>

      <h1 class="text-5xl font-bold text-blue-600">
        <?= $totalOngoing ?>
      </h1>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <h3 class="font-semibold mb-3">
        Pembayaran Pending
      </h3>

      <h1 class="text-5xl font-bold text-orange-500">
        <?= $totalPending ?>
      </h1>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <h3 class="font-semibold mb-3">
        Rata-rata Nilai
      </h3>

      <h1 class="text-5xl font-bold text-green-600">
        <?= $averageScore ?>
      </h1>

    </div>

  </div>

  <!-- CHART -->

  <div class="bg-white rounded-xl shadow p-6 mt-8">

    <h2 class="text-xl font-bold mb-5">

      Statistik Status Tes

    </h2>

    <canvas id="statusChart"></canvas>

  </div>

  <!-- TABLE -->

  <div class="grid grid-cols-2 gap-6 mt-8">

    <!-- USER -->

    <div class="bg-white rounded-xl shadow">

      <div class="border-b p-5">

        <h2 class="font-bold">

          Peserta Terbaru

        </h2>

      </div>

      <table class="w-full">

        <thead>

          <tr class="bg-gray-50">

            <th class="p-3 text-left">Nama</th>

            <th>Email</th>

            <th>Status</th>

          </tr>

        </thead>

        <tbody>

          <?php while ($row = mysqli_fetch_assoc($users)): ?>

            <tr class="border-t">

              <td class="p-3">

                <?= $row['fullname']; ?>

              </td>

              <td>

                <?= $row['email']; ?>

              </td>

              <td>

                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                  <?= $row['status']; ?>

                </span>

              </td>

            </tr>

          <?php endwhile; ?>

        </tbody>

      </table>

    </div>

    <!-- PAYMENT -->

    <div class="bg-white rounded-xl shadow">

      <div class="border-b p-5">

        <h2 class="font-bold">

          Pembayaran Terbaru

        </h2>

      </div>

      <table class="w-full">

        <thead>

          <tr class="bg-gray-50">

            <th class="p-3 text-left">Nama</th>

            <th>Tes</th>

            <th>Status</th>

          </tr>

        </thead>

        <tbody>

          <?php while ($pay = mysqli_fetch_assoc($payments)): ?>

            <tr class="border-t">

              <td class="p-3">

                <?= $pay['fullname']; ?>

              </td>

              <td>

                <?= $pay['title']; ?>

              </td>

              <td>

                <?php

                if ($pay['payment_status'] == "paid") {
                  echo "<span class='bg-green-100 text-green-700 px-3 py-1 rounded-full'>Paid</span>";
                } else {
                  echo "<span class='bg-red-100 text-red-600 px-3 py-1 rounded-full'>Pending</span>";
                }

                ?>

              </td>

            </tr>

          <?php endwhile; ?>

        </tbody>

      </table>

    </div>

  </div>

</div>

<script>
  new Chart(document.getElementById('statusChart'), {

    type: 'bar',

    data: {

      labels: ['Selesai', 'Berlangsung', 'Pending'],

      datasets: [{

        label: 'Jumlah',

        data: [
          <?= $totalFinished ?>,
          <?= $totalOngoing ?>,
          <?= $totalPending ?>
        ]

      }]

    }

  });
</script>

<?php include "../includes/admin_footer.php"; ?>
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

$pageTitle = "Verifikasi Pembayaran";

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";

/* =====================================
   SEARCH & FILTER
===================================== */

$search = "";
$status = "";
$test = "";

if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if (isset($_GET['status'])) {
  $status = mysqli_real_escape_string($conn, $_GET['status']);
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

  $where .= " AND user_tests.payment_status='$status'";
}

if (!empty($test)) {

  $where .= " AND user_tests.test_id='$test'";
}

/* =====================================
   DATA PEMBAYARAN
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

ORDER BY

user_tests.created_at DESC

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

$totalPending = mysqli_fetch_assoc(

  mysqli_query($conn, "

SELECT COUNT(*) AS total

FROM user_tests

WHERE payment_status='pending'

")

)['total'];

$totalPaid = mysqli_fetch_assoc(

  mysqli_query($conn, "

SELECT COUNT(*) AS total

FROM user_tests

WHERE payment_status='paid'

")

)['total'];

$totalRejected = mysqli_fetch_assoc(

  mysqli_query($conn, "

SELECT COUNT(*) AS total

FROM user_tests

WHERE payment_status='rejected'

")

)['total'];

?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-100">

  <!-- Header -->

  <div class="flex justify-between items-center mb-8">

    <div>

      <h1 class="text-3xl font-bold text-gray-800">

        Verifikasi Pembayaran

      </h1>

      <p class="text-gray-500 mt-1">

        Kelola seluruh pembayaran peserta psikotes.

      </p>

    </div>

  </div>

  <!-- Statistik -->

  <div class="grid grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Menunggu Verifikasi

      </p>

      <h2 class="text-4xl font-bold text-yellow-500 mt-3">

        <?= $totalPending ?>

      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Pembayaran Disetujui

      </p>

      <h2 class="text-4xl font-bold text-green-600 mt-3">

        <?= $totalPaid ?>

      </h2>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

      <p class="text-gray-500">

        Pembayaran Ditolak

      </p>

      <h2 class="text-4xl font-bold text-red-600 mt-3">

        <?= $totalRejected ?>

      </h2>

    </div>

  </div>

  <!-- Search -->

  <div class="bg-white rounded-xl shadow p-5 mb-6">

    <form method="GET" class="grid grid-cols-4 gap-3">

      <!-- Search -->

      <input

        type="text"

        name="search"

        value="<?= htmlspecialchars($search) ?>"

        placeholder="Cari peserta..."

        class="border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

      <!-- Test -->

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

      <!-- Status -->

      <select

        name="status"

        class="border rounded-lg px-4 py-3">

        <option value="">

          Semua Status

        </option>

        <option

          value="pending"

          <?= ($status == "pending") ? "selected" : ""; ?>>

          Pending

        </option>

        <option

          value="paid"

          <?= ($status == "paid") ? "selected" : ""; ?>>

          Paid

        </option>

        <option

          value="rejected"

          <?= ($status == "rejected") ? "selected" : ""; ?>>

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

          <th class="text-center">Harga</th>

          <th class="text-center">Bukti</th>

          <th class="text-center">Upload</th>

          <th class="text-center">Status</th>

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

                <div class="font-semibold text-blue-600">

                  <?= htmlspecialchars($row['title']) ?>

                </div>

              </td>

              <!-- Harga -->

              <td class="text-center font-semibold text-green-600">

                Rp <?= number_format($row['price'], 0, ",", ".") ?>

              </td>

              <!-- Bukti -->

              <td class="text-center">

                <?php if (!empty($row['payment_proof'])): ?>

                  <a
                    href="../assets/uploads/payments/<?= $row['payment_proof'] ?>"
                    target="_blank"
                    class="bg-blue-100 text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-200">

                    <i class="fa-solid fa-image mr-2"></i>

                    Lihat

                  </a>

                <?php else: ?>

                  <span class="bg-gray-100 text-gray-600 px-3 py-2 rounded-lg">

                    Belum Upload

                  </span>

                <?php endif; ?>

              </td>

              <!-- Upload -->

              <td class="text-center text-sm text-gray-500">

                <?php

                if (!empty($row['payment_date'])) {

                  echo date("d M Y", strtotime($row['payment_date']));

                  echo "<br>";

                  echo date("H:i", strtotime($row['payment_date']));
                } else {

                  echo "-";
                }

                ?>

              </td>

              <!-- Status -->

              <td class="text-center">

                <?php

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

                <span class="<?= $badge ?> px-3 py-1 rounded-full text-sm font-semibold">

                  <?= ucfirst($row['payment_status']) ?>

                </span>

              </td>

              <!-- Aksi -->

              <td>

                <div class="flex justify-center gap-2">

                  <?php if ($row['payment_status'] == "pending" && !empty($row['payment_proof'])): ?>

                    <a
                      href="payment_approve.php?id=<?= $row['id'] ?>"
                      onclick="return confirm('Setujui pembayaran ini?')"
                      class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg">

                      <i class="fa-solid fa-check"></i>

                    </a>

                    <a
                      href="payment_reject.php?id=<?= $row['id'] ?>"
                      onclick="return confirm('Tolak pembayaran ini?')"
                      class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg">

                      <i class="fa-solid fa-xmark"></i>

                    </a>

                  <?php else: ?>

                    <span class="text-gray-400">

                      -

                    </span>

                  <?php endif; ?>

                </div>

              </td>

            </tr>

          <?php endwhile; ?>

        <?php else: ?>

          <tr>

            <td colspan="8" class="py-14 text-center text-gray-500">

              <i class="fa-regular fa-folder-open text-5xl mb-4 block"></i>

              Belum ada data pembayaran.

            </td>

          </tr>

        <?php endif; ?>

      </tbody>

    </table>

  </div>

</div>

<?php include "../includes/admin_footer.php"; ?>
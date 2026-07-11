<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {

  header("Location: ../auth/login.php");
  exit;
}

require "../config/database.php";

/* =====================================
   VALIDASI ID
===================================== */

if (!isset($_GET['id'])) {

  header("Location: payments.php");
  exit;
}

$id = (int) $_GET['id'];
$admin_id = (int) $_SESSION['id'];

/* =====================================
   CEK DATA
===================================== */

$check = mysqli_query($conn, "

SELECT *

FROM user_tests

WHERE id='$id'

LIMIT 1

");

if (mysqli_num_rows($check) == 0) {

  echo "<script>

        alert('Data pembayaran tidak ditemukan.');

        window.location='payments.php';

    </script>";

  exit;
}

$data = mysqli_fetch_assoc($check);

/* =====================================
   VALIDASI
===================================== */

if (empty($data['payment_proof'])) {

  echo "<script>

        alert('Peserta belum mengunggah bukti pembayaran.');

        window.location='payments.php';

    </script>";

  exit;
}

if ($data['payment_status'] == 'rejected') {

  echo "<script>

        alert('Pembayaran sudah ditolak.');

        window.location='payments.php';

    </script>";

  exit;
}

/* =====================================
   UPDATE
===================================== */

$query = mysqli_query($conn, "

UPDATE user_tests

SET

payment_status='rejected',

verified_at=NOW(),

verified_by='$admin_id'

WHERE id='$id'

");

if (!$query) {

  die(mysqli_error($conn));
}

/* =====================================
   REDIRECT
===================================== */

echo "<script>

    alert('Pembayaran berhasil ditolak.');

    window.location='payments.php';

</script>";

exit;

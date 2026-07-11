<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

require "../../config/database.php";

/* =====================================
   Validasi Parameter ID
===================================== */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: payments.php");
  exit;
}

$id = (int)$_GET['id'];
$admin_id = (int)$_SESSION['id'];

/* =====================================
   Cek Keberadaan Data
===================================== */
$check = mysqli_query($conn, "
  SELECT * FROM user_tests WHERE id = '$id' LIMIT 1
");

if (!$check || mysqli_num_rows($check) == 0) {
  echo "<script>
        alert('Data pembayaran tidak ditemukan.');
        window.location.href = 'payments.php';
      </script>";
  exit;
}

$data = mysqli_fetch_assoc($check);

/* =====================================
   Validasi Status
===================================== */
if (empty($data['payment_proof'])) {
  echo "<script>
        alert('Peserta belum mengunggah bukti pembayaran.');
        window.location.href = 'payments.php';
      </script>";
  exit;
}

if ($data['payment_status'] === 'rejected') {
  echo "<script>
        alert('Pembayaran ini sudah ditolak sebelumnya.');
        window.location.href = 'payments.php';
      </script>";
  exit;
}

/* =====================================
   Proses Update Data
===================================== */
$update = mysqli_query($conn, "
  UPDATE user_tests
  SET
    payment_status = 'rejected',
    verified_at = NOW(),
    verified_by = '$admin_id'
  WHERE id = '$id'
");

if (!$update) {
  die("Terjadi kesalahan: " . mysqli_error($conn));
}

/* =====================================
   Redirect Kembali
===================================== */
echo "<script>
      alert('Pembayaran berhasil ditolak.');
      window.location.href = 'payments.php';
    </script>";
exit;

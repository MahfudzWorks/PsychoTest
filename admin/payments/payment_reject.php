<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

require "../../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: payments.php");
  exit;
}

$id = (int)$_GET['id'];
$admin_id = (int)$_SESSION['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM user_tests WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
  echo "<script>alert('Data pembayaran tidak ditemukan.'); window.location.href='payments.php';</script>";
  exit;
}

$data = mysqli_fetch_assoc($result);

if (empty($data['payment_proof'])) {
  echo "<script>alert('Peserta belum mengunggah bukti pembayaran.'); window.location.href='payments.php';</script>";
  exit;
}

if ($data['payment_status'] === 'rejected') {
  echo "<script>alert('Pembayaran ini sudah ditolak sebelumnya.'); window.location.href='payments.php';</script>";
  exit;
}

$update = mysqli_prepare($conn, "UPDATE user_tests SET payment_status = 'rejected', verified_at = NOW(), verified_by = ? WHERE id = ?");
mysqli_stmt_bind_param($update, "ii", $admin_id, $id);

if (mysqli_stmt_execute($update)) {
  echo "<script>alert('Pembayaran berhasil ditolak.'); window.location.href='payments.php';</script>";
} else {
  die("Terjadi kesalahan: " . mysqli_error($conn));
}
exit;

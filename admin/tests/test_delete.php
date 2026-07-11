<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";

if (!isset($_GET['id'])) {
  header("Location: tests.php");
  exit;
}

$id = (int)$_GET['id'];

$check = mysqli_query($conn, "SELECT id FROM tests WHERE id='$id'");
if (mysqli_num_rows($check) == 0) {
  header("Location: tests.php");
  exit;
}

// Cek apakah tes sudah digunakan peserta
$used = mysqli_query($conn, "SELECT id FROM user_tests WHERE test_id='$id' LIMIT 1");
if (mysqli_num_rows($used) > 0) {
  echo "<script>alert('Tes tidak dapat dihapus karena sudah digunakan oleh peserta.'); window.location='tests.php';</script>";
  exit;
}

// Hapus data yang berhubungan terlebih dahulu
mysqli_query($conn, "DELETE FROM questions WHERE test_id='$id'");

// Hapus data tes
mysqli_query($conn, "DELETE FROM tests WHERE id='$id'");

// Kembali ke daftar
header("Location: tests.php?status=deleted");
exit;

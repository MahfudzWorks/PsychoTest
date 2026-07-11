<?php
session_start();

// Cek akses admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";

// Cek ID
if (!isset($_GET['id'])) {
  header("Location: users.php");
  exit;
}

$id = (int)$_GET['id'];

// Pastikan data ada dan berjenis peserta
$cek = mysqli_query($conn, "SELECT id FROM users WHERE id='$id' AND role='peserta'");
if (mysqli_num_rows($cek) == 0) {
  header("Location: users.php");
  exit;
}

// Hapus data
mysqli_query($conn, "DELETE FROM users WHERE id='$id' AND role='peserta'");

// Kembali ke daftar
header("Location: users.php?status=deleted");
exit;

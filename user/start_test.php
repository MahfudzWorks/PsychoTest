<?php
session_start();
require "../config/database.php";

// 1. Validasi Login & Sesi ID
if (!isset($_SESSION['login']) || empty($_SESSION['id'])) {
  header("Location: ../auth/login.php");
  exit;
}

// 2. Validasi Parameter ID Tes
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: dashboard.php");
  exit;
}

$user_id = (int)$_SESSION['id'];
$test_id = (int)$_GET['id'];

// 3. Cek validitas modul tes aktif
$query_test = "SELECT * FROM tests WHERE id = ? AND status = 'active' LIMIT 1";
$stmt_test = mysqli_prepare($conn, $query_test);
mysqli_stmt_bind_param($stmt_test, "i", $test_id);
mysqli_stmt_execute($stmt_test);
$res_test = mysqli_stmt_get_result($stmt_test);

if (mysqli_num_rows($res_test) === 0) {
  echo "<script>alert('Tes tidak ditemukan atau sudah tidak aktif.'); window.location='dashboard.php';</script>";
  exit;
}
$test = mysqli_fetch_assoc($res_test);

// 4. Cek riwayat status pengerjaan user sebelumnya
$query_check = "SELECT * FROM user_tests WHERE user_id = ? AND test_id = ? ORDER BY id DESC LIMIT 1";
$stmt_check = mysqli_prepare($conn, $query_check);
mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $test_id);
mysqli_stmt_execute($stmt_check);
$res_check = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($res_check) > 0) {
  $userTest = mysqli_fetch_assoc($res_check);

  // Jika sedang berjalan, teruskan kembali ke halaman ujian
  if ($userTest['status'] === 'on_progress') {
    header("Location: test.php?user_test_id=" . $userTest['id']);
    exit;
  }
  // Jika sudah selesai, alihkan ke halaman selesai/invoice
  if ($userTest['status'] === 'completed') {
    header("Location: finish.php?id=" . $userTest['id']);
    exit;
  }
}

// 5. Daftarkan sesi pengerjaan ujian baru
$duration = (int)$test['duration'] * 60; // Konversi menit ke detik

$query_insert = "
  INSERT INTO user_tests (user_id, test_id, start_time, status, score, remaining_time, payment_status, created_at) 
  VALUES (?, ?, NOW(), 'on_progress', 0, ?, 'unpaid', NOW())
";
$stmt_insert = mysqli_prepare($conn, $query_insert);
mysqli_stmt_bind_param($stmt_insert, "iii", $user_id, $test_id, $duration);
mysqli_stmt_execute($stmt_insert);

$user_test_id = mysqli_insert_id($conn);

// 6. Alihkan ke halaman lembar soal
header("Location: test.php?user_test_id=" . $user_test_id);
exit;

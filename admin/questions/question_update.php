<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";

// Cek metode akses
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: questions.php");
  exit;
}

// Ambil dan bersihkan data
$id         = (int)$_POST['id'];
$test_id    = (int)$_POST['test_id'];
$question   = mysqli_real_escape_string($conn, trim($_POST['question']));
$option_a   = mysqli_real_escape_string($conn, trim($_POST['option_a']));
$option_b   = mysqli_real_escape_string($conn, trim($_POST['option_b']));
$option_c   = mysqli_real_escape_string($conn, trim($_POST['option_c']));
$option_d   = mysqli_real_escape_string($conn, trim($_POST['option_d']));
$option_e   = mysqli_real_escape_string($conn, trim($_POST['option_e']));
$answer     = strtoupper(trim($_POST['answer']));

// Validasi data
if (empty($id) || empty($test_id) || empty($question) || empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d) || empty($option_e) || !in_array($answer, ['A', 'B', 'C', 'D', 'E'])) {
  echo "<script>alert('Semua kolom harus diisi dengan benar!'); window.history.back();</script>";
  exit;
}

// Ambil data tes lama
$cek = mysqli_query($conn, "SELECT test_id FROM questions WHERE id = '$id' LIMIT 1");
if (!$cek || mysqli_num_rows($cek) == 0) {
  echo "<script>alert('Data soal tidak ditemukan!'); window.location.href = 'questions.php';</script>";
  exit;
}
$old_test = (int)mysqli_fetch_assoc($cek)['test_id'];

// Perbarui data soal
$update = mysqli_query($conn, "
  UPDATE questions SET
    test_id = '$test_id', question = '$question',
    option_a = '$option_a', option_b = '$option_b',
    option_c = '$option_c', option_d = '$option_d',
    option_e = '$option_e', answer = '$answer',
    updated_at = NOW()
  WHERE id = '$id'
");

// Perbarui jumlah soal
if ($update) {
  if ($old_test != $test_id) {
    mysqli_query($conn, "UPDATE tests SET total_questions = (SELECT COUNT(*) FROM questions WHERE test_id = '$old_test') WHERE id = '$old_test'");
  }
  mysqli_query($conn, "UPDATE tests SET total_questions = (SELECT COUNT(*) FROM questions WHERE test_id = '$test_id') WHERE id = '$test_id'");
  echo "<script>alert('Soal berhasil diperbarui!'); window.location.href = 'questions.php';</script>";
} else {
  echo "<script>alert('Gagal memperbarui soal!'); window.history.back();</script>";
}

exit;

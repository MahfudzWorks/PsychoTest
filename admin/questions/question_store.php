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
$test_id   = (int)$_POST['test_id'];
$question  = mysqli_real_escape_string($conn, trim($_POST['question']));
$option_a  = mysqli_real_escape_string($conn, trim($_POST['option_a']));
$option_b  = mysqli_real_escape_string($conn, trim($_POST['option_b']));
$option_c  = mysqli_real_escape_string($conn, trim($_POST['option_c']));
$option_d  = mysqli_real_escape_string($conn, trim($_POST['option_d']));
$option_e  = mysqli_real_escape_string($conn, trim($_POST['option_e']));
$answer    = strtoupper(trim($_POST['answer']));

// Validasi data
if (empty($test_id) || empty($question) || empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d) || empty($option_e) || !in_array($answer, ['A', 'B', 'C', 'D', 'E'])) {
  echo "<script>alert('Semua kolom harus diisi dengan benar!'); window.history.back();</script>";
  exit;
}

// Simpan ke database
$insert = mysqli_query($conn, "
  INSERT INTO questions (test_id, question, option_a, option_b, option_c, option_d, option_e, answer, created_at)
  VALUES ('$test_id', '$question', '$option_a', '$option_b', '$option_c', '$option_d', '$option_e', '$answer', NOW())
");

// Perbarui jumlah soal
if ($insert) {
  mysqli_query($conn, "UPDATE tests SET total_questions = (SELECT COUNT(*) FROM questions WHERE test_id = '$test_id') WHERE id = '$test_id'");
  echo "<script>alert('Soal berhasil ditambahkan!'); window.location.href = 'questions.php';</script>";
} else {
  echo "<script>alert('Gagal menambahkan soal!'); window.history.back();</script>";
}

exit;

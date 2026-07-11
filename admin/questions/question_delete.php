<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";

/* ==========================
   Validasi Parameter ID
========================== */
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || empty($_GET['id'])) {
  header("Location: questions.php");
  exit;
}

$id = (int)$_GET['id'];

/* ==========================
   Cek Keberadaan Data Soal
========================== */
$getQuestion = mysqli_query($conn, "
  SELECT test_id FROM questions WHERE id = '$id' LIMIT 1
");

if (!$getQuestion || mysqli_num_rows($getQuestion) == 0) {
  echo "<script>
        alert('Data soal tidak ditemukan.');
        window.location.href = 'questions.php';
      </script>";
  exit;
}

$data = mysqli_fetch_assoc($getQuestion);
$test_id = (int)$data['test_id'];

/* ==========================
   Proses Hapus Soal
========================== */
$delete = mysqli_query($conn, "
  DELETE FROM questions WHERE id = '$id'
");

/* ==========================
   Perbarui Jumlah Soal di Tes
========================== */
if ($delete) {
  // Hitung ulang jumlah soal untuk tes terkait
  mysqli_query($conn, "
    UPDATE tests
    SET total_questions = (
      SELECT COUNT(*) FROM questions WHERE test_id = '$test_id'
    )
    WHERE id = '$test_id'
  ");

  echo "<script>
        alert('Soal berhasil dihapus.');
        window.location.href = 'questions.php';
      </script>";
} else {
  echo "<script>
        alert('Gagal menghapus soal: " . addslashes(mysqli_error($conn)) . "');
        window.location.href = 'questions.php';
      </script>";
}

exit;

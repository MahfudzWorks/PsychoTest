<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

/* ==========================
   VALIDASI ID
========================== */

if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: questions.php");
  exit;
}

$id = (int) $_GET['id'];

/* ==========================
   AMBIL TEST ID
========================== */

$getQuestion = mysqli_query($conn, "
    SELECT test_id
    FROM questions
    WHERE id='$id'
");

if (mysqli_num_rows($getQuestion) == 0) {

  echo "<script>
        alert('Data soal tidak ditemukan.');
        window.location='questions.php';
    </script>";
  exit;
}

$data = mysqli_fetch_assoc($getQuestion);
$test_id = $data['test_id'];

/* ==========================
   HAPUS SOAL
========================== */

$delete = mysqli_query($conn, "
    DELETE FROM questions
    WHERE id='$id'
");

/* ==========================
   UPDATE TOTAL SOAL
========================== */

if ($delete) {

  mysqli_query($conn, "
        UPDATE tests
        SET total_questions = (
            SELECT COUNT(*)
            FROM questions
            WHERE test_id='$test_id'
        )
        WHERE id='$test_id'
    ");

  echo "<script>
        alert('Soal berhasil dihapus.');
        window.location='questions.php';
    </script>";
} else {

  echo "<script>
        alert('Gagal menghapus soal.');
        window.location='questions.php';
    </script>";
}

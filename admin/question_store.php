<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

/* ==========================
   VALIDASI METHOD
========================== */

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("Location: questions.php");
  exit;
}

/* ==========================
   AMBIL DATA
========================== */

$test_id   = mysqli_real_escape_string($conn, $_POST['test_id']);
$question  = mysqli_real_escape_string($conn, $_POST['question']);
$option_a  = mysqli_real_escape_string($conn, $_POST['option_a']);
$option_b  = mysqli_real_escape_string($conn, $_POST['option_b']);
$option_c  = mysqli_real_escape_string($conn, $_POST['option_c']);
$option_d  = mysqli_real_escape_string($conn, $_POST['option_d']);
$option_e  = mysqli_real_escape_string($conn, $_POST['option_e']);
$answer    = mysqli_real_escape_string($conn, $_POST['answer']);

/* ==========================
   VALIDASI
========================== */

if (
  empty($test_id) ||
  empty($question) ||
  empty($option_a) ||
  empty($option_b) ||
  empty($option_c) ||
  empty($option_d) ||
  empty($option_e) ||
  empty($answer)
) {

  echo "<script>
            alert('Semua data wajib diisi!');
            window.history.back();
          </script>";
  exit;
}

/* ==========================
   SIMPAN SOAL
========================== */

$insert = mysqli_query($conn, "
INSERT INTO questions
(
    test_id,
    question,
    option_a,
    option_b,
    option_c,
    option_d,
    option_e,
    answer,
    created_at
)

VALUES
(
    '$test_id',
    '$question',
    '$option_a',
    '$option_b',
    '$option_c',
    '$option_d',
    '$option_e',
    '$answer',
    NOW()
)
");

/* ==========================
   UPDATE TOTAL SOAL
========================== */

if ($insert) {

  mysqli_query($conn, "
        UPDATE tests
        SET total_questions =
        (
            SELECT COUNT(*)
            FROM questions
            WHERE test_id='$test_id'
        )
        WHERE id='$test_id'
    ");

  echo "<script>
            alert('Soal berhasil ditambahkan.');
            window.location='questions.php';
          </script>";
} else {

  echo "<script>
            alert('Gagal menambahkan soal.');
            window.history.back();
          </script>";
}

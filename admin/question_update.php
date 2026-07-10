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

$id         = (int) $_POST['id'];
$test_id    = mysqli_real_escape_string($conn, $_POST['test_id']);
$question   = mysqli_real_escape_string($conn, $_POST['question']);
$option_a   = mysqli_real_escape_string($conn, $_POST['option_a']);
$option_b   = mysqli_real_escape_string($conn, $_POST['option_b']);
$option_c   = mysqli_real_escape_string($conn, $_POST['option_c']);
$option_d   = mysqli_real_escape_string($conn, $_POST['option_d']);
$option_e   = mysqli_real_escape_string($conn, $_POST['option_e']);
$answer     = mysqli_real_escape_string($conn, $_POST['answer']);

/* ==========================
   VALIDASI
========================== */

if (
  empty($id) ||
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
        alert('Semua data wajib diisi.');
        window.history.back();
    </script>";
  exit;
}

/* ==========================
   TEST LAMA
========================== */

$getOld = mysqli_query($conn, "
    SELECT test_id
    FROM questions
    WHERE id='$id'
");

$oldData = mysqli_fetch_assoc($getOld);
$old_test = $oldData['test_id'];

/* ==========================
   UPDATE
========================== */

$update = mysqli_query($conn, "
UPDATE questions
SET
    test_id='$test_id',
    question='$question',
    option_a='$option_a',
    option_b='$option_b',
    option_c='$option_c',
    option_d='$option_d',
    option_e='$option_e',
    answer='$answer'
WHERE id='$id'
");

/* ==========================
   UPDATE TOTAL QUESTION
========================== */

if ($update) {

  // Update total soal tes lama
  mysqli_query($conn, "
        UPDATE tests
        SET total_questions = (
            SELECT COUNT(*)
            FROM questions
            WHERE test_id='$old_test'
        )
        WHERE id='$old_test'
    ");

  // Update total soal tes baru
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
        alert('Soal berhasil diperbarui.');
        window.location='questions.php';
    </script>";
} else {

  echo "<script>
        alert('Gagal memperbarui soal.');
        window.history.back();
    </script>";
}

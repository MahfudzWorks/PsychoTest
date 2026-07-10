<?php

session_start();

require "../config/database.php";

/* =====================================
   LOGIN
===================================== */

if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

/* =====================================
   VALIDASI TEST ID
===================================== */

if (!isset($_GET['id'])) {
  header("Location: dashboard.php");
  exit;
}

$user_id = (int) $_SESSION['id'];
$test_id = (int) $_GET['id'];

/* =====================================
   CEK TEST
===================================== */

$testQuery = mysqli_query($conn, "
SELECT *
FROM tests
WHERE id='$test_id'
AND status='active'
");

if (mysqli_num_rows($testQuery) == 0) {

  echo "<script>

    alert('Tes tidak ditemukan.');

    window.location='dashboard.php';

    </script>";

  exit;
}

$test = mysqli_fetch_assoc($testQuery);

/* =====================================
   CEK APAKAH SUDAH ADA
===================================== */

$check = mysqli_query($conn, "
SELECT *
FROM user_tests
WHERE user_id='$user_id'
AND test_id='$test_id'
ORDER BY id DESC
LIMIT 1
");

if (mysqli_num_rows($check) > 0) {

  $userTest = mysqli_fetch_assoc($check);

  /* ===========================
       MASIH MENGERJAKAN
    =========================== */

  if ($userTest['status'] == 'in_progress') {

    header("Location: test.php?user_test_id=" . $userTest['id']);
    exit;
  }

  /* ===========================
       SUDAH SELESAI
    =========================== */

  if ($userTest['status'] == 'finished') {

    header("Location: finish.php?id=" . $userTest['id']);
    exit;
  }
}

/* =====================================
   INSERT USER TEST
===================================== */

$duration = $test['duration'] * 60;

mysqli_query($conn, "

INSERT INTO user_tests(

user_id,
test_id,
start_time,
status,
score,
remaining_time,
payment_status,
created_at

)

VALUES(

'$user_id',
'$test_id',
NOW(),
'in_progress',
0,
'$duration',
'pending',
NOW()

)

");

$user_test_id = mysqli_insert_id($conn);

/* =====================================
   REDIRECT TEST
===================================== */

header("Location: test.php?user_test_id=" . $user_test_id);

exit;

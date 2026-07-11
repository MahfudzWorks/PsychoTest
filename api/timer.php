<?php

session_start();

require "../config/database.php";

/* =====================================
   LOGIN
===================================== */

if (!isset($_SESSION['login'])) {

  http_response_code(401);
  exit("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {

  http_response_code(405);
  exit("Method Not Allowed");
}

/* =====================================
   VALIDASI INPUT
===================================== */

$user_test_id = isset($_POST['user_test_id'])
  ? (int) $_POST['user_test_id']
  : 0;

$remaining_time = isset($_POST['remaining_time'])
  ? (int) $_POST['remaining_time']
  : 0;

if ($user_test_id <= 0) {

  http_response_code(400);
  exit("Invalid User Test");
}

if ($remaining_time < 0) {

  $remaining_time = 0;
}

/* =====================================
   VALIDASI KEPEMILIKAN TES
===================================== */

$user_id = (int) $_SESSION['id'];

$check = mysqli_query($conn, "

SELECT id,status

FROM user_tests

WHERE

id='$user_test_id'

AND

user_id='$user_id'

LIMIT 1

");

if (mysqli_num_rows($check) == 0) {

  http_response_code(403);
  exit("Access Denied");
}

$data = mysqli_fetch_assoc($check);

/* =====================================
   HANYA UPDATE JIKA MASIH BERLANGSUNG
===================================== */

if ($data['status'] != 'started') {

  exit("Finished");
}

/* =====================================
   UPDATE SISA WAKTU
===================================== */

mysqli_query($conn, "

UPDATE user_tests

SET

remaining_time='$remaining_time'

WHERE

id='$user_test_id'

");

/* =====================================
   RESPONSE
===================================== */

echo "success";

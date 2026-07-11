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

$question_id = isset($_POST['question_id'])
  ? (int) $_POST['question_id']
  : 0;

$answer = isset($_POST['answer'])
  ? strtoupper(trim($_POST['answer']))
  : "";

/* =====================================
   VALIDASI JAWABAN
===================================== */

$allowedAnswer = ['A', 'B', 'C', 'D', 'E'];

if (
  $user_test_id <= 0 ||
  $question_id <= 0 ||
  !in_array($answer, $allowedAnswer)
) {

  http_response_code(400);
  exit("Invalid Data");
}

/* =====================================
   VALIDASI USER TEST
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

$userTest = mysqli_fetch_assoc($check);

/* =====================================
   CEK STATUS
===================================== */

if ($userTest['status'] != 'started') {

  http_response_code(400);
  exit("Test Finished");
}

/* =====================================
   CEK JAWABAN
===================================== */

$exist = mysqli_query($conn, "

SELECT id

FROM user_answers

WHERE
user_test_id='$user_test_id'
AND
question_id='$question_id'

LIMIT 1

");

if (mysqli_num_rows($exist) > 0) {

  /* ===========================
       UPDATE
    =========================== */

  $query = mysqli_query($conn, "

  UPDATE user_answers

  SET answer='$answer'

  WHERE

  user_test_id='$user_test_id'

  AND

  question_id='$question_id'

  ");

  if (!$query) {

    die(mysqli_error($conn));
  }
} else {

  /* ===========================
       INSERT
    =========================== */

  $query = mysqli_query($conn, "

  INSERT INTO user_answers(

      user_test_id,
      question_id,
      answer,
      created_at

  )

  VALUES(

      '$user_test_id',
      '$question_id',
      '$answer',
      NOW()

  )

  ");

  if (!$query) {

    die(mysqli_error($conn));
  }
}

/* =====================================
   RESPONSE
===================================== */

echo "success";

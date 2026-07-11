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
  ? (int)$_POST['user_test_id']
  : 0;

if ($user_test_id <= 0) {

  http_response_code(400);
  exit("Invalid User Test");
}

$user_id = (int)$_SESSION['id'];

/* =====================================
   CEK USER TEST
===================================== */

$query = mysqli_query($conn, "

SELECT *

FROM user_tests

WHERE

id='$user_test_id'

AND

user_id='$user_id'

LIMIT 1

");

if (mysqli_num_rows($query) == 0) {

  http_response_code(403);
  exit("Access Denied");
}

$userTest = mysqli_fetch_assoc($query);

if ($userTest['status'] == 'finished') {

  exit("success");
}

/* =====================================
   HITUNG NILAI
===================================== */

$result = mysqli_query($conn, "

SELECT

questions.answer AS correct_answer,
user_answers.answer AS user_answer

FROM questions

LEFT JOIN user_answers

ON questions.id = user_answers.question_id

AND user_answers.user_test_id='$user_test_id'

WHERE

questions.test_id='" . $userTest['test_id'] . "'

");

$totalQuestion = mysqli_num_rows($result);

$correct = 0;

while ($row = mysqli_fetch_assoc($result)) {

  if (
    !empty($row['user_answer']) &&
    $row['user_answer'] == $row['correct_answer']
  ) {

    $correct++;
  }
}

$wrong = $totalQuestion - $correct;

$score = 0;

if ($totalQuestion > 0) {

  $score = round(($correct / $totalQuestion) * 100);
}

/* =====================================
   UPDATE USER TEST
===================================== */

mysqli_query($conn, "

UPDATE user_tests

SET

status='finished',

score='$score',

correct_answers='$correct',

wrong_answers='$wrong',

remaining_time='0',

end_time=NOW()

WHERE

id='$user_test_id'

");

/* =====================================
   RESPONSE
===================================== */

echo "success";

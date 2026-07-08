<?php

session_start();

require "../config/database.php";

header("Content-Type: application/json");

if (!isset($_SESSION['login'])) {

  exit;
}

$user_id = $_SESSION['id'];

$user_test_id = (int)$_POST['user_test_id'];

$question_id = (int)$_POST['question_id'];

$answer = $_POST['answer'];

$stmt = mysqli_prepare($conn, "
INSERT INTO user_answers
(user_test_id,question_id,answer)

VALUES(?,?,?)

ON DUPLICATE KEY UPDATE

answer=VALUES(answer)
");

mysqli_stmt_bind_param(

  $stmt,

  "iis",

  $user_test_id,

  $question_id,

  $answer

);

mysqli_stmt_execute($stmt);

echo json_encode([

  "success" => true

]);

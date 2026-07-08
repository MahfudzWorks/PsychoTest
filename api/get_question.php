<?php

session_start();
require "../config/database.php";

header('Content-Type: application/json');

if (!isset($_SESSION['login'])) {

  http_response_code(401);

  echo json_encode([
    "success" => false,
    "message" => "Unauthorized"
  ]);

  exit;
}

$test_id = isset($_GET['test_id']) ? (int)$_GET['test_id'] : 1;

$number = isset($_GET['number']) ? (int)$_GET['number'] : 1;

$offset = $number - 1;

$stmt = mysqli_prepare($conn, "
SELECT *
FROM questions
WHERE test_id=?
ORDER BY id ASC
LIMIT 1 OFFSET ?
");

mysqli_stmt_bind_param($stmt, "ii", $test_id, $offset);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if ($question = mysqli_fetch_assoc($result)) {

  echo json_encode([
    "success" => true,
    "question" => $question
  ]);
} else {

  echo json_encode([
    "success" => false
  ]);
}

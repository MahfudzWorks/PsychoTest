<?php

session_start();

require "../config/database.php";

header("Content-Type: application/json");

$user_test_id = (int)$_POST['user_test_id'];

$remaining = (int)$_POST['remaining'];

mysqli_query(
  $conn,

  "UPDATE user_tests

SET remaining_time=$remaining

WHERE id=$user_test_id"

);

echo json_encode([

  "success" => true

]);

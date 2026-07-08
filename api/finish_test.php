<?php

session_start();

require "../config/database.php";

$user_test_id = (int)$_POST['user_test_id'];

mysqli_query(
  $conn,

  "

UPDATE user_tests

SET

status='finished',

end_time=NOW()

WHERE id=$user_test_id

"

);

echo json_encode([

  "success" => true

]);

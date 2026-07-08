<?php

session_start();

require "../config/database.php";

header("Content-Type: application/json");

$user_test_id = (int)$_GET['user_test_id'];

$sql = "

SELECT COUNT(*) total,

SUM(

CASE

WHEN answer IS NOT NULL

THEN 1

ELSE 0

END

) answered

FROM user_answers

WHERE user_test_id=$user_test_id

";

$data = mysqli_fetch_assoc(mysqli_query($conn, $sql));

echo json_encode($data);

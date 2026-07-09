<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

if (!isset($_GET['id'])) {
  header("Location: users.php");
  exit;
}

$id = (int)$_GET['id'];

$cek = mysqli_query($conn, "
SELECT id
FROM users
WHERE id='$id'
AND role='peserta'
");

if (mysqli_num_rows($cek) == 0) {

  header("Location: users.php");
  exit;
}

mysqli_query($conn, "
DELETE FROM users
WHERE id='$id'
AND role='peserta'
");

header("Location: users.php");
exit;

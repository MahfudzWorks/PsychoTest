<?php

session_start();

require '../config/database.php';

$fullname = trim($_POST['fullname']);
$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($fullname) || empty($email) || empty($password)) {
  $_SESSION['error'] = "Semua data wajib diisi.";
  header("Location: register.php");
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['error'] = "Email tidak valid.";
  header("Location: register.php");
  exit;
}

$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email=?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

  $_SESSION['error'] = "Email sudah digunakan.";

  header("Location: register.php");
  exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn, "INSERT INTO users(fullname,email,password) VALUES(?,?,?)");

mysqli_stmt_bind_param($stmt, "sss", $fullname, $email, $hash);

mysqli_stmt_execute($stmt);

$_SESSION['success'] = "Registrasi berhasil. Silakan login.";

header("Location: login.php");
exit;

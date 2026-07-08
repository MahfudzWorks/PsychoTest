<?php

session_start();
require '../config/database.php';

$email = trim($_POST['email']);
$password = $_POST['password'];

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email=?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {

  if (password_verify($password, $user['password'])) {

    session_regenerate_id(true);

    $_SESSION['login'] = true;
    $_SESSION['id'] = $user['id'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'admin') {
      header("Location: ../admin/dashboard.php");
    } else {
      header("Location: ../user/dashboard.php");
    }

    exit;
  }
}

$_SESSION['error'] = "Email atau password salah.";
header("Location: login.php");
exit;

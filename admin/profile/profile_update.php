<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: profile.php");
  exit;
}

$id = (int)$_POST['id'];
$full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
$username = mysqli_real_escape_string($conn, trim($_POST['username']));
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
$password = trim($_POST['password']);

if (empty($full_name) || empty($username) || empty($email)) {
  echo "<script>alert('Data tidak boleh kosong!'); window.history.back();</script>";
  exit;
}

// Cek username/email sudah dipakai orang lain
$cek = mysqli_query($conn, "SELECT id FROM users WHERE (username = '$username' OR email = '$email') AND id != '$id' LIMIT 1");
if (mysqli_num_rows($cek) > 0) {
  echo "<script>alert('Username atau email sudah digunakan!'); window.history.back();</script>";
  exit;
}

// Update data
if (!empty($password)) {
  $pass_hash = password_hash($password, PASSWORD_DEFAULT);
  $update = mysqli_query($conn, "UPDATE users SET full_name = '$full_name', username = '$username', email = '$email', password = '$pass_hash' WHERE id = '$id'");
} else {
  $update = mysqli_query($conn, "UPDATE users SET full_name = '$full_name', username = '$username', email = '$email' WHERE id = '$id'");
}

if ($update) {
  echo "<script>alert('Profil berhasil diperbarui!'); window.location.href = 'profile.php';</script>";
} else {
  echo "<script>alert('Gagal memperbarui data!'); window.history.back();</script>";
}

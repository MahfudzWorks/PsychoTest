<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
  header("Location: ../auth/login.php");
  exit;
}

// Pakai koneksi yang sama
include "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: profile.php");
  exit;
}

$id = (int)$_POST['id'];
$fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
$password = trim($_POST['password']);

// Ambil data lama
$cek = mysqli_query($conn, "SELECT profile_pic FROM users WHERE id = '$id' LIMIT 1");
$user = mysqli_fetch_assoc($cek);
$nama_foto = $user['profile_pic'];

// Folder penyimpanan foto
$folder_tujuan = __DIR__ . "/profiles/";
if (!file_exists($folder_tujuan)) {
  mkdir($folder_tujuan, 0755, true);
}

// Proses unggah foto
if (!empty($_FILES['profile_pic']['name'])) {
  $nama_file_baru = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['profile_pic']['name']);
  $lokasi_sementara = $_FILES['profile_pic']['tmp_name'];
  $lokasi_tujuan = $folder_tujuan . $nama_file_baru;

  $tipe_diizinkan = ['image/jpeg', 'image/jpg', 'image/png'];
  if (!in_array($_FILES['profile_pic']['type'], $tipe_diizinkan)) {
    echo "<script>alert('Hanya file JPG, JPEG, dan PNG yang diperbolehkan!'); history.back();</script>";
    exit;
  }

  if (move_uploaded_file($lokasi_sementara, $lokasi_tujuan)) {
    if (!empty($user['profile_pic']) && file_exists($folder_tujuan . $user['profile_pic'])) {
      unlink($folder_tujuan . $user['profile_pic']);
    }
    $nama_foto = $nama_file_baru;
  } else {
    echo "<script>alert('Gagal mengunggah foto!'); history.back();</script>";
    exit;
  }
}

// Update data
if (!empty($password)) {
  $hash_pass = password_hash($password, PASSWORD_DEFAULT);
  $update = mysqli_query($conn, "UPDATE users SET fullname = '$fullname', email = '$email', password = '$hash_pass', profile_pic = '$nama_foto' WHERE id = '$id'");
} else {
  $update = mysqli_query($conn, "UPDATE users SET fullname = '$fullname', email = '$email', profile_pic = '$nama_foto' WHERE id = '$id'");
}

if ($update) {
  $_SESSION['fullname'] = $fullname;
  echo "<script>alert('Profil berhasil diperbarui!'); window.location.href = 'profile.php';</script>";
} else {
  echo "<script>alert('Gagal memperbarui data!'); history.back();</script>";
}

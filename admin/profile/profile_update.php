<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

// ✅ Jalur benar
include "../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: profile.php");
  exit;
}

$id = (int)$_POST['id'];
$fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
$password = trim($_POST['password']);

// Ambil data lama dari database
$cek = mysqli_query($conn, "SELECT profile_pic FROM users WHERE id = '$id' LIMIT 1");
$user = mysqli_fetch_assoc($cek);
$nama_foto = $user['profile_pic'];

// ✅ Jalur simpan: folder profiles ada di satu tingkat dengan file ini
$folder_tujuan = __DIR__ . "/profiles/";

// Buat folder secara otomatis jika belum ada
if (!file_exists($folder_tujuan)) {
  mkdir($folder_tujuan, 0755, true);
}

// Proses jika ada foto baru yang diunggah
if (!empty($_FILES['profile_pic']['name'])) {
  // Buat nama file unik agar tidak tertimpa
  $nama_file_baru = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['profile_pic']['name']);
  $lokasi_sementara = $_FILES['profile_pic']['tmp_name'];
  $lokasi_tujuan = $folder_tujuan . $nama_file_baru;

  // Cek jenis file yang diizinkan
  $tipe_diizinkan = ['image/jpeg', 'image/jpg', 'image/png'];
  if (!in_array($_FILES['profile_pic']['type'], $tipe_diizinkan)) {
    echo "<script>alert('Hanya file dengan format JPG, JPEG, dan PNG yang diperbolehkan!'); history.back();</script>";
    exit;
  }

  // Pindahkan file ke folder tujuan
  if (move_uploaded_file($lokasi_sementara, $lokasi_tujuan)) {
    // Hapus foto lama jika ada
    if (!empty($user['profile_pic']) && file_exists($folder_tujuan . $user['profile_pic'])) {
      unlink($folder_tujuan . $user['profile_pic']);
    }
    $nama_foto = $nama_file_baru;
  } else {
    echo "<script>alert('Gagal menyimpan foto! Pastikan izin folder sudah benar.'); history.back();</script>";
    exit;
  }
}

// Perbarui data ke database
if (!empty($password)) {
  $hash_pass = password_hash($password, PASSWORD_DEFAULT);
  $update = mysqli_query($conn, "UPDATE users SET fullname = '$fullname', email = '$email', password = '$hash_pass', profile_pic = '$nama_foto' WHERE id = '$id'");
} else {
  $update = mysqli_query($conn, "UPDATE users SET fullname = '$fullname', email = '$email', profile_pic = '$nama_foto' WHERE id = '$id'");
}

if ($update) {
  echo "<script>alert('Profil berhasil diperbarui!'); window.location.href = 'profile.php';</script>";
} else {
  echo "<script>alert('Gagal memperbarui data!'); history.back();</script>";
}

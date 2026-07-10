<?php

session_start();

require "../config/database.php";

/* =====================================
   LOGIN
===================================== */

if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

/* =====================================
   VALIDASI POST
===================================== */

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  header("Location: ../user/dashboard.php");
  exit;
}

$user_test_id = (int) $_POST['user_test_id'];

/* =====================================
   AMBIL DATA USER TEST
===================================== */

$query = mysqli_query($conn, "
SELECT *
FROM user_tests
WHERE id='$user_test_id'
AND user_id='" . $_SESSION['id'] . "'
");

if (mysqli_num_rows($query) == 0) {

  echo "<script>
    alert('Data tidak ditemukan.');
    window.location='../user/dashboard.php';
    </script>";
  exit;
}

$data = mysqli_fetch_assoc($query);

/* =====================================
   VALIDASI FILE
===================================== */

if (!isset($_FILES['payment_proof'])) {

  echo "<script>
    alert('Silakan pilih file.');
    history.back();
    </script>";
  exit;
}

$file = $_FILES['payment_proof'];

if ($file['error'] != 0) {

  echo "<script>
    alert('Gagal mengupload file.');
    history.back();
    </script>";
  exit;
}

/* =====================================
   VALIDASI UKURAN
===================================== */

$maxSize = 2 * 1024 * 1024;

if ($file['size'] > $maxSize) {

  echo "<script>
    alert('Ukuran file maksimal 2 MB.');
    history.back();
    </script>";
  exit;
}

/* =====================================
   VALIDASI EXTENSION
===================================== */

$allowed = ['jpg', 'jpeg', 'png', 'pdf'];

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($extension, $allowed)) {

  echo "<script>
    alert('Format file tidak didukung.');
    history.back();
    </script>";
  exit;
}

/* =====================================
   FOLDER
===================================== */

$folder = "../assets/uploads/payments/";

if (!is_dir($folder)) {
  mkdir($folder, 0777, true);
}

/* =====================================
   HAPUS FILE LAMA
===================================== */

if (!empty($data['payment_proof'])) {

  $oldFile = "../" . $data['payment_proof'];

  if (file_exists($oldFile)) {
    unlink($oldFile);
  }
}

/* =====================================
   NAMA FILE BARU
===================================== */

$newName = "payment_" .
  $user_test_id . "_" .
  time() . "." .
  $extension;

$destination = $folder . $newName;

/* =====================================
   UPLOAD
===================================== */

if (!move_uploaded_file($file['tmp_name'], $destination)) {

  echo "<script>
    alert('Upload gagal.');
    history.back();
    </script>";
  exit;
}

/* =====================================
   SIMPAN DATABASE
===================================== */

$filePath = "assets/uploads/payments/" . $newName;

$update = mysqli_query($conn, "
UPDATE user_tests
SET

payment_proof='$filePath',
payment_date=NOW(),
payment_status='pending'

WHERE id='$user_test_id'
");

/* =====================================
   REDIRECT
===================================== */

if ($update) {

  echo "<script>

    alert('Bukti pembayaran berhasil dikirim.');

    window.location='../user/payment.php?id=$user_test_id';

    </script>";
} else {

  echo "<script>

    alert('Gagal menyimpan data.');

    history.back();

    </script>";
}

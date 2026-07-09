<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

$pageTitle = "Tambah Peserta";

if (isset($_POST['save'])) {

  $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $status = $_POST['status'];

  $cek = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

  if (mysqli_num_rows($cek) > 0) {

    echo "<script>alert('Email sudah digunakan');</script>";
  } else {

    mysqli_query($conn, "
        INSERT INTO users
        (fullname,email,password,role,status,created_at)

        VALUES

        ('$fullname','$email','$password','peserta','$status',NOW())
        ");

    echo "<script>

        alert('Peserta berhasil ditambahkan');

        window.location='users.php';

        </script>";

    exit;
  }
}

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";
?>

<div class="ml-64 mt-16 p-8 bg-gray-100 min-h-screen">

  <div class="bg-white rounded-xl shadow p-8 max-w-3xl mx-auto">

    <h2 class="text-3xl font-bold mb-8">
      Tambah Peserta
    </h2>

    <form method="POST">

      <div class="mb-5">

        <label class="font-medium">
          Nama Lengkap
        </label>

        <input
          type="text"
          name="fullname"
          required
          class="w-full mt-2 border rounded-lg px-4 py-3">

      </div>

      <div class="mb-5">

        <label>Email</label>

        <input
          type="email"
          name="email"
          required
          class="w-full mt-2 border rounded-lg px-4 py-3">

      </div>

      <div class="mb-5">

        <label>Password</label>

        <input
          type="password"
          name="password"
          required
          class="w-full mt-2 border rounded-lg px-4 py-3">

      </div>

      <div class="mb-5">

        <label>Status</label>

        <select
          name="status"
          class="w-full mt-2 border rounded-lg px-4 py-3">

          <option value="active">
            Active
          </option>

          <option value="inactive">
            Inactive
          </option>

        </select>

      </div>

      <div class="flex gap-3">

        <button
          name="save"
          class="bg-blue-600 text-white px-6 py-3 rounded-lg">

          Simpan

        </button>

        <a
          href="users.php"
          class="bg-gray-300 px-6 py-3 rounded-lg">

          Kembali

        </a>

      </div>

    </form>

  </div>

</div>

<?php include "../includes/admin_footer.php"; ?>
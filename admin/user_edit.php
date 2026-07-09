<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

$pageTitle = "Edit Peserta";

if (!isset($_GET['id'])) {
  header("Location: users.php");
  exit;
}

$id = (int)$_GET['id'];

$data = mysqli_query($conn, "SELECT * FROM users WHERE id='$id' AND role='peserta'");

if (mysqli_num_rows($data) == 0) {
  header("Location: users.php");
  exit;
}

$user = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {

  $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $password = $_POST['password'];

  $cek = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' AND id != '$id'");

  if (mysqli_num_rows($cek) > 0) {

    echo "<script>alert('Email sudah digunakan!');</script>";
  } else {

    if (!empty($password)) {

      $password = password_hash($password, PASSWORD_DEFAULT);

      mysqli_query($conn, "
                UPDATE users SET
                fullname='$fullname',
                email='$email',
                password='$password',
                status='$status'
                WHERE id='$id'
            ");
    } else {

      mysqli_query($conn, "
                UPDATE users SET
                fullname='$fullname',
                email='$email',
                status='$status'
                WHERE id='$id'
            ");
    }

    echo "<script>
            alert('Data peserta berhasil diperbarui');
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

  <div class="max-w-3xl mx-auto bg-white rounded-xl shadow">

    <div class="border-b p-6">
      <h2 class="text-2xl font-bold">Edit Peserta</h2>
    </div>

    <form method="POST" class="p-6">

      <div class="mb-5">
        <label class="font-medium">Nama Lengkap</label>
        <input
          type="text"
          name="fullname"
          required
          value="<?= htmlspecialchars($user['fullname']) ?>"
          class="w-full border rounded-lg px-4 py-3 mt-2">
      </div>

      <div class="mb-5">
        <label class="font-medium">Email</label>
        <input
          type="email"
          name="email"
          required
          value="<?= htmlspecialchars($user['email']) ?>"
          class="w-full border rounded-lg px-4 py-3 mt-2">
      </div>

      <div class="mb-5">
        <label class="font-medium">
          Password Baru
          <small class="text-gray-500">(Kosongkan jika tidak ingin diubah)</small>
        </label>

        <input
          type="password"
          name="password"
          class="w-full border rounded-lg px-4 py-3 mt-2">
      </div>

      <div class="mb-6">
        <label class="font-medium">Status</label>

        <select
          name="status"
          class="w-full border rounded-lg px-4 py-3 mt-2">

          <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>
            Active
          </option>

          <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>
            Inactive
          </option>

        </select>

      </div>

      <div class="flex gap-3">

        <button
          type="submit"
          name="update"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

          Update

        </button>

        <a
          href="users.php"
          class="bg-gray-300 hover:bg-gray-400 px-6 py-3 rounded-lg">

          Kembali

        </a>

      </div>

    </form>

  </div>

</div>

<?php include "../includes/admin_footer.php"; ?>
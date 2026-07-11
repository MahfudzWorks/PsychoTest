<?php
session_start();

// Cek akses admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";
$pageTitle = "Edit Peserta";

// Cek ID
if (!isset($_GET['id'])) {
  header("Location: users.php");
  exit;
}

$id = (int)$_GET['id'];

// Ambil data peserta
$data = mysqli_query($conn, "SELECT * FROM users WHERE id='$id' AND role='peserta'");
if (mysqli_num_rows($data) == 0) {
  header("Location: users.php");
  exit;
}
$user = mysqli_fetch_assoc($data);

// Proses update
if (isset($_POST['update'])) {
  $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $password = $_POST['password'];

  // Cek email duplikat
  $cek = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' AND id != '$id'");
  if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Email sudah digunakan!');</script>";
  } else {
    // Update dengan atau tanpa password baru
    if (!empty($password)) {
      $password = password_hash($password, PASSWORD_DEFAULT);
      $query = "UPDATE users SET fullname='$fullname', email='$email', password='$password', status='$status' WHERE id='$id'";
    } else {
      $query = "UPDATE users SET fullname='$fullname', email='$email', status='$status' WHERE id='$id'";
    }

    mysqli_query($conn, $query);
    echo "<script>alert('Data berhasil diperbarui!'); window.location='users.php';</script>";
    exit;
  }
}

include "../../includes/admin_header.php";
include "../../includes/admin_sidebar.php";
include "../../includes/admin_navbar.php";
?>

<div class="ml-64 mt-16 p-8 bg-gray-50 min-h-screen">

  <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden card-animate">

    <div class="p-6 border-b border-gray-100 bg-gray-50">
      <h2 class="text-2xl font-bold text-gray-800">Edit Data Peserta</h2>
      <p class="text-gray-500 mt-1">Perbarui informasi peserta di bawah ini</p>
    </div>

    <form method="POST" class="p-6 space-y-5">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
        <input type="text" name="fullname" required value="<?= htmlspecialchars($user['fullname']) ?>"
          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>"
          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Password Baru
          <small class="text-gray-500 font-normal">(Kosongkan jika tidak ingin diubah)</small>
        </label>
        <input type="password" name="password"
          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status Akun</label>
        <select name="status"
          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
          <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Aktif</option>
          <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
        </select>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="submit" name="update"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-sm hover:shadow transition-all duration-200">
          <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
        </button>
        <a href="users.php"
          class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2.5 rounded-lg transition-all duration-200">
          Kembali
        </a>
      </div>
    </form>

  </div>

</div>

<!-- Efek animasi -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const card = document.querySelector('.card-animate');
    card.style.opacity = '0';
    card.style.transform = 'translateY(15px)';
    setTimeout(() => {
      card.style.transition = 'all 0.4s ease-out';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, 100);
  });
</script>

<?php include "../../includes/admin_footer.php"; ?>
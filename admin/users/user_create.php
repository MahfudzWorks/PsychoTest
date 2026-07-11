<?php
session_start();

// Cek akses admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";
$pageTitle = "Tambah Peserta";

// Proses simpan data
if (isset($_POST['save'])) {
  $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $status = mysqli_real_escape_string($conn, $_POST['status']);

  // Cek email sudah terdaftar
  $cek = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
  if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Email sudah digunakan!');</script>";
  } else {
    // Simpan ke database
    mysqli_query($conn, "
      INSERT INTO users (fullname, email, password, role, status, created_at)
      VALUES ('$fullname', '$email', '$password', 'peserta', '$status', NOW())
    ");
    echo "<script>alert('Peserta berhasil ditambahkan!'); window.location='users.php';</script>";
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
      <h2 class="text-2xl font-bold text-gray-800">Tambah Peserta Baru</h2>
      <p class="text-gray-500 mt-1">Lengkapi data di bawah ini untuk mendaftarkan peserta</p>
    </div>

    <form method="POST" class="p-6 space-y-5">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
        <input type="text" name="fullname" required
          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input type="email" name="email" required
          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input type="password" name="password" required
          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status Akun</label>
        <select name="status"
          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
          <option value="active">Aktif</option>
          <option value="inactive">Tidak Aktif</option>
        </select>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="submit" name="save"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-sm hover:shadow transition-all duration-200">
          <i class="fa-solid fa-plus mr-2"></i> Simpan Data
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
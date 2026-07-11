<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";
$pageTitle = "Profil Saya";

include "../../includes/admin_header.php";
include "../../includes/admin_sidebar.php";
include "../../includes/admin_navbar.php";

$admin_id = (int)$_SESSION['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$admin_id' AND role = 'admin' LIMIT 1");
$user = mysqli_fetch_assoc($query);
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">

  <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Profil Saya</h1>
    <p class="text-gray-500 mt-1">Kelola informasi akun administrator</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kartu Profil -->
    <div class="lg:col-span-1">
      <div class="bg-white rounded-xl shadow-md p-6 text-center card-animate">
        <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fa-solid fa-user text-5xl text-blue-500"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($user['full_name']) ?></h3>
        <p class="text-gray-500">Administrator</p>
        <div class="mt-4 pt-4 border-t">
          <p class="text-sm text-gray-600">Terdaftar sejak: <?= date('d M Y', strtotime($user['created_at'])) ?></p>
        </div>
      </div>
    </div>

    <!-- Form Ubah Data -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-xl shadow-md p-6 card-animate">
        <h4 class="text-lg font-semibold text-gray-700 mb-6">Ubah Informasi Akun</h4>
        <form action="profile_update.php" method="POST">
          <input type="hidden" name="id" value="<?= $user['id'] ?>">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
              <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-600 mb-1">Username</label>
              <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-600 mb-1">Password Baru <span class="text-gray-400">(kosongkan jika tidak ingin diubah)</span></label>
            <input type="password" name="password" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
          </div>

          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
            <i class="fa-solid fa-save mr-1"></i> Simpan Perubahan
          </button>
        </form>
      </div>
    </div>
  </div>

</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card-animate');
    cards.forEach((el, i) => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(15px)';
      setTimeout(() => {
        el.style.transition = 'all 0.3s ease-out';
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
      }, i * 100);
    });
  });
</script>

<?php include "../../includes/admin_footer.php"; ?>
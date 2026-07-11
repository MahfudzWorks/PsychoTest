<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

// ✅ Jalur benar: naik 2 tingkat dari folder profile
include "../../config/database.php";
$pageTitle = "Profil Saya";

include "../../includes/admin_header.php";
include "../../includes/admin_sidebar.php";
include "../../includes/admin_navbar.php";

$admin_id = (int)$_SESSION['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$admin_id' AND role = 'admin' LIMIT 1");
$user = mysqli_fetch_assoc($query);

// ✅ Jalur foto: dari folder profile masuk ke folder profiles
$foto = !empty($user['profile_pic'])
  ? "profiles/" . $user['profile_pic']
  : "../../assets/images/default.png";
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">

  <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Profil Saya</h1>
    <p class="text-gray-500 mt-1">Kelola data akun administrator</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Info Profil + Foto -->
    <div class="lg:col-span-1">
      <div class="bg-white rounded-xl shadow-md p-6 text-center card-animate">
        <!-- Foto dengan tombol edit -->
        <div class="relative w-32 h-32 mx-auto mb-4">
          <img src="<?= htmlspecialchars($foto) ?>" alt="Foto Profil" class="w-full h-full object-cover rounded-full border-4 border-blue-100 shadow">
          <!-- Tombol Edit Kecil -->
          <button type="button" onclick="document.getElementById('inputFoto').click()" class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full shadow-md transition">
            <i class="fa-solid fa-pen text-sm"></i>
          </button>
        </div>

        <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($user['fullname']) ?></h3>
        <p class="text-gray-500">Administrator</p>
        <div class="mt-4 pt-4 border-t text-sm text-gray-600">
          Terdaftar sejak: <?= date('d M Y', strtotime($user['created_at'])) ?>
        </div>
      </div>
    </div>

    <!-- Form Ubah Data -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-xl shadow-md p-6 card-animate">
        <h4 class="text-lg font-semibold text-gray-700 mb-6">Ubah Data Akun</h4>
        <form action="profile_update.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $user['id'] ?>">
          <!-- Input file tersembunyi -->
          <input type="file" name="profile_pic" id="inputFoto" accept="image/*" class="hidden">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
              <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
              <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-600 mb-1">Password Baru <span class="text-gray-400">(kosongkan jika tidak diubah)</span></label>
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
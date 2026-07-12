<?php
session_start();

// Cek sesi dengan cara aman
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
  header("Location: ../auth/login.php");
  exit;
}

// Pastikan role ada
if (!isset($_SESSION['role'])) {
  $_SESSION['role'] = 'user';
}

// ✅ Gunakan file koneksi yang sudah ada dan berfungsi
include "../config/database.php";

$pageTitle = "Profil Saya";

// Panggil komponen yang sama seperti dashboard
include "../includes/header.php";
include "../includes/navbar_user.php";

$user_id = (int)$_SESSION['id'];

// Ambil data pengguna
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id' LIMIT 1");
if (!$query) {
  die("Error pada query: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($query);
if (!$user) {
  die("Data akun tidak ditemukan.");
}

// Tentukan lokasi foto
$foto = !empty($user['profile_pic'])
  ? "profiles/" . $user['profile_pic']
  : "../assets/images/default.png";
?>

<!-- Konten Halaman -->
<div class="min-h-[80vh] px-4 pt-4 lg:pt-8 pb-12">
  <div class="max-w-6xl mx-auto">

    <div class="mb-8">
      <h1 class="text-3xl font-bold text-slate-900">Profil Saya</h1>
      <p class="text-slate-500 mt-1">Kelola data akun Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Kartu Foto -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-md p-6 text-center border border-slate-100 hover:shadow-lg transition-shadow">
          <div class="relative w-36 h-36 mx-auto mb-5">
            <img src="<?= htmlspecialchars($foto) ?>" alt="Foto Profil" class="w-full h-full object-cover rounded-full border-4 border-indigo-100">
            <button type="button" onclick="document.getElementById('inputFoto').click()" class="absolute bottom-1 right-1 bg-indigo-600 hover:bg-indigo-700 text-white p-2.5 rounded-full shadow-md">
              <i class="fa-solid fa-pen text-sm"></i>
            </button>
          </div>
          <h3 class="text-xl font-bold text-slate-900"><?= htmlspecialchars($user['fullname']) ?></h3>
          <p class="text-slate-500 mt-1">Pengguna</p>
          <div class="mt-5 pt-4 border-t text-sm text-slate-600">
            Terdaftar: <?= date('d M Y', strtotime($user['created_at'])) ?>
          </div>
        </div>
      </div>

      <!-- Form Ubah Data -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-md p-6 border border-slate-100 hover:shadow-lg transition-shadow">
          <h4 class="text-lg font-semibold text-slate-800 mb-6">Ubah Data Akun</h4>
          <form action="profile_update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <input type="file" name="profile_pic" id="inputFoto" accept="image/jpeg, image/jpg, image/png" class="hidden">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
              <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Nama Lengkap</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none">
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Alamat Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none">
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-slate-600 mb-2">Kata Sandi Baru <span class="text-xs text-slate-400">(kosongkan jika tidak diubah)</span></label>
              <input type="password" name="password" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none">
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl shadow">
              <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

<?php include "../includes/footer.php"; ?>
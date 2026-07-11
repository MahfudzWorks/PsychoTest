<?php
// Ambil data admin dari database
$admin_id = (int)$_SESSION['id'];
$user_nav = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fullname, profile_pic FROM users WHERE id = '$admin_id' LIMIT 1"));

// ✅ Jalur lokasi foto profil
$foto_nav = !empty($user_nav['profile_pic'])
  ? "/PsychoTest/admin/profile/profiles/" . $user_nav['profile_pic']
  : "../assets/images/default.png";

// ✅ Ambil jumlah NOTIFIKASI yang BELUM DIBACA
// Sesuai struktur tabel: related_id = ID pengguna, is_read = 0 = belum dibaca
$jumlah_notif = 0;
$query_notif = mysqli_query($conn, "SELECT COUNT(*) AS total FROM notifications WHERE related_id = '$admin_id' AND is_read = 0");
if ($query_notif) {
  $data_notif = mysqli_fetch_array($query_notif);
  $jumlah_notif = (int)$data_notif['total'];
}

// ✅ Ambil jumlah PESAN dari tabel `massages` yang BELUM DIBACA
// Karena tabel massages tidak ada kolom penerima, dihitung semua pesan masuk ke admin
$jumlah_pesan = 0;
$query_pesan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM messages WHERE is_read = 0");
if ($query_pesan) {
  $data_pesan = mysqli_fetch_array($query_pesan);
  $jumlah_pesan = (int)$data_pesan['total'];
}
?>

<nav class="fixed top-0 left-64 right-0 h-16 bg-white shadow-sm border-b border-gray-100 z-40 transition-all duration-300">
  <div class="flex items-center justify-between h-full px-8">

    <!-- Bagian Kiri -->
    <div class="flex items-center gap-6">
      <h2 class="text-xl md:text-2xl font-bold text-gray-800 tracking-tight">
        Dashboard
      </h2>

      <!-- Kotak Pencarian -->
      <div class="relative hidden md:block">
        <input
          type="text"
          placeholder="Cari sesuatu..."
          class="w-72 lg:w-80 pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-gray-400"></i>
      </div>
    </div>

    <!-- Bagian Kanan -->
    <div class="flex items-center gap-4 lg:gap-6">
      <!-- Notifikasi -->
      <a href="/PsychoTest/includes/notifikasi.php" class="relative w-10 h-10 rounded-xl hover:bg-gray-100 transition-all duration-200 flex items-center justify-center group">
        <i class="fa-regular fa-bell text-lg text-gray-600 group-hover:text-gray-800"></i>
        <?php if ($jumlah_notif > 0): ?>
          <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-medium w-5 h-5 rounded-full flex items-center justify-center shadow-sm">
            <?= $jumlah_notif > 99 ? '99+' : $jumlah_notif ?>
          </span>
        <?php endif; ?>
      </a>

      <!-- Pesan -->
      <a href="/PsychoTest/includes/pesan.php" class="relative w-10 h-10 rounded-xl hover:bg-gray-100 transition-all duration-200 flex items-center justify-center group">
        <i class="fa-regular fa-envelope text-lg text-gray-600 group-hover:text-gray-800"></i>
        <?php if ($jumlah_pesan > 0): ?>
          <span class="absolute -top-1 -right-1 bg-green-500 text-white text-[10px] font-medium w-5 h-5 rounded-full flex items-center justify-center shadow-sm">
            <?= $jumlah_pesan > 99 ? '99+' : $jumlah_pesan ?>
          </span>
        <?php endif; ?>
      </a>

      <!-- Menu Profil -->
      <div class="relative group cursor-pointer">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition-all duration-200">
          <img
            src="<?= htmlspecialchars($foto_nav) ?>"
            alt="Profil"
            class="w-10 h-10 rounded-full border-2 border-gray-200 object-cover shadow-sm"
            onerror="this.src='../assets/images/default.png'">
          <div class="hidden md:block text-left">
            <h4 class="font-semibold text-gray-800 text-sm">
              <?= htmlspecialchars($user_nav['fullname'] ?? "Administrator") ?>
            </h4>
            <p class="text-xs text-gray-500">
              Administrator
            </p>
          </div>
          <i class="fa-solid fa-chevron-down text-xs text-gray-400 group-hover:text-gray-600 transition-transform duration-200 group-hover:rotate-180"></i>
        </div>

        <!-- Dropdown Menu -->
        <div class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden">
          <a href="/PsychoTest/admin/profile/profile.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
            <i class="fa-regular fa-user text-blue-500"></i>
            <span class="text-gray-700 font-medium">Profil Saya</span>
          </a>
          <div class="border-t border-gray-100 my-1"></div>
          <a href="/PsychoTest/auth/logout.php" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-colors">
            <i class="fa-solid fa-right-from-bracket text-red-500"></i>
            <span class="text-red-600 font-medium">Keluar</span>
          </a>
        </div>
      </div>
    </div>

  </div>
</nav>
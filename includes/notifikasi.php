<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";
$pageTitle = "Pemberitahuan";

// Tandai semua dibaca jika tombol diklik
if (isset($_GET['mark_all_read'])) {
  mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE is_read = 0");
  header("Location: notifikasi.php");
  exit;
}

// Ambil semua notifikasi, urutkan dari yang terbaru
$query_notif = mysqli_query($conn, "
  SELECT * FROM notifications 
  ORDER BY created_at DESC
");

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Pemberitahuan</h1>
    <p class="text-gray-500 mt-1">Semua aktivitas dan informasi sistem</p>
  </div>

  <div class="bg-white rounded-xl shadow-md p-6 card-animate">
    <div class="flex justify-between items-center mb-5">
      <h4 class="text-lg font-semibold text-gray-700">Daftar Pemberitahuan</h4>
      <a href="?mark_all_read=1" class="text-sm text-blue-600 hover:underline">Tandai semua dibaca</a>
    </div>

    <div class="space-y-3">
      <?php if (mysqli_num_rows($query_notif) > 0): ?>
        <?php while ($notif = mysqli_fetch_assoc($query_notif)): ?>
          <?php
          // Tentukan warna dan gaya berdasarkan jenis notifikasi
          $bg = $notif['is_read'] == 0 ? 'bg-blue-50 border-blue-100' : 'bg-gray-50 border-gray-100';
          $dot = $notif['is_read'] == 0 ? '<span class="w-2 h-2 rounded-full bg-blue-500"></span>' : '';
          ?>
          <div class="p-4 rounded-lg border <?= $bg ?>">
            <div class="flex justify-between items-start">
              <div>
                <h5 class="font-semibold text-gray-800"><?= htmlspecialchars($notif['title']) ?></h5>
                <p class="text-gray-600 mt-1"><?= htmlspecialchars($notif['message']) ?></p>
                <span class="text-xs text-gray-400 mt-2 inline-block">
                  <?= date('d M Y H:i', strtotime($notif['created_at'])) ?>
                </span>
              </div>
              <?= $dot ?>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="p-6 text-center text-gray-500">
          Belum ada pemberitahuan untuk saat ini.
        </div>
      <?php endif; ?>
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
</script>

<?php include "../includes/admin_footer.php"; ?>
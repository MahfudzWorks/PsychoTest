<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";
$pageTitle = "Pesan Masuk";

// Tandai pesan sudah dibaca jika ada aksi
if (isset($_GET['read_id'])) {
  $read_id = (int)$_GET['read_id'];
  mysqli_query($conn, "UPDATE messages SET is_read = 1 WHERE id = $read_id");
  header("Location: pesan.php");
  exit;
}

// Ambil semua pesan dari database
$query_pesan = mysqli_query($conn, "
  SELECT * FROM messages 
  ORDER BY created_at DESC
");

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Pesan Masuk</h1>
    <p class="text-gray-500 mt-1">Pesan dari pengguna dan pengunjung</p>
  </div>

  <div class="bg-white rounded-xl shadow-md p-6 card-animate">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-200">
            <th class="text-left p-3">Pengirim</th>
            <th class="text-left p-3">Subjek</th>
            <th class="text-center p-3">Waktu</th>
            <th class="text-center p-3">Status</th>
            <th class="text-center p-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($query_pesan) > 0): ?>
            <?php while ($pesan = mysqli_fetch_assoc($query_pesan)): ?>
              <?php
              $baris = $pesan['is_read'] == 0 ? 'bg-blue-50 font-medium' : '';
              $status = $pesan['is_read'] == 0
                ? '<span class="px-2 py-1 rounded-full bg-blue-100 text-blue-600 text-xs">Belum dibaca</span>'
                : '<span class="px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-xs">Sudah dibaca</span>';
              ?>
              <tr class="border-b border-gray-100 hover:bg-gray-50 <?= $baris ?>">
                <td class="p-3">
                  <?= htmlspecialchars($pesan['sender_name']) ?><br>
                  <span class="text-xs text-gray-500"><?= htmlspecialchars($pesan['sender_email']) ?></span>
                </td>
                <td class="p-3"><?= htmlspecialchars($pesan['subject']) ?></td>
                <td class="text-center p-3 text-gray-500">
                  <?= date('d M Y H:i', strtotime($pesan['created_at'])) ?>
                </td>
                <td class="text-center p-3"><?= $status ?></td>
                <td class="text-center p-3">
                  <a href="lihat_pesan.php?id=<?= $pesan['id'] ?>" class="text-blue-600 hover:underline">Lihat</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="p-6 text-center text-gray-500">
                Belum ada pesan masuk untuk saat ini.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
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

<?php include "../includes/admin_footer.php"; ?>
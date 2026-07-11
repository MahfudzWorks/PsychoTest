<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";

$pageTitle = "Edit Jenis Tes";

if (!isset($_GET['id'])) {
  header("Location: tests.php");
  exit;
}

$id = (int)$_GET['id'];

$data = mysqli_query($conn, "SELECT * FROM tests WHERE id='$id'");
if (mysqli_num_rows($data) == 0) {
  header("Location: tests.php");
  exit;
}
$test = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $duration = (int)$_POST['duration'];
  $total_questions = (int)$_POST['total_questions'];
  $status = mysqli_real_escape_string($conn, $_POST['status']);

  $check = mysqli_query($conn, "SELECT id FROM tests WHERE title='$title' AND id != '$id'");
  if (mysqli_num_rows($check) > 0) {
    echo "<script>alert('Nama tes sudah digunakan.');</script>";
  } else {
    mysqli_query($conn, "
      UPDATE tests 
      SET title='$title', description='$description', duration='$duration', total_questions='$total_questions', status='$status'
      WHERE id='$id'
    ");
    echo "<script>alert('Jenis tes berhasil diperbarui.'); window.location='tests.php';</script>";
    exit;
  }
}

include "../../includes/admin_header.php";
include "../../includes/admin_sidebar.php";
include "../../includes/admin_navbar.php";
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">

  <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden card-animate">

    <div class="p-6 border-b border-gray-100 bg-gray-50">
      <h2 class="text-2xl font-bold text-gray-800">Edit Jenis Tes</h2>
      <p class="text-gray-500 mt-1">Perbarui informasi jenis tes sesuai kebutuhan</p>
    </div>

    <form method="POST" class="p-6 space-y-5">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tes</label>
        <input type="text" name="title" required value="<?= htmlspecialchars($test['title']) ?>"
          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
        <textarea name="description" rows="4" required
          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"><?= htmlspecialchars($test['description']) ?></textarea>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (Menit)</label>
          <input type="number" name="duration" min="1" required value="<?= $test['duration'] ?>"
            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Soal</label>
          <input type="number" name="total_questions" min="1" required value="<?= $test['total_questions'] ?>"
            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status Tes</label>
        <select name="status"
          class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
          <option value="active" <?= $test['status'] == 'active' ? 'selected' : '' ?>>Aktif</option>
          <option value="inactive" <?= $test['status'] == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
        </select>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="submit" name="update"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-sm hover:shadow transition-all duration-200">
          <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Perubahan
        </button>
        <a href="tests.php"
          class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2.5 rounded-lg transition-all duration-200">
          <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
      </div>
    </form>

  </div>

</div>

<!-- Efek Animasi -->
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
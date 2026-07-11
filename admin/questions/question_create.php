<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../../auth/login.php");
  exit;
}

include "../../config/database.php";

$pageTitle = "Tambah Soal";

include "../../includes/admin_header.php";
include "../../includes/admin_sidebar.php";
include "../../includes/admin_navbar.php";

/*
|--------------------------------------------------------------------------
| Ambil Semua Jenis Tes Aktif
|--------------------------------------------------------------------------
*/
$tests = mysqli_query($conn, "
  SELECT * FROM tests
  WHERE status = 'active'
  ORDER BY title ASC
");
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-50">

  <!-- Judul & Tombol Kembali -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Tambah Soal</h1>
      <p class="text-gray-500 mt-1">Masukkan data soal baru untuk ditambahkan ke dalam paket psikotes</p>
    </div>
    <a href="questions.php"
      class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- Form Tambah Soal -->
  <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100 card-animate">
    <form action="question_store.php" method="POST">

      <!-- Pilih Jenis Tes -->
      <div class="mb-6">
        <label for="test_id" class="block font-semibold text-gray-700 mb-2">Jenis Tes</label>
        <select name="test_id" id="test_id" required
          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200">
          <option value="">-- Pilih Jenis Tes --</option>
          <?php while ($test = mysqli_fetch_assoc($tests)): ?>
            <option value="<?= $test['id'] ?>">
              <?= htmlspecialchars($test['title']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- Pertanyaan -->
      <div class="mb-6">
        <label for="question" class="block font-semibold text-gray-700 mb-2">Pertanyaan</label>
        <textarea name="question" id="question" rows="5" required
          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200 resize-y"
          placeholder="Tuliskan isi soal di sini..."></textarea>
      </div>

      <!-- Pilihan A -->
      <div class="mb-6">
        <label for="option_a" class="block font-semibold text-gray-700 mb-2">Pilihan A</label>
        <input type="text" name="option_a" id="option_a" required
          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200"
          placeholder="Masukkan jawaban pilihan A">
      </div>

      <!-- Pilihan B -->
      <div class="mb-6">
        <label for="option_b" class="block font-semibold text-gray-700 mb-2">Pilihan B</label>
        <input type="text" name="option_b" id="option_b" required
          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200"
          placeholder="Masukkan jawaban pilihan B">
      </div>

      <!-- Pilihan C -->
      <div class="mb-6">
        <label for="option_c" class="block font-semibold text-gray-700 mb-2">Pilihan C</label>
        <input type="text" name="option_c" id="option_c" required
          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200"
          placeholder="Masukkan jawaban pilihan C">
      </div>

      <!-- Pilihan D -->
      <div class="mb-6">
        <label for="option_d" class="block font-semibold text-gray-700 mb-2">Pilihan D</label>
        <input type="text" name="option_d" id="option_d" required
          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200"
          placeholder="Masukkan jawaban pilihan D">
      </div>

      <!-- Pilihan E -->
      <div class="mb-6">
        <label for="option_e" class="block font-semibold text-gray-700 mb-2">Pilihan E</label>
        <input type="text" name="option_e" id="option_e" required
          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200"
          placeholder="Masukkan jawaban pilihan E">
      </div>

      <!-- Jawaban Benar -->
      <div class="mb-8">
        <label for="answer" class="block font-semibold text-gray-700 mb-2">Jawaban Benar</label>
        <select name="answer" id="answer" required
          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition-all duration-200">
          <option value="">-- Pilih Jawaban yang Benar --</option>
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
          <option value="E">E</option>
        </select>
      </div>

      <!-- Tombol Aksi -->
      <div class="flex flex-col sm:flex-row justify-end gap-3">
        <a href="questions.php"
          class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-lg text-center transition-all duration-200">
          Batal
        </a>
        <button type="reset"
          class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2.5 rounded-lg transition-all duration-200">
          Reset
        </button>
        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg flex items-center justify-center gap-2 transition-all duration-200">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Soal
        </button>
      </div>

    </form>
  </div>

</div>

<!-- Efek Animasi -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.card-animate');
    elements.forEach((el, index) => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      setTimeout(() => {
        el.style.transition = 'all 0.4s ease-out';
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
      }, index * 100);
    });
  });
</script>

<?php include "../../includes/admin_footer.php"; ?>
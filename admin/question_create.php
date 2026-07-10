<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

$pageTitle = "Tambah Soal";

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";

/*
|--------------------------------------------------------------------------
| Ambil Semua Jenis Tes
|--------------------------------------------------------------------------
*/

$tests = mysqli_query($conn, "
SELECT *
FROM tests
WHERE status='active'
ORDER BY title ASC
");

?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-100">

  <!-- Header -->

  <div class="flex justify-between items-center mb-8">

    <div>

      <h1 class="text-3xl font-bold text-gray-800">
        Tambah Soal
      </h1>

      <p class="text-gray-500">
        Tambahkan soal baru ke dalam paket psikotes.
      </p>

    </div>

    <a href="questions.php"
      class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg">

      <i class="fa-solid fa-arrow-left mr-2"></i>

      Kembali

    </a>

  </div>

  <!-- Form -->

  <div class="bg-white rounded-xl shadow p-8">

    <form
      action="question_store.php"
      method="POST">

      <!-- Pilih Tes -->

      <div class="mb-6">

        <label class="block font-semibold mb-2">

          Jenis Tes

        </label>

        <select
          name="test_id"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

          <option value="">
            -- Pilih Tes --
          </option>

          <?php while ($test = mysqli_fetch_assoc($tests)): ?>

            <option value="<?= $test['id']; ?>">

              <?= htmlspecialchars($test['title']); ?>

            </option>

          <?php endwhile; ?>

        </select>

      </div>

      <!-- Soal -->

      <div class="mb-6">

        <label class="block font-semibold mb-2">

          Pertanyaan

        </label>

        <textarea
          name="question"
          rows="5"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
          placeholder="Masukkan soal..."></textarea>

      </div>
      <!-- Pilihan A -->

      <div class="mb-6">

        <label class="block font-semibold mb-2">

          Pilihan A

        </label>

        <input
          type="text"
          name="option_a"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
          placeholder="Masukkan pilihan A">

      </div>

      <!-- Pilihan B -->

      <div class="mb-6">

        <label class="block font-semibold mb-2">

          Pilihan B

        </label>

        <input
          type="text"
          name="option_b"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
          placeholder="Masukkan pilihan B">

      </div>

      <!-- Pilihan C -->

      <div class="mb-6">

        <label class="block font-semibold mb-2">

          Pilihan C

        </label>

        <input
          type="text"
          name="option_c"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
          placeholder="Masukkan pilihan C">

      </div>

      <!-- Pilihan D -->

      <div class="mb-6">

        <label class="block font-semibold mb-2">

          Pilihan D

        </label>

        <input
          type="text"
          name="option_d"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
          placeholder="Masukkan pilihan D">

      </div>

      <!-- Pilihan E -->

      <div class="mb-6">

        <label class="block font-semibold mb-2">

          Pilihan E

        </label>

        <input
          type="text"
          name="option_e"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
          placeholder="Masukkan pilihan E">

      </div>

      <!-- Jawaban Benar -->

      <div class="mb-8">

        <label class="block font-semibold mb-2">

          Jawaban Benar

        </label>

        <select
          name="answer"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

          <option value="">
            -- Pilih Jawaban --
          </option>

          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
          <option value="E">E</option>

        </select>

      </div>

      <!-- Tombol -->

      <div class="flex justify-end gap-3">

        <a href="questions.php"
          class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

          Batal

        </a>

        <button
          type="reset"
          class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg">

          Reset

        </button>

        <button
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

          <i class="fa-solid fa-floppy-disk mr-2"></i>

          Simpan Soal

        </button>

      </div>

    </form>

  </div>

</div>

<?php include "../includes/admin_footer.php"; ?>
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

$pageTitle = "Edit Soal";

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";

/* ==========================
   VALIDASI ID
========================== */

if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: questions.php");
  exit;
}

$id = (int) $_GET['id'];

/* ==========================
   AMBIL DATA SOAL
========================== */

$query = mysqli_query($conn, "
    SELECT *
    FROM questions
    WHERE id='$id'
");

if (mysqli_num_rows($query) == 0) {
  header("Location: questions.php");
  exit;
}

$data = mysqli_fetch_assoc($query);

/* ==========================
   AMBIL DATA TEST
========================== */

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
        Edit Soal
      </h1>

      <p class="text-gray-500">
        Perbarui data soal psikotes.
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
      action="question_update.php"
      method="POST">

      <input
        type="hidden"
        name="id"
        value="<?= $data['id']; ?>">

      <!-- Jenis Tes -->

      <div class="mb-6">

        <label class="block font-semibold mb-2">

          Jenis Tes

        </label>

        <select
          name="test_id"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

          <?php while ($test = mysqli_fetch_assoc($tests)): ?>

            <option
              value="<?= $test['id']; ?>"
              <?= ($test['id'] == $data['test_id']) ? 'selected' : ''; ?>>

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
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($data['question']); ?></textarea>

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
          value="<?= htmlspecialchars($data['option_a']); ?>"
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

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
          value="<?= htmlspecialchars($data['option_b']); ?>"
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

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
          value="<?= htmlspecialchars($data['option_c']); ?>"
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

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
          value="<?= htmlspecialchars($data['option_d']); ?>"
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

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
          value="<?= htmlspecialchars($data['option_e']); ?>"
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

      </div>

      <!-- Jawaban -->

      <div class="mb-8">

        <label class="block font-semibold mb-2">

          Jawaban Benar

        </label>

        <select
          name="answer"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">

          <option value="A" <?= ($data['answer'] == 'A') ? 'selected' : ''; ?>>A</option>

          <option value="B" <?= ($data['answer'] == 'B') ? 'selected' : ''; ?>>B</option>

          <option value="C" <?= ($data['answer'] == 'C') ? 'selected' : ''; ?>>C</option>

          <option value="D" <?= ($data['answer'] == 'D') ? 'selected' : ''; ?>>D</option>

          <option value="E" <?= ($data['answer'] == 'E') ? 'selected' : ''; ?>>E</option>

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

          Update Soal

        </button>

      </div>

    </form>

  </div>

</div>

<?php include "../includes/admin_footer.php"; ?>
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

$pageTitle = "Tambah Jenis Tes";

if (isset($_POST['save'])) {

  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $duration = (int) $_POST['duration'];
  $total_questions = (int) $_POST['total_questions'];
  $status = mysqli_real_escape_string($conn, $_POST['status']);

  $check = mysqli_query($conn, "
        SELECT id
        FROM tests
        WHERE title='$title'
    ");

  if (mysqli_num_rows($check) > 0) {

    echo "<script>alert('Nama tes sudah tersedia.');</script>";
  } else {

    mysqli_query($conn, "
            INSERT INTO tests (
                title,
                description,
                duration,
                total_questions,
                status,
                created_at
            )
            VALUES (
                '$title',
                '$description',
                '$duration',
                '$total_questions',
                '$status',
                NOW()
            )
        ");

    echo "<script>
            alert('Jenis tes berhasil ditambahkan.');
            window.location='tests.php';
        </script>";

    exit;
  }
}

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";
?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-100">

  <div class="max-w-4xl mx-auto bg-white rounded-xl shadow">

    <div class="border-b p-6">
      <h2 class="text-2xl font-bold text-gray-800">
        Tambah Jenis Tes
      </h2>
    </div>

    <form method="POST" class="p-6">

      <div class="mb-5">
        <label class="block font-medium mb-2">
          Nama Tes
        </label>

        <input
          type="text"
          name="title"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
          placeholder="Contoh: Tes Kepribadian DISC">
      </div>

      <div class="mb-5">
        <label class="block font-medium mb-2">
          Deskripsi
        </label>

        <textarea
          name="description"
          rows="4"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
          placeholder="Masukkan deskripsi tes..."></textarea>
      </div>

      <div class="grid grid-cols-2 gap-5">

        <div>
          <label class="block font-medium mb-2">
            Durasi (Menit)
          </label>

          <input
            type="number"
            name="duration"
            min="1"
            required
            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div>
          <label class="block font-medium mb-2">
            Total Soal
          </label>

          <input
            type="number"
            name="total_questions"
            min="1"
            required
            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

      </div>

      <div class="mt-5 mb-6">

        <label class="block font-medium mb-2">
          Status
        </label>

        <select
          name="status"
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

          <option value="active">
            Active
          </option>

          <option value="inactive">
            Inactive
          </option>

        </select>

      </div>

      <div class="flex gap-3">

        <button
          type="submit"
          name="save"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

          <i class="fa-solid fa-floppy-disk mr-2"></i>
          Simpan

        </button>

        <a
          href="tests.php"
          class="bg-gray-300 hover:bg-gray-400 px-6 py-3 rounded-lg">

          <i class="fa-solid fa-arrow-left mr-2"></i>
          Kembali

        </a>

      </div>

    </form>

  </div>

</div>

<?php include "../includes/admin_footer.php"; ?>
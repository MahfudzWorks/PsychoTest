<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

$pageTitle = "Edit Jenis Tes";

if (!isset($_GET['id'])) {
  header("Location: tests.php");
  exit;
}

$id = (int) $_GET['id'];

$data = mysqli_query($conn, "
    SELECT *
    FROM tests
    WHERE id='$id'
");

if (mysqli_num_rows($data) == 0) {
  header("Location: tests.php");
  exit;
}

$test = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {

  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $duration = (int) $_POST['duration'];
  $total_questions = (int) $_POST['total_questions'];
  $status = mysqli_real_escape_string($conn, $_POST['status']);

  $check = mysqli_query($conn, "
        SELECT id
        FROM tests
        WHERE title='$title'
        AND id != '$id'
    ");

  if (mysqli_num_rows($check) > 0) {

    echo "<script>alert('Nama tes sudah digunakan.');</script>";
  } else {

    mysqli_query($conn, "
            UPDATE tests SET
                title='$title',
                description='$description',
                duration='$duration',
                total_questions='$total_questions',
                status='$status'
            WHERE id='$id'
        ");

    echo "<script>
            alert('Jenis tes berhasil diperbarui.');
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
        Edit Jenis Tes
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
          value="<?= htmlspecialchars($test['title']) ?>"
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

      </div>

      <div class="mb-5">

        <label class="block font-medium mb-2">
          Deskripsi
        </label>

        <textarea
          name="description"
          rows="4"
          required
          class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"><?= htmlspecialchars($test['description']) ?></textarea>

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
            value="<?= $test['duration'] ?>"
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
            value="<?= $test['total_questions'] ?>"
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

          <option value="active" <?= $test['status'] == 'active' ? 'selected' : '' ?>>
            Active
          </option>

          <option value="inactive" <?= $test['status'] == 'inactive' ? 'selected' : '' ?>>
            Inactive
          </option>

        </select>

      </div>

      <div class="flex gap-3">

        <button
          type="submit"
          name="update"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

          <i class="fa-solid fa-floppy-disk mr-2"></i>
          Update

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
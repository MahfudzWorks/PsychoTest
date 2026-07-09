<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include "../config/database.php";

$pageTitle = "Kelola Jenis Tes";

include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";
include "../includes/admin_navbar.php";

/* ==========================
   SEARCH
========================== */

$search = "";

if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
}

/* ==========================
   DATA TEST
========================== */

$query = mysqli_query($conn, "
    SELECT *
    FROM tests
    WHERE title LIKE '%$search%'
    ORDER BY created_at DESC
");

/* ==========================
   STATISTIK
========================== */

$total = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM tests
"))['total'];

$active = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM tests
    WHERE status='active'
"))['total'];

$inactive = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM tests
    WHERE status='inactive'
"))['total'];

?>

<div class="ml-64 mt-16 p-8 min-h-screen bg-gray-100">

  <div class="flex justify-between items-center mb-8">

    <div>
      <h1 class="text-3xl font-bold text-gray-800">
        Kelola Jenis Tes
      </h1>

      <p class="text-gray-500">
        Kelola seluruh paket psikotes.
      </p>
    </div>

    <a href="test_create.php"
      class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">
      <i class="fa-solid fa-plus mr-2"></i>
      Tambah Tes
    </a>

  </div>

  <!-- Statistik -->

  <div class="grid grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow p-6">
      <p class="text-gray-500">Total Tes</p>
      <h2 class="text-4xl font-bold text-blue-600 mt-2">
        <?= $total ?>
      </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
      <p class="text-gray-500">Tes Aktif</p>
      <h2 class="text-4xl font-bold text-green-600 mt-2">
        <?= $active ?>
      </h2>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
      <p class="text-gray-500">Tes Tidak Aktif</p>
      <h2 class="text-4xl font-bold text-red-600 mt-2">
        <?= $inactive ?>
      </h2>
    </div>

  </div>

  <!-- Search -->

  <div class="bg-white rounded-xl shadow p-5 mb-6">

    <form method="GET" class="flex gap-3">

      <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search) ?>"
        placeholder="Cari nama tes..."
        class="flex-1 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

      <button
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-lg">

        <i class="fa-solid fa-magnifying-glass mr-2"></i>
        Cari

      </button>

    </form>

  </div>

  <!-- Table -->

  <div class="bg-white rounded-xl shadow overflow-hidden">

    <table class="w-full">

      <thead class="bg-gray-100">

        <tr>
          <th class="p-4 w-16">No</th>
          <th class="text-left">Nama Tes</th>
          <th class="text-left">Durasi</th>
          <th class="text-center">Jumlah Soal</th>
          <th class="text-center">Status</th>
          <th class="text-center">Aksi</th>
        </tr>

      </thead>

      <tbody>

        <?php if (mysqli_num_rows($query) > 0) : ?>

          <?php $no = 1; ?>

          <?php while ($row = mysqli_fetch_assoc($query)) : ?>

            <tr class="border-t hover:bg-gray-50">

              <td class="text-center p-4">
                <?= $no++ ?>
              </td>

              <td>
                <div class="font-semibold">
                  <?= htmlspecialchars($row['title']) ?>
                </div>

                <small class="text-gray-500">
                  <?= htmlspecialchars($row['description']) ?>
                </small>
              </td>

              <td>
                <?= $row['duration'] ?> Menit
              </td>

              <td class="text-center">
                <?= $row['total_questions'] ?>
              </td>

              <td class="text-center">

                <?php if ($row['status'] == 'active') : ?>

                  <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                    Active
                  </span>

                <?php else : ?>

                  <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">
                    Inactive
                  </span>

                <?php endif; ?>

              </td>

              <td>

                <div class="flex justify-center gap-2">

                  <a href="test_edit.php?id=<?= $row['id'] ?>"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg">
                    <i class="fa-solid fa-pen"></i>
                  </a>

                  <a href="test_delete.php?id=<?= $row['id'] ?>"
                    onclick="return confirm('Yakin ingin menghapus tes ini?')"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg">
                    <i class="fa-solid fa-trash"></i>
                  </a>

                </div>

              </td>

            </tr>

          <?php endwhile; ?>

        <?php else : ?>

          <tr>

            <td colspan="6" class="text-center py-10 text-gray-500">
              Data tes belum tersedia.
            </td>

          </tr>

        <?php endif; ?>

      </tbody>

    </table>

  </div>

</div>

<?php include "../includes/admin_footer.php"; ?>
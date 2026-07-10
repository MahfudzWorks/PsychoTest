<?php

session_start();

require "../config/database.php";

/* =====================================
   LOGIN
===================================== */

if (!isset($_SESSION['login'])) {
  header("Location: ../auth/login.php");
  exit;
}

/* =====================================
   VALIDASI ID TEST
===================================== */

if (!isset($_GET['id'])) {

  header("Location: dashboard.php");
  exit;
}

$test_id = (int) $_GET['id'];

/* =====================================
   DATA USER
===================================== */

$user_id  = $_SESSION['id'];
$fullname = $_SESSION['fullname'];

/* =====================================
   AMBIL DATA TEST
===================================== */

$query = mysqli_query($conn, "
SELECT *
FROM tests
WHERE id='$test_id'
AND status='active'
");

if (mysqli_num_rows($query) == 0) {

  echo "<script>

    alert('Tes tidak ditemukan.');

    window.location='dashboard.php';

    </script>";

  exit;
}

$test = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>

<html lang="id">

<head>

  <meta charset="UTF-8">

  <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

  <title>Persiapan Tes | PsychoTest</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-gray-100 font-[Poppins]">

  <!-- Navbar -->

  <nav class="bg-white shadow">

    <div class="max-w-7xl mx-auto px-6">

      <div class="flex justify-between items-center h-20">

        <a
          href="dashboard.php"
          class="text-3xl font-bold text-blue-600">

          🧠 PsychoTest

        </a>

        <span class="font-semibold text-gray-700">

          <?= htmlspecialchars($fullname); ?>

        </span>

      </div>

    </div>

  </nav>

  <!-- Content -->

  <section class="max-w-5xl mx-auto py-10 px-6">

    <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

      <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-10">

        <h1 class="text-4xl font-bold">

          <?= htmlspecialchars($test['title']); ?>

        </h1>

        <p class="mt-3 text-blue-100 text-lg">

          <?= htmlspecialchars($test['description']); ?>

        </p>

      </div>

      <div class="p-10">

        <!-- Informasi Tes -->

        <div class="grid md:grid-cols-3 gap-6 mb-10">

          <div class="bg-blue-50 rounded-xl p-6 text-center">

            <i class="fa-solid fa-file-lines text-4xl text-blue-600"></i>

            <h3 class="font-bold mt-4">

              Jumlah Soal

            </h3>

            <p class="text-gray-600 mt-2 text-lg font-semibold">

              <?= $test['total_questions']; ?> Soal

            </p>

          </div>

          <div class="bg-orange-50 rounded-xl p-6 text-center">

            <i class="fa-solid fa-clock text-4xl text-orange-500"></i>

            <h3 class="font-bold mt-4">

              Durasi

            </h3>

            <p class="text-gray-600 mt-2 text-lg font-semibold">

              <?= $test['duration']; ?> Menit

            </p>

          </div>

          <div class="bg-green-50 rounded-xl p-6 text-center">

            <i class="fa-solid fa-money-bill-wave text-4xl text-green-600"></i>

            <h3 class="font-bold mt-4">

              Biaya Tes

            </h3>

            <p class="text-gray-600 mt-2 text-lg font-semibold">

              Rp <?= number_format($test['price'], 0, ",", "."); ?>

            </p>

          </div>

        </div>

        <!-- Petunjuk -->

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-xl">

          <h2 class="text-2xl font-bold mb-4">

            Petunjuk Pengerjaan Tes

          </h2>

          <ul class="space-y-3 text-gray-700">

            <li>✅ Pastikan koneksi internet stabil.</li>

            <li>✅ Kerjakan tes di tempat yang tenang.</li>

            <li>✅ Jangan menutup browser selama tes berlangsung.</li>

            <li>✅ Timer akan berjalan otomatis setelah tes dimulai.</li>

            <li>✅ Jawaban masih dapat diubah sebelum waktu habis.</li>

            <li>✅ Tes hanya dapat dikerjakan satu kali.</li>

            <li>✅ Hasil tes dapat dilihat setelah pembayaran diverifikasi oleh admin.</li>

          </ul>

        </div>
        <!-- Persetujuan -->

        <div class="mt-8">

          <label class="flex items-start gap-3">

            <input
              type="checkbox"
              id="agree"
              class="mt-1 w-5 h-5">

            <span class="text-gray-700 leading-7">

              Saya telah membaca seluruh petunjuk di atas dan
              memahami tata cara pelaksanaan tes psikologi.
              Saya bersedia mengikuti tes dengan jujur dan
              bertanggung jawab.

            </span>

          </label>

        </div>

        <!-- Tombol -->

        <div class="mt-10 flex justify-between">

          <a
            href="dashboard.php"
            class="px-8 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 transition">

            <i class="fa-solid fa-arrow-left mr-2"></i>

            Kembali

          </a>

          <button
            id="startBtn"
            disabled
            class="px-8 py-3 rounded-xl bg-blue-600 text-white opacity-50 cursor-not-allowed transition">

            <i class="fa-solid fa-play mr-2"></i>

            Saya Siap, Mulai Tes

          </button>

        </div>

      </div>

    </div>

  </section>

  <script>
    const agree = document.getElementById("agree");
    const startBtn = document.getElementById("startBtn");

    agree.addEventListener("change", function() {

      if (this.checked) {

        startBtn.disabled = false;

        startBtn.classList.remove(
          "opacity-50",
          "cursor-not-allowed"
        );

      } else {

        startBtn.disabled = true;

        startBtn.classList.add(
          "opacity-50",
          "cursor-not-allowed"
        );

      }

    });

    startBtn.addEventListener("click", function() {

      if (!confirm("Apakah Anda yakin ingin memulai tes? Waktu tes akan langsung berjalan setelah dimulai.")) {
        return;
      }

      window.location.href = "start_test.php?id=<?= $test['id']; ?>";

    });
  </script>

</body>

</html>
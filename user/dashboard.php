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

$user_id  = (int)$_SESSION['id'];
$fullname = $_SESSION['fullname'];

/* =====================================
   DATA TES
===================================== */

$query = mysqli_query($conn, "

SELECT

tests.*,

ut.id AS user_test_id,
ut.status AS test_status,
ut.payment_status

FROM tests

LEFT JOIN user_tests ut

ON ut.id=(

SELECT id

FROM user_tests

WHERE

user_id='$user_id'

AND

test_id=tests.id

ORDER BY id DESC

LIMIT 1

)

WHERE tests.status='active'

ORDER BY tests.created_at DESC

");

?>

<!DOCTYPE html>

<html lang="id">

<head>

  <meta charset="UTF-8">

  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

  <title>

    Dashboard User

  </title>

  <script src="https://cdn.tailwindcss.com"></script>

  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-gray-100 font-[Poppins]">

  <!-- ==========================
NAVBAR
========================== -->

  <nav class="bg-white shadow">

    <div class="max-w-7xl mx-auto px-6">

      <div class="h-20 flex justify-between items-center">

        <div>

          <h1 class="text-3xl font-bold text-blue-600">

            🧠 PsychoTest

          </h1>

        </div>

        <div class="flex items-center gap-5">

          <span class="font-semibold">

            Halo,

            <b>

              <?= htmlspecialchars($fullname) ?>

            </b>

          </span>

          <a
            href="profile.php"
            class="text-blue-600 hover:text-blue-800">

            <i class="fa-solid fa-user"></i>

            Profil

          </a>

          <a
            href="../auth/logout.php"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">

            Logout

          </a>

        </div>

      </div>

    </div>

  </nav>

  <!-- ==========================
HERO
========================== -->

  <section class="max-w-7xl mx-auto px-6 py-10">

    <div
      class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl text-white p-10 shadow-xl">

      <h1 class="text-4xl font-bold">

        Selamat Datang 👋

      </h1>

      <p class="mt-4 text-blue-100 text-lg">

        Halo

        <b>

          <?= htmlspecialchars($fullname) ?>

        </b>

        Selamat datang di sistem PsychoTest.

        Silakan pilih tes yang ingin Anda kerjakan.

      </p>

    </div>

  </section>

  <!-- ==========================
DAFTAR TES
========================== -->

  <section class="max-w-7xl mx-auto px-6 pb-10">

    <h2 class="text-3xl font-bold mb-8">

      Daftar Tes

    </h2>

    <div class="grid lg:grid-cols-2 gap-8">

      <?php if (mysqli_num_rows($query) > 0): ?>

        <?php while ($test = mysqli_fetch_assoc($query)): ?>


          <div class="bg-white rounded-3xl shadow-lg p-8 hover:shadow-xl transition">


            <!-- HEADER CARD -->

            <div class="flex justify-between items-start mb-5">

              <div>

                <h3 class="text-2xl font-bold text-gray-800">

                  <?= htmlspecialchars($test['title']) ?>

                </h3>

                <p class="text-gray-500 mt-2">

                  <?= htmlspecialchars($test['description']) ?>

                </p>

              </div>


              <div>

                <?php if (!$test['test_status']): ?>

                  <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">

                    Belum Dikerjakan

                  </span>


                <?php elseif ($test['test_status'] == "in_progress"): ?>

                  <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">

                    Sedang Tes

                  </span>


                <?php elseif ($test['payment_status'] == "verified"): ?>

                  <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                    Selesai

                  </span>


                <?php else: ?>

                  <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">

                    Menunggu Pembayaran

                  </span>

                <?php endif; ?>

              </div>

            </div>



            <!-- DETAIL -->

            <div class="grid grid-cols-3 gap-4 mb-8">


              <div class="bg-blue-50 rounded-xl p-4 text-center">

                <i class="fa-solid fa-clock text-blue-600 text-xl"></i>

                <p class="text-sm text-gray-500 mt-2">

                  Durasi

                </p>

                <p class="font-bold">

                  <?= $test['duration'] ?> Menit

                </p>

              </div>



              <div class="bg-green-50 rounded-xl p-4 text-center">

                <i class="fa-solid fa-file-lines text-green-600 text-xl"></i>

                <p class="text-sm text-gray-500 mt-2">

                  Soal

                </p>

                <p class="font-bold">

                  <?= $test['total_questions'] ?>

                </p>

              </div>



              <div class="bg-orange-50 rounded-xl p-4 text-center">

                <i class="fa-solid fa-money-bill text-orange-600 text-xl"></i>

                <p class="text-sm text-gray-500 mt-2">

                  Harga

                </p>

                <p class="font-bold">

                  Rp <?= number_format($test['price'], 0, ",", ".") ?>

                </p>

              </div>


            </div>



            <!-- BUTTON -->

            <div class="flex justify-end">


              <?php if (!$test['test_status']): ?>


                <!-- BELUM MULAI -->

                <a href="start.php?id=<?= $test['id'] ?>"

                  class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold">

                  <i class="fa-solid fa-play mr-2"></i>

                  Mulai Tes

                </a>



              <?php elseif ($test['test_status'] == "in_progress"): ?>


                <!-- LANJUT TES -->

                <a href="test.php?user_test_id=<?= $test['user_test_id'] ?>"

                  class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold">

                  <i class="fa-solid fa-arrow-right mr-2"></i>

                  Lanjutkan Tes

                </a>



              <?php elseif ($test['payment_status'] != "verified"): ?>


                <!-- PEMBAYARAN -->

                <a href="payment.php?id=<?= $test['user_test_id'] ?>"

                  class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-3 rounded-xl font-semibold">

                  <i class="fa-solid fa-wallet mr-2"></i>

                  Bayar Sekarang

                </a>



              <?php else: ?>


                <!-- HASIL -->

                <a href="result.php?id=<?= $test['user_test_id'] ?>"

                  class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold">

                  <i class="fa-solid fa-chart-line mr-2"></i>

                  Lihat Hasil

                </a>


              <?php endif; ?>


            </div>


          </div>


        <?php endwhile; ?>


      <?php else: ?>


        <div class="col-span-2 bg-white rounded-2xl p-10 text-center shadow">

          <i class="fa-solid fa-folder-open text-5xl text-gray-400"></i>

          <h3 class="text-xl font-bold mt-4">

            Belum ada tes tersedia

          </h3>

          <p class="text-gray-500">

            Silakan tunggu admin menambahkan tes baru.

          </p>

        </div>


      <?php endif; ?>


    </div>

  </section>


  <!-- FOOTER -->

  <footer class="bg-white border-t mt-10">

    <div class="max-w-7xl mx-auto px-6 py-6 text-center text-gray-500">

      © <?= date('Y') ?> PsychoTest. All Rights Reserved.

    </div>

  </footer>


</body>

</html>
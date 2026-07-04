<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<section class="min-h-screen bg-gray-100 flex items-center justify-center py-16">

  <div class="bg-white shadow-xl rounded-2xl overflow-hidden max-w-5xl w-full grid md:grid-cols-2">

    <!-- Kiri -->
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-10 flex flex-col justify-center">

      <h1 class="text-4xl font-bold">
        Selamat Datang 👋
      </h1>

      <p class="mt-5 text-blue-100 leading-8">
        Masuk ke akun PsychoTest untuk mengerjakan psikotes,
        melihat status pembayaran, dan mengakses hasil tes.
      </p>

      <img src="../assets/images/login.png"
        class="mt-10 w-72 mx-auto">

    </div>

    <!-- Kanan -->
    <div class="p-10">

      <h2 class="text-3xl font-bold text-gray-800">
        Login
      </h2>

      <p class="text-gray-500 mt-2">
        Silakan masuk menggunakan akun Anda.
      </p>

      <form action="#" method="POST" class="mt-8">

        <div class="mb-5">
          <label class="font-medium">Email</label>

          <input
            type="email"
            name="email"
            class="w-full mt-2 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="email@example.com">
        </div>

        <div class="mb-6">
          <label class="font-medium">Password</label>

          <input
            type="password"
            name="password"
            class="w-full mt-2 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="********">
        </div>

        <button
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold">

          Login

        </button>

      </form>

      <p class="text-center mt-6 text-gray-600">

        Belum punya akun?

        <a href="register.php"
          class="text-blue-600 font-semibold">

          Daftar

        </a>

      </p>

    </div>

  </div>

</section>

<?php include '../includes/footer.php'; ?>
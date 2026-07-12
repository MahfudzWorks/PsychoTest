<?php
session_start();

if (isset($_SESSION['login'])) {
  header("Location: ../index.php");
  exit;
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<!-- Menggunakan jarak yang diminta -->
<div class="min-h-[80vh] flex flex-col justify-center items-center px-4 pt-4 lg:pt-8">
  <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-gray-100 transition-all duration-300">

    <div class="text-center mb-8">
      <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
        Daftar Akun
      </h2>
      <p class="text-sm text-gray-500 mt-2">
        Lengkapi data berikut untuk membuat akun baru
      </p>
    </div>

    <?php
    if (isset($_SESSION['error'])) {
      echo "<div class='bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-xl mb-6 flex items-center gap-2'>" . $_SESSION['error'] . "</div>";
      unset($_SESSION['error']);
    }

    if (isset($_SESSION['success'])) {
      echo "<div class='bg-green-50 border border-green-200 text-green-600 text-sm p-4 rounded-xl mb-6 flex items-center gap-2'>" . $_SESSION['success'] . "</div>";
      unset($_SESSION['success']);
    }
    ?>

    <form action="register_process.php" method="POST" class="space-y-5">

      <div>
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Nama Lengkap</label>
        <input
          type="text"
          name="fullname"
          placeholder="Masukkan nama lengkap"
          required
          class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-700 bg-gray-50 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all duration-200">
      </div>

      <div>
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Email</label>
        <input
          type="email"
          name="email"
          placeholder="nama@email.com"
          required
          class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-700 bg-gray-50 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all duration-200">
      </div>

      <div>
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Password</label>
        <input
          type="password"
          name="password"
          placeholder="Buat kata sandi"
          required
          class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-700 bg-gray-50 placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all duration-200">
      </div>

      <div class="pt-2">
        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-medium w-full py-3 px-4 rounded-xl shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform active:scale-[0.98]">
          Daftar Sekarang
        </button>
      </div>

    </form>

    <div class="mt-8 text-center border-t border-gray-100 pt-6">
      <p class="text-sm text-gray-600">
        Sudah punya akun?
        <a href="login.php" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors duration-200">
          Masuk di sini
        </a>
      </p>
    </div>

  </div>
</div>

<?php include '../includes/footer.php'; ?>
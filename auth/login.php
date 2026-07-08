<?php
session_start();

if (isset($_SESSION['login'])) {
  header("Location: ../index.php");
  exit;
}

include '../includes/header.php';
?>

<div class="max-w-md mx-auto mt-10 bg-white p-8 rounded-xl shadow">

  <h2 class="text-2xl font-bold mb-6">
    Login
  </h2>

  <?php

  if (isset($_SESSION['error'])) {
    echo "<div class='bg-red-100 text-red-600 p-3 rounded mb-4'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
  }

  if (isset($_SESSION['success'])) {
    echo "<div class='bg-green-100 text-green-600 p-3 rounded mb-4'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
  }

  ?>

  <form action="login_process.php" method="POST">

    <input
      type="email"
      name="email"
      required
      placeholder="Email"
      class="w-full border p-3 rounded mb-4">

    <input
      type="password"
      name="password"
      required
      placeholder="Password"
      class="w-full border p-3 rounded mb-4">

    <button
      class="bg-blue-600 text-white w-full py-3 rounded">

      Login

    </button>

  </form>

</div>

<?php include '../includes/footer.php'; ?>
<nav class="fixed top-0 left-64 right-0 h-16 bg-white shadow-sm border-b border-gray-200 z-40">

  <div class="flex items-center justify-between h-full px-8">

    <!-- Left -->
    <div class="flex items-center gap-5">

      <h2 class="text-2xl font-bold text-gray-800">
        Dashboard
      </h2>

      <div class="relative">

        <input type="text"
          placeholder="Cari..."
          class="w-80 pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none">

        <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400"></i>

      </div>

    </div>

    <!-- Right -->
    <div class="flex items-center gap-6">

      <!-- Notification -->
      <button
        class="relative w-10 h-10 rounded-full hover:bg-gray-100 transition flex items-center justify-center">

        <i class="fa-regular fa-bell text-xl text-gray-600"></i>

        <span
          class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
          3
        </span>

      </button>

      <!-- Message -->
      <button
        class="relative w-10 h-10 rounded-full hover:bg-gray-100 transition flex items-center justify-center">

        <i class="fa-regular fa-envelope text-xl text-gray-600"></i>

        <span
          class="absolute -top-1 -right-1 bg-blue-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
          5
        </span>

      </button>

      <!-- Profile -->
      <div class="relative group cursor-pointer">

        <div class="flex items-center gap-3">

          <img src="../assets/images/default.png"
            class="w-11 h-11 rounded-full border object-cover">

          <div class="hidden md:block">

            <h4 class="font-semibold text-gray-700">
              <?= $_SESSION['fullname'] ?? "Administrator"; ?>
            </h4>

            <small class="text-gray-500">
              Administrator
            </small>

          </div>

          <i class="fa-solid fa-chevron-down text-gray-500"></i>

        </div>

        <!-- Dropdown -->

        <div
          class="absolute right-0 mt-4 w-52 bg-white rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 overflow-hidden">

          <a href="profile.php"
            class="flex items-center gap-3 px-5 py-3 hover:bg-gray-100">

            <i class="fa-regular fa-user text-blue-600"></i>

            Profile

          </a>

          <a href="#"
            class="flex items-center gap-3 px-5 py-3 hover:bg-gray-100">

            <i class="fa-solid fa-gear text-green-600"></i>

            Settings

          </a>

          <hr>

          <a href="../auth/logout.php"
            class="flex items-center gap-3 px-5 py-3 hover:bg-red-50 text-red-600">

            <i class="fa-solid fa-right-from-bracket"></i>

            Logout

          </a>

        </div>

      </div>

    </div>

  </div>

</nav>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<section class="bg-blue-600 text-white py-20">

  <div class="max-w-7xl mx-auto px-6 text-center">

    <h1 class="text-5xl font-bold">
      Hubungi Kami
    </h1>

    <p class="mt-5 text-xl text-blue-100">
      Kami siap membantu jika Anda memiliki pertanyaan atau kendala.
    </p>

  </div>

</section>

<section class="py-20">

  <div class="max-w-6xl mx-auto px-6">

    <div class="grid md:grid-cols-2 gap-10">

      <div>

        <h2 class="text-3xl font-bold mb-8">
          Kirim Pesan
        </h2>

        <form>

          <div class="mb-5">
            <label>Nama</label>
            <input type="text"
              class="w-full mt-2 border rounded-lg p-3">
          </div>

          <div class="mb-5">
            <label>Email</label>
            <input type="email"
              class="w-full mt-2 border rounded-lg p-3">
          </div>

          <div class="mb-5">
            <label>Pesan</label>
            <textarea rows="5"
              class="w-full mt-2 border rounded-lg p-3"></textarea>
          </div>

          <button
            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            Kirim Pesan
          </button>

        </form>

      </div>

      <div>

        <div class="bg-white rounded-xl shadow p-8">

          <h2 class="text-3xl font-bold">
            Informasi Kontak
          </h2>

          <div class="mt-8 space-y-5">

            <p>
              <i class="fa-solid fa-envelope text-blue-600 mr-3"></i>

              support@psychotest.com
            </p>

            <p>
              <i class="fa-solid fa-phone text-blue-600 mr-3"></i>

              +62 812-3456-7890
            </p>

            <p>
              <i class="fa-solid fa-location-dot text-blue-600 mr-3"></i>

              Indonesia
            </p>

          </div>

        </div>

        <div class="mt-8">

          <iframe
            class="w-full rounded-xl h-72"
            src="https://maps.google.com/maps?q=Indonesia&t=&z=5&ie=UTF8&iwloc=&output=embed">
          </iframe>

        </div>

      </div>

    </div>

  </div>

</section>

<?php include 'includes/footer.php'; ?>
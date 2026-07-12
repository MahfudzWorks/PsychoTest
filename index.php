<?php
session_start();
require "config/database.php";
?>

<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />

<?php include 'includes/navbar.php'; ?>

<!-- HERO -->
<section class="relative bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-900 text-white overflow-hidden py-20 lg:py-32">
  <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">

      <div class="lg:col-span-5 text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
        <span class="inline-flex items-center gap-1.5 bg-indigo-500/20 text-indigo-300 text-xs px-3 py-1.5 rounded-full font-medium tracking-wide uppercase mb-6 border border-indigo-500/30">
          <i class="fa-solid fa-sparkles text-amber-400"></i> Platform Tes Terstandarisasi
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-none bg-gradient-to-r from-white via-slate-100 to-indigo-200 bg-clip-text text-transparent">
          PsychoTest
        </h1>
        <p class="mt-4 text-lg sm:text-xl text-indigo-200/80 font-medium">
          Platform Asesmen Psikologi Online
        </p>
        <p class="mt-6 text-base sm:text-lg text-slate-300 leading-relaxed max-w-xl mx-auto lg:mx-0">
          Ukur potensi, kecerdasan, dan kepribadian Anda secara akurat dengan sistem tes online yang cepat, aman, dan terpercaya. Mendukung penuh kebutuhan individu maupun rekrutmen perusahaan.
        </p>
        <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
          <a href="auth/login.php" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-4 rounded-xl font-bold shadow-lg shadow-indigo-600/30 transition-all duration-200 transform hover:-translate-y-0.5">
            Mulai Tes Sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
          <a href="#daftar-tes" class="inline-flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 text-white border border-white/10 px-8 py-4 rounded-xl font-bold backdrop-blur-sm transition-all duration-200">
            Lihat Jenis Tes
          </a>
        </div>
      </div>

      <div class="lg:col-span-7" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
        <div class="relative mx-auto max-w-lg lg:max-w-none bg-slate-950/40 border border-slate-700/50 rounded-2xl p-4 shadow-2xl shadow-indigo-950/50 backdrop-blur-md">
          <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-4">
            <div class="flex gap-1.5">
              <span class="w-3 h-3 bg-rose-500 rounded-full inline-block"></span>
              <span class="w-3 h-3 bg-amber-500 rounded-full inline-block"></span>
              <span class="w-3 h-3 bg-emerald-500 rounded-full inline-block"></span>
            </div>
            <span class="text-[10px] text-slate-500 font-mono select-none">psychotest-dashboard.app</span>
            <div class="w-10"></div>
          </div>

          <div class="bg-slate-900 rounded-xl border border-slate-800 p-4 text-left">
            <div class="flex items-center justify-between mb-4">
              <div>
                <p class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">Selamat Datang</p>
                <h4 class="text-sm font-bold text-white">Peserta Tes Psikologi</h4>
              </div>
              <span class="bg-indigo-500/10 text-indigo-400 text-[10px] px-2.5 py-1 rounded-md border border-indigo-500/20">Sesi Aktif</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div class="bg-slate-950/60 border border-slate-800 rounded-xl p-3">
                <div class="flex justify-between items-start mb-2">
                  <span class="text-[9px] bg-amber-500/10 text-amber-400 px-2 py-0.5 rounded border border-amber-500/20 font-medium">Logika & Numerik</span>
                  <i class="fa-solid fa-clock text-slate-500 text-xs"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-200">Tes Penalaran Analitis</h5>
                <div class="mt-3 pt-2 border-t border-slate-800 flex justify-between items-center text-[10px] text-slate-400">
                  <span>30 Soal</span>
                  <span class="text-indigo-400 font-medium">Mulai Tes <i class="fa-solid fa-chevron-right text-[8px] ml-0.5"></i></span>
                </div>
              </div>
              <div class="bg-slate-950/60 border border-slate-800 rounded-xl p-3">
                <div class="flex justify-between items-start mb-2">
                  <span class="text-[9px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded border border-emerald-500/20 font-medium">Kepribadian</span>
                  <i class="fa-solid fa-circle-check text-emerald-400 text-xs"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-200">EPPS (Tes Kepribadian)</h5>
                <div class="mt-3 pt-2 border-t border-slate-800 flex justify-between items-center text-[10px] text-slate-400">
                  <span>60 Soal</span>
                  <span class="text-emerald-400 font-medium"><i class="fa-solid fa-check mr-0.5"></i> Selesai</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- DAFTAR TES -->
<section id="daftar-tes" class="py-24 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center max-w-3xl mx-auto" data-aos="fade-up" data-aos-duration="1000">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
        Jenis Tes yang Tersedia
      </h2>
      <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
        Pilih tes yang sesuai dengan kebutuhan Anda. Setiap tes telah disusun oleh tenaga ahli psikologi.
      </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mt-16">
      <?php
      $query_tes = mysqli_query($conn, "SELECT * FROM tests WHERE status = 'active' ORDER BY created_at DESC");

      if ($query_tes && mysqli_num_rows($query_tes) > 0):
        while ($tes = mysqli_fetch_assoc($query_tes)):
      ?>
          <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <span class="bg-indigo-50 text-indigo-600 text-xs px-3 py-1 rounded-full font-medium">Kategori Tes</span>
                <span class="text-lg font-bold <?= $tes['price'] == 0 ? 'text-emerald-600' : 'text-slate-900' ?>">
                  <?= $tes['price'] == 0 ? 'Gratis' : 'Rp ' . number_format($tes['price'], 0, ',', '.') ?>
                </span>
              </div>
              <h3 class="text-xl font-bold text-slate-900 mb-3"><?= $tes['title'] ?></h3>
              <p class="text-slate-600 text-sm leading-relaxed mb-4">
                <?= $tes['description'] ?>
              </p>
              <div class="flex items-center justify-between text-sm text-slate-500 border-t border-slate-100 pt-4">
                <span><i class="fa-solid fa-clock mr-1"></i> <?= $tes['duration'] ?> Menit</span>
                <span><i class="fa-solid fa-list-ol mr-1"></i> <?= $tes['total_questions'] ?> Soal</span>
              </div>
            </div>
            <div class="bg-slate-50 px-6 py-3 border-t border-slate-100">
              <a href="auth/login.php" class="block text-center text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">
                Mulai Tes <i class="fa-solid fa-arrow-right ml-1"></i>
              </a>
            </div>
          </div>
        <?php
        endwhile;
      else:
        ?>
        <div class="col-span-full text-center py-12 text-slate-500">
          <i class="fa-solid fa-file-circle-question text-4xl mb-4"></i>
          <p>Belum ada tes yang tersedia saat ini.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- FITUR -->
<section id="fitur" class="py-24 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center max-w-3xl mx-auto" data-aos="fade-up" data-aos-duration="1000">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
        Kenapa Memilih Platform PsychoTest?
      </h2>
      <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
        Kami menyediakan layanan asesmen psikologi yang profesional, mudah diakses, dan terpercaya.
      </p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 mt-16">
      <div class="bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-8" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl shadow-sm mb-6">
          <i class="fa-solid fa-brain"></i>
        </div>
        <h3 class="font-bold text-xl text-slate-900">Validitas Tes Terjamin</h3>
        <p class="text-slate-500 mt-3 text-sm sm:text-base leading-relaxed">
          Soal tes disusun sesuai standar psikologi untuk mengukur aspek kognitif, kepribadian, dan bakat secara akurat.
        </p>
      </div>
      <div class="bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-8" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl shadow-sm mb-6">
          <i class="fa-solid fa-stopwatch"></i>
        </div>
        <h3 class="font-bold text-xl text-slate-900">Waktu Terkendali</h3>
        <p class="text-slate-500 mt-3 text-sm sm:text-base leading-relaxed">
          Sistem menghitung waktu secara otomatis. Tes akan berhenti saat waktu habis untuk menjaga keadilan.
        </p>
      </div>
      <div class="bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-8" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl shadow-sm mb-6">
          <i class="fa-solid fa-chart-pie"></i>
        </div>
        <h3 class="font-bold text-xl text-slate-900">Hasil Langsung</h3>
        <p class="text-slate-500 mt-3 text-sm sm:text-base leading-relaxed">
          Setelah pembayaran dikonfirmasi, skor dan laporan hasil tes dapat langsung dilihat dan diunduh.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ALUR PENGGUNAAN -->
<section class="py-24 bg-white border-t border-slate-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up" data-aos-duration="1000">
      <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Alur Penggunaan Layanan</h2>
      <p class="text-slate-500 mt-2 text-sm sm:text-base">Ikuti 4 langkah mudah ini untuk mendapatkan hasil tes Anda.</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 relative">
      <div class="text-center group" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
        <div class="w-14 h-14 rounded-full bg-slate-50 border-2 border-slate-200 group-hover:border-indigo-600 group-hover:bg-indigo-50 flex items-center justify-center text-slate-700 group-hover:text-indigo-600 font-bold text-lg mx-auto transition-all duration-300">
          01
        </div>
        <h4 class="font-bold text-slate-900 mt-4 text-base">Registrasi Akun</h4>
        <p class="text-xs text-slate-400 mt-2 px-4">Buat akun baru dengan mengisi data diri yang lengkap dan valid untuk mengamankan data tes Anda.</p>
      </div>

      <div class="text-center group" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
        <div class="w-14 h-14 rounded-full bg-slate-50 border-2 border-slate-200 group-hover:border-indigo-600 group-hover:bg-indigo-50 flex items-center justify-center text-slate-700 group-hover:text-indigo-600 font-bold text-lg mx-auto transition-all duration-300">
          02
        </div>
        <h4 class="font-bold text-slate-900 mt-4 text-base">Pilih dan Kerjakan Tes</h4>
        <p class="text-xs text-slate-400 mt-2 px-4">Pilih jenis tes yang dibutuhkan, baca petunjuk pengerjaan, lalu kerjakan semua soal hingga selesai.</p>
      </div>

      <div class="text-center group" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
        <div class="w-14 h-14 rounded-full bg-slate-50 border-2 border-slate-200 group-hover:border-indigo-600 group-hover:bg-indigo-50 flex items-center justify-center text-slate-700 group-hover:text-indigo-600 font-bold text-lg mx-auto transition-all duration-300">
          03
        </div>
        <h4 class="font-bold text-slate-900 mt-4 text-base">Lakukan Pembayaran QRIS</h4>
        <p class="text-xs text-slate-400 mt-2 px-4">Setelah selesai mengerjakan, bayar biaya akses laporan hasil tes dengan cara memindai kode QRIS yang tersedia.</p>
      </div>

      <div class="text-center group" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
        <div class="w-14 h-14 rounded-full bg-slate-50 border-2 border-slate-200 group-hover:border-indigo-600 group-hover:bg-indigo-50 flex items-center justify-center text-slate-700 group-hover:text-indigo-600 font-bold text-lg mx-auto transition-all duration-300">
          04
        </div>
        <h4 class="font-bold text-slate-900 mt-4 text-base">Lihat Hasil Tes</h4>
        <p class="text-xs text-slate-400 mt-2 px-4">Setelah pembayaran terverifikasi, Anda dapat langsung melihat skor, grafik analisis, dan laporan lengkap hasil tes.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="bg-gradient-to-br from-indigo-700 via-indigo-800 to-blue-900 text-white py-20 relative overflow-hidden">
  <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:20px_20px]"></div>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" data-aos="zoom-in" data-aos-duration="800">
    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
      Siap Mengetahui Potensi Diri Anda?
    </h2>
    <p class="mt-4 text-base sm:text-lg text-indigo-100 max-w-xl mx-auto">
      Bergabunglah dengan ribuan pengguna lainnya dan dapatkan wawasan mendalam mengenai kepribadian serta kemampuan Anda.
    </p>
    <div class="mt-8">
      <a href="auth/register.php" class="inline-flex items-center justify-center bg-white hover:bg-slate-50 text-indigo-700 px-8 py-4 rounded-xl font-bold shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
        Daftar Akun Sekarang
      </a>
    </div>
  </div>
</section>

<!-- PESAN MASUKAN DARI PENGGUNA -->
<section class="py-24 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up" data-aos-duration="1000">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Apa Kata Mereka?</h2>
      <p class="mt-4 text-lg text-slate-600">Pendapat dan masukan dari pengguna yang telah menggunakan layanan kami</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php
      $query_pesan = mysqli_query($conn, "SELECT * FROM messages ORDER BY created_at DESC LIMIT 6");
      if ($query_pesan && mysqli_num_rows($query_pesan) > 0):
        while ($pesan = mysqli_fetch_assoc($query_pesan)):
      ?>
          <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
            <div class="flex items-center gap-4 mb-4">
              <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xl font-bold">
                <?= strtoupper(substr($pesan['sender_name'], 0, 1)) ?>
              </div>
              <div>
                <h4 class="font-semibold text-slate-900"><?= $pesan['sender_name'] ?></h4>
                <p class="text-xs text-slate-500"><?= date('d M Y', strtotime($pesan['created_at'])) ?></p>
              </div>
            </div>
            <h5 class="font-medium text-slate-800 mb-2"><?= $pesan['subject'] ?></h5>
            <p class="text-slate-600 text-sm leading-relaxed">
              <?= substr($pesan['message_text'], 0, 150) . (strlen($pesan['message_text']) > 150 ? '...' : '') ?>
            </p>
          </div>
        <?php
        endwhile;
      else:
        ?>
        <div class="col-span-full text-center py-10 text-slate-500">
          <i class="fa-solid fa-comments text-4xl mb-3"></i>
          <p>Belum ada masukan dari pengguna saat ini.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init({
    once: true,
    offset: 120
  });
</script>
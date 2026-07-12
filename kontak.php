<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />

<?php include 'includes/navbar.php'; ?>

<!-- HERO HUBUNGI KAMI -->
<section class="relative bg-white text-slate-950 py-24 sm:py-32 overflow-hidden border-b border-slate-100">
  <!-- Modern Geometric Background Grid -->
  <div class="absolute inset-0 bg-[linear-gradient(to_right,#f0f4f8_1px,transparent_1px),linear-gradient(to_bottom,#f0f4f8_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>

  <div class="max-w-7xl mx-auto px-6 text-center relative z-10" data-aos="fade-up">
    <!-- Modern Badge -->
    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold tracking-wide uppercase text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-full mb-6">
      <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span> Kontak Bantuan
    </span>

    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 leading-[1.15]">
      Ada Pertanyaan? <span class="bg-gradient-to-r from-indigo-600 via-violet-600 to-blue-600 bg-clip-text text-transparent">Hubungi Kami</span>
    </h1>
    <p class="mt-6 text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto font-medium leading-relaxed">
      Tim support kami siap membantu Anda menyelesaikan kendala integrasi maupun pertanyaan seputar modul psikotes.
    </p>
  </div>
</section>

<!-- MAIN CONTENT SECTION -->
<section class="py-24 bg-gradient-to-b from-white to-slate-50/50">
  <div class="max-w-6xl mx-auto px-6">
    <div class="grid md:grid-cols-2 gap-16 items-start">

      <!-- FORM KIRIM PESAN -->
      <div data-aos="fade-right" data-aos-delay="100">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
          Kirim Pesan Langsung
        </h2>
        <p class="text-slate-500 mb-8">Isi formulir di bawah ini dan tim kami akan segera membalas email Anda.</p>

        <form class="space-y-6">
          <div>
            <label class="text-sm font-semibold text-slate-700 tracking-wide block">Nama Lengkap</label>
            <input type="text" placeholder="Masukkan nama Anda"
              class="w-full mt-2 border border-slate-200 rounded-xl p-3.5 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
          </div>

          <div>
            <label class="text-sm font-semibold text-slate-700 tracking-wide block">Alamat Email</label>
            <input type="email" placeholder="nama@perusahaan.com"
              class="w-full mt-2 border border-slate-200 rounded-xl p-3.5 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
          </div>

          <div>
            <label class="text-sm font-semibold text-slate-700 tracking-wide block">Detail Pesan</label>
            <textarea rows="5" placeholder="Tuliskan kendala atau pertanyaan Anda di sini..."
              class="w-full mt-2 border border-slate-200 rounded-xl p-3.5 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"></textarea>
          </div>

          <button
            class="w-full sm:w-auto bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold px-8 py-3.5 rounded-xl hover:opacity-95 transform active:scale-[0.98] transition-all duration-200 shadow-sm shadow-indigo-200">
            Kirim Pesan
          </button>
        </form>
      </div>

      <!-- INFORMASI KONTAK & PETA -->
      <div class="space-y-8" data-aos="fade-left" data-aos-delay="200">
        <!-- Kartu Kontak -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 sm:p-10">
          <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
            Informasi Kontak
          </h2>
          <p class="text-slate-500 text-sm mt-1 mb-8">Hubungi kami melalui saluran resmi berikut.</p>

          <div class="space-y-6">
            <div class="flex items-center gap-4 group">
              <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center transition-colors group-hover:bg-indigo-100">
                <i class="fa-solid fa-envelope text-base"></i>
              </div>
              <div>
                <span class="text-xs font-medium text-slate-400 block uppercase tracking-wider">Email Support</span>
                <span class="text-slate-700 font-medium">support@psychotest.com</span>
              </div>
            </div>

            <div class="flex items-center gap-4 group">
              <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center transition-colors group-hover:bg-blue-100">
                <i class="fa-solid fa-phone text-base"></i>
              </div>
              <div>
                <span class="text-xs font-medium text-slate-400 block uppercase tracking-wider">Hotline</span>
                <span class="text-slate-700 font-medium">+62 812-3456-7890</span>
              </div>
            </div>

            <div class="flex items-start gap-4 group">
              <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center transition-colors group-hover:bg-emerald-100 flex-shrink-0 mt-0.5">
                <i class="fa-solid fa-location-dot text-base"></i>
              </div>
              <div>
                <span class="text-xs font-medium text-slate-400 block uppercase tracking-wider">Alamat Kami</span>
                <span class="text-slate-700 font-medium block leading-relaxed">
                  Balongmojo Kulon RT 2 RW 1, Desa Balongmojo, <br>
                  Kecamatan Benjeng, Kabupaten Gresik, <br>
                  Jawa Timur
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Peta Embed dengan lokasi Benjeng, Gresik -->
        <div class="relative group">
          <div class="absolute -inset-1.5 bg-gradient-to-r from-slate-200 to-slate-100 rounded-2xl opacity-40 blur-md"></div>
          <div class="relative border border-slate-200/60 rounded-xl overflow-hidden shadow-sm bg-white p-1.5">
            <iframe
              class="w-full rounded-lg h-64 filter grayscale contrast-125 opacity-90 hover:grayscale-0 transition-all duration-500"
              src="https://maps.google.com/maps?q=Balongmojo,%20Benjeng,%20Gresik&t=&z=14&ie=UTF8&iwloc=&output=embed"
              allowfullscreen="" loading="lazy">
            </iframe>
          </div>
        </div>

      </div>

    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init({
    once: true,
    offset: 40,
    duration: 700,
    easing: 'ease-out-cubic'
  });
</script>
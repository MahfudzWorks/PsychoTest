-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 19, 2026 at 10:21 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `psychotest`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `sender_email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message_text` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0' COMMENT '0 = Belum dibaca, 1 = Sudah dibaca',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `type` varchar(50) NOT NULL COMMENT 'jenis: new_user, new_payment, new_test, payment_verified, test_completed',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `related_id` int DEFAULT NULL COMMENT 'ID terkait: user_id, test_id, user_test_id',
  `is_read` tinyint(1) DEFAULT '0' COMMENT '0 = belum dibaca, 1 = sudah dibaca',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int NOT NULL,
  `test_id` int NOT NULL,
  `question` text NOT NULL,
  `option_a` text,
  `option_b` text,
  `option_c` text,
  `option_d` text,
  `option_e` text,
  `answer` char(1) DEFAULT NULL COMMENT 'Jawaban benar: a/b/c/d/e',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `test_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `answer`, `created_at`) VALUES
(1, 1, 'Sinonim dari kata \"Efektif\" adalah...', 'Cepat', 'Tepat guna', 'Mahal', 'Rumit', 'Lambat', 'b', '2026-07-11 00:09:57'),
(2, 1, 'Lawan kata dari \"Ekspansi\" adalah...', 'Penyempitan', 'Perluasan', 'Pertumbuhan', 'Pengembangan', 'Penyebaran', 'a', '2026-07-11 00:09:57'),
(3, 1, 'Persamaan makna kata \"Mandiri\" adalah...', 'Bergantung', 'Dapat berdiri sendiri', 'Bekerja sama', 'Dibantu orang lain', 'Mengandalkan orang', 'b', '2026-07-11 00:09:57'),
(4, 1, 'Lawan kata dari \"Konsisten\" adalah...', 'Tetap', 'Berubah-ubah', 'Selalu sama', 'Teratur', 'Terus-menerus', 'b', '2026-07-11 00:09:57'),
(5, 1, 'Sinonim dari kata \"Signifikan\" adalah...', 'Tidak penting', 'Berarti', 'Kecil', 'Sama rata', 'Biasa saja', 'b', '2026-07-11 00:09:57'),
(6, 1, 'Lawan kata dari \"Optimis\" adalah...', 'Percaya diri', 'Pesimis', 'Semangat', 'Yakin', 'Harapan', 'b', '2026-07-11 00:09:57'),
(7, 1, 'Persamaan makna kata \"Akurat\" adalah...', 'Tepat', 'Salah', 'Kurang', 'Kasar', 'Abstrak', 'a', '2026-07-11 00:09:57'),
(8, 1, 'Lawan kata dari \"Stabil\" adalah...', 'Tetap', 'Berubah', 'Kokoh', 'Teratur', 'Kuat', 'b', '2026-07-11 00:09:57'),
(9, 1, 'Sinonim dari kata \"Kritis\" adalah...', 'Mengamati dengan teliti', 'Menyalahkan terus', 'Membenci', 'Mengabaikan', 'Menyetujui', 'a', '2026-07-11 00:09:57'),
(10, 1, 'Lawan kata dari \"Kuantitas\" adalah...', 'Banyak', 'Sedikit', 'Kualitas', 'Jumlah', 'Ukuran', 'c', '2026-07-11 00:09:57'),
(11, 1, 'Manakah susunan kata yang paling tepat? 1. Pagi 2. Berangkat 3. Sekolah 4. Saya', '1-2-3-4', '4-1-2-3', '4-2-1-3', '2-1-4-3', '3-2-1-4', 'b', '2026-07-11 00:09:57'),
(12, 1, 'Jika \"Kucing : Meong\", maka \"Anjing : ...\"', 'Mengeong', 'Menggonggong', 'Mencicit', 'Mengaum', 'Mendesah', 'b', '2026-07-11 00:09:57'),
(13, 1, 'Jika \"Buku : Membaca\", maka \"Pisau : ...\"', 'Memotong', 'Menulis', 'Menggambar', 'Menyulam', 'Menjahit', 'a', '2026-07-11 00:09:57'),
(14, 1, 'Manakah kata yang tidak termasuk dalam kelompok berikut? Meja, Kursi, Lemari, Sendok, Rak', 'Meja', 'Kursi', 'Sendok', 'Lemari', 'Rak', 'c', '2026-07-11 00:09:57'),
(15, 1, 'Manakah kata yang tidak termasuk dalam kelompok berikut? Merah, Kuning, Hijau, Awan, Biru', 'Merah', 'Awan', 'Hijau', 'Kuning', 'Biru', 'b', '2026-07-11 00:09:57'),
(16, 1, 'Berapakah hasil dari 125 + 75 - 50?', '100', '150', '200', '250', '300', 'b', '2026-07-11 00:09:57'),
(17, 1, 'Jika 3 × 8 = 24, maka 24 ÷ 6 = ...', '2', '3', '4', '5', '6', 'c', '2026-07-11 00:09:57'),
(18, 1, 'Urutan bilangan berikut: 2, 5, 8, 11, ... Berapakah bilangan selanjutnya?', '12', '13', '14', '15', '16', 'c', '2026-07-11 00:09:57'),
(19, 1, 'Berapakah 25% dari 200?', '25', '40', '50', '75', '100', 'c', '2026-07-11 00:09:57'),
(20, 1, 'Jika harga sebuah buku Rp12.000 dan mendapat diskon 10%, maka harga yang harus dibayar adalah...', 'Rp9.600', 'Rp10.000', 'Rp10.800', 'Rp11.000', 'Rp11.200', 'c', '2026-07-11 00:09:57'),
(21, 1, 'Sebuah mobil menempuh jarak 120 km dalam waktu 2 jam. Berapakah kecepatan rata-ratanya?', '40 km/jam', '50 km/jam', '55 km/jam', '60 km/jam', '70 km/jam', 'd', '2026-07-11 00:09:57'),
(22, 1, 'Berapakah hasil dari 15² - 10²?', '25', '50', '100', '125', '150', 'd', '2026-07-11 00:09:57'),
(23, 1, 'Jika a = 4 dan b = 5, maka nilai 2a + 3b = ...', '18', '20', '22', '23', '25', 'd', '2026-07-11 00:09:57'),
(24, 1, 'Urutan bilangan berikut: 2, 4, 8, 16, ... Berapakah bilangan selanjutnya?', '20', '24', '28', '32', '64', 'd', '2026-07-11 00:09:57'),
(25, 1, 'Berapakah 3/4 dari 80?', '40', '50', '55', '60', '70', 'd', '2026-07-11 00:09:57'),
(26, 1, 'Sebuah persegi panjang memiliki panjang 12 cm dan lebar 8 cm. Berapakah luasnya?', '20 cm²', '40 cm²', '80 cm²', '96 cm²', '100 cm²', 'd', '2026-07-11 00:09:57'),
(27, 1, 'Jika 5 pekerja dapat menyelesaikan pekerjaan dalam 8 hari, berapa hari yang dibutuhkan oleh 10 pekerja?', '2 hari', '3 hari', '4 hari', '5 hari', '6 hari', 'c', '2026-07-11 00:09:57'),
(28, 1, 'Berapakah hasil dari 1.250 + 750 - 500?', '1.000', '1.250', '1.500', '1.750', '2.000', 'c', '2026-07-11 00:09:57'),
(29, 1, 'Urutan bilangan berikut: 100, 90, 81, 73, ... Berapakah bilangan selanjutnya?', '65', '66', '67', '68', '69', 'b', '2026-07-11 00:09:57'),
(30, 1, 'Jika harga barang naik 20% menjadi Rp120.000, maka harga semula adalah...', 'Rp90.000', 'Rp95.000', 'Rp100.000', 'Rp105.000', 'Rp110.000', 'c', '2026-07-11 00:09:57'),
(31, 1, 'Berapakah hasil dari √144 + √81?', '12', '15', '18', '21', '25', 'd', '2026-07-11 00:09:57'),
(32, 1, 'Sebuah tabung memiliki jari-jari 7 cm dan tinggi 10 cm. Jika π = 22/7, maka volumenya adalah...', '1.440 cm³', '1.540 cm³', '1.640 cm³', '1.740 cm³', '1.840 cm³', 'b', '2026-07-11 00:09:57'),
(33, 1, 'Jika 3 buah pulpen harganya Rp15.000, maka 5 buah pulpen harganya adalah...', 'Rp20.000', 'Rp22.500', 'Rp25.000', 'Rp27.500', 'Rp30.000', 'c', '2026-07-11 00:09:57'),
(34, 1, 'Berapakah hasil dari 25 × 12 ÷ 6?', '40', '50', '55', '60', '70', 'd', '2026-07-11 00:09:57'),
(35, 1, 'Urutan bilangan berikut: 3, 6, 12, 24, ... Berapakah bilangan ke-7?', '48', '72', '96', '144', '192', 'e', '2026-07-11 00:09:57'),
(36, 1, 'Jika semua siswa rajin belajar, maka mereka akan lulus. Budi adalah siswa yang rajin belajar. Maka kesimpulannya adalah...', 'Budi pasti lulus', 'Budi mungkin lulus', 'Budi tidak lulus', 'Semua siswa lulus', 'Tidak dapat disimpulkan', 'a', '2026-07-11 00:09:57'),
(37, 1, 'Jika hari ini hujan, maka jalanan basah. Hari ini jalanan tidak basah. Maka kesimpulannya adalah...', 'Hari ini hujan', 'Hari ini tidak hujan', 'Jalanan tetap basah', 'Hari ini cerah', 'Tidak dapat disimpulkan', 'b', '2026-07-11 00:09:57'),
(38, 1, 'Semua kucing adalah hewan. Semua hewan bernapas. Maka kesimpulannya adalah...', 'Semua hewan adalah kucing', 'Semua kucing bernapas', 'Hanya kucing yang bernapas', 'Semua yang bernapas adalah kucing', 'Tidak ada jawaban benar', 'b', '2026-07-11 00:09:57'),
(39, 1, 'Manakah pernyataan yang paling logis?', 'Jika malam hari maka matahari terbit', 'Jika hujan maka tanah basah', 'Jika panas maka air membeku', 'Jika angin kencang maka pohon tumbang', 'Jika siang hari maka bulan terlihat', 'b', '2026-07-11 00:09:57'),
(40, 1, 'Jika A lebih tua dari B, dan B lebih tua dari C, maka...', 'A lebih muda dari C', 'C lebih tua dari A', 'A lebih tua dari C', 'B lebih muda dari C', 'A, B, dan C sama tua', 'c', '2026-07-11 00:09:57'),
(41, 1, 'Manakah kelompok yang memiliki hubungan yang sama dengan \"Pohon : Kayu\"?', 'Bunga : Warna', 'Batu : Berat', 'Sapi : Daging', 'Kertas : Buku', 'Air : Minum', 'c', '2026-07-11 00:09:57'),
(42, 1, 'Jika \"Makan : Kenyang\", maka \"Minum : ...\"', 'Lapar', 'Haus', 'Segar', 'Cukup', 'Kenyang', 'c', '2026-07-11 00:09:57'),
(43, 1, 'Manakah urutan peristiwa yang paling logis? 1. Menanam 2. Memanen 3. Menyiram 4. Membajak tanah', '4-1-3-2', '1-4-3-2', '4-3-1-2', '2-1-3-4', '3-1-4-2', 'a', '2026-07-11 00:09:57'),
(44, 1, 'Jika X = 2Y dan Y = 3Z, maka hubungan antara X dan Z adalah...', 'X = 3Z', 'X = 4Z', 'X = 5Z', 'X = 6Z', 'X = 9Z', 'd', '2026-07-11 00:09:57'),
(45, 1, 'Manakah pernyataan yang pasti benar?', 'Semua burung bisa terbang', 'Semua hewan bernapas', 'Semua ikan hidup di air', 'Semua tumbuhan berbuah', 'Semua manusia bisa berenang', 'b', '2026-07-11 00:09:57'),
(46, 1, 'Jika lampu menyala berarti ada arus listrik. Lampu tidak menyala. Maka kesimpulannya adalah...', 'Tidak ada arus listrik', 'Ada arus listrik tapi lampu rusak', 'Bisa jadi tidak ada arus atau lampu rusak', 'Saklar mati', 'Tidak dapat disimpulkan', 'c', '2026-07-11 00:09:57'),
(47, 1, 'Manakah kelompok yang berbeda dari yang lain?', 'Pisau', 'Gunting', 'Kapak', 'Sendok', 'Gergaji', 'd', '2026-07-11 00:09:57'),
(48, 1, 'Jika \"Pagi : Siang\", maka \"Sore : ...\"', 'Malam', 'Senja', 'Matahari terbenam', 'Tidur', 'Beristirahat', 'a', '2026-07-11 00:09:57'),
(49, 1, 'Jika 4 pekerja dapat menyelesaikan pekerjaan dalam 12 hari, maka 6 pekerja dapat menyelesaikannya dalam waktu...', '6 hari', '8 hari', '9 hari', '10 hari', '12 hari', 'b', '2026-07-11 00:12:40'),
(50, 1, 'Manakah pernyataan yang paling logis? Jika lampu menyala, maka saklar dalam keadaan...', 'Terbuka', 'Tertutup', 'Rusak', 'Lepas', 'Mati', 'b', '2026-07-11 00:12:40'),
(51, 2, 'Semboyan Negara Indonesia adalah...', 'Bhinneka Tunggal Ika', 'Indonesia Raya', 'Maju Terus Pantang Mundur', 'Negara Kesatuan Republik Indonesia', 'Pancasila', 'a', '2026-07-11 00:17:27'),
(52, 2, 'Pancasila disahkan sebagai dasar negara pada tanggal...', '17 Agustus 1945', '18 Agustus 1945', '1 Juni 1945', '22 Juni 1945', '10 November 1945', 'b', '2026-07-11 00:17:27'),
(53, 2, 'Makna dari sila pertama Pancasila adalah...', 'Mengakui adanya Tuhan Yang Maha Esa dan menjalankan ajaran agama masing-masing', 'Mengutamakan kepentingan bersama di atas kepentingan pribadi', 'Mengakui persamaan derajat, hak, dan kewajiban', 'Membina persatuan dan kesatuan bangsa', 'Mengutamakan musyawarah untuk mencapai mufakat', 'a', '2026-07-11 00:17:27'),
(54, 2, 'Undang-Undang Dasar Negara Republik Indonesia Tahun 1945 merupakan...', 'Peraturan daerah tertinggi', 'Hukum tertinggi di Indonesia', 'Hukum yang hanya berlaku untuk pejabat negara', 'Pedoman hidup beragama', 'Peraturan yang dapat diubah setiap saat', 'b', '2026-07-11 00:17:27'),
(55, 2, 'Bentuk negara Indonesia adalah...', 'Negara Kesatuan', 'Negara Serikat', 'Negara Konfederasi', 'Negara Kerajaan', 'Negara Federal', 'a', '2026-07-11 00:17:27'),
(56, 2, 'Lambang negara Indonesia adalah...', 'Bendera Merah Putih', 'Lagu Indonesia Raya', 'Garuda Pancasila', 'Pohon Beringin', 'Bintang Emas', 'c', '2026-07-11 00:17:27'),
(57, 2, 'Salah satu upaya menjaga persatuan dan kesatuan bangsa adalah...', 'Membeda-bedakan suku dan agama', 'Menghargai perbedaan yang ada', 'Mengutamakan kepentingan daerah sendiri', 'Menghindari kerjasama antarwarga', 'Membatasi pergaulan dengan orang lain', 'b', '2026-07-11 00:17:27'),
(58, 2, 'Hak asasi manusia yang paling mendasar adalah...', 'Hak memiliki harta benda', 'Hak hidup dan mempertahankan hidup', 'Hak mendapatkan pekerjaan', 'Hak berpolitik', 'Hak mengeluarkan pendapat', 'b', '2026-07-11 00:17:27'),
(59, 2, 'Kedaulatan berada di tangan rakyat dan dilaksanakan menurut Undang-Undang Dasar adalah bunyi dari...', 'Pasal 1 ayat (2) UUD 1945', 'Pasal 27 ayat (1) UUD 1945', 'Pasal 30 ayat (1) UUD 1945', 'Pasal 31 ayat (1) UUD 1945', 'Pasal 34 ayat (1) UUD 1945', 'a', '2026-07-11 00:17:27'),
(60, 2, 'Wilayah Indonesia terbentang dari Sabang sampai...', 'Merauke', 'Papua', 'Timor', 'Kalimantan', 'Sulawesi', 'a', '2026-07-11 00:17:27'),
(61, 2, 'Salah satu ciri khas negara hukum adalah...', 'Adanya penguasa yang mutlak', 'Semua warga negara dan penyelenggara negara tunduk pada hukum', 'Hukum hanya berlaku untuk rakyat biasa', 'Keputusan pemimpin tidak dapat diganggu gugat', 'Tidak ada lembaga yang mengawasi pelaksanaan hukum', 'b', '2026-07-11 00:17:27'),
(62, 2, 'Perjuangan mempertahankan kemerdekaan Indonesia melawan Belanda dilakukan pada tahun...', '1945 - 1949', '1942 - 1945', '1950 - 1955', '1965 - 1970', '1975 - 1980', 'a', '2026-07-11 00:17:27'),
(63, 2, 'Pemerintah daerah berhak mengatur dan mengurus sendiri urusan pemerintahan menurut asas otonomi, hal ini tercantum dalam...', 'Pasal 18 UUD 1945', 'Pasal 20 UUD 1945', 'Pasal 22 UUD 1945', 'Pasal 25 UUD 1945', 'Pasal 28 UUD 1945', 'a', '2026-07-11 00:17:27'),
(64, 2, 'Sikap yang mencerminkan pengamalan sila ke-2 Pancasila adalah...', 'Menghormati hak orang lain', 'Beribadah sesuai agama masing-masing', 'Membela tanah air', 'Bekerja sama dalam musyawarah', 'Bersikap adil dan bijaksana', 'a', '2026-07-11 00:17:27'),
(65, 2, 'Bahasa negara Indonesia adalah...', 'Bahasa daerah', 'Bahasa Indonesia', 'Bahasa Melayu', 'Bahasa Jawa', 'Bahasa Inggris', 'b', '2026-07-11 00:17:27'),
(66, 2, 'Sinonim dari kata \"Kompeten\" adalah...', 'Mampu', 'Kurang', 'Ragu', 'Lambat', 'Sulit', 'a', '2026-07-11 00:17:27'),
(67, 2, 'Lawan kata dari \"Konstruktif\" adalah...', 'Membangun', 'Merusak', 'Membantu', 'Menyelesaikan', 'Memperbaiki', 'b', '2026-07-11 00:17:27'),
(68, 2, 'Jika \"Dokter : Rumah Sakit\", maka \"Guru : ...\"', 'Sekolah', 'Perpustakaan', 'Pasar', 'Taman', 'Rumah', 'a', '2026-07-11 00:17:27'),
(69, 2, 'Urutan angka berikut: 4, 9, 19, 39, ... Angka selanjutnya adalah...', '59', '69', '79', '89', '99', 'c', '2026-07-11 00:17:27'),
(70, 2, 'Berapakah hasil dari 15% dari 400?', '40', '50', '60', '70', '80', 'c', '2026-07-11 00:17:27'),
(71, 2, 'Jika 6 orang dapat menyelesaikan pekerjaan dalam 10 hari, maka berapa hari yang dibutuhkan oleh 12 orang?', '3 hari', '4 hari', '5 hari', '6 hari', '7 hari', 'c', '2026-07-11 00:17:27'),
(72, 2, 'Sebuah mobil melaju dengan kecepatan 80 km/jam selama 2,5 jam. Jarak yang ditempuh adalah...', '160 km', '180 km', '200 km', '220 km', '240 km', 'c', '2026-07-11 00:17:27'),
(73, 2, 'Jika x + 5 = 12, maka nilai 2x - 3 adalah...', '9', '10', '11', '12', '13', 'c', '2026-07-11 00:17:27'),
(74, 2, 'Manakah kata yang tidak termasuk dalam kelompok berikut? Kapal, Pesawat, Kereta Api, Sepeda Motor, Mobil', 'Kapal', 'Pesawat', 'Kereta Api', 'Sepeda Motor', 'Mobil', 'a', '2026-07-11 00:17:27'),
(75, 2, 'Persamaan makna kata \"Mandat\" adalah...', 'Perintah', 'Larangan', 'Saran', 'Usulan', 'Pendapat', 'a', '2026-07-11 00:17:27'),
(76, 2, 'Urutan angka berikut: 2, 6, 12, 20, 30, ... Angka selanjutnya adalah...', '40', '42', '44', '46', '48', 'b', '2026-07-11 00:17:27'),
(77, 2, 'Berapakah hasil dari √225 + √196?', '25', '27', '29', '31', '33', 'c', '2026-07-11 00:17:27'),
(78, 2, 'Sebuah persegi memiliki keliling 60 cm. Luasnya adalah...', '150 cm²', '200 cm²', '225 cm²', '250 cm²', '300 cm²', 'c', '2026-07-11 00:17:27'),
(79, 2, 'Jika harga sebuah barang naik 25% menjadi Rp250.000, maka harga semulanya adalah...', 'Rp180.000', 'Rp190.000', 'Rp200.000', 'Rp210.000', 'Rp220.000', 'c', '2026-07-11 00:17:27'),
(80, 2, 'Manakah pernyataan yang paling logis? Semua siswa rajin belajar mendapat nilai bagus. Andi adalah siswa yang rajin belajar. Maka...', 'Andi pasti mendapat nilai bagus', 'Andi mungkin mendapat nilai bagus', 'Andi tidak mendapat nilai bagus', 'Semua siswa mendapat nilai bagus', 'Tidak dapat disimpulkan', 'a', '2026-07-11 00:17:27'),
(81, 2, 'Jika \"Besi : Keras\", maka \"Kapas : ...\"', 'Lembut', 'Halus', 'Putih', 'Ringan', 'Bersih', 'a', '2026-07-11 00:17:27'),
(82, 2, 'Berapakah hasil dari 3/5 dari 250?', '120', '130', '140', '150', '160', 'd', '2026-07-11 00:17:27'),
(83, 2, 'Urutan peristiwa yang benar: 1. Menanam benih 2. Memanen 3. Menyiram 4. Tumbuh tunas', '1-3-4-2', '1-4-3-2', '3-1-4-2', '2-1-3-4', '4-1-3-2', 'a', '2026-07-11 00:17:27'),
(84, 2, 'Jika A = 2B dan B = 3C, maka nilai A + B adalah...', '8C', '9C', '10C', '11C', '12C', 'b', '2026-07-11 00:17:27'),
(85, 2, 'Manakah kelompok yang memiliki hubungan sama dengan \"Air : Cair\"?', 'Es : Padat', 'Uap : Panas', 'Batu : Berat', 'Kayu : Keras', 'Besi : Logam', 'a', '2026-07-11 00:17:27'),
(86, 2, 'Anda sedang mengerjakan tugas penting, tiba-tiba rekan kerja meminta bantuan menyelesaikan pekerjaannya. Sikap Anda adalah...', 'Menolak karena sedang sibuk', 'Membantu sebentar saja lalu melanjutkan tugas sendiri', 'Menghentikan tugas sendiri dan membantu sepenuhnya', 'Menyuruhnya meminta bantuan orang lain', 'Membantu setelah tugas Anda selesai', 'e', '2026-07-11 00:17:27'),
(87, 2, 'Dalam menghadapi perubahan aturan kerja yang baru, sikap Anda adalah...', 'Merasa terganggu dan enggan menyesuaikan', 'Mempelajari perubahan tersebut dan menyesuaikan diri', 'Mengikuti saja tanpa memahami alasannya', 'Menyalahkan pihak yang membuat aturan baru', 'Berusaha tetap bekerja seperti biasa', 'b', '2026-07-11 00:17:27'),
(88, 2, 'Saat bekerja dalam tim, pendapat Anda berbeda dengan pendapat mayoritas. Sikap Anda adalah...', 'Memaksakan pendapat agar diterima', 'Mendengarkan pendapat lain dan menyampaikan alasan pendapat sendiri', 'Diam saja dan mengikuti pendapat mayoritas', 'Marah karena pendapat tidak didengar', 'Keluar dari tim jika tidak disetujui', 'b', '2026-07-11 00:17:27'),
(89, 2, 'Anda menemukan kesalahan kecil dalam pekerjaan yang sudah selesai. Sikap Anda adalah...', 'Membiarkannya karena tidak terlalu penting', 'Memperbaiki kesalahan tersebut secepatnya', 'Menyalahkan orang lain yang mengerjakan sebelumnya', 'Menyembunyikan kesalahan tersebut', 'Melaporkan tanpa berusaha memperbaiki', 'b', '2026-07-11 00:17:27'),
(90, 2, 'Saat menghadapi tekanan pekerjaan yang menumpuk, sikap Anda adalah...', 'Merasa panik dan bingung', 'Membagi tugas dan menyelesaikannya secara bertahap', 'Mengeluh pada rekan kerja', 'Menunda-nunda pekerjaan', 'Menyalahkan situasi', 'b', '2026-07-11 00:17:27'),
(91, 2, 'Atasan memberikan tugas di luar tanggung jawab Anda. Sikap Anda adalah...', 'Menolak dengan tegas', 'Menerima dan melaksanakannya dengan baik', 'Menerima tapi mengeluh terus', 'Menerima tapi mengerjakan dengan asal-asalan', 'Meminta tambahan gaji terlebih dahulu', 'b', '2026-07-11 00:17:27'),
(92, 2, 'Anda melihat rekan kerja melakukan kesalahan yang bisa merugikan pekerjaan. Sikap Anda adalah...', 'Membiarkannya saja', 'Mengingatkan dengan cara yang baik', 'Melaporkan langsung ke atasan tanpa bicara dulu', 'Membicarakan ke orang lain', 'Menertawakan kesalahannya', 'b', '2026-07-11 00:17:27'),
(93, 2, 'Dalam menyelesaikan pekerjaan, Anda lebih mengutamakan...', 'Kecepatan selesai tanpa memikirkan hasilnya', 'Ketepatan dan kualitas hasil pekerjaan', 'Melakukan seminimal mungkin', 'Menyelesaikan secepat mungkin meski kurang rapi', 'Menyelesaikan dengan bantuan orang lain', 'b', '2026-07-11 00:17:27'),
(94, 2, 'Saat menerima kritik dari orang lain, sikap Anda adalah...', 'Marah dan membela diri', 'Mendengarkan dengan baik dan menjadikannya perbaikan', 'Mengabaikan kritik tersebut', 'Membalas dengan kritik juga', 'Berpura-pura tidak mendengar', 'b', '2026-07-11 00:17:27'),
(95, 2, 'Jika ada kesempatan untuk mengikuti pelatihan peningkatan kemampuan, Anda akan...', 'Tidak tertarik karena sudah merasa mampu', 'Mengikuti jika tidak mengganggu waktu istirahat', 'Mengikuti dengan antusias untuk menambah pengetahuan', 'Mengikuti hanya jika disuruh atasan', 'Menolak karena takut sulit', 'c', '2026-07-11 00:17:27'),
(96, 2, 'Anda mengetahui ada informasi penting yang harus disampaikan ke tim. Sikap Anda adalah...', 'Menyampaikan segera kepada seluruh anggota tim', 'Menyampaikan hanya ke orang yang dianggap perlu', 'Menyimpan sendiri sampai ditanya', 'Menyampaikan secara berangsur-angsur', 'Menyampaikan jika ada waktu luang', 'a', '2026-07-11 00:17:27'),
(97, 2, 'Saat bekerja dengan orang yang memiliki sifat berbeda dari Anda, sikap Anda adalah...', 'Menghindari kerjasama dengannya', 'Tetap bekerja sama dan menghargai perbedaannya', 'Bekerja tapi hanya sebatasnya saja', 'Mencari alasan agar tidak bekerja sama', 'Membicarakan keburukannya ke orang lain', 'b', '2026-07-11 00:17:27'),
(98, 2, 'Jika pekerjaan yang Anda kerjakan mengalami kegagalan, sikap Anda adalah...', 'Menyalahkan keadaan atau orang lain', 'Mencari penyebab kegagalan dan berusaha memperbaikinya', 'Menyerah dan tidak mau mencoba lagi', 'Merasa sedih berkepanjangan', 'Menganggap itu takdir', 'b', '2026-07-11 00:17:27'),
(99, 2, 'Dalam mengerjakan tugas, Anda selalu berusaha...', 'Menyelesaikan tepat waktu dan sesuai standar', 'Menyelesaikan sebelum batas waktu meski kurang rapi', 'Menyelesaikan mendekati batas waktu', 'Menyelesaikan jika diawasi', 'Menyelesaikan secepatnya tanpa peduli kualitas', 'a', '2026-07-11 00:17:27'),
(100, 2, 'Saat diberi kepercayaan memimpin kelompok, Anda akan...', 'Bekerja sendiri agar lebih cepat selesai', 'Membagi tugas sesuai kemampuan anggota dan mengawasi jalannya pekerjaan', 'Menyuruh anggota mengerjakan semuanya', 'Membiarkan anggota bekerja sendiri tanpa arahan', 'Memilih anggota yang paling dekat saja', 'b', '2026-07-11 00:17:27'),
(101, 2, 'Salah satu prinsip penyelenggaraan negara adalah...', 'Bebas melakukan apa saja', 'Terbuka, jujur, dan bertanggung jawab', 'Hanya menguntungkan golongan sendiri', 'Tertutup dari masyarakat', 'Berdasarkan kehendak pemimpin', 'b', '2026-07-11 00:23:37'),
(102, 2, 'Lembaga negara yang berwenang membuat undang-undang adalah...', 'Presiden', 'Mahkamah Agung', 'Dewan Perwakilan Rakyat', 'Pemerintah Daerah', 'Badan Pemeriksa Keuangan', 'c', '2026-07-11 00:23:37'),
(103, 2, 'Hak untuk mendapatkan pekerjaan dan penghidupan yang layak tercantum dalam...', 'Pasal 27 UUD 1945', 'Pasal 28 UUD 1945', 'Pasal 29 UUD 1945', 'Pasal 30 UUD 1945', 'Pasal 31 UUD 1945', 'a', '2026-07-11 00:23:37'),
(104, 2, 'Persatuan Indonesia tercantum dalam sila ke...', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'c', '2026-07-11 00:23:37'),
(105, 2, 'Yang menjadi dasar hukum otonomi daerah adalah...', 'UUD 1945 dan Undang-Undang tentang Pemerintahan Daerah', 'Keputusan presiden saja', 'Peraturan daerah saja', 'Kebiasaan masyarakat setempat', 'Perjanjian internasional', 'a', '2026-07-11 00:23:37'),
(106, 2, 'Sikap yang tidak mencerminkan persatuan dan kesatuan adalah...', 'Saling menghormati antarwarga', 'Saling membantu dalam kesulitan', 'Mengutamakan kepentingan suku sendiri', 'Bekerja sama membangun lingkungan', 'Menghargai budaya lain', 'c', '2026-07-11 00:23:37'),
(107, 2, 'Negara Indonesia merdeka pada tanggal...', '1 Juni 1945', '17 Agustus 1945', '18 Agustus 1945', '28 Oktober 1928', '20 Mei 1908', 'b', '2026-07-11 00:23:37'),
(108, 2, 'Makna sila keempat Pancasila adalah...', 'Keadilan sosial bagi seluruh rakyat Indonesia', 'Persatuan Indonesia', 'Kerakyatan yang dipimpin oleh hikmat kebijaksanaan dalam permusyawaratan/perwakilan', 'Kemanusiaan yang adil dan beradab', 'Ketuhanan Yang Maha Esa', 'c', '2026-07-11 00:23:37'),
(109, 2, 'Bunyi Sumpah Pemuda adalah satu tanah air, satu bangsa, dan satu...', 'Bahasa', 'Agama', 'Adat', 'Pemerintah', 'Hukum', 'a', '2026-07-11 00:23:37'),
(110, 2, 'Lembaga yang berwenang menguji undang-undang terhadap UUD 1945 adalah...', 'Mahkamah Konstitusi', 'Mahkamah Agung', 'Dewan Perwakilan Rakyat', 'Presiden', 'Badan Pengawas Keuangan', 'a', '2026-07-11 00:23:37'),
(111, 2, 'Kewajiban warga negara di bidang pertahanan dan keamanan negara tercantum dalam...', 'Pasal 27 ayat (2)', 'Pasal 29 ayat (1)', 'Pasal 30 ayat (1)', 'Pasal 31 ayat (2)', 'Pasal 32 ayat (1)', 'c', '2026-07-11 00:23:37'),
(112, 2, 'Bahasa Indonesia ditetapkan sebagai bahasa persatuan pada peristiwa...', 'Proklamasi Kemerdekaan', 'Sumpah Pemuda', 'Sidang BPUPKI', 'Sidang PPKI', 'Kongres Bahasa Indonesia', 'b', '2026-07-11 00:23:37'),
(113, 2, 'Salah satu ciri negara kesatuan adalah...', 'Hanya ada satu kedaulatan', 'Setiap daerah memiliki negara sendiri', 'Memiliki beberapa konstitusi', 'Pemerintahan terpisah sepenuhnya', 'Tidak ada pemerintahan pusat', 'a', '2026-07-11 00:23:37'),
(114, 2, 'Pengamalan sila kelima Pancasila dalam kehidupan sehari-hari adalah...', 'Bersikap adil kepada sesama', 'Beribadah dengan khusyuk', 'Menghargai pendapat orang lain', 'Mencintai sesama manusia', 'Mencintai tanah air', 'a', '2026-07-11 00:23:37'),
(115, 2, 'Kedaulatan rakyat diwujudkan dalam sistem pemerintahan...', 'Kerajaan mutlak', 'Demokrasi', 'Diktator', 'Otoriter', 'Militer', 'b', '2026-07-11 00:23:37'),
(116, 2, 'Hak dan kewajiban warga negara harus dijalankan secara...', 'Sebelah pihak saja', 'Seimbang dan selaras', 'Mengutamakan hak lebih dulu', 'Mengutamakan kewajiban saja', 'Bebas tanpa batas', 'b', '2026-07-11 00:23:37'),
(117, 2, 'Lambang sila keempat Pancasila adalah...', 'Bintang', 'Rantai', 'Pohon Beringin', 'Kepala Banteng', 'Padi dan Kapas', 'd', '2026-07-11 00:23:37'),
(118, 2, 'Pembukaan UUD 1945 memuat dasar negara yaitu...', 'Undang-Undang', 'Pancasila', 'Hukum Adat', 'Peraturan Pemerintah', 'Ketetapan MPR', 'b', '2026-07-11 00:23:37'),
(119, 2, 'Yang menjadi tujuan negara Indonesia tercantum dalam...', 'Pembukaan UUD 1945 alinea ke-4', 'Batang tubuh UUD 1945', 'Penjelasan UUD 1945', 'Ketetapan MPR', 'Undang-Undang Dasar Sementara', 'a', '2026-07-11 00:23:37'),
(120, 2, 'Sikap menghargai pendapat orang lain dalam musyawarah mencerminkan pengamalan sila ke...', 'Dua', 'Tiga', 'Empat', 'Lima', 'Satu', 'c', '2026-07-11 00:23:37'),
(121, 2, 'Sinonim dari kata \"Prasyarat\" adalah...', 'Syarat awal', 'Hasil akhir', 'Tujuan utama', 'Langkah terakhir', 'Kesimpulan', 'a', '2026-07-11 00:23:37'),
(122, 2, 'Lawan kata dari \"Efisien\" adalah...', 'Boros', 'Cepat', 'Tepat', 'Baik', 'Mudah', 'a', '2026-07-11 00:23:37'),
(123, 2, 'Jika \"Padi : Gabah\", maka \"Karet : ...\"', 'Getah', 'Karet lembaran', 'Pohon', 'Tanaman', 'Hutan', 'a', '2026-07-11 00:23:37'),
(124, 2, 'Urutan angka: 1, 3, 7, 15, 31, ... Angka selanjutnya adalah...', '47', '53', '63', '71', '83', 'c', '2026-07-11 00:23:37'),
(125, 2, 'Berapakah hasil dari 20% dari Rp750.000?', 'Rp125.000', 'Rp150.000', 'Rp175.000', 'Rp200.000', 'Rp225.000', 'b', '2026-07-11 00:23:37'),
(126, 2, 'Jika 8 orang dapat menyelesaikan pekerjaan dalam 6 hari, maka 4 orang membutuhkan waktu...', '8 hari', '10 hari', '12 hari', '14 hari', '16 hari', 'c', '2026-07-11 00:23:37'),
(127, 2, 'Sebuah sepeda bergerak dengan kecepatan 15 km/jam selama 4 jam. Jarak yang ditempuh adalah...', '45 km', '50 km', '55 km', '60 km', '65 km', 'd', '2026-07-11 00:23:37'),
(128, 2, 'Jika 3x - 4 = 14, maka nilai 2x + 5 adalah...', '12', '14', '16', '18', '20', 'e', '2026-07-11 00:23:37'),
(129, 2, 'Manakah yang berbeda dari kelompok: Gajah, Harimau, Hiu, Kuda, Kerbau', 'Gajah', 'Harimau', 'Hiu', 'Kuda', 'Kerbau', 'c', '2026-07-11 00:23:37'),
(130, 2, 'Persamaan makna kata \"Verifikasi\" adalah...', 'Pemeriksaan kebenaran', 'Penolakan', 'Pengesahan', 'Pengumuman', 'Pengiriman', 'a', '2026-07-11 00:23:37'),
(131, 2, 'Urutan angka: 5, 10, 20, 40, 80, ... Angka ke-7 adalah...', '120', '160', '200', '240', '320', 'b', '2026-07-11 00:23:37'),
(132, 2, 'Berapakah hasil dari √400 + √289?', '32', '37', '42', '47', '52', 'b', '2026-07-11 00:23:37'),
(133, 2, 'Sebuah persegi panjang memiliki luas 240 cm² dan panjang 20 cm. Lebarnya adalah...', '10 cm', '12 cm', '14 cm', '16 cm', '18 cm', 'b', '2026-07-11 00:23:37'),
(134, 2, 'Jika harga barang turun 20% menjadi Rp400.000, maka harga semula adalah...', 'Rp450.000', 'Rp480.000', 'Rp500.000', 'Rp520.000', 'Rp550.000', 'c', '2026-07-11 00:23:37'),
(135, 2, 'Semua siswa yang rajin mendapatkan nilai bagus. Budi mendapatkan nilai bagus. Maka kesimpulannya...', 'Budi pasti rajin', 'Budi mungkin rajin', 'Budi tidak rajin', 'Semua siswa rajin', 'Tidak dapat disimpulkan', 'b', '2026-07-11 00:23:37'),
(136, 2, 'Jika \"Dingin : Es\", maka \"Panas : ...\"', 'Uap', 'Air', 'Api', 'Asap', 'Bara', 'c', '2026-07-11 00:23:37'),
(137, 2, 'Berapakah hasil dari 4/7 dari 420?', '210', '220', '230', '240', '250', 'd', '2026-07-11 00:23:37'),
(138, 2, 'Urutan peristiwa: 1. Menggambar pola 2. Memotong kain 3. Menjahit 4. Membuat baju', '1-2-3-4', '2-1-3-4', '1-3-2-4', '3-2-1-4', '4-3-2-1', 'a', '2026-07-11 00:23:37'),
(139, 2, 'Jika P = 4Q dan Q = 2R, maka nilai P - Q adalah...', '4R', '6R', '8R', '10R', '12R', 'b', '2026-07-11 00:23:37'),
(140, 2, 'Hubungan yang sama dengan \"Buku : Halaman\" adalah...', 'Rumah : Kamar', 'Mobil : Ban', 'Pohon : Daun', 'Tas : Resleting', 'Sepatu : Tali', 'a', '2026-07-11 00:23:37'),
(141, 2, 'Berapakah hasil dari 1250 + 250 × 4 - 1000?', '1000', '1250', '1500', '1750', '2000', 'b', '2026-07-11 00:23:37'),
(142, 2, 'Rasio uang A dan B adalah 3 : 5. Jika jumlah uang mereka Rp800.000, maka uang A adalah...', 'Rp250.000', 'Rp300.000', 'Rp350.000', 'Rp400.000', 'Rp450.000', 'b', '2026-07-11 00:23:37'),
(143, 2, 'Jika hari ini hari Senin, maka 100 hari lagi adalah hari...', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu', 'c', '2026-07-11 00:23:37'),
(144, 2, 'Manakah bilangan yang paling besar?', '0,75', '72%', '3/4', '0,8', '18/25', 'd', '2026-07-11 00:23:37'),
(145, 2, 'Sebuah tangki air terisi penuh dalam waktu 6 jam dengan 2 keran. Jika dipakai 3 keran, waktu yang dibutuhkan adalah...', '2 jam', '3 jam', '4 jam', '5 jam', '6 jam', 'c', '2026-07-11 00:23:37'),
(146, 2, 'Sinonim dari kata \"Analisis\" adalah...', 'Penguraian', 'Penggabungan', 'Penyatuan', 'Penyederhanaan', 'Penjumlahan', 'a', '2026-07-11 00:23:37'),
(147, 2, 'Lawan kata dari \"Objektif\" adalah...', 'Netral', 'Pribadi', 'Adil', 'Jelas', 'Tepat', 'b', '2026-07-11 00:23:37'),
(148, 2, 'Jika \"Burung : Terbang\", maka \"Ikan : ...\"', 'Berenang', 'Menyelam', 'Berenang di air', 'Hidup di laut', 'Bergerak', 'a', '2026-07-11 00:23:37'),
(149, 2, 'Urutan angka: 1, 2, 4, 8, 16, ... Angka ke-10 adalah...', '256', '512', '1024', '2048', '4096', 'b', '2026-07-11 00:23:37'),
(150, 2, 'Berapakah hasil dari 15% + 25% dari 2000?', '600', '700', '800', '900', '1000', 'c', '2026-07-11 00:23:37'),
(151, 2, 'Jika 5 pekerja dapat menyelesaikan dalam 12 hari, maka dalam 3 hari butuh pekerja sebanyak...', '15 orang', '20 orang', '25 orang', '30 orang', '35 orang', 'b', '2026-07-11 00:23:37'),
(152, 2, 'Sebuah segitiga memiliki alas 18 cm dan tinggi 12 cm. Luasnya adalah...', '96 cm²', '108 cm²', '120 cm²', '132 cm²', '144 cm²', 'b', '2026-07-11 00:23:37'),
(153, 2, 'Jika harga naik 10% menjadi Rp1.100.000, maka harga semula adalah...', 'Rp950.000', 'Rp1.000.000', 'Rp1.050.000', 'Rp1.150.000', 'Rp1.200.000', 'b', '2026-07-11 00:23:37'),
(154, 2, 'Jika semua siswa rajin belajar, maka lulus ujian. Rina tidak lulus ujian. Kesimpulannya...', 'Rina tidak rajin belajar', 'Rina rajin belajar', 'Semua siswa tidak lulus', 'Tidak dapat disimpulkan', 'Semua siswa tidak rajin', 'a', '2026-07-11 00:23:37'),
(155, 2, 'Hubungan \"Guru : Mengajar\" sama dengan \"Dokter : ...\"', 'Menyembuhkan', 'Memeriksa', 'Memberi obat', 'Bekerja', 'Di rumah sakit', 'a', '2026-07-11 00:23:37'),
(156, 2, 'Anda melihat rekan kerja melanggar aturan kantor yang ringan. Sikap Anda...', 'Mengingatkan dengan sopan agar tidak terulang', 'Membiarkan saja', 'Langsung lapor atasan', 'Membicarakan ke orang lain', 'Menegur dengan keras', 'a', '2026-07-11 00:23:37'),
(157, 2, 'Saat diberi tugas baru yang belum pernah Anda kerjakan, sikap Anda...', 'Mencari informasi dan mempelajari cara mengerjakannya', 'Menolak karena takut salah', 'Menerima tapi asal mengerjakan', 'Meminta orang lain mengerjakan', 'Mengeluh karena sulit', 'a', '2026-07-11 00:23:37'),
(158, 2, 'Jika ada kesalahan dalam pekerjaan tim dan Anda ikut bertanggung jawab, sikap Anda...', 'Mengakui kesalahan dan membantu memperbaikinya', 'Menyalahkan anggota lain', 'Membela diri sekuat tenaga', 'Diam saja tidak berkomentar', 'Mengalihkan pembicaraan', 'a', '2026-07-11 00:23:37'),
(159, 2, 'Dalam bekerja, Anda lebih mengutamakan...', 'Hasil kerja yang berkualitas dan tepat waktu', 'Selesai cepat meski kurang rapi', 'Kerja seminimal mungkin', 'Banyak istirahat', 'Mengerjakan sesuai perasaan', 'a', '2026-07-11 00:23:37'),
(160, 2, 'Atasan memberikan tugas mendesak di luar jam kerja. Sikap Anda...', 'Menerima dan menyelesaikannya dengan baik', 'Menolak karena sudah pulang', 'Menerima tapi mengerjakan nanti', 'Menerima tapi mengeluh', 'Menerima tapi meminta bayaran tambahan', 'a', '2026-07-11 00:23:37'),
(161, 2, 'Anda melihat kesalahan laporan yang dibuat rekan kerja. Sikap Anda...', 'Menyampaikan dengan baik agar diperbaiki', 'Membiarkan saja', 'Langsung lapor atasan', 'Memperbaiki sendiri tanpa memberi tahu', 'Membicarakan ke orang lain', 'a', '2026-07-11 00:23:37'),
(162, 2, 'Saat bekerja dengan rekan yang lambat, sikap Anda...', 'Membantu dan mengajari cara yang lebih efisien', 'Menyuruhnya bekerja lebih cepat', 'Mengerjakan bagiannya sendiri', 'Mengeluh ke atasan', 'Menghindari bekerja dengannya', 'a', '2026-07-11 00:23:37'),
(163, 2, 'Jika ada perbedaan pendapat dalam rapat, Anda akan...', 'Menyampaikan pendapat dengan sopan dan mendengarkan pandangan lain', 'Memaksakan pendapat sendiri', 'Diam saja mengikuti mayoritas', 'Marah jika tidak disetujui', 'Keluar dari rapat', 'a', '2026-07-11 00:23:37'),
(164, 2, 'Anda menerima tugas yang dianggap sulit oleh rekan lain. Sikap Anda...', 'Menerima dan berusaha menyelesaikannya dengan sebaik mungkin', 'Menolak karena sulit', 'Menerima tapi meminta bantuan terus', 'Menerima tapi mengerjakan asal', 'Membagi tugas ke orang lain', 'a', '2026-07-11 00:23:37'),
(165, 2, 'Saat mendapatkan pujian atas hasil kerja, Anda akan...', 'Mengucapkan terima kasih dan tetap rendah hati', 'Merasa paling hebat', 'Membanggakan diri ke orang lain', 'Menganggap itu sudah biasa', 'Meminta pujian lebih banyak', 'a', '2026-07-11 00:23:37'),
(166, 2, 'Jika pekerjaan Anda terhambat karena kendala teknis, Anda akan...', 'Mencari solusi atau melaporkan segera agar tidak tertunda', 'Menunggu sampai selesai sendiri', 'Menyalahkan keadaan', 'Berhenti bekerja sementara', 'Mengeluh terus-menerus', 'a', '2026-07-11 00:23:37'),
(167, 2, 'Dalam tim, Anda melihat ada anggota yang tidak aktif. Sikap Anda...', 'Mengajak berpartisipasi dan memberikan tugas sesuai kemampuan', 'Membiarkan saja', 'Melaporkan ke atasan', 'Mengerjakan tugasnya sendiri', 'Membicarakan keburukannya', 'a', '2026-07-11 00:23:37'),
(168, 2, 'Anda diminta membantu pekerjaan rekan meski sedang sibuk. Sikap Anda...', 'Membantu sebisa mungkin setelah mengatur jadwal sendiri', 'Menolak dengan tegas', 'Membantu tapi mengeluh', 'Membantu tapi asal saja', 'Menyuruhnya cari orang lain', 'a', '2026-07-11 00:23:37'),
(169, 2, 'Saat ada kesempatan untuk mengembangkan kemampuan, Anda akan...', 'Mengikuti dengan antusias untuk meningkatkan kinerja', 'Mengikuti jika tidak mengganggu waktu', 'Tidak tertarik karena sudah cukup', 'Mengikuti hanya jika disuruh', 'Menolak karena memakan waktu', 'a', '2026-07-11 00:23:37'),
(170, 2, 'Jika terjadi kesalahpahaman dengan rekan kerja, Anda akan...', 'Membicarakan baik-baik untuk menyelesaikannya', 'Mendiamkan saja', 'Membalas dengan sikap sama', 'Mengadu ke atasan', 'Membicarakan ke orang lain', 'a', '2026-07-11 00:23:37'),
(171, 3, 'Sinonim dari kata \"Analisis\" adalah...', 'Penguraian', 'Penggabungan', 'Penyatuan', 'Penjumlahan', 'Perkiraan', 'a', '2026-07-11 00:28:14'),
(172, 3, 'Lawan kata dari \"Konstruktif\" adalah...', 'Membangun', 'Merusak', 'Membantu', 'Memperbaiki', 'Mengembangkan', 'b', '2026-07-11 00:28:14'),
(173, 3, 'Persamaan makna kata \"Konsisten\" adalah...', 'Berubah-ubah', 'Tetap', 'Lambat', 'Cepat', 'Ragu', 'b', '2026-07-11 00:28:14'),
(174, 3, 'Lawan kata dari \"Efisien\" adalah...', 'Tepat', 'Boros', 'Cepat', 'Baik', 'Mudah', 'b', '2026-07-11 00:28:14'),
(175, 3, 'Sinonim dari kata \"Signifikan\" adalah...', 'Tidak berarti', 'Penting', 'Kecil', 'Biasa', 'Sama', 'b', '2026-07-11 00:28:14'),
(176, 3, 'Lawan kata dari \"Optimis\" adalah...', 'Percaya diri', 'Pesimis', 'Semangat', 'Yakin', 'Harapan', 'b', '2026-07-11 00:28:14'),
(177, 3, 'Persamaan makna kata \"Mandat\" adalah...', 'Perintah', 'Larangan', 'Saran', 'Usulan', 'Pendapat', 'a', '2026-07-11 00:28:14'),
(178, 3, 'Lawan kata dari \"Objektif\" adalah...', 'Netral', 'Subjektif', 'Adil', 'Jelas', 'Tepat', 'b', '2026-07-11 00:28:14'),
(179, 3, 'Jika \"Buku : Membaca\", maka \"Peta : ...\"', 'Melihat', 'Menulis', 'Mempelajari', 'Menemukan arah', 'Menyimpan', 'd', '2026-07-11 00:28:14'),
(180, 3, 'Jika \"Dokter : Rumah Sakit\", maka \"Guru : ...\"', 'Sekolah', 'Perpustakaan', 'Pasar', 'Taman', 'Rumah', 'a', '2026-07-11 00:28:14'),
(181, 3, 'Manakah kata yang tidak termasuk dalam kelompok berikut? Kambing, Sapi, Kerbau, Harimau, Domba', 'Kambing', 'Sapi', 'Harimau', 'Kerbau', 'Domba', 'c', '2026-07-11 00:28:14'),
(182, 3, 'Manakah susunan kata yang paling logis? 1. Menanam 2. Memanen 3. Menyiram 4. Tumbuh', '1-3-4-2', '1-4-3-2', '3-1-4-2', '2-1-3-4', '4-1-3-2', 'a', '2026-07-11 00:28:14'),
(183, 3, 'Sinonim dari kata \"Kompeten\" adalah...', 'Mampu', 'Kurang', 'Ragu', 'Lambat', 'Sulit', 'a', '2026-07-11 00:28:14'),
(184, 3, 'Lawan kata dari \"Ekspansi\" adalah...', 'Perluasan', 'Penyempitan', 'Pertumbuhan', 'Pengembangan', 'Penyebaran', 'b', '2026-07-11 00:28:14'),
(185, 3, 'Jika \"Kayu : Meja\", maka \"Kain : ...\"', 'Pakaian', 'Benang', 'Warna', 'Jahitan', 'Kapas', 'a', '2026-07-11 00:28:14'),
(186, 3, 'Urutan bilangan: 2, 5, 11, 23, 47, ... Bilangan selanjutnya adalah...', '79', '89', '95', '97', '101', 'd', '2026-07-11 00:28:14'),
(187, 3, 'Berapakah hasil dari 12,5% dari 800?', '80', '90', '100', '120', '150', 'c', '2026-07-11 00:28:14'),
(188, 3, 'Jika 6 pekerja dapat menyelesaikan pekerjaan dalam 12 hari, maka 9 pekerja membutuhkan waktu...', '6 hari', '7 hari', '8 hari', '9 hari', '10 hari', 'c', '2026-07-11 00:28:14'),
(189, 3, 'Sebuah kendaraan melaju dengan kecepatan 60 km/jam selama 2 jam 30 menit. Jarak yang ditempuh adalah...', '120 km', '130 km', '140 km', '150 km', '160 km', 'd', '2026-07-11 00:28:14'),
(190, 3, 'Jika 3x - 7 = 23, maka nilai 5x + 2 adalah...', '42', '47', '52', '57', '62', 'd', '2026-07-11 00:28:14'),
(191, 3, 'Urutan bilangan: 1, 4, 9, 16, 25, ... Bilangan ke-8 adalah...', '49', '56', '64', '72', '81', 'c', '2026-07-11 00:28:14'),
(192, 3, 'Berapakah hasil dari √625 + ∛27?', '28', '30', '32', '34', '36', 'a', '2026-07-11 00:28:14'),
(193, 3, 'Sebuah persegi panjang memiliki luas 360 cm² dan lebar 15 cm. Panjangnya adalah...', '20 cm', '22 cm', '24 cm', '26 cm', '28 cm', 'c', '2026-07-11 00:28:14'),
(194, 3, 'Jika harga barang naik 25% menjadi Rp500.000, maka harga semula adalah...', 'Rp350.000', 'Rp380.000', 'Rp400.000', 'Rp420.000', 'Rp450.000', 'c', '2026-07-11 00:28:14'),
(195, 3, 'Rasio uang A dan B adalah 2 : 5. Jika selisih uang mereka Rp900.000, maka uang B adalah...', 'Rp600.000', 'Rp900.000', 'Rp1.200.000', 'Rp1.500.000', 'Rp1.800.000', 'd', '2026-07-11 00:28:14'),
(196, 3, 'Berapakah hasil dari 250 + 150 × 3 - 400?', '200', '300', '400', '500', '600', 'b', '2026-07-11 00:28:14'),
(197, 3, 'Sebuah tabung memiliki jari-jari 7 cm dan tinggi 15 cm. Volume tabung tersebut (π = 22/7) adalah...', '2.150 cm³', '2.250 cm³', '2.310 cm³', '2.450 cm³', '2.520 cm³', 'c', '2026-07-11 00:28:14'),
(198, 3, 'Jika hari ini hari Rabu, maka 75 hari lagi adalah hari...', 'Jumat', 'Sabtu', 'Minggu', 'Senin', 'Selasa', 'a', '2026-07-11 00:28:14'),
(199, 3, 'Manakah nilai yang paling besar?', '0,65', '62%', '2/3', '13/20', '0,6', 'c', '2026-07-11 00:28:14'),
(200, 3, 'Jika sebuah tangki terisi penuh dalam 8 jam dengan 3 keran, maka dengan 4 keran waktu yang dibutuhkan adalah...', '4 jam', '5 jam', '6 jam', '7 jam', '8 jam', 'c', '2026-07-11 00:28:14'),
(201, 3, 'Jika semua mahasiswa rajin belajar, maka mereka lulus ujian. Andi adalah mahasiswa yang tidak lulus ujian. Maka kesimpulannya adalah...', 'Andi rajin belajar', 'Andi tidak rajin belajar', 'Semua mahasiswa tidak lulus', 'Semua mahasiswa malas', 'Tidak dapat disimpulkan', 'b', '2026-07-11 00:28:14'),
(202, 3, 'Jika hari hujan, maka jalanan basah. Hari ini jalanan basah. Maka kesimpulannya adalah...', 'Pasti hujan', 'Bisa jadi hujan atau ada sebab lain', 'Tidak hujan', 'Pasti cerah', 'Jalanan selalu basah', 'b', '2026-07-11 00:28:14'),
(203, 3, 'Semua burung memiliki sayap. Merpati adalah burung. Maka kesimpulannya adalah...', 'Merpati bisa terbang', 'Merpati memiliki sayap', 'Semua yang bersayap adalah burung', 'Burung adalah merpati', 'Tidak ada jawaban benar', 'b', '2026-07-11 00:28:14'),
(204, 3, 'Jika A lebih tua dari B, B lebih tua dari C, dan D lebih muda dari C. Maka urutan dari yang tertua adalah...', 'A - B - C - D', 'A - C - B - D', 'D - C - B - A', 'B - A - C - D', 'A - B - D - C', 'a', '2026-07-11 00:28:14'),
(205, 3, 'Hubungan yang sama dengan \"Panas : Api\" adalah...', 'Terang : Lampu', 'Air : Dingin', 'Gelap : Malam', 'Cahaya : Sinar', 'Besar : Besi', 'a', '2026-07-11 00:28:14'),
(206, 3, 'Jika \"Makan : Kenyang\", maka \"Minum : ...\"', 'Lapar', 'Haus', 'Segar', 'Cukup', 'Kenyang', 'c', '2026-07-11 00:28:14'),
(207, 3, 'Manakah pernyataan yang paling logis?', 'Jika malam hari maka matahari bersinar', 'Jika air mendidih maka suhunya mencapai 100°C', 'Jika langit mendung maka pasti hujan', 'Jika angin kencang maka pohon tumbang', 'Jika siang hari maka bulan terlihat', 'b', '2026-07-11 00:28:14'),
(208, 3, 'Jika X = 3Y dan Y = 2Z, maka nilai X + Y adalah...', '5Z', '6Z', '7Z', '8Z', '9Z', 'd', '2026-07-11 00:28:14'),
(209, 3, 'Manakah kelompok yang berbeda dari yang lain?', 'Segitiga', 'Persegi', 'Lingkaran', 'Persegi panjang', 'Jajar genjang', 'c', '2026-07-11 00:28:14'),
(210, 3, 'Jika lampu menyala, maka ada aliran listrik. Tidak ada aliran listrik. Maka kesimpulannya adalah...', 'Lampu menyala', 'Lampu tidak menyala', 'Lampu rusak', 'Saklar mati', 'Tidak dapat disimpulkan', 'b', '2026-07-11 00:28:14'),
(211, 4, 'Jika semua hewan menyukai air, dan ikan adalah hewan. Maka kesimpulannya adalah...', 'Ikan tidak menyukai air', 'Ikan menyukai air', 'Semua yang menyukai air adalah ikan', 'Hanya ikan yang menyukai air', 'Tidak dapat disimpulkan', 'b', '2026-07-11 00:30:19'),
(212, 4, 'Jika hujan turun, maka tanah menjadi basah. Tanah tidak basah. Maka kesimpulannya adalah...', 'Hujan baru saja turun', 'Hujan tidak turun', 'Tanah selalu basah', 'Pasti ada yang menyiram', 'Tidak ada jawaban benar', 'b', '2026-07-11 00:30:19'),
(213, 4, 'Semua buku adalah benda cetak. Sebagian benda cetak berwarna hitam. Maka...', 'Semua buku berwarna hitam', 'Sebagian buku berwarna hitam', 'Sebagian buku adalah benda cetak', 'Semua benda cetak adalah buku', 'Tidak dapat disimpulkan', 'c', '2026-07-11 00:30:19'),
(214, 4, 'Jika A lebih berat dari B, dan B lebih berat dari C, maka...', 'A lebih ringan dari C', 'C lebih berat dari A', 'A lebih berat dari C', 'B lebih ringan dari C', 'A, B, dan C sama berat', 'c', '2026-07-11 00:30:19'),
(215, 4, 'Jika lampu menyala, maka saklar dalam keadaan tertutup. Saklar terbuka. Maka...', 'Lampu menyala terang', 'Lampu tidak menyala', 'Lampu rusak', 'Ada arus listrik', 'Tidak dapat dipastikan', 'b', '2026-07-11 00:30:19'),
(216, 4, 'Semua siswa yang lulus ujian telah belajar. Budi telah belajar. Maka...', 'Budi pasti lulus ujian', 'Budi mungkin lulus ujian', 'Budi tidak lulus ujian', 'Semua siswa lulus ujian', 'Budi pasti tidak lulus', 'b', '2026-07-11 00:30:19'),
(217, 4, 'Jika hari libur, maka taman kota ramai dikunjungi. Hari ini taman kota sepi. Maka...', 'Hari ini hari libur', 'Hari ini bukan hari libur', 'Hari ini hujan', 'Taman kota ditutup', 'Tidak ada kesimpulan yang pasti', 'b', '2026-07-11 00:30:19'),
(218, 4, 'Semua kucing menyukai ikan. Kucing adalah hewan. Maka...', 'Semua hewan menyukai ikan', 'Semua yang menyukai ikan adalah kucing', 'Beberapa hewan menyukai ikan', 'Ikan disukai semua hewan', 'Tidak dapat disimpulkan', 'c', '2026-07-11 00:30:19'),
(219, 4, 'Berapakah angka selanjutnya dalam pola: 2, 4, 7, 11, 16, ...', '20', '21', '22', '23', '24', 'b', '2026-07-11 00:30:19'),
(220, 4, 'Pola angka: 3, 6, 12, 24, 48, ... Angka ke-7 adalah...', '72', '96', '120', '144', '192', 'b', '2026-07-11 00:30:19'),
(221, 4, 'Berapakah angka yang hilang: 5, 10, 9, 18, 17, ?, 33', '30', '32', '34', '36', '38', 'b', '2026-07-11 00:30:19'),
(222, 4, 'Pola: 1, 4, 9, 16, 25, ... Angka selanjutnya adalah...', '30', '32', '34', '36', '40', 'd', '2026-07-11 00:30:19'),
(223, 4, 'Berapakah angka yang cocok: 8, 12, 16, 20, ?, 28', '22', '23', '24', '25', '26', 'c', '2026-07-11 00:30:19'),
(224, 4, 'Pola: 2, 5, 11, 23, 47, ... Angka selanjutnya adalah...', '79', '89', '95', '97', '101', 'd', '2026-07-11 00:30:19'),
(225, 4, 'Jika 3 → 9, 4 → 16, 5 → 25, maka 7 → ?', '35', '42', '49', '56', '63', 'c', '2026-07-11 00:30:19'),
(226, 4, 'Berapakah angka berikutnya: 100, 81, 64, 49, ...', '32', '36', '40', '42', '45', 'b', '2026-07-11 00:30:19'),
(227, 4, 'Hubungan \"Pohon : Kayu\" sama dengan...', 'Bunga : Warna', 'Sapi : Daging', 'Batu : Berat', 'Kertas : Buku', 'Air : Minum', 'b', '2026-07-11 00:30:19'),
(228, 4, 'Jika \"Makan : Kenyang\", maka \"Istirahat : ...\"', 'Lelah', 'Segar', 'Tidur', 'Santai', 'Rileks', 'b', '2026-07-11 00:30:19'),
(229, 4, 'Manakah yang memiliki hubungan sama dengan \"Dokter : Pasien\"?', 'Guru : Sekolah', 'Petani : Sawah', 'Polisi : Pelanggar', 'Penjual : Pembeli', 'Sopir : Mobil', 'd', '2026-07-11 00:30:19'),
(230, 4, 'Jika \"Atas : Bawah\", maka \"Kiri : ...\"', 'Depan', 'Belakang', 'Tengah', 'Kanan', 'Samping', 'd', '2026-07-11 00:30:19'),
(231, 4, 'Manakah kelompok yang berbeda dari yang lain?', 'Persegi', 'Segitiga', 'Lingkaran', 'Jajar genjang', 'Trapesium', 'c', '2026-07-11 00:30:19'),
(232, 4, 'Jika \"Besi : Padat\", maka \"Air : ...\"', 'Cair', 'Bebas', 'Bening', 'Dingin', 'Basah', 'a', '2026-07-11 00:30:19'),
(233, 4, 'Manakah urutan peristiwa yang paling logis? 1. Menyiram 2. Tumbuh 3. Menanam 4. Berbunga', '1-2-3-4', '3-1-2-4', '3-2-1-4', '2-3-1-4', '4-3-2-1', 'b', '2026-07-11 00:30:19'),
(234, 4, 'Jika \"Kamera : Memotret\", maka \"Pensil : ...\"', 'Menghapus', 'Menggambar', 'Mengasah', 'Menyimpan', 'Membawa', 'b', '2026-07-11 00:30:19'),
(235, 4, 'Manakah pernyataan yang paling logis?', 'Jika langit gelap maka pasti malam hari', 'Jika ada asap maka pasti ada api', 'Jika hujan turun maka langit biasanya mendung', 'Jika pohon tumbuh maka pasti diberi pupuk', 'Jika mobil bergerak maka pengemudi sedang tidur', 'c', '2026-07-11 00:30:19'),
(236, 5, 'Sinonim dari kata \"Aktif\" adalah...', 'Pasif', 'Giat', 'Lambat', 'Diam', 'Malas', 'b', '2026-07-11 00:33:18'),
(237, 5, 'Persamaan makna kata \"Akurat\" adalah...', 'Tepat', 'Salah', 'Kurang', 'Ragu', 'Sembarang', 'a', '2026-07-11 00:33:18'),
(238, 5, 'Sinonim dari kata \"Berkelanjutan\" adalah...', 'Terputus', 'Berlanjut', 'Berhenti', 'Terpisah', 'Terbagi', 'b', '2026-07-11 00:33:18'),
(239, 5, 'Persamaan makna kata \"Cermat\" adalah...', 'Teliti', 'Cepat', 'Kasaran', 'Sembarang', 'Terburu-buru', 'a', '2026-07-11 00:33:18'),
(240, 5, 'Sinonim dari kata \"Daya guna\" adalah...', 'Manfaat', 'Kerugian', 'Biaya', 'Tenaga', 'Usaha', 'a', '2026-07-11 00:33:18'),
(241, 5, 'Persamaan makna kata \"Efektif\" adalah...', 'Tepat guna', 'Lambat', 'Sulit', 'Mahal', 'Rumit', 'a', '2026-07-11 00:33:18'),
(242, 5, 'Sinonim dari kata \"Fasilitas\" adalah...', 'Sarana', 'Hambatan', 'Biaya', 'Waktu', 'Tenaga', 'a', '2026-07-11 00:33:18'),
(243, 5, 'Persamaan makna kata \"Gigih\" adalah...', 'Tekun', 'Cepat', 'Mudah', 'Lemah', 'Malas', 'a', '2026-07-11 00:33:18'),
(244, 5, 'Sinonim dari kata \"Hemat\" adalah...', 'Irit', 'Boros', 'Banyak', 'Sering', 'Lebih', 'a', '2026-07-11 00:33:18'),
(245, 5, 'Persamaan makna kata \"Indah\" adalah...', 'Cantik', 'Jelek', 'Kasar', 'Biasa', 'Sederhana', 'a', '2026-07-11 00:33:18'),
(246, 5, 'Lawan kata dari \"Kuat\" adalah...', 'Tangguh', 'Lemah', 'Tegas', 'Kokoh', 'Keras', 'b', '2026-07-11 00:33:18'),
(247, 5, 'Lawan kata dari \"Lancar\" adalah...', 'Lambat', 'Terhambat', 'Cepat', 'Mulus', 'Mudah', 'b', '2026-07-11 00:33:18'),
(248, 5, 'Lawan kata dari \"Maju\" adalah...', 'Berkembang', 'Mundur', 'Tetap', 'Naik', 'Bertambah', 'b', '2026-07-11 00:33:18'),
(249, 5, 'Lawan kata dari \"Nyata\" adalah...', 'Asli', 'Palsu', 'Abstrak', 'Jelas', 'Terbukti', 'c', '2026-07-11 00:33:18'),
(250, 5, 'Lawan kata dari \"Percaya\" adalah...', 'Ragu', 'Yakin', 'Setuju', 'Mengakui', 'Menyetujui', 'a', '2026-07-11 00:33:18'),
(251, 5, 'Lawan kata dari \"Ramah\" adalah...', 'Baik', 'Sopan', 'Dingin', 'Suka', 'Murah senyum', 'c', '2026-07-11 00:33:18'),
(252, 5, 'Lawan kata dari \"Sukses\" adalah...', 'Berhasil', 'Gagal', 'Maju', 'Untung', 'Baik', 'b', '2026-07-11 00:33:18'),
(253, 5, 'Lawan kata dari \"Tinggi\" adalah...', 'Rendah', 'Panjang', 'Lebar', 'Datar', 'Mendatar', 'a', '2026-07-11 00:33:18'),
(254, 5, 'Lawan kata dari \"Utuh\" adalah...', 'Lengkap', 'Terpecah', 'Sempurna', 'Kokoh', 'Baik', 'b', '2026-07-11 00:33:18'),
(255, 5, 'Lawan kata dari \"Zaman dulu\" adalah...', 'Kuno', 'Masa kini', 'Lama', 'Tua', 'Sejarah', 'b', '2026-07-11 00:33:18'),
(256, 5, 'Jika \"Penulis : Buku\", maka \"Pematung : ...\"', 'Pahatan', 'Tanah', 'Pahat', 'Kayu', 'Tukang', 'a', '2026-07-11 00:33:18'),
(257, 5, 'Jika \"Kunci : Pintu\", maka \"Kancing : ...\"', 'Pakaian', 'Gembok', 'Rumah', 'Kotak', 'Laci', 'a', '2026-07-11 00:33:18'),
(258, 5, 'Jika \"Mata : Melihat\", maka \"Telinga : ...\"', 'Mendengar', 'Merasakan', 'Mencium', 'Mengecap', 'Menyentuh', 'a', '2026-07-11 00:33:18'),
(259, 5, 'Jika \"Petani : Sawah\", maka \"Nelayan : ...\"', 'Laut', 'Jala', 'Ikan', 'Kapal', 'Pantai', 'a', '2026-07-11 00:33:18'),
(260, 5, 'Jika \"Kertas : Tulis\", maka \"Papan tulis : ...\"', 'Gambar', 'Tulis', 'Kapur', 'Hapus', 'Baca', 'b', '2026-07-11 00:33:18'),
(261, 5, 'Manakah kata yang tidak termasuk dalam kelompok berikut? Sepatu, Sandal, Topi, Sendal, Kaos kaki', 'Sepatu', 'Sandal', 'Topi', 'Sendal', 'Kaos kaki', 'c', '2026-07-11 00:33:18'),
(262, 5, 'Manakah kata yang berbeda dari yang lain? Merakit, Memperbaiki, Membangun, Merusak, Menyusun', 'Merakit', 'Memperbaiki', 'Membangun', 'Merusak', 'Menyusun', 'd', '2026-07-11 00:33:18'),
(263, 5, 'Susunan kata yang paling logis: 1. Membeli 2. Memasak 3. Makan 4. Bahan makanan', '1-2-3-4', '4-1-2-3', '1-4-2-3', '2-1-4-3', '4-2-1-3', 'b', '2026-07-11 00:33:18'),
(264, 5, 'Manakah kata yang tidak sejenis? Gunung, Lembah, Sungai, Bukit, Dataran tinggi', 'Gunung', 'Lembah', 'Sungai', 'Bukit', 'Dataran tinggi', 'c', '2026-07-11 00:33:18'),
(265, 5, 'Susunan peristiwa yang benar: 1. Membayar 2. Memilih barang 3. Membawa pulang 4. Pergi ke toko', '4-2-1-3', '2-4-1-3', '4-1-2-3', '1-2-4-3', '2-1-3-4', 'a', '2026-07-11 00:33:18'),
(266, 6, 'Berapakah hasil dari 125 + 375 - 225?', '250', '275', '300', '325', '350', 'b', '2026-07-11 00:37:57'),
(267, 6, 'Hasil dari 15% dari 600 adalah...', '75', '80', '90', '100', '120', 'c', '2026-07-11 00:37:57'),
(268, 6, 'Jika 20% dari sebuah bilangan adalah 80, maka bilangan tersebut adalah...', '200', '300', '400', '500', '600', 'c', '2026-07-11 00:37:57'),
(269, 6, 'Berapakah hasil dari 2,5% × 1.200?', '25', '30', '35', '40', '45', 'b', '2026-07-11 00:37:57'),
(270, 6, 'Harga barang semula Rp400.000, mendapat diskon 15%. Harga yang harus dibayar adalah...', 'Rp320.000', 'Rp330.000', 'Rp340.000', 'Rp350.000', 'Rp360.000', 'b', '2026-07-11 00:37:57'),
(271, 6, 'Jika harga barang naik 20% menjadi Rp600.000, maka harga semulanya adalah...', 'Rp450.000', 'Rp480.000', 'Rp500.000', 'Rp520.000', 'Rp550.000', 'c', '2026-07-11 00:37:57'),
(272, 6, 'Berapakah hasil dari 3/8 dari 480?', '150', '160', '170', '180', '200', 'd', '2026-07-11 00:37:57'),
(273, 6, 'Manakah nilai yang paling besar?', '0,62', '61%', '3/5', '5/8', '0,59', 'd', '2026-07-11 00:37:57');
INSERT INTO `questions` (`id`, `test_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `answer`, `created_at`) VALUES
(274, 6, 'Hasil dari √225 + √144 adalah...', '25', '27', '29', '31', '33', 'b', '2026-07-11 00:37:57'),
(275, 6, 'Berapakah nilai dari 12 × 15 ÷ 9 + 25?', '35', '40', '45', '50', '55', 'c', '2026-07-11 00:37:57'),
(276, 6, 'Angka selanjutnya dari pola: 2, 6, 12, 20, 30, ... adalah...', '40', '42', '44', '46', '48', 'b', '2026-07-11 00:37:57'),
(277, 6, 'Pola: 4, 9, 19, 39, 79, ... Angka berikutnya adalah...', '119', '139', '159', '179', '199', 'c', '2026-07-11 00:37:57'),
(278, 6, 'Angka yang hilang: 3, 5, 9, 15, ?, 33', '21', '23', '25', '27', '29', 'b', '2026-07-11 00:37:57'),
(279, 6, 'Pola: 1, 3, 7, 15, 31, ... Angka ke-7 adalah...', '63', '95', '127', '159', '191', 'c', '2026-07-11 00:37:57'),
(280, 6, 'Urutan: 100, 95, 85, 70, 50, ... Angka selanjutnya adalah...', '25', '30', '35', '40', '45', 'a', '2026-07-11 00:37:57'),
(281, 6, 'Jika 2 → 8, 3 → 27, 4 → 64, maka 5 → ?', '100', '125', '150', '175', '200', 'b', '2026-07-11 00:37:57'),
(282, 6, 'Angka yang cocok: 7, 14, 28, 56, ?, 224', '98', '104', '112', '126', '140', 'c', '2026-07-11 00:37:57'),
(283, 6, 'Pola: 121, 100, 81, 64, ... Angka berikutnya adalah...', '36', '40', '42', '45', '49', 'e', '2026-07-11 00:37:57'),
(284, 6, 'Rasio uang A dan B adalah 3 : 4. Jika jumlah uang mereka Rp1.400.000, maka uang B adalah...', 'Rp500.000', 'Rp600.000', 'Rp700.000', 'Rp800.000', 'Rp900.000', 'd', '2026-07-11 00:37:57'),
(285, 6, 'Jika 6 orang dapat menyelesaikan pekerjaan dalam 8 hari, maka 12 orang dapat menyelesaikannya dalam waktu...', '3 hari', '4 hari', '5 hari', '6 hari', '7 hari', 'b', '2026-07-11 00:37:57'),
(286, 6, 'Sebuah mobil melaju dengan kecepatan 75 km/jam selama 2 jam 20 menit. Jarak yang ditempuh adalah...', '150 km', '160 km', '170 km', '175 km', '180 km', 'e', '2026-07-11 00:37:57'),
(287, 6, 'Jika x + 2y = 20 dan y = 5, maka nilai 3x adalah...', '10', '15', '20', '25', '30', 'e', '2026-07-11 00:37:57'),
(288, 6, 'Sebuah persegi panjang memiliki keliling 90 cm dan perbandingan panjang : lebar = 3 : 2. Luasnya adalah...', '324 cm²', '360 cm²', '384 cm²', '400 cm²', '432 cm²', 'b', '2026-07-11 00:37:57'),
(289, 6, 'Harga 8 buku adalah Rp120.000. Maka harga 15 buku yang sama adalah...', 'Rp200.000', 'Rp215.000', 'Rp225.000', 'Rp240.000', 'Rp250.000', 'c', '2026-07-11 00:37:57'),
(290, 6, 'Sebuah tabung memiliki jari-jari 7 cm dan tinggi 10 cm. Volumenya adalah... (π = 22/7)', '1.440 cm³', '1.540 cm³', '1.640 cm³', '1.740 cm³', '1.840 cm³', 'b', '2026-07-11 00:37:57'),
(291, 6, 'Jika hari ini hari Senin, maka 90 hari lagi adalah hari...', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu', 'c', '2026-07-11 00:37:57'),
(292, 6, 'Rasio umur Ayah dan Anak adalah 7 : 2. Jika selisih umur mereka 25 tahun, maka umur Ayah sekarang adalah...', '30 tahun', '35 tahun', '40 tahun', '45 tahun', '50 tahun', 'b', '2026-07-11 00:37:57'),
(293, 6, 'Sebuah tangki terisi penuh dalam waktu 12 jam menggunakan 2 keran. Jika dipakai 3 keran, waktu yang dibutuhkan adalah...', '6 jam', '7 jam', '8 jam', '9 jam', '10 jam', 'c', '2026-07-11 00:37:57'),
(294, 6, 'Hasil penjualan sebuah toko naik 10% pada bulan pertama, lalu naik lagi 10% pada bulan kedua. Jika awalnya Rp10.000.000, maka hasil akhirnya adalah...', 'Rp11.500.000', 'Rp11.800.000', 'Rp12.000.000', 'Rp12.100.000', 'Rp12.500.000', 'd', '2026-07-11 00:37:57'),
(295, 6, 'Jika 5 pekerja dapat menyelesaikan pekerjaan dalam 16 hari, maka agar selesai dalam 10 hari dibutuhkan pekerja sebanyak...', '7 orang', '8 orang', '9 orang', '10 orang', '12 orang', 'b', '2026-07-11 00:37:57'),
(296, 7, 'Sebuah kubus dilihat dari arah depan, bentuk tampakannya adalah...', 'Persegi', 'Persegi panjang', 'Segitiga', 'Lingkaran', 'Jajar genjang', 'a', '2026-07-11 00:39:24'),
(297, 7, 'Sebuah tabung berdiri tegak dilihat dari arah samping, bentuk tampakannya adalah...', 'Lingkaran', 'Persegi', 'Persegi panjang', 'Segitiga', 'Trapesium', 'c', '2026-07-11 00:39:24'),
(298, 7, 'Jika sebuah kerucut dilihat dari arah atas, bentuk tampakannya adalah...', 'Segitiga', 'Lingkaran dengan titik di tengah', 'Persegi', 'Setengah lingkaran', 'Oval', 'b', '2026-07-11 00:39:24'),
(299, 7, 'Sebuah bola dilihat dari segala arah, bentuk tampakannya selalu...', 'Persegi', 'Segitiga', 'Lingkaran', 'Oval', 'Tidak beraturan', 'c', '2026-07-11 00:39:24'),
(300, 7, 'Sebuah balok dengan ukuran panjang 10 cm, lebar 5 cm, dan tinggi 5 cm. Jika dilihat dari arah samping kanan, bentuk yang terlihat adalah...', 'Persegi berukuran 5 × 5 cm', 'Persegi panjang 10 × 5 cm', 'Persegi panjang 10 × 10 cm', 'Segitiga', 'Lingkaran', 'a', '2026-07-11 00:39:24'),
(301, 7, 'Sebuah limas persegi dilihat dari arah depan, bentuk yang terlihat adalah...', 'Persegi', 'Segitiga sama kaki', 'Persegi panjang', 'Segitiga siku-siku', 'Jajar genjang', 'b', '2026-07-11 00:39:24'),
(302, 7, 'Sebuah prisma segitiga dilihat dari arah atas, bentuk yang terlihat adalah...', 'Segitiga', 'Persegi', 'Persegi panjang', 'Trapesium', 'Segi empat', 'c', '2026-07-11 00:39:24'),
(303, 7, 'Jika jaring-jaring berbentuk 6 persegi sama besar disusun dan dilipat, maka akan membentuk bangun ruang...', 'Balok', 'Kubus', 'Prisma', 'Limas', 'Tabung', 'b', '2026-07-11 00:39:24'),
(304, 7, 'Jaring-jaring yang terdiri dari 1 persegi dan 4 segitiga sama kaki jika dilipat akan menjadi bangun ruang...', 'Kubus', 'Balok', 'Limas persegi', 'Prisma segitiga', 'Kerucut', 'c', '2026-07-11 00:39:24'),
(305, 7, 'Jaring-jaring yang terdiri dari 2 lingkaran sama besar dan 1 persegi panjang jika disusun akan membentuk bangun ruang...', 'Kerucut', 'Tabung', 'Bola', 'Limas', 'Prisma', 'b', '2026-07-11 00:39:24'),
(306, 7, 'Jika sebuah kertas berbentuk persegi dilipat menjadi dua bagian sama besar secara vertikal, maka bentuk yang terbentuk adalah...', 'Persegi', 'Persegi panjang', 'Segitiga', 'Trapesium', 'Jajar genjang', 'b', '2026-07-11 00:39:24'),
(307, 7, 'Sebuah kertas berbentuk persegi panjang dilipat sehingga kedua ujungnya bertemu, maka bentuk yang terbentuk adalah...', 'Dua persegi panjang lebih kecil', 'Dua persegi', 'Dua segitiga', 'Dua lingkaran', 'Dua jajar genjang', 'a', '2026-07-11 00:39:24'),
(308, 7, 'Jika jaring-jaring kerucut dilipat, bagian yang menjadi alasnya adalah...', 'Segitiga', 'Lingkaran', 'Persegi', 'Persegi panjang', 'Setengah lingkaran', 'b', '2026-07-11 00:39:24'),
(309, 7, 'Berapa banyak sisi yang dimiliki oleh sebuah kubus?', '4 sisi', '5 sisi', '6 sisi', '8 sisi', '12 sisi', 'c', '2026-07-11 00:39:24'),
(310, 7, 'Jika Anda menghadap ke arah Utara, lalu berputar ke kanan sejauh 90 derajat, maka Anda sekarang menghadap ke arah...', 'Barat', 'Timur', 'Selatan', 'Tenggara', 'Barat Laut', 'b', '2026-07-11 00:39:24'),
(311, 7, 'Jika Anda menghadap ke arah Barat, lalu berputar ke kiri sejauh 180 derajat, maka arah hadap Anda sekarang adalah...', 'Timur', 'Barat', 'Utara', 'Selatan', 'Tenggara', 'a', '2026-07-11 00:39:24'),
(312, 7, 'Arah yang berada di antara Utara dan Timur disebut...', 'Barat Laut', 'Tenggara', 'Timur Laut', 'Barat Daya', 'Selatan', 'c', '2026-07-11 00:39:24'),
(313, 7, 'Benda A berada di sebelah kanan benda B, dan benda B berada di sebelah kanan benda C. Maka posisi benda C berada di sebelah...', 'Kiri benda A', 'Kanan benda A', 'Depan benda A', 'Belakang benda A', 'Sama lurus dengan benda A', 'a', '2026-07-11 00:39:24'),
(314, 7, 'Sebuah titik berada 5 satuan ke timur dan 3 satuan ke utara dari titik awal. Jika dipindahkan 3 satuan ke barat dan 2 satuan ke selatan, maka posisinya sekarang berada...', '2 satuan ke timur dan 1 satuan ke utara', '2 satuan ke barat dan 1 satuan ke selatan', '5 satuan ke timur dan 3 satuan ke utara', 'Tepat di titik awal', '3 satuan ke timur dan 2 satuan ke utara', 'a', '2026-07-11 00:39:24'),
(315, 7, 'Jika sebuah kotak diletakkan di atas meja, dan sebuah buku diletakkan di atas kotak, maka posisi meja berada...', 'Di atas kotak', 'Di bawah kotak', 'Di atas buku', 'Di samping kotak', 'Di depan kotak', 'b', '2026-07-11 00:39:24'),
(316, 8, 'Anda sedang mengerjakan tugas penting yang harus selesai hari ini, tiba-tiba rekan kerja meminta bantuan menyelesaikan pekerjaannya. Sikap Anda adalah...', 'Menolak karena sedang sibuk', 'Membantu sebentar saja lalu segera kembali ke tugas sendiri', 'Menghentikan tugas sendiri dan membantu sepenuhnya', 'Menyuruhnya meminta bantuan orang lain', 'Membantu setelah tugas Anda selesai dengan baik', 'e', '2026-07-11 00:43:15'),
(317, 8, 'Atasan mengubah aturan kerja yang sudah biasa Anda jalani. Sikap Anda adalah...', 'Merasa terganggu dan enggan mengikuti aturan baru', 'Mempelajari perubahan tersebut dan menyesuaikan diri sebaik mungkin', 'Mengikuti saja tanpa memahami alasannya', 'Menyalahkan keputusan atasan', 'Berusaha bekerja seperti biasa saja', 'b', '2026-07-11 00:43:15'),
(318, 8, 'Dalam rapat kelompok, pendapat Anda berbeda dengan pendapat mayoritas anggota. Sikap Anda adalah...', 'Memaksakan pendapat agar diterima semua orang', 'Mendengarkan dengan baik, lalu menyampaikan alasan pendapat Anda dengan sopan', 'Diam saja dan mengikuti pendapat mayoritas', 'Marah karena pendapat tidak didengar', 'Keluar dari ruangan rapat', 'b', '2026-07-11 00:43:15'),
(319, 8, 'Anda menemukan kesalahan kecil dalam laporan yang sudah diserahkan ke atasan. Sikap Anda adalah...', 'Membiarkannya saja karena tidak terlalu berpengaruh', 'Segera melaporkan dan meminta izin untuk memperbaikinya', 'Menyalahkan orang lain yang membantu mengerjakan', 'Menyembunyikan kesalahan tersebut', 'Menunggu sampai atasan menanyakannya', 'b', '2026-07-11 00:43:15'),
(320, 8, 'Pekerjaan Anda menumpuk dan waktunya sangat terbatas. Sikap Anda adalah...', 'Merasa panik dan bingung harus mulai dari mana', 'Membagi tugas menjadi bagian kecil dan menyelesaikannya secara bertahap', 'Mengeluh dan meminta perpanjangan waktu', 'Mengerjakan semuanya sekaligus agar cepat selesai', 'Menunda-nunda sampai mendekati batas waktu', 'b', '2026-07-11 00:43:15'),
(321, 8, 'Atasan memberikan tugas tambahan yang bukan bagian dari tanggung jawab Anda. Sikap Anda adalah...', 'Menolak dengan alasan sudah punya tugas sendiri', 'Menerima dan berusaha menyelesaikannya dengan sebaik mungkin', 'Menerima tapi mengerjakan dengan asal-asalan', 'Menerima tapi terus mengeluh', 'Meminta imbalan tambahan terlebih dahulu', 'b', '2026-07-11 00:43:15'),
(322, 8, 'Anda melihat rekan kerja melakukan kesalahan yang bisa mengganggu hasil kerja tim. Sikap Anda adalah...', 'Membiarkan saja agar tidak menyinggung perasaannya', 'Mengingatkan dengan bahasa yang sopan dan memberikan solusi', 'Langsung melaporkan ke atasan tanpa bicara dulu', 'Membicarakan kesalahannya ke rekan lain', 'Menertawakan kesalahan tersebut', 'b', '2026-07-11 00:43:15'),
(323, 8, 'Dalam menyelesaikan pekerjaan, hal yang paling Anda utamakan adalah...', 'Kecepatan selesai meski hasilnya kurang rapi', 'Ketepatan, kualitas, dan sesuai standar yang ditetapkan', 'Mengerjakan seminimal mungkin agar tidak lelah', 'Menyelesaikan hanya jika diawasi', 'Menyelesaikan dengan bantuan orang lain', 'b', '2026-07-11 00:43:15'),
(324, 8, 'Anda menerima kritik dari atasan mengenai hasil kerja Anda. Sikap Anda adalah...', 'Marah dan membela diri sekuat tenaga', 'Mendengarkan dengan tenang, memahami maksudnya, dan menjadikannya perbaikan', 'Mengabaikan dan menganggap kritik itu tidak adil', 'Membalas dengan kritik juga', 'Berpura-pura setuju tapi tidak mengubah cara kerja', 'b', '2026-07-11 00:43:15'),
(325, 8, 'Ada kesempatan untuk mengikuti pelatihan guna meningkatkan kemampuan kerja. Sikap Anda adalah...', 'Tidak tertarik karena sudah merasa cukup mampu', 'Mengikuti jika tidak mengganggu waktu istirahat', 'Mengikuti dengan antusias untuk menambah pengetahuan dan keterampilan', 'Mengikuti hanya jika disuruh atasan', 'Menolak karena takut materi yang diajarkan sulit', 'c', '2026-07-11 00:43:15'),
(326, 8, 'Anda mengetahui informasi penting yang harus diketahui seluruh anggota tim. Sikap Anda adalah...', 'Menyampaikan segera kepada semua anggota tim', 'Menyampaikan hanya kepada orang yang dianggap perlu saja', 'Menyimpan sendiri sampai ada yang menanyakan', 'Menyampaikan secara bertahap agar tidak terburu-buru', 'Menyampaikan hanya jika ada waktu luang', 'a', '2026-07-11 00:43:15'),
(327, 8, 'Anda harus bekerja sama dengan rekan yang memiliki sifat dan cara kerja sangat berbeda dari Anda. Sikap Anda adalah...', 'Menghindari kerjasama dan mencari alasan lain', 'Tetap bekerja sama, menghargai perbedaan, dan fokus pada tujuan bersama', 'Bekerja sama tapi hanya sebatas yang diperlukan', 'Membicarakan keburukannya ke rekan lain', 'Meminta dipindahkan ke tim lain', 'b', '2026-07-11 00:43:15'),
(328, 8, 'Pekerjaan yang Anda kerjakan mengalami kegagalan dan mempengaruhi hasil tim. Sikap Anda adalah...', 'Menyalahkan keadaan atau faktor lain di luar kendali', 'Mengakui tanggung jawab, mencari penyebabnya, dan berusaha memperbaiki di kesempatan berikutnya', 'Diam saja dan berharap tidak ada yang menanyakan', 'Merasa sedih berkepanjangan sampai mengganggu pekerjaan lain', 'Menganggap itu sebagai takdir yang tidak bisa diubah', 'b', '2026-07-11 00:43:15'),
(329, 8, 'Anda diberi kepercayaan untuk memimpin kelompok. Sikap Anda adalah...', 'Mengerjakan semuanya sendiri agar hasilnya lebih cepat dan sesuai keinginan', 'Membagi tugas sesuai kemampuan masing-masing anggota dan mengawasi jalannya pekerjaan', 'Menyuruh anggota mengerjakan semua tugas tanpa arahan', 'Membiarkan anggota bekerja sendiri tanpa koordinasi', 'Memilih hanya anggota yang dekat dengan Anda untuk diajak bekerja', 'b', '2026-07-11 00:43:15'),
(330, 8, 'Rekan kerja meminta pendapat Anda mengenai cara menyelesaikan tugasnya. Sikap Anda adalah...', 'Menjawab sekenanya saja agar tidak ribet', 'Mendengarkan masalahnya, memberikan saran yang logis, dan mendukung sebisa mungkin', 'Menyarankan untuk bertanya langsung ke atasan', 'Mengatakan tidak tahu apa-apa', 'Menyuruhnya mencari contoh pekerjaan orang lain', 'b', '2026-07-11 00:43:15'),
(331, 8, 'Anda melihat ada prosedur kerja yang sudah usang dan bisa diperbaiki agar lebih efisien. Sikap Anda adalah...', 'Tetap mengikuti prosedur yang ada saja', 'Mencatat kekurangannya, menyusun usulan perbaikan, lalu menyampaikannya ke atasan dengan sopan', 'Mengubahnya sendiri tanpa sepengetahuan atasan', 'Membicarakan kekurangannya saja tanpa memberikan solusi', 'Menganggap itu bukan urusan Anda', 'b', '2026-07-11 00:43:15'),
(332, 8, 'Saat bekerja, Anda menemukan kendala yang menghambat penyelesaian tugas. Sikap Anda adalah...', 'Berhenti bekerja dan menunggu sampai kendala itu hilang sendiri', 'Mencari cara mengatasinya atau melaporkan segera agar tidak menunda pekerjaan', 'Mengeluh terus-menerus kepada rekan kerja', 'Menyalahkan pihak yang dianggap menyebabkan kendala', 'Menyelesaikan dengan cara sendiri meski tidak sesuai aturan', 'b', '2026-07-11 00:43:15'),
(333, 8, 'Anda diberi tugas yang belum pernah Anda kerjakan sebelumnya. Sikap Anda adalah...', 'Menolak karena takut membuat kesalahan', 'Menerima, mencari informasi yang dibutuhkan, dan mempelajari cara mengerjakannya dengan sungguh-sungguh', 'Menerima tapi mengerjakan seadanya saja', 'Menerima dan meminta orang lain mengerjakannya untuk Anda', 'Menerima tapi terus bertanya hal yang sama berulang kali', 'b', '2026-07-11 00:43:15'),
(334, 8, 'Ada kesalahpahaman antara Anda dan rekan kerja. Sikap Anda adalah...', 'Mendiamkannya sampai rekan itu yang meminta maaf lebih dulu', 'Mengajak bicara secara pribadi untuk menyelesaikan masalah dengan kepala dingin', 'Membalas dengan sikap yang sama dinginnya', 'Menceritakan masalah itu ke rekan lain agar didukung', 'Melaporkan langsung ke atasan tanpa bicara dulu', 'b', '2026-07-11 00:43:15'),
(335, 8, 'Anda mendapatkan pujian atas hasil kerja yang baik. Sikap Anda adalah...', 'Merasa paling hebat dan membanggakan diri ke semua orang', 'Mengucapkan terima kasih, tetap rendah hati, dan berusaha mempertahankan kinerja tersebut', 'Menganggap itu hal yang biasa saja dan tidak perlu ditanggapi', 'Meminta pujian lebih banyak lagi', 'Mengatakan bahwa hasil itu bisa lebih baik lagi', 'b', '2026-07-11 00:43:15'),
(336, 8, 'Dalam tim kerja, ada anggota yang kurang aktif dan tidak mau berkontribusi. Sikap Anda adalah...', 'Membiarkannya saja dan mengerjakan tugasnya sendiri', 'Mendekatinya, mengajak berpartisipasi, dan memberikan tugas yang sesuai dengan kemampuannya', 'Membicarakan kelemahannya ke anggota tim lain', 'Melaporkan ke atasan tanpa memberi kesempatan', 'Mengabaikan keberadaannya sepenuhnya', 'b', '2026-07-11 00:43:15'),
(337, 8, 'Anda diminta membantu pekerjaan rekan kerja padahal Anda juga sedang sibuk. Sikap Anda adalah...', 'Menolak dengan tegas tanpa alasan', 'Mengatur jadwal terlebih dahulu, membantu sebisa mungkin setelah tugas utama Anda aman', 'Membantu tapi sambil terus mengeluh', 'Membantu tapi asal mengerjakannya saja', 'Menyuruhnya mencari bantuan ke orang lain', 'b', '2026-07-11 00:43:15'),
(338, 8, 'Atasan menilai hasil kerja Anda kurang memuaskan padahal menurut Anda sudah cukup baik. Sikap Anda adalah...', 'Membela diri dan menyangkal semua penilaiannya', 'Mendengarkan penjelasannya, memahami kekurangannya, dan berusaha memperbaiki di kesempatan berikutnya', 'Menganggap atasan tidak adil dan memihak orang lain', 'Berhenti bekerja sebaik dulu karena merasa tidak dihargai', 'Menyampaikan ketidakpuasan Anda ke rekan lain', 'b', '2026-07-11 00:43:15'),
(339, 8, 'Anda melihat ada rekan kerja yang melakukan pelanggaran aturan kantor yang cukup ringan. Sikap Anda adalah...', 'Mengingatkan dengan cara yang baik agar tidak terulang lagi', 'Membiarkannya saja karena tidak mengganggu Anda', 'Langsung melaporkan ke atasan tanpa bicara dulu', 'Membicarakan hal itu ke banyak orang', 'Menegur dengan nada tinggi dan kasar', 'a', '2026-07-11 00:43:15'),
(340, 8, 'Dalam menyelesaikan pekerjaan, Anda lebih menyukai cara yang...', 'Cepat meski ada risiko kesalahan', 'Tertata, sistematis, dan meminimalkan kesalahan', 'Mudah dan tidak membutuhkan banyak tenaga', 'Bebas tanpa aturan yang mengikat', 'Mengikuti kebiasaan orang lain saja', 'b', '2026-07-11 00:43:15'),
(341, 8, 'Anda diminta memberikan masukan untuk perbaikan kinerja tim. Sikap Anda adalah...', 'Diam saja karena takut menyinggung orang lain', 'Menyampaikan masukan secara jujur, objektif, dan disertai alasan yang membangun', 'Hanya menyampaikan hal-hal yang baik saja', 'Menyampaikan kekurangan saja tanpa solusi', 'Mengatakan semuanya sudah baik-baik saja', 'b', '2026-07-11 00:43:15'),
(342, 8, 'Saat bekerja dalam tim, Anda merasa pendapat Anda lebih baik dari pendapat orang lain. Sikap Anda adalah...', 'Memaksakan pendapat Anda agar diterima', 'Menyampaikan pendapat Anda dengan sopan, mendengarkan pendapat lain, lalu mencari kesepakatan terbaik', 'Diam saja dan membiarkan keputusan diambil orang lain', 'Menyindir pendapat orang lain yang dianggap kurang tepat', 'Keluar dari diskusi jika tidak diikuti', 'b', '2026-07-11 00:43:15'),
(343, 8, 'Anda diberi tugas dengan batas waktu yang sangat singkat. Sikap Anda adalah...', 'Mengeluh dan menyatakan bahwa tugas itu mustahil diselesaikan', 'Menyusun rencana kerja, membagi waktu, dan berusaha menyelesaikannya sebaik mungkin sesuai waktu yang ada', 'Menerima tapi mengerjakan setengahnya saja', 'Meminta orang lain mengerjakan sebagian tugas Anda', 'Menunda-nunda sampai waktu hampir habis', 'b', '2026-07-11 00:43:15'),
(344, 8, 'Jika ada kesalahan yang terjadi dalam pekerjaan tim dan Anda ikut bertanggung jawab, sikap Anda adalah...', 'Menyalahkan anggota lain yang dianggap lebih salah', 'Mengakui kesalahan bersama dan membantu mencari solusi perbaikannya', 'Membela diri agar tidak dipersalahkan', 'Membiarkan ketua tim yang bertanggung jawab sepenuhnya', 'Mengalihkan pembicaraan ke hal lain', 'b', '2026-07-11 00:43:15'),
(345, 8, 'Anda melihat ada kesalahan kecil dalam laporan yang dibuat rekan kerja. Sikap Anda adalah...', 'Menyampaikan dengan halus agar dia bisa memperbaikinya', 'Membiarkannya saja karena itu urusannya sendiri', 'Memperbaikinya sendiri tanpa memberi tahu dia', 'Membicarakan kesalahan itu ke rekan kerja lain', 'Menunggu sampai dia menyadari sendiri', 'a', '2026-07-11 00:43:15'),
(346, 8, 'Saat bekerja dengan rekan yang cara kerjanya lebih lambat dari Anda. Sikap Anda adalah...', 'Menyuruhnya bekerja lebih cepat dengan nada tegas', 'Membantu dan mengajari cara yang lebih efisien agar pekerjaan selesai tepat waktu', 'Mengerjakan bagian tugasnya sendiri saja', 'Mengeluh kepada atasan agar dia diganti', 'Menjauh agar tidak terhambat pekerjaannya', 'b', '2026-07-11 00:43:15'),
(347, 8, 'Anda menerima tugas yang dianggap sulit dan berat oleh rekan kerja lain. Sikap Anda adalah...', 'Menolak karena takut tidak sanggup menyelesaikannya', 'Menerima dengan penuh tanggung jawab dan berusaha menyelesaikannya sebaik mungkin', 'Menerima tapi meminta bantuan terus-menerus', 'Menerima tapi mengerjakan hanya sebagian saja', 'Menerima tapi meminta imbalan tambahan', 'b', '2026-07-11 00:43:15'),
(348, 8, 'Jika pekerjaan Anda terhambat karena masalah di luar kendali Anda. Sikap Anda adalah...', 'Segera melaporkan ke atasan dan menyampaikan usulan solusi', 'Menunggu sampai masalah itu selesai dengan sendirinya', 'Mengeluh dan menyalahkan keadaan', 'Membiarkan pekerjaan tertunda tanpa kabar', 'Mencari cara sendiri meski melanggar aturan', 'a', '2026-07-11 00:43:15'),
(349, 8, 'Dalam rapat kerja, Anda diberi kesempatan untuk menyampaikan pendapat. Sikap Anda adalah...', 'Berbicara sepanjang mungkin agar didengar semua orang', 'Menyampaikan pendapat secara singkat, jelas, dan berhubungan dengan topik', 'Diam saja dan tidak berani bicara', 'Hanya menyampaikan pendapat jika disetujui teman dekat', 'Menyampaikan hal yang sama seperti orang lain agar aman', 'b', '2026-07-11 00:43:15'),
(350, 8, 'Anda melihat ada rekan kerja yang mendapatkan beban tugas lebih banyak dari biasanya. Sikap Anda adalah...', 'Membantu sebisa mungkin jika tugas Anda sudah selesai', 'Membiarkannya saja karena itu urusan dia', 'Mengatakan bahwa itu tidak adil ke atasan', 'Menyuruhnya mengeluh saja', 'Menganggap itu sudah menjadi haknya', 'a', '2026-07-11 00:43:15'),
(351, 8, 'Anda diminta menjadi pengganti sementara untuk tugas yang belum pernah Anda jalani. Sikap Anda adalah...', 'Menerima dan berusaha mempelajari serta melaksanakannya dengan baik', 'Menolak karena merasa tidak mampu', 'Menerima tapi mengerjakan seadanya saja', 'Menerima tapi meminta orang lain mengawasi terus', 'Menerima tapi terus mengeluh kesulitannya', 'a', '2026-07-11 00:43:15'),
(352, 8, 'Jika hasil kerja Anda dibandingkan dengan rekan lain yang lebih baik dari Anda. Sikap Anda adalah...', 'Merasa iri dan berusaha menjatuhkan nama baiknya', 'Menjadikan itu motivasi untuk meningkatkan kualitas kerja Anda sendiri', 'Merasa rendah diri dan kehilangan semangat kerja', 'Menganggap dia lebih beruntung saja', 'Membandingkan kelebihan diri Anda yang lain', 'b', '2026-07-11 00:43:15'),
(353, 8, 'Anda harus bekerja di luar jam kerja karena ada tugas mendesak. Sikap Anda adalah...', 'Menerima dan menyelesaikannya dengan tanggung jawab', 'Menolak karena itu waktu istirahat Anda', 'Menerima tapi mengerjakan dengan lambat', 'Menerima tapi terus mengeluh lelah', 'Menerima tapi meminta bayaran tambahan secara paksa', 'a', '2026-07-11 00:43:15'),
(354, 8, 'Dalam menyelesaikan tugas, Anda selalu berusaha...', 'Menyelesaikan tepat waktu dan sesuai standar yang ditetapkan', 'Menyelesaikan secepat mungkin meski kurang teliti', 'Menyelesaikan mendekati batas waktu saja', 'Menyelesaikan hanya jika diawasi', 'Menyelesaikan seminimal mungkin agar cepat selesai', 'a', '2026-07-11 00:43:15'),
(355, 8, 'Anda melihat ada kebijakan baru yang menurut Anda kurang tepat. Sikap Anda adalah...', 'Menyampaikan pendapat dan usulan perbaikan secara sopan dan tertulis', 'Membicarakan kekurangannya ke rekan kerja saja', 'Menolak mengikuti kebijakan itu', 'Mengikuti saja tanpa mempedulikan isinya', 'Menyalahkan pembuat kebijakan tersebut', 'a', '2026-07-11 00:43:15'),
(356, 8, 'Jika ada kesempatan untuk mengembangkan keterampilan kerja, Anda akan...', 'Mengikuti dengan antusias untuk meningkatkan kinerja', 'Mengikuti jika tidak mengganggu waktu pribadi', 'Tidak tertarik karena merasa sudah cukup', 'Mengikuti hanya jika disuruh atasan', 'Mengikuti tapi tidak serius', 'a', '2026-07-11 00:43:15'),
(357, 8, 'Saat ada kesalahpahaman dalam tim, Anda akan...', 'Mengajak berdiskusi untuk menyelesaikannya dengan kepala dingin', 'Mendiamkan saja sampai rekan lain yang menyelesaikannya', 'Membela diri sendiri saja', 'Mengadu ke atasan agar diputuskan', 'Membiarkan berlarut-larut', 'a', '2026-07-11 00:43:15'),
(358, 8, 'Anda melihat ada rekan kerja yang membutuhkan bantuan namun tidak meminta. Sikap Anda adalah...', 'Mendekati dan menawarkan bantuan dengan sopan', 'Membiarkannya saja sampai dia meminta sendiri', 'Menganggap dia mampu mengerjakannya sendiri', 'Menyuruhnya meminta bantuan jika butuh', 'Memperhatikan saja tanpa bertindak', 'a', '2026-07-11 00:43:15'),
(359, 8, 'Dalam bekerja, prinsip yang paling Anda pegang adalah...', 'Hasil kerja yang baik dan bermanfaat bagi banyak pihak', 'Menyelesaikan tugas dengan cepat tanpa kesulitan', 'Menghindari pekerjaan yang terlalu berat', 'Bekerja sesuai keinginan sendiri', 'Mendapatkan pujian sebanyak-banyaknya', 'a', '2026-07-11 00:43:15'),
(360, 8, 'Jika Anda membuat kesalahan yang mengganggu kinerja tim. Sikap Anda adalah...', 'Mengakui kesalahan, meminta maaf, dan berusaha memperbaikinya', 'Menyembunyikan agar tidak diketahui orang lain', 'Menyalahkan keadaan atau orang lain', 'Membiarkan saja semoga tidak ketahuan', 'Mengalihkan perhatian ke hal lain', 'a', '2026-07-11 00:43:15');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `duration` int NOT NULL COMMENT 'Durasi dalam menit',
  `total_questions` int NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('active','inactive','draft') NOT NULL DEFAULT 'draft',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `title`, `description`, `duration`, `total_questions`, `price`, `status`, `created_at`) VALUES
(1, 'Tes Kemampuan Dasar (TKD)', 'Tes mengukur kemampuan logika, numerik, dan verbal untuk kebutuhan seleksi umum.', 90, 50, 0.00, 'active', '2026-07-11 00:09:09'),
(2, 'Tes SKD CPNS', 'Tes standar seleksi CPNS meliputi TWK, TIU, dan TKP. Durasi resmi 100 menit sesuai ketentuan BKN.', 100, 120, 0.00, 'active', '2026-07-11 00:09:09'),
(3, 'Tes Potensi Akademik (TPA)', 'Tes untuk mengukur kemampuan penalaran, analisis, dan pemahaman umum.', 75, 40, 15000.00, 'active', '2026-07-11 00:09:09'),
(4, 'Tes Logika Berpikir', 'Tes mengukur kemampuan menarik kesimpulan dan menganalisis pola pikir.', 45, 25, 10000.00, 'active', '2026-07-11 00:09:09'),
(5, 'Tes Kemampuan Verbal', 'Tes mengukur penguasaan kosakata, pemahaman bacaan, dan tata bahasa.', 40, 30, 10000.00, 'active', '2026-07-11 00:09:09'),
(6, 'Tes Kemampuan Numerik', 'Tes mengukur kemampuan berhitung cepat, memahami data, dan pola angka.', 50, 30, 12000.00, 'active', '2026-07-11 00:09:09'),
(7, 'Tes Kemampuan Spasial', 'Tes mengukur kemampuan membayangkan bentuk, ruang, dan posisi benda.', 35, 20, 8000.00, 'active', '2026-07-11 00:09:09'),
(8, 'Tes Karakteristik Pribadi', 'Tes untuk melihat sifat, sikap, dan perilaku dalam lingkungan kerja atau sosial.', 60, 45, 0.00, 'active', '2026-07-11 00:09:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','peserta') DEFAULT 'peserta',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_pic` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `status`, `created_at`, `profile_pic`) VALUES
(1, 'users', 'users@gmail.com', '$2y$10$JMM6yUBBUANsrDMBiV4aSuex3PXl0nVaKhA6DJnZ.7IpryUCkTbKu', 'peserta', 'active', '2026-07-08 01:40:02', '1783824068_Foto_Non_Jas.png'),
(2, 'Administrator', 'admin@gmail.com', '$2y$10$ELL.JTgG2GUwSXiSrdBTnuZTusVBdtSSgkcVH06boxaCT6zh6.iUe', 'admin', 'active', '2026-07-09 03:45:03', '1783751633_Foto_Jas.png'),
(3, 'users2', 'users2@gmail.com', '$2y$10$yougkrd/Nldxs1SYROpSN.1sJtUYYMge8k/iESKLkBqfmXT.acdp2', 'peserta', 'active', '2026-07-11 01:42:58', 'default.png'),
(4, 'Dharma', 'dharma@gmail.com', '$2y$10$pCMetasZzTTZ.4X9Pblil.xAa8QlR4Xg5tZqfXSaUZCEcam/steJS', 'peserta', 'active', '2026-07-12 02:46:42', '1783827166_Foto_Jas.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `id` int NOT NULL,
  `user_test_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer` char(1) DEFAULT NULL COMMENT 'Jawaban yang dipilih: a/b/c/d/e',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tests`
--

CREATE TABLE `user_tests` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `test_id` int NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` enum('pending','on_progress','completed','expired') NOT NULL DEFAULT 'pending',
  `score` decimal(5,2) DEFAULT NULL,
  `correct_answers` int DEFAULT '0',
  `wrong_answers` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `remaining_time` int DEFAULT NULL COMMENT 'Waktu tersisa dalam detik',
  `payment_status` enum('unpaid','pending','paid','rejected') NOT NULL DEFAULT 'unpaid',
  `payment_proof` varchar(255) DEFAULT NULL COMMENT 'Nama file bukti pembayaran',
  `payment_date` datetime DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `verified_by` int DEFAULT NULL COMMENT 'ID admin yang memverifikasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_question` (`user_test_id`,`question_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `user_tests`
--
ALTER TABLE `user_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `verified_by` (`verified_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=361;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_tests`
--
ALTER TABLE `user_tests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_ibfk_1` FOREIGN KEY (`user_test_id`) REFERENCES `user_tests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_tests`
--
ALTER TABLE `user_tests`
  ADD CONSTRAINT `user_tests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_tests_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_tests_ibfk_3` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

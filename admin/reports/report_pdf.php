<?php
// Mencegah output error/karakter kosong agar PDF tidak rusak
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

// Muat DOMPDF
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Sesi & Koneksi Database (sesuai file utama Anda)
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: ../auth/login.php");
  exit;
}
require "../config/database.php";

// =====================================
// BACA PARAMETER FILTER DARI URL
// =====================================
$search = "";
$test = "";
$status = "";

if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
}
if (isset($_GET['test'])) {
  $test = mysqli_real_escape_string($conn, $_GET['test']);
}
if (isset($_GET['status'])) {
  $status = mysqli_real_escape_string($conn, $_GET['status']);
}

// =====================================
// BUAT KONDISI WHERE SESUAI FILTER
// =====================================
$where = "WHERE user_tests.status = 'finished'";

if (!empty($search)) {
  $where .= " AND (users.fullname LIKE '%$search%' OR users.email LIKE '%$search%')";
}
if (!empty($test)) {
  $where .= " AND user_tests.test_id = '$test'";
}
if (!empty($status)) {
  $where .= " AND user_tests.payment_status = '$status'";
}

// =====================================
// AMBIL DATA LAPORAN SESUAI FILTER
// =====================================
$query = mysqli_query($conn, "
    SELECT
        user_tests.*,
        users.fullname,
        users.email,
        tests.title
    FROM user_tests
    JOIN users ON users.id = user_tests.user_id
    JOIN tests ON tests.id = user_tests.test_id
    $where
    ORDER BY user_tests.created_at DESC
");

// =====================================
// HITUNG STATISTIK SESUAI HALAMAN UTAMA
// =====================================
$totalParticipant = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(DISTINCT user_id) AS total FROM user_tests WHERE status = 'finished'
"))['total'] ?? 0;

$totalFinished = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total FROM user_tests WHERE status = 'finished'
"))['total'] ?? 0;

$totalIncome = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(tests.price) AS total
    FROM user_tests
    JOIN tests ON tests.id = user_tests.test_id
    WHERE user_tests.payment_status = 'paid' AND user_tests.status = 'finished'
"))['total'] ?? 0;

$averageScore = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT AVG(score) AS avg_score FROM user_tests WHERE status = 'finished'
"))['avg_score'] ?? 0;

// =====================================
// KONFIGURASI DOMPDF
// =====================================
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isPhpEnabled', false);

$dompdf = new Dompdf($options);

// =====================================
// SIAPKAN KONTEN HTML
// =====================================
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 20px;
            size: A4 landscape;
        }
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        h1 {
            text-align: center;
            margin: 0 0 5px 0;
            font-size: 22px;
            font-weight: bold;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
            font-size: 11px;
        }
        .stats {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .stats th {
            background-color: #2563eb;
            color: #ffffff;
            padding: 10px 8px;
            border: 1px solid #d1d5db;
            font-weight: bold;
        }
        .stats td {
            padding: 12px 8px;
            border: 1px solid #d1d5db;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        .report {
            width: 100%;
            border-collapse: collapse;
        }
        .report th {
            background-color: #1f2937;
            color: #ffffff;
            padding: 8px 5px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
        }
        .report td {
            border: 1px solid #ddd;
            padding: 7px 5px;
            font-size: 10px;
            vertical-align: middle;
        }
        .center { text-align: center; }
        .green { color: #16a34a; font-weight: bold; }
        .red { color: #dc2626; font-weight: bold; }
        .orange { color: #ea580c; font-weight: bold; }
        .gray { color: #6b7280; font-weight: bold; }
        .footer {
            margin-top: 30px;
            text-align: right;
            color: #888;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h1>LAPORAN HASIL PSIKOTES</h1>
    <div class="subtitle">Dicetak pada : ' . date("d F Y H:i") . '</div>

    <table class="stats">
        <tr>
            <th>Total Peserta</th>
            <th>Tes Selesai</th>
            <th>Total Pendapatan</th>
            <th>Rata-rata Nilai</th>
        </tr>
        <tr>
            <td>' . $totalParticipant . '</td>
            <td>' . $totalFinished . '</td>
            <td>Rp ' . number_format($totalIncome, 0, ",", ".") . '</td>
            <td>' . round($averageScore) . '</td>
        </tr>
    </table>

    <table class="report">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="18%">Peserta</th>
                <th width="20%">Email</th>
                <th width="18%">Nama Tes</th>
                <th width="8%">Nilai</th>
                <th width="8%">Benar</th>
                <th width="8%">Salah</th>
                <th width="10%">Pembayaran</th>
                <th width="15%">Tanggal</th>
            </tr>
        </thead>
        <tbody>';

$no = 1;
if ($query && mysqli_num_rows($query) > 0) {
  while ($row = mysqli_fetch_assoc($query)) {
    // Warna nilai
    $scoreVal = isset($row['score']) ? (int)$row['score'] : 0;
    if ($scoreVal >= 80) {
      $score = '<span class="green">' . $scoreVal . '</span>';
    } elseif ($scoreVal >= 60) {
      $score = '<span class="orange">' . $scoreVal . '</span>';
    } else {
      $score = '<span class="red">' . $scoreVal . '</span>';
    }

    // Status pembayaran
    $statusBayar = $row['payment_status'] ?? '';
    switch ($statusBayar) {
      case "paid":
        $payment = '<span class="green">Lunas</span>';
        break;
      case "pending":
        $payment = '<span class="orange">Menunggu</span>';
        break;
      default:
        $payment = '<span class="red">Ditolak</span>';
        break;
    }

    // Masukkan baris data
    $html .= '
        <tr>
            <td class="center">' . $no++ . '</td>
            <td>' . htmlspecialchars($row['fullname'] ?? '-') . '</td>
            <td>' . htmlspecialchars($row['email'] ?? '-') . '</td>
            <td>' . htmlspecialchars($row['title'] ?? '-') . '</td>
            <td class="center">' . $score . '</td>
            <td class="center">' . ($row['correct_answers'] ?? 0) . '</td>
            <td class="center">' . ($row['wrong_answers'] ?? 0) . '</td>
            <td class="center">' . $payment . '</td>
            <td class="center">' . (!empty($row['created_at']) ? date("d M Y H:i", strtotime($row['created_at'])) : '-') . '</td>
        </tr>';
  }
} else {
  // Jika tidak ada data
  $html .= '
    <tr>
        <td colspan="9" style="text-align:center; padding:25px; color:#777;">
            Belum ada data laporan yang sesuai dengan filter.
        </td>
    </tr>';
}

// Akhir konten
$html .= '
        </tbody>
    </table>

    <div class="footer">
        Dibuat oleh <b>Sistem Psikotes</b><br>
        ' . date("d F Y H:i:s") . '
    </div>
</body>
</html>';

// =====================================
// PROSES GENERATE & UNDUH PDF
// =====================================
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Bersihkan semua output sebelum mengirim file
ob_end_clean();

// Nama file unduhan
$namaFile = 'Laporan_Psikotes_' . date("Ymd_His") . '.pdf';

// Kirim ke browser untuk diunduh
$dompdf->stream($namaFile, ['Attachment' => true]);

exit;

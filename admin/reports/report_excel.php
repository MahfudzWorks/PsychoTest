<?php
// ✅ Cegah keluaran apa pun sebelum file Excel dibuat
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
  header("Location: ../auth/login.php");
  exit;
}

require "../config/database.php";
require "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

/*=====================================
FILTER
=====================================*/

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

/*=====================================
DATA
=====================================*/
$query = mysqli_query($conn, "
    SELECT
        user_tests.*,
        users.fullname,
        users.email,
        tests.title,
        tests.price
    FROM user_tests
    JOIN users ON users.id = user_tests.user_id
    JOIN tests ON tests.id = user_tests.test_id
    $where
    ORDER BY user_tests.created_at DESC
");

/*=====================================
STATISTIK (TAMBAH PENANGANAN NILAI KOSONG)
=====================================*/
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

/*=====================================
BUAT FILE EXCEL
=====================================*/
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Laporan Psikotes");

/*=====================================
JUDUL
=====================================*/
$sheet->mergeCells("A1:I1");
$sheet->setCellValue("A1", "LAPORAN HASIL PSIKOTES");

$sheet->mergeCells("A2:I2");
$sheet->setCellValue("A2", "Tanggal Cetak : " . date("d F Y H:i"));

$sheet->getStyle("A1")->getFont()->setBold(true)->setSize(18);
$sheet->getStyle("A2")->getFont()->setItalic(true);

/*=====================================
STATISTIK
=====================================*/
$sheet->setCellValue("A4", "Total Peserta");
$sheet->setCellValue("B4", $totalParticipant);

$sheet->setCellValue("D4", "Tes Selesai");
$sheet->setCellValue("E4", $totalFinished);

$sheet->setCellValue("G4", "Rata-rata Nilai");
$sheet->setCellValue("H4", round($averageScore));

$sheet->setCellValue("A5", "Total Pendapatan");
$sheet->setCellValue("B5", "Rp " . number_format($totalIncome, 0, ",", "."));

/*=====================================
HEADER TABEL
=====================================*/
$row = 7;
$header = [
  "No",
  "Nama Peserta",
  "Email",
  "Nama Tes",
  "Nilai",
  "Jumlah Benar",
  "Jumlah Salah",
  "Status Pembayaran",
  "Tanggal Tes"
];

$col = "A";
foreach ($header as $h) {
  $sheet->setCellValue($col . $row, $h);
  $col++;
}

// Gaya header
$sheet->getStyle("A7:I7")->getFont()->setBold(true);
$sheet->getStyle("A7:I7")->getFill()
  ->setFillType(Fill::FILL_SOLID)
  ->getStartColor()->setARGB("FF4F81BD");

$sheet->getStyle("A7:I7")->getFont()
  ->getColor()->setARGB("FFFFFFFF");

/*=====================================
ISI DATA
=====================================*/
$row++;
$no = 1;

if ($query && mysqli_num_rows($query) > 0) {
  while ($data = mysqli_fetch_assoc($query)) {
    $sheet->setCellValue("A" . $row, $no++);
    $sheet->setCellValue("B" . $row, $data['fullname'] ?? '-');
    $sheet->setCellValue("C" . $row, $data['email'] ?? '-');
    $sheet->setCellValue("D" . $row, $data['title'] ?? '-');
    $sheet->setCellValue("E" . $row, $data['score'] ?? 0);
    $sheet->setCellValue("F" . $row, $data['correct_answers'] ?? 0);
    $sheet->setCellValue("G" . $row, $data['wrong_answers'] ?? 0);
    $sheet->setCellValue("H" . $row, ucfirst($data['payment_status'] ?? '-'));
    $sheet->setCellValue("I" . $row, !empty($data['created_at']) ? date("d-m-Y H:i", strtotime($data['created_at'])) : '-');
    $row++;
  }
} else {
  // Jika tidak ada data
  $sheet->mergeCells("A$row:I$row");
  $sheet->setCellValue("A$row", "Tidak ada data yang sesuai dengan filter");
  $sheet->getStyle("A$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
  $row++;
}

/*=====================================
BORDER & LEBAR KOLOM
=====================================*/
if ($row > 8) {
  $sheet->getStyle("A7:I" . ($row - 1))
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN);
}

foreach (range('A', 'I') as $column) {
  $sheet->getColumnDimension($column)->setAutoSize(true);
}

/*=====================================
UNDUH FILE
=====================================*/
$filename = "Laporan_Psikotes_" . date("Ymd_His") . ".xlsx";

// 🧹 Bersihkan semua keluaran sebelum mengirim file
ob_end_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Pragma: public');

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;

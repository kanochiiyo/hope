<?php
// PASTIKAN TIDAK ADA SPASI, BARIS KOSONG, ATAU KARAKTER APAPUN
// SEBELUM TAG <?php INI. Ini KRITIS untuk download file agar header HTTP tidak rusak.

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Sertakan jika memang diperlukan untuk otentikasi/data sesi

// =========================================================
// 1. PANGGIL COMPOSER AUTOLOADER UNTUK PHPSPREADSHEET
// =    Sesuaikan path jika folder 'vendor' tidak langsung di dalam 'hope'
// =========================================================
require_once(__DIR__ . '/vendor/autoload.php'); // Path yang umum jika vendor di root proyek

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv; // Masih dipakai jika kamu ingin opsi CSV

// =========================================================
// 2. KONEKSI DATABASE
//    Menggunakan kode koneksi yang kamu berikan
// =========================================================
$connection = null;
$hostname = "localhost";
$username = "root";
$password = "";
$database = "hope"; // Pastikan nama database ini benar

try {
  $connection = new mysqli($hostname, $username, $password, $database);
} catch (Exception $e) {
  // Jika koneksi gagal, log error ke log server dan tampilkan pesan umum ke user
  error_log('Koneksi database gagal: ' . $e->getMessage());
  http_response_code(500); // Internal Server Error
  die('Maaf, ada masalah dengan koneksi database.');
}

// Cek lagi jika objek koneksi benar-benar terbentuk dan tidak ada error koneksi MySQLi
if ($connection->connect_error) {
  error_log("Koneksi database gagal (MySQLi error): " . $connection->connect_error);
  http_response_code(500);
  die('Maaf, koneksi database tidak dapat dibuat. Periksa kredensial.');
}


// =========================================================
// 3. MENENTUKAN FORMAT REPORT DARI URL
//    Akan menggunakan 'xlsx' untuk Excel asli atau 'csv'
// =========================================================
$format = isset($_GET['format']) ? strtolower($_GET['format']) : 'csv'; // Default: csv

// =========================================================
// 4. MENYIAPKAN NAMA FILE UNTUK DOWNLOAD
// =========================================================
$filename = "laporan_orders_" . date('Ymd_His');

// =========================================================
// 5. MENENTUKAN HEADERS KOLOM UNTUK REPORT
//    Ini akan menjadi baris pertama di file CSV/Excel.
//    Urutan ini SANGAT PENTING dan harus cocok dengan urutan di SELECT dan $row_data.
// =========================================================
$column_headers = [
  'ID',                   // id
  'Nama',                 // name
  'No. Telepon',          // phone_number
  'Deskripsi',            // deskripsi
  'Harga',                // price
  'Tanggal Order',        // order_date
  'Estimasi Selesai',     // estimation_date
  'Tanggal Selesai',      // end_date
  'Kuantitas',            // qty
  'Alamat Pengiriman',    // shipping_address
  'Owner Approval',       // owner_approve
  'Customer Approval',    // cust_approve
  'ID Customer'           // cust_id
];

// =========================================================
// 6. MENGAMBIL DATA DARI DATABASE
//    QUERY INI MENCERMINKAN PERSIS STRUKTUR TABEL KAMU
//    sesuai dengan image_911c31.png.
// =========================================================
$sql = "SELECT
            id,
            name,
            phone_number,
            deskripsi,
            price,
            order_date,
            estimation_date,
            end_date,
            qty,
            shipping_address,
            owner_approve,
            cust_approve,
            cust_id
        FROM
            orders
        ORDER BY
            order_date DESC, id DESC"; // Urutkan dari order terbaru

$result = $connection->query($sql);

// Cek jika ada masalah saat menjalankan query SQL
if (!$result) {
  error_log("Gagal menjalankan query SQL: " . $connection->error);
  http_response_code(500); // Internal Server Error
  die("Maaf, terjadi kesalahan saat mengambil data laporan dari database.");
}

// =========================================================
// 7. MEMBUAT DAN MENGIRIM REPORT SESUAI FORMAT YANG DIMINTA
// =========================================================

if ($format == 'csv') {
  // ---- KODE UNTUK FORMAT CSV ----
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
  header('Pragma: no-cache');
  header('Expires: 0');

  $output = fopen('php://output', 'w');
  fputcsv($output, $column_headers);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      // Susun data untuk setiap baris. URUTAN HARUS SESUAI DENGAN $column_headers dan SELECT.
      $row_data = [
        $row['id'],
        $row['name'],
        $row['phone_number'],
        $row['deskripsi'],
        $row['price'],
        $row['order_date'],
        $row['estimation_date'],
        $row['end_date'],
        $row['qty'],
        $row['shipping_address'],
        ($row['owner_approve'] == 1 ? 'Approved' : 'Pending'), // Konversi tinyint(1) ke teks
        ($row['cust_approve'] == 1 ? 'Approved' : 'Pending'),   // Konversi tinyint(1) ke teks
        $row['cust_id']
      ];
      fputcsv($output, $row_data);
    }
  } else {
    fputcsv($output, ["Tidak ada data orders yang tersedia untuk laporan ini."]);
  }
  fclose($output); // Tutup stream untuk CSV

} elseif ($format == 'xlsx') { // Menggunakan 'xlsx' untuk Excel asli dengan PhpSpreadsheet

  // ---- KODE UNTUK FORMAT EXCEL (.xlsx) MENGGUNAKAN PHPSPREADSHEET ----

  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();
  $sheet->setTitle('Data Orders'); // Nama sheet di Excel

  // Menulis header kolom ke sheet
  $sheet->fromArray($column_headers, NULL, 'A1'); // Tulis array headers mulai dari sel A1

  // Menulis data baris demi baris ke sheet
  $rowIndex = 2; // Data dimulai dari baris ke-2 (setelah header)
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      // Susun data untuk setiap baris. URUTAN HARUS SESUAI DENGAN $column_headers dan SELECT.
      $row_data = [
        $row['id'],
        $row['name'],
        $row['phone_number'],
        $row['deskripsi'],
        $row['price'],
        $row['order_date'],
        $row['estimation_date'],
        $row['end_date'],
        $row['qty'],
        $row['shipping_address'],
        ($row['owner_approve'] == 1 ? 'Approved' : 'Pending'), // Konversi tinyint(1) ke teks
        ($row['cust_approve'] == 1 ? 'Approved' : 'Pending'),
        $row['cust_id']
      ];
      $sheet->fromArray($row_data, NULL, 'A' . $rowIndex); // Tulis baris data
      $rowIndex++;
    }
  } else {
    // Jika tidak ada data, tulis pesan di sel A2 dan gabungkan sel
    $sheet->setCellValue('A' . $rowIndex, "Tidak ada data orders yang tersedia untuk laporan ini.");
    $sheet->mergeCells('A' . $rowIndex . ':M' . $rowIndex); // Gabungkan sel (M adalah kolom ke-13, sesuai jumlah kolom headers)
  }

  // Mengatur HTTP Headers untuk download file XLSX
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
  header('Cache-Control: max-age=0'); // Untuk kompatibilitas browser lama/IE

  $writer = new Xlsx($spreadsheet);
  $writer->save('php://output'); // Simpan spreadsheet langsung ke output HTTP

} else {
  // Jika parameter 'format' tidak dikenali (bukan 'csv' atau 'xlsx')
  http_response_code(400); // Bad Request
  die("Format laporan tidak didukung. Mohon gunakan 'csv' atau 'xlsx'.");
}

// =========================================================
// 8. MENUTUP KONEKSI DATABASE
// =========================================================
$connection->close();

// =========================================================
// 9. HENTIKAN EKSEKUSI SCRIPT
//    Ini sangat penting untuk memastikan tidak ada output tambahan
//    yang bisa merusak file yang didownload.
// =========================================================
exit();
?>
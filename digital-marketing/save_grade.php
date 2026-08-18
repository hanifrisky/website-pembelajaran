<?php
error_reporting(0);
ini_set('display_errors', 0);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode request tidak diizinkan"]);
    exit();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$nama_tim = isset($data['nama_tim']) ? trim($data['nama_tim']) : '';
$skenario = isset($data['skenario']) ? trim($data['skenario']) : '';
$empati = isset($data['empati']) ? intval($data['empati']) : 0;
$transparansi = isset($data['transparansi']) ? intval($data['transparansi']) : 0;
$solusi = isset($data['solusi']) ? intval($data['solusi']) : 0;
$netiket = isset($data['netiket']) ? intval($data['netiket']) : 0;

// Weighted formula
$total_score = ($empati * 0.25) + ($transparansi * 0.25) + ($solusi * 0.3) + ($netiket * 0.2);
$timestamp = date('d/m/Y, H.i.s');

if (empty($nama_tim) || empty($skenario)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Nama Kelompok dan Skenario wajib diisi."]);
    exit();
}

$csv_file = 'hasil_penilaian_tim.csv';
$file_exists = file_exists($csv_file);

$f = fopen($csv_file, 'a');
if ($f === false) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Server gagal menyimpan penilaian."]);
    exit();
}

if (!$file_exists) {
    fputs($f, "\xEF\xBB\xBF"); // UTF-8 BOM
    fputcsv($f, ['Waktu', 'Nama Kelompok', 'Skenario', 'Empati', 'Transparansi', 'Solusi', 'Netiket', 'Nilai Akhir']);
}

fputcsv($f, [$timestamp, $nama_tim, $skenario, $empati, $transparansi, $solusi, $netiket, $total_score]);
fclose($f);

echo json_encode(["status" => "success", "message" => "Penilaian berhasil terkirim secara anonim!"]);
exit();
?>

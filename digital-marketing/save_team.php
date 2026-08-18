<?php
error_reporting(0);
ini_set('display_errors', 0);
// Set headers for JSON response and CORS
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode request tidak diizinkan"]);
    exit();
}

// Retrieve raw request body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$nama_tim = isset($data['nama_tim']) ? trim($data['nama_tim']) : '';
$skenario = isset($data['skenario']) ? trim($data['skenario']) : '';
$respons = isset($data['respons']) ? trim($data['respons']) : '';
$timestamp = date('d/m/Y, H.i.s');

// Simple validation
if (empty($nama_tim) || empty($skenario) || empty($respons)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Nama Tim, Skenario, dan Draf Respons wajib diisi."]);
    exit();
}

$csv_file = 'hasil_game_tim.csv';
$file_exists = file_exists($csv_file);

// Open file for appending
$f = fopen($csv_file, 'a');
if ($f === false) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Server gagal membuka file penyimpanan."]);
    exit();
}

// If file is new, add BOM and Header row
if (!$file_exists) {
    fputs($f, "\xEF\xBB\xBF"); // UTF-8 BOM
    fputcsv($f, ['Waktu', 'Nama Kelompok', 'Skenario', 'Draft Respons']);
}

// Append data row
fputcsv($f, [$timestamp, $nama_tim, $skenario, $respons]);
fclose($f);

echo json_encode(["status" => "success", "message" => "Draft respons tim berhasil dikirim!"]);
exit();
?>

<?php
// Set CORS headers so it works even if index.html is opened locally as file://
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Handle CORS Preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan"]);
    exit();
}

// Get raw post data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Payload kosong atau format salah"]);
    exit();
}

$nama = isset($data['nama']) ? trim($data['nama']) : '';
$kelas = isset($data['kelas']) ? trim($data['kelas']) : '';
$kategori = isset($data['kategori']) ? trim($data['kategori']) : 'Semua Materi (Komprehensif)';
$nilai = isset($data['nilai']) ? intval($data['nilai']) : 0;
$total_soal = isset($data['total_soal']) ? intval($data['total_soal']) : 20;
$persentase = isset($data['persentase']) ? intval($data['persentase']) : 0;
$timestamp = isset($data['timestamp']) ? trim($data['timestamp']) : date('d/m/Y, H.i.s');
$soal_dilihat = isset($data['soal_dilihat']) ? $data['soal_dilihat'] : [];

if (empty($nama) || empty($kelas)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Nama dan Kelas wajib diisi"]);
    exit();
}

function get_csv_filename($kategori) {
    $kategori_lower = strtolower($kategori);
    if (strpos($kategori_lower, 'komprehensif') !== false || strpos($kategori_lower, 'semua materi') !== false) {
        return 'hasil_latihan_komprehensif.csv';
    }
    if (preg_match('/(modul|materi|stage)\s*(\d+)/i', $kategori, $matches)) {
        return 'hasil_latihan_' . strtolower($matches[1]) . '_' . intval($matches[2]) . '.csv';
    }
    $clean = preg_replace('/[^a-zA-Z0-9]/', '_', $kategori);
    $clean = preg_replace('/__+/', '_', $clean);
    $clean = trim($clean, '_');
    return 'hasil_latihan_' . strtolower($clean) . '.csv';
}

function save_to_csv($csv_file, $timestamp, $nama, $kelas, $kategori, $nilai, $total_soal, $persentase, $soal_dilihat) {
    $file_exists = file_exists($csv_file);
    
    // Auto migrate CSV if it is in the old 7-column format (only needed for master)
    if ($file_exists && basename($csv_file) === 'hasil_latihan.csv') {
        $rows = [];
        $needs_migration = false;
        if (($handle = fopen($csv_file, "r")) !== FALSE) {
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }
            $headers = fgetcsv($handle, 1000, ",");
            if ($headers !== FALSE && count($headers) === 7) {
                $needs_migration = true;
                $rows[] = [$headers[0], $headers[1], $headers[2], 'Kategori/Materi', $headers[3], $headers[4], $headers[5], $headers[6]];
                while (($row_data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (count($row_data) === 7) {
                        $rows[] = [$row_data[0], $row_data[1], $row_data[2], 'Semua Materi (Komprehensif)', $row_data[3], $row_data[4], $row_data[5], $row_data[6]];
                    } else {
                        $rows[] = $row_data;
                    }
                }
            }
            fclose($handle);
        }
        if ($needs_migration && !empty($rows)) {
            $write_handle = fopen($csv_file, "w");
            if ($write_handle !== FALSE) {
                fputs($write_handle, "\xEF\xBB\xBF");
                foreach ($rows as $r) {
                    fputcsv($write_handle, $r);
                }
                fclose($write_handle);
            }
        }
    }

    $f = fopen($csv_file, 'a');
    if ($f !== false) {
        if (!$file_exists) {
            fputs($f, "\xEF\xBB\xBF");
            fputcsv($f, ['Waktu', 'Nama Lengkap', 'Kelas', 'Kategori/Materi', 'Jawaban Benar', 'Total Soal', 'Persentase Nilai', 'Soal Dilihat Jawabannya']);
        }
        $soal_dilihat_str = json_encode($soal_dilihat, JSON_UNESCAPED_UNICODE);
        fputcsv($f, [$timestamp, $nama, $kelas, $kategori, $nilai, $total_soal, $persentase . '%', $soal_dilihat_str]);
        fclose($f);
        return true;
    }
    return false;
}

$csv_file = 'hasil_latihan.csv';
$category_csv_file = get_csv_filename($kategori);

// Save to master CSV
$success_master = save_to_csv($csv_file, $timestamp, $nama, $kelas, $kategori, $nilai, $total_soal, $persentase, $soal_dilihat);

// Save to category CSV if it is different
if ($category_csv_file !== $csv_file) {
    save_to_csv($category_csv_file, $timestamp, $nama, $kelas, $kategori, $nilai, $total_soal, $persentase, $soal_dilihat);
}

if (!$success_master) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Gagal membuka file penyimpanan"]);
    exit();
}

// Return success
echo json_encode(["status" => "success", "message" => "Nilai berhasil disimpan"]);
exit();
?>

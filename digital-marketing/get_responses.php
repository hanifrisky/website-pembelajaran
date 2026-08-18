<?php
error_reporting(0);
ini_set('display_errors', 0);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

$csv_file = 'hasil_game_tim.csv';
$responses = [];

if (file_exists($csv_file)) {
    if (($handle = fopen($csv_file, "r")) !== FALSE) {
        // Skip UTF-8 BOM
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }
        
        // Skip header row
        $header = fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) >= 4) {
                $responses[] = [
                    'waktu' => $data[0],
                    'nama_tim' => $data[1],
                    'skenario' => $data[2],
                    'respons' => $data[3]
                ];
            }
        }
        fclose($handle);
    }
}

echo json_encode(["status" => "success", "data" => $responses]);
exit();
?>

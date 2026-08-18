<?php
error_reporting(0);
ini_set('display_errors', 0);
// Set session params to persist cookie for 24 hours (86400 seconds)
session_set_cookie_params(86400);
session_start();

// Admin Password Configuration
$admin_password = 'Cdxfs1qaz'; 

$error = '';
$csv_file = 'hasil_game_tim.csv';
$csv_grades_file = 'hasil_penilaian_tim.csv';

// Handle Login Submission
if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['loggedin_dm'] = true;
        $_SESSION['login_time_dm'] = time();
        header("Location: hasil.php");
        exit();
    } else {
        $error = 'Kata sandi salah!';
    }
}

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['loggedin_dm']);
    unset($_SESSION['login_time_dm']);
    header("Location: hasil.php");
    exit();
}

// Check Authentication and 24-hour Expiry
$is_authenticated = false;
if (isset($_SESSION['loggedin_dm']) && $_SESSION['loggedin_dm'] === true) {
    if (time() - $_SESSION['login_time_dm'] < 86400) {
        $is_authenticated = true;
    } else {
        unset($_SESSION['loggedin_dm']);
        unset($_SESSION['login_time_dm']);
        $error = 'Sesi Anda telah berakhir (24 jam). Harap masuk kembali.';
    }
}

// Actions once Authenticated
if ($is_authenticated) {
    // 1. Download CSV Team Drafts
    if (isset($_GET['action']) && $_GET['action'] === 'download') {
        if (file_exists($csv_file)) {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=hasil_game_tim_digital_marketing.csv');
            readfile($csv_file);
            exit();
        } else {
            header("Location: hasil.php");
            exit();
        }
    }

    // 2. Download CSV Peer Reviews
    if (isset($_GET['action']) && $_GET['action'] === 'download_grades') {
        if (file_exists($csv_grades_file)) {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=hasil_peer_review_tim.csv');
            readfile($csv_grades_file);
            exit();
        } else {
            header("Location: hasil.php");
            exit();
        }
    }

    // 3. Clear All Submissions (Drafts)
    if (isset($_POST['action']) && $_POST['action'] === 'clear_all') {
        if (file_exists($csv_file)) {
            unlink($csv_file);
        }
        header("Location: hasil.php");
        exit();
    }

    // 4. Clear All Grades (Peer Reviews)
    if (isset($_POST['action']) && $_POST['action'] === 'clear_all_grades') {
        if (file_exists($csv_grades_file)) {
            unlink($csv_grades_file);
        }
        header("Location: hasil.php");
        exit();
    }

    // 5. Delete Specific Row Draft
    if (isset($_POST['action']) && $_POST['action'] === 'delete_row') {
        $row_idx = isset($_POST['row_idx']) ? intval($_POST['row_idx']) : -1;
        if ($row_idx >= 0 && file_exists($csv_file)) {
            $temp_rows = [];
            if (($handle = fopen($csv_file, "r")) !== FALSE) {
                $bom = fread($handle, 3);
                if ($bom !== "\xEF\xBB\xBF") {
                    rewind($handle);
                }
                $header = fgetcsv($handle);
                $idx = 0;
                while (($data = fgetcsv($handle)) !== FALSE) {
                    if ($idx !== $row_idx) {
                        $temp_rows[] = $data;
                    }
                    $idx++;
                }
                fclose($handle);
            }

            // Rewrite CSV file
            $f = fopen($csv_file, 'w');
            if ($f !== false) {
                fputs($f, "\xEF\xBB\xBF");
                fputcsv($f, ['Waktu', 'Nama Kelompok', 'Skenario', 'Draft Respons']);
                foreach ($temp_rows as $row) {
                    fputcsv($f, $row);
                }
                fclose($f);
            }
        }
        header("Location: hasil.php");
        exit();
    }

    // 6. Delete Specific Row Peer Review Grade
    if (isset($_POST['action']) && $_POST['action'] === 'delete_grade') {
        $row_idx = isset($_POST['row_idx']) ? intval($_POST['row_idx']) : -1;
        if ($row_idx >= 0 && file_exists($csv_grades_file)) {
            $temp_rows = [];
            if (($handle = fopen($csv_grades_file, "r")) !== FALSE) {
                $bom = fread($handle, 3);
                if ($bom !== "\xEF\xBB\xBF") {
                    rewind($handle);
                }
                $header = fgetcsv($handle);
                $idx = 0;
                while (($data = fgetcsv($handle)) !== FALSE) {
                    if ($idx !== $row_idx) {
                        $temp_rows[] = $data;
                    }
                    $idx++;
                }
                fclose($handle);
            }

            // Rewrite CSV file
            $f = fopen($csv_grades_file, 'w');
            if ($f !== false) {
                fputs($f, "\xEF\xBB\xBF");
                fputcsv($f, ['Waktu', 'Nama Kelompok', 'Skenario', 'Empati', 'Transparansi', 'Solusi', 'Netiket', 'Nilai Akhir']);
                foreach ($temp_rows as $row) {
                    fputcsv($f, $row);
                }
                fclose($f);
            }
        }
        header("Location: hasil.php");
        exit();
    }
}

// If not authenticated, render Login Screen
if (!$is_authenticated):
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Akses Terproteksi - Dashboard Game DM</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #C9F13B;
      --primary-hover: #b0d42c;
      --bg-dark: #050a07;
      --card-bg: rgba(255, 255, 255, 0.03);
      --border-glass: rgba(255, 255, 255, 0.08);
      --border-glass-focus: rgba(201, 241, 59, 0.4);
      --text-main: #f3f4f6;
      --text-secondary: #9ca3af;
      --font-display: 'Space Grotesk', sans-serif;
      --font-body: 'Manrope', sans-serif;
      --danger: #ef4444;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: var(--font-body);
      background-color: var(--bg-dark);
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    body::before {
      content: "";
      position: absolute; inset: 0;
      background-image: linear-gradient(rgba(255,255,255,0.005) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255,255,255,0.005) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
      z-index: 0;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.02);
      border: 1px solid var(--border-glass);
      backdrop-filter: blur(12px);
      padding: 40px;
      border-radius: 24px;
      width: 100%;
      max-width: 420px;
      text-align: center;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
      z-index: 10;
      position: relative;
    }

    .login-container::before {
      content: "";
      position: absolute; top: 0; left: 0; right: 0; height: 4px;
      background: var(--primary);
      border-radius: 24px 24px 0 0;
    }

    .logo-icon {
      font-size: 3rem;
      margin-bottom: 20px;
      display: inline-block;
    }

    h1 {
      font-family: var(--font-display);
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 8px;
      letter-spacing: -0.01em;
    }

    .subtitle {
      font-size: 0.9rem;
      color: var(--text-secondary);
      margin-bottom: 30px;
      line-height: 1.5;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }

    label {
      display: block;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 8px;
      color: var(--text-secondary);
    }

    input[type="password"] {
      width: 100%;
      padding: 14px 18px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-glass);
      color: var(--text-main);
      font-family: var(--font-body);
      font-size: 1rem;
      transition: all 0.2s ease;
      outline: none;
    }

    input[type="password"]:focus {
      border-color: var(--primary);
      box-shadow: 0 0 12px rgba(201, 241, 59, 0.15);
      background: rgba(255, 255, 255, 0.08);
    }

    .btn-submit {
      width: 100%;
      padding: 14px;
      border-radius: 12px;
      border: none;
      background: var(--primary);
      color: #000000;
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(201, 241, 59, 0.25);
      transition: all 0.2s ease;
    }

    .btn-submit:hover {
      background: var(--primary-hover);
      box-shadow: 0 6px 20px rgba(201, 241, 59, 0.4);
    }

    .error-msg {
      margin-top: 15px;
      color: var(--danger);
      font-size: 0.85rem;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <span class="logo-icon">📊</span>
    <h1>Admin Lock</h1>
    <p class="subtitle">Silakan masukkan kata sandi guru untuk masuk ke Dashboard Hasil Game Kelompok.</p>
    <form method="POST">
      <div class="form-group">
        <label for="password">Kata Sandi Guru:</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required autofocus>
      </div>
      <button type="submit" class="btn-submit">🔓 Buka Dashboard</button>
    </form>
    <?php if (!empty($error)): ?>
      <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
<?php
exit();
endif;

// --- AUTHENTICATED ADMIN DASHBOARD VIEW ---

// 1. Parse Team Drafts
$submissions = [];
if (file_exists($csv_file)) {
    if (($handle = fopen($csv_file, "r")) !== FALSE) {
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }
        $header = fgetcsv($handle);
        $idx = 0;
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) >= 4) {
                $submissions[] = [
                    'original_idx' => $idx,
                    'waktu' => $data[0],
                    'nama_tim' => $data[1],
                    'skenario' => $data[2],
                    'respons' => $data[3]
                ];
            }
            $idx++;
        }
        fclose($handle);
    }
}
$submissions_reversed = array_reverse($submissions);

// 2. Parse Peer Review Grades
$grades = [];
$team_averages = [];

if (file_exists($csv_grades_file)) {
    if (($handle = fopen($csv_grades_file, "r")) !== FALSE) {
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }
        $header = fgetcsv($handle);
        $idx = 0;
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) >= 8) {
                $item = [
                    'original_idx' => $idx,
                    'waktu' => $data[0],
                    'nama_tim' => $data[1],
                    'skenario' => $data[2],
                    'empati' => floatval($data[3]),
                    'transparansi' => floatval($data[4]),
                    'solusi' => floatval($data[5]),
                    'netiket' => floatval($data[6]),
                    'nilai_akhir' => floatval($data[7])
                ];
                $grades[] = $item;
                
                // Grouping for average calculations
                $key = $data[1] . '|||' . $data[2];
                if (!isset($team_averages[$key])) {
                    $team_averages[$key] = [
                        'nama_tim' => $data[1],
                        'skenario' => $data[2],
                        'sum_empati' => 0,
                        'sum_transparansi' => 0,
                        'sum_solusi' => 0,
                        'sum_netiket' => 0,
                        'sum_nilai_akhir' => 0,
                        'count' => 0
                    ];
                }
                $team_averages[$key]['sum_empati'] += floatval($data[3]);
                $team_averages[$key]['sum_transparansi'] += floatval($data[4]);
                $team_averages[$key]['sum_solusi'] += floatval($data[5]);
                $team_averages[$key]['sum_netiket'] += floatval($data[6]);
                $team_averages[$key]['sum_nilai_akhir'] += floatval($data[7]);
                $team_averages[$key]['count']++;
            }
            $idx++;
        }
        fclose($handle);
    }
}
$grades_reversed = array_reverse($grades);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Hasil Game - Digital Marketing</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #C9F13B;
      --primary-hover: #b0d42c;
      --bg-dark: #050a07;
      --card-bg: rgba(255, 255, 255, 0.02);
      --border-glass: rgba(255, 255, 255, 0.08);
      --text-main: #f3f4f6;
      --text-secondary: #9ca3af;
      --danger: #ef4444;
      --danger-hover: #dc2626;
      --font-display: 'Space Grotesk', sans-serif;
      --font-body: 'Manrope', sans-serif;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: var(--font-body);
      background-color: var(--bg-dark);
      color: var(--text-main);
      min-height: 100vh;
      padding: 40px 20px;
    }

    body::before {
      content: "";
      position: absolute; inset: 0;
      background-image: linear-gradient(rgba(255,255,255,0.003) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255,255,255,0.003) 1px, transparent 1px);
      background-size: 50px 50px;
      pointer-events: none;
      z-index: -1;
    }

    .container {
      max-width: 1100px;
      margin: 0 auto;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 30px;
      border-bottom: 1px solid var(--border-glass);
      padding-bottom: 24px;
    }

    h1 {
      font-family: var(--font-display);
      font-size: 2.2rem;
      font-weight: 700;
      letter-spacing: -0.02em;
    }
    h1 span { color: var(--primary); }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      border-radius: 8px;
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 0.85rem;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.2s;
      border: none;
    }

    .btn-back {
      background: rgba(255,255,255,0.05);
      border: 1px solid var(--border-glass);
      color: var(--text-main);
    }
    .btn-back:hover { background: rgba(255,255,255,0.1); }

    .btn-primary {
      background: var(--primary);
      color: #000000;
      box-shadow: 0 4px 12px rgba(201, 241, 59, 0.2);
    }
    .btn-primary:hover {
      background: var(--primary-hover);
    }

    .btn-danger {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.2);
      color: var(--danger);
    }
    .btn-danger:hover {
      background: var(--danger);
      color: white;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }

    .stat-card {
      background: var(--card-bg);
      border: 1px solid var(--border-glass);
      border-radius: 16px;
      padding: 20px;
      backdrop-filter: blur(10px);
    }
    .stat-card h3 {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--text-secondary);
      margin-bottom: 8px;
    }
    .stat-card .value {
      font-family: var(--font-display);
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--primary);
    }

    .section-title {
      font-family: var(--font-display);
      font-size: 1.4rem;
      font-weight: 700;
      margin: 40px 0 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-left: 4px solid var(--primary);
      padding-left: 12px;
    }

    .table-container {
      background: var(--card-bg);
      border: 1px solid var(--border-glass);
      border-radius: 20px;
      overflow-x: auto;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }

    th {
      font-family: var(--font-display);
      font-size: 0.8rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--text-secondary);
      background: rgba(255,255,255,0.01);
      padding: 16px 20px;
      border-bottom: 1px solid var(--border-glass);
    }

    td {
      padding: 16px 20px;
      border-bottom: 1px solid var(--border-glass);
      font-size: 0.9rem;
      line-height: 1.5;
      vertical-align: middle;
    }

    tr:last-child td {
      border-bottom: none;
    }

    .waktu-col { color: var(--text-secondary); font-size: 0.8rem; }
    .tim-col { font-weight: 700; color: var(--primary); }
    .skenario-col { font-weight: 600; font-size: 0.85rem; }
    .respons-col { max-width: 380px; word-break: break-word; font-style: italic; }
    
    .score-badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 6px;
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 13px;
      background: rgba(201, 241, 59, 0.1);
      color: var(--primary);
    }

    .no-data {
      text-align: center;
      padding: 45px 20px;
      color: var(--text-secondary);
    }
    .no-data span {
      display: block;
      font-size: 2.5rem;
      margin-bottom: 8px;
    }

    .btn-delete-row {
      background: transparent;
      border: none;
      color: var(--danger);
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 700;
    }
    .btn-delete-row:hover { text-decoration: underline; }

    @media (max-width: 860px) {
      .stats-grid { grid-template-columns: 1fr; }
      header { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>

  <div class="container">
    <header>
      <div>
        <h1>Dashboard Guru — <span>War Room</span></h1>
        <p style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 4px;">Pusat kendali, pemantauan respons, dan rekap penilaian peer review kelas.</p>
      </div>
      <div class="header-actions" style="display:flex; gap:10px;">
        <a href="index.html" class="btn btn-back">🏠 Beranda Portal</a>
        <a href="hasil.php?action=logout" class="btn btn-back" style="color: var(--danger);">🚪 Logout</a>
      </div>
    </header>

    <div class="stats-grid">
      <div class="stat-card">
        <h3>Total Tim Pengirim</h3>
        <div class="value"><?php echo count($submissions); ?> Kelompok</div>
      </div>
      <div class="stat-card">
        <h3>Total Suara Penilaian</h3>
        <div class="value" style="color: #A7F3D0;"><?php echo count($grades); ?> Penilai Anonim</div>
      </div>
      <div class="stat-card">
        <h3>Modul Kasus</h3>
        <div class="value" style="color: white;">Etika &amp; Netiket</div>
      </div>
    </div>

    <!-- SECTION 1: KELOMPOK DRAFT ANSWERS -->
    <div class="section-title">
      <span>Draf Respons Klarifikasi Kelompok (Slide 11)</span>
      <?php if (!empty($submissions)): ?>
        <div style="display:flex; gap:10px;">
          <a href="hasil.php?action=download" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.75rem;">📥 Unduh CSV</a>
          <form method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA jawaban draf kelompok?');">
            <input type="hidden" name="action" value="clear_all">
            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.75rem;">⚠️ Reset</button>
          </form>
        </div>
      <?php endif; ?>
    </div>

    <div class="table-container">
      <?php if (empty($submissions)): ?>
        <div class="no-data">
          <span>📦</span>
          <p>Belum ada draf respons yang dikirimkan oleh kelompok.</p>
        </div>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th style="width: 140px;">Waktu Kirim</th>
              <th style="width: 160px;">Nama Kelompok</th>
              <th style="width: 220px;">Skenario Kasus</th>
              <th>Draft Respons Klarifikasi</th>
              <th style="width: 70px; text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            foreach ($submissions_reversed as $row): 
            ?>
              <tr>
                <td style="font-family: var(--font-display); font-weight: 700;"><?php echo $no++; ?></td>
                <td class="waktu-col"><?php echo htmlspecialchars($row['waktu']); ?></td>
                <td class="tim-col"><?php echo htmlspecialchars($row['nama_tim']); ?></td>
                <td class="skenario-col"><?php echo htmlspecialchars($row['skenario']); ?></td>
                <td class="respons-col"><?php echo nl2br(htmlspecialchars($row['respons'])); ?></td>
                <td style="text-align: center;">
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus draf respons ini?');">
                    <input type="hidden" name="action" value="delete_row">
                    <input type="hidden" name="row_idx" value="<?php echo $row['original_idx']; ?>">
                    <button type="submit" class="btn-delete-row">Hapus</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>


    <!-- SECTION 2: PAPAN REKAP RATARATA TIM -->
    <div class="section-title">
      <span>Papan Rekap Rata-Rata Penilaian Tim (Peer Review)</span>
    </div>

    <div class="table-container">
      <?php if (empty($team_averages)): ?>
        <div class="no-data">
          <span>📊</span>
          <p>Belum ada akumulasi rata-rata penilaian.</p>
        </div>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th style="width: 160px;">Nama Kelompok</th>
              <th style="width: 220px;">Skenario Kasus</th>
              <th style="text-align: center;">Rata<sup>2</sup> Empati (25%)</th>
              <th style="text-align: center;">Rata<sup>2</sup> Jujur (25%)</th>
              <th style="text-align: center;">Rata<sup>2</sup> Solusi (30%)</th>
              <th style="text-align: center;">Rata<sup>2</sup> Netiket (20%)</th>
              <th style="text-align: center;">Jumlah Penilai</th>
              <th style="text-align: center; background: rgba(201, 241, 59, 0.03);">Nilai Akhir Rata-Rata</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            foreach ($team_averages as $key => $val):
              $avg_emp = $val['sum_empati'] / $val['count'];
              $avg_trans = $val['sum_transparansi'] / $val['count'];
              $avg_sol = $val['sum_solusi'] / $val['count'];
              $avg_net = $val['sum_netiket'] / $val['count'];
              $avg_final = $val['sum_nilai_akhir'] / $val['count'];
            ?>
              <tr>
                <td style="font-family: var(--font-display); font-weight: 700;"><?php echo $no++; ?></td>
                <td class="tim-col"><?php echo htmlspecialchars($val['nama_tim']); ?></td>
                <td class="skenario-col"><?php echo htmlspecialchars($val['skenario']); ?></td>
                <td style="text-align: center;"><?php echo number_format($avg_emp, 1); ?></td>
                <td style="text-align: center;"><?php echo number_format($avg_trans, 1); ?></td>
                <td style="text-align: center;"><?php echo number_format($avg_sol, 1); ?></td>
                <td style="text-align: center;"><?php echo number_format($avg_net, 1); ?></td>
                <td style="text-align: center; font-weight: 700; color: var(--text-secondary);"><?php echo $val['count']; ?> suara</td>
                <td style="text-align: center; background: rgba(201, 241, 59, 0.02); font-weight: 700;">
                  <span class="score-badge"><?php echo number_format($avg_final, 1); ?></span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>


    <!-- SECTION 3: LOG DETAIL PENILAIAN -->
    <div class="section-title">
      <span>Log Detail Penilaian Masuk (Anonim)</span>
      <?php if (!empty($grades)): ?>
        <div style="display:flex; gap:10px;">
          <a href="hasil.php?action=download_grades" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.75rem;">📥 Unduh CSV Penilaian</a>
          <form method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA data penilaian masuk?');">
            <input type="hidden" name="action" value="clear_all_grades">
            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.75rem;">⚠️ Reset</button>
          </form>
        </div>
      <?php endif; ?>
    </div>

    <div class="table-container" style="margin-bottom: 60px;">
      <?php if (empty($grades)): ?>
        <div class="no-data">
          <span>🗳️</span>
          <p>Belum ada rincian penilaian masuk dari kelompok penilai.</p>
        </div>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th style="width: 140px;">Waktu Penilaian</th>
              <th style="width: 160px;">Nama Kelompok Dinilai</th>
              <th style="width: 200px;">Skenario Kasus</th>
              <th style="text-align: center;">Empati (25%)</th>
              <th style="text-align: center;">Jujur (25%)</th>
              <th style="text-align: center;">Solusi (30%)</th>
              <th style="text-align: center;">Netiket (20%)</th>
              <th style="text-align: center;">Nilai Kelompok Penilai</th>
              <th style="width: 70px; text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            foreach ($grades_reversed as $row): 
            ?>
              <tr>
                <td style="font-family: var(--font-display); font-weight: 700;"><?php echo $no++; ?></td>
                <td class="waktu-col"><?php echo htmlspecialchars($row['waktu']); ?></td>
                <td class="tim-col"><?php echo htmlspecialchars($row['nama_tim']); ?></td>
                <td class="skenario-col"><?php echo htmlspecialchars($row['skenario']); ?></td>
                <td style="text-align: center;"><?php echo htmlspecialchars($row['empati']); ?></td>
                <td style="text-align: center;"><?php echo htmlspecialchars($row['transparansi']); ?></td>
                <td style="text-align: center;"><?php echo htmlspecialchars($row['solusi']); ?></td>
                <td style="text-align: center;"><?php echo htmlspecialchars($row['netiket']); ?></td>
                <td style="text-align: center; font-weight: 700; color: var(--lime-300);"><?php echo number_format($row['nilai_akhir'], 1); ?></td>
                <td style="text-align: center;">
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus baris penilaian masuk ini?');">
                    <input type="hidden" name="action" value="delete_grade">
                    <input type="hidden" name="row_idx" value="<?php echo $row['original_idx']; ?>">
                    <button type="submit" class="btn-delete-row">Hapus</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

  </div>

</body>
</html>

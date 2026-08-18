<?php
// Set session params to persist cookie for 24 hours (86400 seconds)
session_set_cookie_params(86400);
session_start();

// Admin Password Configuration
$admin_password = 'Cdxfs1qaz'; 

$error = '';

// Read existing config or set default
$config_file = 'config.json';
$config = ['show_keys' => true];
if (file_exists($config_file)) {
    $config_data = json_decode(file_get_contents($config_file), true);
    if (is_array($config_data)) {
        $config = array_merge($config, $config_data);
    }
}

// Handle update config settings POST request
if (isset($_POST['action']) && $_POST['action'] === 'update_settings') {
    $config['show_keys'] = isset($_POST['show_keys']) && $_POST['show_keys'] == '1';
    $config['question_count'] = isset($_POST['question_count']) ? intval($_POST['question_count']) : 20;
    if ($config['question_count'] < 1) {
        $config['question_count'] = 1;
    }
    file_put_contents($config_file, json_encode($config, JSON_PRETTY_PRINT));
    
    header("Location: hasil.php");
    exit();
}

// Handle Login Submission
if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['login_time'] = time();
        header("Location: hasil.php");
        exit();
    } else {
        $error = 'Kata sandi salah!';
    }
}

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();
    session_destroy();
    header("Location: hasil.php");
    exit();
}

// Check Authentication and 24-hour Expiry
$is_authenticated = false;
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // 86400 seconds = 24 Hours
    if (time() - $_SESSION['login_time'] < 86400) {
        $is_authenticated = true;
    } else {
        // Expired session
        $_SESSION = array();
        session_destroy();
        $error = 'Sesi Anda telah berakhir (24 jam). Harap masuk kembali.';
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
  <title>Akses Terproteksi - Dashboard Hasil KKA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #8b5cf6;
      --primary-hover: #7c3aed;
      --bg-dark: #07050f;
      --card-bg: rgba(255, 255, 255, 0.03);
      --border-glass: rgba(255, 255, 255, 0.08);
      --border-glass-focus: rgba(139, 92, 246, 0.4);
      --text-main: #f3f4f6;
      --text-secondary: #c7d2fe;
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
      background-image: radial-gradient(rgba(139, 92, 246, 0.04) 1px, transparent 1px);
      background-size: 24px 24px;
    }

    .login-container {
      width: 100%;
      max-width: 440px;
      background: var(--card-bg);
      border: 1px solid var(--border-glass);
      border-radius: 20px;
      padding: 40px;
      backdrop-filter: blur(12px);
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
      text-align: center;
    }

    .lock-icon {
      font-size: 3rem;
      margin-bottom: 20px;
      display: inline-block;
    }

    <h2> {
      font-family: var(--font-display);
      font-size: 1.6rem;
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 8px;
    }

    p {
      font-size: 0.9rem;
      color: var(--text-secondary);
      margin-bottom: 24px;
      line-height: 1.5;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .form-group label {
      display: block;
      font-size: 0.8rem;
      color: var(--text-secondary);
      margin-bottom: 6px;
      font-weight: 600;
    }

    .form-group input {
      width: 100%;
      padding: 12px 16px;
      border-radius: 8px;
      border: 1.5px solid var(--border-glass);
      background: rgba(0, 0, 0, 0.35);
      color: #ffffff;
      font-family: var(--font-body);
      font-size: 0.95rem;
      outline: none;
      transition: all 0.2s;
    }

    .form-group input:focus {
      border-color: var(--primary);
    }

    .btn {
      width: 100%;
      padding: 12px;
      font-family: var(--font-body);
      font-weight: 700;
      font-size: 0.95rem;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      background: var(--primary);
      color: #ffffff;
      transition: all 0.2s;
      box-shadow: 0 4px 14px rgba(139, 92, 246, 0.2);
    }

    .btn:hover {
      background: var(--primary-hover);
      box-shadow: 0 6px 20px rgba(139, 92, 246, 0.3);
    }

    .error-msg {
      color: var(--danger);
      background: rgba(239, 68, 68, 0.1);
      border: 1.5px solid rgba(239, 68, 68, 0.2);
      border-radius: 8px;
      padding: 10px;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <span class="lock-icon">🔒</span>
    <h2>Akses Dashboard KKA</h2>
    <p>Silakan masukkan kata sandi guru untuk melihat rekapitulasi nilai siswa.</p>

    <?php if (!empty($error)): ?>
      <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="hasil.php">
      <div class="form-group">
        <label for="password-input">KATA SANDI GURU:</label>
        <input type="password" id="password-input" name="password" placeholder="Masukkan kata sandi" required autofocus>
      </div>
      <button type="submit" class="btn">Masuk Ke Dashboard</button>
    </form>
    
    <div style="margin-top: 20px;">
      <a href="index.html" style="font-size: 0.85rem; color: var(--primary); text-decoration: none;">🏠 Kembali Ke Beranda</a>
    </div>
  </div>

</body>
</html>
<?php
exit();
endif;

// --- AUTHENTICATED DASHBOARD CODE BELOW ---

$csv_file = 'hasil_latihan.csv';
$submissions = [];
$error_msg = '';

// Check if reset action is triggered
if (isset($_POST['action']) && $_POST['action'] === 'reset') {
    if (file_exists($csv_file)) {
        unlink($csv_file);
        header("Location: hasil.php?status=reset");
        exit();
    }
}

// Read CSV data if it exists
if (file_exists($csv_file)) {
    if (($handle = fopen($csv_file, "r")) !== FALSE) {
        // Skip UTF-8 BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }
        
        // Read headers
        $headers = fgetcsv($handle, 1000, ",");
        
        // Read data rows
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $col_count = count($data);
            if ($col_count >= 6) {
                $has_kategori = ($col_count >= 8 && !is_numeric($data[3]));
                if ($has_kategori) {
                    $submissions[] = [
                        'waktu' => $data[0],
                        'nama' => $data[1],
                        'kelas' => $data[2],
                        'kategori' => $data[3],
                        'nilai' => intval($data[4]),
                        'total' => intval($data[5]),
                        'persentase' => intval(str_replace('%', '', $data[6])),
                        'soal_dilihat' => isset($data[7]) ? $data[7] : '[]'
                    ];
                } else {
                    $submissions[] = [
                        'waktu' => $data[0],
                        'nama' => $data[1],
                        'kelas' => $data[2],
                        'kategori' => 'Semua Materi (Komprehensif)',
                        'nilai' => intval($data[3]),
                        'total' => intval($data[4]),
                        'persentase' => intval(str_replace('%', '', $data[5])),
                        'soal_dilihat' => isset($data[6]) ? $data[6] : '[]'
                    ];
                }
            }
        }
        fclose($handle);
    } else {
        $error_msg = 'Gagal membaca berkas hasil latihan.';
    }
}

// Calculate Stats
$total_students = count($submissions);
$average_score = 0;
$highest_score = 0;
$lowest_score = 100;

if ($total_students > 0) {
    $total_percent = 0;
    foreach ($submissions as $sub) {
        $total_percent += $sub['persentase'];
        if ($sub['persentase'] > $highest_score) {
            $highest_score = $sub['persentase'];
        }
        if ($sub['persentase'] < $lowest_score) {
            $lowest_score = $sub['persentase'];
        }
    }
    $average_score = round($total_percent / $total_students);
} else {
    $lowest_score = 0;
}

// Sort submissions by time descending
usort($submissions, function($a, $b) {
    return strcmp($b['waktu'], $a['waktu']);
});
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Hasil Latihan KKA - SMK Telkom Malang</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #8b5cf6;
      --primary-hover: #7c3aed;
      --bg-dark: #07050f;
      --card-bg: rgba(255, 255, 255, 0.03);
      --border-glass: rgba(255, 255, 255, 0.08);
      --border-glass-focus: rgba(139, 92, 246, 0.4);
      --text-main: #f3f4f6;
      --text-secondary: #c7d2fe;
      --font-display: 'Space Grotesk', sans-serif;
      --font-body: 'Manrope', sans-serif;
      --success: #10b981;
      --warning: #fbbf24;
      --danger: #ef4444;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: var(--font-body);
      background-color: var(--bg-dark);
      color: var(--text-main);
      padding: 40px 20px;
      line-height: 1.6;
      background-image: radial-gradient(rgba(139, 92, 246, 0.05) 1px, transparent 1px);
      background-size: 24px 24px;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
    }

    header {
      text-align: center;
      margin-bottom: 40px;
      position: relative;
    }

    header h1 {
      font-family: var(--font-display);
      font-size: clamp(28px, 4vw, 42px);
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 8px;
    }
    header h1 span { color: var(--primary); }

    /* Glassmorphism card styles */
    .glass-card {
      background: var(--card-bg);
      border: 1px solid var(--border-glass);
      border-radius: 16px;
      padding: 24px;
      backdrop-filter: blur(12px);
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
      margin-bottom: 30px;
    }

    /* Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }
    .stat-card {
      background: var(--card-bg);
      border: 1px solid var(--border-glass);
      border-radius: 14px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
    }
    .stat-card:hover {
      transform: translateY(-2px);
      border-color: var(--border-glass-focus);
    }
    .stat-label {
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--text-secondary);
      margin-bottom: 6px;
    }
    .stat-value {
      font-family: var(--font-display);
      font-size: 2rem;
      font-weight: 700;
      color: #ffffff;
    }
    .stat-value.avg { color: var(--warning); }
    .stat-value.high { color: var(--primary); }
    .stat-value.low { color: var(--danger); }

    /* Actions row */
    .action-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }
    .search-box {
      display: flex;
      flex-grow: 1;
      max-width: 400px;
    }
    .search-box input {
      width: 100%;
      padding: 12px 18px;
      border-radius: 8px;
      border: 1.5px solid var(--border-glass);
      background: rgba(0, 0, 0, 0.3);
      color: #ffffff;
      font-family: var(--font-body);
      font-size: 0.95rem;
      outline: none;
      transition: all 0.2s;
    }
    .search-box input:focus {
      border-color: var(--primary);
    }
    .btn-group {
      display: flex;
      gap: 12px;
    }
    .btn {
      padding: 12px 20px;
      border-radius: 8px;
      border: none;
      font-family: var(--font-body);
      font-weight: 600;
      font-size: 0.9rem;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
      text-decoration: none;
    }
    .btn-primary {
      background: var(--primary);
      color: #ffffff;
    }
    .btn-primary:hover {
      background: var(--primary-hover);
    }
    .btn-secondary {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-glass);
      color: #ffffff;
    }
    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.1);
    }
    .btn-danger {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.2);
      color: var(--danger);
    }
    .btn-danger:hover {
      background: rgba(239, 68, 68, 0.2);
    }

    /* Table styles */
    .table-container {
      overflow-x: auto;
      border-radius: 12px;
      border: 1px solid var(--border-glass);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }
    th, td {
      padding: 16px 20px;
      border-bottom: 1px solid var(--border-glass);
    }
    th {
      background: rgba(139, 92, 246, 0.05);
      font-family: var(--font-display);
      font-weight: 700;
      color: #ffffff;
      font-size: 0.95rem;
    }
    td {
      font-size: 0.95rem;
      color: var(--text-main);
    }
    tr:last-child td {
      border-bottom: none;
    }
    tr:hover td {
      background: rgba(255, 255, 255, 0.01);
    }
    .badge-score {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 999px;
      font-weight: 700;
      font-size: 0.85rem;
    }
    .badge-score.high {
      background: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }
    .badge-score.mid {
      background: rgba(251, 191, 36, 0.1);
      color: var(--warning);
    }
    .badge-score.low {
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger);
    }

    .no-data {
      text-align: center;
      padding: 60px 20px;
      color: var(--text-secondary);
    }
    .no-data span {
      font-size: 3rem;
      display: block;
      margin-bottom: 12px;
    }

    /* Modal dialog */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(4px);
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s ease;
    }
    .modal-overlay.active {
      opacity: 1;
      pointer-events: all;
    }
    .modal {
      background: var(--bg-dark);
      border: 1px solid var(--border-glass);
      border-radius: 16px;
      padding: 30px;
      max-width: 500px;
      width: 100%;
      text-align: center;
      box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    }
    .modal h3 {
      font-family: var(--font-display);
      font-size: 1.3rem;
      margin-bottom: 12px;
      color: #ffffff;
    }
    .modal p {
      color: var(--text-secondary);
      font-size: 0.95rem;
      margin-bottom: 24px;
    }
    
    th.sortable {
      cursor: pointer;
      user-select: none;
      transition: background 0.2s;
      position: relative;
    }
    th.sortable:hover {
      background: rgba(139, 92, 246, 0.15) !important;
    }
  </style>
</head>
<body>

  <div class="container">
    <header>
      <!-- Logout Button (Top Right) -->
      <a href="hasil.php?action=logout" class="btn btn-secondary" style="position: absolute; right: 0; top: 0; padding: 8px 16px; font-size: 0.8rem;">🔒 Keluar Sesi</a>
      <h1>Dashboard Hasil <span>Latihan KKA</span></h1>
      <p style="color: var(--text-secondary);">Rangkuman Penyetoran Nilai Siswa SMK Telkom Malang</p>
    </header>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'reset'): ?>
      <div style="background: rgba(139, 92, 246, 0.1); border: 1.5px solid var(--primary); border-radius: 10px; padding: 14px; text-align: center; color: #ffffff; margin-bottom: 30px; font-weight: 500;">
        Penyimpanan database hasil kuis berhasil dikosongkan.
      </div>
    <?php endif; ?>

    <!-- 1. Stats Summary Panel -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-label">Total Siswa Ujian</div>
        <div class="stat-value"><?php echo $total_students; ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Rata-rata Nilai</div>
        <div class="stat-value avg"><?php echo $average_score; ?>%</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Skor Tertinggi</div>
        <div class="stat-value high"><?php echo $highest_score; ?>%</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Skor Terendah</div>
        <div class="stat-value low"><?php echo $lowest_score; ?>%</div>
      </div>
    </div>

    <!-- 2. Configuration Settings Panel -->
    <div class="glass-card" style="margin-bottom: 25px; padding: 20px; border-color: rgba(139, 92, 246, 0.25); background: rgba(139, 92, 246, 0.02);">
      <h3 style="font-family: var(--font-display); font-size: 1.15rem; color: #ffffff; margin-bottom: 12px;">⚙️ PENGATURAN KUIS SISWA</h3>
      
      <form method="POST" action="hasil.php" style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-end;">
        <input type="hidden" name="action" value="update_settings">
        
        <div style="flex: 1; min-width: 250px; text-align: left;">
          <label style="font-size: 0.85rem; color: var(--text-secondary); display: block; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Akses Kunci Jawaban</label>
          <label style="font-size: 0.95rem; font-weight: 600; color: #ffffff; cursor: pointer; display: flex; align-items: center; gap: 10px; padding: 10px 0;">
            <input type="checkbox" name="show_keys" value="1" <?php echo (!isset($config['show_keys']) || $config['show_keys']) ? 'checked' : ''; ?> style="width: 20px; height: 20px; accent-color: var(--primary); cursor: pointer;">
            Tampilkan Kunci Jawaban (Saat Review Siswa)
          </label>
        </div>
        
        <div style="width: 220px; text-align: left;">
          <label for="question_count_setting" style="font-size: 0.85rem; color: var(--text-secondary); display: block; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Jumlah Soal Ditampilkan</label>
          <input type="number" id="question_count_setting" name="question_count" value="<?php echo isset($config['question_count']) ? intval($config['question_count']) : 20; ?>" min="1" max="600" required style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1.5px solid var(--border-glass); background: rgba(9, 10, 15, 0.8); color: #ffffff; font-family: var(--font-body); font-size: 0.95rem; outline: none; transition: all 0.2s;">
        </div>
        
        <div>
          <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-size: 0.9rem; height: 42px;">Simpan Pengaturan</button>
        </div>
      </form>
    </div>

    <!-- 3. Data Table Card -->
    <div class="glass-card">
      <div class="action-row" style="display: flex; flex-direction: column; gap: 15px; align-items: stretch; margin-bottom: 25px;">
        <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center; justify-content: space-between;">
          <h3 style="font-family: var(--font-display); font-size: 1.2rem; color: #ffffff;">📊 REKAPITULASI HASIL KUIS</h3>
          
          <div class="btn-group">
            <?php if ($total_students > 0): ?>
              <a href="hasil_latihan.csv" id="downloadBtn" class="btn btn-secondary" download="hasil_latihan.csv">📥 Unduh Semua (CSV)</a>
              <button class="btn btn-danger" onclick="openResetModal()">⚠️ Kosongkan Database</button>
            <?php endif; ?>
            <a href="index.html" class="btn btn-primary">🌐 Buka Portal Utama</a>
          </div>
        </div>

        <!-- Filter Bar -->
        <div style="display: flex; flex-wrap: wrap; gap: 12px; width: 100%;">
          <!-- Search box -->
          <div style="flex: 2; min-width: 250px;">
            <input type="text" id="tableFilter" placeholder="Cari nama, kelas, atau kata kunci soal..." onkeyup="filterResultsTable()" style="width: 100%; padding: 12px 18px; border-radius: 8px; border: 1.5px solid var(--border-glass); background: rgba(0, 0, 0, 0.3); color: #ffffff; font-family: var(--font-body); font-size: 0.95rem; outline: none; transition: all 0.2s;">
          </div>
          
          <!-- Category dropdown -->
          <div style="flex: 1.5; min-width: 200px;">
            <select id="categoryFilter" onchange="filterResultsTable()" style="width: 100%; padding: 12px 18px; border-radius: 8px; border: 1.5px solid var(--border-glass); background: rgba(0, 0, 0, 0.3); color: #ffffff; font-family: var(--font-body); font-size: 0.95rem; outline: none; transition: all 0.2s; cursor: pointer;">
              <option value="all">Semua Kategori/Materi</option>
              <option value="Komprehensif">Semua Materi (Komprehensif)</option>
              <option value="Materi 1">Materi 1: Berpikir Komputasional</option>
              <option value="Materi 2">Materi 2: Literasi Digital</option>
              <option value="Materi 3">Materi 3: Algoritma Pemrograman</option>
              <option value="Materi 4">Materi 4: Analisis Data</option>
              <option value="Materi 5">Materi 5: Literasi & Etika Kecerdasan Artifisial</option>
              <option value="Materi 6">Materi 6: Pemanfaatan & Pengembangan Kecerdasan Artifisial</option>
            </select>
          </div>

          <!-- Score/Status dropdown -->
          <div style="flex: 1; min-width: 150px;">
            <select id="scoreFilter" onchange="filterResultsTable()" style="width: 100%; padding: 12px 18px; border-radius: 8px; border: 1.5px solid var(--border-glass); background: rgba(0, 0, 0, 0.3); color: #ffffff; font-family: var(--font-body); font-size: 0.95rem; outline: none; transition: all 0.2s; cursor: pointer;">
              <option value="all">Semua Nilai</option>
              <option value="high">Sangat Kompeten (>= 80%)</option>
              <option value="mid">Cukup Kompeten (60% - 79%)</option>
              <option value="low">Belum Kompeten (< 60%)</option>
            </select>
          </div>
        </div>
      </div>

      <?php if ($total_students > 0): ?>
        <div class="table-container">
          <table id="resultsTable">
            <thead>
              <tr>
                <th class="sortable" onclick="sortTable(0, false)">Waktu Setor</th>
                <th class="sortable" onclick="sortTable(1, false)">Nama Lengkap</th>
                <th>Kelas</th>
                <th>Kategori/Materi</th>
                <th>Benar</th>
                <th>Total Soal</th>
                <th class="sortable" onclick="sortTable(6, true)">Nilai Akhir</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($submissions as $sub): ?>
                <?php
                // Determine color code badge based on score
                $badge_class = 'low';
                if ($sub['persentase'] >= 80) {
                    $badge_class = 'high';
                } elseif ($sub['persentase'] >= 60) {
                    $badge_class = 'mid';
                }
                ?>
                <tr data-kategori="<?php echo htmlspecialchars($sub['kategori']); ?>" data-persentase="<?php echo $sub['persentase']; ?>" data-soal="<?php echo htmlspecialchars($sub['soal_dilihat']); ?>">
                  <td style="color: var(--text-secondary);"><?php echo htmlspecialchars($sub['waktu']); ?></td>
                  <td style="font-weight: 600; color: #ffffff;"><?php echo htmlspecialchars($sub['nama']); ?></td>
                  <td><?php echo htmlspecialchars($sub['kelas']); ?></td>
                  <td style="color: var(--text-secondary);"><?php echo htmlspecialchars($sub['kategori']); ?></td>
                  <td><?php echo htmlspecialchars($sub['nilai']); ?></td>
                  <td><?php echo htmlspecialchars($sub['total']); ?></td>
                  <td>
                    <span class="badge-score <?php echo $badge_class; ?>">
                      <?php echo htmlspecialchars($sub['persentase']); ?>%
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-secondary" onclick="showDetailModal('<?php echo addslashes(htmlspecialchars($sub['nama'])); ?>', '<?php echo addslashes(htmlspecialchars($sub['soal_dilihat'])); ?>')" style="padding: 6px 12px; font-size: 0.8rem; border-radius: 6px; font-weight: 700;">
                      👁️ Lihat Soal
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="no-data">
          <span>📭</span>
          <h3>Belum Ada Nilai Tersimpan</h3>
          <p>Belum ada siswa yang mengirimkan nilai kuis ke server saat ini.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Reset Confirmation Modal -->
  <div id="resetModal" class="modal-overlay">
    <div class="modal">
      <h3>Konfirmasi Hapus Data</h3>
      <p>Apakah Anda yakin ingin menghapus seluruh rekaman nilai siswa dari server? Tindakan ini permanen dan tidak dapat dibatalkan.</p>
      <form method="POST" action="hasil.php" style="display: flex; gap: 12px; justify-content: center;">
        <input type="hidden" name="action" value="reset">
        <button type="button" class="btn btn-secondary" onclick="closeResetModal()">Kembali</button>
        <button type="submit" class="btn btn-danger" style="background:var(--danger); color:white;">Ya, Hapus Semua</button>
      </form>
    </div>
  </div>

  <!-- Viewed Questions Detail Modal -->
  <div id="detailModal" class="modal-overlay">
    <div class="modal" style="max-width: 600px; text-align: left;">
      <h3 id="modalTitle" style="margin-bottom: 15px; border-bottom: 1px solid var(--border-glass); padding-bottom: 10px; color:#ffffff; font-family:var(--font-display);">Detail Kunci Jawaban yang Dilihat</h3>
      <div id="modalContent" style="max-height: 400px; overflow-y: auto; font-size: 0.9rem; line-height: 1.5; color: var(--text-main); margin-bottom: 20px; padding-right: 8px;">
        <!-- Loaded dynamically -->
      </div>
      <div style="text-align: right;">
        <button type="button" class="btn btn-secondary" onclick="closeDetailModal()" style="width:auto; padding: 8px 18px;">Tutup</button>
      </div>
    </div>
  </div>

  <script>
    // Live Client-side Table Filtering
    function filterResultsTable() {
      const searchQuery = document.getElementById("tableFilter").value.toUpperCase();
      const selectedCategory = document.getElementById("categoryFilter").value;
      const selectedScore = document.getElementById("scoreFilter").value;
      
      const table = document.getElementById("resultsTable");
      if (!table) return;
      
      const tr = table.getElementsByTagName("tr");
      
      for (let i = 1; i < tr.length; i++) {
        const row = tr[i];
        const tdNama = row.getElementsByTagName("td")[1];
        const tdKelas = row.getElementsByTagName("td")[2];
        
        if (tdNama && tdKelas) {
          const nameText = tdNama.textContent || tdNama.innerText;
          const classText = tdKelas.textContent || tdKelas.innerText;
          
          const rowCategory = row.getAttribute("data-kategori") || "";
          const rowPercentage = parseInt(row.getAttribute("data-persentase")) || 0;
          const rowSoalJson = row.getAttribute("data-soal") || "[]";
          
          // Search matching
          let searchMatch = false;
          if (nameText.toUpperCase().indexOf(searchQuery) > -1 || classText.toUpperCase().indexOf(searchQuery) > -1) {
            searchMatch = true;
          } else {
            // Also check if any question matches the search query
            try {
              const viewedQuestions = JSON.parse(rowSoalJson);
              if (Array.isArray(viewedQuestions)) {
                for (let q of viewedQuestions) {
                  if (q.toUpperCase().indexOf(searchQuery) > -1) {
                    searchMatch = true;
                    break;
                  }
                }
              }
            } catch (e) {
              // Ignore json parse error
            }
          }
          
          // Category matching
          let categoryMatch = false;
          if (selectedCategory === "all") {
            categoryMatch = true;
          } else if (selectedCategory === "Komprehensif") {
            categoryMatch = rowCategory.toLowerCase().includes("komprehensif");
          } else {
            categoryMatch = rowCategory.includes(selectedCategory);
          }
          
          // Score matching
          let scoreMatch = false;
          if (selectedScore === "all") {
            scoreMatch = true;
          } else if (selectedScore === "high") {
            scoreMatch = (rowPercentage >= 80);
          } else if (selectedScore === "mid") {
            scoreMatch = (rowPercentage >= 60 && rowPercentage < 80);
          } else if (selectedScore === "low") {
            scoreMatch = (rowPercentage < 60);
          }
          
          if (searchMatch && categoryMatch && scoreMatch) {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        }
      }
      updateDownloadButton(selectedCategory);
    }

    // Dynamic Column Sorting
    let currentSortColumn = -1;
    let currentSortDirection = true; // true = asc, false = desc

    function sortTable(columnIndex, numeric = false) {
      const table = document.getElementById("resultsTable");
      if (!table) return;
      const tbody = table.querySelector("tbody");
      const rows = Array.from(tbody.querySelectorAll("tr"));
      
      if (currentSortColumn === columnIndex) {
        currentSortDirection = !currentSortDirection;
      } else {
        currentSortColumn = columnIndex;
        currentSortDirection = true;
      }
      
      // Sort rows
      rows.sort((rowA, rowB) => {
        let cellA = rowA.cells[columnIndex].textContent || rowA.cells[columnIndex].innerText;
        let cellB = rowB.cells[columnIndex].textContent || rowB.cells[columnIndex].innerText;
        
        cellA = cellA.trim();
        cellB = cellB.trim();
        
        if (numeric) {
          const valA = parseFloat(cellA.replace(/%/g, '')) || 0;
          const valB = parseFloat(cellB.replace(/%/g, '')) || 0;
          return currentSortDirection ? valA - valB : valB - valA;
        } else {
          // Special date parsing for column 0
          if (columnIndex === 0) {
            const dateA = parseDateTime(cellA);
            const dateB = parseDateTime(cellB);
            return currentSortDirection ? dateA - dateB : dateB - dateA;
          }
          return currentSortDirection ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
        }
      });
      
      // Re-append rows
      tbody.innerHTML = "";
      rows.forEach(row => tbody.appendChild(row));
      
      // Update indicators
      updateSortIndicators(columnIndex);
    }

    function parseDateTime(dateTimeStr) {
      // Format: dd/mm/yyyy, hh.mm.ss or dd/mm/yyyy, hh:mm:ss
      const cleaned = dateTimeStr.replace(/\./g, ':');
      const parts = cleaned.split(', ');
      if (parts.length < 2) return new Date(0);
      
      const dateParts = parts[0].split('/');
      const timeParts = parts[1].split(':');
      
      if (dateParts.length < 3 || timeParts.length < 3) return new Date(0);
      
      const day = parseInt(dateParts[0], 10);
      const month = parseInt(dateParts[1], 10) - 1;
      const year = parseInt(dateParts[2], 10);
      
      const hour = parseInt(timeParts[0], 10);
      const minute = parseInt(timeParts[1], 10);
      const second = parseInt(timeParts[2], 10);
      
      return new Date(year, month, day, hour, minute, second);
    }

    function updateSortIndicators(sortedColIdx) {
      const table = document.getElementById("resultsTable");
      const headers = table.querySelectorAll("thead th");
      headers.forEach((th, idx) => {
        // Remove existing arrow indicators
        th.innerHTML = th.innerHTML.replace(/ (▲|▼)$/g, '');
        if (idx === sortedColIdx) {
          th.innerHTML += currentSortDirection ? ' ▲' : ' ▼';
        }
      });
    }

    // Modal Control
    function openResetModal() {
      document.getElementById('resetModal').classList.add('active');
    }
    function closeResetModal() {
      document.getElementById('resetModal').classList.remove('active');
    }

    // Show Detail Modal
    function showDetailModal(nama, soalDilihatJson) {
      document.getElementById('modalTitle').innerHTML = `Soal yang Dilihat Kunci Jawabannya oleh: <br><span style="color:var(--primary); font-weight:700; font-size: 1.15rem;">${nama}</span>`;
      const content = document.getElementById('modalContent');
      content.innerHTML = '';

      let list = [];
      try {
        list = JSON.parse(soalDilihatJson);
      } catch (e) {
        list = [];
      }

      if (list && list.length > 0) {
        const ol = document.createElement('ol');
        ol.style.paddingLeft = '20px';
        ol.style.display = 'flex';
        ol.style.flexDirection = 'column';
        ol.style.gap = '12px';

        list.forEach(item => {
          const li = document.createElement('li');
          li.style.color = 'var(--text-main)';
          li.textContent = item;
          ol.appendChild(li);
        });
        content.appendChild(ol);
      } else {
        content.innerHTML = '<p style="color: var(--text-secondary); text-align: center; padding: 30px 20px;">Siswa ini menjawab semua soal dengan benar atau tidak memicu penampilan kunci jawaban (tidak ada soal salah).</p>';
      }

      document.getElementById('detailModal').classList.add('active');
    }

    // Close Detail Modal
    function closeDetailModal() {
      document.getElementById('detailModal').classList.remove('active');
    }

    function updateDownloadButton(selectedCategory) {
      const downloadBtn = document.getElementById("downloadBtn");
      if (!downloadBtn) return;
      
      let href = "hasil_latihan.csv";
      let text = "📥 Unduh Semua (CSV)";
      
      if (selectedCategory !== "all") {
        if (selectedCategory === "Komprehensif") {
          href = "hasil_latihan_komprehensif.csv";
          text = "📥 Unduh CSV Komprehensif";
        } else {
          const match = selectedCategory.match(/(modul|materi|stage)\s*(\d+)/i);
          if (match) {
            const type = match[1].toLowerCase();
            const num = parseInt(match[2]);
            href = `hasil_latihan_${type}_${num}.csv`;
            text = `📥 Unduh CSV ${match[1]} ${num}`;
          }
        }
      }
      
      downloadBtn.setAttribute("href", href);
      downloadBtn.setAttribute("download", href);
      downloadBtn.textContent = text;
    }
  </script>
</body>
</html>

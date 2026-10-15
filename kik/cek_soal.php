<?php
// Set session params to persist cookie for 24 hours (86400 seconds)
session_set_cookie_params(86400);
session_start();

// Admin Password Configuration
$admin_password = 'Cdxfs1qaz'; 

$error = '';

// Handle Login Submission
if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['login_time'] = time();
        header("Location: cek_soal.php");
        exit();
    } else {
        $error = 'Kata sandi salah!';
    }
}

// Check Authentication and 24-hour Expiry
$is_authenticated = false;
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (time() - $_SESSION['login_time'] < 86400) {
        $is_authenticated = true;
    } else {
        // Expired session
        $_SESSION = array();
        session_destroy();
        $error = 'Sesi Anda telah berakhir (24 jam). Harap masuk kembali.';
    }
}

// Handle AJAX Save Question POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_question') {
    if (!$is_authenticated) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Sesi tidak valid atau belum masuk sebagai admin.']);
        exit();
    }

    $modul = isset($_POST['modul']) ? intval($_POST['modul']) : 0;
    $no = isset($_POST['no']) ? intval($_POST['no']) : 0;
    $question = isset($_POST['question']) ? trim($_POST['question']) : '';
    $options = isset($_POST['options']) ? $_POST['options'] : null;
    $answer_hash = isset($_POST['answer_hash']) ? trim($_POST['answer_hash']) : '';

    if ($modul < 1 || $modul > 8 || $no < 1 || empty($question) || !$options || count($options) < 5 || empty($answer_hash)) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap atau data tidak valid.']);
        exit();
    }

    $json_file = "soal/materi{$modul}.json";
    if (!file_exists($json_file)) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => "Berkas bank soal materi {$modul} tidak ditemukan."]);
        exit();
    }

    // Read JSON
    $json_content = file_get_contents($json_file);
    $data = json_decode($json_content, true);
    if (!$data || !isset($data['daftar_soal'])) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => "Format berkas bank soal materi {$modul} tidak valid."]);
        exit();
    }

    // Create Backup first
    $backup_file = "soal/backup_materi{$modul}.json";
    file_put_contents($backup_file, $json_content);

    // Update question
    $found = false;
    foreach ($data['daftar_soal'] as &$q) {
        if (intval($q['no']) === $no) {
            $q['question'] = $question;
            $q['options'] = [
                'A' => trim($options['A']),
                'B' => trim($options['B']),
                'C' => trim($options['C']),
                'D' => trim($options['D']),
                'E' => trim($options['E'])
            ];
            $q['answer_hash'] = $answer_hash;
            $found = true;
            break;
        }
    }

    if (!$found) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => "Soal nomor {$no} tidak ditemukan dalam berkas."]);
        exit();
    }

    // Write back
    $write_status = file_put_contents($json_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Content-Type: application/json');
    if ($write_status !== false) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menulis berkas ke server. Cek izin akses folder/berkas.']);
    }
    exit();
}

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();
    session_destroy();
    header("Location: cek_soal.php");
    exit();
}

// If not authenticated, render Login Screen
if (!$is_authenticated):
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Akses Terproteksi - Editor Bank Soal KIK</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #10b981;
      --primary-hover: #059669;
      --bg-dark: #090d0b;
      --card-bg: rgba(255, 255, 255, 0.03);
      --border-glass: rgba(255, 255, 255, 0.08);
      --border-glass-focus: rgba(16, 185, 129, 0.4);
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
      background-image: radial-gradient(rgba(16, 185, 129, 0.04) 1px, transparent 1px);
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

    h2 {
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
      box-shadow: 0 4px 14px rgba(16, 185, 129, 0.2);
    }

    .btn:hover {
      background: var(--primary-hover);
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
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
    <h2>Akses Editor Bank Soal</h2>
    <p>Silakan masukkan kata sandi guru untuk mengedit & melihat bank soal.</p>

    <?php if (!empty($error)): ?>
      <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="cek_soal.php">
      <div class="form-group">
        <label for="password-input">KATA SANDI GURU:</label>
        <input type="password" id="password-input" name="password" placeholder="Masukkan kata sandi" required autofocus>
      </div>
      <button type="submit" class="btn">Masuk Ke Editor</button>
    </form>
    
    <div style="margin-top: 20px;">
      <a href="hasil.php" style="font-size: 0.85rem; color: var(--primary); text-decoration: none;">📊 Kembali Ke Dashboard Nilai</a>
    </div>
  </div>

</body>
</html>
<?php
exit();
endif;

// --- AUTHENTICATED EDITOR CODE BELOW ---
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editor & Pengecekan Bank Soal KIK - SMK Telkom Malang</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #10b981;
      --primary-hover: #059669;
      --bg-dark: #090d0b;
      --card-bg: rgba(255, 255, 255, 0.03);
      --border-glass: rgba(255, 255, 255, 0.08);
      --border-glass-focus: rgba(16, 185, 129, 0.4);
      --text-main: #f3f4f6;
      --text-secondary: #9ca3af;
      --text-muted: #6b7280;
      --font-display: 'Space Grotesk', sans-serif;
      --font-body: 'Manrope', sans-serif;
      
      --correct-bg: rgba(16, 185, 129, 0.12);
      --correct-border: #10b981;
      --danger: #ef4444;
      --warning: #fbbf24;
      --success: #10b981;
      
      --border-radius-sm: 8px;
      --border-radius-md: 14px;
      --border-radius-lg: 20px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: var(--font-body);
      background-color: var(--bg-dark);
      color: var(--text-main);
      min-height: 100vh;
      padding: 40px 20px;
      background-image: radial-gradient(rgba(16, 185, 129, 0.04) 1px, transparent 1px);
      background-size: 24px 24px;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
    }

    header {
      position: relative;
      margin-bottom: 40px;
      text-align: center;
    }
    header h1 {
      font-family: var(--font-display);
      font-size: clamp(26px, 4vw, 38px);
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 8px;
    }
    header h1 span { color: var(--primary); }
    header p {
      font-size: 1rem;
      color: var(--text-secondary);
    }

    /* Glassmorphism panel layout */
    .editor-layout {
      display: grid;
      grid-template-columns: 350px 1fr;
      gap: 30px;
      align-items: start;
    }

    @media (max-width: 900px) {
      .editor-layout {
        grid-template-columns: 1fr;
      }
    }

    .glass-card {
      background: var(--card-bg);
      border: 1px solid var(--border-glass);
      border-radius: var(--border-radius-lg);
      padding: 30px;
      backdrop-filter: blur(12px);
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
      margin-bottom: 30px;
    }

    /* Buttons */
    .btn {
      padding: 12px 20px;
      border-radius: var(--border-radius-sm);
      border: none;
      font-family: var(--font-body);
      font-weight: 700;
      font-size: 0.95rem;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.2s ease;
      text-decoration: none;
      color: #ffffff;
    }
    .btn-primary {
      background: var(--primary);
      box-shadow: 0 4px 14px rgba(16, 185, 129, 0.2);
    }
    .btn-primary:hover {
      background: var(--primary-hover);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    }
    .btn-secondary {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-glass);
    }
    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.1);
    }
    .btn-danger {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.2);
      color: var(--danger);
    }
    .btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
      transform: none !important;
      box-shadow: none !important;
    }

    /* Forms */
    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }
    .form-group label {
      display: block;
      font-size: 0.85rem;
      color: var(--text-secondary);
      margin-bottom: 8px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .form-group select, .form-group input, .form-group textarea {
      width: 100%;
      padding: 12px 16px;
      border-radius: var(--border-radius-sm);
      border: 1.5px solid var(--border-glass);
      background: rgba(0, 0, 0, 0.3);
      color: #ffffff;
      font-family: var(--font-body);
      font-size: 0.95rem;
      outline: none;
      transition: all 0.2s;
    }
    .form-group select:focus, .form-group input:focus, .form-group textarea:focus {
      border-color: var(--primary);
      background: rgba(0, 0, 0, 0.5);
    }
    .form-group select option {
      background: var(--bg-dark);
      color: #ffffff;
    }

    /* Questions Grid Selector */
    .q-grid-container {
      margin-top: 15px;
    }
    .q-grid-label {
      font-size: 0.8rem;
      color: var(--text-secondary);
      font-weight: 700;
      text-transform: uppercase;
      margin-bottom: 10px;
    }
    .q-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 8px;
      max-height: 320px;
      overflow-y: auto;
      padding-right: 8px;
    }
    .q-grid::-webkit-scrollbar {
      width: 6px;
    }
    .q-grid::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 99px;
    }
    .q-grid-btn {
      aspect-ratio: 1;
      border-radius: 6px;
      border: 1px solid var(--border-glass);
      background: rgba(255, 255, 255, 0.02);
      color: var(--text-secondary);
      font-weight: 700;
      font-size: 0.85rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
    }
    .q-grid-btn:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #ffffff;
    }
    .q-grid-btn.active {
      background: var(--primary);
      border-color: var(--primary);
      color: #ffffff;
      box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
    }

    /* Option Edit Card Layout */
    .option-edit-row {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      background: rgba(255, 255, 255, 0.01);
      border: 1.5px solid var(--border-glass);
      border-radius: var(--border-radius-md);
      padding: 16px;
      margin-bottom: 12px;
      transition: all 0.2s;
    }
    .option-edit-row.correct-key {
      background: var(--correct-bg);
      border-color: var(--correct-border);
    }
    .option-edit-row:focus-within {
      border-color: var(--border-glass-focus);
    }
    .option-radio-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
      padding-top: 10px;
    }
    .option-radio-container input[type="radio"] {
      width: 20px;
      height: 20px;
      accent-color: var(--primary);
      cursor: pointer;
    }
    .option-key-label {
      font-size: 0.75rem;
      font-weight: 700;
      color: var(--text-secondary);
    }
    .option-edit-row.correct-key .option-key-label {
      color: var(--primary);
    }
    .option-letter-badge {
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 32px;
      height: 32px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-glass);
      color: #ffffff;
      font-weight: 800;
      font-size: 0.9rem;
      margin-top: 6px;
    }
    .option-edit-row.correct-key .option-letter-badge {
      background: var(--primary);
      border-color: var(--primary);
    }
    .option-textarea-wrapper {
      flex-grow: 1;
    }
    .option-textarea-wrapper textarea {
      width: 100%;
      min-height: 60px;
      padding: 8px 12px;
      border: none;
      background: transparent;
      color: #ffffff;
      font-family: var(--font-body);
      font-size: 0.95rem;
      resize: vertical;
      outline: none;
    }

    /* Aux Info */
    .hash-badge {
      font-family: monospace;
      font-size: 0.8rem;
      padding: 4px 8px;
      background: rgba(0, 0, 0, 0.3);
      border-radius: 4px;
      border: 1px solid var(--border-glass);
      color: var(--text-secondary);
    }
    .hash-badge.matched {
      color: var(--primary);
      border-color: rgba(16, 185, 129, 0.3);
    }
    
    .status-msg {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 0.9rem;
      font-weight: 700;
      margin-right: 15px;
    }

    /* Header Link Menu */
    .menu-bar {
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-50%);
      display: flex;
      gap: 12px;
    }
    @media (max-width: 768px) {
      header {
        padding-top: 40px;
      }
      .menu-bar {
        position: static;
        transform: none;
        justify-content: center;
        margin-top: 15px;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <header>
      <div class="menu-bar">
        <a href="hasil.php" class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.85rem;">📊 Dashboard Nilai</a>
        <a href="index.html" class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.85rem;">🌐 Portal KIK</a>
        <a href="cek_soal.php?action=logout" class="btn btn-danger" style="padding: 8px 16px; font-size: 0.85rem;">🔒 Keluar Sesi</a>
      </div>
      <div style="font-size: 0.85rem; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; color: var(--primary); margin-bottom: 10px;">SMK Telkom Malang</div>
      <h1>Manajemen & <span>Cek Bank Soal</span> KIK</h1>
      <p>Pengecekan berurutan, pemilihan langsung, dan modifikasi konten soal</p>
    </header>

    <main class="editor-layout">
      <!-- LEFT SIDEBAR: Navigasi Modul & Nomor -->
      <section class="glass-card" style="padding: 24px;">
        <h3 style="font-family: var(--font-display); font-size: 1.15rem; margin-bottom: 20px; color:#ffffff; border-bottom: 1px solid var(--border-glass); padding-bottom: 10px;">🧭 Navigasi Bank Soal</h3>
        
        <div class="form-group">
          <label for="modul-select">Pilih Modul Materi:</label>
          <select id="modul-select" onchange="changeModul()">
            <option value="1">Modul 1: Kewirausahaan, Kepemimpinan, dan Etika</option>
            <option value="2">Modul 2: Peluang Usaha, Perencanaan, & BMC</option>
            <option value="3">Modul 3: Desain dan Produksi Kreatif</option>
            <option value="4">Modul 4: Pemasaran, Branding, dan Pitching</option>
            <option value="5">Modul 5: Keuangan Usaha dan Pembiayaan</option>
            <option value="6">Modul 6: Manajemen Risiko Bisnis</option>
            <option value="7">Modul 7: Digitalisasi dan Teknologi Bisnis</option>
            <option value="8">Modul 8: Wirausaha Sosial & Berkelanjutan</option>
          </select>
        </div>

        <div class="form-group">
          <label for="question-select">Nomor Soal:</label>
          <select id="question-select" onchange="selectQuestionFromDropdown()">
            <!-- Loaded dynamically -->
          </select>
        </div>

        <div class="q-grid-container">
          <div class="q-grid-label">Grid Nomor Soal:</div>
          <div id="q-grid" class="q-grid">
            <!-- Grid buttons loaded dynamically -->
          </div>
        </div>
      </section>

      <!-- RIGHT SIDEBAR: Editor Form -->
      <section class="glass-card">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border-glass); padding-bottom: 15px; margin-bottom: 25px; flex-wrap: wrap; gap:10px;">
          <div>
            <h2 id="editor-title" style="font-family: var(--font-display); font-size: 1.3rem; color: #ffffff;">Memuat Editor...</h2>
            <span id="modul-badge" style="font-size: 0.75rem; color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing:0.05em; display:block; margin-top:4px;">Modul -</span>
          </div>
          <div style="display:flex; gap: 8px;">
            <button id="prev-btn" class="btn btn-secondary" onclick="navigateQuestion(-1)" style="padding: 8px 14px; font-size:0.85rem;">◀ Sebelumnya</button>
            <button id="next-btn" class="btn btn-secondary" onclick="navigateQuestion(1)" style="padding: 8px 14px; font-size:0.85rem;">Selanjutnya ▶</button>
          </div>
        </div>

        <div id="editor-form-body">
          <div class="form-group">
            <label for="question-text-input">Pertanyaan / Soal:</label>
            <textarea id="question-text-input" rows="4" placeholder="Ketikkan teks pertanyaan di sini..." oninput="markDirty()"></textarea>
          </div>

          <div class="form-group" style="margin-bottom: 12px;">
            <label>Opsi Jawaban & Kunci Jawaban:</label>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 15px; text-transform: none;">Pilih radio button di sebelah kiri opsi untuk menentukan **kunci jawaban yang benar**.</p>
            
            <div id="options-edit-container">
              <!-- Render A, B, C, D, E edit rows -->
              <?php foreach (['A', 'B', 'C', 'D', 'E'] as $letter): ?>
                <div class="option-edit-row" id="row-<?php echo $letter; ?>">
                  <div class="option-radio-container">
                    <input type="radio" name="correct-key-radio" id="radio-<?php echo $letter; ?>" value="<?php echo $letter; ?>" onchange="changeCorrectKey('<?php echo $letter; ?>')">
                    <label for="radio-<?php echo $letter; ?>" class="option-key-label">KUNCI</label>
                  </div>
                  <div class="option-letter-badge"><?php echo $letter; ?></div>
                  <div class="option-textarea-wrapper">
                    <textarea id="textarea-<?php echo $letter; ?>" placeholder="Teks opsi jawaban <?php echo $letter; ?>..." oninput="markDirty()"></textarea>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Bottom Action Area -->
          <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap: 15px; border-top: 1px solid var(--border-glass); padding-top:20px; margin-top:20px;">
            <div style="display:flex; flex-direction:column; gap:6px;">
              <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size: 0.8rem; color: var(--text-secondary); font-weight:700;">Hash Kunci (KIK Salt):</span>
                <span id="calculated-hash-display" class="hash-badge">Menghitung...</span>
              </div>
              <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size: 0.8rem; color: var(--text-secondary); font-weight:700;">Hash Tersimpan di File:</span>
                <span id="saved-hash-display" class="hash-badge">Memuat...</span>
              </div>
            </div>
            
            <div style="display:flex; align-items:center;">
              <span id="save-status" class="status-msg" style="display:none;"></span>
              <button id="save-btn" class="btn btn-primary" onclick="saveQuestion()" disabled>
                💾 Simpan Perubahan
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    // Constants
    const SALT = "kik_salt_smk_telkom_malang";

    // App State
    let loadedQuestions = [];
    let currentQuestionIndex = 0;
    let isDirty = false;

    // Hashing Utility
    function djb2Hash(str) {
      let hash = 5381;
      for (let i = 0; i < str.length; i++) {
        hash = ((hash << 5) + hash) + str.charCodeAt(i);
      }
      return (hash >>> 0).toString(16);
    }

    // Mark editor as dirty
    function markDirty() {
      isDirty = true;
      const saveBtn = document.getElementById('save-btn');
      saveBtn.disabled = false;
      
      const statusEl = document.getElementById('save-status');
      statusEl.style.color = 'var(--warning)';
      statusEl.innerHTML = '⚠️ Perubahan belum disimpan';
      statusEl.style.display = 'inline-flex';
      
      updateCalculatedHash();
    }

    // Reset dirty flag
    function resetDirty() {
      isDirty = false;
      document.getElementById('save-btn').disabled = true;
      document.getElementById('save-status').style.display = 'none';
    }

    // Change Correct Key selection
    function changeCorrectKey(selectedLetter) {
      // Toggle CSS classes on options container
      ['A', 'B', 'C', 'D', 'E'].forEach(letter => {
        const row = document.getElementById(`row-${letter}`);
        if (letter === selectedLetter) {
          row.classList.add('correct-key');
        } else {
          row.classList.remove('correct-key');
        }
      });
      markDirty();
    }

    // Calculate dynamic hash based on current text of the correct key
    function updateCalculatedHash() {
      const correctRadio = document.querySelector('input[name="correct-key-radio"]:checked');
      if (!correctRadio) {
        document.getElementById('calculated-hash-display').textContent = 'Kunci belum dipilih';
        document.getElementById('calculated-hash-display').className = 'hash-badge';
        return;
      }
      const correctLetter = correctRadio.value;
      const textVal = document.getElementById(`textarea-${correctLetter}`).value.trim();
      
      const hashVal = djb2Hash(textVal + SALT);
      const displayEl = document.getElementById('calculated-hash-display');
      displayEl.textContent = hashVal;
      
      const savedHash = document.getElementById('saved-hash-display').textContent;
      if (hashVal === savedHash) {
        displayEl.className = 'hash-badge matched';
      } else {
        displayEl.className = 'hash-badge';
      }
    }

    // Load modul questions
    async function loadModul(modulId) {
      try {
        const res = await fetch(`soal/materi${modulId}.json?t=` + Date.now());
        if (!res.ok) throw new Error('Gagal memuat bank soal.');
        const data = await res.json();
        
        loadedQuestions = data.daftar_soal || [];
        
        // Populate dropdown
        const qSelect = document.getElementById('question-select');
        qSelect.innerHTML = '';
        loadedQuestions.forEach((q, idx) => {
          const opt = document.createElement('option');
          opt.value = idx;
          opt.textContent = `Soal #${q.no} - ${q.question.substring(0, 50)}...`;
          qSelect.appendChild(opt);
        });

        // Populate Grid
        const grid = document.getElementById('q-grid');
        grid.innerHTML = '';
        loadedQuestions.forEach((q, idx) => {
          const btn = document.createElement('button');
          btn.className = 'q-grid-btn';
          btn.id = `grid-btn-${idx}`;
          btn.textContent = q.no;
          btn.onclick = () => selectQuestion(idx);
          grid.appendChild(btn);
        });

        // Load first question
        currentQuestionIndex = 0;
        loadQuestion(0);

      } catch (err) {
        console.error(err);
        alert('Gagal memuat file JSON bank soal: ' + err.message);
      }
    }

    // Change Modul from select
    function changeModul() {
      if (isDirty) {
        if (!confirm('Ada perubahan yang belum disimpan di soal saat ini! Apakah Anda yakin ingin berpindah modul dan membuang perubahan?')) {
          // Restore dropdown selection
          const currentModul = document.getElementById('modul-select').getAttribute('data-current');
          document.getElementById('modul-select').value = currentModul;
          return;
        }
      }
      
      const select = document.getElementById('modul-select');
      select.setAttribute('data-current', select.value);
      loadModul(select.value);
      resetDirty();
    }

    // Select Question
    function selectQuestion(idx) {
      if (idx < 0 || idx >= loadedQuestions.length) return;
      if (isDirty) {
        if (!confirm('Ada perubahan yang belum disimpan! Apakah Anda yakin ingin berpindah soal dan membuang perubahan?')) {
          return;
        }
      }
      
      currentQuestionIndex = idx;
      loadQuestion(idx);
      resetDirty();
    }

    // Dropdown Select Handler
    function selectQuestionFromDropdown() {
      const select = document.getElementById('question-select');
      selectQuestion(parseInt(select.value));
    }

    // Navigate question next / prev
    function navigateQuestion(direction) {
      const targetIdx = currentQuestionIndex + direction;
      if (targetIdx >= 0 && targetIdx < loadedQuestions.length) {
        selectQuestion(targetIdx);
      }
    }

    // Load question details to editor form
    function loadQuestion(idx) {
      const q = loadedQuestions[idx];
      if (!q) return;

      document.getElementById('editor-title').textContent = `Soal Nomor ${q.no} (Urutan JSON: ${idx + 1})`;
      const selectModul = document.getElementById('modul-select');
      document.getElementById('modul-badge').textContent = selectModul.options[selectModul.selectedIndex].text;
      
      // Update Active Grid Button
      const allGridBtns = document.querySelectorAll('.q-grid-btn');
      allGridBtns.forEach(btn => btn.classList.remove('active'));
      const activeGridBtn = document.getElementById(`grid-btn-${idx}`);
      if (activeGridBtn) {
        activeGridBtn.classList.add('active');
        activeGridBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }

      // Update question select dropdown
      document.getElementById('question-select').value = idx;

      // Populate text inputs
      document.getElementById('question-text-input').value = q.question;
      
      // Load options
      let matchedKey = null;
      ['A', 'B', 'C', 'D', 'E'].forEach(letter => {
        const optionVal = q.options[letter] || '';
        document.getElementById(`textarea-${letter}`).value = optionVal;
        
        // Verify hash to find which option matches the answer_hash
        const computedOptHash = djb2Hash(optionVal + SALT);
        const radio = document.getElementById(`radio-${letter}`);
        const row = document.getElementById(`row-${letter}`);
        
        if (computedOptHash === q.answer_hash) {
          radio.checked = true;
          row.classList.add('correct-key');
          matchedKey = letter;
        } else {
          radio.checked = false;
          row.classList.remove('correct-key');
        }
      });

      // Display Saved Hash
      document.getElementById('saved-hash-display').textContent = q.answer_hash;

      // Check navigation buttons state
      document.getElementById('prev-btn').disabled = (idx === 0);
      document.getElementById('next-btn').disabled = (idx === loadedQuestions.length - 1);

      updateCalculatedHash();
    }

    // Save Question AJAX
    async function saveQuestion() {
      const q = loadedQuestions[currentQuestionIndex];
      if (!q) return;

      const questionText = document.getElementById('question-text-input').value.trim();
      const optA = document.getElementById('textarea-A').value.trim();
      const optB = document.getElementById('textarea-B').value.trim();
      const optC = document.getElementById('textarea-C').value.trim();
      const optD = document.getElementById('textarea-D').value.trim();
      const optE = document.getElementById('textarea-E').value.trim();
      
      const correctRadio = document.querySelector('input[name="correct-key-radio"]:checked');
      if (!correctRadio) {
        alert('Harap tentukan satu opsi sebagai kunci jawaban yang benar!');
        return;
      }
      
      const correctLetter = correctRadio.value;
      const correctText = document.getElementById(`textarea-${correctLetter}`).value.trim();
      if (correctText === '') {
        alert('Opsi kunci jawaban yang terpilih tidak boleh kosong!');
        return;
      }

      if (questionText === '' || optA === '' || optB === '' || optC === '' || optD === '' || optE === '') {
        alert('Semua kolom pertanyaan dan opsi A-E tidak boleh kosong!');
        return;
      }

      const newHash = djb2Hash(correctText + SALT);

      const statusEl = document.getElementById('save-status');
      statusEl.style.color = 'var(--text-secondary)';
      statusEl.innerHTML = '⏳ Menyimpan perubahan...';
      statusEl.style.display = 'inline-flex';
      
      const saveBtn = document.getElementById('save-btn');
      saveBtn.disabled = true;

      const selectModul = document.getElementById('modul-select');
      const currentModul = selectModul.value;

      const formData = new URLSearchParams();
      formData.append('action', 'save_question');
      formData.append('modul', currentModul);
      formData.append('no', q.no);
      formData.append('question', questionText);
      formData.append('options[A]', optA);
      formData.append('options[B]', optB);
      formData.append('options[C]', optC);
      formData.append('options[D]', optD);
      formData.append('options[E]', optE);
      formData.append('answer_hash', newHash);

      try {
        const response = await fetch('cek_soal.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: formData.toString()
        });

        if (!response.ok) throw new Error('Koneksi jaringan bermasalah.');
        const result = await response.json();

        if (result.status === 'success') {
          statusEl.style.color = 'var(--success)';
          statusEl.innerHTML = '✅ Perubahan berhasil disimpan!';
          
          // Update local JS state
          q.question = questionText;
          q.options = { A: optA, B: optB, C: optC, D: optD, E: optE };
          q.answer_hash = newHash;

          // Update saved hash label
          document.getElementById('saved-hash-display').textContent = newHash;
          
          // Update question list dropdown preview text
          const qSelect = document.getElementById('question-select');
          const dropdownOption = qSelect.options[currentQuestionIndex];
          if (dropdownOption) {
            dropdownOption.textContent = `Soal #${q.no} - ${questionText.substring(0, 50)}...`;
          }

          resetDirty();
          updateCalculatedHash();
        } else {
          throw new Error(result.message || 'Gagal menyimpan.');
        }
      } catch (err) {
        console.error(err);
        statusEl.style.color = 'var(--danger)';
        statusEl.innerHTML = '❌ Gagal: ' + err.message;
        saveBtn.disabled = false;
      }
    }

    // Init on load
    window.addEventListener('DOMContentLoaded', () => {
      const select = document.getElementById('modul-select');
      select.setAttribute('data-current', select.value);
      loadModul(select.value);
    });
  </script>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/auth.php';
if (isLoggedIn()) {
  header('Location: ' . appUrl('pages/dashboard.php'));
  exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  try {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->prepare('SELECT username, password_hash, nama_lengkap, role FROM tb_users WHERE username = ? AND status = ? LIMIT 1');
    $stmt->execute([$username, 'aktif']);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
      $_SESSION['user'] = [
        'username' => $user['username'],
        'nama_lengkap' => $user['nama_lengkap'],
        'role' => $user['role'] ?? 'user',
      ];
      $landingPage = (($user['role'] ?? 'user') === 'admin')
        ? 'admin/dashboard.php'
        : 'pages/dashboard.php';
      header('Location: ' . appUrl($landingPage));
      exit;
    }

    $error = 'Username atau password salah!';
  } catch (Throwable $e) {
    $error = 'Database login belum siap atau belum terhubung.';
  }
}
$currentUser = getCurrentUser();
if ($currentUser) {
  $landingPage = (($currentUser['role'] ?? 'user') === 'admin')
    ? 'admin/dashboard.php'
    : 'pages/dashboard.php';
  header('Location: ' . appUrl($landingPage));
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmarTourism Magelang — Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #eef3f8;
      --panel: #ffffff;
      --panel-soft: #f8fafc;
      --ink: #0f172a;
      --muted: #64748b;
      --line: #dbe3ee;
      --line-strong: #c9d5e3;
      --emerald: #0f766e;
      --emerald-d: #115e59;
      --emerald-soft: #ccfbf1;
      --shadow: 0 20px 60px rgba(15, 23, 42, 0.12);
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 28px;
      color: var(--ink);
      position: relative;
      overflow: hidden;
      background-image:
        radial-gradient(circle at top left, rgba(15, 118, 110, 0.08), transparent 30%),
        radial-gradient(circle at bottom right, rgba(15, 118, 110, 0.06), transparent 28%);
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: linear-gradient(rgba(15, 23, 42, 0.035) 1px, transparent 1px), linear-gradient(90deg, rgba(15, 23, 42, 0.035) 1px, transparent 1px);
      background-size: 54px 54px;
      opacity: 0.55;
      pointer-events: none;
    }

    .login-shell {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 1120px;
      display: grid;
      grid-template-columns: 1.1fr 0.9fr;
      gap: 24px;
      align-items: stretch;
    }

    .hero-panel,
    .form-panel {
      border-radius: 24px;
      border: 1px solid var(--line);
      background: rgba(255, 255, 255, 0.78);
      box-shadow: var(--shadow);
      backdrop-filter: blur(12px);
    }

    .hero-panel {
      padding: 44px;
      background: linear-gradient(160deg, #0f172a 0%, #132235 55%, #0b1220 100%);
      color: white;
      position: relative;
      overflow: hidden;
    }

    .hero-panel::after {
      content: '';
      position: absolute;
      inset: auto -80px -90px auto;
      width: 240px;
      height: 240px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(15, 118, 110, 0.35), transparent 68%);
      pointer-events: none;
    }

    .brand {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 7px 12px;
      border-radius: 999px;
      border: 1px solid rgba(255, 255, 255, 0.12);
      background: rgba(255, 255, 255, 0.04);
      color: #c7d2fe;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 28px;
    }

    .brand-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #2dd4bf;
      box-shadow: 0 0 0 4px rgba(45, 212, 191, 0.15);
    }

    .hero-title {
      font-size: 42px;
      line-height: 1.05;
      letter-spacing: -1px;
      font-weight: 800;
      margin-bottom: 14px;
      max-width: 420px;
    }

    .hero-title span {
      color: #2dd4bf;
    }

    .hero-copy {
      max-width: 500px;
      color: rgba(226, 232, 240, 0.78);
      line-height: 1.7;
      font-size: 14px;
      margin-bottom: 30px;
    }

    .hero-metrics {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 14px;
      margin-top: 10px;
    }

    .metric {
      padding: 16px;
      border-radius: 18px;
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .metric-label {
      font-size: 11px;
      color: rgba(226, 232, 240, 0.62);
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .metric-value {
      font-size: 16px;
      font-weight: 700;
      color: #ffffff;
    }

    .metric-note {
      margin-top: 8px;
      font-size: 12px;
      color: rgba(226, 232, 240, 0.72);
      line-height: 1.55;
    }

    .form-panel {
      padding: 34px;
      background: rgba(255, 255, 255, 0.92);
    }

    .card-title {
      font-size: 12px;
      font-weight: 700;
      color: var(--emerald-d);
      letter-spacing: 1.2px;
      text-transform: uppercase;
      margin-bottom: 12px;
    }

    .headline {
      font-size: 30px;
      line-height: 1.15;
      color: var(--ink);
      margin-bottom: 10px;
      font-weight: 800;
    }

    .subtitle {
      font-size: 14px;
      line-height: 1.7;
      color: var(--muted);
      margin-bottom: 26px;
    }

    .pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--emerald-soft);
      border: 1px solid rgba(15, 118, 110, 0.16);
      border-radius: 999px;
      padding: 7px 13px;
      font-size: 11px;
      font-weight: 700;
      color: var(--emerald-d);
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 18px;
    }

    .pill-dot {
      width: 6px;
      height: 6px;
      background: var(--emerald);
      border-radius: 50%;
    }

    .field {
      margin-bottom: 16px;
    }

    .label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 7px;
    }

    .input {
      width: 100%;
      background: #f8fbfd;
      border: 1px solid var(--line);
      border-radius: 14px;
      padding: 14px 16px;
      font-size: 14px;
      font-family: inherit;
      color: var(--ink);
      outline: none;
      transition: all 0.2s ease;
    }

    .input::placeholder {
      color: #94a3b8;
    }

    .input:focus {
      border-color: rgba(15, 118, 110, 0.45);
      background: white;
      box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.08);
    }

    .error {
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 12px;
      padding: 12px 14px;
      font-size: 13px;
      color: #b91c1c;
      margin-bottom: 16px;
    }

    .btn {
      width: 100%;
      background: linear-gradient(135deg, var(--emerald), var(--emerald-d));
      border: none;
      border-radius: 14px;
      padding: 14px 16px;
      font-size: 14px;
      font-weight: 700;
      color: white;
      font-family: inherit;
      cursor: pointer;
      transition: all 0.2s;
      box-shadow: 0 12px 24px rgba(15, 118, 110, 0.22);
      margin-top: 6px;
    }

    .btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 14px 30px rgba(15, 118, 110, 0.28);
    }

    .btn:active {
      transform: translateY(0);
    }

    .support-row {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      align-items: center;
      margin-top: 18px;
      padding-top: 18px;
      border-top: 1px solid var(--line);
      font-size: 12px;
      color: var(--muted);
    }

    .support-row strong {
      color: var(--ink);
      font-weight: 600;
    }

    .footer-note {
      margin-top: 18px;
      font-size: 12px;
      color: var(--muted);
      line-height: 1.6;
    }

    .compact-list {
      display: grid;
      gap: 12px;
      margin-top: 28px;
    }

    .compact-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 14px 0;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .compact-item:first-child {
      border-top: none;
      padding-top: 0;
    }

    .compact-bullet {
      width: 28px;
      height: 28px;
      border-radius: 8px;
      flex-shrink: 0;
      background: rgba(255, 255, 255, 0.08);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #2dd4bf;
      font-size: 14px;
      font-weight: 800;
    }

    .compact-text {
      min-width: 0;
    }

    .compact-title {
      font-size: 14px;
      font-weight: 700;
      color: white;
      margin-bottom: 4px;
    }

    .compact-desc {
      font-size: 13px;
      line-height: 1.6;
      color: rgba(226, 232, 240, 0.72);
    }

    .brand-mini {
      text-align: center;
      font-size: 12px;
      color: var(--muted);
      margin-top: 18px;
    }

    @media (max-width: 900px) {
      body {
        padding: 18px;
      }

      .login-shell {
        grid-template-columns: 1fr;
      }

      .hero-panel,
      .form-panel {
        padding: 28px;
      }

      .hero-title {
        font-size: 34px;
      }

      .hero-metrics {
        grid-template-columns: 1fr;
      }

      .support-row {
        flex-direction: column;
        align-items: flex-start;
      }
    }

    @media (max-width: 480px) {

      .hero-panel,
      .form-panel {
        padding: 22px;
        border-radius: 20px;
      }

      .headline {
        font-size: 26px;
      }

      .hero-title {
        font-size: 30px;
      }
    }
  </style>
</head>

<body>
  <div class="login-shell">
    <section class="hero-panel">
      <div class="brand"><span class="brand-dot"></span> SmarTourism System</div>
      <div class="hero-title">Pemetaan potensi wisata yang <span>lebih rapi</span> dan mudah dipakai.</div>
      <p class="hero-copy">Masuk untuk mengakses data destinasi, hasil clustering, dan peta interaktif Kabupaten Magelang. Hak akses dibedakan untuk admin dan user biasa agar alur kerja tetap sederhana.</p>

      <div class="hero-metrics">
        <div class="metric">
          <div class="metric-label">Data terpusat</div>
          <div class="metric-value">MySQL</div>
          <div class="metric-note">Seluruh destinasi dibaca langsung dari database.</div>
        </div>
        <div class="metric">
          <div class="metric-label">Akses per peran</div>
          <div class="metric-value">Admin & User</div>
          <div class="metric-note">Admin mengelola data, user fokus melihat hasil.</div>
        </div>
        <div class="metric">
          <div class="metric-label">Analisis</div>
          <div class="metric-value">K-Means</div>
          <div class="metric-note">Pengelompokan destinasi berdasarkan kriteria wisata.</div>
        </div>
      </div>

      <div class="compact-list">
        <div class="compact-item">
          <div class="compact-bullet">1</div>
          <div class="compact-text">
            <div class="compact-title">Peta interaktif</div>
            <div class="compact-desc">Lihat posisi destinasi dan sebaran cluster dengan tampilan peta yang bisa diganti.</div>
          </div>
        </div>
        <div class="compact-item">
          <div class="compact-bullet">2</div>
          <div class="compact-text">
            <div class="compact-title">Data yang konsisten</div>
            <div class="compact-desc">Semua halaman membaca data yang sama dari database, bukan lagi hardcode.</div>
          </div>
        </div>
      </div>
    </section>

    <section class="form-panel">
      <div class="card-title">Login</div>
      <div class="headline">Masuk ke sistem</div>
      <p class="subtitle">Silakan gunakan akun yang sudah terdaftar. Admin akan diarahkan ke halaman pengelolaan, sedangkan user biasa ke dashboard hasil.</p>

      <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="field">
          <label class="label">Username</label>
          <input type="text" name="username" class="input" placeholder="Masukkan username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        </div>
        <div class="field">
          <label class="label">Password</label>
          <input type="password" name="password" class="input" placeholder="Masukkan password" required>
        </div>
        <button type="submit" class="btn">Masuk ke Sistem</button>
      </form>


      <div class="brand-mini">SmarTourism Magelang · Dashboard Wisata dan Clustering</div>
    </section>
  </div>
</body>

</html>
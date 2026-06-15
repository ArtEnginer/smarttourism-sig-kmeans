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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #f0f4f8;
      --ink: #0f172a;
      --muted: #64748b;
      --line: #e2e8f0;
      --emerald: #0f766e;
      --emerald-d: #115e59;
      --emerald-soft: #f0fdf9;
      --emerald-border: rgba(15, 118, 110, 0.2);
      --red-soft: #fef2f2;
      --red-border: #fecaca;
      --red-text: #b91c1c;
      --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.08), 0 1px 2px rgba(15, 23, 42, 0.06);
      --shadow-md: 0 4px 24px rgba(15, 23, 42, 0.10);
      --shadow-lg: 0 12px 40px rgba(15, 23, 42, 0.13);
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
      padding: 24px 16px;
      color: var(--ink);
      background-image:
        radial-gradient(ellipse at 20% 10%, rgba(15, 118, 110, 0.07) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 90%, rgba(15, 118, 110, 0.05) 0%, transparent 50%);
    }

    /* ── Shell ── */
    .shell {
      width: 100%;
      max-width: 1080px;
      display: grid;
      grid-template-columns: 1fr 420px;
      gap: 20px;
      align-items: stretch;
    }

    /* ── Hero panel ── */
    .hero {
      background: linear-gradient(150deg, #0d1b2a 0%, #0f2235 60%, #071521 100%);
      border-radius: 20px;
      padding: 44px 40px;
      color: #fff;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }

    .hero::before {
      content: '';
      position: absolute;
      bottom: -60px;
      right: -60px;
      width: 280px;
      height: 280px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(15, 118, 110, 0.30) 0%, transparent 65%);
      pointer-events: none;
    }

    .hero::after {
      content: '';
      position: absolute;
      top: -40px;
      left: 40%;
      width: 180px;
      height: 180px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(45, 212, 191, 0.08) 0%, transparent 70%);
      pointer-events: none;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      border: 1px solid rgba(255, 255, 255, 0.13);
      background: rgba(255, 255, 255, 0.05);
      border-radius: 999px;
      padding: 6px 13px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: #94d8d1;
      margin-bottom: 28px;
      width: fit-content;
    }

    .badge-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #2dd4bf;
      box-shadow: 0 0 0 3px rgba(45, 212, 191, 0.18);
    }

    .hero-title {
      font-size: 36px;
      font-weight: 700;
      line-height: 1.12;
      letter-spacing: -0.5px;
      margin-bottom: 14px;
      max-width: 400px;
    }

    .hero-title em {
      font-style: normal;
      color: #2dd4bf;
    }

    .hero-desc {
      font-size: 14px;
      line-height: 1.75;
      color: rgba(226, 232, 240, 0.68);
      max-width: 440px;
      margin-bottom: 32px;
    }

    .metrics {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      margin-bottom: 28px;
    }

    .metric {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 14px;
      padding: 14px;
    }

    .metric-label {
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: .8px;
      color: rgba(226, 232, 240, 0.50);
      margin-bottom: 5px;
    }

    .metric-val {
      font-size: 14px;
      font-weight: 600;
      color: #fff;
      margin-bottom: 5px;
    }

    .metric-note {
      font-size: 11px;
      color: rgba(226, 232, 240, 0.55);
      line-height: 1.5;
    }

    .feature-list {
      margin-top: auto;
      display: grid;
      gap: 0;
    }

    .feature {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 14px 0;
      border-top: 1px solid rgba(255, 255, 255, 0.07);
    }

    .feature:first-child {
      border-top: none;
    }

    .feature-num {
      width: 28px;
      height: 28px;
      border-radius: 8px;
      background: rgba(45, 212, 191, 0.12);
      border: 1px solid rgba(45, 212, 191, 0.2);
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 700;
      color: #2dd4bf;
    }

    .feature-title {
      font-size: 13px;
      font-weight: 600;
      color: #fff;
      margin-bottom: 3px;
    }

    .feature-desc {
      font-size: 12px;
      color: rgba(226, 232, 240, 0.60);
      line-height: 1.55;
    }

    /* ── Form panel ── */
    .form-panel {
      background: #fff;
      border-radius: 20px;
      padding: 36px 32px;
      box-shadow: var(--shadow-lg);
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .form-eyebrow {
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: var(--emerald);
      margin-bottom: 8px;
    }

    .form-title {
      font-size: 26px;
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 8px;
      line-height: 1.15;
    }

    .form-sub {
      font-size: 13px;
      color: var(--muted);
      line-height: 1.7;
      margin-bottom: 26px;
    }

    .field {
      margin-bottom: 16px;
    }

    .field-label {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      font-weight: 600;
      color: var(--ink);
      margin-bottom: 7px;
    }

    .field-label svg {
      color: var(--muted);
    }

    .input-wrap {
      position: relative;
    }

    .input {
      width: 100%;
      background: #f8fafc;
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 13px 16px;
      font-size: 14px;
      font-family: inherit;
      color: var(--ink);
      outline: none;
      transition: border .15s, box-shadow .15s, background .15s;
      -webkit-appearance: none;
    }

    .input::placeholder {
      color: #b0bec5;
    }

    .input:focus {
      border-color: rgba(15, 118, 110, 0.50);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.10);
    }

    .input-pw {
      padding-right: 46px;
    }

    .pw-toggle {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      padding: 4px;
      color: var(--muted);
      display: flex;
      align-items: center;
      line-height: 1;
      transition: color .15s;
    }

    .pw-toggle:hover {
      color: var(--ink);
    }

    .error-box {
      display: flex;
      align-items: center;
      gap: 9px;
      background: var(--red-soft);
      border: 1px solid var(--red-border);
      border-radius: 12px;
      padding: 11px 14px;
      font-size: 13px;
      color: var(--red-text);
      margin-bottom: 16px;
    }

    .error-box svg {
      flex-shrink: 0;
    }

    .btn {
      width: 100%;
      background: var(--emerald);
      border: none;
      border-radius: 12px;
      padding: 14px 20px;
      font-size: 14px;
      font-weight: 600;
      color: #fff;
      font-family: inherit;
      cursor: pointer;
      margin-top: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background .15s, transform .1s, box-shadow .15s;
      box-shadow: 0 4px 16px rgba(15, 118, 110, 0.25);
    }

    .btn:hover {
      background: var(--emerald-d);
      box-shadow: 0 6px 20px rgba(15, 118, 110, 0.30);
    }

    .btn:active {
      transform: scale(0.99);
    }

    .divider {
      border: none;
      border-top: 1px solid var(--line);
      margin: 22px 0;
    }

    .role-info {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .role-card {
      background: #f8fafc;
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 12px 14px;
    }

    .role-card-title {
      font-size: 12px;
      font-weight: 600;
      color: var(--ink);
      margin-bottom: 3px;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .role-card-desc {
      font-size: 11px;
      color: var(--muted);
      line-height: 1.5;
    }

    .form-footer {
      text-align: center;
      font-size: 11px;
      color: #b0bec5;
      margin-top: 20px;
    }

    /* ── Responsive ── */
    @media (max-width: 840px) {
      .shell {
        grid-template-columns: 1fr;
        max-width: 480px;
      }

      .hero {
        padding: 30px 28px;
      }

      .hero-title {
        font-size: 28px;
      }

      .metrics {
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
      }

      .metric {
        padding: 11px 10px;
      }

      .metric-note {
        display: none;
      }

      .form-panel {
        padding: 28px 24px;
      }
    }

    @media (max-width: 480px) {
      body {
        padding: 16px 12px;
      }

      .hero {
        padding: 24px 20px;
      }

      .hero-title {
        font-size: 24px;
      }

      .badge {
        font-size: 10px;
      }

      .metrics {
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
      }

      .metric-val {
        font-size: 12px;
      }

      .metric-label {
        font-size: 9px;
      }

      .form-panel {
        padding: 24px 18px;
      }

      .form-title {
        font-size: 22px;
      }

      .role-info {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <div class="shell">

    <!-- Hero -->
    <section class="hero">
      <div class="badge"><span class="badge-dot"></span> SmarTourism System</div>
      <h1 class="hero-title">Pemetaan potensi wisata yang <em>lebih rapi</em> dan mudah dipakai.</h1>
      <p class="hero-desc">Akses data destinasi, hasil clustering, dan peta interaktif Kabupaten Magelang. Hak akses dibedakan untuk admin dan user biasa.</p>

      <div class="metrics">
        <div class="metric">
          <div class="metric-label">Database</div>
          <div class="metric-val">MySQL</div>
          <div class="metric-note">Terpusat, langsung dari DB.</div>
        </div>
        <div class="metric">
          <div class="metric-label">Akses</div>
          <div class="metric-val">Admin & User</div>
          <div class="metric-note">Peran terpisah, alur jelas.</div>
        </div>
        <div class="metric">
          <div class="metric-label">Analisis</div>
          <div class="metric-val">K-Means</div>
          <div class="metric-note">Cluster otomatis.</div>
        </div>
      </div>

      <div class="feature-list">
        <div class="feature">
          <div class="feature-num">1</div>
          <div>
            <div class="feature-title">Peta interaktif</div>
            <div class="feature-desc">Lihat posisi destinasi dan sebaran cluster dengan tampilan peta yang bisa diganti.</div>
          </div>
        </div>
        <div class="feature">
          <div class="feature-num">2</div>
          <div>
            <div class="feature-title">Data yang konsisten</div>
            <div class="feature-desc">Semua halaman membaca data yang sama dari database, bukan lagi hardcode.</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Form -->
    <section class="form-panel">
      <div class="form-eyebrow">Masuk ke Sistem</div>
      <h2 class="form-title">Selamat datang</h2>
      <p class="form-sub">Gunakan akun yang sudah terdaftar. Admin diarahkan ke halaman pengelolaan, user biasa ke dashboard hasil.</p>

      <?php if ($error): ?>
        <div class="error-box">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
          </svg>
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" id="loginForm">
        <div class="field">
          <label class="field-label" for="username">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            Username
          </label>
          <input type="text" id="username" name="username" class="input" placeholder="Masukkan username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" autocomplete="username" required>
        </div>
        <div class="field">
          <label class="field-label" for="password">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
              <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
            Password
          </label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" class="input input-pw" placeholder="Masukkan password" autocomplete="current-password" required>
            <button type="button" class="pw-toggle" onclick="togglePw()" aria-label="Tampilkan atau sembunyikan password">
              <svg id="eye-show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
              </svg>
              <svg id="eye-hide" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                <line x1="1" y1="1" x2="23" y2="23" />
              </svg>
            </button>
          </div>
        </div>
        <button type="submit" class="btn" id="submitBtn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
            <polyline points="10 17 15 12 10 7" />
            <line x1="15" y1="12" x2="3" y2="12" />
          </svg>
          Masuk ke Sistem
        </button>
      </form>

      <hr class="divider">

      <div class="role-info">
        <div class="role-card">
          <div class="role-card-title">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#0f766e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 20h9" />
              <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
            </svg>
            Admin
          </div>
          <div class="role-card-desc">Mengelola data destinasi dan pengaturan sistem.</div>
        </div>
        <div class="role-card">
          <div class="role-card-title">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#0f766e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            User
          </div>
          <div class="role-card-desc">Melihat dashboard hasil clustering dan peta.</div>
        </div>
      </div>

      <div class="form-footer">SmarTourism Magelang &middot; Dashboard Wisata &amp; Clustering</div>
    </section>

  </div>

  <script>
    function togglePw() {
      var inp = document.getElementById('password');
      var show = document.getElementById('eye-show');
      var hide = document.getElementById('eye-hide');
      if (inp.type === 'password') {
        inp.type = 'text';
        show.style.display = 'none';
        hide.style.display = 'block';
      } else {
        inp.type = 'password';
        show.style.display = 'block';
        hide.style.display = 'none';
      }
    }

    document.getElementById('loginForm').addEventListener('submit', function() {
      var btn = document.getElementById('submitBtn');
      btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation:spin .8s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memverifikasi...';
      btn.disabled = true;
      btn.style.opacity = '0.8';
    });
  </script>
  <style>
    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
  </style>
</body>

</html>
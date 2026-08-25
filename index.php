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
      --bg: #f8fafc;
      --surface: #ffffff;
      --surface-2: #f0fdf4;
      --ink: #0f172a;
      --muted: #64748b;
      --line: #e2e8f0;
      --emerald: #059669;
      --emerald-d: #047857;
      --emerald-soft: #ecfdf5;
      --emerald-badge: #dcfce7;
      --emerald-border: #a7f3d0;
      --red-soft: #fef2f2;
      --red-border: #fecaca;
      --red-text: #dc2626;
      --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.05);
      --shadow-md: 0 4px 20px rgba(5, 150, 105, 0.08);
      --shadow-lg: 0 12px 36px rgba(5, 150, 105, 0.12);
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      -webkit-tap-highlight-color: transparent;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
      color: var(--ink);
      background-image:
        radial-gradient(ellipse at 10% 10%, rgba(5, 150, 105, 0.08) 0%, transparent 50%),
        radial-gradient(ellipse at 90% 90%, rgba(16, 185, 129, 0.06) 0%, transparent 50%);
    }

    /* ── Shell ── */
    .shell {
      width: 100%;
      max-width: 1040px;
      display: grid;
      grid-template-columns: 1fr 420px;
      gap: 24px;
      align-items: stretch;
    }

    /* ── Hero panel ── */
    .hero {
      background: linear-gradient(145deg, #ffffff 0%, #f0fdf4 100%);
      border: 1px solid var(--emerald-border);
      border-radius: 24px;
      padding: 44px 40px;
      color: var(--ink);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      box-shadow: var(--shadow-md);
    }

    .hero::before {
      content: '';
      position: absolute;
      bottom: -60px;
      right: -60px;
      width: 280px;
      height: 280px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(5, 150, 105, 0.12) 0%, transparent 65%);
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
      background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
      pointer-events: none;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      border: 1px solid var(--emerald-border);
      background: var(--emerald-soft);
      border-radius: 999px;
      padding: 6px 14px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      color: var(--emerald-d);
      margin-bottom: 28px;
      width: fit-content;
      box-shadow: var(--shadow-sm);
    }

    .badge-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--emerald);
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2);
    }

    .hero-title {
      font-size: 34px;
      font-weight: 800;
      line-height: 1.15;
      letter-spacing: -0.6px;
      margin-bottom: 14px;
      max-width: 440px;
      color: var(--ink);
    }

    .hero-title em {
      font-style: normal;
      color: var(--emerald);
    }

    .hero-desc {
      font-size: 14px;
      line-height: 1.7;
      color: var(--muted);
      max-width: 440px;
      margin-bottom: 32px;
    }

    .metrics {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-bottom: 28px;
    }

    .metric {
      background: #ffffff;
      border: 1px solid var(--emerald-border);
      border-radius: 16px;
      padding: 14px;
      box-shadow: var(--shadow-sm);
    }

    .metric-label {
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--emerald-d);
      font-weight: 700;
      margin-bottom: 5px;
    }

    .metric-val {
      font-size: 15px;
      font-weight: 800;
      color: var(--ink);
      margin-bottom: 3px;
    }

    .metric-note {
      font-size: 11px;
      color: var(--muted);
      line-height: 1.4;
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
      border-top: 1px solid var(--line);
    }

    .feature:first-child {
      border-top: none;
    }

    .feature-num {
      width: 30px;
      height: 30px;
      border-radius: 10px;
      background: var(--emerald-badge);
      border: 1px solid var(--emerald-border);
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 800;
      color: var(--emerald-d);
    }

    .feature-title {
      font-size: 13.5px;
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 3px;
    }

    .feature-desc {
      font-size: 12px;
      color: var(--muted);
      line-height: 1.55;
    }

    /* ── Form panel ── */
    .form-panel {
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: 24px;
      padding: 40px 36px;
      box-shadow: var(--shadow-lg);
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .form-eyebrow {
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: var(--emerald);
      margin-bottom: 8px;
    }

    .form-title {
      font-size: 26px;
      font-weight: 800;
      color: var(--ink);
      margin-bottom: 8px;
      line-height: 1.2;
      letter-spacing: -0.3px;
    }

    .form-sub {
      font-size: 13px;
      color: var(--muted);
      line-height: 1.6;
      margin-bottom: 28px;
    }

    .field {
      margin-bottom: 18px;
    }

    .field-label {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 12.5px;
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 8px;
    }

    .field-label svg {
      color: var(--emerald);
    }

    .input-wrap {
      position: relative;
    }

    .input {
      width: 100%;
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 13px 16px;
      font-size: 14px;
      font-family: inherit;
      color: var(--ink);
      outline: none;
      transition: all .2s;
      -webkit-appearance: none;
      min-height: 48px;
    }

    .input::placeholder {
      color: #94a3b8;
    }

    .input:focus {
      border-color: var(--emerald);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.12);
    }

    .input-pw {
      padding-right: 48px;
    }

    .pw-toggle {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      padding: 6px;
      color: var(--muted);
      display: flex;
      align-items: center;
      line-height: 1;
      transition: color .15s;
    }

    .pw-toggle:hover {
      color: var(--emerald-d);
    }

    .error-box {
      display: flex;
      align-items: center;
      gap: 10px;
      background: var(--red-soft);
      border: 1px solid var(--red-border);
      border-radius: 12px;
      padding: 12px 16px;
      font-size: 13px;
      font-weight: 600;
      color: var(--red-text);
      margin-bottom: 20px;
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
      font-size: 14.5px;
      font-weight: 700;
      color: #fff;
      font-family: inherit;
      cursor: pointer;
      margin-top: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all .2s cubic-bezier(0.16, 1, 0.3, 1);
      box-shadow: 0 4px 16px rgba(5, 150, 105, 0.25);
      min-height: 48px;
    }

    .btn:hover {
      background: var(--emerald-d);
      box-shadow: 0 6px 22px rgba(5, 150, 105, 0.35);
      transform: translateY(-1px);
    }

    .btn:active {
      transform: translateY(0);
    }

    .divider {
      border: none;
      border-top: 1px solid var(--line);
      margin: 24px 0;
    }

    .role-info {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .role-card {
      background: var(--surface-2);
      border: 1px solid var(--emerald-border);
      border-radius: 12px;
      padding: 12px 14px;
    }

    .role-card-title {
      font-size: 12px;
      font-weight: 700;
      color: var(--emerald-d);
      margin-bottom: 3px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .role-card-desc {
      font-size: 11px;
      color: var(--muted);
      line-height: 1.5;
    }

    .form-footer {
      text-align: center;
      font-size: 11.5px;
      color: var(--muted);
      margin-top: 22px;
      font-weight: 500;
    }

    /* ── Responsive ── */
    @media (max-width: 840px) {
      .shell {
        grid-template-columns: 1fr;
        max-width: 460px;
      }

      .hero {
        padding: 32px 28px;
        border-radius: 20px;
      }

      .hero-title {
        font-size: 26px;
      }

      .metrics {
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
      }

      .metric {
        padding: 10px;
      }

      .metric-note {
        display: none;
      }

      .form-panel {
        padding: 32px 26px;
        border-radius: 20px;
      }
    }

    @media (max-width: 480px) {
      body {
        padding: 14px 10px;
      }

      .hero {
        padding: 24px 20px;
      }

      .hero-title {
        font-size: 22px;
      }

      .badge {
        font-size: 10px;
        margin-bottom: 18px;
      }

      .metrics {
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
      }

      .metric-val {
        font-size: 13px;
      }

      .metric-label {
        font-size: 8.5px;
      }

      .form-panel {
        padding: 26px 20px;
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
      <div class="badge"><span class="badge-dot"></span> SmarTourism Kabupaten Magelang</div>
      <h1 class="hero-title">Jelajahi &amp; Petakan <em>Potensi Wisata</em> Kabupaten Magelang.</h1>
      <p class="hero-desc">Sistem Informasi Geografis interaktif untuk pemetaan lokasi destinasi pariwisata, analisis klaster potensi wilayah, dan panduan data terpadu.</p>

      <div style="margin-bottom:24px;border-radius:18px;overflow:hidden;border:1px solid var(--emerald-border);box-shadow:var(--shadow-sm);background:#fff;max-height:220px;display:flex;align-items:center;justify-content:center;">
        <img src="<?= appUrl('assets/hero_map.png') ?>" alt="Magelang Tourism Map Graphic" style="width:100%;height:100%;object-fit:cover;">
      </div>

      <div class="metrics">
        <div class="metric">
          <div class="metric-label">Destinasi</div>
          <div class="metric-val">50+ Wisata</div>
          <div class="metric-note">Alam &amp; Budaya</div>
        </div>
        <div class="metric">
          <div class="metric-label">Pemetaan</div>
          <div class="metric-val">3 Klaster</div>
          <div class="metric-note">Potensi Wilayah</div>
        </div>
        <div class="metric">
          <div class="metric-label">Fitur GIS</div>
          <div class="metric-val">Peta Digital</div>
          <div class="metric-note">Interaktif Real-time</div>
        </div>
      </div>
    </section>

    <!-- Form -->
    <section class="form-panel">
      <div class="form-eyebrow">Masuk ke Portal</div>
      <h2 class="form-title">Selamat datang</h2>
      <p class="form-sub">Masukkan akun terdaftar Anda untuk mengakses portal Smart Tourism.</p>

      <?php if ($error): ?>
        <div class="error-box">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            Username
          </label>
          <input type="text" id="username" name="username" class="input" placeholder="Masukkan username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" autocomplete="username" required>
        </div>
        <div class="field">
          <label class="field-label" for="password">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
            <polyline points="10 17 15 12 10 7" />
            <line x1="15" y1="12" x2="3" y2="12" />
          </svg>
          Masuk ke Portal
        </button>
      </form>

      <div style="text-align:center;margin-top:16px;font-size:13px;color:var(--muted);">
        Belum memiliki akun? <a href="<?= appUrl('register.php') ?>" style="color:var(--emerald);font-weight:700;text-decoration:none;">Daftar Akun Baru</a>
      </div>

      <div class="form-footer" style="border-top:1px solid var(--line);padding-top:16px;margin-top:24px;">
        SmarTourism Kabupaten Magelang &bull; Portal Informasi Geografis Pariwisata
      </div>
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
      btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation:spin .8s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memverifikasi...';
      btn.disabled = true;
      btn.style.opacity = '0.85';
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
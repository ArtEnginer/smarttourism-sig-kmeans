<?php
session_start();
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
  header('Location: ' . appUrl('pages/dashboard.php'));
  exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $nama = trim($_POST['nama_lengkap'] ?? '');
  $password = $_POST['password'] ?? '';
  $passwordConfirm = $_POST['password_confirm'] ?? '';

  if ($username === '' || $nama === '' || $password === '') {
    $error = 'Semua bidang form wajib diisi.';
  } elseif (strlen($username) < 3) {
    $error = 'Username minimal 3 karakter.';
  } elseif ($password !== $passwordConfirm) {
    $error = 'Konfirmasi password tidak cocok.';
  } elseif (strlen($password) < 4) {
    $error = 'Password minimal 4 karakter.';
  } else {
    try {
      $pdo = getDatabaseConnection();
      // Check duplicate username
      $check = $pdo->prepare('SELECT COUNT(*) FROM tb_users WHERE username = ?');
      $check->execute([$username]);
      if ($check->fetchColumn() > 0) {
        $error = 'Username "' . htmlspecialchars($username) . '" sudah terdaftar.';
      } else {
        $stmt = $pdo->prepare('INSERT INTO tb_users (username, password_hash, nama_lengkap, role, status) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $nama, 'user', 'aktif']);
        $success = 'Pendaftaran berhasil! Silakan masuk dengan akun baru Anda.';
      }
    } catch (Throwable $e) {
      $error = 'Gagal mendaftar: Database belum terhubung.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>SmarTourism Magelang — Registrasi Akun</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
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

    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      -webkit-tap-highlight-color: transparent;
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

    .shell {
      width: 100%;
      max-width: 1040px;
      display: grid;
      grid-template-columns: 1fr 440px;
      gap: 24px;
      align-items: stretch;
    }

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
      margin-bottom: 24px;
      width: fit-content;
    }

    .badge-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--emerald);
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2);
    }

    .hero-title {
      font-size: 32px;
      font-weight: 800;
      line-height: 1.15;
      letter-spacing: -0.6px;
      margin-bottom: 14px;
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
      margin-bottom: 32px;
    }

    .feature-list {
      margin-top: auto;
      display: grid;
      gap: 14px;
    }

    .feature-card {
      background: #ffffff;
      border: 1px solid var(--emerald-border);
      border-radius: 16px;
      padding: 16px;
      display: flex;
      align-items: center;
      gap: 14px;
      box-shadow: var(--shadow-sm);
    }

    .feature-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: var(--emerald-soft);
      color: var(--emerald-d);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .form-panel {
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: 24px;
      padding: 38px 34px;
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
      margin-bottom: 6px;
    }

    .form-title {
      font-size: 24px;
      font-weight: 800;
      color: var(--ink);
      margin-bottom: 6px;
      letter-spacing: -0.3px;
    }

    .form-sub {
      font-size: 13px;
      color: var(--muted);
      margin-bottom: 24px;
    }

    .field {
      margin-bottom: 14px;
    }

    .field-label {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      font-weight: 700;
      color: var(--ink);
      margin-bottom: 6px;
    }

    .input {
      width: 100%;
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 11px 14px;
      font-size: 13.5px;
      font-family: inherit;
      color: var(--ink);
      outline: none;
      transition: all .2s;
      min-height: 44px;
    }

    .input:focus {
      border-color: var(--emerald);
      box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.12);
    }

    .alert {
      padding: 12px 14px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert-error {
      background: var(--red-soft);
      border: 1px solid var(--red-border);
      color: var(--red-text);
    }

    .alert-success {
      background: var(--emerald-soft);
      border: 1px solid var(--emerald-border);
      color: var(--emerald-d);
    }

    .btn {
      width: 100%;
      background: var(--emerald);
      border: none;
      border-radius: 12px;
      padding: 13px 20px;
      font-size: 14px;
      font-weight: 700;
      color: #fff;
      font-family: inherit;
      cursor: pointer;
      margin-top: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all .2s;
      box-shadow: 0 4px 16px rgba(5, 150, 105, 0.25);
      min-height: 46px;
    }

    .btn:hover {
      background: var(--emerald-d);
      box-shadow: 0 6px 22px rgba(5, 150, 105, 0.35);
      transform: translateY(-1px);
    }

    .login-link {
      text-align: center;
      margin-top: 20px;
      font-size: 13px;
      color: var(--muted);
    }

    .login-link a {
      color: var(--emerald);
      font-weight: 700;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    @media (max-width: 840px) {
      .shell {
        grid-template-columns: 1fr;
        max-width: 440px;
      }
      .hero { display: none; }
      .form-panel { padding: 28px 22px; }
    }
  </style>
</head>

<body>
  <div class="shell">

    <!-- Hero -->
    <section class="hero">
      <div class="badge"><span class="badge-dot"></span> Pendaftaran Pengguna</div>
      <h1 class="hero-title">Bergabunglah dengan <em>SmarTourism</em> Magelang.</h1>
      <p class="hero-desc">Daftar akun baru untuk menjelajahi peta digital interaktif GIS dan melihat hasil analisis sebaran destinasi pariwisata Kabupaten Magelang.</p>

      <div style="margin-bottom:20px;border-radius:18px;overflow:hidden;border:1px solid var(--emerald-border);box-shadow:var(--shadow-sm);background:#fff;max-height:220px;display:flex;align-items:center;justify-content:center;">
        <img src="<?= appUrl('assets/hero_map.png') ?>" alt="Magelang Tourism Map Graphic" style="width:100%;height:100%;object-fit:cover;">
      </div>

      <div class="feature-list">
        <div class="feature-card">
          <div class="feature-icon"><i data-lucide="map-pin"></i></div>
          <div>
            <div style="font-size:13px;font-weight:700;">Akses Peta GIS Interaktif</div>
            <div style="font-size:12px;color:var(--muted);">Jelajahi sebaran destinasi pariwisata secara spasial.</div>
          </div>
        </div>
        <div class="feature-card">
          <div class="feature-icon"><i data-lucide="pie-chart"></i></div>
          <div>
            <div style="font-size:13px;font-weight:700;">Analisis Potensi Wilayah</div>
            <div style="font-size:12px;color:var(--muted);">Pengelompokan klaster wisata otomatis.</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Form Panel -->
    <section class="form-panel">
      <div class="form-eyebrow">Pendaftaran Akun</div>
      <h2 class="form-title">Buat Akun Baru</h2>
      <p class="form-sub">Lengkapi data Anda untuk mendaftar.</p>

      <?php if ($error): ?>
        <div class="alert alert-error">
          <i data-lucide="alert-circle" style="width:18px;height:18px;"></i>
          <span><?= htmlspecialchars($error) ?></span>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert alert-success">
          <i data-lucide="check-circle-2" style="width:18px;height:18px;"></i>
          <span><?= htmlspecialchars($success) ?></span>
        </div>
      <?php endif; ?>

      <form method="POST">
        <div class="field">
          <label class="field-label" for="username">
            <i data-lucide="user" style="width:14px;height:14px;color:var(--emerald);"></i> Username *
          </label>
          <input type="text" id="username" name="username" class="input" placeholder="Pilih username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        </div>

        <div class="field">
          <label class="field-label" for="nama_lengkap">
            <i data-lucide="user-check" style="width:14px;height:14px;color:var(--emerald);"></i> Nama Lengkap *
          </label>
          <input type="text" id="nama_lengkap" name="nama_lengkap" class="input" placeholder="Nama lengkap Anda" value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>" required>
        </div>

        <div class="field">
          <label class="field-label" for="password">
            <i data-lucide="lock" style="width:14px;height:14px;color:var(--emerald);"></i> Password *
          </label>
          <input type="password" id="password" name="password" class="input" placeholder="Password akun" required>
        </div>

        <div class="field">
          <label class="field-label" for="password_confirm">
            <i data-lucide="key-round" style="width:14px;height:14px;color:var(--emerald);"></i> Konfirmasi Password *
          </label>
          <input type="password" id="password_confirm" name="password_confirm" class="input" placeholder="Ulangi password" required>
        </div>

        <button type="submit" class="btn">
          <i data-lucide="user-plus" style="width:18px;height:18px;"></i> Daftar Akun Sekarang
        </button>
      </form>

      <div class="login-link">
        Sudah memiliki akun? <a href="<?= appUrl('index.php') ?>">Masuk ke Aplikasi</a>
      </div>
    </section>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      if (window.lucide) lucide.createIcons();
    });
  </script>
</body>

</html>

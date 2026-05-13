<?php
session_start();
if (isset($_SESSION['user'])) { header('Location: pages/dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  if ($username === 'admin' && $password === 'admin123') {
    $_SESSION['user'] = $username;
    header('Location: pages/dashboard.php'); exit;
  } else {
    $error = 'Username atau password salah!';
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmarTourism Magelang — Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
  <style>
    :root {
      --emerald: #10b981;
      --emerald-d: #059669;
      --bg: #080d17;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body {
      font-family: 'Syne', sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }
    /* Grid bg */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(16,185,129,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(16,185,129,0.04) 1px, transparent 1px);
      background-size: 48px 48px;
      z-index: 0;
    }
    /* Gradient radial glow */
    body::after {
      content: '';
      position: fixed;
      width: 700px; height: 700px;
      background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, transparent 70%);
      top: 50%; left: 50%;
      transform: translate(-50%,-50%);
      z-index: 0;
    }
    .login-wrap {
      position: relative; z-index: 10;
      width: 100%; max-width: 420px;
      padding: 20px;
      animation: up 0.7s cubic-bezier(0.16,1,0.3,1) forwards;
      opacity: 0; transform: translateY(32px);
    }
    @keyframes up { to { opacity:1; transform:translateY(0); } }
    .card {
      background: rgba(17,24,39,0.95);
      border: 1px solid rgba(16,185,129,0.15);
      border-radius: 22px;
      padding: 44px 40px;
      box-shadow: 0 40px 80px rgba(0,0,0,0.6), 0 0 0 1px rgba(16,185,129,0.05);
    }
    .pill {
      display: inline-flex; align-items: center; gap: 7px;
      background: rgba(16,185,129,0.1);
      border: 1px solid rgba(16,185,129,0.25);
      border-radius: 100px;
      padding: 5px 13px;
      font-size: 10px; font-weight: 700; color: #34d399;
      letter-spacing: 1.5px; text-transform: uppercase;
      margin-bottom: 24px;
    }
    .pill-dot { width: 6px; height: 6px; background: #34d399; border-radius: 50%; animation: blink 2s infinite; }
    @keyframes blink { 0%,100%{opacity:1}50%{opacity:0.2} }
    h1 {
      font-family: 'Instrument Serif', serif;
      font-style: italic;
      font-size: 40px; line-height: 1.05;
      color: white; margin-bottom: 8px;
    }
    h1 span { color: #34d399; }
    .sub { font-size: 13px; color: rgba(255,255,255,0.4); margin-bottom: 32px; line-height: 1.6; }
    .label {
      display: block; font-size: 10px; font-weight: 700;
      color: rgba(255,255,255,0.4); letter-spacing: 1.2px;
      text-transform: uppercase; margin-bottom: 7px;
    }
    .input {
      width: 100%;
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 12px;
      padding: 13px 16px;
      font-size: 14px; font-family: inherit; color: white;
      outline: none; transition: all 0.2s; margin-bottom: 14px;
    }
    .input::placeholder { color: rgba(255,255,255,0.2); }
    .input:focus {
      border-color: rgba(16,185,129,0.5);
      background: rgba(16,185,129,0.05);
      box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
    }
    .error {
      background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25);
      border-radius: 10px; padding: 10px 14px;
      font-size: 12px; color: #fca5a5;
      margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
    }
    .btn {
      width: 100%;
      background: linear-gradient(135deg, var(--emerald), var(--emerald-d));
      border: none; border-radius: 12px;
      padding: 14px;
      font-size: 14px; font-weight: 800; color: white;
      font-family: inherit; cursor: pointer; transition: all 0.2s;
      box-shadow: 0 4px 20px rgba(16,185,129,0.35);
      margin-top: 4px; letter-spacing: 0.3px;
    }
    .btn:hover { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(16,185,129,0.45); }
    .btn:active { transform: translateY(0); }
    .divider { height: 1px; background: rgba(255,255,255,0.06); margin: 26px 0; }
    .hint { text-align: center; font-size: 11px; color: rgba(255,255,255,0.25); margin-bottom: 20px; }
    .hint code { background: rgba(255,255,255,0.06); padding: 2px 6px; border-radius: 5px; color: rgba(255,255,255,0.45); }
    .features { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; }
    .feat { text-align: center; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 10px 6px; font-size: 9px; color: rgba(255,255,255,0.35); font-weight: 600; letter-spacing: 0.5px; }
    .feat .ico { font-size: 18px; display: block; margin-bottom: 5px; }
  </style>
</head>
<body>
  <div class="login-wrap">
    <div class="card">
      <div class="pill"><div class="pill-dot"></div>Smart Tourism System</div>
      <h1>Selamat<br><span>Datang</span></h1>
      <p class="sub">Sistem Pemetaan Potensi Wisata Kabupaten Magelang berbasis K-Means Clustering</p>

      <?php if ($error): ?>
      <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <label class="label">Username</label>
        <input type="text" name="username" class="input" placeholder="Masukkan username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        <label class="label">Password</label>
        <input type="password" name="password" class="input" placeholder="••••••••" required>
        <button type="submit" class="btn">Masuk ke Sistem →</button>
      </form>

      <div class="divider"></div>
      <div class="hint">Demo: <code>admin</code> / <code>admin123</code></div>
      <div class="features">
        <div class="feat"><span class="ico">🗺️</span>Peta Interaktif</div>
        <div class="feat"><span class="ico">🤖</span>K-Means AI</div>
        <div class="feat"><span class="ico">📊</span>Analitik Data</div>
      </div>
    </div>
  </div>
</body>
</html>

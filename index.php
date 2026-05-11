<?php
session_start();
if (isset($_SESSION['user'])) {
  header('Location: pages/dashboard.php');
  exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  if ($username === 'admin' && $password === 'admin123') {
    $_SESSION['user'] = $username;
    header('Location: pages/dashboard.php');
    exit;
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
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <style>
    :root {
      --emerald: #059669;
      --emerald-dark: #047857;
      --emerald-light: #34d399;
      --emerald-pale: #d1fae5;
      --gold: #d97706;
      --gold-light: #fbbf24;
      --slate: #1e293b;
      --slate-mid: #334155;
      --slate-light: #64748b;
      --bg: #f0fdf4;
      --white: #ffffff;
      --danger: #ef4444;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
    }

    /* Animated background */
    .bg-scene {
      position: fixed;
      inset: 0;
      z-index: 0;
      background: linear-gradient(135deg, #064e3b 0%, #065f46 30%, #047857 60%, #1a1a2e 100%);
      overflow: hidden;
    }

    .bg-scene::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2334d399' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      opacity: 0.5;
    }

    .orb {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.15;
      animation: drift 8s ease-in-out infinite;
    }

    .orb1 {
      width: 600px;
      height: 600px;
      background: #34d399;
      top: -200px;
      left: -200px;
      animation-delay: 0s;
    }

    .orb2 {
      width: 400px;
      height: 400px;
      background: #d97706;
      bottom: -100px;
      right: -100px;
      animation-delay: 3s;
    }

    .orb3 {
      width: 300px;
      height: 300px;
      background: #60a5fa;
      top: 50%;
      left: 50%;
      animation-delay: 1.5s;
    }

    @keyframes drift {

      0%,
      100% {
        transform: translate(0, 0) scale(1);
      }

      33% {
        transform: translate(30px, -20px) scale(1.05);
      }

      66% {
        transform: translate(-20px, 15px) scale(0.95);
      }
    }

    /* Floating temple silhouette */
    .temple-silhouette {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 1;
      height: 200px;
      background: linear-gradient(to top, rgba(4, 40, 24, 0.8) 0%, transparent 100%);
    }

    .login-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 440px;
      padding: 20px;
      animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      opacity: 0;
      transform: translateY(40px);
    }

    @keyframes slideUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-card {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 28px;
      padding: 48px 40px;
      box-shadow: 0 32px 64px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.05);
    }

    .brand-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(52, 211, 153, 0.15);
      border: 1px solid rgba(52, 211, 153, 0.3);
      border-radius: 100px;
      padding: 6px 14px;
      font-size: 11px;
      font-weight: 600;
      color: #34d399;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-bottom: 24px;
    }

    .brand-badge::before {
      content: '●';
      font-size: 8px;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1
      }

      50% {
        opacity: 0.3
      }
    }

    .login-title {
      font-family: 'DM Serif Display', serif;
      font-size: 38px;
      line-height: 1.1;
      color: white;
      margin-bottom: 8px;
    }

    .login-title em {
      font-style: italic;
      color: #34d399;
    }

    .login-subtitle {
      font-size: 14px;
      color: rgba(255, 255, 255, 0.5);
      margin-bottom: 36px;
      line-height: 1.6;
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.6);
      letter-spacing: 0.8px;
      text-transform: uppercase;
      margin-bottom: 8px;
    }

    .form-input {
      width: 100%;
      background: rgba(255, 255, 255, 0.07);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 14px;
      padding: 14px 18px;
      font-size: 15px;
      font-family: inherit;
      color: white;
      transition: all 0.2s;
      outline: none;
    }

    .form-input::placeholder {
      color: rgba(255, 255, 255, 0.25);
    }

    .form-input:focus {
      border-color: rgba(52, 211, 153, 0.5);
      background: rgba(52, 211, 153, 0.08);
      box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.1);
    }

    .error-msg {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.3);
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 13px;
      color: #fca5a5;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-login {
      width: 100%;
      background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dark) 100%);
      border: none;
      border-radius: 14px;
      padding: 15px;
      font-size: 15px;
      font-weight: 700;
      color: white;
      font-family: inherit;
      cursor: pointer;
      transition: all 0.2s;
      position: relative;
      overflow: hidden;
      margin-top: 8px;
      box-shadow: 0 4px 24px rgba(5, 150, 105, 0.4);
    }

    .btn-login:hover {
      transform: translateY(-1px);
      box-shadow: 0 8px 32px rgba(5, 150, 105, 0.5);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .demo-hint {
      text-align: center;
      margin-top: 20px;
      font-size: 12px;
      color: rgba(255, 255, 255, 0.3);
    }

    .demo-hint code {
      background: rgba(255, 255, 255, 0.08);
      padding: 2px 7px;
      border-radius: 5px;
      color: rgba(255, 255, 255, 0.5);
    }

    .divider {
      height: 1px;
      background: rgba(255, 255, 255, 0.08);
      margin: 28px 0;
    }

    .features-row {
      display: flex;
      gap: 12px;
    }

    .feature-tag {
      flex: 1;
      text-align: center;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 10px;
      padding: 10px 6px;
      font-size: 10px;
      color: rgba(255, 255, 255, 0.4);
      font-weight: 500;
      letter-spacing: 0.3px;
    }

    .feature-tag span {
      display: block;
      font-size: 18px;
      margin-bottom: 4px;
    }
  </style>
</head>

<body>
  <div class="bg-scene">
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>
  </div>
  <div class="temple-silhouette"></div>

  <div class="login-container">
    <div class="login-card">
      <div class="brand-badge">Smart Tourism System</div>
      <h1 class="login-title">Selamat<br><em>Datang</em></h1>
      <p class="login-subtitle">Sistem Pemetaan Potensi Wisata Kabupaten Magelang berbasis AI & K-Means Clustering</p>

      <?php if ($error): ?>
        <div class="error-msg">⚠ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="form-group">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-input" placeholder="Masukkan username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-input" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-login">🔐 Masuk ke Sistem</button>
      </form>

      <div class="demo-hint">Demo: <code>admin</code> / <code>admin123</code></div>

      <div class="divider"></div>
      <div class="features-row">
        <div class="feature-tag"><span>🗺️</span>Peta Interaktif</div>
        <div class="feature-tag"><span>🤖</span>K-Means AI</div>
        <div class="feature-tag"><span>📊</span>Analitik Data</div>
      </div>
    </div>
  </div>
</body>

</html>
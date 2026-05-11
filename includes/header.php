<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$navItems = [
    'dashboard' => ['icon' => '📊', 'label' => 'Dashboard'],
    'data_wisata' => ['icon' => '🗺️', 'label' => 'Data Wisata'],
    'clustering' => ['icon' => '🤖', 'label' => 'K-Means Clustering'],
    'iterasi' => ['icon' => '🔄', 'label' => 'Detail Iterasi'],
    'hasil' => ['icon' => '📍', 'label' => 'Hasil Clustering'],
    'peta' => ['icon' => '🗾', 'label' => 'Peta Interaktif'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SmarTourism Magelang</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<style>
:root {
  --emerald: #059669; --emerald-dark: #047857; --emerald-light: #34d399;
  --emerald-pale: #ecfdf5; --gold: #d97706; --gold-light: #fbbf24;
  --purple: #6366f1; --slate: #1e293b; --slate-mid: #334155;
  --slate-light: #64748b; --slate-pale: #f8fafc;
  --white: #ffffff; --border: #e2e8f0;
  --sidebar-w: 260px; --header-h: 64px;
  --text: #1e293b; --text-muted: #64748b;
  --danger: #ef4444; --success: #059669; --warning: #d97706;
  --shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.04);
  --shadow-lg: 0 8px 32px rgba(0,0,0,0.1);
}
* { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; color: var(--text); }
a { text-decoration: none; color: inherit; }

/* ========== LAYOUT ========== */
.app-layout { display: flex; min-height: 100vh; }

/* ========== SIDEBAR ========== */
.sidebar {
  width: var(--sidebar-w); background: var(--slate);
  display: flex; flex-direction: column;
  position: fixed; top: 0; left: 0; height: 100vh; z-index: 100;
  transition: transform 0.3s cubic-bezier(0.16,1,0.3,1);
}
.sidebar-brand {
  padding: 20px 20px 16px;
  border-bottom: 1px solid rgba(255,255,255,0.06);
}
.brand-logo {
  display: flex; align-items: center; gap: 10px;
}
.brand-icon {
  width: 36px; height: 36px; border-radius: 10px;
  background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; flex-shrink: 0;
}
.brand-text-main {
  font-family: 'DM Serif Display', serif;
  font-size: 16px; color: white; line-height: 1;
}
.brand-text-sub { font-size: 10px; color: rgba(255,255,255,0.35); letter-spacing: 0.5px; margin-top: 2px; }

.sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
.nav-section-label {
  padding: 16px 20px 6px;
  font-size: 9px; font-weight: 700; letter-spacing: 1.5px;
  color: rgba(255,255,255,0.25); text-transform: uppercase;
}
.nav-item {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 20px; margin: 1px 8px;
  border-radius: 10px; font-size: 13.5px; font-weight: 500;
  color: rgba(255,255,255,0.55);
  transition: all 0.15s; cursor: pointer;
  position: relative;
}
.nav-item:hover { background: rgba(255,255,255,0.07); color: white; }
.nav-item.active {
  background: linear-gradient(135deg, rgba(5,150,105,0.3), rgba(5,150,105,0.1));
  color: var(--emerald-light);
  border: 1px solid rgba(52,211,153,0.2);
}
.nav-item.active::before {
  content: ''; position: absolute; left: -8px;
  width: 3px; height: 20px; background: var(--emerald-light);
  border-radius: 0 2px 2px 0;
}
.nav-icon { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
.nav-badge {
  margin-left: auto; background: var(--emerald);
  color: white; font-size: 9px; font-weight: 700;
  padding: 2px 6px; border-radius: 100px;
}

.sidebar-footer {
  padding: 16px 20px;
  border-top: 1px solid rgba(255,255,255,0.06);
}
.user-card {
  display: flex; align-items: center; gap: 10px;
}
.user-avatar {
  width: 32px; height: 32px; border-radius: 8px;
  background: linear-gradient(135deg, var(--emerald), var(--gold));
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; color: white; font-weight: 700; flex-shrink: 0;
}
.user-name { font-size: 13px; font-weight: 600; color: white; }
.user-role { font-size: 10px; color: rgba(255,255,255,0.35); }
.logout-btn {
  margin-left: auto; background: rgba(239,68,68,0.15);
  border: 1px solid rgba(239,68,68,0.2); border-radius: 6px;
  padding: 4px 8px; font-size: 10px; color: #fca5a5;
  cursor: pointer; font-family: inherit; transition: all 0.15s;
}
.logout-btn:hover { background: rgba(239,68,68,0.25); }

/* ========== MAIN CONTENT ========== */
.main-content {
  margin-left: var(--sidebar-w);
  flex: 1; display: flex; flex-direction: column;
  min-height: 100vh;
}

/* ========== TOP BAR ========== */
.topbar {
  height: var(--header-h); background: white;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center;
  padding: 0 28px; gap: 16px;
  position: sticky; top: 0; z-index: 50;
  box-shadow: 0 1px 0 var(--border);
}
.hamburger {
  display: none; background: none; border: none;
  font-size: 20px; cursor: pointer; padding: 4px;
}
.topbar-title { font-size: 15px; font-weight: 700; flex: 1; }
.topbar-badge {
  background: var(--emerald-pale); color: var(--emerald-dark);
  font-size: 11px; font-weight: 600; padding: 4px 10px;
  border-radius: 100px; border: 1px solid rgba(5,150,105,0.2);
}

/* ========== PAGE CONTENT ========== */
.page-content { padding: 28px; flex: 1; max-width: 1400px; }

/* ========== CARDS ========== */
.card {
  background: white; border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
  overflow: hidden;
}
.card-header {
  padding: 20px 24px; border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 12px;
}
.card-title {
  font-size: 15px; font-weight: 700; color: var(--slate);
  display: flex; align-items: center; gap: 8px;
}
.card-body { padding: 24px; }

/* ========== STAT CARDS ========== */
.stats-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px; margin-bottom: 24px;
}
.stat-card {
  background: white; border-radius: 16px;
  border: 1px solid var(--border);
  padding: 20px; box-shadow: var(--shadow);
  transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
.stat-icon {
  width: 44px; height: 44px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; margin-bottom: 12px;
}
.stat-value { font-size: 28px; font-weight: 800; line-height: 1; margin-bottom: 4px; }
.stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; }
.stat-change { font-size: 11px; margin-top: 6px; font-weight: 600; }
.stat-change.up { color: var(--emerald); }
.stat-change.neutral { color: var(--text-muted); }

/* ========== TABLES ========== */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
th {
  text-align: left; padding: 10px 14px;
  font-size: 10.5px; font-weight: 700; letter-spacing: 0.5px;
  text-transform: uppercase; color: var(--text-muted);
  background: var(--slate-pale); border-bottom: 1px solid var(--border);
  white-space: nowrap;
}
td {
  padding: 12px 14px; border-bottom: 1px solid #f1f5f9;
  vertical-align: middle;
}
tr:hover td { background: #f8fafc; }
tr:last-child td { border-bottom: none; }

/* ========== BADGES ========== */
.badge {
  display: inline-flex; align-items: center;
  padding: 3px 10px; border-radius: 100px;
  font-size: 11px; font-weight: 600;
}
.badge-green { background: #dcfce7; color: #15803d; }
.badge-yellow { background: #fef9c3; color: #a16207; }
.badge-purple { background: #ede9fe; color: #6d28d9; }
.badge-blue { background: #dbeafe; color: #1d4ed8; }
.badge-red { background: #fee2e2; color: #b91c1c; }

/* ========== CLUSTER BADGES ========== */
.cluster-high { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
.cluster-medium { background: #fef3c7; color: #78350f; border: 1px solid #fcd34d; }
.cluster-low { background: #ede9fe; color: #4c1d95; border: 1px solid #c4b5fd; }

/* ========== BUTTONS ========== */
.btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border-radius: 10px;
  font-size: 13px; font-weight: 600; font-family: inherit;
  cursor: pointer; border: none; transition: all 0.15s;
}
.btn-primary {
  background: var(--emerald); color: white;
}
.btn-primary:hover { background: var(--emerald-dark); transform: translateY(-1px); }
.btn-secondary {
  background: var(--slate-pale); color: var(--slate);
  border: 1px solid var(--border);
}
.btn-secondary:hover { background: var(--border); }
.btn-sm { padding: 5px 11px; font-size: 12px; border-radius: 8px; }

/* ========== GRID HELPERS ========== */
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.mb-20 { margin-bottom: 20px; }
.mb-24 { margin-bottom: 24px; }

/* ========== PROGRESS BAR ========== */
.progress-bar {
  height: 6px; background: #f1f5f9; border-radius: 100px; overflow: hidden;
}
.progress-fill { height: 100%; border-radius: 100px; transition: width 0.6s ease; }

/* ========== OVERLAY ========== */
.sidebar-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,0.5); z-index: 99;
}

/* ========== MOBILE ========== */
@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }
  .sidebar.open {
    transform: translateX(0);
  }
  .sidebar-overlay.show { display: block; }
  .main-content { margin-left: 0; }
  .hamburger { display: block; }
  .page-content { padding: 16px; }
  .grid-2 { grid-template-columns: 1fr; }
  .grid-3 { grid-template-columns: 1fr; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
  .stats-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>
<div class="app-layout">

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-logo">
      <div class="brand-icon">🏛️</div>
      <div>
        <div class="brand-text-main">SmarTourism</div>
        <div class="brand-text-sub">Kabupaten Magelang</div>
      </div>
    </div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section-label">Menu Utama</div>
    <?php foreach ($navItems as $page => $item): ?>
    <a href="<?= $page ?>.php" class="nav-item <?= $currentPage === $page ? 'active' : '' ?>">
      <span class="nav-icon"><?= $item['icon'] ?></span>
      <span><?= $item['label'] ?></span>
      <?php if ($page === 'clustering'): ?>
      <span class="nav-badge">AI</span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </nav>
  <div class="sidebar-footer">
    <div class="user-card">
      <div class="user-avatar">A</div>
      <div>
        <div class="user-name">Administrator</div>
        <div class="user-role">Super Admin</div>
      </div>
      <form method="POST" action="../logout.php" style="margin:0">
        <button type="submit" class="logout-btn">Keluar</button>
      </form>
    </div>
  </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="main-content">
  <header class="topbar">
    <button class="hamburger" onclick="toggleSidebar()">☰</button>
    <div class="topbar-title"><?= $navItems[$currentPage]['icon'] ?? '📌' ?> <?= $navItems[$currentPage]['label'] ?? ucfirst($currentPage) ?></div>
    <div class="topbar-badge">K-Means AI v2.0</div>
  </header>

<script>
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('sidebarOverlay').classList.toggle('show');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebarOverlay').classList.remove('show');
}
</script>

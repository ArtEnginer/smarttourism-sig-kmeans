<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: ../index.php');
  exit;
}
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$navItems = [
  'dashboard'   => ['icon'=>'grid',       'label'=>'Dashboard'],
  'data_wisata' => ['icon'=>'database',   'label'=>'Data Wisata'],
  'clustering'  => ['icon'=>'cpu',        'label'=>'Clustering'],
  'iterasi'     => ['icon'=>'refresh-cw', 'label'=>'Iterasi'],
  'hasil'       => ['icon'=>'bar-chart-2','label'=>'Hasil'],
  'peta'        => ['icon'=>'map',        'label'=>'Peta'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmarTourism Magelang</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <style>
    :root {
      --bg:        #0a0e1a;
      --surface:   #111827;
      --surface-2: #1a2233;
      --surface-3: #1f2b3e;
      --border:    #1e2d42;
      --border-2:  #253549;
      --emerald:   #10b981;
      --emerald-d: #059669;
      --emerald-l: #34d399;
      --emerald-gl:#d1fae5;
      --gold:      #f59e0b;
      --gold-l:    #fbbf24;
      --purple:    #8b5cf6;
      --blue:      #3b82f6;
      --red:       #ef4444;
      --text:      #f1f5f9;
      --text-2:    #94a3b8;
      --text-3:    #64748b;
      --mono:      'DM Mono', monospace;
      --radius:    14px;
      --radius-sm: 8px;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body {
      font-family: 'Syne', sans-serif;
      background: var(--bg);
      color: var(--text);
      display: flex;
      min-height: 100vh;
    }
    /* ---- SIDEBAR ---- */
    .sidebar {
      width: 240px;
      flex-shrink: 0;
      background: var(--surface);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0; left: 0; bottom: 0;
      z-index: 100;
    }
    .sidebar-brand {
      padding: 24px 20px 20px;
      border-bottom: 1px solid var(--border);
    }
    .brand-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 6px;
    }
    .brand-icon {
      width: 36px; height: 36px;
      background: linear-gradient(135deg, var(--emerald), var(--emerald-d));
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px;
      box-shadow: 0 4px 12px rgba(16,185,129,0.3);
    }
    .brand-name {
      font-size: 16px; font-weight: 800; color: var(--text);
      letter-spacing: -0.3px;
    }
    .brand-sub {
      font-size: 10px; color: var(--text-3);
      font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase;
      margin-top: 2px;
    }
    .sidebar-nav {
      flex: 1;
      padding: 16px 12px;
      overflow-y: auto;
    }
    .nav-section-label {
      font-size: 9px; font-weight: 700; letter-spacing: 2px;
      color: var(--text-3); text-transform: uppercase;
      padding: 0 8px; margin-bottom: 8px; margin-top: 16px;
    }
    .nav-section-label:first-child { margin-top: 0; }
    .nav-item {
      display: flex; align-items: center; gap: 10px;
      padding: 9px 10px; border-radius: 10px;
      color: var(--text-2); font-size: 13px; font-weight: 500;
      text-decoration: none; cursor: pointer;
      transition: all 0.15s; margin-bottom: 2px;
      border: 1px solid transparent;
    }
    .nav-item:hover { background: var(--surface-2); color: var(--text); }
    .nav-item.active {
      background: rgba(16,185,129,0.12);
      color: var(--emerald);
      border-color: rgba(16,185,129,0.2);
    }
    .nav-item .icon { width: 16px; height: 16px; flex-shrink: 0; }
    .sidebar-footer {
      padding: 14px 12px;
      border-top: 1px solid var(--border);
    }
    .logout-btn {
      display: flex; align-items: center; gap: 10px;
      width: 100%; padding: 9px 10px; border-radius: 10px;
      background: none; border: none; color: var(--text-3);
      font-size: 13px; font-weight: 500; cursor: pointer;
      font-family: inherit; transition: all 0.15s;
    }
    .logout-btn:hover { color: var(--red); background: rgba(239,68,68,0.08); }
    /* ---- MAIN ---- */
    .main-wrapper {
      margin-left: 240px;
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .topbar {
      height: 56px;
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center;
      padding: 0 28px;
      gap: 12px;
      position: sticky; top: 0; z-index: 50;
    }
    .topbar-title { font-size: 15px; font-weight: 700; color: var(--text); }
    .topbar-breadcrumb {
      display: flex; align-items: center; gap: 6px;
      font-size: 12px; color: var(--text-3);
    }
    .topbar-breadcrumb .sep { opacity: 0.4; }
    .topbar-spacer { flex: 1; }
    .topbar-user {
      display: flex; align-items: center; gap: 8px;
      background: var(--surface-2);
      border: 1px solid var(--border);
      border-radius: 20px; padding: 5px 12px 5px 6px;
    }
    .user-avatar {
      width: 26px; height: 26px; border-radius: 50%;
      background: linear-gradient(135deg, var(--emerald), var(--emerald-d));
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 800; color: white;
    }
    .user-name { font-size: 12px; font-weight: 600; color: var(--text-2); }
    .page-content { padding: 28px; flex: 1; }
    /* ---- COMPONENTS ---- */
    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
    }
    .card-header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 16px 20px;
      border-bottom: 1px solid var(--border);
      gap: 12px;
    }
    .card-title {
      display: flex; align-items: center; gap: 8px;
      font-size: 14px; font-weight: 700; color: var(--text);
    }
    .card-body { padding: 20px; }
    .mb-6  { margin-bottom: 6px; }
    .mb-12 { margin-bottom: 12px; }
    .mb-16 { margin-bottom: 16px; }
    .mb-20 { margin-bottom: 20px; }
    .mb-24 { margin-bottom: 24px; }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
    .grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; margin-bottom: 24px; }
    .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 24px; }
    .stat-card {
      background: var(--surface); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 18px 20px;
    }
    .stat-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 17px; margin-bottom: 12px; }
    .stat-value { font-size: 26px; font-weight: 800; line-height: 1; margin-bottom: 4px; }
    .stat-label { font-size: 12px; color: var(--text-3); font-weight: 500; margin-bottom: 4px; }
    .stat-change { font-size: 11px; }
    .stat-change.up { color: var(--emerald); }
    .stat-change.neutral { color: var(--text-3); }
    /* Buttons */
    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; font-family: inherit; cursor: pointer; text-decoration: none; border: 1px solid transparent; transition: all 0.15s; white-space: nowrap; }
    .btn-primary { background: var(--emerald); color: white; border-color: var(--emerald); box-shadow: 0 2px 8px rgba(16,185,129,0.3); }
    .btn-primary:hover { background: var(--emerald-d); }
    .btn-secondary { background: var(--surface-2); color: var(--text-2); border-color: var(--border); }
    .btn-secondary:hover { border-color: var(--border-2); color: var(--text); }
    .btn-sm { padding: 5px 10px; font-size: 12px; border-radius: 7px; }
    /* Badge */
    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 100px; font-size: 11px; font-weight: 700; }
    .cluster-high   { background: rgba(16,185,129,0.15);  color: var(--emerald); }
    .cluster-medium { background: rgba(245,158,11,0.15);  color: var(--gold); }
    .cluster-low    { background: rgba(139,92,246,0.15);  color: var(--purple); }
    .badge-green  { background: rgba(16,185,129,0.15); color: var(--emerald); }
    .badge-blue   { background: rgba(59,130,246,0.15); color: var(--blue); }
    .badge-yellow { background: rgba(245,158,11,0.15); color: var(--gold); }
    .badge-purple { background: rgba(139,92,246,0.15); color: var(--purple); }
    .badge-red    { background: rgba(239,68,68,0.15);  color: var(--red); }
    /* Table */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
    thead tr { background: var(--surface-2); }
    th { padding: 10px 12px; text-align: left; font-size: 11px; font-weight: 700; color: var(--text-3); letter-spacing: 0.4px; text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border); }
    td { padding: 10px 12px; border-bottom: 1px solid var(--border); color: var(--text-2); vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: var(--surface-2); }
    td strong { color: var(--text); }
    /* Progress */
    .progress-bar { height: 4px; background: var(--surface-3); border-radius: 2px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 2px; transition: width 0.4s; }
    /* Formula box */
    .formula-box {
      background: var(--surface-3);
      border: 1px solid var(--border-2);
      border-left: 3px solid var(--emerald);
      border-radius: 10px;
      padding: 12px 16px;
      font-family: var(--mono);
      font-size: 12px;
      color: var(--emerald-l);
      margin-bottom: 16px;
    }
    .formula-box .formula-title { font-family: 'Syne', sans-serif; font-size: 11px; color: var(--text-3); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 4px; }
    /* Step badge */
    .step-badge {
      display: inline-flex; align-items: center; justify-content: center;
      width: 26px; height: 26px; border-radius: 50%;
      font-size: 12px; font-weight: 800; color: white; flex-shrink: 0;
    }
    /* Info box */
    .info-box {
      border-radius: 10px; padding: 12px 16px;
      font-size: 12.5px; margin-bottom: 16px;
    }
    .info-box.green { background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2); color: var(--emerald-l); }
    .info-box.blue  { background: rgba(59,130,246,0.08);  border: 1px solid rgba(59,130,246,0.2);  color: #93c5fd; }
    .info-box.amber { background: rgba(245,158,11,0.08);  border: 1px solid rgba(245,158,11,0.2);  color: #fcd34d; }
    /* Mono text */
    .mono { font-family: var(--mono); }
  </style>
</head>
<body>
<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="brand-logo">
      <div class="brand-icon"><i data-lucide="compass" style="width:20px;height:20px;color:white;"></i></div>
      <div>
        <div class="brand-name">SmarTourism</div>
        <div class="brand-sub">Kab. Magelang</div>
      </div>
    </div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section-label">Main</div>
    <?php foreach ($navItems as $page => $item): ?>
    <a href="<?= $page ?>.php" class="nav-item <?= $currentPage === $page ? 'active' : '' ?>">
      <i data-lucide="<?= $item['icon'] ?>" class="icon"></i>
      <?= $item['label'] ?>
    </a>
    <?php endforeach; ?>
  </nav>
  <div class="sidebar-footer">
    <a href="../index.php" class="logout-btn">
      <i data-lucide="log-out" style="width:15px;height:15px;"></i>
      Keluar
    </a>
  </div>
</aside>
<!-- MAIN -->
<div class="main-wrapper">
  <header class="topbar">
    <?php $labels = ['dashboard'=>'Dashboard','data_wisata'=>'Data Wisata','clustering'=>'K-Means Clustering','iterasi'=>'Detail Iterasi','hasil'=>'Hasil Clustering','peta'=>'Peta Interaktif']; ?>
    <div class="topbar-breadcrumb">
      <span>SmarTourism</span>
      <span class="sep">/</span>
      <span style="color:var(--text); font-weight:600;"><?= $labels[$currentPage] ?? ucfirst($currentPage) ?></span>
    </div>
    <div class="topbar-spacer"></div>
    <div class="topbar-user">
      <div class="user-avatar">A</div>
      <span class="user-name">Admin</span>
    </div>
  </header>
<script>
document.addEventListener('DOMContentLoaded', () => { if(window.lucide) lucide.createIcons(); });
</script>

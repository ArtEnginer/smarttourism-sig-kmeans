<?php
require_once __DIR__ . '/auth.php';
requireLogin();

$user = getCurrentUser();
$isAdminUser = $user && (($user['role'] ?? '') === 'admin');

$currentPage = basename($_SERVER['PHP_SELF'], '.php');

/*
|--------------------------------------------------------------------------
| MENU USER
|--------------------------------------------------------------------------
*/
$userNavItems = [
  'dashboard' => [
    'icon'  => 'layout-dashboard',
    'label' => 'Dashboard',
    'url'   => appUrl('pages/dashboard.php')
  ],
  'data_wisata' => [
    'icon'  => 'database',
    'label' => 'Data Wisata',
    'url'   => appUrl('pages/data_wisata.php')
  ],
  'clustering' => [
    'icon'  => 'brain-circuit',
    'label' => 'K-Means Clustering',
    'url'   => appUrl('pages/clustering.php')
  ],
  'iterasi' => [
    'icon'  => 'history',
    'label' => 'Detail Iterasi',
    'url'   => appUrl('pages/iterasi.php')
  ],
  'hasil' => [
    'icon'  => 'bar-chart-3',
    'label' => 'Hasil Clustering',
    'url'   => appUrl('pages/hasil.php')
  ],
  'peta' => [
    'icon'  => 'map',
    'label' => 'Peta Interaktif',
    'url'   => appUrl('pages/peta.php')
  ],
  'api_docs' => [
    'icon'  => 'code-2',
    'label' => 'Dokumentasi API',
    'url'   => appUrl('pages/api_docs.php')
  ],
];

/*
|--------------------------------------------------------------------------
| MENU ADMIN
|--------------------------------------------------------------------------
*/
$adminNavItems = [
  'dashboard' => [
    'icon'  => 'shield-check',
    'label' => 'Admin Dashboard',
    'url'   => appUrl('admin/dashboard.php')
  ],
  'destinasi' => [
    'icon'  => 'settings-2',
    'label' => 'Kelola Destinasi',
    'url'   => appUrl('admin/destinasi.php')
  ],
  'users' => [
    'icon'  => 'users',
    'label' => 'Kelola Pengguna',
    'url'   => appUrl('admin/users.php')
  ],
  'api_docs' => [
    'icon'  => 'code-2',
    'label' => 'Dokumentasi API',
    'url'   => appUrl('pages/api_docs.php')
  ],
];

$allNavItems = $isAdminUser ? $adminNavItems : $userNavItems;

$currentPageKey = basename($_SERVER['PHP_SELF'], '.php');

$currentNav = $allNavItems[$currentPageKey]
  ?? [
    'icon' => 'circle',
    'label' => ucfirst($currentPage)
  ];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <meta name="theme-color" content="#059669">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <title>SmarTourism Magelang — Smart Tourism GIS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <style>
    :root {
      /* Dominant White & Fresh Light Green Palette */
      --bg:            #f8fafc;
      --surface:       #ffffff;
      --surface-2:     #f0fdf4;
      --surface-3:     #e6f4ea;
      
      --emerald:       #059669;
      --emerald-dark:  #047857;
      --emerald-light: #10b981;
      --emerald-pale:  #ecfdf5;
      --emerald-soft:  #dcfce7;
      --emerald-border:#a7f3d0;

      --gold:          #d97706;
      --gold-light:    #fef3c7;
      --purple:        #7c3aed;
      --purple-light:  #f3e8ff;
      --blue:          #2563eb;
      --blue-light:    #dbeafe;
      --red:           #dc2626;
      --red-light:     #fee2e2;

      --slate:         #0f172a;
      --slate-mid:     #334155;
      --slate-light:   #64748b;
      --slate-pale:    #f1f5f9;
      --white:         #ffffff;
      --border:        #e2e8f0;
      --border-emerald:#cbd5e1;

      --sidebar-w:     260px;
      --header-h:      62px;
      --mobile-nav-h:  68px;

      --text:          #0f172a;
      --text-muted:    #64748b;
      --text-emerald:  #047857;

      --shadow-sm:     0 1px 3px rgba(15, 23, 42, 0.05);
      --shadow:        0 4px 14px rgba(15, 23, 42, 0.05), 0 1px 2px rgba(5, 150, 105, 0.04);
      --shadow-md:     0 8px 24px rgba(5, 150, 105, 0.08);
      --shadow-lg:     0 16px 36px rgba(5, 150, 105, 0.12);

      --radius-sm:     10px;
      --radius:        16px;
      --radius-lg:     22px;
    }

    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      -webkit-tap-highlight-color: transparent;
    }

    html, body {
      height: 100%;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: var(--bg);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    /* ========== LAYOUT ========== */
    .app-layout {
      display: flex;
      min-height: 100vh;
      background: var(--bg);
    }

    /* ========== DESKTOP SIDEBAR ========== */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--surface);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      z-index: 100;
      transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      box-shadow: 2px 0 16px rgba(15, 23, 42, 0.03);
    }

    .sidebar-brand {
      padding: 20px 20px 18px;
      border-bottom: 1px solid var(--border);
    }

    .brand-logo {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .brand-icon {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
    }

    .brand-icon svg {
      width: 22px;
      height: 22px;
      color: white;
    }

    .brand-text-main {
      font-size: 17px;
      font-weight: 800;
      color: var(--slate);
      line-height: 1.2;
      letter-spacing: -0.3px;
    }

    .brand-text-sub {
      font-size: 10.5px;
      color: var(--emerald-dark);
      font-weight: 600;
      letter-spacing: 0.5px;
      margin-top: 2px;
      text-transform: uppercase;
    }

    .sidebar-nav {
      flex: 1;
      padding: 16px 12px;
      overflow-y: auto;
    }

    .nav-section-label {
      padding: 12px 12px 8px;
      font-size: 10px;
      font-weight: 800;
      letter-spacing: 1.5px;
      color: var(--slate-light);
      text-transform: uppercase;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 14px;
      margin-bottom: 4px;
      border-radius: var(--radius-sm);
      font-size: 13.5px;
      font-weight: 600;
      color: var(--slate-mid);
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
      cursor: pointer;
      position: relative;
    }

    .nav-item:hover {
      background: var(--surface-2);
      color: var(--emerald-dark);
      transform: translateX(2px);
    }

    .nav-item.active {
      background: var(--emerald-soft);
      color: var(--emerald-dark);
      font-weight: 700;
      border: 1px solid var(--emerald-border);
      box-shadow: 0 2px 8px rgba(5, 150, 105, 0.08);
    }

    .nav-item.active::before {
      content: '';
      position: absolute;
      left: -12px;
      top: 20%;
      bottom: 20%;
      width: 4px;
      background: var(--emerald);
      border-radius: 0 4px 4px 0;
    }

    .nav-icon {
      width: 22px;
      height: 22px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .nav-icon svg {
      width: 18px;
      height: 18px;
    }

    .nav-badge {
      margin-left: auto;
      background: var(--emerald);
      color: white;
      font-size: 10px;
      font-weight: 800;
      padding: 2px 7px;
      border-radius: 999px;
    }

    .sidebar-footer {
      padding: 16px;
      border-top: 1px solid var(--border);
      background: var(--surface-2);
    }

    .user-card {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .user-avatar {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--emerald), var(--emerald-light));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      color: white;
      font-weight: 800;
      flex-shrink: 0;
      box-shadow: 0 2px 8px rgba(5, 150, 105, 0.2);
    }

    .user-name {
      font-size: 13px;
      font-weight: 700;
      color: var(--slate);
      line-height: 1.2;
    }

    .user-role {
      font-size: 11px;
      color: var(--slate-light);
      font-weight: 500;
    }

    .logout-btn {
      margin-left: auto;
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 8px;
      padding: 6px 10px;
      font-size: 11px;
      font-weight: 700;
      color: var(--red);
      cursor: pointer;
      font-family: inherit;
      transition: all 0.15s;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .logout-btn:hover {
      background: var(--red);
      color: white;
      border-color: var(--red);
    }

    /* ========== MAIN CONTENT ========== */
    .main-content {
      margin-left: var(--sidebar-w);
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      width: calc(100% - var(--sidebar-w));
      transition: all 0.3s;
    }

    /* ========== TOP BAR ========== */
    .topbar {
      height: var(--header-h);
      background: rgba(255, 255, 255, 0.92);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      padding: 0 24px;
      gap: 16px;
      position: sticky;
      top: 0;
      z-index: 50;
      box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
    }

    .hamburger {
      display: none;
      background: var(--surface-2);
      border: 1px solid var(--emerald-border);
      color: var(--emerald-dark);
      width: 38px;
      height: 38px;
      border-radius: 10px;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
    }

    .hamburger:active {
      transform: scale(0.95);
    }

    .topbar-title {
      font-size: 15px;
      font-weight: 700;
      color: var(--slate);
      flex: 1;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .topbar-badge {
      background: var(--emerald-pale);
      color: var(--emerald-dark);
      font-size: 11.5px;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: 999px;
      border: 1px solid var(--emerald-border);
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .topbar-badge::before {
      content: '';
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--emerald-light);
      display: inline-block;
    }

    /* ========== PAGE CONTENT CONTAINER ========== */
    .page-content {
      padding: 24px;
      flex: 1;
      max-width: 1400px;
      width: 100%;
      margin: 0 auto;
    }

    /* ========== HERO CARDS ========== */
    .page-hero {
      background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
      border: 1px solid var(--emerald-border);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow);
      padding: 24px 28px;
      margin-bottom: 24px;
      display: flex;
      justify-content: space-between;
      gap: 20px;
      align-items: flex-start;
      flex-wrap: wrap;
      position: relative;
      overflow: hidden;
    }

    .page-hero::before {
      content: '';
      position: absolute;
      top: -30px;
      right: -30px;
      width: 180px;
      height: 180px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, transparent 70%);
      pointer-events: none;
    }

    .page-hero-title {
      font-size: 22px;
      font-weight: 800;
      color: var(--slate);
      letter-spacing: -0.4px;
      margin-bottom: 6px;
    }

    .page-hero-subtitle {
      font-size: 13.5px;
      color: var(--slate-light);
      line-height: 1.6;
      max-width: 760px;
    }

    .page-hero-meta {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center;
    }

    .page-hero-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 999px;
      border: 1px solid var(--emerald-border);
      background: var(--surface);
      color: var(--emerald-dark);
      font-size: 12px;
      font-weight: 700;
      box-shadow: var(--shadow-sm);
    }

    .page-hero-actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center;
    }

    /* ========== CARDS & PANELS ========== */
    .card, .panel {
      background: var(--surface);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      overflow: hidden;
      transition: box-shadow 0.2s, border-color 0.2s;
    }

    .card:hover, .panel:hover {
      box-shadow: var(--shadow-md);
      border-color: var(--border-emerald);
    }

    .card-header {
      padding: 18px 22px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      background: var(--surface);
    }

    .card-title {
      font-size: 15px;
      font-weight: 700;
      color: var(--slate);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .card-body, .panel-body {
      padding: 22px;
    }

    /* ========== FORM CONTROLS ========== */
    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 14px;
      margin-bottom: 20px;
    }

    .form-field {
      display: flex;
      flex-direction: column;
      gap: 7px;
    }

    .form-label {
      font-size: 12px;
      font-weight: 700;
      color: var(--slate);
    }

    .form-control, select, input[type="text"], input[type="number"], input[type="password"], textarea {
      width: 100%;
      padding: 11px 14px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      background: var(--surface);
      color: var(--slate);
      font-family: inherit;
      font-size: 13.5px;
      transition: all 0.2s;
      min-height: 44px;
    }

    .form-control:focus, select:focus, input:focus, textarea:focus {
      outline: none;
      border-color: var(--emerald);
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
      background: var(--surface);
    }

    /* ========== STAT CARDS ========== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 18px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: var(--surface);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      padding: 20px 22px;
      box-shadow: var(--shadow);
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
    }

    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-md);
      border-color: var(--emerald-border);
    }

    .stat-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      margin-bottom: 14px;
    }

    .stat-value {
      font-size: 28px;
      font-weight: 800;
      line-height: 1;
      margin-bottom: 6px;
      letter-spacing: -0.5px;
    }

    .stat-label {
      font-size: 12px;
      color: var(--slate-light);
      font-weight: 600;
    }

    .stat-change {
      font-size: 11.5px;
      margin-top: 8px;
      font-weight: 700;
    }

    .stat-change.up { color: var(--emerald-dark); }
    .stat-change.neutral { color: var(--slate-light); }

    /* ========== TABLES ========== */
    .table-wrap {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      border-radius: var(--radius);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }

    th {
      text-align: left;
      padding: 12px 16px;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      color: var(--slate-light);
      background: var(--surface-2);
      border-bottom: 1px solid var(--border);
      white-space: nowrap;
    }

    td {
      padding: 13px 16px;
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
      color: var(--slate-mid);
    }

    tr:hover td {
      background: var(--surface-2);
    }

    tr:last-child td {
      border-bottom: none;
    }

    /* ========== BADGES ========== */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 10px;
      border-radius: 999px;
      font-size: 11.5px;
      font-weight: 700;
      line-height: 1;
    }

    .badge-green, .cluster-high {
      background: var(--emerald-soft);
      color: var(--emerald-dark);
      border: 1px solid var(--emerald-border);
    }

    .badge-yellow, .cluster-medium {
      background: var(--gold-light);
      color: var(--gold);
      border: 1px solid #fde68a;
    }

    .badge-purple, .cluster-low {
      background: var(--purple-light);
      color: var(--purple);
      border: 1px solid #e9d5ff;
    }

    .badge-blue {
      background: var(--blue-light);
      color: var(--blue);
      border: 1px solid #bfdbfe;
    }

    .badge-red {
      background: var(--red-light);
      color: var(--red);
      border: 1px solid #fca5a5;
    }

    /* ========== MODAL COMPONENT ========== */
    .modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.5);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      z-index: 2000;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.25s ease, visibility 0.25s ease;
    }

    .modal-backdrop.open {
      opacity: 1;
      visibility: visible;
    }

    .modal-dialog {
      background: var(--surface);
      border-radius: var(--radius-lg);
      border: 1px solid var(--emerald-border);
      box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
      width: 100%;
      max-width: 640px;
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      transform: scale(0.95) translateY(10px);
      transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
      overflow: hidden;
    }

    .modal-backdrop.open .modal-dialog {
      transform: scale(1) translateY(0);
    }

    .modal-header {
      padding: 18px 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: var(--surface);
    }

    .modal-title {
      font-size: 16px;
      font-weight: 800;
      color: var(--slate);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .modal-close {
      background: var(--surface-2);
      border: 1px solid var(--border);
      border-radius: 10px;
      width: 34px;
      height: 34px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: var(--slate-light);
      transition: all 0.2s;
    }

    .modal-close:hover {
      background: #fee2e2;
      color: var(--red);
      border-color: #fca5a5;
    }

    .modal-body {
      padding: 24px;
      overflow-y: auto;
      flex: 1;
    }

    .modal-footer {
      padding: 16px 24px;
      border-top: 1px solid var(--border);
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      background: var(--surface-2);
    }

    /* ========== BUTTONS ========== */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 10px 18px;
      border-radius: var(--radius-sm);
      font-size: 13px;
      font-weight: 700;
      font-family: inherit;
      cursor: pointer;
      border: 1px solid transparent;
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
      white-space: nowrap;
      min-height: 40px;
    }

    .btn-primary {
      background: var(--emerald);
      color: white;
      box-shadow: 0 3px 12px rgba(5, 150, 105, 0.25);
    }

    .btn-primary:hover {
      background: var(--emerald-dark);
      box-shadow: 0 5px 18px rgba(5, 150, 105, 0.35);
      transform: translateY(-1px);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .btn-secondary {
      background: var(--surface);
      color: var(--slate);
      border-color: var(--border);
      box-shadow: var(--shadow-sm);
    }

    .btn-secondary:hover {
      background: var(--surface-2);
      border-color: var(--emerald-border);
      color: var(--emerald-dark);
    }

    .btn-sm {
      padding: 6px 12px;
      font-size: 12px;
      min-height: 34px;
      border-radius: 8px;
    }

    /* ========== PROGRESS & FORMULAS ========== */
    .progress-bar {
      height: 7px;
      background: var(--slate-pale);
      border-radius: 999px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      border-radius: 999px;
      transition: width 0.5s ease;
    }

    .formula-box {
      background: var(--surface-2);
      border: 1px solid var(--emerald-border);
      border-left: 4px solid var(--emerald);
      border-radius: var(--radius-sm);
      padding: 14px 18px;
      font-size: 12.5px;
      color: var(--emerald-dark);
      margin-bottom: 18px;
    }

    .grid-2 {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    .grid-3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .mb-24 { margin-bottom: 24px; }
    .mb-20 { margin-bottom: 20px; }

    /* ========== MOBILE BOTTOM NAVIGATION BAR (APP BAR NATIVE) ========== */
    .mobile-bottom-nav {
      display: none;
    }

    .sidebar-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.4);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      z-index: 1900;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .sidebar-overlay.show {
      display: block;
      opacity: 1;
    }

    /* ========== RESPONSIVE MOBILE APP BREAKPOINTS ========== */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        z-index: 2000;
        height: 100vh;
        height: 100dvh;
        box-shadow: 4px 0 24px rgba(15, 23, 42, 0.15);
      }

      .sidebar.open {
        transform: translateX(0);
        box-shadow: 4px 0 30px rgba(15, 23, 42, 0.25);
      }

      .sidebar-footer {
        padding-bottom: max(16px, calc(12px + env(safe-area-inset-bottom)));
      }

      .main-content {
        margin-left: 0;
        width: 100%;
        padding-bottom: calc(var(--mobile-nav-h) + 16px);
      }

      .hamburger {
        display: flex;
      }

      .topbar {
        padding: 0 16px;
        height: 58px;
      }

      .page-content {
        padding: 16px 14px;
      }

      .page-hero {
        padding: 18px 16px;
        border-radius: var(--radius);
      }

      .page-hero-title {
        font-size: 19px;
      }

      .grid-2, .grid-3 {
        grid-template-columns: 1fr;
        gap: 16px;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
      }

      .stat-card {
        padding: 14px 16px;
      }

      .stat-value {
        font-size: 22px;
      }

      .stat-icon {
        width: 38px;
        height: 38px;
        font-size: 16px;
        margin-bottom: 10px;
      }

      /* Mobile Bottom Navigation Bar */
      .mobile-bottom-nav {
        display: flex;
        align-items: center;
        justify-content: space-around;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: var(--mobile-nav-h);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-top: 1px solid var(--border);
        box-shadow: 0 -4px 20px rgba(15, 23, 42, 0.06);
        z-index: 1000;
        padding-bottom: env(safe-area-inset-bottom);
      }

      .mobile-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        flex: 1;
        height: 100%;
        color: var(--slate-light);
        font-size: 10px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        position: relative;
      }

      .mobile-nav-item i, .mobile-nav-item svg {
        width: 20px;
        height: 20px;
        transition: transform 0.2s;
      }

      .mobile-nav-item.active {
        color: var(--emerald-dark);
      }

      .mobile-nav-item.active i, .mobile-nav-item.active svg {
        transform: translateY(-2px) scale(1.1);
        color: var(--emerald);
      }

      .mobile-nav-item.active::after {
        content: '';
        position: absolute;
        bottom: 4px;
        width: 16px;
        height: 3px;
        border-radius: 999px;
        background: var(--emerald);
      }
    }

    @media (max-width: 480px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }
      
      .topbar-badge {
        display: none;
      }
    }
  </style>
</head>

<body>
  <div class="app-layout">

    <!-- Desktop Sidebar Drawer -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-brand">
        <div class="brand-logo">
          <div class="brand-icon"><i data-lucide="compass"></i></div>
          <div>
            <div class="brand-text-main">SmarTourism</div>
            <div class="brand-text-sub">Kab. Magelang</div>
          </div>
        </div>
      </div>
      <nav class="sidebar-nav">
        <div class="nav-section-label">
          <?= $isAdminUser ? 'Menu Admin' : 'Menu Utama' ?>
        </div>

        <?php foreach ($allNavItems as $page => $item): ?>
          <a href="<?= htmlspecialchars($item['url']) ?>"
            class="nav-item <?= ($currentPageKey === $page) ? 'active' : '' ?>">

            <span class="nav-icon">
              <i data-lucide="<?= $item['icon'] ?>"></i>
            </span>

            <span><?= htmlspecialchars($item['label']) ?></span>

            <?php if ($page === 'clustering'): ?>
              <span class="nav-badge">AI</span>
            <?php endif; ?>
          </a>
        <?php endforeach; ?>
      </nav>

      <div class="sidebar-footer">
        <div class="user-card">
          <?php $u = getCurrentUser();
          $initial = strtoupper(substr($u['nama_lengkap'] ?? ($u['username'] ?? 'U'), 0, 1)); ?>
          <div class="user-avatar"><?= htmlspecialchars($initial) ?></div>
          <div style="flex:1;min-width:0;">
            <div class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($u['nama_lengkap'] ?? $u['username']) ?></div>
            <div class="user-role"><?= htmlspecialchars(ucfirst($u['role'] ?? 'user')) ?></div>
          </div>
          <form method="POST" action="<?= htmlspecialchars(appUrl('logout.php')) ?>" style="margin:0">
            <button type="submit" class="logout-btn" title="Keluar">
              <i data-lucide="log-out" style="width:13px;height:13px;"></i>
            </button>
          </form>
        </div>
      </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Main Wrapper -->
    <div class="main-content">
      <header class="topbar">
        <button class="hamburger" onclick="toggleSidebar()" aria-label="Buka menu">
          <i data-lucide="menu" style="width:20px;height:20px;"></i>
        </button>
        <div class="topbar-title">
          <div style="width:28px;height:28px;border-radius:8px;background:var(--emerald-soft);display:flex;align-items:center;justify-content:center;color:var(--emerald-dark);">
            <i data-lucide="<?= $currentNav['icon'] ?>" style="width:16px;height:16px;"></i>
          </div>
          <span><?= $currentNav['label'] ?></span>
        </div>
        <div class="topbar-badge">K-Means AI</div>
      </header>

      <script>
        function toggleSidebar() {
          const sidebar = document.getElementById('sidebar');
          const overlay = document.getElementById('sidebarOverlay');
          sidebar.classList.toggle('open');
          overlay.classList.toggle('show');
        }

        function closeSidebar() {
          const sidebar = document.getElementById('sidebar');
          const overlay = document.getElementById('sidebarOverlay');
          sidebar.classList.remove('open');
          overlay.classList.remove('show');
        }

        document.addEventListener('DOMContentLoaded', function() {
          if (window.lucide) {
            lucide.createIcons();
          }
        });
      </script>
<?php
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
require_once __DIR__ . '/../../includes/kmeans.php';

$pdo = getDatabaseConnection();
$totalDestinasi = (int)$pdo->query('SELECT COUNT(*) FROM tb_destinasi')->fetchColumn();
$totalUsers = (int)$pdo->query('SELECT COUNT(*) FROM tb_users')->fetchColumn();
$adminUsers = (int)$pdo->query("SELECT COUNT(*) FROM tb_users WHERE role = 'admin'")->fetchColumn();
$aktifUsers = (int)$pdo->query("SELECT COUNT(*) FROM tb_users WHERE status = 'aktif'")->fetchColumn();
$dataSample = runKMeans(3);
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="page-content">
    <div class="page-hero">
        <div>
            <div class="page-hero-title">Admin Dashboard</div>
            <div class="page-hero-subtitle">Kelola data destinasi, pengguna, dan hasil clustering dari satu tempat.</div>
        </div>
        <div class="page-hero-meta">
            <span class="page-hero-chip">Destinasi <?= $totalDestinasi ?></span>
            <span class="page-hero-chip">Pengguna <?= $totalUsers ?></span>
            <span class="page-hero-chip">Admin <?= $adminUsers ?></span>
            <span class="page-hero-chip">Aktif <?= $aktifUsers ?></span>
            <div class="page-hero-actions">
                <a class="btn btn-primary btn-sm" href="destinasi.php">Kelola Destinasi</a>
                <a class="btn btn-secondary btn-sm" href="users.php">Kelola Pengguna</a>
                <a class="btn btn-secondary btn-sm" href="../dashboard.php">Dashboard Publik</a>
            </div>
        </div>
    </div>

    <div class="card mb-24">
        <div class="card-body">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" style="color:var(--emerald)"><?= $totalDestinasi ?></div>
                    <div class="stat-label">Total Destinasi</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color:var(--blue)"><?= $totalUsers ?></div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color:var(--gold)"><?= $adminUsers ?></div>
                    <div class="stat-label">Admin Aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color:var(--purple)"><?= $aktifUsers ?></div>
                    <div class="stat-label">Akun Aktif</div>
                </div>
            </div>

            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Status K-Means</div>
                    </div>
                    <div class="card-body">
                        <div style="font-size:13px;margin-bottom:8px;">Iterasi: <strong><?= $dataSample['iterations'] ?></strong></div>
                        <div style="font-size:13px;margin-bottom:8px;">SSE: <strong><?= $dataSample['sse'] ?></strong></div>
                        <div style="font-size:13px;">Konvergen: <strong><?= $dataSample['converged'] ? 'Ya' : 'Tidak' ?></strong></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Akses Cepat</div>
                    </div>
                    <div class="card-body" style="display:grid;gap:10px;">
                        <a class="btn btn-primary" href="destinasi.php">Kelola Destinasi</a>
                        <a class="btn btn-secondary" href="users.php">Kelola Pengguna</a>
                        <a class="btn btn-secondary" href="../dashboard.php">Lihat Dashboard Publik</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
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
$clusterCounts = array_count_values(array_column($dataSample['data'], 'cluster'));
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="page-content">

    <!-- Admin Visual Hero Banner -->
    <div class="card mb-24" style="background:linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%);border:1px solid var(--emerald-border);overflow:hidden;position:relative;">
        <div class="card-body" style="padding:28px 32px;">
            <div style="display:grid;grid-template-columns:1fr 300px;gap:24px;align-items:center;">
                <div>
                    <div style="display:inline-flex;align-items:center;gap:6px;background:var(--emerald-soft);border:1px solid var(--emerald-border);padding:4px 12px;border-radius:99px;font-size:11px;font-weight:700;color:var(--emerald-dark);text-transform:uppercase;margin-bottom:12px;">
                        <span style="width:7px;height:7px;border-radius:50%;background:var(--emerald);display:inline-block;"></span> Administrator Portal &bull; Mode Kelola Sistem
                    </div>
                    <h1 style="font-size:24px;font-weight:800;color:var(--slate);margin-bottom:8px;">
                        Dashboard Administrator SmarTourism
                    </h1>
                    <p style="font-size:13px;color:var(--slate-light);margin-bottom:20px;max-width:540px;line-height:1.6;">
                        Kelola data destinasi wisata, manajemen akun pengguna, serta pantau status pengelompokan K-Means Kabupaten Magelang.
                    </p>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <a href="<?= appUrl('admin/destinasi.php') ?>" class="btn btn-primary btn-sm" style="box-shadow:0 4px 14px rgba(5,150,105,0.25);">
                            <i data-lucide="plus-circle" style="width:15px;height:15px;"></i> Kelola Destinasi
                        </a>
                        <a href="<?= appUrl('admin/users.php') ?>" class="btn btn-secondary btn-sm">
                            <i data-lucide="users" style="width:15px;height:15px;"></i> Kelola Pengguna
                        </a>
                        <a href="<?= appUrl('pages/dashboard.php') ?>" class="btn btn-secondary btn-sm">
                            <i data-lucide="external-link" style="width:15px;height:15px;"></i> Portal Publik
                        </a>
                    </div>
                </div>
                <div style="border-radius:16px;overflow:hidden;border:1px solid var(--emerald-border);box-shadow:0 4px 18px rgba(5,150,105,0.12);height:170px;background:#fff;">
                    <img src="<?= appUrl('assets/hero_map.png') ?>" alt="Map Visual Graphic" style="width:100%;height:100%;object-fit:cover;">
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Stats Grid -->
    <div class="stats-grid mb-24">
        <div class="stat-card" style="border-top:3px solid var(--emerald);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <div class="stat-value" style="color:var(--emerald);font-size:28px;"><?= $totalDestinasi ?></div>
                    <div class="stat-label">Total Destinasi</div>
                </div>
                <div class="stat-icon" style="background:var(--emerald-soft);"><i data-lucide="map-pin" style="width:20px;height:20px;color:var(--emerald);"></i></div>
            </div>
            <div class="stat-change neutral" style="margin-top:10px;">Data Destinasi Aktif</div>
        </div>

        <div class="stat-card" style="border-top:3px solid var(--blue);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <div class="stat-value" style="color:var(--blue);font-size:28px;"><?= $totalUsers ?></div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
                <div class="stat-icon" style="background:rgba(59,130,246,0.12);"><i data-lucide="users" style="width:20px;height:20px;color:var(--blue);"></i></div>
            </div>
            <div class="stat-change up" style="margin-top:10px;">Terdaftar di Sistem</div>
        </div>

        <div class="stat-card" style="border-top:3px solid var(--gold);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <div class="stat-value" style="color:var(--gold);font-size:28px;"><?= $adminUsers ?></div>
                    <div class="stat-label">Admin Administrator</div>
                </div>
                <div class="stat-icon" style="background:rgba(245,158,11,0.12);"><i data-lucide="shield-check" style="width:20px;height:20px;color:var(--gold);"></i></div>
            </div>
            <div class="stat-change neutral" style="margin-top:10px;">Akses Penuh Kelola</div>
        </div>

        <div class="stat-card" style="border-top:3px solid var(--purple);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <div class="stat-value" style="color:var(--purple);font-size:28px;"><?= $aktifUsers ?></div>
                    <div class="stat-label">Akun Status Aktif</div>
                </div>
                <div class="stat-icon" style="background:rgba(139,92,246,0.12);"><i data-lucide="user-check" style="width:20px;height:20px;color:var(--purple);"></i></div>
            </div>
            <div class="stat-change up" style="margin-top:10px;">Siap Mengakses Portal</div>
        </div>
    </div>

    <!-- Cluster Analytics & Management Cards -->
    <div class="grid-2 mb-24">
        <!-- Status K-Means Cluster Health -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i data-lucide="activity" style="width:18px;height:18px;color:var(--emerald);"></i>
                    Status Pengelompokan K-Means
                </div>
                <span class="badge badge-green"><i data-lucide="check-circle-2" style="width:12px;height:12px;"></i> Konvergen</span>
            </div>
            <div class="card-body">
                <?php foreach ($dataSample['labels'] as $ci => $label): ?>
                    <?php $cnt = $clusterCounts[$ci] ?? 0;
                    $pct = $totalDestinasi > 0 ? round($cnt / $totalDestinasi * 100) : 0; ?>
                    <div style="margin-bottom:14px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;font-size:13px;font-weight:700;">
                            <span><?= $label ?></span>
                            <span><?= $cnt ?> Destinasi (<?= $pct ?>%)</span>
                        </div>
                        <div class="progress-bar" style="height:8px;border-radius:99px;background:var(--slate-pale);">
                            <div class="progress-fill" style="width:<?= $pct ?>%;background:<?= $dataSample['colors'][$ci] ?>;border-radius:99px;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div style="margin-top:16px;padding:12px 16px;background:var(--slate-pale);border:1px solid var(--border);border-radius:12px;font-size:12px;display:flex;justify-content:space-between;color:var(--slate);">
                    <span>Total Iterasi: <strong><?= $dataSample['iterations'] ?> Kali</strong></span>
                    <span>Nilai SSE: <strong style="font-family:var(--mono);"><?= $dataSample['sse'] ?></strong></span>
                </div>
            </div>
        </div>

        <!-- Shortcut Kelola Management Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i data-lucide="sliders" style="width:18px;height:18px;color:var(--blue);"></i>
                    Menu Pintar Administrator
                </div>
            </div>
            <div class="card-body" style="display:grid;gap:12px;">
                <a href="<?= appUrl('admin/destinasi.php') ?>" style="display:flex;align-items:center;gap:14px;padding:14px 16px;background:var(--surface);border:1px solid var(--border);border-radius:12px;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='var(--emerald)'" onmouseout="this.style.borderColor='var(--border)'">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--emerald-soft);color:var(--emerald);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="database" style="width:18px;height:18px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:13.5px;font-weight:700;color:var(--slate);">Kelola Data Destinasi</div>
                        <div style="font-size:11.5px;color:var(--slate-light);">Tambah, edit, atau hapus data wisata dan kriteria.</div>
                    </div>
                    <i data-lucide="chevron-right" style="width:16px;height:16px;color:var(--slate-light);"></i>
                </a>

                <a href="<?= appUrl('admin/users.php') ?>" style="display:flex;align-items:center;gap:14px;padding:14px 16px;background:var(--surface);border:1px solid var(--border);border-radius:12px;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='var(--emerald)'" onmouseout="this.style.borderColor='var(--border)'">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(59,130,246,0.12);color:var(--blue);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="user-cog" style="width:18px;height:18px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:13.5px;font-weight:700;color:var(--slate);">Kelola Pengguna Sistem</div>
                        <div style="font-size:11.5px;color:var(--slate-light);">Kelola role (Admin / User), atur password &amp; status akun.</div>
                    </div>
                    <i data-lucide="chevron-right" style="width:16px;height:16px;color:var(--slate-light);"></i>
                </a>

                <a href="<?= appUrl('pages/peta.php') ?>" style="display:flex;align-items:center;gap:14px;padding:14px 16px;background:var(--surface);border:1px solid var(--border);border-radius:12px;text-decoration:none;transition:all .2s;" onmouseover="this.style.borderColor='var(--emerald)'" onmouseout="this.style.borderColor='var(--border)'">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(245,158,11,0.12);color:var(--gold);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="map" style="width:18px;height:18px;"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:13.5px;font-weight:700;color:var(--slate);">Pratinjau Peta GIS</div>
                        <div style="font-size:11.5px;color:var(--slate-light);">Lihat persebaran titik lokasi destinasi secara visual.</div>
                    </div>
                    <i data-lucide="chevron-right" style="width:16px;height:16px;color:var(--slate-light);"></i>
                </a>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
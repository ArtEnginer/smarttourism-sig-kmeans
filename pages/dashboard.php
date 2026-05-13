<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';
$km = runKMeans(3);
$data = $km['data'];
$clusterCounts = array_count_values(array_column($data, 'cluster'));
$totalWisata = count($data);
$rawData = getWisataData();
$totalPengunjung = array_sum(array_column($rawData, 'jumlah_pengunjung'));
$avgRating = round(array_sum(array_column($rawData, 'rating')) / $totalWisata, 2);
?>
<div class="page-content">

  <!-- Banner -->
  <div style="background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #0f172a 100%); border-radius: 18px; padding: 28px 32px; margin-bottom: 24px; position: relative; overflow: hidden; border: 1px solid rgba(16,185,129,0.2);">
    <div style="position:absolute;right:-30px;top:-30px;width:180px;height:180px;background:rgba(16,185,129,0.06);border-radius:50%;"></div>
    <div style="position:absolute;right:80px;bottom:-50px;width:130px;height:130px;background:rgba(16,185,129,0.04);border-radius:50%;"></div>
    <div style="font-size:10px;font-weight:700;color:rgba(255,255,255,0.4);letter-spacing:2px;text-transform:uppercase;margin-bottom:8px;">Kabupaten Magelang</div>
    <h1 style="font-family:'Instrument Serif',serif;font-size:26px;font-style:italic;color:white;margin-bottom:6px;position:relative;">Sistem Pemetaan Potensi Wisata</h1>
    <p style="font-size:12px;color:rgba(255,255,255,0.5);position:relative;">Berbasis Algoritma K-Means Clustering &nbsp;·&nbsp; 2 Iterasi &nbsp;·&nbsp; SSE = <?= $km['sse'] ?></p>
    <div style="display:flex;gap:10px;margin-top:20px;flex-wrap:wrap;">
      <a href="clustering.php" style="background:rgba(16,185,129,0.2);border:1px solid rgba(16,185,129,0.35);color:#34d399;padding:7px 16px;border-radius:9px;font-size:12px;font-weight:700;cursor:pointer;text-decoration:none;">Lihat Clustering →</a>
      <a href="peta.php" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.7);padding:7px 16px;border-radius:9px;font-size:12px;font-weight:700;cursor:pointer;text-decoration:none;">Buka Peta</a>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(16,185,129,0.12);"><i data-lucide="landmark" style="width:18px;height:18px;color:var(--emerald);"></i></div>
      <div class="stat-value" style="color:var(--emerald)"><?= $totalWisata ?></div>
      <div class="stat-label">Total Destinasi Wisata</div>
      <div class="stat-change neutral">Kabupaten Magelang</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(245,158,11,0.12);"><i data-lucide="users" style="width:18px;height:18px;color:var(--gold);"></i></div>
      <div class="stat-value" style="color:var(--gold);font-size:20px;"><?= number_format($totalPengunjung, 0, ',', '.') ?></div>
      <div class="stat-label">Total Pengunjung/Tahun</div>
      <div class="stat-change up">↑ Data 2024</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(139,92,246,0.12);"><i data-lucide="star" style="width:18px;height:18px;color:var(--purple);"></i></div>
      <div class="stat-value" style="color:var(--purple)"><?= $avgRating ?></div>
      <div class="stat-label">Rata-rata Rating</div>
      <div class="stat-change up">Skala 1–5</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(59,130,246,0.12);"><i data-lucide="refresh-cw" style="width:18px;height:18px;color:var(--blue);"></i></div>
      <div class="stat-value" style="color:var(--blue)"><?= $km['iterations'] ?></div>
      <div class="stat-label">Iterasi K-Means</div>
      <div class="stat-change neutral">Konvergen: <?= $km['converged'] ? 'Ya ✓' : 'Tidak' ?></div>
    </div>
  </div>

  <div class="grid-2">
    <!-- Cluster Distribution -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i data-lucide="pie-chart" style="width:15px;height:15px;color:var(--emerald);"></i> Distribusi Cluster</div>
        <a href="hasil.php" class="btn btn-secondary btn-sm">Detail</a>
      </div>
      <div class="card-body">
        <?php foreach ($km['labels'] as $ci => $label): ?>
          <?php $cnt = $clusterCounts[$ci] ?? 0;
          $pct = round($cnt / $totalWisata * 100); ?>
          <div style="margin-bottom:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
              <span style="font-size:13px;font-weight:700;"><?= $label ?></span>
              <span style="font-size:12px;color:var(--text-3);"><?= $cnt ?> destinasi &nbsp;·&nbsp; <?= $pct ?>%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width:<?= $pct ?>%;background:<?= $km['colors'][$ci] ?>;"></div>
            </div>
          </div>
        <?php endforeach; ?>
        <div style="margin-top:14px;padding:10px 14px;background:var(--surface-2);border:1px solid var(--border);border-radius:10px;font-size:11px;color:var(--text-3);display:flex;gap:16px;">
          <span>SSE: <strong style="color:var(--text);font-family:var(--mono);"><?= $km['sse'] ?></strong></span>
          <span>K = <strong style="color:var(--text);"><?= $km['k'] ?></strong></span>
          <span>Iterasi: <strong style="color:var(--text);"><?= $km['iterations'] ?></strong></span>
        </div>
      </div>
    </div>

    <!-- Top 5 -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i data-lucide="trending-up" style="width:15px;height:15px;color:var(--gold);"></i> Top 5 Destinasi</div>
        <a href="data_wisata.php" class="btn btn-secondary btn-sm">Semua</a>
      </div>
      <div class="card-body" style="padding:0;">
        <?php
        $sorted = $data;
        usort($sorted, fn($a, $b) => $b['jumlah_pengunjung'] - $a['jumlah_pengunjung']);
        $top5 = array_slice($sorted, 0, 5);
        $rankBg = ['rgba(16,185,129,0.15)', 'rgba(245,158,11,0.12)', 'rgba(139,92,246,0.12)', 'rgba(59,130,246,0.12)', 'rgba(239,68,68,0.12)'];
        $rankColor = ['var(--emerald)', 'var(--gold)', 'var(--purple)', 'var(--blue)', 'var(--red)'];
        foreach ($top5 as $i => $w):
          $clsClass = $w['cluster_icon'] === 'high' ? 'cluster-high' : ($w['cluster_icon'] === 'medium' ? 'cluster-medium' : 'cluster-low');
        ?>
          <div style="display:flex;align-items:center;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border);">
            <div style="width:26px;height:26px;border-radius:8px;background:<?= $rankBg[$i] ?>;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:<?= $rankColor[$i] ?>;flex-shrink:0;"><?= $i + 1 ?></div>
            <div style="flex:1;min-width:0;">
              <div style="font-size:12.5px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($w['nama']) ?></div>
              <div style="font-size:11px;color:var(--text-3);"><?= number_format($w['jumlah_pengunjung'], 0, ',', '.') ?> pengunjung</div>
            </div>
            <span class="badge <?= $clsClass ?>"><?= htmlspecialchars($w['cluster_label']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Jenis Wisata -->
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i data-lucide="layers" style="width:15px;height:15px;color:var(--blue);"></i> Distribusi Jenis Wisata</div>
    </div>
    <div class="card-body">
      <?php
      $jenisCounts = [];
      foreach ($rawData as $w) $jenisCounts[$w['jenis']] = ($jenisCounts[$w['jenis']] ?? 0) + 1;
      $jenisColors = ['Budaya' => 'var(--emerald)', 'Alam' => 'var(--blue)', 'Desa Wisata' => 'var(--gold)', 'Religi' => 'var(--purple)', 'Taman' => 'var(--red)'];
      $maxCount = max($jenisCounts);
      ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;">
        <?php foreach ($jenisCounts as $jenis => $cnt): ?>
          <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:12px;padding:16px;text-align:center;">
            <div style="font-size:30px;font-weight:800;color:<?= $jenisColors[$jenis] ?? 'var(--text-2)' ?>;margin-bottom:4px;"><?= $cnt ?></div>
            <div style="font-size:12px;color:var(--text-3);font-weight:600;margin-bottom:10px;"><?= $jenis ?></div>
            <div class="progress-bar">
              <div class="progress-fill" style="width:<?= round($cnt / $maxCount * 100) ?>%;background:<?= $jenisColors[$jenis] ?? 'var(--text-3)' ?>;"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>
<?php require_once '../includes/footer.php'; ?>
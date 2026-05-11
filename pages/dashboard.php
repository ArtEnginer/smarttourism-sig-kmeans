<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';
$km = runKMeans(3);
$data = $km['data'];
$clusterCounts = array_count_values(array_column($data, 'cluster'));
$totalWisata = count($data);
$totalPengunjung = array_sum(array_column(getWisataData(), 'jumlah_pengunjung'));
$avgRating = round(array_sum(array_column(getWisataData(), 'rating')) / $totalWisata, 2);
?>
<div class="page-content">

<!-- Welcome Banner -->
<div style="background: linear-gradient(135deg, #064e3b 0%, #047857 60%, #065f46 100%); border-radius: 20px; padding: 28px 32px; margin-bottom: 24px; position: relative; overflow: hidden;">
  <div style="position: absolute; right: -20px; top: -20px; width: 200px; height: 200px; background: rgba(255,255,255,0.04); border-radius: 50%;"></div>
  <div style="position: absolute; right: 60px; bottom: -40px; width: 150px; height: 150px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
  <div style="font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.5); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">Selamat Datang</div>
  <h1 style="font-family: 'DM Serif Display', serif; font-size: 28px; color: white; margin-bottom: 6px; position: relative;">Sistem Pemetaan Potensi Wisata</h1>
  <p style="font-size: 13px; color: rgba(255,255,255,0.6); position: relative;">Kabupaten Magelang · Berbasis Algoritma K-Means Clustering · TKT 4→5</p>
  <div style="display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap;">
    <a href="clustering.php" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 8px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; backdrop-filter: blur(8px); cursor: pointer;">🤖 Jalankan Clustering</a>
    <a href="peta.php" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); color: rgba(255,255,255,0.8); padding: 8px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer;">🗾 Lihat Peta</a>
  </div>
</div>

<!-- Stats -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background: #d1fae5;">🏖️</div>
    <div class="stat-value" style="color: var(--emerald)"><?= $totalWisata ?></div>
    <div class="stat-label">Total Destinasi Wisata</div>
    <div class="stat-change neutral">Kabupaten Magelang</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: #fef3c7;">👥</div>
    <div class="stat-value" style="color: var(--gold)"><?= number_format($totalPengunjung, 0, ',', '.') ?></div>
    <div class="stat-label">Total Pengunjung/Tahun</div>
    <div class="stat-change up">↑ Data 2024</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: #ede9fe;">⭐</div>
    <div class="stat-value" style="color: var(--purple)"><?= $avgRating ?></div>
    <div class="stat-label">Rata-rata Rating</div>
    <div class="stat-change up">↑ Skala 1-5</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: #dbeafe;">🔄</div>
    <div class="stat-value" style="color: #3b82f6"><?= $km['iterations'] ?></div>
    <div class="stat-label">Iterasi K-Means</div>
    <div class="stat-change neutral">Konvergen: <?= $km['converged'] ? 'Ya' : 'Tidak' ?></div>
  </div>
</div>

<div class="grid-2 mb-24">

<!-- Cluster Summary -->
<div class="card">
  <div class="card-header">
    <div class="card-title">🏆 Distribusi Cluster</div>
    <a href="hasil.php" class="btn btn-secondary btn-sm">Lihat Detail</a>
  </div>
  <div class="card-body">
    <?php foreach ($km['labels'] as $ci => $label): ?>
    <?php $cnt = $clusterCounts[$ci] ?? 0; $pct = round($cnt/$totalWisata*100); ?>
    <div style="margin-bottom: 18px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
        <span style="font-size: 13px; font-weight: 600;"><?= $label ?></span>
        <span style="font-size: 13px; color: var(--text-muted);"><?= $cnt ?> destinasi (<?= $pct ?>%)</span>
      </div>
      <div class="progress-bar">
        <div class="progress-fill" style="width: <?= $pct ?>%; background: <?= $km['colors'][$ci] ?>;"></div>
      </div>
    </div>
    <?php endforeach; ?>
    <div style="margin-top: 16px; padding: 12px; background: var(--slate-pale); border-radius: 10px; font-size: 12px; color: var(--text-muted);">
      SSE (Within-Cluster Error): <strong style="color: var(--slate)"><?= $km['sse'] ?></strong> &nbsp;·&nbsp; K = <?= $km['k'] ?>
    </div>
  </div>
</div>

<!-- Top Wisata -->
<div class="card">
  <div class="card-header">
    <div class="card-title">🏅 Top 5 Destinasi</div>
    <a href="data_wisata.php" class="btn btn-secondary btn-sm">Semua Data</a>
  </div>
  <div class="card-body" style="padding: 0;">
    <?php
    $sorted = $data;
    usort($sorted, fn($a,$b) => $b['jumlah_pengunjung'] - $a['jumlah_pengunjung']);
    $top5 = array_slice($sorted, 0, 5);
    foreach ($top5 as $i => $w):
    $clusterClass = $w['cluster_icon'] === 'high' ? 'cluster-high' : ($w['cluster_icon'] === 'medium' ? 'cluster-medium' : 'cluster-low');
    ?>
    <div style="display: flex; align-items: center; gap: 12px; padding: 14px 20px; border-bottom: 1px solid #f1f5f9;">
      <div style="width: 28px; height: 28px; border-radius: 8px; background: <?= ['#d1fae5','#fef3c7','#ede9fe','#dbeafe','#fce7f3'][$i] ?>; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; color: <?= ['#065f46','#78350f','#4c1d95','#1d4ed8','#831843'][$i] ?>; flex-shrink: 0;"><?= $i+1 ?></div>
      <div style="flex: 1; min-width: 0;">
        <div style="font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($w['nama']) ?></div>
        <div style="font-size: 11px; color: var(--text-muted);"><?= number_format($w['jumlah_pengunjung'],0,',','.') ?> pengunjung</div>
      </div>
      <span class="badge <?= $clusterClass ?>"><?= explode(' ', $w['cluster_label'])[1] ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</div>

</div>

<!-- Jenis Wisata Chart -->
<div class="card mb-24">
  <div class="card-header">
    <div class="card-title">📊 Distribusi Jenis Wisata</div>
  </div>
  <div class="card-body">
    <?php
    $jenisCounts = [];
    foreach (getWisataData() as $w) {
        $jenisCounts[$w['jenis']] = ($jenisCounts[$w['jenis']] ?? 0) + 1;
    }
    $jenisColors = ['Budaya'=>'#059669','Alam'=>'#3b82f6','Desa Wisata'=>'#d97706','Religi'=>'#6366f1','Taman'=>'#ec4899'];
    $maxCount = max($jenisCounts);
    ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px;">
    <?php foreach ($jenisCounts as $jenis => $cnt): ?>
    <div style="background: var(--slate-pale); border-radius: 12px; padding: 16px; text-align: center; border: 1px solid var(--border);">
      <div style="font-size: 28px; font-weight: 800; color: <?= $jenisColors[$jenis] ?? '#64748b' ?>;"><?= $cnt ?></div>
      <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;"><?= $jenis ?></div>
      <div class="progress-bar" style="margin-top: 10px;">
        <div class="progress-fill" style="width: <?= round($cnt/$maxCount*100) ?>%; background: <?= $jenisColors[$jenis] ?? '#64748b' ?>;"></div>
      </div>
    </div>
    <?php endforeach; ?>
    </div>
  </div>
</div>

</div>
<?php require_once '../includes/footer.php'; ?>

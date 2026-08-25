<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';
$km = runKMeans(3);
$data = $km['data'];
$clusterCounts = array_count_values(array_column($data, 'cluster'));
$totalWisata = count($data);
$rawData = getWisataData();
$totalPengunjung = array_sum(array_column($rawData, 'jumlah_pengunjung'));
$avgRating = $totalWisata > 0 ? round(array_sum(array_column($rawData, 'rating')) / $totalWisata, 2) : 0;
?>
<div class="page-content">

  <!-- Visual Hero Banner -->
  <div class="card mb-24" style="background:linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);border:1px solid var(--emerald-border);overflow:hidden;position:relative;">
    <div class="card-body" style="padding:28px 32px;">
      <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:center;">
        <div>
          <div style="display:inline-flex;align-items:center;gap:6px;background:var(--emerald-soft);border:1px solid var(--emerald-border);padding:4px 12px;border-radius:99px;font-size:11px;font-weight:700;color:var(--emerald-dark);text-transform:uppercase;margin-bottom:12px;">
            <span style="width:7px;height:7px;border-radius:50%;background:var(--emerald);display:inline-block;"></span> Portal Pariwisata Digital
          </div>
          <h1 style="font-size:24px;font-weight:800;color:var(--slate);line-height:1.2;margin-bottom:8px;">
            Sistem Informasi Geografis &amp; Cluster Wisata Magelang
          </h1>
          <p style="font-size:13px;color:var(--slate-light);line-height:1.6;margin-bottom:20px;max-width:540px;">
            Ringkasan data destinasi Kabupaten Magelang, pemetaan spasial lokasi, serta hasil analisis klastering potensi wilayah pariwisata.
          </p>
          <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="<?= appUrl('pages/peta.php') ?>" class="btn btn-primary btn-sm" style="box-shadow:0 4px 14px rgba(5,150,105,0.25);">
              <i data-lucide="map" style="width:15px;height:15px;"></i> Buka Peta GIS Interaktif
            </a>
            <a href="<?= appUrl('pages/clustering.php') ?>" class="btn btn-secondary btn-sm">
              <i data-lucide="pie-chart" style="width:15px;height:15px;"></i> Analisis Clustering
            </a>
          </div>
        </div>
        <div style="border-radius:16px;overflow:hidden;border:1px solid var(--emerald-border);box-shadow:0 4px 18px rgba(5,150,105,0.12);height:180px;background:#fff;">
          <img src="<?= appUrl('assets/hero_map.png') ?>" alt="Map Visual Graphic" style="width:100%;height:100%;object-fit:cover;">
        </div>
      </div>
    </div>
  </div>

  <!-- Visual Stats Grid -->
  <div class="stats-grid mb-24">
    <div class="stat-card" style="border-top:3px solid var(--emerald);">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;">
        <div>
          <div class="stat-value" style="color:var(--emerald);font-size:28px;"><?= $totalWisata ?></div>
          <div class="stat-label">Total Destinasi Wisata</div>
        </div>
        <div class="stat-icon" style="background:var(--emerald-soft);"><i data-lucide="landmark" style="width:20px;height:20px;color:var(--emerald);"></i></div>
      </div>
      <div class="stat-change neutral" style="margin-top:10px;">Tersebar di Kab. Magelang</div>
    </div>

    <div class="stat-card" style="border-top:3px solid var(--gold);">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;">
        <div>
          <div class="stat-value" style="color:var(--gold);font-size:24px;"><?= number_format($totalPengunjung, 0, ',', '.') ?></div>
          <div class="stat-label">Total Pengunjung/Tahun</div>
        </div>
        <div class="stat-icon" style="background:rgba(245,158,11,0.12);"><i data-lucide="users" style="width:20px;height:20px;color:var(--gold);"></i></div>
      </div>
      <div class="stat-change up" style="margin-top:10px;">Data Rekapitulasi 2024</div>
    </div>

    <div class="stat-card" style="border-top:3px solid var(--purple);">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;">
        <div>
          <div class="stat-value" style="color:var(--purple);font-size:28px;"><?= $avgRating ?></div>
          <div class="stat-label">Rata-rata Rating Wisata</div>
        </div>
        <div class="stat-icon" style="background:rgba(139,92,246,0.12);"><i data-lucide="star" style="width:20px;height:20px;color:var(--purple);"></i></div>
      </div>
      <div class="stat-change up" style="margin-top:10px;">Skala Kepuasan 1.0 &ndash; 5.0</div>
    </div>

    <div class="stat-card" style="border-top:3px solid var(--blue);">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;">
        <div>
          <div class="stat-value" style="color:var(--blue);font-size:28px;"><?= $km['k'] ?> Klaster</div>
          <div class="stat-label">Pengelompokan K-Means</div>
        </div>
        <div class="stat-icon" style="background:rgba(59,130,246,0.12);"><i data-lucide="layers" style="width:20px;height:20px;color:var(--blue);"></i></div>
      </div>
      <div class="stat-change neutral" style="margin-top:10px;"><?= $km['iterations'] ?> Iterasi (Konvergen)</div>
    </div>
  </div>

  <div class="grid-2 mb-24">
    <!-- Visual Cluster Distribution Card -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <i data-lucide="pie-chart" style="width:18px;height:18px;color:var(--emerald);"></i>
          Distribusi Klaster Potensi
        </div>
        <a href="<?= appUrl('pages/hasil.php') ?>" class="btn btn-secondary btn-sm">Lihat Detail</a>
      </div>
      <div class="card-body">
        <?php foreach ($km['labels'] as $ci => $label): ?>
          <?php $cnt = $clusterCounts[$ci] ?? 0;
          $pct = round($cnt / $totalWisata * 100); ?>
          <div style="margin-bottom:18px;background:var(--slate-pale);padding:14px;border-radius:12px;border:1px solid var(--border);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
              <span style="font-size:13px;font-weight:700;color:var(--slate);display:flex;align-items:center;gap:6px;">
                <span style="width:10px;height:10px;border-radius:50%;background:<?= $km['colors'][$ci] ?>;display:inline-block;"></span>
                <?= $label ?>
              </span>
              <span class="badge" style="background:#fff;border:1px solid var(--border);color:var(--slate);font-weight:700;">
                <?= $cnt ?> Destinasi (<?= $pct ?>%)
              </span>
            </div>
            <div class="progress-bar" style="height:10px;border-radius:99px;background:#e2e8f0;">
              <div class="progress-fill" style="width:<?= $pct ?>%;background:<?= $km['colors'][$ci] ?>;border-radius:99px;"></div>
            </div>
          </div>
        <?php endforeach; ?>
        <div style="padding:12px 16px;background:var(--emerald-soft);border:1px solid var(--emerald-border);border-radius:12px;font-size:12px;color:var(--emerald-dark);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
          <span>Nilai Sum of Squared Errors (SSE): <strong style="font-family:var(--mono);"><?= $km['sse'] ?></strong></span>
          <span>Status: <strong style="color:var(--emerald);"><i data-lucide="check-circle-2" style="width:13px;height:13px;vertical-align:-2px;"></i> Optimal</strong></span>
        </div>
      </div>
    </div>

    <!-- Top 5 Destinasi -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">
          <i data-lucide="trending-up" style="width:18px;height:18px;color:var(--gold);"></i>
          Top 5 Destinasi Terpopuler
        </div>
        <a href="<?= appUrl('pages/data_wisata.php') ?>" class="btn btn-secondary btn-sm">Lihat Semua</a>
      </div>
      <div class="card-body" style="padding:0;">
        <?php
        $sorted = $data;
        usort($sorted, fn($a, $b) => $b['jumlah_pengunjung'] - $a['jumlah_pengunjung']);
        $top5 = array_slice($sorted, 0, 5);
        $rankBg = ['var(--emerald-soft)', 'rgba(245,158,11,0.12)', 'rgba(139,92,246,0.12)', 'rgba(59,130,246,0.12)', 'rgba(239,68,68,0.12)'];
        $rankColor = ['var(--emerald)', 'var(--gold)', 'var(--purple)', 'var(--blue)', 'var(--red)'];
        foreach ($top5 as $i => $w):
          $clsClass = $w['cluster_icon'] === 'high' ? 'badge-green' : ($w['cluster_icon'] === 'medium' ? 'badge-blue' : 'badge-red');
        ?>
          <div style="display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid var(--border);">
            <div style="width:30px;height:30px;border-radius:10px;background:<?= $rankBg[$i] ?>;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:<?= $rankColor[$i] ?>;flex-shrink:0;border:1px solid var(--border);">
              <?= $i + 1 ?>
            </div>
            <div style="flex:1;min-width:0;">
              <div style="font-size:13px;font-weight:700;color:var(--slate);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($w['nama']) ?></div>
              <div style="font-size:11.5px;color:var(--slate-light);display:flex;align-items:center;gap:8px;margin-top:2px;">
                <span><i data-lucide="users" style="width:12px;height:12px;vertical-align:-1px;color:var(--emerald);"></i> <?= number_format($w['jumlah_pengunjung'], 0, ',', '.') ?> pengunjung</span>
                <span>&bull;</span>
                <span><i data-lucide="star" style="width:12px;height:12px;vertical-align:-1px;color:var(--gold);"></i> <?= $w['rating'] ?></span>
              </div>
            </div>
            <span class="badge <?= $clsClass ?>"><?= htmlspecialchars($w['cluster_label']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Distribusi Jenis Wisata Visual -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i data-lucide="compass" style="width:18px;height:18px;color:var(--emerald);"></i>
        Kategori &amp; Jenis Destinasi Wisata
      </div>
    </div>
    <div class="card-body">
      <?php
      $jenisCounts = [];
      foreach ($rawData as $w) $jenisCounts[$w['jenis']] = ($jenisCounts[$w['jenis']] ?? 0) + 1;
      $jenisColors = ['Budaya' => 'var(--emerald)', 'Alam' => 'var(--blue)', 'Desa Wisata' => 'var(--gold)', 'Religi' => 'var(--purple)', 'Taman' => 'var(--red)'];
      $maxCount = $jenisCounts ? max($jenisCounts) : 0;
      ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;">
        <?php foreach ($jenisCounts as $jenis => $cnt): ?>
          <div style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px;text-align:center;box-shadow:var(--shadow-sm);position:relative;overflow:hidden;">
            <div style="position:absolute;top:0;left:0;right:0;height:4px;background:<?= $jenisColors[$jenis] ?? 'var(--emerald)' ?>;"></div>
            <div style="font-size:32px;font-weight:800;color:<?= $jenisColors[$jenis] ?? 'var(--slate)' ?>;margin-bottom:2px;"><?= $cnt ?></div>
            <div style="font-size:12.5px;color:var(--slate);font-weight:700;margin-bottom:10px;"><?= $jenis ?></div>
            <div class="progress-bar" style="height:6px;border-radius:99px;background:var(--slate-pale);">
              <div class="progress-fill" style="width:<?= $maxCount > 0 ? round($cnt / $maxCount * 100) : 0 ?>%;background:<?= $jenisColors[$jenis] ?? 'var(--emerald)' ?>;border-radius:99px;"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>
<?php require_once '../includes/footer.php'; ?>
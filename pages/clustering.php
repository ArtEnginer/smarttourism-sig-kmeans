<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';

$k = isset($_GET['k']) ? max(2, min(5, (int)$_GET['k'])) : 3;
$km = runKMeans($k);
$fields = $km['fields'];
$fieldsLabel = $km['fields_label'];
$rawData = getWisataData();
$normMeta = $km['norm_meta'];
?>
<div class="page-content">

<!-- Config Panel -->
<div class="card mb-24">
  <div class="card-header">
    <div class="card-title">⚙️ Konfigurasi Algoritma K-Means Clustering</div>
  </div>
  <div class="card-body">
    <form method="GET" style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
      <div>
        <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 6px;">Jumlah Cluster (K)</label>
        <div style="display: flex; gap: 8px;">
          <?php for ($ki=2; $ki<=5; $ki++): ?>
          <button type="submit" name="k" value="<?= $ki ?>" class="btn <?= $k===$ki ? 'btn-primary' : 'btn-secondary' ?>"><?= $ki ?></button>
          <?php endfor; ?>
        </div>
      </div>
      <div style="background: var(--slate-pale); border: 1px solid var(--border); border-radius: 12px; padding: 12px 20px;">
        <div style="font-size: 11px; color: var(--text-muted);">Kriteria Clustering</div>
        <div style="font-size: 13px; font-weight: 600; margin-top: 3px;"><?= implode(', ', array_values($fieldsLabel)) ?></div>
      </div>
    </form>
  </div>
</div>

<!-- Result Summary -->
<div class="stats-grid mb-24">
  <div class="stat-card">
    <div class="stat-icon" style="background: #d1fae5;">🎯</div>
    <div class="stat-value" style="color: var(--emerald)"><?= $km['k'] ?></div>
    <div class="stat-label">Jumlah Cluster (K)</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: #dbeafe;">🔄</div>
    <div class="stat-value" style="color: #3b82f6"><?= $km['iterations'] ?></div>
    <div class="stat-label">Total Iterasi</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: #fef3c7;">📐</div>
    <div class="stat-value" style="color: var(--gold)"><?= $km['sse'] ?></div>
    <div class="stat-label">SSE (Sum of Squared Error)</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background: #ede9fe;">✅</div>
    <div class="stat-value" style="color: var(--purple); font-size: 18px;"><?= $km['converged'] ? 'Konvergen' : 'Max Iterasi' ?></div>
    <div class="stat-label">Status Algoritma</div>
  </div>
</div>

<!-- STEP 1: Normalisasi Data -->
<div class="card mb-24">
  <div class="card-header">
    <div class="card-title">
      <span style="width: 28px; height: 28px; background: var(--emerald); color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800;">1</span>
      Normalisasi Data (Min-Max Normalization)
    </div>
  </div>
  <div class="card-body">
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 18px; margin-bottom: 16px; font-size: 13px;">
      <strong>Formula:</strong> x' = (x - x_min) / (x_max - x_min)
      <br><span style="color: var(--text-muted); margin-top: 4px; display: block;">Normalisasi mengubah semua nilai kriteria ke rentang [0, 1] agar tidak ada kriteria yang mendominasi perhitungan jarak.</span>
    </div>
    <!-- Min-Max Info -->
    <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px;">
      <?php foreach ($fields as $f): ?>
      <div style="background: white; border: 1px solid var(--border); border-radius: 10px; padding: 10px 14px; min-width: 120px;">
        <div style="font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;"><?= $fieldsLabel[$f] ?></div>
        <div style="font-size: 12px; margin-top: 4px;"><span style="color:var(--emerald);">Min: <?= $normMeta['min'][$f] ?></span> &nbsp;·&nbsp; <span style="color:var(--danger);">Max: <?= $normMeta['max'][$f] ?></span></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Destinasi</th>
            <?php foreach ($fields as $f): ?>
            <th><?= $fieldsLabel[$f] ?> (asli)</th>
            <th><?= $fieldsLabel[$f] ?> (norm)</th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($km['data'] as $w): ?>
          <tr>
            <td style="color:var(--text-muted);"><?= $w['id'] ?></td>
            <td style="font-weight:600;"><?= htmlspecialchars($w['nama']) ?></td>
            <?php foreach ($fields as $f): ?>
            <td><?= $f === 'jumlah_pengunjung' ? number_format($w[$f],0,',','.') : $w[$f] ?></td>
            <td style="font-family: monospace; color: var(--emerald-dark); font-weight: 600;"><?= round($w[$f.'_norm'], 4) ?></td>
            <?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- STEP 2: Inisialisasi Centroid -->
<div class="card mb-24">
  <div class="card-header">
    <div class="card-title">
      <span style="width: 28px; height: 28px; background: #3b82f6; color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800;">2</span>
      Inisialisasi Centroid Awal
    </div>
  </div>
  <div class="card-body">
    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 14px 18px; margin-bottom: 16px; font-size: 13px;">
      <strong>Metode:</strong> Pemilihan titik awal tersebar (Uniform Sampling) — memilih setiap ke-<?= round(count($km['data'])/$k) ?> data sebagai centroid awal untuk K=<?= $k ?>.
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
      <?php if (!empty($km['history'])): ?>
      <?php $initCentroids = $km['history'][0]['centroids']; ?>
      <?php foreach ($initCentroids as $ci => $c): ?>
      <div style="background: white; border: 2px solid <?= $km['colors'][$ci] ?? '#64748b' ?>; border-radius: 14px; padding: 16px;">
        <div style="font-size: 12px; font-weight: 700; color: <?= $km['colors'][$ci] ?? '#64748b' ?>; margin-bottom: 10px;">Centroid <?= $ci+1 ?> (C<?= $ci+1 ?>)</div>
        <?php foreach ($fields as $f): ?>
        <div style="display: flex; justify-content: space-between; font-size: 12px; padding: 3px 0; border-bottom: 1px solid #f1f5f9;">
          <span style="color: var(--text-muted);"><?= $fieldsLabel[$f] ?></span>
          <span style="font-family: monospace; font-weight: 600;"><?= round($c[$f], 4) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- STEP 3: Proses Iterasi - Tabel Jarak & Penugasan -->
<div class="card mb-24">
  <div class="card-header">
    <div class="card-title">
      <span style="width: 28px; height: 28px; background: var(--gold); color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800;">3</span>
      Perhitungan Jarak Euclidean & Penugasan Cluster — Iterasi 1
    </div>
    <a href="iterasi.php?k=<?= $k ?>" class="btn btn-secondary btn-sm">Lihat Semua Iterasi</a>
  </div>
  <div class="card-body">
    <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 14px 18px; margin-bottom: 16px; font-size: 13px;">
      <strong>Formula Euclidean:</strong> d(x, c) = √[ Σ (x<sub>i</sub> - c<sub>i</sub>)² ]
      <br><span style="color: var(--text-muted);">Setiap data dihitung jaraknya ke semua centroid. Data dimasukkan ke cluster dengan jarak terpendek.</span>
    </div>
    <?php if (!empty($km['history'])): ?>
    <?php $iter1 = $km['history'][0]; ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Destinasi</th>
            <?php for ($ci=0; $ci<$k; $ci++): ?>
            <th>d(x, C<?= $ci+1 ?>)</th>
            <?php endfor; ?>
            <th>Cluster Terpilih</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($km['data'] as $i => $w): ?>
          <?php $dm = $iter1['distance_matrix'][$i]; ?>
          <tr>
            <td style="color:var(--text-muted);"><?= $w['id'] ?></td>
            <td style="font-weight:600;"><?= htmlspecialchars($w['nama']) ?></td>
            <?php foreach ($dm['distances'] as $di => $dist): ?>
            <td style="font-family: monospace; font-size: 12px; <?= $di === $dm['assigned'] ? 'font-weight: 800; color: var(--emerald); background: #f0fdf4;' : 'color: var(--text-muted);' ?>"><?= $dist ?></td>
            <?php endforeach; ?>
            <td>
              <?php $clsIcon = $km['icons'][$w['cluster']] ?? 'low'; $clsClass = $clsIcon === 'high' ? 'cluster-high' : ($clsIcon === 'medium' ? 'cluster-medium' : 'cluster-low'); ?>
              <span class="badge <?= $clsClass ?>">C<?= $w['cluster']+1 ?> · <?= explode(' ',trim($km['labels'][$w['cluster']]))[1] ?></span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- STEP 4: Final Centroids -->
<div class="card mb-24">
  <div class="card-header">
    <div class="card-title">
      <span style="width: 28px; height: 28px; background: var(--purple); color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800;">4</span>
      Centroid Akhir (Setelah Konvergen — Iterasi <?= $km['iterations'] ?>)
    </div>
  </div>
  <div class="card-body">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px;">
      <?php foreach ($km['centroids'] as $ci => $c): ?>
      <div style="background: white; border: 2px solid <?= $km['colors'][$ci] ?? '#64748b' ?>; border-radius: 14px; padding: 16px; position: relative; overflow: hidden;">
        <div style="position: absolute; right: -10px; top: -10px; width: 60px; height: 60px; background: <?= $km['colors'][$ci] ?>22; border-radius: 50%;"></div>
        <div style="font-weight: 800; color: <?= $km['colors'][$ci] ?>; margin-bottom: 4px; font-size: 13px;"><?= $km['labels'][$ci] ?></div>
        <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 12px;">Centroid C<?= $ci+1 ?></div>
        <?php foreach ($fields as $f): ?>
        <div style="display: flex; justify-content: space-between; font-size: 12px; padding: 4px 0; border-bottom: 1px solid #f1f5f9;">
          <span style="color: var(--text-muted);"><?= $fieldsLabel[$f] ?></span>
          <span style="font-family: monospace; font-weight: 700;"><?= round($c[$f], 4) ?></span>
        </div>
        <?php endforeach; ?>
        <div style="margin-top: 10px; font-size: 11px; color: var(--text-muted);">
          Anggota: <strong><?= count(array_filter($km['data'], fn($w) => $w['cluster'] === $ci)) ?> destinasi</strong>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- SSE Explanation -->
    <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
      <div style="font-size: 13px; font-weight: 700; margin-bottom: 8px;">📐 Evaluasi: Sum of Squared Error (SSE)</div>
      <div style="font-size: 13px; font-family: monospace; background: var(--slate); color: #34d399; padding: 10px 14px; border-radius: 8px; margin-bottom: 8px;">
        SSE = Σ Σ ||x<sub>i</sub> - c<sub>k</sub>||² = <?= $km['sse'] ?>
      </div>
      <div style="font-size: 12px; color: var(--text-muted);">SSE mengukur seberapa kompak data dalam setiap cluster. Nilai yang lebih kecil berarti clustering lebih baik. Algoritma berhenti setelah <?= $km['iterations'] ?> iterasi karena centroid tidak berubah (konvergen).</div>
    </div>
  </div>
</div>

<div style="text-align: center; padding: 10px 0;">
  <a href="iterasi.php?k=<?= $k ?>" class="btn btn-primary">🔄 Lihat Detail Setiap Iterasi</a>
  &nbsp;
  <a href="hasil.php?k=<?= $k ?>" class="btn btn-secondary">📍 Lihat Hasil Akhir Clustering</a>
</div>

</div>
<?php require_once '../includes/footer.php'; ?>

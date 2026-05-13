<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';

$k = isset($_GET['k']) ? max(2, min(5, (int)$_GET['k'])) : 3;
$km = runKMeans($k);
$fields = $km['fields'];
$fieldsLabel = $km['fields_label'];
$normMeta = $km['norm_meta'];
?>
<div class="page-content">

  <!-- Config -->
  <div class="card mb-24">
    <div class="card-header">
      <div class="card-title"><i data-lucide="settings" style="width:15px;height:15px;color:var(--emerald);"></i> Konfigurasi K-Means Clustering</div>
    </div>
    <div class="card-body" style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
      <div>
        <div style="font-size:10px;font-weight:700;color:var(--text-3);letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;">Jumlah Cluster (K)</div>
        <div style="display:flex;gap:6px;">
          <?php for ($ki = 2; $ki <= 5; $ki++): ?>
            <a href="?k=<?= $ki ?>" class="btn <?= $k === $ki ? 'btn-primary' : 'btn-secondary' ?>"><?= $ki ?></a>
          <?php endfor; ?>
        </div>
      </div>
      <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:10px;padding:10px 16px;">
        <div style="font-size:10px;color:var(--text-3);font-weight:600;margin-bottom:3px;">Fitur / Kriteria</div>
        <div style="font-size:12px;font-weight:600;color:var(--text);"><?= implode(', ', array_values($fieldsLabel)) ?></div>
      </div>
    </div>
  </div>

  <!-- Summary Stats -->
  <div class="stats-grid mb-24">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(16,185,129,0.12);"><i data-lucide="target" style="width:20px;height:20px;color:var(--emerald);"></i></div>
      <div class="stat-value" style="color:var(--emerald)"><?= $km['k'] ?></div>
      <div class="stat-label">Jumlah Cluster (K)</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(59,130,246,0.12);"><i data-lucide="refresh-cw" style="width:20px;height:20px;color:var(--blue);"></i></div>
      <div class="stat-value" style="color:var(--blue)"><?= $km['iterations'] ?></div>
      <div class="stat-label">Total Iterasi</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(245,158,11,0.12);"><i data-lucide="sigma" style="width:20px;height:20px;color:var(--gold);"></i></div>
      <div class="stat-value mono" style="color:var(--gold);font-size:18px;"><?= $km['sse'] ?></div>
      <div class="stat-label">SSE (Sum of Squared Error)</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(139,92,246,0.12);"><i data-lucide="check-circle-2" style="width:20px;height:20px;color:var(--purple);"></i></div>
      <div class="stat-value" style="color:var(--purple);font-size:16px;"><?= $km['converged'] ? 'Konvergen' : 'Max Iterasi' ?></div>
      <div class="stat-label">Status Algoritma</div>
    </div>
  </div>

  <!-- STEP 1: Normalisasi -->
  <div class="card mb-24">
    <div class="card-header">
      <div class="card-title">
        <span class="step-badge" style="background:var(--emerald);">1</span>
        Normalisasi Data (Min-Max)
      </div>
    </div>
    <div class="card-body">
      <div class="formula-box">
        <div class="formula-title">Formula Normalisasi</div>
        x' = (x − x_min) / (x_max − x_min) &nbsp;&nbsp;→&nbsp;&nbsp; rentang [0, 1]
      </div>
      <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
        <?php foreach ($fields as $f): ?>
          <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:9px;padding:8px 12px;min-width:110px;">
            <div style="font-size:10px;color:var(--text-3);font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;"><?= $fieldsLabel[$f] ?></div>
            <div style="font-size:11px;"><span style="color:var(--emerald);">min = <?= $normMeta['min'][$f] ?></span> &nbsp;·&nbsp; <span style="color:var(--red);">max = <?= $normMeta['max'][$f] ?></span></div>
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
                <td style="color:var(--text-3);font-weight:700;"><?= $w['id'] ?></td>
                <td style="font-weight:700;color:var(--text);"><?= htmlspecialchars($w['nama']) ?></td>
                <?php foreach ($fields as $f): ?>
                  <td><?= $f === 'jumlah_pengunjung' ? number_format($w[$f], 0, ',', '.') : $w[$f] ?></td>
                  <td class="mono" style="color:var(--emerald-l);font-weight:600;"><?= round($w[$f . '_norm'], 4) ?></td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- STEP 2: Centroid Awal -->
  <div class="card mb-24">
    <div class="card-header">
      <div class="card-title">
        <span class="step-badge" style="background:var(--blue);">2</span>
        Inisialisasi Centroid Awal
      </div>
    </div>
    <div class="card-body">
      <div class="info-box blue">
        <strong>Metode:</strong> Uniform Sampling — memilih setiap ke-<?= $km['init_step'] ?> data sebagai centroid awal untuk K=<?= $k ?>.
        <?php if ($k === 3): ?>
          &nbsp; C1 = baris 1 (Candi Borobudur), C2 = baris 6 (Air Terjun Kedung Kayang), C3 = baris 11 (Taman Kyai Langgeng).
        <?php endif; ?>
      </div>
      <?php if (!empty($km['history'])): $initCentroids = $km['history'][0]['centroids']; ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;">
          <?php foreach ($initCentroids as $ci => $c): ?>
            <div style="background:var(--surface-2);border:2px solid <?= $km['colors'][$ci] ?>33;border-top:3px solid <?= $km['colors'][$ci] ?>;border-radius:12px;padding:14px;">
              <div style="font-size:11px;font-weight:800;color:<?= $km['colors'][$ci] ?>;margin-bottom:10px;text-transform:uppercase;letter-spacing:0.5px;">Centroid C<?= $ci + 1 ?></div>
              <?php foreach ($fields as $f): ?>
                <div style="display:flex;justify-content:space-between;font-size:11px;padding:3px 0;border-bottom:1px solid var(--border);">
                  <span style="color:var(--text-3);"><?= $fieldsLabel[$f] ?></span>
                  <span class="mono" style="font-weight:600;color:var(--text);"><?= round($c[$f], 4) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- STEP 3: Iterasi 1 -->
  <div class="card mb-24">
    <div class="card-header">
      <div class="card-title">
        <span class="step-badge" style="background:var(--gold);">3</span>
        Jarak Euclidean & Penugasan — Iterasi 1
      </div>
      <a href="iterasi.php?k=<?= $k ?>" class="btn btn-secondary btn-sm">Semua Iterasi →</a>
    </div>
    <div class="card-body">
      <div class="formula-box">
        <div class="formula-title">Formula Euclidean Distance</div>
        d(x, c) = √[ Σ (xᵢ − cᵢ)² ] &nbsp;&nbsp;·&nbsp;&nbsp; Data → cluster dengan jarak terkecil (ditandai ✓)
      </div>
      <?php if (!empty($km['history'])): $iter1 = $km['history'][0]; ?>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>No</th>
                <th>Destinasi</th>
                <?php for ($ci = 0; $ci < $k; $ci++): ?>
                  <th style="color:<?= $km['colors'][$ci] ?>;">d(x, C<?= $ci + 1 ?>)</th>
                <?php endfor; ?>
                <th>Cluster</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($km['data'] as $i => $w): $dm = $iter1['distance_matrix'][$i]; ?>
                <tr>
                  <td style="color:var(--text-3);font-weight:700;"><?= $w['id'] ?></td>
                  <td style="font-weight:700;color:var(--text);"><?= htmlspecialchars($w['nama']) ?></td>
                  <?php foreach ($dm['distances'] as $di => $dist): $isMin = ($di === $dm['assigned']); ?>
                    <td class="mono" style="font-size:12px;font-weight:<?= $isMin ? '800' : '400' ?>;color:<?= $isMin ? 'var(--emerald)' : 'var(--text-3)' ?>;background:<?= $isMin ? 'rgba(16,185,129,0.07)' : 'transparent' ?>;">
                      <?= $dist ?><?= $isMin ? ' ✓' : '' ?>
                    </td>
                  <?php endforeach; ?>
                  <td>
                    <?php $ci2 = $dm['assigned'];
                    $ic = $km['icons'][$ci2] ?? 'low';
                    $cc = $ic === 'high' ? 'cluster-high' : ($ic === 'medium' ? 'cluster-medium' : 'cluster-low'); ?>
                    <span class="badge <?= $cc ?>">C<?= $ci2 + 1 ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- STEP 4: Centroid Akhir -->
  <div class="card mb-24">
    <div class="card-header">
      <div class="card-title">
        <span class="step-badge" style="background:var(--purple);">4</span>
        Centroid Akhir (Iterasi <?= $km['iterations'] ?> — Konvergen)
      </div>
    </div>
    <div class="card-body">
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:14px;margin-bottom:20px;">
        <?php foreach ($km['centroids'] as $ci => $c): ?>
          <div style="background:var(--surface-2);border:1px solid var(--border);border-left:4px solid <?= $km['colors'][$ci] ?>;border-radius:12px;padding:16px;position:relative;overflow:hidden;">
            <div style="position:absolute;right:-10px;top:-10px;width:60px;height:60px;background:<?= $km['colors'][$ci] ?>18;border-radius:50%;"></div>
            <div style="font-weight:800;color:<?= $km['colors'][$ci] ?>;margin-bottom:4px;font-size:12px;"><?= $km['labels'][$ci] ?></div>
            <div style="font-size:10px;color:var(--text-3);margin-bottom:12px;text-transform:uppercase;letter-spacing:0.5px;">Centroid C<?= $ci + 1 ?></div>
            <?php foreach ($fields as $f): ?>
              <div style="display:flex;justify-content:space-between;font-size:11px;padding:3px 0;border-bottom:1px solid var(--border);">
                <span style="color:var(--text-3);"><?= $fieldsLabel[$f] ?></span>
                <span class="mono" style="font-weight:700;color:var(--text);"><?= round($c[$f], 6) ?></span>
              </div>
            <?php endforeach; ?>
            <div style="margin-top:10px;font-size:11px;color:var(--text-3);">
              Anggota: <strong style="color:var(--text);"><?= count(array_filter($km['data'], fn($w) => $w['cluster'] === $ci)) ?> destinasi</strong>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:12px;padding:16px;">
        <div style="font-size:12px;font-weight:700;margin-bottom:10px;color:var(--text);display:flex;align-items:center;gap:6px;"><i data-lucide="sigma" style="width:14px;height:14px;color:var(--gold);"></i> Evaluasi: Sum of Squared Error (SSE)</div>
        <div class="formula-box" style="margin-bottom:10px;">SSE = Σ Σ ‖xᵢ − cₖ‖² = <?= $km['sse'] ?></div>
        <div style="font-size:12px;color:var(--text-3);">Algoritma berhenti setelah <strong style="color:var(--text);"><?= $km['iterations'] ?> iterasi</strong> karena centroid tidak berubah (konvergen). Nilai SSE lebih kecil = clustering lebih kompak.</div>
      </div>
    </div>
  </div>

  <div style="display:flex;gap:12px;justify-content:center;padding:8px 0;">
    <a href="iterasi.php?k=<?= $k ?>" class="btn btn-primary"><i data-lucide="refresh-cw" style="width:14px;height:14px;"></i> Detail Setiap Iterasi</a>
    <a href="hasil.php?k=<?= $k ?>" class="btn btn-secondary"><i data-lucide="map-pin" style="width:14px;height:14px;"></i> Hasil Akhir Clustering</a>
  </div>

</div>
<?php require_once '../includes/footer.php'; ?>
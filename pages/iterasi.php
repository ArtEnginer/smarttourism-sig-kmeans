<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';

$k = isset($_GET['k']) ? max(2, min(5, (int)$_GET['k'])) : 3;
$km = runKMeans($k);
$showIter = isset($_GET['iter']) ? (int)$_GET['iter'] : 1;
$totalIter = $km['iterations'];
$showIter = max(1, min($showIter, $totalIter));
$iterData = $km['history'][$showIter - 1];
$fields = $km['fields'];
$fieldsLabel = $km['fields_label'];
?>
<div class="page-content">

  <?php if ($totalIter === 0): ?>
    <div class="card">
      <div class="card-body">
        <div class="info-box blue">Belum ada data destinasi di database, sehingga iterasi K-Means tidak dapat ditampilkan.</div>
      </div>
    </div>
  <?php else: ?>

    <!-- Navigator -->
    <div class="card mb-24">
      <div class="card-header">
        <div class="card-title"><i data-lucide="refresh-cw" style="width:15px;height:15px;color:var(--blue);"></i> Detail Iterasi K-Means (K=<?= $k ?>)</div>
        <a href="clustering.php?k=<?= $k ?>" class="btn btn-secondary btn-sm">← Kembali</a>
      </div>
      <div class="card-body">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:14px;">
          <?php if ($showIter > 1): ?>
            <a href="?k=<?= $k ?>&iter=<?= $showIter - 1 ?>" class="btn btn-secondary btn-sm">← Sebelumnya</a>
          <?php endif; ?>
          <div style="display:flex;gap:6px;flex-wrap:wrap;">
            <?php for ($it = 1; $it <= $totalIter; $it++): ?>
              <a href="?k=<?= $k ?>&iter=<?= $it ?>" class="btn <?= $it === $showIter ? 'btn-primary' : 'btn-secondary' ?> btn-sm">
                Iterasi <?= $it ?><?= $it === $totalIter ? ' ✓' : '' ?>
              </a>
            <?php endfor; ?>
          </div>
          <?php if ($showIter < $totalIter): ?>
            <a href="?k=<?= $k ?>&iter=<?= $showIter + 1 ?>" class="btn btn-secondary btn-sm">Berikutnya →</a>
          <?php endif; ?>
        </div>
        <div class="info-box <?= $showIter === $totalIter ? 'green' : 'blue' ?>">
          <?php if ($showIter === $totalIter): ?>
            ✅ <strong>Iterasi <?= $showIter ?> (Terakhir)</strong> — Centroid tidak berubah dari iterasi sebelumnya → <strong>KONVERGEN</strong>
          <?php else: ?>
            🔄 <strong>Iterasi <?= $showIter ?> dari <?= $totalIter ?></strong> — Memperbarui penugasan dan menghitung centroid baru...
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Centroid posisi iterasi ini -->
    <div class="card mb-24">
      <div class="card-header">
        <div class="card-title"><i data-lucide="crosshair" style="width:15px;height:15px;color:var(--purple);"></i> Posisi Centroid — Iterasi <?= $showIter ?></div>
      </div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;">
          <?php foreach ($iterData['centroids'] as $ci => $c): ?>
            <div style="background:var(--surface-2);border:1px solid var(--border);border-left:4px solid <?= $km['colors'][$ci] ?>;border-radius:12px;padding:12px;">
              <div style="font-size:10px;font-weight:800;color:<?= $km['colors'][$ci] ?>;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.5px;">Centroid C<?= $ci + 1 ?></div>
              <?php foreach ($fields as $f): ?>
                <div style="display:flex;justify-content:space-between;font-size:11px;padding:2px 0;">
                  <span style="color:var(--text-3);"><?= $fieldsLabel[$f] ?></span>
                  <span class="mono" style="font-weight:600;color:var(--text);"><?= round($c[$f], 6) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Matriks Jarak -->
    <div class="card mb-24">
      <div class="card-header">
        <div class="card-title"><i data-lucide="ruler" style="width:15px;height:15px;color:var(--gold);"></i> Matriks Jarak Euclidean & Penugasan — Iterasi <?= $showIter ?></div>
      </div>
      <div class="card-body">
        <div class="formula-box">
          d(x, c) = √[ (x₁−c₁)² + (x₂−c₂)² + (x₃−c₃)² + (x₄−c₄)² + (x₅−c₅)² ] &nbsp;·&nbsp; Jarak terkecil → ditugaskan (✓)
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Destinasi</th>
                <?php for ($ci = 0; $ci < $k; $ci++): ?>
                  <th style="color:<?= $km['colors'][$ci] ?>;">d(x, C<?= $ci + 1 ?>)</th>
                <?php endfor; ?>
                <th>Cluster</th>
                <th>Jarak Min</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($km['data'] as $i => $w): $dm = $iterData['distance_matrix'][$i]; ?>
                <tr>
                  <td style="color:var(--text-3);font-weight:700;"><?= $w['id'] ?></td>
                  <td style="font-weight:700;color:var(--text);font-size:12px;"><?= htmlspecialchars($w['nama']) ?></td>
                  <?php foreach ($dm['distances'] as $di => $dist): $isMin = ($di === $dm['assigned']); ?>
                    <td class="mono" style="font-size:12px;font-weight:<?= $isMin ? '800' : '400' ?>;color:<?= $isMin ? 'var(--emerald)' : 'var(--text-3)' ?>;background:<?= $isMin ? 'rgba(16,185,129,0.07)' : 'transparent' ?>;">
                      <?= $dist ?><?= $isMin ? ' ✓' : '' ?>
                    </td>
                  <?php endforeach; ?>
                  <td>
                    <?php $ca = $dm['assigned'];
                    $ic = $km['icons'][$ca] ?? 'low';
                    $cc = $ic === 'high' ? 'cluster-high' : ($ic === 'medium' ? 'cluster-medium' : 'cluster-low'); ?>
                    <span class="badge <?= $cc ?>">C<?= $ca + 1 ?></span>
                  </td>
                  <td class="mono" style="font-weight:700;color:var(--emerald);font-size:12px;"><?= $dm['min_dist'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Ringkasan cluster iterasi ini -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i data-lucide="users" style="width:15px;height:15px;color:var(--emerald);"></i> Anggota Per Cluster — Iterasi <?= $showIter ?></div>
      </div>
      <div class="card-body">
        <?php
        $assigns = $iterData['assignments'];
        $clusterMembers = [];
        foreach ($km['data'] as $i => $w) {
          $c = $assigns[$i];
          $clusterMembers[$c][] = $w['nama'];
        }
        ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px;">
          <?php for ($ci = 0; $ci < $k; $ci++): $cnt = count($clusterMembers[$ci] ?? []); ?>
            <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:12px;overflow:hidden;">
              <div style="background:<?= $km['colors'][$ci] ?>;padding:10px 14px;display:flex;justify-content:space-between;align-items:center;">
                <div>
                  <div style="font-size:13px;font-weight:800;color:white;"><?= $km['labels'][$ci] ?></div>
                  <div style="font-size:10px;color:rgba(255,255,255,0.7);"><?= $cnt ?> destinasi</div>
                </div>
                <div style="background:rgba(255,255,255,0.2);border-radius:8px;padding:4px 10px;font-size:20px;font-weight:900;color:white;"><?= $cnt ?></div>
              </div>
              <div style="padding:10px 14px;">
                <?php foreach ($clusterMembers[$ci] ?? [] as $name): ?>
                  <div style="display:flex;align-items:center;gap:8px;padding:5px 0;font-size:12px;border-bottom:1px solid var(--border);color:var(--text-2);">
                    <span style="color:<?= $km['colors'][$ci] ?>;font-size:8px;">●</span>
                    <?= htmlspecialchars($name) ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>

</div>
<?php require_once '../includes/footer.php'; ?>

<?php endif; ?>
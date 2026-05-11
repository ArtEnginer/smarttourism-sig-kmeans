<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';

$k = isset($_GET['k']) ? max(2, min(5, (int)$_GET['k'])) : 3;
$showIter = isset($_GET['iter']) ? (int)$_GET['iter'] : 1;
$km = runKMeans($k);
$totalIter = $km['iterations'];
$showIter = max(1, min($showIter, $totalIter));
$iterData = $km['history'][$showIter - 1];
$fieldsLabel = $km['fields_label'];
$fields = $km['fields'];
?>
<div class="page-content">

<!-- Iteration Navigator -->
<div class="card mb-24">
  <div class="card-header">
    <div class="card-title">🔄 Detail Iterasi K-Means (K=<?= $k ?>)</div>
    <a href="clustering.php?k=<?= $k ?>" class="btn btn-secondary btn-sm">← Kembali</a>
  </div>
  <div class="card-body">
    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 16px;">
      <?php if ($showIter > 1): ?>
      <a href="?k=<?= $k ?>&iter=<?= $showIter-1 ?>" class="btn btn-secondary btn-sm">← Sebelumnya</a>
      <?php endif; ?>
      
      <div style="display: flex; gap: 6px; flex-wrap: wrap;">
        <?php for ($it=1; $it<=$totalIter; $it++): ?>
        <a href="?k=<?= $k ?>&iter=<?= $it ?>" class="btn <?= $it===$showIter ? 'btn-primary' : 'btn-secondary' ?> btn-sm">
          Iterasi <?= $it ?><?= $it===$totalIter ? ' ✓' : '' ?>
        </a>
        <?php endfor; ?>
      </div>

      <?php if ($showIter < $totalIter): ?>
      <a href="?k=<?= $k ?>&iter=<?= $showIter+1 ?>" class="btn btn-secondary btn-sm">Berikutnya →</a>
      <?php endif; ?>
    </div>
    <div style="background: <?= $showIter===$totalIter ? '#f0fdf4' : '#eff6ff' ?>; border: 1px solid <?= $showIter===$totalIter ? '#bbf7d0' : '#bfdbfe' ?>; border-radius: 10px; padding: 12px 16px; font-size: 13px;">
      <?php if ($showIter === $totalIter): ?>
      ✅ <strong>Iterasi <?= $showIter ?> (Terakhir)</strong> — Algoritma konvergen! Centroid tidak berubah dari iterasi sebelumnya.
      <?php else: ?>
      🔄 <strong>Iterasi <?= $showIter ?> dari <?= $totalIter ?></strong> — Sedang memperbarui penugasan dan centroid...
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Centroids at this iteration -->
<div class="card mb-24">
  <div class="card-header">
    <div class="card-title">📌 Posisi Centroid — Iterasi <?= $showIter ?></div>
  </div>
  <div class="card-body">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
      <?php foreach ($iterData['centroids'] as $ci => $c): ?>
      <div style="background: white; border: 2px solid <?= $km['colors'][$ci] ?? '#64748b' ?>20; border-left: 4px solid <?= $km['colors'][$ci] ?? '#64748b' ?>; border-radius: 12px; padding: 14px;">
        <div style="font-size: 11px; font-weight: 700; color: <?= $km['colors'][$ci] ?>; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Centroid C<?= $ci+1 ?></div>
        <?php foreach ($fields as $f): ?>
        <div style="display: flex; justify-content: space-between; font-size: 11px; padding: 2px 0;">
          <span style="color:var(--text-muted);"><?= $fieldsLabel[$f] ?></span>
          <span style="font-family: monospace; font-weight: 600; color: var(--slate);"><?= round($c[$f], 4) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Distance Matrix & Assignment -->
<div class="card mb-24">
  <div class="card-header">
    <div class="card-title">📏 Matriks Jarak & Penugasan Cluster — Iterasi <?= $showIter ?></div>
  </div>
  <div class="card-body">
    <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 12px;">
      <strong>Formula:</strong> d(x, c) = √[ (x₁-c₁)² + (x₂-c₂)² + ... + (xₙ-cₙ)² ] &nbsp;|&nbsp; Data ditetapkan ke cluster dengan jarak <strong>terkecil</strong> (ditandai hijau).
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="min-width: 40px;">No</th>
            <th style="min-width: 180px;">Nama Destinasi</th>
            <?php for ($ci=0; $ci<$k; $ci++): ?>
            <th style="background: <?= $km['colors'][$ci] ?>18; color: <?= $km['colors'][$ci] ?>; min-width: 100px;">
              d(x, C<?= $ci+1 ?>)
            </th>
            <?php endfor; ?>
            <th>Cluster</th>
            <th>Jarak Min</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($km['data'] as $i => $w): ?>
          <?php $dm = $iterData['distance_matrix'][$i]; ?>
          <tr>
            <td style="color:var(--text-muted); font-weight:600;"><?= $w['id'] ?></td>
            <td style="font-weight: 600; font-size: 12px;"><?= htmlspecialchars($w['nama']) ?></td>
            <?php foreach ($dm['distances'] as $di => $dist): ?>
            <?php $isMin = ($di === $dm['assigned']); ?>
            <td style="font-family: monospace; font-size: 12px; font-weight: <?= $isMin ? '800' : '400' ?>; color: <?= $isMin ? '#059669' : 'var(--text-muted)' ?>; background: <?= $isMin ? '#f0fdf4' : 'transparent' ?>; border-radius: <?= $isMin ? '6px' : '0' ?>; position: relative;">
              <?= $dist ?>
              <?php if ($isMin): ?><span style="font-size:9px;"> ✓</span><?php endif; ?>
            </td>
            <?php endforeach; ?>
            <td>
              <?php 
              $clAssigned = $dm['assigned'];
              $clsIcon = $km['icons'][$clAssigned] ?? 'low';
              $clsClass = $clsIcon === 'high' ? 'cluster-high' : ($clsIcon === 'medium' ? 'cluster-medium' : 'cluster-low');
              ?>
              <span class="badge <?= $clsClass ?>">C<?= $clAssigned+1 ?></span>
            </td>
            <td style="font-family: monospace; font-size: 12px; font-weight: 700; color: var(--emerald);"><?= $dm['min_dist'] ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Cluster summary for this iteration -->
<div class="card">
  <div class="card-header">
    <div class="card-title">📊 Ringkasan Pengelompokan — Iterasi <?= $showIter ?></div>
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
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
      <?php for ($ci=0; $ci<$k; $ci++): ?>
      <div style="background: white; border: 1px solid var(--border); border-radius: 14px; overflow: hidden;">
        <div style="background: <?= $km['colors'][$ci] ?>; padding: 12px 16px; color: white;">
          <div style="font-weight: 800; font-size: 14px;">Cluster <?= $ci+1 ?></div>
          <div style="font-size: 11px; opacity: 0.8;"><?= count($clusterMembers[$ci] ?? []) ?> destinasi wisata</div>
        </div>
        <div style="padding: 12px 16px;">
          <?php foreach ($clusterMembers[$ci] ?? [] as $name): ?>
          <div style="display: flex; align-items: center; gap: 8px; padding: 4px 0; font-size: 12px; border-bottom: 1px solid #f8fafc;">
            <span style="color: <?= $km['colors'][$ci] ?>;">●</span>
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

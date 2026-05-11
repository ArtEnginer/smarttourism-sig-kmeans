<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';

$k = isset($_GET['k']) ? max(2, min(5, (int)$_GET['k'])) : 3;
$km = runKMeans($k);
$data = $km['data'];
?>
<div class="page-content">

<!-- Summary Cards -->
<div class="grid-3 mb-24">
  <?php foreach ($km['labels'] as $ci => $label): ?>
  <?php
  $members = array_filter($data, fn($w) => $w['cluster'] === $ci);
  $cnt = count($members);
  $avgPengunjung = $cnt > 0 ? array_sum(array_column(array_values($members), 'jumlah_pengunjung')) / $cnt : 0;
  $avgRating = $cnt > 0 ? array_sum(array_column(array_values($members), 'rating')) / $cnt : 0;
  $clsClass = $km['icons'][$ci] === 'high' ? 'cluster-high' : ($km['icons'][$ci] === 'medium' ? 'cluster-medium' : 'cluster-low');
  ?>
  <div class="card">
    <div style="height: 6px; background: <?= $km['colors'][$ci] ?>;"></div>
    <div class="card-body">
      <div style="font-size: 11px; font-weight: 700; color: <?= $km['colors'][$ci] ?>; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Cluster <?= $ci+1 ?></div>
      <div style="font-size: 20px; font-weight: 800; margin-bottom: 4px; color: var(--slate);"><?= $label ?></div>
      <div style="font-size: 28px; font-weight: 800; color: <?= $km['colors'][$ci] ?>; margin-bottom: 12px;"><?= $cnt ?> <span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">destinasi</span></div>
      <div style="display: flex; gap: 16px; font-size: 12px; color: var(--text-muted);">
        <div>👥 <?= number_format($avgPengunjung, 0, ',', '.') ?>/thn avg</div>
        <div>⭐ <?= round($avgRating, 2) ?> avg rating</div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Final Result Table -->
<div class="card mb-24">
  <div class="card-header">
    <div class="card-title">📋 Data Hasil Clustering (K=<?= $k ?>)</div>
    <div style="display: flex; gap: 8px;">
      <?php for ($ki=2; $ki<=5; $ki++): ?>
      <a href="?k=<?= $ki ?>" class="btn <?= $k===$ki ? 'btn-primary' : 'btn-secondary' ?> btn-sm"><?= $ki ?> Cluster</a>
      <?php endfor; ?>
    </div>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Destinasi Wisata</th>
          <th>Jenis</th>
          <th>Daya Tarik</th>
          <th>Aksesibilitas</th>
          <th>Fasilitas</th>
          <th>Sarana</th>
          <th>Ulasan</th>
          <th>Pengunjung</th>
          <th>Rating</th>
          <th>Cluster</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $w): ?>
        <?php
        $clsClass = $w['cluster_icon'] === 'high' ? 'cluster-high' : ($w['cluster_icon'] === 'medium' ? 'cluster-medium' : 'cluster-low');
        $jenisColor = ['Budaya'=>'badge-green','Alam'=>'badge-blue','Desa Wisata'=>'badge-yellow','Religi'=>'badge-purple','Taman'=>'badge-red'][$w['jenis']] ?? 'badge-blue';
        ?>
        <tr>
          <td style="color:var(--text-muted); font-weight:600;"><?= $w['id'] ?></td>
          <td style="font-weight: 700;"><?= htmlspecialchars($w['nama']) ?></td>
          <td><span class="badge <?= $jenisColor ?>"><?= $w['jenis'] ?></span></td>
          <td><span style="font-weight:700; color: var(--emerald);"><?= $w['daya_tarik'] ?></span></td>
          <td><?= $w['aksesibilitas'] ?></td>
          <td><?= $w['fasilitas'] ?></td>
          <td><?= $w['sarana'] ?></td>
          <td><?= $w['ulasan'] ?></td>
          <td style="white-space: nowrap;"><?= number_format($w['jumlah_pengunjung'],0,',','.') ?></td>
          <td><span style="color:#d97706; font-weight:700;">⭐ <?= $w['rating'] ?></span></td>
          <td>
            <span class="badge <?= $clsClass ?>" style="white-space: nowrap;">C<?= $w['cluster']+1 ?></span>
          </td>
          <td>
            <span style="font-size: 11px; color: <?= $km['colors'][$w['cluster']] ?>; font-weight: 600;"><?= $w['cluster_label'] ?></span>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Per-Cluster Detailed Cards -->
<div style="margin-bottom: 8px; font-size: 15px; font-weight: 700; color: var(--slate);">📌 Detail Anggota Per Cluster</div>
<?php foreach ($km['labels'] as $ci => $label): ?>
<?php $members = array_values(array_filter($data, fn($w) => $w['cluster'] === $ci)); ?>
<div class="card mb-24">
  <div style="background: linear-gradient(135deg, <?= $km['colors'][$ci] ?>, <?= $km['colors'][$ci] ?>cc); padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;">
    <div>
      <div style="font-size: 18px; font-weight: 800; color: white;"><?= $label ?></div>
      <div style="font-size: 12px; color: rgba(255,255,255,0.7);"><?= count($members) ?> destinasi wisata · Centroid C<?= $ci+1 ?></div>
    </div>
    <div style="background: rgba(255,255,255,0.15); border-radius: 12px; padding: 8px 16px; font-size: 24px; font-weight: 900; color: white;"><?= count($members) ?></div>
  </div>
  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1px; background: var(--border);">
    <?php foreach ($members as $w): ?>
    <div style="background: white; padding: 16px 20px;">
      <div style="font-weight: 700; font-size: 14px; margin-bottom: 6px;"><?= htmlspecialchars($w['nama']) ?></div>
      <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 10px;"><?= $w['jenis'] ?></div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
        <?php
        $attrs = [
          ['🎯', 'Daya Tarik', $w['daya_tarik'].'/10'],
          ['🚗', 'Aksesibilitas', $w['aksesibilitas'].'/10'],
          ['🏪', 'Fasilitas', $w['fasilitas'].'/10'],
          ['⭐', 'Rating', $w['rating'].'/5'],
          ['👥', 'Pengunjung', number_format($w['jumlah_pengunjung']/1000,0).'K'],
        ];
        foreach ($attrs as $a):
        ?>
        <div style="background: var(--slate-pale); border-radius: 8px; padding: 6px 8px; font-size: 11px;">
          <span><?= $a[0] ?></span> <span style="color:var(--text-muted);"><?= $a[1] ?></span>
          <div style="font-weight: 700; color: var(--slate);"><?= $a[2] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endforeach; ?>

</div>
<?php require_once '../includes/footer.php'; ?>

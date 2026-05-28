<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';

$k = isset($_GET['k']) ? max(2, min(5, (int)$_GET['k'])) : 3;
$km = runKMeans($k);
$data = $km['data'];
?>
<div class="page-content">

  <?php if (empty($data)): ?>
    <div class="card">
      <div class="card-body">
        <div class="info-box blue">Belum ada data destinasi di database, sehingga hasil clustering belum tersedia.</div>
      </div>
    </div>
  <?php else: ?>

    <!-- Summary Cards -->
    <div class="grid-3 mb-24">
      <?php foreach ($km['labels'] as $ci => $label):
        $members = array_filter($data, fn($w) => $w['cluster'] === $ci);
        $cnt = count($members);
        $avgPengunjung = $cnt > 0 ? array_sum(array_column(array_values($members), 'jumlah_pengunjung')) / $cnt : 0;
        $avgRating = $cnt > 0 ? array_sum(array_column(array_values($members), 'rating')) / $cnt : 0;
      ?>
        <div class="card" style="overflow:hidden;">
          <div style="height:4px;background:<?= $km['colors'][$ci] ?>;"></div>
          <div class="card-body">
            <div style="font-size:10px;font-weight:800;color:<?= $km['colors'][$ci] ?>;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Cluster <?= $ci + 1 ?></div>
            <div style="font-size:17px;font-weight:800;margin-bottom:6px;color:var(--text);"><?= $label ?></div>
            <div style="font-size:32px;font-weight:800;color:<?= $km['colors'][$ci] ?>;margin-bottom:12px;"><?= $cnt ?> <span style="font-size:13px;font-weight:500;color:var(--text-3);">destinasi</span></div>
            <div style="display:flex;gap:12px;font-size:11px;color:var(--text-3);">
              <div><i data-lucide="users" style="width:12px;height:12px;vertical-align:-2px;"></i> <?= number_format($avgPengunjung, 0, ',', '.') ?>/thn avg</div>
              <div><i data-lucide="star" style="width:12px;height:12px;vertical-align:-2px;"></i> <?= round($avgRating, 2) ?> avg</div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- K Selector + Result Table -->
    <div class="card mb-24">
      <div class="card-header">
        <div class="card-title"><i data-lucide="table" style="width:15px;height:15px;color:var(--emerald);"></i> Hasil Akhir Clustering (K=<?= $k ?>)</div>
        <div style="display:flex;gap:6px;">
          <?php for ($ki = 2; $ki <= 5; $ki++): ?>
            <a href="?k=<?= $ki ?>" class="btn <?= $k === $ki ? 'btn-primary' : 'btn-secondary' ?> btn-sm"><?= $ki ?> Cluster</a>
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
              <th>Pengunjung/Thn</th>
              <th>Rating</th>
              <th>Cluster</th>
              <th>Label</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $w):
              $ic = $w['cluster_icon'];
              $cc = $ic === 'high' ? 'cluster-high' : ($ic === 'medium' ? 'cluster-medium' : 'cluster-low');
              $jc = ['Budaya' => 'badge-green', 'Alam' => 'badge-blue', 'Desa Wisata' => 'badge-yellow', 'Religi' => 'badge-purple', 'Taman' => 'badge-red'][$w['jenis']] ?? 'badge-blue';
            ?>
              <tr>
                <td style="color:var(--text-3);font-weight:700;"><?= $w['id'] ?></td>
                <td style="font-weight:700;color:var(--text);"><?= htmlspecialchars($w['nama']) ?></td>
                <td><span class="badge <?= $jc ?>"><?= $w['jenis'] ?></span></td>
                <td style="color:var(--emerald);font-weight:700;"><?= $w['daya_tarik'] ?></td>
                <td><?= $w['aksesibilitas'] ?></td>
                <td><?= $w['fasilitas'] ?></td>
                <td><?= $w['sarana'] ?></td>
                <td><?= $w['ulasan'] ?></td>
                <td class="mono" style="font-size:12px;"><?= number_format($w['jumlah_pengunjung'], 0, ',', '.') ?></td>
                <td style="color:var(--gold);font-weight:700;"><i data-lucide="star" style="width:12px;height:12px;vertical-align:-2px;"></i> <?= $w['rating'] ?></td>
                <td><span class="badge <?= $cc ?>">C<?= $w['cluster'] + 1 ?></span></td>
                <td style="font-size:11px;color:<?= $km['colors'][$w['cluster']] ?>;font-weight:600;"><?= $w['cluster_label'] ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Detail per cluster -->
    <div style="font-size:14px;font-weight:800;color:var(--text);margin-bottom:16px;display:flex;align-items:center;gap:6px;"><i data-lucide="badge-info" style="width:14px;height:14px;color:var(--emerald);"></i> Detail Anggota Per Cluster</div>
    <?php foreach ($km['labels'] as $ci => $label):
      $members = array_values(array_filter($data, fn($w) => $w['cluster'] === $ci));
    ?>
      <div class="card mb-20">
        <div style="background:<?= $km['colors'][$ci] ?>;padding:14px 20px;display:flex;align-items:center;justify-content:space-between;">
          <div>
            <div style="font-size:16px;font-weight:800;color:white;"><?= $label ?></div>
            <div style="font-size:11px;color:rgba(255,255,255,0.65);"><?= count($members) ?> destinasi wisata &nbsp;·&nbsp; Centroid C<?= $ci + 1 ?></div>
          </div>
          <div style="background:rgba(255,255,255,0.18);border-radius:10px;padding:6px 14px;font-size:28px;font-weight:900;color:white;"><?= count($members) ?></div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:1px;background:var(--border);">
          <?php foreach ($members as $w): ?>
            <div style="background:var(--surface);padding:14px 18px;">
              <div style="font-weight:700;font-size:13px;color:var(--text);margin-bottom:4px;"><?= htmlspecialchars($w['nama']) ?></div>
              <div style="font-size:11px;color:var(--text-3);margin-bottom:10px;"><?= $w['jenis'] ?></div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                <?php
                $attrs = [
                  ['target', 'Daya Tarik', $w['daya_tarik'] . '/10'],
                  ['car-front', 'Aksesibilitas', $w['aksesibilitas'] . '/10'],
                  ['store', 'Fasilitas', $w['fasilitas'] . '/10'],
                  ['star', 'Rating', $w['rating'] . '/5'],
                  ['users', 'Pengunjung', number_format($w['jumlah_pengunjung'] / 1000, 0) . 'K/thn'],
                ];
                foreach ($attrs as $a):
                ?>
                  <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:8px;padding:6px 8px;font-size:11px;">
                    <span style="color:var(--text-2);display:flex;align-items:center;gap:4px;"><i data-lucide="<?= $a[0] ?>" style="width:11px;height:11px;"></i> <?= $a[1] ?></span>
                    <div style="font-weight:700;color:var(--text);margin-top:1px;"><?= $a[2] ?></div>
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

<?php endif; ?>
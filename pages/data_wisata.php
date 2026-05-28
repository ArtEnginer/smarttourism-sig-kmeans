<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';
$allWisata = getWisataData();
$data = $allWisata;
$jenisFilter = $_GET['jenis'] ?? '';
if ($jenisFilter) $data = array_filter($data, fn($w) => $w['jenis'] === $jenisFilter);
$jenisOptions = array_unique(array_column($allWisata, 'jenis'));
$totalData = count($allWisata);
?>
<div class="page-content">

  <div class="page-hero">
    <div>
      <div class="page-hero-title">Data Wisata Kabupaten Magelang</div>
      <div class="page-hero-subtitle">Daftar destinasi yang dipakai sebagai sumber analisis. Data disaring langsung dari basis data dan siap diproses untuk clustering.</div>
    </div>
    <div class="page-hero-actions">
      <select onchange="location.href='?jenis='+this.value" style="background:white;border:1px solid var(--border);border-radius:10px;padding:8px 12px;font-size:12px;font-family:inherit;color:var(--text-muted);cursor:pointer;">
        <option value="">Semua Jenis</option>
        <?php foreach ($jenisOptions as $j): ?>
          <option value="<?= $j ?>" <?= $jenisFilter === $j ? 'selected' : '' ?>><?= $j ?></option>
        <?php endforeach; ?>
      </select>
      <a href="clustering.php" class="btn btn-primary btn-sm"><i data-lucide="bot" style="width:14px;height:14px;"></i> Jalankan Clustering</a>
      <?php if (function_exists('isAdmin') && isAdmin()): ?>
        <a href="admin/destinasi.php" class="btn btn-secondary btn-sm"><i data-lucide="plus-circle" style="width:14px;height:14px;"></i> Kelola Data</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="card mb-24">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Destinasi</th>
            <th>Jenis</th>
            <th>Daya Tarik</th>
            <th>Aksesibilitas</th>
            <th>Fasilitas</th>
            <th>Sarana</th>
            <th>Ulasan</th>
            <th>Pengunjung/Thn</th>
            <th>Rating</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data)): ?>
            <?php foreach (array_values($data) as $w):
              $jc = ['Budaya' => 'badge-green', 'Alam' => 'badge-blue', 'Desa Wisata' => 'badge-yellow', 'Religi' => 'badge-purple', 'Taman' => 'badge-red'][$w['jenis']] ?? 'badge-blue';
            ?>
              <tr>
                <td style="color:var(--text-3);font-weight:700;"><?= $w['id'] ?></td>
                <td style="font-weight:700;color:var(--text);"><?= htmlspecialchars($w['nama']) ?></td>
                <td><span class="badge <?= $jc ?>"><?= $w['jenis'] ?></span></td>
                <?php foreach (['daya_tarik', 'aksesibilitas', 'fasilitas'] as $f): ?>
                  <td>
                    <div style="display:flex;align-items:center;gap:6px;">
                      <span style="font-weight:700;color:var(--text);"><?= $w[$f] ?></span>
                      <div style="flex:1;min-width:40px;" class="progress-bar">
                        <div class="progress-fill" style="width:<?= $w[$f] * 10 ?>%;background:var(--<?= $f === 'daya_tarik' ? 'emerald' : ($f === 'aksesibilitas' ? 'blue' : 'gold') ?>);"></div>
                      </div>
                    </div>
                  </td>
                <?php endforeach; ?>
                <td style="font-weight:700;color:var(--text);"><?= $w['sarana'] ?></td>
                <td style="font-weight:700;color:var(--text);"><?= $w['ulasan'] ?></td>
                <td class="mono" style="font-size:12px;"><?= number_format($w['jumlah_pengunjung'], 0, ',', '.') ?></td>
                <td style="color:var(--gold);font-weight:700;"><i data-lucide="star" style="width:12px;height:12px;vertical-align:-2px;"></i> <?= $w['rating'] ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="10" style="padding:24px;text-align:center;color:var(--text-3);">Belum ada data destinasi di database.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div style="padding:12px 20px;background:var(--slate-pale);font-size:11px;color:var(--text-muted);border-top:1px solid var(--border);">
      Menampilkan <?= count($data) ?> dari <?= $totalData ?> destinasi wisata
    </div>
  </div>

  <!-- Keterangan kriteria -->
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i data-lucide="info" style="width:15px;height:15px;color:var(--blue);"></i> Keterangan Kriteria</div>
    </div>
    <div class="card-body">
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;">
        <?php
        $kriteria = [
          ['target', 'Daya Tarik', 'Keunikan, keindahan, dan daya tarik destinasi. Skala 1–10.', 'var(--emerald)'],
          ['car-front', 'Aksesibilitas', 'Kemudahan jangkauan & infrastruktur jalan. Skala 1–10.', 'var(--blue)'],
          ['store', 'Fasilitas', 'Ketersediaan toilet, parkir, warung, dll. Skala 1–10.', 'var(--gold)'],
          ['shield-check', 'Sarana', 'Sarana & prasarana pendukung wisata. Skala 1–10.', 'var(--purple)'],
          ['message-square', 'Ulasan', 'Rata-rata ulasan pengunjung. Skala 1–5.', 'var(--red)'],
        ];
        foreach ($kriteria as $k2):
        ?>
          <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:12px;padding:14px;">
            <div style="width:28px;height:28px;border-radius:8px;background:<?= $k2[3] ?>18;color:<?= $k2[3] ?>;display:flex;align-items:center;justify-content:center;margin-bottom:8px;"><i data-lucide="<?= $k2[0] ?>" style="width:14px;height:14px;"></i></div>
            <div style="font-size:13px;font-weight:700;color:<?= $k2[3] ?>;margin-bottom:4px;"><?= $k2[1] ?></div>
            <div style="font-size:12px;color:var(--text-3);"><?= $k2[2] ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>
<?php require_once '../includes/footer.php'; ?>
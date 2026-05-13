<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';
$data = getWisataData();
$jenisFilter = $_GET['jenis'] ?? '';
if ($jenisFilter) $data = array_filter($data, fn($w) => $w['jenis'] === $jenisFilter);
$jenisOptions = array_unique(array_column(getWisataData(), 'jenis'));
?>
<div class="page-content">

  <div class="card mb-24">
    <div class="card-header">
      <div class="card-title"><i data-lucide="database" style="width:15px;height:15px;color:var(--emerald);"></i> Data Wisata Kabupaten Magelang</div>
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <select onchange="location.href='?jenis='+this.value" style="background:var(--surface-2);border:1px solid var(--border);border-radius:8px;padding:6px 12px;font-size:12px;font-family:inherit;color:var(--text-2);cursor:pointer;">
          <option value="">Semua Jenis</option>
          <?php foreach ($jenisOptions as $j): ?>
            <option value="<?= $j ?>" <?= $jenisFilter === $j ? 'selected' : '' ?>><?= $j ?></option>
          <?php endforeach; ?>
        </select>
        <a href="clustering.php" class="btn btn-primary btn-sm"><i data-lucide="bot" style="width:14px;height:14px;"></i> Jalankan Clustering</a>
      </div>
    </div>
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
        </tbody>
      </table>
    </div>
    <div style="padding:12px 20px;background:var(--surface-2);font-size:11px;color:var(--text-3);border-top:1px solid var(--border);">
      Menampilkan <?= count($data) ?> dari <?= count(getWisataData()) ?> destinasi wisata
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
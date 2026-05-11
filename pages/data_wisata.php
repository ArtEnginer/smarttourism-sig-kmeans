<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';
$data = getWisataData();
$jenisFilter = $_GET['jenis'] ?? '';
if ($jenisFilter) {
    $data = array_filter($data, fn($w) => $w['jenis'] === $jenisFilter);
}
$jenisOptions = array_unique(array_column(getWisataData(), 'jenis'));
?>
<div class="page-content">

<div class="card mb-24">
  <div class="card-header">
    <div class="card-title">🗺️ Daftar Data Wisata Kabupaten Magelang</div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
      <select onchange="location.href='?jenis='+this.value" style="border: 1px solid var(--border); border-radius: 8px; padding: 6px 12px; font-size: 13px; font-family: inherit; background: white; cursor: pointer;">
        <option value="">Semua Jenis</option>
        <?php foreach ($jenisOptions as $j): ?>
        <option value="<?= $j ?>" <?= $jenisFilter === $j ? 'selected' : '' ?>><?= $j ?></option>
        <?php endforeach; ?>
      </select>
      <a href="clustering.php" class="btn btn-primary btn-sm">🤖 Jalankan Clustering</a>
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
          <th>Blok Bangunan</th>
          <th>Sarana</th>
          <th>Ulasan</th>
          <th>Pengunjung/Thn</th>
          <th>Rating</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (array_values($data) as $i => $w): ?>
        <?php
        $jenisColor = ['Budaya'=>'badge-green','Alam'=>'badge-blue','Desa Wisata'=>'badge-yellow','Religi'=>'badge-purple','Taman'=>'badge-red'][$w['jenis']] ?? 'badge-blue';
        ?>
        <tr>
          <td style="color: var(--text-muted); font-weight: 600;"><?= $w['id'] ?></td>
          <td style="font-weight: 600;"><?= htmlspecialchars($w['nama']) ?></td>
          <td><span class="badge <?= $jenisColor ?>"><?= $w['jenis'] ?></span></td>
          <td>
            <div style="display: flex; align-items: center; gap: 6px;">
              <span style="font-weight: 600;"><?= $w['daya_tarik'] ?></span>
              <div style="flex: 1; min-width: 50px;" class="progress-bar"><div class="progress-fill" style="width:<?= $w['daya_tarik']*10 ?>%; background: var(--emerald);"></div></div>
            </div>
          </td>
          <td>
            <div style="display: flex; align-items: center; gap: 6px;">
              <span style="font-weight: 600;"><?= $w['aksesibilitas'] ?></span>
              <div style="flex: 1; min-width: 50px;" class="progress-bar"><div class="progress-fill" style="width:<?= $w['aksesibilitas']*10 ?>%; background: #3b82f6;"></div></div>
            </div>
          </td>
          <td>
            <div style="display: flex; align-items: center; gap: 6px;">
              <span style="font-weight: 600;"><?= $w['fasilitas'] ?></span>
              <div style="flex: 1; min-width: 50px;" class="progress-bar"><div class="progress-fill" style="width:<?= $w['fasilitas']*10 ?>%; background: var(--gold);"></div></div>
            </div>
          </td>
          <td style="text-align: center; font-weight: 600;"><?= $w['blok_bangunan'] ?></td>
          <td style="text-align: center; font-weight: 600;"><?= $w['sarana'] ?></td>
          <td style="text-align: center; font-weight: 600;"><?= $w['ulasan'] ?></td>
          <td style="font-weight: 600; white-space: nowrap;"><?= number_format($w['jumlah_pengunjung'],0,',','.') ?></td>
          <td>
            <span style="color: #d97706; font-weight: 700;">⭐ <?= $w['rating'] ?></span>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div style="padding: 14px 24px; background: var(--slate-pale); font-size: 12px; color: var(--text-muted); border-top: 1px solid var(--border);">
    Menampilkan <?= count($data) ?> dari <?= count(getWisataData()) ?> destinasi wisata
  </div>
</div>

<!-- Info kriteria -->
<div class="card">
  <div class="card-header">
    <div class="card-title">📋 Keterangan Kriteria Penilaian</div>
  </div>
  <div class="card-body">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
      <?php
      $kriteria = [
        ['icon'=>'🎯','nama'=>'Daya Tarik','desc'=>'Keunikan, keindahan, dan daya tarik destinasi. Skala 1-10.'],
        ['icon'=>'🚗','nama'=>'Aksesibilitas','desc'=>'Kemudahan jangkauan & infrastruktur jalan. Skala 1-10.'],
        ['icon'=>'🏪','nama'=>'Fasilitas','desc'=>'Ketersediaan toilet, parkir, warung, dll. Skala 1-10.'],
        ['icon'=>'🏗️','nama'=>'Blok Bangunan','desc'=>'Jumlah bangunan pendukung di destinasi.'],
        ['icon'=>'🛎️','nama'=>'Sarana','desc'=>'Sarana & prasarana pendukung wisata. Skala 1-10.'],
        ['icon'=>'💬','nama'=>'Ulasan','desc'=>'Rata-rata ulasan pengunjung. Skala 1-5.'],
      ];
      foreach ($kriteria as $k):
      ?>
      <div style="background: var(--slate-pale); border: 1px solid var(--border); border-radius: 12px; padding: 14px;">
        <div style="font-size: 20px; margin-bottom: 6px;"><?= $k['icon'] ?></div>
        <div style="font-size: 13px; font-weight: 700; margin-bottom: 4px;"><?= $k['nama'] ?></div>
        <div style="font-size: 12px; color: var(--text-muted);"><?= $k['desc'] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

</div>
<?php require_once '../includes/footer.php'; ?>

<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';

$k = isset($_GET['k']) ? max(2, min(5, (int)$_GET['k'])) : 3;
$km = runKMeans($k);

$koordinat = [
  1  => [-7.6079, 110.2038],
  2  => [-7.5992, 110.2278],
  3  => [-7.6035, 110.2196],
  4  => [-7.5258, 110.3453],
  5  => [-7.6226, 110.1875],
  6  => [-7.5589, 110.4012],
  7  => [-7.6011, 110.2156],
  8  => [-7.5943, 110.2489],
  9  => [-7.4712, 110.2177],
  10 => [-7.5218, 110.2134],
  11 => [-7.4706, 110.2123],
  12 => [-7.4983, 110.3512],
  13 => [-7.5031, 110.4198],
  14 => [-7.6134, 110.1923],
  15 => [-7.4695, 110.2195],
];

$jsonData = json_encode(array_map(function ($w) use ($koordinat, $km) {
  $coord = $koordinat[$w['id']] ?? [-7.55, 110.25];
  return [
    'id'            => $w['id'],
    'nama'          => $w['nama'],
    'jenis'         => $w['jenis'],
    'cluster'       => $w['cluster'],
    'cluster_label' => $w['cluster_label'],
    'cluster_color' => $w['cluster_color'],
    'cluster_icon'  => $w['cluster_icon'],
    'daya_tarik'    => $w['daya_tarik'],
    'aksesibilitas' => $w['aksesibilitas'],
    'fasilitas'     => $w['fasilitas'],
    'sarana'        => $w['sarana'],
    'ulasan'        => $w['ulasan'],
    'rating'        => $w['rating'],
    'jumlah_pengunjung' => $w['jumlah_pengunjung'],
    'lat'           => $coord[0],
    'lng'           => $coord[1],
  ];
}, $km['data']));
$colorsJson = json_encode($km['colors']);
$labelsJson = json_encode($km['labels']);
?>
<style>
  #map {
    height: calc(100vh - 200px);
    min-height: 500px;
    border-radius: 0 0 14px 14px;
    overflow: hidden;
  }

  .leaflet-popup-content-wrapper {
    background: #111827;
    border: 1px solid #1e2d42;
    border-radius: 12px;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5);
    color: #f1f5f9;
  }

  .leaflet-popup-tip {
    background: #111827;
  }

  .leaflet-popup-content {
    margin: 0;
    padding: 0;
    min-width: 220px;
  }

  .popup-header {
    padding: 10px 14px;
    font-size: 12px;
    font-weight: 700;
    color: white;
    border-radius: 12px 12px 0 0;
  }

  .popup-body {
    padding: 12px 14px;
  }

  .popup-name {
    font-size: 14px;
    font-weight: 800;
    color: #f1f5f9;
    margin-bottom: 2px;
  }

  .popup-jenis {
    font-size: 11px;
    color: #64748b;
    margin-bottom: 10px;
  }

  .popup-row {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    padding: 3px 0;
    border-bottom: 1px solid #1e2d42;
    color: #94a3b8;
  }

  .popup-row strong {
    color: #f1f5f9;
  }
</style>

<div class="page-content">
  <div class="card mb-0">
    <div class="card-header">
      <div class="card-title"><i data-lucide="map" style="width:15px;height:15px;color:var(--emerald);"></i> Peta Distribusi Cluster Wisata — K=<?= $k ?></div>
      <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
        <?php foreach ($km['labels'] as $ci => $label): ?>
          <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;color:<?= $km['colors'][$ci] ?>;background:<?= $km['colors'][$ci] ?>18;border:1px solid <?= $km['colors'][$ci] ?>33;padding:3px 9px;border-radius:20px;font-weight:700;">
            <span style="width:7px;height:7px;background:<?= $km['colors'][$ci] ?>;border-radius:50%;display:inline-block;"></span>
            <?= $label ?>
          </span>
        <?php endforeach; ?>
        <div style="width:1px;height:20px;background:var(--border);"></div>
        <?php for ($ki = 2; $ki <= 5; $ki++): ?>
          <a href="?k=<?= $ki ?>" class="btn <?= $k === $ki ? 'btn-primary' : 'btn-secondary' ?> btn-sm"><?= $ki ?> Cluster</a>
        <?php endfor; ?>
      </div>
    </div>
    <div id="map"></div>
  </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  const wisataData = <?= $jsonData ?>;
  const clusterColors = <?= $colorsJson ?>;
  const clusterLabels = <?= $labelsJson ?>;

  const map = L.map('map', {
    zoomControl: true
  }).setView([-7.55, 110.25], 11);

  L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    maxZoom: 18,
    attribution: '© CartoDB © OpenStreetMap contributors'
  }).addTo(map);

  const labelMap = {
    high: 'H',
    medium: 'M',
    low: 'L'
  };

  function createIcon(color, iconType) {
    return L.divIcon({
      className: '',
      html: `<div style="
      width:40px;height:40px;
      background:${color};
      border:3px solid rgba(255,255,255,0.9);
      border-radius:50%;
      display:flex;align-items:center;justify-content:center;
      font-size:14px;
      font-weight:800;
      color:white;
      box-shadow:0 4px 16px ${color}66, 0 2px 6px rgba(0,0,0,0.4);
      cursor:pointer;
      transition:transform 0.15s;
    ">${labelMap[iconType] || 'C'}</div>`,
      iconSize: [40, 40],
      iconAnchor: [20, 20],
      popupAnchor: [0, -22]
    });
  }

  wisataData.forEach(w => {
    const marker = L.marker([w.lat, w.lng], {
      icon: createIcon(w.cluster_color, w.cluster_icon)
    }).addTo(map);
    const popup = `
    <div>
      <div class="popup-header" style="background:${w.cluster_color};">${w.cluster_label}</div>
      <div class="popup-body">
        <div class="popup-name">${w.nama}</div>
        <div class="popup-jenis">${w.jenis}</div>
        <div class="popup-row"><span>Daya Tarik</span><strong>${w.daya_tarik}/10</strong></div>
        <div class="popup-row"><span>Aksesibilitas</span><strong>${w.aksesibilitas}/10</strong></div>
        <div class="popup-row"><span>Fasilitas</span><strong>${w.fasilitas}/10</strong></div>
        <div class="popup-row"><span>Sarana</span><strong>${w.sarana}/10</strong></div>
        <div class="popup-row"><span>Rating</span><strong>${w.rating}/5</strong></div>
        <div class="popup-row"><span>Pengunjung</span><strong>${w.jumlah_pengunjung.toLocaleString()}/thn</strong></div>
      </div>
    </div>`;
    marker.bindPopup(popup, {
      maxWidth: 260
    });
    marker.on('mouseover', function() {
      this.openPopup();
    });
  });

  map.fitBounds(wisataData.map(w => [w.lat, w.lng]), {
    padding: [40, 40]
  });
</script>

<?php require_once '../includes/footer.php'; ?>
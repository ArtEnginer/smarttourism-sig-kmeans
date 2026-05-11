<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';

$k = isset($_GET['k']) ? max(2, min(5, (int)$_GET['k'])) : 3;
$km = runKMeans($k);

// Koordinat destinasi wisata (lat, lng approximate)
$koordinat = [
    1 => [-7.6079, 110.2038], // Borobudur
    2 => [-7.5992, 110.2278], // Mendut
    3 => [-7.6035, 110.2196], // Pawon
    4 => [-7.5258, 110.3453], // Ketep Pass
    5 => [-7.6226, 110.1875], // Punthuk Setumbu
    6 => [-7.5589, 110.4012], // Kedung Kayang
    7 => [-7.6011, 110.2156], // Candirejo
    8 => [-7.5943, 110.2489], // Ngargogondo
    9 => [-7.4712, 110.2177], // Masjid Agung
    10 => [-7.5218, 110.2134], // Makam Kyai
    11 => [-7.4706, 110.2123], // Taman Kyai Langgeng
    12 => [-7.4983, 110.3512], // Kebun Teh Ngluwar
    13 => [-7.5031, 110.4198], // Merbabu Basecamp
    14 => [-7.6134, 110.1923], // Puthuk Mongkrong
    15 => [-7.4695, 110.2195], // Museum Diponegoro
];
$jsonData = json_encode(array_map(function($w) use ($koordinat, $km) {
    $coord = $koordinat[$w['id']] ?? [-7.55, 110.25];
    return [
        'id' => $w['id'],
        'nama' => $w['nama'],
        'jenis' => $w['jenis'],
        'cluster' => $w['cluster'],
        'cluster_label' => $w['cluster_label'],
        'cluster_color' => $w['cluster_color'],
        'cluster_icon' => $w['cluster_icon'],
        'daya_tarik' => $w['daya_tarik'],
        'aksesibilitas' => $w['aksesibilitas'],
        'fasilitas' => $w['fasilitas'],
        'rating' => $w['rating'],
        'jumlah_pengunjung' => $w['jumlah_pengunjung'],
        'lat' => $coord[0],
        'lng' => $coord[1],
    ];
}, $km['data']));
$colorsJson = json_encode($km['colors']);
$labelsJson = json_encode($km['labels']);
?>
<style>
#map { height: calc(100vh - 180px); min-height: 500px; border-radius: 16px; overflow: hidden; }
.leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.15); }
.leaflet-popup-content { margin: 0; padding: 0; min-width: 220px; }
.popup-inner { padding: 16px; }
.popup-title { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
.popup-jenis { font-size: 11px; color: #64748b; margin-bottom: 10px; }
.popup-stat { display: flex; justify-content: space-between; font-size: 12px; padding: 3px 0; border-bottom: 1px solid #f1f5f9; }
.popup-cluster { padding: 8px 16px; font-size: 12px; font-weight: 700; color: white; }
.map-legend { position: absolute; bottom: 30px; right: 10px; z-index: 1000; background: white; border-radius: 12px; padding: 12px 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; min-width: 180px; }
.legend-item { display: flex; align-items: center; gap: 8px; font-size: 12px; padding: 3px 0; }
.legend-dot { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; }
</style>
<div class="page-content">

<div class="card mb-20">
  <div class="card-header">
    <div class="card-title">🗾 Peta Interaktif Distribusi Cluster Wisata</div>
    <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
      <?php for ($ki=2; $ki<=5; $ki++): ?>
      <a href="?k=<?= $ki ?>" class="btn <?= $k===$ki ? 'btn-primary' : 'btn-secondary' ?> btn-sm"><?= $ki ?> Cluster</a>
      <?php endfor; ?>
    </div>
  </div>
</div>

<div class="card" style="position: relative; overflow: visible;">
  <div id="map"></div>
  <div class="map-legend">
    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">LEGENDA CLUSTER</div>
    <?php foreach ($km['labels'] as $ci => $label): ?>
    <div class="legend-item">
      <div class="legend-dot" style="background: <?= $km['colors'][$ci] ?>;"></div>
      <span><?= $label ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</div>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const wisataData = <?= $jsonData ?>;
const clusterColors = <?= $colorsJson ?>;
const clusterLabels = <?= $labelsJson ?>;

const map = L.map('map').setView([-7.55, 110.25], 11);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 18,
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

function createCustomIcon(color, icon) {
  const emojiMap = { high: '🏆', medium: '⭐', low: '🌱' };
  return L.divIcon({
    className: '',
    html: `<div style="
      width: 38px; height: 38px;
      background: ${color};
      border: 3px solid white;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px;
      box-shadow: 0 3px 12px ${color}66, 0 1px 4px rgba(0,0,0,0.2);
      cursor: pointer;
      transition: transform 0.2s;
    ">${emojiMap[icon] || '📍'}</div>`,
    iconSize: [38, 38],
    iconAnchor: [19, 19],
    popupAnchor: [0, -20]
  });
}

wisataData.forEach(w => {
  const marker = L.marker([w.lat, w.lng], {
    icon: createCustomIcon(w.cluster_color, w.cluster_icon)
  }).addTo(map);

  const popupHtml = `
    <div style="border-radius: 12px; overflow: hidden;">
      <div class="popup-cluster" style="background: ${w.cluster_color};">${w.cluster_label}</div>
      <div class="popup-inner">
        <div class="popup-title">${w.nama}</div>
        <div class="popup-jenis">${w.jenis}</div>
        <div class="popup-stat"><span>🎯 Daya Tarik</span><strong>${w.daya_tarik}/10</strong></div>
        <div class="popup-stat"><span>🚗 Aksesibilitas</span><strong>${w.aksesibilitas}/10</strong></div>
        <div class="popup-stat"><span>🏪 Fasilitas</span><strong>${w.fasilitas}/10</strong></div>
        <div class="popup-stat"><span>⭐ Rating</span><strong>${w.rating}/5</strong></div>
        <div class="popup-stat"><span>👥 Pengunjung</span><strong>${w.jumlah_pengunjung.toLocaleString()}/thn</strong></div>
        <div class="popup-stat"><span>🔢 Cluster</span><strong>C${w.cluster + 1}</strong></div>
      </div>
    </div>`;
  
  marker.bindPopup(popupHtml, { maxWidth: 260 });
  marker.on('mouseover', function() { this.openPopup(); });
});

// Fit bounds
const bounds = wisataData.map(w => [w.lat, w.lng]);
map.fitBounds(bounds, { padding: [40, 40] });
</script>

<?php require_once '../includes/footer.php'; ?>

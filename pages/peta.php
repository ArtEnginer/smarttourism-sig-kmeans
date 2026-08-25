<?php
require_once '../includes/header.php';
require_once '../includes/kmeans.php';

$k = isset($_GET['k']) ? max(2, min(5, (int)$_GET['k'])) : 3;
$km = runKMeans($k);
if (empty($km['data'])) {
?>
  <div class="page-content">
    <div class="card">
      <div class="card-body">
        <div class="info-box blue">Belum ada data destinasi di database, sehingga peta tidak dapat ditampilkan.</div>
      </div>
    </div>
  </div>
<?php require_once '../includes/footer.php';
  return;
}

$jsonData = json_encode(array_map(function ($w) {
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
    'lat'           => $w['lat'],
    'lng'           => $w['lng'],
  ];
}, $km['data']));
$colorsJson = json_encode($km['colors']);
$labelsJson = json_encode($km['labels']);
?>
<style>
  #map {
    height: calc(100vh - 210px);
    min-height: 480px;
    border-radius: 0 0 var(--radius) var(--radius);
    overflow: hidden;
    z-index: 1;
  }

  .leaflet-popup-content-wrapper {
    background: #ffffff;
    border: 1px solid var(--emerald-border);
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
    color: var(--slate);
  }

  .leaflet-popup-tip {
    background: #ffffff;
  }

  .leaflet-popup-content {
    margin: 0;
    padding: 0;
    min-width: 230px;
  }

  .popup-header {
    padding: 11px 14px;
    font-size: 12px;
    font-weight: 800;
    color: white;
    border-radius: 13px 13px 0 0;
  }

  .popup-body {
    padding: 14px;
  }

  .popup-name {
    font-size: 14.5px;
    font-weight: 800;
    color: var(--slate);
    margin-bottom: 2px;
  }

  .popup-jenis {
    font-size: 11.5px;
    color: var(--slate-light);
    margin-bottom: 10px;
    font-weight: 600;
  }

  .popup-row {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    padding: 4px 0;
    border-bottom: 1px solid var(--border);
    color: var(--slate-light);
  }

  .popup-row strong {
    color: var(--slate);
    font-weight: 700;
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
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);background:linear-gradient(180deg, rgba(16,185,129,0.05), rgba(255,255,255,0));display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;">
      <div style="display:flex;gap:14px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-2);"><span style="width:10px;height:10px;border-radius:50%;background:var(--emerald);display:inline-block;"></span>Prioritas tinggi</div>
        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-2);"><span style="width:10px;height:10px;border-radius:50%;background:var(--gold);display:inline-block;"></span>Prioritas sedang</div>
        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-2);"><span style="width:10px;height:10px;border-radius:50%;background:var(--purple);display:inline-block;"></span>Prioritas rendah</div>
      </div>
      <div style="font-size:12px;color:var(--text-3);">Klik marker untuk detail destinasi, lalu ganti jenis peta lewat kontrol di kanan atas.</div>
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

  const baseLayers = {
    'Peta Ringan': L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
      maxZoom: 19,
      attribution: '© CartoDB © OpenStreetMap contributors'
    }),
    'Peta Jalan': L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '© OpenStreetMap contributors'
    }),
    'Peta Gelap': L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
      maxZoom: 19,
      attribution: '© CartoDB © OpenStreetMap contributors'
    }),
    'Satelit': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
      maxZoom: 19,
      attribution: 'Tiles © Esri'
    })
  };

  baseLayers['Peta Ringan'].addTo(map);
  L.control.layers(baseLayers, null, {
    position: 'topright',
    collapsed: false
  }).addTo(map);
  map.getContainer().classList.add('map-frame');

  const clusterLegend = L.control({
    position: 'bottomleft'
  });
  clusterLegend.onAdd = function() {
    const div = L.DomUtil.create('div', 'map-legend');
    div.innerHTML = `
      <div class="map-legend-title">Legenda Cluster</div>
      ${Object.entries(clusterLabels).map(([index, label]) => {
        const color = clusterColors[index];
        return `<div class="map-legend-row"><span class="legend-dot" style="background:${color}"></span><span>${label}</span></div>`;
      }).join('')}
    `;
    return div;
  };
  clusterLegend.addTo(map);

  const labelMap = {
    high: 'H',
    medium: 'M',
    low: 'L'
  };

  function createIcon(color, iconType) {
    return L.divIcon({
      className: '',
      html: `<div style="
      width:42px;height:42px;
      background:${color};
      border:3px solid rgba(255,255,255,0.96);
      border-radius:50%;
      display:flex;align-items:center;justify-content:center;
      font-size:14px;
      font-weight:800;
      color:white;
      box-shadow:0 8px 24px ${color}55, 0 4px 10px rgba(15,23,42,0.22);
      cursor:pointer;
      transition:transform 0.15s, box-shadow 0.15s;
    ">${labelMap[iconType] || 'C'}</div>`,
      iconSize: [42, 42],
      iconAnchor: [21, 21],
      popupAnchor: [0, -24]
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

<style>
  .map-frame {
    border-radius: 0 0 14px 14px;
    overflow: hidden;
  }

  #map {
    height: calc(100vh - 220px);
    min-height: 560px;
    background: linear-gradient(180deg, #e2e8f0, #f8fafc);
  }

  .leaflet-control-layers {
    border: 1px solid rgba(148, 163, 184, 0.24) !important;
    border-radius: 12px !important;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12) !important;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.96) !important;
    backdrop-filter: blur(10px);
  }

  .leaflet-control-layers-expanded {
    padding: 10px 12px 8px !important;
    color: var(--text);
  }

  .leaflet-control-layers label {
    font-size: 12px;
    line-height: 1.55;
    margin-bottom: 4px;
    cursor: pointer;
  }

  .leaflet-control-layers-selector {
    accent-color: var(--emerald);
  }

  .map-legend {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(148, 163, 184, 0.18);
    box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
    backdrop-filter: blur(10px);
    border-radius: 14px;
    padding: 12px 14px;
    min-width: 160px;
    color: var(--text);
  }

  .map-legend-title {
    font-size: 12px;
    font-weight: 800;
    margin-bottom: 10px;
  }

  .map-legend-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--text-2);
    margin-bottom: 7px;
  }

  .map-legend-row:last-child {
    margin-bottom: 0;
  }

  .legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.9);
    flex-shrink: 0;
  }

  .leaflet-popup-content-wrapper,
  .leaflet-popup-tip {
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.25) !important;
  }

  .leaflet-container a.leaflet-popup-close-button {
    color: rgba(255, 255, 255, 0.7) !important;
  }

  .leaflet-control-attribution {
    background: rgba(255, 255, 255, 0.88) !important;
    border-radius: 10px 0 0 0;
    padding: 2px 8px !important;
    font-size: 10px !important;
  }
</style>

<?php require_once '../includes/footer.php'; ?>
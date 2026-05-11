<?php
session_start();

// ===================== DATA WISATA MAGELANG =====================
$destinations = [
    ['id' => 1, 'name' => 'Candi Borobudur', 'category' => 'Budaya', 'kecamatan' => 'Borobudur', 'lat' => -7.6079, 'lng' => 110.2038, 'a1' => 5, 'a2' => 5, 'a3' => 5, 'a4' => 5, 'a5' => 950, 'desc' => 'Candi Buddha terbesar di dunia, warisan UNESCO.'],
    ['id' => 2, 'name' => 'Candi Pawon', 'category' => 'Budaya', 'kecamatan' => 'Borobudur', 'lat' => -7.6018, 'lng' => 110.2117, 'a1' => 4, 'a2' => 4, 'a3' => 3, 'a4' => 3, 'a5' => 180, 'desc' => 'Candi kecil antara Borobudur dan Mendut.'],
    ['id' => 3, 'name' => 'Candi Mendut', 'category' => 'Budaya', 'kecamatan' => 'Mungkid', 'lat' => -7.5978, 'lng' => 110.2267, 'a1' => 4, 'a2' => 4, 'a3' => 3, 'a4' => 3, 'a5' => 220, 'desc' => 'Candi Buddha dengan patung Buddha besar.'],
    ['id' => 4, 'name' => 'Museum Diponegoro', 'category' => 'Budaya', 'kecamatan' => 'Magelang Utara', 'lat' => -7.4676, 'lng' => 110.2176, 'a1' => 4, 'a2' => 4, 'a3' => 4, 'a4' => 3, 'a5' => 150, 'desc' => 'Museum yang menyimpan peninggalan Pangeran Diponegoro.'],
    ['id' => 5, 'name' => 'Taman Kyai Langgeng', 'category' => 'Alam', 'kecamatan' => 'Magelang Tengah', 'lat' => -7.4731, 'lng' => 110.2181, 'a1' => 4, 'a2' => 5, 'a3' => 5, 'a4' => 5, 'a5' => 320, 'desc' => 'Taman wisata alam dengan wahana dan kebun binatang mini.'],
    ['id' => 6, 'name' => 'Gunung Tidar', 'category' => 'Alam', 'kecamatan' => 'Magelang Selatan', 'lat' => -7.4892, 'lng' => 110.2147, 'a1' => 4, 'a2' => 3, 'a3' => 3, 'a4' => 4, 'a5' => 280, 'desc' => 'Bukit ikonik "Pakunya Pulau Jawa" dengan pemandangan kota.'],
    ['id' => 7, 'name' => 'Air Terjun Sekar Langit', 'category' => 'Alam', 'kecamatan' => 'Grabag', 'lat' => -7.5234, 'lng' => 110.3012, 'a1' => 4, 'a2' => 2, 'a3' => 2, 'a4' => 3, 'a5' => 90, 'desc' => 'Air terjun tersembunyi di lereng pegunungan.'],
    ['id' => 8, 'name' => 'Desa Wisata Candirejo', 'category' => 'Desa Wisata', 'kecamatan' => 'Borobudur', 'lat' => -7.6231, 'lng' => 110.1987, 'a1' => 4, 'a2' => 3, 'a3' => 3, 'a4' => 5, 'a5' => 160, 'desc' => 'Desa wisata tradisional dengan pertanian dan budaya lokal.'],
    ['id' => 9, 'name' => 'Desa Wisata Wanurejo', 'category' => 'Desa Wisata', 'kecamatan' => 'Borobudur', 'lat' => -7.6087, 'lng' => 110.1945, 'a1' => 3, 'a2' => 3, 'a3' => 3, 'a4' => 4, 'a5' => 120, 'desc' => 'Desa penghasil gerabah dan kerajinan lokal.'],
    ['id' => 10, 'name' => 'Masjid Agung Magelang', 'category' => 'Religi', 'kecamatan' => 'Magelang Tengah', 'lat' => -7.4700, 'lng' => 110.2178, 'a1' => 3, 'a2' => 5, 'a3' => 4, 'a4' => 2, 'a5' => 400, 'desc' => 'Masjid bersejarah di pusat kota Magelang.'],
    ['id' => 11, 'name' => 'Gereja St. Ignatius', 'category' => 'Religi', 'kecamatan' => 'Magelang Tengah', 'lat' => -7.4712, 'lng' => 110.2190, 'a1' => 3, 'a2' => 5, 'a3' => 3, 'a4' => 2, 'a5' => 100, 'desc' => 'Gereja Katolik berarsitektur kolonial.'],
    ['id' => 12, 'name' => 'Pasar Rejowinangun', 'category' => 'Kuliner', 'kecamatan' => 'Magelang Tengah', 'lat' => -7.4720, 'lng' => 110.2175, 'a1' => 3, 'a2' => 5, 'a3' => 3, 'a4' => 3, 'a5' => 500, 'desc' => 'Pusat kuliner dan oleh-oleh khas Magelang.'],
    ['id' => 13, 'name' => 'Sentra Kupat Tahu', 'category' => 'Kuliner', 'kecamatan' => 'Magelang Tengah', 'lat' => -7.4698, 'lng' => 110.2168, 'a1' => 3, 'a2' => 5, 'a3' => 3, 'a4' => 2, 'a5' => 380, 'desc' => 'Kuliner khas Magelang yang terkenal nasional.'],
    ['id' => 14, 'name' => 'Museum BPK RI', 'category' => 'Budaya', 'kecamatan' => 'Magelang Selatan', 'lat' => -7.4867, 'lng' => 110.2143, 'a1' => 3, 'a2' => 4, 'a3' => 4, 'a4' => 3, 'a5' => 80, 'desc' => 'Museum sejarah keuangan negara.'],
    ['id' => 15, 'name' => 'Pantai Sepanjang Sisi Progo', 'category' => 'Alam', 'kecamatan' => 'Borobudur', 'lat' => -7.5987, 'lng' => 110.1834, 'a1' => 3, 'a2' => 2, 'a3' => 2, 'a4' => 3, 'a5' => 70, 'desc' => 'Wisata sungai Progo dengan aktivitas rafting.'],
    ['id' => 16, 'name' => 'Desa Wisata Karanganyar', 'category' => 'Desa Wisata', 'kecamatan' => 'Borobudur', 'lat' => -7.6312, 'lng' => 110.2134, 'a1' => 3, 'a2' => 2, 'a3' => 2, 'a4' => 4, 'a5' => 55, 'desc' => 'Desa wisata dengan kerajinan batik khas Borobudur.'],
    ['id' => 17, 'name' => 'Kolam Renang Borobudur', 'category' => 'Alam', 'kecamatan' => 'Borobudur', 'lat' => -7.6112, 'lng' => 110.2087, 'a1' => 2, 'a2' => 4, 'a3' => 4, 'a4' => 3, 'a5' => 210, 'desc' => 'Kolam renang bertema wisata dekat Borobudur.'],
    ['id' => 18, 'name' => 'Puncak Suroloyo', 'category' => 'Alam', 'kecamatan' => 'Samigaluh', 'lat' => -7.6543, 'lng' => 110.1456, 'a1' => 5, 'a2' => 2, 'a3' => 2, 'a4' => 4, 'a5' => 130, 'desc' => 'Puncak tertinggi dengan pemandangan Borobudur dari atas awan.'],
    ['id' => 19, 'name' => 'Sunrise Punthuk Setumbu', 'category' => 'Alam', 'kecamatan' => 'Borobudur', 'lat' => -7.6098, 'lng' => 110.1876, 'a1' => 5, 'a2' => 3, 'a3' => 3, 'a4' => 4, 'a5' => 290, 'desc' => 'Spot sunrise terbaik dengan latar Borobudur.'],
    ['id' => 20, 'name' => 'Desa Wisata Ngargogondo', 'category' => 'Desa Wisata', 'kecamatan' => 'Borobudur', 'lat' => -7.6198, 'lng' => 110.2076, 'a1' => 3, 'a2' => 3, 'a3' => 2, 'a4' => 4, 'a5' => 65, 'desc' => 'Desa wisata edukasi pertanian organik.'],
];

// ===================== K-MEANS CLUSTERING =====================
function minMaxNormalize($data, $cols)
{
    $mins = [];
    $maxs = [];
    foreach ($cols as $c) {
        $vals = array_column($data, $c);
        $mins[$c] = min($vals);
        $maxs[$c] = max($vals);
    }
    $normalized = [];
    foreach ($data as $row) {
        $nr = $row;
        foreach ($cols as $c) {
            $range = $maxs[$c] - $mins[$c];
            $nr[$c . '_norm'] = $range > 0 ? ($row[$c] - $mins[$c]) / $range : 0;
        }
        $normalized[] = $nr;
    }
    return [$normalized, $mins, $maxs];
}

function euclideanDistance($p1, $p2, $cols)
{
    $sum = 0;
    foreach ($cols as $c) {
        $sum += pow($p1[$c . '_norm'] - $p2[$c . '_norm'], 2);
    }
    return sqrt($sum);
}

function kMeans($data, $k, $cols, $maxIter = 100)
{
    $n = count($data);
    $iterations = [];

    // K-Means++ initialization
    $centroids = [];
    $centroids[] = $data[array_rand($data)];
    for ($i = 1; $i < $k; $i++) {
        $distances = [];
        foreach ($data as $d) {
            $minD = INF;
            foreach ($centroids as $c) {
                $dist = euclideanDistance($d, $c, $cols);
                if ($dist < $minD) $minD = $dist;
            }
            $distances[] = $minD * $minD;
        }
        $total = array_sum($distances);
        $r = (float)rand() / PHP_INT_MAX * $total;
        $cumulative = 0;
        foreach ($distances as $idx => $dist) {
            $cumulative += $dist;
            if ($cumulative >= $r) {
                $centroids[] = $data[$idx];
                break;
            }
        }
    }

    $assignments = array_fill(0, $n, 0);
    for ($iter = 0; $iter < $maxIter; $iter++) {
        $iterData = ['iteration' => $iter + 1, 'centroids' => [], 'assignments' => [], 'distances' => []];

        // Assign to nearest centroid
        $newAssignments = [];
        $allDistances = [];
        foreach ($data as $i => $d) {
            $minDist = INF;
            $minCluster = 0;
            $dists = [];
            foreach ($centroids as $ci => $c) {
                $dist = euclideanDistance($d, $c, $cols);
                $dists[] = round($dist, 4);
                if ($dist < $minDist) {
                    $minDist = $dist;
                    $minCluster = $ci;
                }
            }
            $newAssignments[] = $minCluster;
            $allDistances[] = $dists;
        }
        $iterData['assignments'] = $newAssignments;
        $iterData['distances'] = $allDistances;

        // Record centroids
        foreach ($centroids as $ci => $c) {
            $cv = [];
            foreach ($cols as $col) $cv[$col . '_norm'] = round($c[$col . '_norm'], 4);
            $iterData['centroids'][] = $cv;
        }
        $iterations[] = $iterData;

        // Update centroids
        $newCentroids = [];
        for ($ci = 0; $ci < $k; $ci++) {
            $members = [];
            foreach ($newAssignments as $i => $a) {
                if ($a == $ci) $members[] = $data[$i];
            }
            if (empty($members)) {
                $newCentroids[] = $centroids[$ci];
                continue;
            }
            $nc = $centroids[$ci];
            foreach ($cols as $col) {
                $nc[$col . '_norm'] = array_sum(array_column($members, $col . '_norm')) / count($members);
            }
            $newCentroids[] = $nc;
        }

        // Check convergence
        $converged = true;
        for ($ci = 0; $ci < $k; $ci++) {
            $dist = euclideanDistance($centroids[$ci], $newCentroids[$ci], $cols);
            if ($dist > 0.0001) {
                $converged = false;
                break;
            }
        }
        $centroids = $newCentroids;
        $assignments = $newAssignments;
        if ($converged) break;
    }

    return [$assignments, $centroids, $iterations];
}

function computeSSE($data, $assignments, $centroids, $cols)
{
    $sse = 0;
    foreach ($data as $i => $d) {
        $c = $centroids[$assignments[$i]];
        $sse += pow(euclideanDistance($d, $c, $cols), 2);
    }
    return $sse;
}

// Run clustering
$cols = ['a1', 'a2', 'a3', 'a4', 'a5'];
[$normalized, $mins, $maxs] = minMaxNormalize($destinations, $cols);

srand(42);
[$assignments, $centroids, $iterations] = kMeans($normalized, 4, $cols);

// Add cluster to destinations
foreach ($destinations as &$d) {
    $idx = $d['id'] - 1;
    $d['cluster'] = $assignments[$idx];
}
unset($d);

// Compute SSE for elbow
$elbowData = [];
for ($k = 1; $k <= 8; $k++) {
    srand(42);
    [$a, $c, $_] = kMeans($normalized, $k, $cols);
    $elbowData[] = round(computeSSE($normalized, $a, $c, $cols), 4);
}

// Cluster labels & colors
$clusterMeta = [
    ['label' => 'Potensi Tinggi', 'color' => '#EF4444', 'hex' => 'EF4444', 'bg' => '#FEE2E2', 'icon' => '🔴'],
    ['label' => 'Potensi Sedang', 'color' => '#F59E0B', 'hex' => 'F59E0B', 'bg' => '#FEF3C7', 'icon' => '🟡'],
    ['label' => 'Potensi Berkembang', 'color' => '#10B981', 'hex' => '10B981', 'bg' => '#D1FAE5', 'icon' => '🟢'],
    ['label' => 'Potensi Khusus', 'color' => '#3B82F6', 'hex' => '3B82F6', 'bg' => '#DBEAFE', 'icon' => '🔵'],
];

// Reorder clusters by avg score
$clusterAvg = [];
for ($i = 0; $i < 4; $i++) {
    $members = array_filter($destinations, fn($d) => $d['cluster'] == $i);
    if (empty($members)) {
        $clusterAvg[$i] = 0;
        continue;
    }
    $scores = array_map(fn($d) => ($d['a1'] + $d['a2'] + $d['a3'] + $d['a4']) / 4 + $d['a5'] / 200, $members);
    $clusterAvg[$i] = array_sum($scores) / count($scores);
}
arsort($clusterAvg);
$clusterOrder = array_keys($clusterAvg);
$clusterMap = array_flip($clusterOrder);
foreach ($destinations as &$d) {
    $d['cluster_display'] = $clusterMap[$d['cluster']];
}
unset($d);

$page = $_GET['page'] ?? 'map';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>SIG Wisata Magelang — Smart Tourism 2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --bg: #0a0f1e;
            --bg2: #0f172a;
            --bg3: #1e293b;
            --surface: rgba(30, 41, 59, 0.8);
            --border: rgba(148, 163, 184, 0.1);
            --border2: rgba(148, 163, 184, 0.2);
            --text: #f1f5f9;
            --muted: #94a3b8;
            --accent: #38bdf8;
            --accent2: #818cf8;
            --gold: #f59e0b;
            --red: #EF4444;
            --green: #10B981;
            --blue: #3B82F6;
            --r: 12px;
            --r2: 20px;
            --shadow: 0 4px 32px rgba(0, 0, 0, 0.4);
            --nav-h: 64px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            background: var(--bg);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        /* ===== BACKGROUND MESH ===== */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 10% 10%, rgba(56, 189, 248, 0.06) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 90% 90%, rgba(129, 140, 248, 0.06) 0%, transparent 60%),
                radial-gradient(ellipse 50% 50% at 50% 50%, rgba(245, 158, 11, 0.03) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ===== NAV ===== */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: var(--nav-h);
            background: rgba(10, 15, 30, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 16px;
            gap: 12px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: var(--text);
            text-decoration: none;
            flex-shrink: 0;
        }

        .nav-brand .logo {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .nav-brand span {
            display: none;
        }

        @media(min-width:480px) {
            .nav-brand span {
                display: block;
            }
        }

        .nav-tabs {
            display: flex;
            gap: 4px;
            flex: 1;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .nav-tabs::-webkit-scrollbar {
            display: none;
        }

        .nav-tab {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--muted);
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            transition: .2s;
            border: 1px solid transparent;
            flex-shrink: 0;
        }

        .nav-tab:hover {
            color: var(--text);
            background: var(--bg3);
        }

        .nav-tab.active {
            color: var(--accent);
            background: rgba(56, 189, 248, 0.1);
            border-color: rgba(56, 189, 248, 0.2);
        }

        .nav-tab .icon {
            font-size: 15px;
        }

        .nav-tab .label {
            display: none;
        }

        @media(min-width:768px) {
            .nav-tab .label {
                display: block;
            }
        }

        /* ===== LAYOUT ===== */
        .page {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            padding-top: var(--nav-h);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 16px;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r2);
            backdrop-filter: blur(12px);
        }

        .card-header {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .card-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-body {
            padding: 20px;
        }

        /* ===== STAT CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        @media(min-width:640px) {
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r2);
            padding: 16px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            transform: translate(20px, -20px);
            opacity: 0.15;
        }

        .stat-card.c-blue::before {
            background: var(--accent);
        }

        .stat-card.c-purple::before {
            background: var(--accent2);
        }

        .stat-card.c-gold::before {
            background: var(--gold);
        }

        .stat-card.c-green::before {
            background: var(--green);
        }

        .stat-label {
            font-size: 11px;
            color: var(--muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            font-family: 'Space Grotesk', sans-serif;
            line-height: 1;
        }

        .stat-card.c-blue .stat-value {
            color: var(--accent);
        }

        .stat-card.c-purple .stat-value {
            color: var(--accent2);
        }

        .stat-card.c-gold .stat-value {
            color: var(--gold);
        }

        .stat-card.c-green .stat-value {
            color: var(--green);
        }

        .stat-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 6px;
        }

        /* ===== MAP PAGE ===== */
        .map-layout {
            display: flex;
            flex-direction: column;
            height: calc(100vh - var(--nav-h));
        }

        @media(min-width:768px) {
            .map-layout {
                flex-direction: row;
            }
        }

        .map-sidebar {
            width: 100%;
            height: 260px;
            background: rgba(10, 15, 30, 0.95);
            border-right: 1px solid var(--border);
            overflow-y: auto;
            flex-shrink: 0;
        }

        @media(min-width:768px) {
            .map-sidebar {
                width: 300px;
                height: 100%;
            }
        }

        .sidebar-header {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 10;
            background: rgba(10, 15, 30, 0.95);
            backdrop-filter: blur(10px);
        }

        .sidebar-title {
            font-size: 13px;
            font-weight: 600;
            font-family: 'Space Grotesk', sans-serif;
            color: var(--text);
            margin-bottom: 10px;
        }

        .search-box {
            width: 100%;
            padding: 9px 12px;
            background: var(--bg3);
            border: 1px solid var(--border2);
            border-radius: 10px;
            color: var(--text);
            font-size: 13px;
            font-family: inherit;
            outline: none;
        }

        .search-box:focus {
            border-color: var(--accent);
        }

        .filter-chips {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .chip {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            transition: .2s;
            white-space: nowrap;
        }

        .chip.all {
            border-color: var(--border2);
            color: var(--muted);
        }

        .chip.all.active {
            background: rgba(56, 189, 248, 0.15);
            border-color: var(--accent);
            color: var(--accent);
        }

        .dest-list {
            padding: 8px;
        }

        .dest-item {
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            margin-bottom: 4px;
            transition: .2s;
            border: 1px solid transparent;
        }

        .dest-item:hover {
            background: var(--bg3);
            border-color: var(--border);
        }

        .dest-item.active {
            background: rgba(56, 189, 248, 0.08);
            border-color: rgba(56, 189, 248, 0.2);
        }

        .dest-name {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .dest-meta {
            display: flex;
            gap: 6px;
            align-items: center;
            flex-wrap: wrap;
        }

        .badge {
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-cluster {
            color: white;
        }

        #map {
            flex: 1;
            min-height: 300px;
        }

        @media(min-width:768px) {
            #map {
                min-height: unset;
            }
        }

        /* LEAFLET custom */
        .leaflet-popup-content-wrapper {
            background: var(--bg2) !important;
            border: 1px solid var(--border2) !important;
            border-radius: 16px !important;
            box-shadow: var(--shadow) !important;
            color: var(--text) !important;
        }

        .leaflet-popup-tip {
            background: var(--bg2) !important;
        }

        .popup-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 6px;
            font-family: 'Space Grotesk', sans-serif;
        }

        .popup-desc {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .popup-attrs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }

        .popup-attr {
            background: var(--bg3);
            border-radius: 8px;
            padding: 8px;
            text-align: center;
        }

        .popup-attr-label {
            font-size: 10px;
            color: var(--muted);
            display: block;
        }

        .popup-attr-val {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent);
        }

        .popup-cluster {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        /* ===== DASHBOARD ===== */
        .dash-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: 1fr;
        }

        @media(min-width:768px) {
            .dash-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media(min-width:1024px) {
            .dash-grid {
                grid-template-columns: 2fr 1fr;
            }
        }

        /* ===== CLUSTER ANALYSIS ===== */
        .cluster-cards {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(2, 1fr);
        }

        @media(min-width:640px) {
            .cluster-cards {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .cluster-card {
            border-radius: var(--r2);
            padding: 16px;
            border: 1px solid var(--border);
            background: var(--surface);
            text-align: center;
        }

        .cluster-emoji {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .cluster-name {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 4px;
            font-family: 'Space Grotesk', sans-serif;
        }

        .cluster-count {
            font-size: 24px;
            font-weight: 800;
        }

        .bar-chart {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .bar-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bar-label {
            font-size: 12px;
            color: var(--muted);
            width: 120px;
            flex-shrink: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .bar-wrap {
            flex: 1;
            background: var(--bg3);
            border-radius: 4px;
            overflow: hidden;
        }

        .bar-fill {
            height: 20px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            padding-left: 8px;
            font-size: 11px;
            font-weight: 600;
            color: white;
            transition: width 1s;
        }

        /* ===== K-MEANS MANUAL ===== */
        .kmeans-section {
            margin-bottom: 24px;
        }

        .kmeans-section h3 {
            font-size: 15px;
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }

        .step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            font-size: 12px;
            font-weight: 700;
            color: white;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .matrix-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 500px;
        }

        table.data-table th {
            background: var(--bg3);
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid var(--border2);
            font-family: 'Space Grotesk', sans-serif;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
        }

        table.data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        table.data-table tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        .formula-box {
            background: var(--bg3);
            border: 1px solid var(--border2);
            border-radius: var(--r);
            padding: 16px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.8;
            color: var(--accent);
        }

        .formula-comment {
            color: var(--muted);
        }

        .iteration-tabs {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .iter-tab {
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border2);
            color: var(--muted);
            transition: .2s;
            background: transparent;
        }

        .iter-tab.active {
            background: rgba(56, 189, 248, 0.1);
            border-color: var(--accent);
            color: var(--accent);
        }

        .iter-content {
            display: none;
        }

        .iter-content.active {
            display: block;
        }

        /* ===== DATA MANAGEMENT ===== */
        .form-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: 1fr;
        }

        @media(min-width:640px) {
            .form-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg3);
            border: 1px solid var(--border2);
            border-radius: 10px;
            color: var(--text);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: .2s;
        }

        .form-control:focus {
            border-color: var(--accent);
            background: rgba(56, 189, 248, 0.05);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: .2s;
            font-family: inherit;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: white;
        }

        .btn-primary:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 8px;
        }

        .btn-danger {
            background: rgba(239, 68, 68, .15);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, .3);
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--bg3);
            border-radius: 2px;
        }

        /* ===== BOTTOM NAV (mobile) ===== */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(10, 15, 30, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid var(--border);
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            padding: 8px 0 max(8px, env(safe-area-inset-bottom));
        }

        @media(min-width:768px) {
            .bottom-nav {
                display: none;
            }
        }

        .bnav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            text-decoration: none;
            color: var(--muted);
            font-size: 10px;
            font-weight: 500;
            padding: 4px;
            transition: .2s;
        }

        .bnav-item.active {
            color: var(--accent);
        }

        .bnav-icon {
            font-size: 20px;
            line-height: 1;
        }

        /* hide top nav on mobile for map (save space) */
        @media(max-width:767px) {
            .page.map-page {
                padding-bottom: 70px;
            }

            .page:not(.map-page) {
                padding-bottom: 80px;
            }
        }

        /* ===== MISC ===== */
        .section-title {
            font-size: 22px;
            font-weight: 800;
            font-family: 'Space Grotesk', sans-serif;
            margin-bottom: 6px;
        }

        .section-sub {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 20px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            color: var(--muted);
        }

        .grid-2 {
            display: grid;
            gap: 16px;
            grid-template-columns: 1fr;
        }

        @media(min-width:640px) {
            .grid-2 {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- TOP NAV -->
    <nav>
        <a href="?page=map" class="nav-brand">
            <div class="logo">🗺️</div>
            <span>SIG Wisata Magelang</span>
        </a>
        <div class="nav-tabs">
            <a href="?page=map" class="nav-tab <?= $page == 'map' ? 'active' : '' ?>">
                <span class="icon">🗺️</span><span class="label">Peta</span>
            </a>
            <a href="?page=dashboard" class="nav-tab <?= $page == 'dashboard' ? 'active' : '' ?>">
                <span class="icon">📊</span><span class="label">Dashboard</span>
            </a>
            <a href="?page=cluster" class="nav-tab <?= $page == 'cluster' ? 'active' : '' ?>">
                <span class="icon">🔬</span><span class="label">Analisis</span>
            </a>
            <a href="?page=kmeans" class="nav-tab <?= $page == 'kmeans' ? 'active' : '' ?>">
                <span class="icon">🧮</span><span class="label">K-Means</span>
            </a>
            <a href="?page=data" class="nav-tab <?= $page == 'data' ? 'active' : '' ?>">
                <span class="icon">🏛️</span><span class="label">Data</span>
            </a>
        </div>
    </nav>

    <?php if ($page === 'map'): ?>
        <!-- ============================================================ MAP PAGE ============================================================ -->
        <div class="page map-page">
            <div class="map-layout">
                <!-- Sidebar -->
                <div class="map-sidebar">
                    <div class="sidebar-header">
                        <div class="sidebar-title">🏔️ Destinasi Wisata</div>
                        <input type="text" class="search-box" id="searchInput" placeholder="🔍 Cari destinasi...">
                        <div class="filter-chips" id="filterChips">
                            <div class="chip all active" data-filter="all">Semua</div>
                            <?php foreach ($clusterMeta as $i => $cm): ?>
                                <div class="chip" data-filter="<?= $i ?>" style="border-color:<?= $cm['color'] ?>22;color:<?= $cm['color'] ?>;background:<?= $cm['color'] ?>15;">
                                    <?= $cm['icon'] ?> C<?= $i + 1 ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="dest-list" id="destList">
                        <?php foreach ($destinations as $d):
                            $cm = $clusterMeta[$d['cluster_display']];
                        ?>
                            <div class="dest-item" data-id="<?= $d['id'] ?>" data-cluster="<?= $d['cluster_display'] ?>" data-name="<?= strtolower($d['name']) ?>">
                                <div class="dest-name"><?= $d['name'] ?></div>
                                <div class="dest-meta">
                                    <span class="badge badge-cluster" style="background:<?= $cm['color'] ?>25;color:<?= $cm['color'] ?>;">
                                        <?= $cm['icon'] ?> <?= $cm['label'] ?>
                                    </span>
                                    <span class="badge" style="background:var(--bg3);color:var(--muted);"><?= $d['category'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Map -->
                <div id="map"></div>
            </div>
        </div>

        <script>
            const destinations = <?= json_encode($destinations) ?>;
            const clusterMeta = <?= json_encode($clusterMeta) ?>;

            const map = L.map('map', {
                center: [-7.5500, 110.2150],
                zoom: 12,
                zoomControl: false
            });

            L.control.zoom({
                position: 'bottomright'
            }).addTo(map);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '© OpenStreetMap contributors © CARTO',
                maxZoom: 19
            }).addTo(map);

            function makeIcon(color) {
                return L.divIcon({
                    html: `<div style="width:14px;height:14px;border-radius:50%;background:${color};border:2px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.5);"></div>`,
                    className: '',
                    iconSize: [14, 14],
                    iconAnchor: [7, 7],
                    popupAnchor: [0, -10]
                });
            }

            const markers = {};
            const layerGroups = {};
            for (let i = 0; i < 4; i++) layerGroups[i] = L.layerGroup().addTo(map);

            destinations.forEach(d => {
                const cm = clusterMeta[d.cluster_display];
                const m = L.marker([d.lat, d.lng], {
                    icon: makeIcon(cm.color)
                });

                const stars = (score) => '★'.repeat(score) + '☆'.repeat(5 - score);
                m.bindPopup(`
    <div style="min-width:220px;">
      <div class="popup-cluster" style="background:${cm.color}20;color:${cm.color};">
        ${cm.icon} ${cm.label}
      </div>
      <div class="popup-title">${d.name}</div>
      <div class="popup-desc">${d.desc}</div>
      <div class="popup-attrs">
        <div class="popup-attr"><span class="popup-attr-label">Daya Tarik</span><div class="popup-attr-val">${d.a1}/5</div></div>
        <div class="popup-attr"><span class="popup-attr-label">Aksesibilitas</span><div class="popup-attr-val">${d.a2}/5</div></div>
        <div class="popup-attr"><span class="popup-attr-label">Fasilitas</span><div class="popup-attr-val">${d.a3}/5</div></div>
        <div class="popup-attr"><span class="popup-attr-label">Aktivitas</span><div class="popup-attr-val">${d.a4}/5</div></div>
        <div class="popup-attr" style="grid-column:span 2;"><span class="popup-attr-label">Pengunjung/bln</span><div class="popup-attr-val" style="font-size:20px;">${d.a5.toLocaleString()}</div></div>
      </div>
      <div style="margin-top:8px;font-size:11px;color:var(--muted);">📍 ${d.kecamatan} | ${d.category}</div>
    </div>
  `, {
                    maxWidth: 260
                });

                layerGroups[d.cluster_display].addLayer(m);
                markers[d.id] = {
                    marker: m,
                    cluster: d.cluster_display
                };
            });

            // Sidebar interaction
            let activeFilter = 'all';
            document.querySelectorAll('.chip').forEach(chip => {
                chip.addEventListener('click', () => {
                    document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
                    chip.classList.add('active');
                    activeFilter = chip.dataset.filter;
                    filterDests();
                });
            });

            document.getElementById('searchInput').addEventListener('input', filterDests);

            function filterDests() {
                const q = document.getElementById('searchInput').value.toLowerCase();
                document.querySelectorAll('.dest-item').forEach(item => {
                    const nameMatch = item.dataset.name.includes(q);
                    const clusterMatch = activeFilter === 'all' || item.dataset.cluster === activeFilter;
                    item.style.display = (nameMatch && clusterMatch) ? '' : 'none';
                });
            }

            document.querySelectorAll('.dest-item').forEach(item => {
                item.addEventListener('click', () => {
                    const id = parseInt(item.dataset.id);
                    const {
                        marker
                    } = markers[id];
                    document.querySelectorAll('.dest-item').forEach(i => i.classList.remove('active'));
                    item.classList.add('active');
                    map.setView(marker.getLatLng(), 15, {
                        animate: true
                    });
                    marker.openPopup();
                });
            });

            // Add legend
            const legend = L.control({
                position: 'bottomleft'
            });
            legend.onAdd = () => {
                const div = L.DomUtil.create('div');
                div.style.cssText = 'background:rgba(10,15,30,0.9);border:1px solid rgba(148,163,184,.15);border-radius:12px;padding:12px;backdrop-filter:blur(10px);';
                let html = '<div style="font-size:11px;font-weight:700;color:#94a3b8;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Klaster Wisata</div>';
                clusterMeta.forEach((cm, i) => {
                    html += `<div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
      <div style="width:10px;height:10px;border-radius:50%;background:${cm.color};border:1.5px solid white;flex-shrink:0;"></div>
      <span style="font-size:11px;color:#f1f5f9;font-weight:500;">${cm.label}</span>
    </div>`;
                });
                div.innerHTML = html;
                return div;
            };
            legend.addTo(map);
        </script>

    <?php elseif ($page === 'dashboard'): ?>
        <!-- ============================================================ DASHBOARD PAGE ============================================================ -->
        <div class="page">
            <div class="container">
                <div style="margin-bottom:20px;">
                    <div class="section-title">📊 Dashboard Smart Tourism</div>
                    <div class="section-sub">Kota Magelang — Sistem Cerdas Pemetaan Potensi Wisata 2026</div>
                </div>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card c-blue">
                        <div class="stat-label">Total Destinasi</div>
                        <div class="stat-value"><?= count($destinations) ?></div>
                        <div class="stat-sub">Tersebar di Kota Magelang</div>
                    </div>
                    <div class="stat-card c-purple">
                        <div class="stat-label">Klaster Aktif</div>
                        <div class="stat-value">4</div>
                        <div class="stat-sub">K-Means Clustering</div>
                    </div>
                    <div class="stat-card c-gold">
                        <div class="stat-label">Pengunjung/bln</div>
                        <div class="stat-value"><?= number_format(array_sum(array_column($destinations, 'a5'))) ?></div>
                        <div class="stat-sub">Total estimasi</div>
                    </div>
                    <div class="stat-card c-green">
                        <div class="stat-label">Kategori</div>
                        <div class="stat-value"><?= count(array_unique(array_column($destinations, 'category'))) ?></div>
                        <div class="stat-sub">Jenis destinasi wisata</div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid-2" style="margin-bottom:16px;">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">🍩 Distribusi Kategori</div>
                        </div>
                        <div class="card-body" style="display:flex;justify-content:center;">
                            <canvas id="catChart" style="max-height:220px;max-width:220px;"></canvas>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">🔵 Distribusi Klaster</div>
                        </div>
                        <div class="card-body" style="display:flex;justify-content:center;">
                            <canvas id="clusterChart" style="max-height:220px;max-width:220px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Visitor Chart -->
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-title">👥 Top 10 Pengunjung Tertinggi</div>
                    </div>
                    <div class="card-body">
                        <div class="matrix-wrap">
                            <canvas id="visitorChart" style="min-width:400px;height:240px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Cluster Overview -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">🗂️ Ringkasan per Klaster</div>
                    </div>
                    <div class="card-body">
                        <div class="cluster-cards">
                            <?php foreach ($clusterMeta as $i => $cm):
                                $members = array_filter($destinations, fn($d) => $d['cluster_display'] == $i);
                                $count = count($members);
                                $avgA1 = $count ? round(array_sum(array_column($members, 'a1')) / $count, 1) : 0;
                                $avgVisit = $count ? round(array_sum(array_column($members, 'a5')) / $count) : 0;
                            ?>
                                <div class="cluster-card" style="border-color:<?= $cm['color'] ?>33;">
                                    <div class="cluster-emoji"><?= $cm['icon'] ?></div>
                                    <div class="cluster-name" style="color:<?= $cm['color'] ?>;"><?= $cm['label'] ?></div>
                                    <div class="cluster-count" style="color:<?= $cm['color'] ?>;"><?= $count ?></div>
                                    <div style="font-size:10px;color:var(--muted);margin-top:4px;">destinasi</div>
                                    <div style="margin-top:10px;font-size:11px;color:var(--muted);">Avg Daya Tarik: <b style="color:var(--text);"><?= $avgA1 ?>/5</b></div>
                                    <div style="font-size:11px;color:var(--muted);">Avg Pengunjung: <b style="color:var(--text);"><?= number_format($avgVisit) ?></b></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script>
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.borderColor = 'rgba(148,163,184,0.1)';
            const destinations = <?= json_encode($destinations) ?>;
            const clusterMeta = <?= json_encode($clusterMeta) ?>;

            // Category donut
            const cats = {};
            destinations.forEach(d => cats[d.category] = (cats[d.category] || 0) + 1);
            new Chart(document.getElementById('catChart'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(cats),
                    datasets: [{
                        data: Object.values(cats),
                        backgroundColor: ['#38bdf8', '#818cf8', '#10B981', '#F59E0B', '#EF4444'],
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 10
                                },
                                boxWidth: 10,
                                padding: 8
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

            // Cluster donut
            const clusterCounts = [0, 0, 0, 0];
            destinations.forEach(d => clusterCounts[d.cluster_display]++);
            new Chart(document.getElementById('clusterChart'), {
                type: 'doughnut',
                data: {
                    labels: clusterMeta.map(c => c.label),
                    datasets: [{
                        data: clusterCounts,
                        backgroundColor: clusterMeta.map(c => c.color),
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 10
                                },
                                boxWidth: 10,
                                padding: 8
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

            // Visitor bar
            const top10 = [...destinations].sort((a, b) => b.a5 - a.a5).slice(0, 10);
            new Chart(document.getElementById('visitorChart'), {
                type: 'bar',
                data: {
                    labels: top10.map(d => d.name.length > 20 ? d.name.slice(0, 20) + '…' : d.name),
                    datasets: [{
                        label: 'Pengunjung/bln',
                        data: top10.map(d => d.a5),
                        backgroundColor: top10.map(d => clusterMeta[d.cluster_display].color + 'cc'),
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        </script>

    <?php elseif ($page === 'cluster'): ?>
        <!-- ============================================================ CLUSTER ANALYSIS PAGE ============================================================ -->
        <div class="page">
            <div class="container">
                <div style="margin-bottom:20px;">
                    <div class="section-title">🔬 Analisis Klaster</div>
                    <div class="section-sub">Hasil K-Means Clustering Destinasi Wisata Kota Magelang</div>
                </div>

                <!-- Elbow + Silhouette -->
                <div class="grid-2" style="margin-bottom:16px;">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">📈 Elbow Method</div>
                        </div>
                        <div class="card-body">
                            <canvas id="elbowChart" style="height:200px;"></canvas>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">📊 Atribut per Klaster</div>
                        </div>
                        <div class="card-body">
                            <canvas id="radarChart" style="height:200px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Scatter Plot -->
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-title">🔵 Scatter Plot Klaster (A1 vs A5)</div>
                    </div>
                    <div class="card-body">
                        <canvas id="scatterChart" style="height:240px;"></canvas>
                    </div>
                </div>

                <!-- Cluster Details -->
                <?php foreach ($clusterMeta as $i => $cm):
                    $members = array_filter($destinations, fn($d) => $d['cluster_display'] == $i);
                    $members = array_values($members);
                    $count = count($members);
                    if ($count == 0) continue;
                    $avgA1 = round(array_sum(array_column($members, 'a1')) / $count, 2);
                    $avgA2 = round(array_sum(array_column($members, 'a2')) / $count, 2);
                    $avgA3 = round(array_sum(array_column($members, 'a3')) / $count, 2);
                    $avgA4 = round(array_sum(array_column($members, 'a4')) / $count, 2);
                    $avgA5 = round(array_sum(array_column($members, 'a5')) / $count);
                    $averages = [
                        'a1' => $avgA1,
                        'a2' => $avgA2,
                        'a3' => $avgA3,
                        'a4' => $avgA4,
                    ];
                ?>
                    <div class="card" style="margin-bottom:16px;border-color:<?= $cm['color'] ?>33;">
                        <div class="card-header" style="background:<?= $cm['color'] ?>10;">
                            <div class="card-title">
                                <?= $cm['icon'] ?> <?= $cm['label'] ?>
                                <span class="pill" style="background:<?= $cm['color'] ?>20;color:<?= $cm['color'] ?>;"><?= $count ?> destinasi</span>
                            </div>
                            <div style="font-size:12px;color:var(--muted);">Centroid: A1=<?= $avgA1 ?> A2=<?= $avgA2 ?> A3=<?= $avgA3 ?> A4=<?= $avgA4 ?> A5=<?= $avgA5 ?></div>
                        </div>
                        <div class="card-body">
                            <!-- Attribute bars -->
                            <div class="bar-chart" style="margin-bottom:16px;">
                                <?php foreach (['a1' => 'Daya Tarik', 'a2' => 'Aksesibilitas', 'a3' => 'Fasilitas', 'a4' => 'Aktivitas'] as $key => $label): ?>
                                    <div class="bar-row">
                                        <div class="bar-label"><?= $label ?></div>
                                        <div class="bar-wrap">
                                            <?php $value = $averages[$key] ?? 0; ?>
                                            <div class="bar-fill" style="width:<?= $value / 5 * 100 ?>%;background:<?= $cm['color'] ?>;">
                                                <?= $value ?>
                                            </div>
                                        </div>
                                        <div style="font-size:12px;font-weight:700;color:<?= $cm['color'] ?>;width:30px;text-align:right;"><?= $value ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- Destination list -->
                            <div class="matrix-wrap">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Destinasi</th>
                                            <th>Kategori</th>
                                            <th>A1</th>
                                            <th>A2</th>
                                            <th>A3</th>
                                            <th>A4</th>
                                            <th>A5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($members as $d): ?>
                                            <tr>
                                                <td><b><?= $d['name'] ?></b></td>
                                                <td><span class="badge" style="background:var(--bg3);color:var(--muted);"><?= $d['category'] ?></span></td>
                                                <td><?= $d['a1'] ?></td>
                                                <td><?= $d['a2'] ?></td>
                                                <td><?= $d['a3'] ?></td>
                                                <td><?= $d['a4'] ?></td>
                                                <td><?= number_format($d['a5']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <script>
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.borderColor = 'rgba(148,163,184,0.1)';
            const destinations = <?= json_encode($destinations) ?>;
            const clusterMeta = <?= json_encode($clusterMeta) ?>;
            const elbowData = <?= json_encode($elbowData) ?>;

            // Elbow
            new Chart(document.getElementById('elbowChart'), {
                type: 'line',
                data: {
                    labels: Array.from({
                        length: 8
                    }, (_, i) => i + 1),
                    datasets: [{
                        label: 'SSE (Inertia)',
                        data: elbowData,
                        borderColor: '#38bdf8',
                        backgroundColor: 'rgba(56,189,248,.1)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#38bdf8',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        annotation: {
                            annotations: {
                                line1: {
                                    type: 'line',
                                    x: 4,
                                    borderColor: '#EF4444',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    label: {
                                        display: true,
                                        content: 'k=4 Optimal',
                                        position: 'start',
                                        color: '#EF4444',
                                        font: {
                                            size: 10
                                        }
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Jumlah Klaster (k)'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'SSE'
                            }
                        }
                    }
                }
            });

            // Radar
            const clusterCentroid = {};
            clusterMeta.forEach((cm, i) => {
                const members = destinations.filter(d => d.cluster_display === i);
                if (!members.length) return;
                clusterCentroid[i] = {
                    a1: members.reduce((s, d) => s + d.a1, 0) / members.length,
                    a2: members.reduce((s, d) => s + d.a2, 0) / members.length,
                    a3: members.reduce((s, d) => s + d.a3, 0) / members.length,
                    a4: members.reduce((s, d) => s + d.a4, 0) / members.length,
                };
            });
            new Chart(document.getElementById('radarChart'), {
                type: 'radar',
                data: {
                    labels: ['Daya Tarik', 'Aksesibilitas', 'Fasilitas', 'Aktivitas'],
                    datasets: clusterMeta.map((cm, i) => ({
                        label: cm.label,
                        data: clusterCentroid[i] ? [clusterCentroid[i].a1, clusterCentroid[i].a2, clusterCentroid[i].a3, clusterCentroid[i].a4] : [],
                        borderColor: cm.color,
                        backgroundColor: cm.color + '30',
                        pointBackgroundColor: cm.color,
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            suggestedMin: 0,
                            suggestedMax: 5,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 9
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 10
                                },
                                boxWidth: 10
                            }
                        }
                    }
                }
            });

            // Scatter
            new Chart(document.getElementById('scatterChart'), {
                type: 'scatter',
                data: {
                    datasets: clusterMeta.map((cm, i) => ({
                        label: cm.label,
                        data: destinations.filter(d => d.cluster_display === i).map(d => ({
                            x: d.a1,
                            y: d.a5,
                            name: d.name
                        })),
                        backgroundColor: cm.color + 'cc',
                        pointRadius: 7
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 10
                                },
                                boxWidth: 10
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.raw.name}: A1=${ctx.raw.x}, Pengunjung=${ctx.raw.y}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Daya Tarik (A1)'
                            },
                            min: 0,
                            max: 6
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Pengunjung/bln (A5)'
                            }
                        }
                    }
                }
            });
        </script>

    <?php elseif ($page === 'kmeans'): ?>
        <!-- ============================================================ K-MEANS MANUAL PAGE ============================================================ -->
        <div class="page">
            <div class="container">
                <div style="margin-bottom:20px;">
                    <div class="section-title">🧮 Perhitungan Manual K-Means</div>
                    <div class="section-sub">Transparansi algoritma step-by-step untuk 20 destinasi wisata Kota Magelang</div>
                </div>

                <!-- Step 1: Normalisasi -->
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-title"><span class="step-badge">1</span> Normalisasi Data (Min-Max Scaling)</div>
                    </div>
                    <div class="card-body">
                        <div class="formula-box" style="margin-bottom:16px;">
                            <span class="formula-comment"># Formula Min-Max Normalization:</span><br>
                            x_norm = (x - x_min) / (x_max - x_min)<br><br>
                            <span class="formula-comment"># Nilai Min-Max per atribut:</span><br>
                            <?php foreach ($cols as $c => $col):
                                $vals = array_column($destinations, $col);
                            ?>
                                <?= $col ?> → min=<?= min($vals) ?>, max=<?= max($vals) ?><br>
                            <?php endforeach; ?>
                        </div>
                        <div class="matrix-wrap">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Destinasi</th>
                                        <th>A1</th>
                                        <th>A2</th>
                                        <th>A3</th>
                                        <th>A4</th>
                                        <th>A5</th>
                                        <th>A1_norm</th>
                                        <th>A2_norm</th>
                                        <th>A3_norm</th>
                                        <th>A4_norm</th>
                                        <th>A5_norm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($normalized as $i => $d): ?>
                                        <tr>
                                            <td style="font-weight:600;white-space:nowrap;"><?= $d['name'] ?></td>
                                            <td><?= $d['a1'] ?></td>
                                            <td><?= $d['a2'] ?></td>
                                            <td><?= $d['a3'] ?></td>
                                            <td><?= $d['a4'] ?></td>
                                            <td><?= number_format($d['a5']) ?></td>
                                            <td style="color:var(--accent);"><?= round($d['a1_norm'], 4) ?></td>
                                            <td style="color:var(--accent);"><?= round($d['a2_norm'], 4) ?></td>
                                            <td style="color:var(--accent);"><?= round($d['a3_norm'], 4) ?></td>
                                            <td style="color:var(--accent);"><?= round($d['a4_norm'], 4) ?></td>
                                            <td style="color:var(--accent);"><?= round($d['a5_norm'], 4) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Inisialisasi -->
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-title"><span class="step-badge">2</span> Inisialisasi Centroid (K-Means++)</div>
                    </div>
                    <div class="card-body">
                        <div class="formula-box" style="margin-bottom:16px;">
                            <span class="formula-comment"># K-Means++ memilih centroid awal dengan probabilitas proporsional terhadap jarak²</span><br>
                            P(x) = D(x)² / Σ D(x)²<br><br>
                            <span class="formula-comment"># k = 4, seed = 42</span><br>
                            Centroid awal dipilih secara acak berbobot untuk distribusi optimal
                        </div>
                        <?php if (!empty($iterations)): ?>
                            <div class="matrix-wrap">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Centroid</th>
                                            <th>A1_norm</th>
                                            <th>A2_norm</th>
                                            <th>A3_norm</th>
                                            <th>A4_norm</th>
                                            <th>A5_norm</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($iterations[0]['centroids'] as $ci => $c):
                                            $cm = $clusterMeta[$ci];
                                        ?>
                                            <tr>
                                                <td><span class="pill" style="background:<?= $cm['color'] ?>20;color:<?= $cm['color'] ?>;"><?= $cm['icon'] ?> C<?= $ci + 1 ?></span></td>
                                                <?php foreach ($cols as $col): ?>
                                                    <td><?= $c[$col . '_norm'] ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Step 3: Iterasi -->
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-title"><span class="step-badge">3</span> Iterasi K-Means</div>
                    </div>
                    <div class="card-body">
                        <div class="formula-box" style="margin-bottom:16px;">
                            <span class="formula-comment"># Jarak Euclidean ke Centroid:</span><br>
                            d(x,c) = √[ Σ(xi - ci)² ]<br><br>
                            <span class="formula-comment"># Assignment: cluster = argmin d(x, cj)</span><br>
                            <span class="formula-comment"># Update centroid: c = mean(anggota klaster)</span>
                        </div>

                        <div class="iteration-tabs" id="iterTabs">
                            <?php foreach ($iterations as $it => $iter): ?>
                                <button class="iter-tab <?= $it == 0 ? 'active' : '' ?>" onclick="showIter(<?= $it ?>)">
                                    Iter <?= $iter['iteration'] ?>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <?php foreach ($iterations as $it => $iter): ?>
                            <div class="iter-content <?= $it == 0 ? 'active' : '' ?>" id="iter<?= $it ?>">
                                <div style="margin-bottom:12px;font-size:13px;color:var(--muted);">
                                    <b style="color:var(--text);">Iterasi <?= $iter['iteration'] ?></b> —
                                    Menghitung jarak semua titik ke <?= count($iter['centroids']) ?> centroid
                                </div>

                                <!-- Centroid positions -->
                                <div style="margin-bottom:12px;">
                                    <div style="font-size:12px;font-weight:600;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Posisi Centroid</div>
                                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                        <?php foreach ($iter['centroids'] as $ci => $c):
                                            $cm = $clusterMeta[$ci];
                                        ?>
                                            <div style="background:<?= $cm['color'] ?>15;border:1px solid <?= $cm['color'] ?>33;border-radius:10px;padding:8px 12px;font-size:11px;">
                                                <div style="font-weight:700;color:<?= $cm['color'] ?>;margin-bottom:4px;"><?= $cm['icon'] ?> C<?= $ci + 1 ?></div>
                                                <?php foreach ($cols as $col): ?>
                                                    <div style="color:var(--muted);"><?= strtoupper($col) ?>: <b style="color:var(--text);"><?= $c[$col . '_norm'] ?></b></div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="matrix-wrap">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th>Destinasi</th>
                                                <?php foreach ($iter['centroids'] as $ci => $c): ?>
                                                    <th>d(C<?= $ci + 1 ?>)</th>
                                                <?php endforeach; ?>
                                                <th>Klaster</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($normalized as $i => $d):
                                                $assignedCluster = $iter['assignments'][$i] ?? 0;
                                                $cm = $clusterMeta[$assignedCluster];
                                                $dists = $iter['distances'][$i] ?? [];
                                            ?>
                                                <tr>
                                                    <td style="font-weight:600;white-space:nowrap;"><?= $d['name'] ?></td>
                                                    <?php foreach ($dists as $di => $dist): ?>
                                                        <td style="<?= $di == $assignedCluster ? 'color:var(--green);font-weight:700;' : '' ?>"><?= $dist ?></td>
                                                    <?php endforeach; ?>
                                                    <td>
                                                        <span class="pill" style="background:<?= $cm['color'] ?>20;color:<?= $cm['color'] ?>;">
                                                            <?= $cm['icon'] ?> C<?= $assignedCluster + 1 ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Step 4: Hasil Akhir -->
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-title"><span class="step-badge">4</span> Hasil Akhir Klasterisasi</div>
                    </div>
                    <div class="card-body">
                        <div class="formula-box" style="margin-bottom:16px;">
                            <span class="formula-comment"># Total iterasi: <?= count($iterations) ?></span><br>
                            <span class="formula-comment"># Konvergensi: centroid tidak berubah signifikan (threshold: 0.0001)</span><br>
                            <span class="formula-comment"># SSE Final: <?= round(computeSSE($normalized, $assignments, $centroids, $cols), 4) ?></span><br>
                            <span class="formula-comment"># k optimal berdasarkan Elbow Method: k = 4</span>
                        </div>
                        <div class="matrix-wrap">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Destinasi</th>
                                        <th>Kategori</th>
                                        <th>A1</th>
                                        <th>A2</th>
                                        <th>A3</th>
                                        <th>A4</th>
                                        <th>A5</th>
                                        <th>Klaster</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($destinations as $i => $d):
                                        $cm = $clusterMeta[$d['cluster_display']];
                                    ?>
                                        <tr>
                                            <td style="color:var(--muted);"><?= $i + 1 ?></td>
                                            <td style="font-weight:600;"><?= $d['name'] ?></td>
                                            <td><?= $d['category'] ?></td>
                                            <td><?= $d['a1'] ?></td>
                                            <td><?= $d['a2'] ?></td>
                                            <td><?= $d['a3'] ?></td>
                                            <td><?= $d['a4'] ?></td>
                                            <td><?= number_format($d['a5']) ?></td>
                                            <td>
                                                <span class="pill" style="background:<?= $cm['color'] ?>20;color:<?= $cm['color'] ?>;">
                                                    <?= $cm['icon'] ?> <?= $cm['label'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Evaluasi -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><span class="step-badge">5</span> Evaluasi Kualitas Klaster</div>
                    </div>
                    <div class="card-body">
                        <div class="grid-2">
                            <div>
                                <div style="font-size:13px;font-weight:600;margin-bottom:12px;">Elbow Method — SSE per k</div>
                                <?php foreach ($elbowData as $k => $sse): ?>
                                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                                        <span style="font-size:12px;font-weight:700;width:20px;color:<?= $k == 3 ? 'var(--red)' : 'var(--muted)' ?>;">k=<?= $k + 1 ?></span>
                                        <div style="flex:1;background:var(--bg3);border-radius:4px;overflow:hidden;">
                                            <div style="height:16px;background:<?= $k == 3 ? 'var(--red)' : 'var(--accent)' ?>;width:<?= round($sse / $elbowData[0] * 100) ?>%;border-radius:4px;"></div>
                                        </div>
                                        <span style="font-size:11px;color:var(--muted);width:60px;text-align:right;"><?= number_format($sse, 3) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:600;margin-bottom:12px;">Centroid Akhir (Normalized)</div>
                                <?php foreach ($centroids as $ci => $c):
                                    $cm = $clusterMeta[$ci];
                                ?>
                                    <div style="background:<?= $cm['color'] ?>10;border:1px solid <?= $cm['color'] ?>30;border-radius:10px;padding:10px;margin-bottom:8px;">
                                        <div style="font-weight:700;color:<?= $cm['color'] ?>;font-size:13px;margin-bottom:6px;"><?= $cm['icon'] ?> Klaster <?= $ci + 1 ?>: <?= $cm['label'] ?></div>
                                        <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                            <?php foreach ($cols as $col): ?>
                                                <span style="background:var(--bg3);border-radius:6px;padding:3px 8px;font-size:11px;">
                                                    <?= strtoupper($col) ?>: <b style="color:var(--text);"><?= round($c[$col . '_norm'], 4) ?></b>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script>
            function showIter(n) {
                document.querySelectorAll('.iter-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.iter-content').forEach(c => c.classList.remove('active'));
                document.querySelectorAll('.iter-tab')[n].classList.add('active');
                document.getElementById('iter' + n).classList.add('active');
            }
        </script>

    <?php elseif ($page === 'data'): ?>
        <!-- ============================================================ DATA MANAGEMENT PAGE ============================================================ -->
        <div class="page">
            <div class="container">
                <div style="margin-bottom:20px;">
                    <div class="section-title">🏛️ Manajemen Data Wisata</div>
                    <div class="section-sub">Data master destinasi wisata Kota Magelang</div>
                </div>

                <!-- Quick Add Form -->
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <div class="card-title">➕ Tambah Destinasi</div>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nama Destinasi</label>
                                <input class="form-control" placeholder="Contoh: Candi Borobudur">
                            </div>
                            <div class="form-group">
                                <label>Kategori</label>
                                <select class="form-control">
                                    <option>Budaya</option>
                                    <option>Alam</option>
                                    <option>Religi</option>
                                    <option>Desa Wisata</option>
                                    <option>Kuliner</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kecamatan</label>
                                <input class="form-control" placeholder="Kecamatan">
                            </div>
                            <div class="form-group">
                                <label>Pengunjung/bulan (A5)</label>
                                <input class="form-control" type="number" placeholder="0">
                            </div>
                            <div class="form-group">
                                <label>Daya Tarik A1 (1-5)</label>
                                <input class="form-control" type="number" min="1" max="5" placeholder="1-5">
                            </div>
                            <div class="form-group">
                                <label>Aksesibilitas A2 (1-5)</label>
                                <input class="form-control" type="number" min="1" max="5" placeholder="1-5">
                            </div>
                            <div class="form-group">
                                <label>Fasilitas A3 (1-5)</label>
                                <input class="form-control" type="number" min="1" max="5" placeholder="1-5">
                            </div>
                            <div class="form-group">
                                <label>Aktivitas A4 (1-5)</label>
                                <input class="form-control" type="number" min="1" max="5" placeholder="1-5">
                            </div>
                            <div class="form-group">
                                <label>Latitude</label>
                                <input class="form-control" placeholder="-7.xxxx">
                            </div>
                            <div class="form-group">
                                <label>Longitude</label>
                                <input class="form-control" placeholder="110.xxxx">
                            </div>
                            <div class="form-group" style="grid-column:1/-1;">
                                <label>Deskripsi</label>
                                <input class="form-control" placeholder="Deskripsi singkat destinasi">
                            </div>
                        </div>
                        <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap;">
                            <button class="btn btn-primary" onclick="alert('Fitur simpan memerlukan database. Hubungkan ke MySQL/PostgreSQL.')">💾 Simpan Destinasi</button>
                            <button class="btn" style="background:var(--bg3);color:var(--muted);border:1px solid var(--border2);" onclick="alert('Import CSV: format A1,A2,A3,A4,A5,lat,lng,nama,kategori')">📂 Import CSV</button>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">📋 Data Destinasi (<?= count($destinations) ?> total)</div>
                        <div style="display:flex;gap:8px;">
                            <input type="text" class="search-box" id="tableSearch" placeholder="🔍 Cari..." style="width:160px;padding:7px 12px;">
                            <button class="btn btn-sm btn-primary" onclick="exportCSV()">⬇️ CSV</button>
                        </div>
                    </div>
                    <div class="card-body" style="padding:0;">
                        <div class="matrix-wrap">
                            <table class="data-table" id="mainTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Kecamatan</th>
                                        <th>A1</th>
                                        <th>A2</th>
                                        <th>A3</th>
                                        <th>A4</th>
                                        <th>A5</th>
                                        <th>Klaster</th>
                                        <th>Lat</th>
                                        <th>Lng</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($destinations as $d):
                                        $cm = $clusterMeta[$d['cluster_display']];
                                    ?>
                                        <tr>
                                            <td style="color:var(--muted);"><?= $d['id'] ?></td>
                                            <td style="font-weight:600;white-space:nowrap;"><?= $d['name'] ?></td>
                                            <td><?= $d['category'] ?></td>
                                            <td style="white-space:nowrap;"><?= $d['kecamatan'] ?></td>
                                            <td><?= $d['a1'] ?></td>
                                            <td><?= $d['a2'] ?></td>
                                            <td><?= $d['a3'] ?></td>
                                            <td><?= $d['a4'] ?></td>
                                            <td><?= number_format($d['a5']) ?></td>
                                            <td>
                                                <span class="pill" style="background:<?= $cm['color'] ?>20;color:<?= $cm['color'] ?>;">
                                                    <?= $cm['icon'] ?> C<?= $d['cluster_display'] + 1 ?>
                                                </span>
                                            </td>
                                            <td style="color:var(--muted);font-size:11px;"><?= $d['lat'] ?></td>
                                            <td style="color:var(--muted);font-size:11px;"><?= $d['lng'] ?></td>
                                            <td>
                                                <div style="display:flex;gap:4px;">
                                                    <button class="btn btn-sm" style="background:var(--bg3);color:var(--accent);border:1px solid var(--border2);"
                                                        onclick="viewOnMap(<?= $d['lat'] ?>,<?= $d['lng'] ?>,<?= $d['id'] ?>)">🗺️</button>
                                                    <button class="btn btn-sm btn-danger" onclick="if(confirm('Hapus?')) alert('Memerlukan database')">🗑️</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Schema Info -->
                <div class="card" style="margin-top:16px;">
                    <div class="card-header">
                        <div class="card-title">🗄️ Skema Database</div>
                    </div>
                    <div class="card-body">
                        <div class="formula-box">
                            <span class="formula-comment">-- destinations (tabel master)</span><br>
                            CREATE TABLE destinations (<br>
                            &nbsp;&nbsp;id INT PRIMARY KEY AUTO_INCREMENT,<br>
                            &nbsp;&nbsp;name VARCHAR(200) NOT NULL,<br>
                            &nbsp;&nbsp;category ENUM('Budaya','Alam','Religi','Desa Wisata','Kuliner'),<br>
                            &nbsp;&nbsp;kecamatan VARCHAR(100),<br>
                            &nbsp;&nbsp;lat DECIMAL(10,6), lng DECIMAL(10,6),<br>
                            &nbsp;&nbsp;a1 TINYINT, a2 TINYINT, a3 TINYINT, a4 TINYINT,<br>
                            &nbsp;&nbsp;a5 INT, -- pengunjung per bulan<br>
                            &nbsp;&nbsp;description TEXT, created_at TIMESTAMP DEFAULT NOW()<br>
                            );<br><br>
                            <span class="formula-comment">-- cluster_results (hasil K-Means)</span><br>
                            CREATE TABLE cluster_results (<br>
                            &nbsp;&nbsp;id INT PRIMARY KEY AUTO_INCREMENT,<br>
                            &nbsp;&nbsp;destination_id INT REFERENCES destinations(id),<br>
                            &nbsp;&nbsp;cluster_id TINYINT,<br>
                            &nbsp;&nbsp;distance_to_centroid DECIMAL(10,6),<br>
                            &nbsp;&nbsp;analysis_id INT, created_at TIMESTAMP DEFAULT NOW()<br>
                            );
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script>
            const destinations = <?= json_encode($destinations) ?>;
            document.getElementById('tableSearch').addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('#mainTable tbody tr').forEach(tr => {
                    tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });

            function viewOnMap(lat, lng, id) {
                window.location.href = '?page=map';
                sessionStorage.setItem('focusDest', id);
            }

            function exportCSV() {
                const rows = [
                    ['ID', 'Nama', 'Kategori', 'Kecamatan', 'A1', 'A2', 'A3', 'A4', 'A5', 'Klaster', 'Lat', 'Lng']
                ];
                destinations.forEach(d => {
                    const cm = ['Potensi Tinggi', 'Potensi Sedang', 'Potensi Berkembang', 'Potensi Khusus'];
                    rows.push([d.id, d.name, d.category, d.kecamatan, d.a1, d.a2, d.a3, d.a4, d.a5, cm[d.cluster_display], d.lat, d.lng]);
                });
                const csv = rows.map(r => r.map(v => `"${v}"`).join(',')).join('\n');
                const a = document.createElement('a');
                a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent('\uFEFF' + csv);
                a.download = 'wisata_magelang_kmeans.csv';
                a.click();
            }
        </script>

    <?php endif; ?>

    <!-- BOTTOM NAV (mobile) -->
    <nav class="bottom-nav">
        <a href="?page=map" class="bnav-item <?= $page == 'map' ? 'active' : '' ?>">
            <span class="bnav-icon">🗺️</span>Peta
        </a>
        <a href="?page=dashboard" class="bnav-item <?= $page == 'dashboard' ? 'active' : '' ?>">
            <span class="bnav-icon">📊</span>Dashboard
        </a>
        <a href="?page=cluster" class="bnav-item <?= $page == 'cluster' ? 'active' : '' ?>">
            <span class="bnav-icon">🔬</span>Analisis
        </a>
        <a href="?page=kmeans" class="bnav-item <?= $page == 'kmeans' ? 'active' : '' ?>">
            <span class="bnav-icon">🧮</span>K-Means
        </a>
        <a href="?page=data" class="bnav-item <?= $page == 'data' ? 'active' : '' ?>">
            <span class="bnav-icon">🏛️</span>Data
        </a>
    </nav>

</body>

</html>
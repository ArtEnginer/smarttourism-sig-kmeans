<?php
// ============================================================
// DATA POTENSI WISATA KABUPATEN MAGELANG
// ============================================================
function getWisataData()
{
    return [
        ['id' => 1, 'nama' => 'Candi Borobudur', 'jenis' => 'Budaya', 'daya_tarik' => 9, 'aksesibilitas' => 9, 'fasilitas' => 8, 'jumlah_pengunjung' => 850000, 'blok_bangunan' => 5, 'sarana' => 9, 'ulasan' => 4.8, 'rating' => 4.8],
        ['id' => 2, 'nama' => 'Candi Mendut', 'jenis' => 'Budaya', 'daya_tarik' => 8, 'aksesibilitas' => 8, 'fasilitas' => 7, 'jumlah_pengunjung' => 180000, 'blok_bangunan' => 4, 'sarana' => 7, 'ulasan' => 4.5, 'rating' => 4.5],
        ['id' => 3, 'nama' => 'Candi Pawon', 'jenis' => 'Budaya', 'daya_tarik' => 7, 'aksesibilitas' => 7, 'fasilitas' => 6, 'jumlah_pengunjung' => 95000, 'blok_bangunan' => 3, 'sarana' => 6, 'ulasan' => 4.2, 'rating' => 4.2],
        ['id' => 4, 'nama' => 'Ketep Pass', 'jenis' => 'Alam', 'daya_tarik' => 9, 'aksesibilitas' => 7, 'fasilitas' => 8, 'jumlah_pengunjung' => 320000, 'blok_bangunan' => 4, 'sarana' => 7, 'ulasan' => 4.6, 'rating' => 4.6],
        ['id' => 5, 'nama' => 'Punthuk Setumbu', 'jenis' => 'Alam', 'daya_tarik' => 9, 'aksesibilitas' => 6, 'fasilitas' => 7, 'jumlah_pengunjung' => 210000, 'blok_bangunan' => 3, 'sarana' => 6, 'ulasan' => 4.7, 'rating' => 4.7],
        ['id' => 6, 'nama' => 'Air Terjun Kedung Kayang', 'jenis' => 'Alam', 'daya_tarik' => 8, 'aksesibilitas' => 5, 'fasilitas' => 5, 'jumlah_pengunjung' => 75000, 'blok_bangunan' => 2, 'sarana' => 5, 'ulasan' => 4.3, 'rating' => 4.3],
        ['id' => 7, 'nama' => 'Desa Wisata Candirejo', 'jenis' => 'Desa Wisata', 'daya_tarik' => 7, 'aksesibilitas' => 7, 'fasilitas' => 6, 'jumlah_pengunjung' => 45000, 'blok_bangunan' => 3, 'sarana' => 6, 'ulasan' => 4.4, 'rating' => 4.4],
        ['id' => 8, 'nama' => 'Desa Wisata Ngargogondo', 'jenis' => 'Desa Wisata', 'daya_tarik' => 6, 'aksesibilitas' => 6, 'fasilitas' => 5, 'jumlah_pengunjung' => 32000, 'blok_bangunan' => 2, 'sarana' => 5, 'ulasan' => 4.1, 'rating' => 4.1],
        ['id' => 9, 'nama' => 'Masjid Agung Magelang', 'jenis' => 'Religi', 'daya_tarik' => 7, 'aksesibilitas' => 9, 'fasilitas' => 8, 'jumlah_pengunjung' => 280000, 'blok_bangunan' => 4, 'sarana' => 8, 'ulasan' => 4.5, 'rating' => 4.5],
        ['id' => 10, 'nama' => 'Makam Kyai Raden Santri', 'jenis' => 'Religi', 'daya_tarik' => 6, 'aksesibilitas' => 7, 'fasilitas' => 5, 'jumlah_pengunjung' => 120000, 'blok_bangunan' => 2, 'sarana' => 5, 'ulasan' => 4.0, 'rating' => 4.0],
        ['id' => 11, 'nama' => 'Taman Kyai Langgeng', 'jenis' => 'Taman', 'daya_tarik' => 8, 'aksesibilitas' => 9, 'fasilitas' => 8, 'jumlah_pengunjung' => 350000, 'blok_bangunan' => 4, 'sarana' => 8, 'ulasan' => 4.4, 'rating' => 4.4],
        ['id' => 12, 'nama' => 'Kebun Teh Ngluwar', 'jenis' => 'Alam', 'daya_tarik' => 7, 'aksesibilitas' => 6, 'fasilitas' => 5, 'jumlah_pengunjung' => 55000, 'blok_bangunan' => 2, 'sarana' => 5, 'ulasan' => 4.2, 'rating' => 4.2],
        ['id' => 13, 'nama' => 'Gunung Merbabu (basecamp)', 'jenis' => 'Alam', 'daya_tarik' => 9, 'aksesibilitas' => 5, 'fasilitas' => 5, 'jumlah_pengunjung' => 42000, 'blok_bangunan' => 2, 'sarana' => 4, 'ulasan' => 4.6, 'rating' => 4.6],
        ['id' => 14, 'nama' => 'Sunrise Puthuk Mongkrong', 'jenis' => 'Alam', 'daya_tarik' => 8, 'aksesibilitas' => 5, 'fasilitas' => 4, 'jumlah_pengunjung' => 28000, 'blok_bangunan' => 1, 'sarana' => 4, 'ulasan' => 4.5, 'rating' => 4.5],
        ['id' => 15, 'nama' => 'Museum Diponegoro', 'jenis' => 'Budaya', 'daya_tarik' => 6, 'aksesibilitas' => 8, 'fasilitas' => 7, 'jumlah_pengunjung' => 85000, 'blok_bangunan' => 3, 'sarana' => 7, 'ulasan' => 4.1, 'rating' => 4.1],
    ];
}

// ============================================================
// K-MEANS CLUSTERING ALGORITHM
// ============================================================
function normalizeData($data, $fields)
{
    $min = [];
    $max = [];
    foreach ($fields as $f) {
        $vals = array_column($data, $f);
        $min[$f] = min($vals);
        $max[$f] = max($vals);
    }
    $normalized = [];
    foreach ($data as $row) {
        $norm = $row;
        foreach ($fields as $f) {
            $range = $max[$f] - $min[$f];
            $norm[$f . '_norm'] = $range > 0 ? ($row[$f] - $min[$f]) / $range : 0;
        }
        $normalized[] = $norm;
    }
    return ['data' => $normalized, 'min' => $min, 'max' => $max];
}

function euclideanDistance($a, $b, $fields)
{
    $sum = 0;
    foreach ($fields as $f) {
        $diff = ($a[$f . '_norm'] ?? $a[$f]) - ($b[$f] ?? 0);
        $sum += $diff * $diff;
    }
    return sqrt($sum);
}

function kMeansClustering($data, $k = 3, $fields = [], $maxIter = 100)
{
    $n = count($data);
    $history = []; // store each iteration
    $palette = [
        '#059669',
        '#d97706',
        '#6366f1',
        '#0ea5e9',
        '#ef4444',
    ];
    $iconPalette = ['high', 'medium', 'low', 'medium', 'low'];

    // Initialize centroids - pick first k points spread out
    $step = (int)($n / $k);
    $centroids = [];
    for ($i = 0; $i < $k; $i++) {
        $idx = $i * $step;
        if ($idx >= $n) {
            $idx = $n - 1;
        }
        $centroid = [];
        foreach ($fields as $f) {
            $centroid[$f] = $data[$idx][$f . '_norm'] ?? 0;
        }
        $centroids[] = $centroid;
    }

    $assignments = array_fill(0, $n, 0);
    $converged = false;

    for ($iter = 0; $iter < $maxIter && !$converged; $iter++) {
        // Assignment step
        $newAssignments = [];
        $distanceMatrix = [];
        foreach ($data as $i => $point) {
            $minDist = PHP_FLOAT_MAX;
            $bestCluster = 0;
            $dists = [];
            foreach ($centroids as $ci => $centroid) {
                $dist = euclideanDistance($point, $centroid, $fields);
                $dists[] = round($dist, 4);
                if ($dist < $minDist) {
                    $minDist = $dist;
                    $bestCluster = $ci;
                }
            }
            $newAssignments[$i] = $bestCluster;
            $distanceMatrix[$i] = ['distances' => $dists, 'assigned' => $bestCluster, 'min_dist' => round($minDist, 4)];
        }

        // Store iteration history
        $history[] = [
            'iteration' => $iter + 1,
            'centroids' => $centroids,
            'assignments' => $newAssignments,
            'distance_matrix' => $distanceMatrix,
        ];

        // Check convergence
        if ($iter > 0 && $newAssignments === $assignments) {
            $converged = true;
        }
        $assignments = $newAssignments;

        // Update step: recalculate centroids
        $newCentroids = array_fill(0, $k, array_fill_keys($fields, 0));
        $counts = array_fill(0, $k, 0);
        foreach ($data as $i => $point) {
            $c = $assignments[$i];
            foreach ($fields as $f) {
                $newCentroids[$c][$f] += $point[$f . '_norm'];
            }
            $counts[$c]++;
        }
        for ($c = 0; $c < $k; $c++) {
            if ($counts[$c] > 0) {
                foreach ($fields as $f) {
                    $newCentroids[$c][$f] /= $counts[$c];
                }
            }
        }

        // Check centroid convergence
        $centroidChanged = false;
        for ($c = 0; $c < $k; $c++) {
            foreach ($fields as $f) {
                if (abs($newCentroids[$c][$f] - $centroids[$c][$f]) > 0.0001) {
                    $centroidChanged = true;
                    break;
                }
            }
        }
        if (!$centroidChanged && $iter > 0) $converged = true;
        $centroids = $newCentroids;
    }

    // Determine cluster labels based on average normalized values
    $clusterSums = array_fill(0, $k, 0);
    $clusterCounts = array_fill(0, $k, 0);
    foreach ($assignments as $i => $c) {
        $sum = 0;
        foreach ($fields as $f) {
            $sum += $data[$i][$f . '_norm'];
        }
        $clusterSums[$c] += $sum / count($fields);
        $clusterCounts[$c]++;
    }
    $clusterAvg = [];
    for ($c = 0; $c < $k; $c++) {
        $clusterAvg[$c] = $clusterCounts[$c] > 0 ? $clusterSums[$c] / $clusterCounts[$c] : 0;
    }
    arsort($clusterAvg);
    $rank = array_keys($clusterAvg);
    $labels = [];
    $colors = [];
    $icons = [];
    $labelNames = ['Prioritas Tinggi', 'Prioritas Sedang', 'Prioritas Rendah'];
    foreach ($rank as $ri => $ci) {
        $labels[$ci] = $labelNames[$ri] ?? ('Cluster ' . ($ri + 1));
        $colors[$ci] = $palette[$ri] ?? $palette[$ri % count($palette)];
        $icons[$ci] = $iconPalette[$ri] ?? ($ri === 0 ? 'high' : 'low');
    }

    // Attach cluster info to data
    $result = [];
    foreach ($data as $i => $point) {
        $c = $assignments[$i];
        $point['cluster'] = $c;
        $point['cluster_label'] = $labels[$c];
        $point['cluster_color'] = $colors[$c];
        $point['cluster_icon'] = $icons[$c];
        $result[] = $point;
    }

    // Compute SSE (Within-Cluster Sum of Squared Errors)
    $sse = 0;
    foreach ($result as $point) {
        $c = $point['cluster'];
        foreach ($fields as $f) {
            $diff = $point[$f . '_norm'] - $centroids[$c][$f];
            $sse += $diff * $diff;
        }
    }

    return [
        'data' => $result,
        'centroids' => $centroids,
        'history' => $history,
        'iterations' => count($history),
        'converged' => $converged,
        'sse' => round($sse, 4),
        'labels' => $labels,
        'colors' => $colors,
        'icons' => $icons,
        'k' => $k,
        'fields' => $fields,
        'init_step' => max(1, $step),
    ];
}

function runKMeans($k = 3)
{
    $raw = getWisataData();
    $fields = ['daya_tarik', 'aksesibilitas', 'fasilitas', 'sarana', 'ulasan'];

    // Normalize jumlah_pengunjung into scale 1-10 for better comparison
    foreach ($raw as &$r) {
        $r['pengunjung_norm_raw'] = $r['jumlah_pengunjung'];
    }

    $normResult = normalizeData($raw, $fields);
    $normalized = $normResult['data'];

    $result = kMeansClustering($normalized, $k, $fields);
    $result['norm_meta'] = $normResult;
    $result['fields_label'] = [
        'daya_tarik' => 'Daya Tarik',
        'aksesibilitas' => 'Aksesibilitas',
        'fasilitas' => 'Fasilitas',
        'sarana' => 'Sarana',
        'ulasan' => 'Ulasan',
    ];
    return $result;
}

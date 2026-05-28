<?php

declare(strict_types=1);

require_once __DIR__ . '/database.php';

function getWisataData(): array
{
    try {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->query(
            "SELECT
                id,
                nama_destinasi AS nama,
                kategori AS jenis,
                daya_tarik,
                aksesibilitas,
                fasilitas,
                sarana,
                ulasan,
                jumlah_pengunjung,
                rating,
                latitude AS lat,
                longitude AS lng
            FROM tb_destinasi
            WHERE status = 'aktif'
            ORDER BY id ASC"
        );

        $rows = $stmt->fetchAll();

        return array_map(static function (array $row): array {
            return [
                'id' => (int) $row['id'],
                'nama' => (string) $row['nama'],
                'jenis' => (string) $row['jenis'],
                'daya_tarik' => (float) $row['daya_tarik'],
                'aksesibilitas' => (float) $row['aksesibilitas'],
                'fasilitas' => (float) $row['fasilitas'],
                'sarana' => (float) $row['sarana'],
                'ulasan' => (float) $row['ulasan'],
                'jumlah_pengunjung' => (int) $row['jumlah_pengunjung'],
                'rating' => (float) $row['rating'],
                'lat' => (float) $row['lat'],
                'lng' => (float) $row['lng'],
            ];
        }, $rows);
    } catch (Throwable $e) {
        error_log('SmartTourism database error: ' . $e->getMessage());

        return [];
    }
}

// ============================================================
// K-MEANS CLUSTERING ALGORITHM
// ============================================================
function normalizeData(array $data, array $fields): array
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

function euclideanDistance(array $a, array $b, array $fields): float
{
    $sum = 0;
    foreach ($fields as $f) {
        $diff = ($a[$f . '_norm'] ?? $a[$f]) - ($b[$f] ?? 0);
        $sum += $diff * $diff;
    }
    return sqrt($sum);
}

function buildEmptyKMeansResult(int $k, array $fields): array
{
    $fieldLabels = [
        'daya_tarik' => 'Daya Tarik',
        'aksesibilitas' => 'Aksesibilitas',
        'fasilitas' => 'Fasilitas',
        'sarana' => 'Sarana',
        'ulasan' => 'Ulasan',
    ];

    return [
        'data' => [],
        'centroids' => [],
        'history' => [],
        'iterations' => 0,
        'converged' => false,
        'sse' => 0,
        'labels' => [],
        'colors' => [],
        'icons' => [],
        'k' => $k,
        'fields' => $fields,
        'fields_label' => $fieldLabels,
        'norm_meta' => [
            'min' => array_fill_keys($fields, 0),
            'max' => array_fill_keys($fields, 0),
        ],
        'init_step' => 1,
        'empty' => true,
    ];
}

function kMeansClustering(array $data, int $k = 3, array $fields = [], int $maxIter = 100): array
{
    $n = count($data);
    $history = [];
    $palette = [
        '#059669',
        '#d97706',
        '#6366f1',
        '#0ea5e9',
        '#ef4444',
    ];
    $iconPalette = ['high', 'medium', 'low', 'medium', 'low'];

    // Initialize centroids - pick first k points spread out
    $step = max(1, (int) floor($n / $k));
    $centroids = [];
    for ($i = 0; $i < $k; $i++) {
        $idx = min($i * $step, $n - 1);
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
        'empty' => false,
    ];
}

function runKMeans(int $k = 3): array
{
    $raw = getWisataData();
    $fields = ['daya_tarik', 'aksesibilitas', 'fasilitas', 'sarana', 'ulasan'];

    if (empty($raw)) {
        return buildEmptyKMeansResult($k, $fields);
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

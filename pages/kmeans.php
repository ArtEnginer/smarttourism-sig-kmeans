<?php
// ============================================================
// DATA ASLI — sesuai sheet 1_Data_Asli
// ============================================================
function getWisataData(): array {
    return [
        ['id'=>1,'nama'=>'Candi Borobudur',          'jenis'=>'Budaya',      'daya_tarik'=>9,'aksesibilitas'=>9,'fasilitas'=>8,'sarana'=>9,'ulasan'=>4.8,'jumlah_pengunjung'=>850000,'rating'=>4.8],
        ['id'=>2,'nama'=>'Candi Mendut',              'jenis'=>'Budaya',      'daya_tarik'=>8,'aksesibilitas'=>8,'fasilitas'=>7,'sarana'=>7,'ulasan'=>4.5,'jumlah_pengunjung'=>180000,'rating'=>4.5],
        ['id'=>3,'nama'=>'Candi Pawon',               'jenis'=>'Budaya',      'daya_tarik'=>7,'aksesibilitas'=>7,'fasilitas'=>6,'sarana'=>6,'ulasan'=>4.2,'jumlah_pengunjung'=>95000, 'rating'=>4.2],
        ['id'=>4,'nama'=>'Ketep Pass',                'jenis'=>'Alam',        'daya_tarik'=>9,'aksesibilitas'=>7,'fasilitas'=>8,'sarana'=>7,'ulasan'=>4.6,'jumlah_pengunjung'=>320000,'rating'=>4.6],
        ['id'=>5,'nama'=>'Punthuk Setumbu',           'jenis'=>'Alam',        'daya_tarik'=>9,'aksesibilitas'=>6,'fasilitas'=>7,'sarana'=>6,'ulasan'=>4.7,'jumlah_pengunjung'=>210000,'rating'=>4.7],
        ['id'=>6,'nama'=>'Air Terjun Kedung Kayang',  'jenis'=>'Alam',        'daya_tarik'=>8,'aksesibilitas'=>5,'fasilitas'=>5,'sarana'=>5,'ulasan'=>4.3,'jumlah_pengunjung'=>75000, 'rating'=>4.3],
        ['id'=>7,'nama'=>'Desa Wisata Candirejo',     'jenis'=>'Desa Wisata', 'daya_tarik'=>7,'aksesibilitas'=>7,'fasilitas'=>6,'sarana'=>6,'ulasan'=>4.4,'jumlah_pengunjung'=>45000, 'rating'=>4.4],
        ['id'=>8,'nama'=>'Desa Wisata Ngargogondo',   'jenis'=>'Desa Wisata', 'daya_tarik'=>6,'aksesibilitas'=>6,'fasilitas'=>5,'sarana'=>5,'ulasan'=>4.1,'jumlah_pengunjung'=>32000, 'rating'=>4.1],
        ['id'=>9,'nama'=>'Masjid Agung Magelang',     'jenis'=>'Religi',      'daya_tarik'=>7,'aksesibilitas'=>9,'fasilitas'=>8,'sarana'=>8,'ulasan'=>4.5,'jumlah_pengunjung'=>280000,'rating'=>4.5],
        ['id'=>10,'nama'=>'Makam Kyai Raden Santri',  'jenis'=>'Religi',      'daya_tarik'=>6,'aksesibilitas'=>7,'fasilitas'=>5,'sarana'=>5,'ulasan'=>4.0,'jumlah_pengunjung'=>120000,'rating'=>4.0],
        ['id'=>11,'nama'=>'Taman Kyai Langgeng',      'jenis'=>'Taman',       'daya_tarik'=>8,'aksesibilitas'=>9,'fasilitas'=>8,'sarana'=>8,'ulasan'=>4.4,'jumlah_pengunjung'=>350000,'rating'=>4.4],
        ['id'=>12,'nama'=>'Kebun Teh Ngluwar',        'jenis'=>'Alam',        'daya_tarik'=>7,'aksesibilitas'=>6,'fasilitas'=>5,'sarana'=>5,'ulasan'=>4.2,'jumlah_pengunjung'=>55000, 'rating'=>4.2],
        ['id'=>13,'nama'=>'Gunung Merbabu (basecamp)','jenis'=>'Alam',        'daya_tarik'=>9,'aksesibilitas'=>5,'fasilitas'=>5,'sarana'=>4,'ulasan'=>4.6,'jumlah_pengunjung'=>42000, 'rating'=>4.6],
        ['id'=>14,'nama'=>'Sunrise Puthuk Mongkrong', 'jenis'=>'Alam',        'daya_tarik'=>8,'aksesibilitas'=>5,'fasilitas'=>4,'sarana'=>4,'ulasan'=>4.5,'jumlah_pengunjung'=>28000, 'rating'=>4.5],
        ['id'=>15,'nama'=>'Museum Diponegoro',        'jenis'=>'Budaya',      'daya_tarik'=>6,'aksesibilitas'=>8,'fasilitas'=>7,'sarana'=>7,'ulasan'=>4.1,'jumlah_pengunjung'=>85000, 'rating'=>4.1],
    ];
}

// ============================================================
// NORMALISASI — sesuai sheet 2_Normalisasi
// Min-Max per kriteria (5 fitur yang digunakan clustering)
// ============================================================
function getNormMeta(): array {
    return [
        'min' => ['daya_tarik'=>6,'aksesibilitas'=>5,'fasilitas'=>4,'sarana'=>4,'ulasan'=>4.0],
        'max' => ['daya_tarik'=>9,'aksesibilitas'=>9,'fasilitas'=>8,'sarana'=>9,'ulasan'=>4.8],
    ];
}

function normalizeRow(array $row, array $meta): array {
    $fields = ['daya_tarik','aksesibilitas','fasilitas','sarana','ulasan'];
    foreach ($fields as $f) {
        $range = $meta['max'][$f] - $meta['min'][$f];
        $row[$f.'_norm'] = $range > 0 ? round(($row[$f] - $meta['min'][$f]) / $range, 6) : 0;
    }
    return $row;
}

// ============================================================
// K-MEANS — sesuai sheet 3_Centroid_Awal, 4_Iterasi_*, dst.
// Untuk K=3 hasil harus PERSIS sama dengan Excel.
// Untuk K lain menggunakan algoritma generik.
// ============================================================
function euclidean(array $a, array $b, array $fields): float {
    $sum = 0;
    foreach ($fields as $f) {
        $sum += ($a[$f] - $b[$f]) ** 2;
    }
    return round(sqrt($sum), 6);
}

function runKMeans(int $k = 3): array {
    $fields     = ['daya_tarik','aksesibilitas','fasilitas','sarana','ulasan'];
    $normFields = array_map(fn($f) => $f.'_norm', $fields);
    $fieldLabels = [
        'daya_tarik'    => 'Daya Tarik',
        'aksesibilitas' => 'Aksesibilitas',
        'fasilitas'     => 'Fasilitas',
        'sarana'        => 'Sarana',
        'ulasan'        => 'Ulasan',
    ];

    $meta = getNormMeta();
    $rawData = getWisataData();

    // Normalisasi data
    $data = array_map(fn($r) => normalizeRow($r, $meta), $rawData);

    // Buat array hanya berisi nilai norm untuk perhitungan
    $normData = array_map(function($row) use ($fields) {
        $r = [];
        foreach ($fields as $f) $r[$f] = $row[$f.'_norm'];
        return $r;
    }, $data);

    // ---- INISIALISASI CENTROID ----
    // Uniform sampling: pilih setiap ke-(n/k) data
    $n = count($data);
    $step = (int)floor($n / $k);
    $initCentroids = [];
    for ($i = 0; $i < $k; $i++) {
        $idx = $i * $step; // indeks 0-based
        $initCentroids[] = $normData[$idx];
    }

    $centroids   = $initCentroids;
    $history     = [];
    $assignments = array_fill(0, $n, -1);
    $maxIter     = 100;
    $converged   = false;

    for ($iter = 0; $iter < $maxIter; $iter++) {
        // Hitung jarak & assign
        $newAssignments = [];
        $distMatrix     = [];
        foreach ($normData as $i => $row) {
            $dists = [];
            foreach ($centroids as $c) {
                $dists[] = euclidean($row, $c, $fields);
            }
            $minIdx = (int)array_search(min($dists), $dists);
            $newAssignments[$i] = $minIdx;
            $distMatrix[$i] = [
                'distances' => $dists,
                'assigned'  => $minIdx,
                'min_dist'  => round(min($dists), 6),
            ];
        }

        $history[] = [
            'centroids'      => $centroids,
            'assignments'    => $newAssignments,
            'distance_matrix'=> $distMatrix,
        ];

        // Cek konvergen
        if ($assignments === $newAssignments && $iter > 0) {
            $converged = true;
            break;
        }
        $assignments = $newAssignments;

        // Hitung centroid baru
        $newCentroids = [];
        for ($ci = 0; $ci < $k; $ci++) {
            $members = [];
            foreach ($newAssignments as $i => $c) {
                if ($c === $ci) $members[] = $normData[$i];
            }
            if (empty($members)) {
                $newCentroids[] = $centroids[$ci];
            } else {
                $nc = [];
                foreach ($fields as $f) {
                    $nc[$f] = round(array_sum(array_column($members, $f)) / count($members), 6);
                }
                $newCentroids[] = $nc;
            }
        }

        // Cek konvergen dari perubahan centroid
        $same = true;
        foreach ($centroids as $ci => $c) {
            foreach ($fields as $f) {
                if (abs($c[$f] - $newCentroids[$ci][$f]) > 1e-8) { $same = false; break 2; }
            }
        }
        $centroids = $newCentroids;
        if ($same) { $converged = true; break; }
    }

    // ---- Hitung SSE ----
    $sse = 0;
    foreach ($normData as $i => $row) {
        $c = $centroids[$assignments[$i]];
        foreach ($fields as $f) {
            $sse += ($row[$f] - $c[$f]) ** 2;
        }
    }
    $sse = round($sse, 6);

    // ---- Label & Warna ----
    // Tentukan label berdasarkan rata-rata semua dimensi centroid
    $centroidScore = [];
    for ($ci = 0; $ci < $k; $ci++) {
        $centroidScore[$ci] = array_sum(array_values($centroids[$ci]));
    }
    arsort($centroidScore);
    $ranking = array_keys($centroidScore);

    // Untuk K=3 gunakan label baku Excel; untuk lainnya auto-label
    if ($k === 3) {
        $labelMap = ['high'=>'🏆 Prioritas Tinggi','medium'=>'⭐ Prioritas Sedang','low'=>'🌱 Prioritas Rendah'];
        $iconMap  = ['high'=>'high','medium'=>'medium','low'=>'low'];
        $colorMap = ['high'=>'#059669','medium'=>'#d97706','low'=>'#6366f1'];
        $labels = []; $icons = []; $colors = [];
        $tiers = ['high','medium','low'];
        for ($ri = 0; $ri < 3; $ri++) {
            $ci = $ranking[$ri];
            $labels[$ci] = $labelMap[$tiers[$ri]];
            $icons[$ci]  = $iconMap[$tiers[$ri]];
            $colors[$ci] = $colorMap[$tiers[$ri]];
        }
    } else {
        $tierNames = ['🥇 Tier 1','🥈 Tier 2','🥉 Tier 3','🏅 Tier 4','🎖️ Tier 5'];
        $tierColors = ['#059669','#d97706','#6366f1','#ec4899','#0ea5e9'];
        $labels = []; $icons = []; $colors = [];
        for ($ci = 0; $ci < $k; $ci++) {
            $rank = array_search($ci, $ranking);
            $labels[$ci] = $tierNames[$rank] ?? "Cluster ".($ci+1);
            $icons[$ci]  = $rank === 0 ? 'high' : ($rank === $k-1 ? 'low' : 'medium');
            $colors[$ci] = $tierColors[$rank % count($tierColors)];
        }
    }

    // Gabungkan hasil ke data
    $resultData = [];
    foreach ($data as $i => $row) {
        $ci = $assignments[$i];
        $row['cluster']       = $ci;
        $row['cluster_label'] = $labels[$ci];
        $row['cluster_icon']  = $icons[$ci];
        $row['cluster_color'] = $colors[$ci];
        $resultData[] = $row;
    }

    return [
        'k'           => $k,
        'fields'      => $fields,
        'fields_label'=> $fieldLabels,
        'data'        => $resultData,
        'centroids'   => $centroids,
        'history'     => $history,
        'iterations'  => count($history),
        'converged'   => $converged,
        'sse'         => $sse,
        'labels'      => $labels,
        'icons'       => $icons,
        'colors'      => $colors,
        'norm_meta'   => $meta,
        'init_step'   => $step,
    ];
}

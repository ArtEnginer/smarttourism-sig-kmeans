<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/api_helper.php';
require_once __DIR__ . '/../../includes/kmeans.php';

setCorsHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse(null, 405, 'Metode HTTP ' . $_SERVER['REQUEST_METHOD'] . ' tidak diizinkan. Gunakan GET.');
}

// Require API Key (Strict Mode)
$apiKeyRecord = validateApiKey(null, true);

try {
    $k = isset($_GET['k']) ? (int) $_GET['k'] : 3;
    if ($k < 2 || $k > 5) {
        sendJsonResponse(null, 400, 'Jumlah klaster (k) harus antara 2 dan 5.');
    }

    $kmeansResult = runKMeans($k);

    if (!isset($kmeansResult['data']) || empty($kmeansResult['data'])) {
        sendJsonResponse(null, 404, 'Belum ada data destinasi wisata untuk dihitung.');
    }

    // Format destination list for response
    $formattedDestinasi = array_map(static function (array $item): array {
        return [
            'id' => (int) $item['id'],
            'nama' => (string) $item['nama'],
            'jenis' => (string) $item['jenis'],
            'cluster' => (int) $item['cluster'],
            'cluster_label' => (string) $item['cluster_label'],
            'cluster_color' => (string) $item['cluster_color'],
            'daya_tarik' => (float) $item['daya_tarik'],
            'aksesibilitas' => (float) $item['aksesibilitas'],
            'fasilitas' => (float) $item['fasilitas'],
            'sarana' => (float) $item['sarana'],
            'ulasan' => (float) $item['ulasan'],
            'rating' => (float) $item['rating'],
            'jumlah_pengunjung' => (int) $item['jumlah_pengunjung'],
            'lat' => (float) $item['lat'],
            'lng' => (float) $item['lng'],
        ];
    }, $kmeansResult['data']);

    // Build cluster summary statistics
    $clusterCounts = [];
    foreach ($kmeansResult['labels'] as $cIndex => $cLabel) {
        $count = count(array_filter($kmeansResult['data'], static fn($item) => $item['cluster'] === $cIndex));
        $clusterCounts[] = [
            'cluster_index' => $cIndex,
            'label' => $cLabel,
            'color' => $kmeansResult['colors'][$cIndex] ?? '#059669',
            'total_destinasi' => $count
        ];
    }

    $response = [
        'k' => $kmeansResult['k'],
        'converged' => $kmeansResult['converged'],
        'iterations' => $kmeansResult['iterations'],
        'sse' => $kmeansResult['sse'],
        'cluster_summary' => $clusterCounts,
        'centroids' => $kmeansResult['centroids'],
        'destinasi' => $formattedDestinasi
    ];

    sendJsonResponse($response, 200, 'Berhasil menghitung dan mengambil data K-Means Clustering');

} catch (Throwable $e) {
    sendJsonResponse(null, 500, 'Terjadi kesalahan pada kalkulasi clustering: ' . $e->getMessage());
}

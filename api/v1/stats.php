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
    $pdo = getDatabaseConnection();

    // Total count
    $totalStmt = $pdo->query("SELECT COUNT(*) FROM tb_destinasi WHERE status = 'aktif'");
    $totalDestinasi = (int) $totalStmt->fetchColumn();

    // Summary averages
    $avgStmt = $pdo->query("SELECT 
        AVG(rating) as avg_rating,
        AVG(ulasan) as avg_ulasan,
        SUM(jumlah_pengunjung) as total_pengunjung,
        AVG(jumlah_pengunjung) as avg_pengunjung
    FROM tb_destinasi WHERE status = 'aktif'");
    $avgData = $avgStmt->fetch(PDO::FETCH_ASSOC);

    // Breakdown per category
    $catStmt = $pdo->query("SELECT 
        kategori, 
        COUNT(*) as total, 
        ROUND(AVG(rating), 2) as avg_rating,
        SUM(jumlah_pengunjung) as total_pengunjung
    FROM tb_destinasi 
    WHERE status = 'aktif' 
    GROUP BY kategori 
    ORDER BY total DESC");
    $byCategory = $catStmt->fetchAll(PDO::FETCH_ASSOC);

    // Top rated
    $topRatedStmt = $pdo->query("SELECT id, nama_destinasi, kategori, rating, jumlah_pengunjung FROM tb_destinasi WHERE status = 'aktif' ORDER BY rating DESC, jumlah_pengunjung DESC LIMIT 1");
    $topRated = $topRatedStmt->fetch(PDO::FETCH_ASSOC);

    // Most visited
    $mostVisitedStmt = $pdo->query("SELECT id, nama_destinasi, kategori, rating, jumlah_pengunjung FROM tb_destinasi WHERE status = 'aktif' ORDER BY jumlah_pengunjung DESC LIMIT 1");
    $mostVisited = $mostVisitedStmt->fetch(PDO::FETCH_ASSOC);

    // Clustering summary (default k=3)
    $kmeansResult = runKMeans(3);
    $clusterSummary = [];
    if (!empty($kmeansResult['data'])) {
        foreach ($kmeansResult['labels'] as $cIdx => $label) {
            $count = count(array_filter($kmeansResult['data'], static fn($item) => $item['cluster'] === $cIdx));
            $clusterSummary[] = [
                'cluster_index' => $cIdx,
                'label' => $label,
                'color' => $kmeansResult['colors'][$cIdx] ?? '#059669',
                'total_destinasi' => $count
            ];
        }
    }

    $response = [
        'overview' => [
            'total_destinasi' => $totalDestinasi,
            'total_pengunjung' => (int) ($avgData['total_pengunjung'] ?? 0),
            'rata_rata_rating' => round((float) ($avgData['avg_rating'] ?? 0), 2),
            'rata_rata_ulasan' => round((float) ($avgData['avg_ulasan'] ?? 0), 2),
            'rata_rata_pengunjung' => round((float) ($avgData['avg_pengunjung'] ?? 0), 0),
        ],
        'highlights' => [
            'top_rated' => $topRated ? [
                'id' => (int) $topRated['id'],
                'nama' => $topRated['nama_destinasi'],
                'kategori' => $topRated['kategori'],
                'rating' => (float) $topRated['rating'],
            ] : null,
            'most_visited' => $mostVisited ? [
                'id' => (int) $mostVisited['id'],
                'nama' => $mostVisited['nama_destinasi'],
                'kategori' => $mostVisited['kategori'],
                'jumlah_pengunjung' => (int) $mostVisited['jumlah_pengunjung'],
            ] : null,
        ],
        'distribusi_kategori' => array_map(static function ($item) {
            return [
                'kategori' => $item['kategori'],
                'total' => (int) $item['total'],
                'avg_rating' => (float) $item['avg_rating'],
                'total_pengunjung' => (int) $item['total_pengunjung'],
            ];
        }, $byCategory),
        'distribusi_klaster' => $clusterSummary
    ];

    sendJsonResponse($response, 200, 'Berhasil mengambil statistik pariwisata Magelang');

} catch (Throwable $e) {
    sendJsonResponse(null, 500, 'Terjadi kesalahan server: ' . $e->getMessage());
}

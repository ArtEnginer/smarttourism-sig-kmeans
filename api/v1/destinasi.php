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

    // Check single item lookup
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $stmt = $pdo->prepare("SELECT 
            id,
            nama_destinasi,
            kategori,
            daya_tarik,
            aksesibilitas,
            fasilitas,
            sarana,
            ulasan,
            jumlah_pengunjung,
            rating,
            latitude,
            longitude,
            status,
            created_at,
            updated_at
        FROM tb_destinasi 
        WHERE id = ? AND status = 'aktif' 
        LIMIT 1");
        $stmt->execute([$id]);
        $destinasi = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$destinasi) {
            sendJsonResponse(null, 404, 'Destinasi wisata dengan ID ' . $id . ' tidak ditemukan.');
        }

        // Format numeric values
        $destinasi['id'] = (int) $destinasi['id'];
        $destinasi['daya_tarik'] = (int) $destinasi['daya_tarik'];
        $destinasi['aksesibilitas'] = (int) $destinasi['aksesibilitas'];
        $destinasi['fasilitas'] = (int) $destinasi['fasilitas'];
        $destinasi['sarana'] = (int) $destinasi['sarana'];
        $destinasi['ulasan'] = (float) $destinasi['ulasan'];
        $destinasi['jumlah_pengunjung'] = (int) $destinasi['jumlah_pengunjung'];
        $destinasi['rating'] = (float) $destinasi['rating'];
        $destinasi['latitude'] = (float) $destinasi['latitude'];
        $destinasi['longitude'] = (float) $destinasi['longitude'];

        sendJsonResponse($destinasi, 200, 'Berhasil mengambil detail destinasi wisata');
    }

    // Query parameters for listing
    $kategori = isset($_GET['kategori']) ? trim((string) $_GET['kategori']) : '';
    $search = isset($_GET['search']) ? trim((string) $_GET['search']) : '';
    $minRating = isset($_GET['min_rating']) ? (float) $_GET['min_rating'] : null;
    $sortBy = isset($_GET['sort_by']) ? trim((string) $_GET['sort_by']) : 'id';
    $order = isset($_GET['order']) && strtolower((string) $_GET['order']) === 'desc' ? 'DESC' : 'ASC';

    // Pagination
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $limit = min(100, max(1, (int) ($_GET['limit'] ?? 10)));
    $offset = ($page - 1) * $limit;

    // Allowed sort columns
    $allowedSort = [
        'id' => 'id',
        'nama' => 'nama_destinasi',
        'kategori' => 'kategori',
        'rating' => 'rating',
        'pengunjung' => 'jumlah_pengunjung',
        'ulasan' => 'ulasan',
        'daya_tarik' => 'daya_tarik'
    ];
    $sortColumn = $allowedSort[$sortBy] ?? 'id';

    // Build WHERE clause
    $where = ["status = 'aktif'"];
    $params = [];

    if ($kategori !== '') {
        $where[] = "kategori = ?";
        $params[] = $kategori;
    }

    if ($search !== '') {
        $where[] = "nama_destinasi LIKE ?";
        $params[] = '%' . $search . '%';
    }

    if ($minRating !== null && $minRating > 0) {
        $where[] = "rating >= ?";
        $params[] = $minRating;
    }

    $whereClause = implode(' AND ', $where);

    // Count total items
    $countSql = "SELECT COUNT(*) FROM tb_destinasi WHERE {$whereClause}";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalItems = (int) $countStmt->fetchColumn();

    // Fetch paginated data
    $sql = "SELECT 
        id,
        nama_destinasi,
        kategori,
        daya_tarik,
        aksesibilitas,
        fasilitas,
        sarana,
        ulasan,
        jumlah_pengunjung,
        rating,
        latitude,
        longitude,
        created_at
    FROM tb_destinasi 
    WHERE {$whereClause} 
    ORDER BY {$sortColumn} {$order} 
    LIMIT {$limit} OFFSET {$offset}";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $formattedRows = array_map(static function (array $row): array {
        return [
            'id' => (int) $row['id'],
            'nama_destinasi' => (string) $row['nama_destinasi'],
            'kategori' => (string) $row['kategori'],
            'daya_tarik' => (int) $row['daya_tarik'],
            'aksesibilitas' => (int) $row['aksesibilitas'],
            'fasilitas' => (int) $row['fasilitas'],
            'sarana' => (int) $row['sarana'],
            'ulasan' => (float) $row['ulasan'],
            'jumlah_pengunjung' => (int) $row['jumlah_pengunjung'],
            'rating' => (float) $row['rating'],
            'latitude' => (float) $row['latitude'],
            'longitude' => (float) $row['longitude'],
            'created_at' => $row['created_at'],
        ];
    }, $rows);

    $totalPages = (int) ceil($totalItems / $limit);

    $meta = [
        'total_items' => $totalItems,
        'current_page' => $page,
        'per_page' => $limit,
        'total_pages' => $totalPages,
        'filters' => [
            'kategori' => $kategori ?: null,
            'search' => $search ?: null,
            'min_rating' => $minRating ?: null,
            'sort_by' => $sortBy,
            'order' => strtolower($order)
        ]
    ];

    sendJsonResponse($formattedRows, 200, 'Berhasil mengambil daftar destinasi wisata', $meta);

} catch (Throwable $e) {
    sendJsonResponse(null, 500, 'Terjadi kesalahan pada server: ' . $e->getMessage());
}

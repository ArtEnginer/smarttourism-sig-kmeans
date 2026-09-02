<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/api_helper.php';
require_once __DIR__ . '/../../includes/auth.php';

setCorsHeaders();

$method = $_SERVER['REQUEST_METHOD'];

// Require user session for API key management
if (!isLoggedIn()) {
    sendJsonResponse(null, 401, 'Anda harus login untuk mengelola API Key.');
}

$user = getCurrentUser();
$userId = (int) ($user['id'] ?? 0);

// If user id is not in session (e.g. legacy session), fetch user by username
try {
    $pdo = getDatabaseConnection();
    ensureApiKeyTableExists($pdo);

    if ($userId <= 0 && isset($user['username'])) {
        $uStmt = $pdo->prepare("SELECT id FROM tb_users WHERE username = ? LIMIT 1");
        $uStmt->execute([$user['username']]);
        $userId = (int) $uStmt->fetchColumn();
    }

    if ($userId <= 0) {
        sendJsonResponse(null, 400, 'User ID tidak valid dalam sesi login.');
    }

    if ($method === 'GET') {
        $stmt = $pdo->prepare("SELECT id, name, api_key, status, created_at, last_used_at FROM tb_api_keys WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$userId]);
        $keys = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $formatted = array_map(static function ($item) {
            return [
                'id' => (int) $item['id'],
                'name' => $item['name'],
                'api_key' => $item['api_key'],
                'status' => $item['status'],
                'created_at' => $item['created_at'],
                'last_used_at' => $item['last_used_at'],
            ];
        }, $keys);

        sendJsonResponse($formatted, 200, 'Berhasil mengambil daftar API Key');
    }

    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $name = trim((string) ($input['name'] ?? 'Aplikasi Saya'));

        if ($name === '') {
            $name = 'Aplikasi Developer';
        }

        // Limit user to max 10 API keys
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM tb_api_keys WHERE user_id = ? AND status = 'active'");
        $countStmt->execute([$userId]);
        if ((int) $countStmt->fetchColumn() >= 10) {
            sendJsonResponse(null, 400, 'Batas maksimum API Key aktif per pengguna adalah 10.');
        }

        $newApiKey = generateSecureApiKey();

        $insertStmt = $pdo->prepare("INSERT INTO tb_api_keys (user_id, name, api_key, status) VALUES (?, ?, ?, 'active')");
        $insertStmt->execute([$userId, $name, $newApiKey]);
        $newId = (int) $pdo->lastInsertId();

        $createdKey = [
            'id' => $newId,
            'name' => $name,
            'api_key' => $newApiKey,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        sendJsonResponse($createdKey, 201, 'API Key baru berhasil dibuat');
    }

    if ($method === 'DELETE') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
            $id = (int) ($input['id'] ?? 0);
        }

        if ($id <= 0) {
            sendJsonResponse(null, 400, 'ID API Key yang akan dicabut harus disertakan.');
        }

        $delStmt = $pdo->prepare("UPDATE tb_api_keys SET status = 'revoked' WHERE id = ? AND user_id = ?");
        $delStmt->execute([$id, $userId]);

        if ($delStmt->rowCount() === 0) {
            sendJsonResponse(null, 404, 'API Key tidak ditemukan atau bukan milik Anda.');
        }

        sendJsonResponse(['id' => $id], 200, 'API Key berhasil dicabut (revoked)');
    }

    sendJsonResponse(null, 405, 'Metode HTTP ' . $method . ' tidak didukung.');

} catch (Throwable $e) {
    sendJsonResponse(null, 500, 'Gagal mengelola API Key: ' . $e->getMessage());
}

<?php

declare(strict_types=1);

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';

/**
 * Set HTTP CORS headers for public API access
 */
function setCorsHeaders(): void
{
    if (!headers_sent()) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, X-API-Key, Authorization');
        header('Content-Type: application/json; charset=UTF-8');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        if (!headers_sent()) {
            http_response_code(200);
        }
        exit;
    }
}

/**
 * Send standard JSON response structure
 */
function sendJsonResponse(mixed $data = null, int $statusCode = 200, string $message = 'Success', array $meta = []): void
{
    if (!headers_sent()) {
        http_response_code($statusCode);
    }
    setCorsHeaders();

    $response = [
        'status' => $statusCode >= 200 && $statusCode < 300 ? 'success' : 'error',
        'code' => $statusCode,
        'message' => $message,
    ];

    if (!empty($meta)) {
        $response['meta'] = $meta;
    }

    $response['data'] = $data;

    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Ensure tb_api_keys table exists automatically
 */
function ensureApiKeyTableExists(PDO $pdo): void
{
    static $checked = false;
    if ($checked) {
        return;
    }

    try {
        $sql = "CREATE TABLE IF NOT EXISTS tb_api_keys (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id INT UNSIGNED NOT NULL,
            name VARCHAR(100) NOT NULL,
            api_key VARCHAR(64) NOT NULL,
            status ENUM('active', 'revoked') NOT NULL DEFAULT 'active',
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            last_used_at TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uq_api_key (api_key),
            KEY idx_user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $pdo->exec($sql);
        $checked = true;
    } catch (Throwable $e) {
        error_log('Failed to ensure tb_api_keys table: ' . $e->getMessage());
    }
}

/**
 * Validate API Key from Header or Query Parameter
 */
function validateApiKey(?PDO $pdo = null, bool $required = false): ?array
{
    if ($pdo === null) {
        try {
            $pdo = getDatabaseConnection();
        } catch (Throwable $e) {
            if ($required) {
                sendJsonResponse(null, 500, 'Database connection error: ' . $e->getMessage());
            }
            return null;
        }
    }

    ensureApiKeyTableExists($pdo);

    // Extract key from X-API-Key header, Authorization Bearer, or ?api_key= query param
    $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? null;

    if (!$apiKey && isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = trim($_SERVER['HTTP_AUTHORIZATION']);
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $apiKey = trim($matches[1]);
        }
    }

    if (!$apiKey && isset($_GET['api_key'])) {
        $apiKey = trim((string) $_GET['api_key']);
    }

    if (!$apiKey) {
        if ($required) {
            sendJsonResponse(null, 401, 'API Key diperlukan. Gunakan header X-API-Key atau parameter ?api_key=');
        }
        return null;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM tb_api_keys WHERE api_key = ? AND status = 'active' LIMIT 1");
        $stmt->execute([$apiKey]);
        $keyRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$keyRecord) {
            if ($required) {
                sendJsonResponse(null, 401, 'API Key tidak valid atau telah dicabut (revoked).');
            }
            return null;
        }

        // Update last_used_at timestamp
        $updateStmt = $pdo->prepare("UPDATE tb_api_keys SET last_used_at = NOW() WHERE id = ?");
        $updateStmt->execute([$keyRecord['id']]);

        return $keyRecord;
    } catch (Throwable $e) {
        if ($required) {
            sendJsonResponse(null, 500, 'Gagal mengautentikasi API Key: ' . $e->getMessage());
        }
        return null;
    }
}

/**
 * Generate a random secure API Key
 */
function generateSecureApiKey(string $prefix = 'smt_'): string
{
    return $prefix . bin2hex(random_bytes(20));
}

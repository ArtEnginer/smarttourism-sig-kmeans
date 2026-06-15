<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool
{
    if (!isset($_SESSION['user'])) {
        return false;
    }

    if (!is_array($_SESSION['user'])) {
        unset($_SESSION['user']);
        return false;
    }

    return isset($_SESSION['user']['username'], $_SESSION['user']['role']);
}

function getCurrentUser(): ?array
{
    return isLoggedIn() ? $_SESSION['user'] : null;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        http_response_code(401);
        echo '<h2>401 Unauthorized</h2><p>Anda harus masuk untuk melihat halaman ini.</p>';
        exit;
    }
}

function isAdmin(): bool
{
    $u = getCurrentUser();
    return $u && (($u['role'] ?? '') === 'admin');
}

function requireAdmin(): void
{
    requireLogin();
    if (!isAdmin()) {
        http_response_code(403);
        echo '<h2>403 Forbidden</h2><p>Akses ditolak. Anda tidak memiliki izin untuk melihat halaman ini.</p>';
        exit;
    }
}

function appUrl(string $path = ''): string
{
    static $baseUrl = null;

    if ($baseUrl === null) {
        // Cek apakah APP_URL diset di environment / .env
        $envUrl = getenv('APP_URL');
        if ($envUrl !== false && $envUrl !== '') {
            $baseUrl = rtrim($envUrl, '/');
        } else {
            // Ambil document root dari server
            $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

            // Ambil direktori file ini (auth.php / config.php berada)
            // Lalu naik ke root project
            $projectRoot = rtrim(dirname(__DIR__), '/');
            // Kalau file ini sudah di root project, pakai dirname(__FILE__)
            // sesuaikan dengan posisi file ini

            // Hitung relative path dari document root ke project root
            $relativePath = str_replace($docRoot, '', $projectRoot);

            $baseUrl = '/' . trim($relativePath, '/');
        }
    }

    // Hindari double slash
    $suffix = $path !== '' ? '/' . ltrim($path, '/') : '';
    return $baseUrl . $suffix;
}

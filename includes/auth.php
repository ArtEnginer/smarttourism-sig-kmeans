<?php

declare(strict_types=1);

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
        $scriptName = $_SERVER['SCRIPT_NAME'];

        // Cari posisi folder project
        $parts = explode('/', trim($scriptName, '/'));

        // contoh:
        // PKMUQOROBIN/smartourism/pages/dashboard.php
        $baseUrl = '/' . $parts[0] . '/' . $parts[1];
    }

    return $baseUrl . '/' . ltrim($path, '/');
}

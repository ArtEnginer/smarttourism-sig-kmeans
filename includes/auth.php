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
        header('Location: /MUQOROBIN/smartourism/index.php');
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
    return '/MUQOROBIN/smartourism/' . ltrim($path, '/');
}

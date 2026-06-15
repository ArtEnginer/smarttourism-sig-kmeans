<?php

declare(strict_types=1);

/**
 * Load environment variables from a .env file.
 */
function loadEnv(string $filePath): void
{
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        
        // Skip comments and empty lines
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        // Split by the first '=' character
        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $key = trim($parts[0]);
        $value = trim($parts[1]);

        // Remove quotes around values if present
        if (preg_match('/^([\'"])(.*)\1$/', $value, $matches)) {
            $value = $matches[2];
        }

        // Put variable in env/server globals if not already set (allowing system environment variables to override)
        if (getenv($key) === false) {
            putenv(sprintf('%s=%s', $key, $value));
        }
        if (!isset($_ENV[$key])) {
            $_ENV[$key] = $value;
        }
        if (!isset($_SERVER[$key])) {
            $_SERVER[$key] = $value;
        }
    }
}

// Load from the project root .env file
loadEnv(dirname(__DIR__) . '/.env');

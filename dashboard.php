<?php
require_once __DIR__ . '/includes/auth.php';
$qs = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
header('Location: ' . appUrl('pages/dashboard.php') . $qs);
exit;

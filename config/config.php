<?php
// config/config.php

defined('APP_INIT') || define('APP_INIT', true);

// 1. Path Definitions
defined('ROOT_PATH') || define('ROOT_PATH', dirname(__DIR__));

if (!defined('BASE_URL')) {
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    $appRoot = str_replace('\\', '/', ROOT_PATH);
    $relative = str_replace($docRoot, '', $appRoot);
    define('BASE_URL', rtrim('/' . ltrim($relative, '/'), '/'));
}

// 2. System Defaults
date_default_timezone_set('Asia/Manila');

// 3. Database Credentials
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'eskuelog_db');
define('DB_PORT', 3306);

// 4. Secure Session Initialization
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}
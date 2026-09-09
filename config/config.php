<?php
// config/config.php

// Prevent direct script execution
if (!defined('APP_INIT')) {
    define('APP_INIT', true);
}

// 1. Detect protocol (http vs https)
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443;
$protocol = $isSecure ? "https://" : "http://";

// 2. Detect host (e.g., localhost or domain.com)
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// 3. Extract subfolder root dynamically from script execution path
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$pathSegments = array_values(array_filter(explode('/', $scriptName)));

// If running in a subdirectory (e.g., /ccc-website/index.php), capture the first segment
$subfolder = (!empty($pathSegments) && count($pathSegments) > 1 && $pathSegments[0] !== 'index.php') 
    ? '/' . $pathSegments[0] 
    : '';

// 4. Define global BASE_URL without trailing slash
if (!defined('BASE_URL')) {
    define('BASE_URL', $subfolder);
}

// 5. System Root Absolute Path for PHP Includes
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}
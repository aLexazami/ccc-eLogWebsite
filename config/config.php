<?php
// config/config.php

// Prevent direct script execution
if (!defined('APP_INIT')) {
    define('APP_INIT', true);
}

// 1. Define Absolute System Root Path
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// 2. Calculate Base URL dynamically by comparing Document Root to System Root
if (!defined('BASE_URL')) {
    // Normalize directory separators for Windows/Linux compatibility
    $docRoot  = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
    $projRoot = str_replace('\\', '/', ROOT_PATH);

    // Extract exact relative path from htdocs to project root
    $baseDir = str_replace($docRoot, '', $projRoot);
    
    // Format leading/trailing slashes correctly
    $baseUrl = '/' . ltrim($baseDir, '/');
    $baseUrl = rtrim($baseUrl, '/');

    define('BASE_URL', $baseUrl);
}
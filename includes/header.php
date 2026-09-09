<?php
// includes/header.php

// 1. Defensively start session if not already initialized
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Load core configuration (defines BASE_URL, ROOT_PATH, DB credentials)
require_once __DIR__ . '/../config/config.php';

// 3. Dynamic CSS cache-busting using system file modification time
$customCssPath = (defined('ROOT_PATH') ? ROOT_PATH : __DIR__ . '/..') . '/assets/css/style.css';
$cssVersion = file_exists($customCssPath) ? filemtime($customCssPath) : time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Dynamic Page Title with XSS Defense -->
    <title><?= htmlspecialchars($pageTitle ?? 'eSkueLog - CCC E-Log & Queue Management', ENT_QUOTES, 'UTF-8'); ?></title>

    <!-- Local Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/vendor/bootstrap/css/bootstrap.min.css">

    <!-- Local Bootstrap Icons -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.min.css">

    <!-- Custom Glassmorphic Stylesheet with Cache-Busting -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/style.css?v=<?= $cssVersion; ?>">
</head>
<body>
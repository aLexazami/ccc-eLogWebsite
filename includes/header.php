<?php
// includes/header.php
require_once __DIR__ . '/../config/config.php';

if (!defined('APP_INIT')) {
    define('APP_INIT', true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'E-Log & Queue System'); ?></title>

    <!-- Local Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/vendor/bootstrap/css/bootstrap.min.css">

    <!-- Local Bootstrap Icons -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.min.css">

    <!-- Custom CSS Overrides -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/style.css">
</head>
<body class="bg-light">
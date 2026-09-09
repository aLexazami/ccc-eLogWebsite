<?php
// includes/auth_check.php

// 1. Ensure core system configuration (BASE_URL, ROOT_PATH, Session Directives) is loaded
require_once __DIR__ . '/../config/config.php';

// 2. Circuit Breaker: Skip authentication enforcement when visiting the logbook login page
$currentScript = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$loginScript    = BASE_URL . '/logbook/login.php';

if ($currentScript === $loginScript || basename($currentScript) === 'login.php') {
    return;
}

// 3. Enforce Logbook Authentication Guard
if (empty($_SESSION['logbook_authenticated']) || $_SESSION['logbook_authenticated'] !== true) {

    // Detect Asynchronous (XHR / Fetch API / JSON) Requests
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
              || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

    if ($isAjax) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status'  => 'error',
            'message' => 'Session expired or unauthorized. Authentication required.'
        ]);
        exit();
    }

    // Standard HTTP 302 Redirect for traditional web page navigation
    header("Location: " . BASE_URL . "/logbook/login.php?error=unauthorized");
    exit();
}
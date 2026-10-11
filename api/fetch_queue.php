<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Path to central config.php
$configPath = __DIR__ . '/../config/config.php';
if (file_exists($configPath)) {
    require_once $configPath;
} else {
    // Fallback PDO connection if config isn't found
    $pdo = new PDO('mysql:host=localhost;dbname=eskuelog;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}

try {
    // 1. Fetch currently called ticket
    $nowCallingStmt = $pdo->query("
        SELECT ticket_number, dept_name, transaction_title 
        FROM queue_tickets 
        WHERE status = 'calling' 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $nowCalling = $nowCallingStmt->fetch() ?: null;

    // 2. Fetch waiting tickets
    $waitingStmt = $pdo->query("
        SELECT ticket_number, dept_name, transaction_title 
        FROM queue_tickets 
        WHERE status = 'waiting' 
        ORDER BY id ASC 
        LIMIT 10
    ");
    $waitingQueue = $waitingStmt->fetchAll() ?: [];

    // 3. Fetch active departments with current ticket
    $officesStmt = $pdo->query("
        SELECT 
            d.department_name,
            d.department_code,
            (
                SELECT qt.ticket_number 
                FROM queue_tickets qt 
                WHERE (qt.dept_code = d.department_code OR qt.dept_name = d.department_name)
                  AND qt.status IN ('calling', 'serving')
                ORDER BY qt.id DESC 
                LIMIT 1
            ) AS current_ticket
        FROM departments d
        WHERE d.status = 'active' 
        ORDER BY d.department_name ASC
    ");
    $offices = $officesStmt->fetchAll() ?: [];

    echo json_encode([
        'status' => 'success',
        'now_calling' => $nowCalling,
        'waiting_queue' => $waitingQueue,
        'offices' => $offices
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
<?php
// queue_number.php - MUST BE AT THE VERY TOP BEFORE HEADER
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Store submitted modal inputs directly into $_SESSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['transaction_id'])) {
        $_SESSION['transaction_id'] = $_POST['transaction_id'];
    }
    if (!empty($_POST['transaction_title'])) {
        $_SESSION['transaction_title'] = $_POST['transaction_title'];
    }
    if (isset($_POST['inquiry_details'])) {
        $_SESSION['inquiry_details'] = $_POST['inquiry_details'];
    }
}

// Generate queue ticket prefix & number if not generated yet
if (!isset($_SESSION['queue_number'])) {
    $deptCode = $_SESSION['dept_code'] ?? 'REG'; 
    $prefix = strtoupper(substr($deptCode, 0, 1)); // First letter (e.g., 'R' for Registrar)
    $sequence = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT); 
    $_SESSION['queue_number'] = $prefix . '-' . $sequence;
}

$deptName = $_SESSION['dept_name'] ?? 'Registrar';
$queueNumber = $_SESSION['queue_number'];
$transactionTitle = $_SESSION['transaction_title'] ?? 'Request Transcript';

$pageTitle = "Your Queue Number";
$pageScript = "/assets/js/kiosk.js";

require_once __DIR__ . '/../includes/header.php';
?>

<div class="portal-bg py-5">
    <div class="container d-flex flex-column align-items-center">
        
        <!-- Navigation Header -->
        <div class="w-100 mb-4" style="max-width: 650px;">
            <a href="select_inquiry.php" class="btn btn-back text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Back to Inquiries
            </a>
        </div>

        <!-- Queue Ticket Display Card -->
        <div class="card ticket-display-card text-center p-4 p-md-5 w-100" style="max-width: 650px;">
            <h1 class="display-6 fw-bold text-white mb-1">Your Queue Number</h1>
            <p class="mb-4" style="color: var(--color-blue-gray);">Please wait for your number to be called.</p>

            <!-- Department Section -->
            <div class="ticket-dept-badge mx-auto mb-4 p-3 w-100">
                <span class="text-uppercase small fw-semibold tracking-wider d-block mb-1" style="color: var(--color-blue-gray); opacity: 0.85;">DEPARTMENT</span>
                <h3 class="fw-bold text-white mb-0"><?= htmlspecialchars($deptName) ?></h3>
            </div>

            <!-- Queue Number Display -->
            <div class="my-3">
                <span class="text-uppercase small fw-semibold tracking-wider d-block mb-1" style="color: var(--color-blue-gray); opacity: 0.85;">QUEUE NUMBER</span>
                <h1 class="ticket-queue-number display-1 fw-bolder my-0"><?= htmlspecialchars($queueNumber) ?></h1>
            </div>

            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.2);">

            <!-- Transaction Title Display -->
            <div class="mb-4">
                <span class="text-uppercase small fw-semibold tracking-wider d-block mb-1" style="color: var(--color-blue-gray); opacity: 0.85;">TRANSACTION</span>
                <h5 class="fw-bold text-white mb-0"><?= htmlspecialchars($transactionTitle) ?></h5>
            </div>

            <!-- Home Button -->
            <div class="mt-2">
                <a href="reset_kiosk.php" class="btn btn-submit-action px-5 py-2 fs-5 text-decoration-none">
                    Home
                </a>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
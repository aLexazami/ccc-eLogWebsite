<?php
// user_log/staff_dashboard.php

// 1. Session and path setup
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = defined('BASE_URL') ? BASE_URL : '../';

// 2. Security & Role Checks
$userRole = $_SESSION['user_role'] ?? $_SESSION['role'] ?? '';
$normalizedRole = strtoupper(trim($userRole));
$allowedRoles = ['STAFF', 'STAFF MEMBER', 'USER', 'SYSTEM ADMIN', 'ADMINISTRATOR', 'ADMIN'];

if (empty($normalizedRole) || !in_array($normalizedRole, $allowedRoles)) {
    header("Location: staff_login.php?error=unauthorized");
    exit();
}

// User Metadata Setup
$fullName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Staff Member';
$userInitials = 'ST';
if (!empty($fullName)) {
    $nameParts = explode(' ', trim($fullName));
    $firstInitial = $nameParts[0][0] ?? '';
    $lastInitial = isset($nameParts[1]) ? $nameParts[count($nameParts) - 1][0] : '';
    $userInitials = strtoupper($firstInitial . $lastInitial);
}

// 3. Include Global Header (if available) or render local asset head
$headerPath = __DIR__ . '/../../includes/header.php';
if (file_exists($headerPath)) {
    require_once $headerPath;
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - City College of Calamba</title>
    <!-- Local Bootstrap & Icons from Assets -->
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl); ?>assets/css/bootstrap-icons.css">
</head>
<body>
<?php } ?>

<div class="d-flex min-vh-100" style="background-color: #f4f6f9;">

    <!-- SIDEBAR NAVIGATION -->
    <aside class="text-white d-flex flex-column flex-shrink-0 p-0" style="width: 260px; background-color: #002366; min-height: 100vh;">
        
        <!-- Header Branding with ccc_banner.webp -->
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <img 
                    src="<?= htmlspecialchars($baseUrl); ?>/../../assets/img/ccc_banner.webp" 
                    alt="City College of Calamba Banner" 
                    class="img-fluid"
                    style="max-height: 48px; width: auto; object-fit: contain;"
                    onerror="this.onerror=null; this.src='<?= htmlspecialchars($baseUrl); ?>/../../assets/img/ccc-logo.png';"
                >
            </div>
            <button class="btn btn-link text-white p-0 fs-5 border-0"><i class="bi bi-list"></i></button>
        </div>

        <div class="px-3 pt-3 pb-1">
            <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">STAFF PORTAL</small>
        </div>

        <!-- Navigation Links -->
        <nav class="nav nav-pills flex-column px-2 gap-1 mt-1">
            <a href="staff_dashboard.php" class="nav-link active bg-white bg-opacity-10 text-white d-flex align-items-center gap-3 py-2 px-3 rounded">
                <i class="bi bi-house-door fs-5"></i>
                <span class="fw-medium">Dashboard</span>
            </a>
            <a href="<?= htmlspecialchars($baseUrl); ?>modules/logbook/" class="nav-link text-white-50 hover-white d-flex align-items-center gap-3 py-2 px-3 rounded">
                <i class="bi bi-people fs-5"></i>
                <span class="fw-medium">User Information</span>
            </a>
            <a href="#" class="nav-link text-white-50 hover-white d-flex align-items-center gap-3 py-2 px-3 rounded">
                <i class="bi bi-list-task fs-5"></i>
                <span class="fw-medium">Activity Log</span>
            </a>
            <a href="#" class="nav-link text-white-50 hover-white d-flex align-items-center gap-3 py-2 px-3 rounded">
                <i class="bi bi-person-badge fs-5"></i>
                <span class="fw-medium">User Log</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-grow-1 d-flex flex-column">

        <!-- TOP BAR HEADER -->
        <header class="bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center shadow-sm">
            <div class="text-muted small fw-medium">
                <?= date('D | F j, Y g:i:s A'); ?>
            </div>

            <!-- User Menu Profile -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-dark gap-2 dropdown-toggle" id="staffUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 38px; height: 38px; background-color: #002B66; font-size: 0.85rem;">
                        <?= htmlspecialchars($userInitials); ?>
                    </div>
                    <div class="text-start lh-sm">
                        <div class="fw-bold small mb-0"><?= htmlspecialchars($fullName); ?></div>
                        <small class="text-muted" style="font-size: 0.7rem;"><?= htmlspecialchars($normalizedRole); ?></small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="staffUserDropdown">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>My Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="staff_login.php"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
                </ul>
            </div>
        </header>

        <!-- MAIN CONTAINER -->
        <main class="p-4 flex-grow-1">

            <!-- Breadcrumb Navigation -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold text-dark mb-0" style="color: #0d1b2a;">Staff Dashboard</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Staff Dashboard</li>
                    </ol>
                </nav>
            </div>

            <!-- STATS COUNTER CARDS -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 rounded-3 bg-white">
                        <div class="d-flex align-items-center">
                            <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 me-3">
                                <i class="bi bi-journal-check fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase fw-bold text-muted small mb-0">Logs Today</h6>
                                <h3 class="fw-bold text-dark mb-0">128</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 rounded-3 bg-white">
                        <div class="d-flex align-items-center">
                            <div class="p-3 bg-info bg-opacity-10 text-info rounded-3 me-3">
                                <i class="bi bi-people fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase fw-bold text-muted small mb-0">Active Users</h6>
                                <h3 class="fw-bold text-dark mb-0">42</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 rounded-3 bg-white">
                        <div class="d-flex align-items-center">
                            <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 me-3">
                                <i class="bi bi-check-circle fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase fw-bold text-muted small mb-0">Shift Status</h6>
                                <h3 class="fw-bold text-success mb-0">Active</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODULE SHORTCUT CARDS -->
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center rounded-3 bg-white d-flex flex-column justify-content-between">
                        <div>
                            <div class="p-3 bg-light text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-journal-plus fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">User Information Module</h5>
                            <p class="small text-muted mb-0">Manage user details, update records, and verify visitor check-ins.</p>
                        </div>
                        <a href="<?= htmlspecialchars($baseUrl); ?>modules/logbook/" class="btn btn-primary w-100 mt-4 rounded-3 fw-semibold">Access Module</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center rounded-3 bg-white d-flex flex-column justify-content-between">
                        <div>
                            <div class="p-3 bg-light text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-activity fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Activity Monitor</h5>
                            <p class="small text-muted mb-0">View real-time system activities and user operations logged today.</p>
                        </div>
                        <a href="#" class="btn btn-primary w-100 mt-4 rounded-3 fw-semibold">View Activity</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center rounded-3 bg-white d-flex flex-column justify-content-between">
                        <div>
                            <div class="p-3 bg-light text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-clock-history fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">User Log</h5>
                            <p class="small text-muted mb-0">Track entry logs, session records, and account activity histories.</p>
                        </div>
                        <a href="#" class="btn btn-primary w-100 mt-4 rounded-3 fw-semibold">View Logs</a>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php 
// 4. Include Global Footer or render local Bootstrap JS script from assets
$footerPath = __DIR__ . '/../../includes/footer.php';
if (file_exists($footerPath)) {
    require_once $footerPath;
} else {
?>
<!-- Local Bootstrap JS Bundle from Assets -->
<script src="<?= htmlspecialchars($baseUrl); ?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
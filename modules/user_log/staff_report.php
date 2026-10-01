<?php
// user_log/staff_report.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = defined('BASE_URL') ? BASE_URL : '../';

// Security & Role Checks
$userRole = $_SESSION['user_role'] ?? $_SESSION['role'] ?? '';
$normalizedRole = strtoupper(trim($userRole));
$allowedRoles = ['STAFF', 'STAFF MEMBER', 'USER', 'SYSTEM ADMIN', 'ADMINISTRATOR', 'ADMIN'];

if (empty($normalizedRole) || !in_array($normalizedRole, $allowedRoles)) {
    header("Location: staff_login.php?error=unauthorized");
    exit();
}

$fullName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Juan Dela Cruz';
$userInitials = 'ST';
if (!empty($fullName)) {
    $nameParts = explode(' ', trim($fullName));
    $firstInitial = $nameParts[0][0] ?? '';
    $lastInitial = isset($nameParts[1]) ? $nameParts[count($nameParts) - 1][0] : '';
    $userInitials = strtoupper($firstInitial . $lastInitial);
}

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
    <title>Reports & Analytics - City College of Calamba</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl); ?>assets/css/bootstrap-icons.css">
</head>
<body style="background-color: #f4f6f9;">
<?php } ?>

<style>
    .hover-white:hover { color: #fff !important; }
    @media (min-width: 992px) {
        .app-layout { flex-direction: row !important; height: 100vh !important; overflow: hidden !important; }
        .app-sidebar { width: 250px !important; min-width: 250px !important; height: 100vh !important; }
        .app-sidebar-collapse { display: block !important; }
        .app-main-content { height: 100vh !important; overflow: hidden !important; }
    }
    @media (max-width: 991.98px) {
        .app-layout { flex-direction: column !important; height: auto !important; overflow: auto !important; }
        .app-sidebar { width: 100% !important; min-width: 100% !important; height: auto !important; }
        .app-main-content { height: auto !important; overflow: visible !important; }
    }
</style>

<div class="d-flex app-layout bg-light">

    <!-- SIDEBAR NAVIGATION -->
    <aside class="text-white d-flex flex-column flex-shrink-0 p-0 app-sidebar" style="background-color: #002366;">
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <img src="<?= htmlspecialchars($baseUrl); ?>/../../assets/img/ccc_banner.webp" alt="City College of Calamba Banner" class="img-fluid" style="max-height: 40px; width: auto; object-fit: contain;" onerror="this.onerror=null; this.src='<?= htmlspecialchars($baseUrl); ?>/../../assets/img/ccc-logo.png';">
            </div>
            <button class="btn btn-outline-light d-lg-none py-1 px-2 border-opacity-50" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapseNav"><i class="bi bi-list fs-4"></i></button>
        </div>

        <div class="collapse app-sidebar-collapse flex-grow-1" id="sidebarCollapseNav">
            <div class="px-3 pt-3 pb-1"><small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">STAFF PORTAL</small></div>
            <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
            <nav class="nav nav-pills flex-column px-2 gap-1 mt-1 pb-3">
                <a href="staff_dashboard.php" class="nav-link <?= ($currentPage === 'staff_dashboard.php') ? 'active bg-white bg-opacity-10 text-white' : 'text-white-50 hover-white'; ?> d-flex align-items-center gap-3 py-2 px-3 rounded"><i class="bi bi-house-door fs-5"></i><span class="fw-medium">Dashboard</span></a>
                <a href="staff_service.php" class="nav-link <?= ($currentPage === 'staff_service.php') ? 'active bg-white bg-opacity-10 text-white' : 'text-white-50 hover-white'; ?> d-flex align-items-center gap-3 py-2 px-3 rounded"><i class="bi bi-clock-history fs-5"></i><span class="fw-medium">Service History</span></a>
                <a href="staff_report.php" class="nav-link <?= ($currentPage === 'staff_report.php') ? 'active bg-white bg-opacity-10 text-white' : 'text-white-50 hover-white'; ?> d-flex align-items-center gap-3 py-2 px-3 rounded"><i class="bi bi-bar-chart-line fs-5"></i><span class="fw-medium">Reports</span></a>
            </nav>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-grow-1 d-flex flex-column app-main-content overflow-hidden">
        <header class="bg-white border-bottom px-3 px-md-4 py-2 d-flex justify-content-between align-items-center shadow-sm flex-shrink-0" style="min-height: 56px;">
            <div class="text-muted small fw-medium text-truncate me-2"><?= date('D | F j, Y g:i:s A'); ?></div>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-dark gap-2 dropdown-toggle" id="staffUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width: 32px; height: 32px; background-color: #002B66; font-size: 0.8rem;"><?= htmlspecialchars($userInitials); ?></div>
                    <div class="text-start lh-sm d-none d-sm-block">
                        <div class="fw-bold small mb-0" style="font-size: 0.8rem;"><?= htmlspecialchars($fullName); ?></div>
                        <small class="text-muted" style="font-size: 0.65rem;"><?= htmlspecialchars($normalizedRole); ?></small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="staffUserDropdown">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>My Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="staff_login.php"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
                </ul>
            </div>
        </header>

        <main class="p-2 p-md-3 flex-grow-1 d-flex flex-column overflow-auto">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-3 flex-shrink-0 gap-1">
                <h4 class="fw-bold text-dark mb-0">Reports & Analytics</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="staff_dashboard.php" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reports</li>
                    </ol>
                </nav>
            </div>

            <!-- Summary Stat Cards -->
            <div class="row g-3 mb-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                        <span class="text-muted small fw-semibold">Total Served Today</span>
                        <h3 class="fw-bold text-primary mb-0 mt-1">42</h3>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                        <span class="text-muted small fw-semibold">Avg. Handling Time</span>
                        <h3 class="fw-bold text-success mb-0 mt-1">4m 12s</h3>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                        <span class="text-muted small fw-semibold">Peak Waiting Time</span>
                        <h3 class="fw-bold text-warning mb-0 mt-1">11m</h3>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                        <span class="text-muted small fw-semibold">Skipped / No-Show</span>
                        <h3 class="fw-bold text-danger mb-0 mt-1">3</h3>
                    </div>
                </div>
            </div>

            <!-- Summary Table -->
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 flex-grow-1 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Daily Summary Breakdown</h6>
                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-download me-1"></i> Export Report</button>
                </div>
                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover align-middle mb-0 small text-nowrap">
                        <thead class="table-light text-muted">
                            <tr>
                                <th>Service Name</th>
                                <th>Total Tickets</th>
                                <th>Completed</th>
                                <th>Skipped</th>
                                <th>Avg. Wait Time</th>
                                <th>Avg. Service Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">Payment Transaction</td>
                                <td>28</td>
                                <td>25</td>
                                <td>2</td>
                                <td>5m</td>
                                <td>8m</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Enrollment</td>
                                <td>15</td>
                                <td>12</td>
                                <td>1</td>
                                <td>8m</td>
                                <td>10m</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Certification</td>
                                <td>9</td>
                                <td>8</td>
                                <td>0</td>
                                <td>3m</td>
                                <td>5m</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php 
$footerPath = __DIR__ . '/../../includes/footer.php';
if (file_exists($footerPath)) {
    require_once $footerPath;
} else {
?>
<script src="<?= htmlspecialchars($baseUrl); ?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
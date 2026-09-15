<?php
// user_log/admin_dashboard.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Pathing: Base folder is one level up ('..')
$baseUrl = defined('BASE_URL') ? BASE_URL : '..';
require_once __DIR__ . '/../includes/header.php';

// 2. RBAC Access Verification
$userRole = strtolower(trim($_SESSION['user_role'] ?? ''));
if (!isset($_SESSION['user_id']) || !in_array($userRole, ['system admin', 'system administrator', 'admin', 'administrator'], true)) {
    header("Location: staff_login.php?error=unauthorized");
    exit();
}

$fullName = $_SESSION['full_name'] ?? 'System Administrator';
?>

<div class="portal-bg min-vh-100 d-flex flex-column justify-content-between position-relative">

    <!-- TOP HEADER / BANNER BACKDROP INTEGRATION -->
    <div class="banner-crop-container">
        <img
            src="<?= $baseUrl; ?>/assets/img/header-backdrop.png"
            alt="City College of Calamba Header Banner"
            class="banner-zoom-img"
            loading="eager"
        >
    </div>

    <!-- MAIN DASHBOARD CONTENT -->
    <div class="container my-auto py-4 position-relative z-1">
        
        <!-- DASHBOARD HEADER BAR -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <span class="badge bg-danger text-uppercase px-3 py-2 rounded-pill fw-bold shadow-sm mb-1">
                    <i class="bi bi-shield-lock-fill me-1"></i> System Administrator Control Panel
                </span>
                <h2 class="fw-extrabold text-dark mb-0">Welcome back, <?= htmlspecialchars($fullName); ?></h2>
            </div>
            <div>
                <a href="staff_login.php" class="btn-back text-decoration-none">
                    <span class="btn-back-icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span>Sign Out</span>
                </a>
            </div>
        </div>

        <!-- STATS OVERVIEW CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card custom-card p-3 border-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-badge-wrapper me-3">
                            <div class="icon-badge"><i class="bi bi-people-fill fs-3"></i></div>
                        </div>
                        <div>
                            <h6 class="text-uppercase fw-bold text-muted small mb-0">Total Active Staff</h6>
                            <h3 class="fw-extrabold text-dark mb-0">24</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card p-3 border-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-badge-wrapper me-3">
                            <div class="icon-badge"><i class="bi bi-activity fs-3"></i></div>
                        </div>
                        <div>
                            <h6 class="text-uppercase fw-bold text-muted small mb-0">Queue Logs Today</h6>
                            <h3 class="fw-extrabold text-dark mb-0">342</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card p-3 border-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-badge-wrapper me-3">
                            <div class="icon-badge"><i class="bi bi-hdd-network-fill fs-3"></i></div>
                        </div>
                        <div>
                            <h6 class="text-uppercase fw-bold text-muted small mb-0">System Status</h6>
                            <h3 class="fw-extrabold text-success mb-0"><i class="bi bi-check-circle-fill me-1"></i> Optimal</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADMIN ACTION MODULES -->
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card custom-card h-100 p-4 border-0 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-badge-wrapper mx-auto mb-3">
                            <div class="icon-badge"><i class="bi bi-person-gear fs-2"></i></div>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">User Accounts</h5>
                        <p class="small text-muted">Manage system administrators, staff privileges, and account credentials.</p>
                    </div>
                    <a href="#" class="btn-portal btn-oval w-100 mt-3">
                        <span class="btn-label">Manage Users</span>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card custom-card h-100 p-4 border-0 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-badge-wrapper mx-auto mb-3">
                            <div class="icon-badge"><i class="bi bi-journal-text fs-2"></i></div>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Audit Logs</h5>
                        <p class="small text-muted">Inspect system-wide security, login activities, and transaction histories.</p>
                    </div>
                    <a href="#" class="btn-portal btn-oval w-100 mt-3">
                        <span class="btn-label">View Logs</span>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card custom-card h-100 p-4 border-0 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-badge-wrapper mx-auto mb-3">
                            <div class="icon-badge"><i class="bi bi-sliders fs-2"></i></div>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Queue Config</h5>
                        <p class="small text-muted">Configure queue ticket prefixes, counter displays, and department routing.</p>
                    </div>
                    <a href="#" class="btn-portal btn-oval w-100 mt-3">
                        <span class="btn-label">Settings</span>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card custom-card h-100 p-4 border-0 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-badge-wrapper mx-auto mb-3">
                            <div class="icon-badge"><i class="bi bi-bar-chart-line-fill fs-2"></i></div>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Reports</h5>
                        <p class="small text-muted">Export analytical summaries of daily wait times and total transactions.</p>
                    </div>
                    <a href="#" class="btn-portal btn-oval w-100 mt-3">
                        <span class="btn-label">Export Data</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- SYSTEM FOOTER -->
    <div class="text-center py-3 small fw-bold position-relative z-1" style="color: #ffffff; text-shadow: 0 1px 3px rgba(0,0,0,0.6);">
        &copy; <?= date('Y'); ?> City College of Calamba &bull; All Rights Reserved
    </div>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
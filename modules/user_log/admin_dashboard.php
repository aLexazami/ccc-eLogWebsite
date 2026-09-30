<?php
// src/Views/Admin/admin_dashboard.php
defined('DOMAIN_PATH') || define('DOMAIN_PATH', dirname(__DIR__, 3));
require_once DOMAIN_PATH . '/config/config.php';
require_once CL_SESSION_PATH;
require_once CONNECT_PATH;
require_once GLOBAL_FUNC;
require_once HELPER;

# Session and Access Verification
$g_user_role = $session_class->getValue('user_role');
$g_user_role_upper = !empty($g_user_role) ? strtoupper(trim($g_user_role)) : '';

if (empty($g_user_role_upper) || !in_array($g_user_role_upper, ['ADMIN', 'DEPARTMENT_STAFF'])) {
    header("Location: " . BASE_URL . "login");
    exit();
}

$fullName = $session_class->getValue('full_name') ?? 'Marlon Reolo';
$userInitials = 'MR';
$pageTitle = "Dashboard - " . SYSTEM_NAME;

// Load standard header
require_once PUBLIC_PATH . '/includes/header.php';
?>

<div class="d-flex min-vh-100" style="background-color: #f4f6f9;">

    <!-- SIDEBAR NAVIGATION -->
    <aside class="bg-primary-dark text-white d-flex flex-column flex-shrink-0 p-0" style="width: 260px; background-color: #002B66;">
        <!-- CCC Logo Header -->
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2">
                <img src="<?= BASE_URL; ?>assets/img/ccc-logo.png" alt="CCC Logo" style="height: 38px;">
                <div>
                    <h6 class="fw-bold mb-0 text-white lh-1" style="font-size: 0.85rem;">CITY COLLEGE OF</h6>
                    <small class="fw-black tracking-wider text-white-50" style="font-size: 0.75rem;">CALAMBA</small>
                </div>
            </div>
            <button class="btn btn-link text-white p-0 fs-5"><i class="bi bi-list"></i></button>
        </div>

        <div class="px-3 pt-3 pb-1">
            <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">ADMINISTRATOR</small>
        </div>

        <!-- Navigation Links -->
        <nav class="nav nav-pills flex-column px-2 gap-1 mt-1">
            <a href="<?= BASE_URL; ?>admin/dashboard" class="nav-link active bg-white bg-opacity-10 text-white d-flex align-items-center gap-3 py-2 px-3 rounded">
                <i class="bi bi-house-door-fill fs-5"></i>
                <span class="fw-medium">Dashboard</span>
            </a>
            <a href="<?= BASE_URL; ?>admin/user-information" class="nav-link text-white-50 hover-white d-flex align-items-center gap-3 py-2 px-3 rounded">
                <i class="bi bi-people-fill fs-5"></i>
                <span class="fw-medium">User Information</span>
            </a>
            
            <div class="nav-item">
                <a class="nav-link text-white-50 d-flex align-items-center justify-content-between py-2 px-3 rounded" data-bs-toggle="collapse" href="#activityLogSub">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-list-task fs-5"></i>
                        <span class="fw-medium">Activity Log</span>
                    </div>
                    <i class="bi bi-chevron-down small"></i>
                </a>
            </div>

            <div class="nav-item">
                <a class="nav-link text-white-50 d-flex align-items-center justify-content-between py-2 px-3 rounded" data-bs-toggle="collapse" href="#userLogSub">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-journal-text fs-5"></i>
                        <span class="fw-medium">User Log</span>
                    </div>
                    <i class="bi bi-chevron-down small"></i>
                </a>
            </div>
        </nav>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-grow-1 d-flex flex-column">

        <!-- TOP BAR / HEADER -->
        <header class="bg-white border-bottom px-4 py-2 d-flex justify-content-between align-items-center shadow-sm">
            <div class="text-muted small fw-medium">
                <?= date('D | F j, Y g:i:s A'); ?>
            </div>
            
            <!-- User Profile Dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-dark gap-2 dropdown-toggle" id="userMenu" data-bs-toggle="dropdown">
                    <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.85rem;">
                        <?= htmlspecialchars($userInitials); ?>
                    </div>
                    <div class="text-start lh-sm">
                        <div class="fw-bold small mb-0"><?= htmlspecialchars($fullName); ?></div>
                        <small class="text-muted" style="font-size: 0.7rem;"><?= htmlspecialchars($g_user_role_upper); ?></small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="<?= BASE_URL; ?>profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL; ?>admin/sign-out"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
                </ul>
            </div>
        </header>

        <!-- DASHBOARD CONTAINER -->
        <main class="p-4 flex-grow-1">
            
            <!-- BREADCRUMB HEADER -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold text-dark mb-0" style="color: #1A2530;">Dashboard</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>admin/dashboard" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>

            <!-- STATS OVERVIEW CARDS -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 rounded-3">
                        <div class="d-flex align-items-center">
                            <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 me-3">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase fw-bold text-muted small mb-0">Total Active Staff</h6>
                                <h3 class="fw-extrabold text-dark mb-0">24</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 rounded-3">
                        <div class="d-flex align-items-center">
                            <div class="p-3 bg-info bg-opacity-10 text-info rounded-3 me-3">
                                <i class="bi bi-activity fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase fw-bold text-muted small mb-0">Queue Logs Today</h6>
                                <h3 class="fw-extrabold text-dark mb-0">342</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3 rounded-3">
                        <div class="d-flex align-items-center">
                            <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 me-3">
                                <i class="bi bi-hdd-network-fill fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase fw-bold text-muted small mb-0">System Status</h6>
                                <h3 class="fw-extrabold text-success mb-0"><i class="bi bi-check-circle-fill me-1"></i> Optimal</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACTION MODULE CARDS -->
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center d-flex flex-column justify-content-between rounded-3">
                        <div>
                            <div class="p-3 bg-light text-primary rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-person-gear fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">User Accounts</h5>
                            <p class="small text-muted">Manage system administrators, staff privileges, and credentials.</p>
                        </div>
                        <a href="<?= BASE_URL; ?>admin/user-information" class="btn btn-primary w-100 mt-3 rounded-pill fw-semibold">Manage Users</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center d-flex flex-column justify-content-between rounded-3">
                        <div>
                            <div class="p-3 bg-light text-primary rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-journal-text fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Audit Logs</h5>
                            <p class="small text-muted">Inspect system security, login activities, and transaction logs.</p>
                        </div>
                        <a href="#" class="btn btn-primary w-100 mt-3 rounded-pill fw-semibold">View Logs</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center d-flex flex-column justify-content-between rounded-3">
                        <div>
                            <div class="p-3 bg-light text-primary rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-sliders fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Queue Config</h5>
                            <p class="small text-muted">Configure ticket prefixes, display counters, and office routing.</p>
                        </div>
                        <a href="#" class="btn btn-primary w-100 mt-3 rounded-pill fw-semibold">Settings</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center d-flex flex-column justify-content-between rounded-3">
                        <div>
                            <div class="p-3 bg-light text-primary rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-bar-chart-line-fill fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Reports</h5>
                            <p class="small text-muted">Export analytical summaries of wait times and total transactions.</p>
                        </div>
                        <a href="#" class="btn btn-primary w-100 mt-3 rounded-pill fw-semibold">Export Data</a>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php require_once PUBLIC_PATH . '/includes/footer.php'; ?>
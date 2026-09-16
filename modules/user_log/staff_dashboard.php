<?php
// user_log/staff_dashboard.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Pathing: Base folder is one level up ('..')
$baseUrl = defined('BASE_URL') ? BASE_URL : '..';
require_once __DIR__ . '/../includes/header.php';

// 2. RBAC Access Verification
if (!isset($_SESSION['user_id'])) {
    header("Location: staff_login.php?error=unauthorized");
    exit();
}

$fullName = $_SESSION['full_name'] ?? 'Staff Member';
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
                <span class="badge bg-primary text-uppercase px-3 py-2 rounded-pill fw-bold shadow-sm mb-1">
                    <i class="bi bi-person-badge-fill me-1"></i> Staff Operational Console
                </span>
                <h2 class="fw-extrabold text-dark mb-0">Hello, <?= htmlspecialchars($fullName); ?></h2>
            </div>
            <div>
                <a href="staff_login.php" class="btn-back text-decoration-none">
                    <span class="btn-back-icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span>Sign Out</span>
                </a>
            </div>
        </div>

        <!-- OPERATIONAL ACTION MODULES -->
        <div class="row g-4 justify-content-center">
            
            <div class="col-md-6 col-lg-4">
                <div class="card custom-card h-100 p-4 border-0 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-badge-wrapper mx-auto mb-3">
                            <div class="icon-badge"><i class="bi bi-display-fill fs-2"></i></div>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Live Queue Counter</h4>
                        <p class="small text-muted">Call next numbers, transfer tickets, or complete ongoing transactions.</p>
                    </div>
                    <a href="#" class="btn-portal btn-oval w-100 mt-3">
                        <span class="btn-label"><i class="bi bi-play-circle-fill me-2"></i>Open Counter</span>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card custom-card h-100 p-4 border-0 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-badge-wrapper mx-auto mb-3">
                            <div class="icon-badge"><i class="bi bi-clock-history fs-2"></i></div>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Daily Log History</h4>
                        <p class="small text-muted">Review transactions serviced by your account during today's shift.</p>
                    </div>
                    <a href="#" class="btn-portal btn-oval w-100 mt-3">
                        <span class="btn-label"><i class="bi bi-card-checklist me-2"></i>View History</span>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card custom-card h-100 p-4 border-0 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-badge-wrapper mx-auto mb-3">
                            <div class="icon-badge"><i class="bi bi-ticket-perforated-fill fs-2"></i></div>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Issue Ticket</h4>
                        <p class="small text-muted">Manually issue or print physical queue tickets for walk-in visitors.</p>
                    </div>
                    <a href="#" class="btn-portal btn-oval w-100 mt-3">
                        <span class="btn-label"><i class="bi bi-printer-fill me-2"></i>Issue Ticket</span>
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
<?php
// index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "eSkueLog - CCC E-Log & Queue Management System";

// 1. Include the reusable Header (loads Bootstrap CSS & config)
require_once __DIR__ . '/includes/header.php';
?>

<div class="portal-bg min-vh-100 d-flex flex-column justify-content-between">
    
    <!-- CCC BANNER (Local File Asset) -->
    <div class="banner-crop-container">
        <img
            src="<?= BASE_URL; ?>/assets/img/header-backdrop.png"
            alt="City College of Calamba Header Banner"
            class="banner-zoom-img"
            loading="eager"
        >
    </div>

    <!-- MAIN PAGE CONTENT (Centered in Grid Container) -->
    <div class="container my-auto py-4">
        
        <!-- HEADER & MODERN LIVE CLOCK -->
        <header class="text-center mb-5">
            <!-- System Title -->
            <h1 class="app-title fw-black tracking-tight mb-4">eSkueLog</h1>

            <!-- Centered Diamond Glass Clock Card Widget -->
            <div class="modern-clock-card mx-auto p-3 p-md-4 rounded-4">
                <div class="d-flex align-items-baseline justify-content-center gap-1 gap-sm-2 mb-1">
                    <span class="clock-time" id="clockTime">00:00:00</span>
                    <span class="clock-period text-uppercase" id="clockPeriod">AM</span>
                </div>
                <div class="clock-date text-uppercase tracking-wide" id="clockDate">Loading date...</div>
            </div>
        </header>

        <!-- MODULE SELECTION SECTION (3 CORE PORTALS) -->
        <div class="row g-4 justify-content-center">

            <!-- 1. DIGITAL LOGBOOK -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 custom-card">
                    <div class="card-body p-4 d-flex flex-column text-center align-items-center">
                        <div class="icon-badge-wrapper my-3">
                            <div class="icon-badge">
                                <i class="bi bi-journal-check fs-1" aria-hidden="true"></i>
                            </div>
                        </div>
                        <h3 class="fw-extrabold mb-4" style="color: var(--color-text-primary);">Digital Logbook</h3>

                        <a href="<?= BASE_URL; ?>/logbook/" class="btn btn-portal btn-oval w-100 py-3 mt-auto shadow-sm" role="button">
                            <span class="btn-label fw-semibold">Check-In</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. QUEUE MONITOR -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 custom-card">
                    <div class="card-body p-4 d-flex flex-column text-center align-items-center">
                        <div class="icon-badge-wrapper my-3">
                            <div class="icon-badge">
                                <i class="bi bi-broadcast fs-1" aria-hidden="true"></i>
                            </div>
                        </div>
                        <h3 class="fw-extrabold mb-4" style="color: var(--color-text-primary);">Live Queue</h3>

                        <a href="<?= BASE_URL; ?>/queue_list/live_queue.php" class="btn btn-portal btn-oval w-100 py-3 mt-auto shadow-sm" role="button">
                            <span class="btn-label fw-semibold">View Queue</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. STAFF & ADMIN CONSOLE -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 custom-card">
                    <div class="card-body p-4 d-flex flex-column text-center align-items-center">
                        <div class="icon-badge-wrapper my-3">
                            <div class="icon-badge">
                                <i class="bi bi-shield-lock-fill fs-1" aria-hidden="true"></i>
                            </div>
                        </div>
                        <h3 class="fw-extrabold mb-4" style="color: var(--color-text-primary);">Staff Portal</h3>

                        <a href="<?= BASE_URL; ?>/user_log/staff_login.php" class="btn btn-portal btn-oval w-100 py-3 mt-auto shadow-sm" role="button">
                            <span class="btn-label fw-semibold">Login</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- SYSTEM FOOTER INFO -->
        <div class="text-center mt-5">
            <p class="small mb-0 fw-bold" style="color: var(--color-text-muted);">
                &copy; <?= date('Y'); ?> City College of Calamba &bull; All Rights Reserved
            </p>
        </div>

    </div>
</div>

<!-- Clock Module JS Inclusion with Dynamic Cache-Busting -->
<?php
$clockJsPath = ROOT_PATH . '/assets/js/clock.js';
$clockJsVersion = file_exists($clockJsPath) ? filemtime($clockJsPath) : time();
?>
<script src="<?= BASE_URL; ?>/assets/js/clock.js?v=<?= $clockJsVersion; ?>"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
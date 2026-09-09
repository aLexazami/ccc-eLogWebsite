<?php
// index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "eSkueLog - CCC E-Log & Queue Management System";

// 1. Include the reusable Header (loads Bootstrap CSS & config)
require_once __DIR__ . '/includes/header.php';
?>

<div class="portal-bg text-white min-vh-100 d-flex flex-column justify-content-between">
    
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
    <div class="container my-auto py-2">
        
        <!-- HEADER & MODERN LIVE CLOCK -->
        <header class="text-center mb-3">
            <!-- System Title with Bright White Highlight Glow -->
            <h1 class="app-title fw-bold tracking-tight mb-5">eSkueLog</h1>

            <!-- Centered Glassmorphic Clock Card Widget -->
            <div class="modern-clock-card mx-auto shadow-lg p-3 p-md-4 rounded-4">
                <div class="d-flex align-items-baseline justify-content-center gap-1 gap-sm-2 mb-1">
                    <span class="clock-time fw-black" id="clockTime">00:00:00</span>
                    <span class="clock-period fw-bold text-uppercase" id="clockPeriod">AM</span>
                </div>
                <div class="clock-date fw-medium" id="clockDate">Loading date...</div>
            </div>
        </header>

        <!-- MODULE SELECTION SECTION (3 CORE PORTALS) -->
        <div class="row g-4 justify-content-center">

            <!-- 1. DIGITAL LOGBOOK -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 custom-card shadow rounded-4">
                    <div class="card-body p-4 d-flex flex-column text-center">
                        <div class="icon-badge mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-check fs-1" style="color: var(--color-powder-blue);" aria-hidden="true"></i>
                        </div>
                        <h4 class="text-white mb-2 fw-bold">Digital Logbook</h4>

                        <a href="<?= BASE_URL; ?>/logbook/" class="btn btn-portal btn-oval w-100 mt-3 shadow-sm" role="button">
                            <span class="btn-label fw-semibold">Check-In</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. QUEUE MONITOR (Redirects directly to live_queue.php) -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 custom-card shadow rounded-4">
                    <div class="card-body p-4 d-flex flex-column text-center">
                        
                        <div class="icon-badge mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-broadcast fs-1" style="color: var(--color-powder-blue);" aria-hidden="true"></i>
                        </div>
                        
                        <h4 class="text-white mb-2 fw-bold">Live Queue</h4>

                        <!-- Updated button destination to live_queue.php -->
                        <a href="<?= BASE_URL; ?>/queue_list/live_queue.php" class="btn btn-portal btn-oval w-100 mt-3 shadow-sm" role="button">
                            <span class="btn-label fw-semibold">View Queue</span>
                        </a>

                    </div>
                </div>
            </div>

            <!-- 3. STAFF & ADMIN CONSOLE -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 custom-card shadow rounded-4">
                    <div class="card-body p-4 d-flex flex-column text-center">
                        
                        <div class="icon-badge mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-shield-lock-fill fs-1" style="color: var(--color-powder-blue);" aria-hidden="true"></i>
                        </div>
                        
                        <h4 class="text-white mb-2 fw-bold">User Login</h4>

                        <a href="<?= BASE_URL; ?>/admin/" class="btn btn-portal btn-oval w-100 mt-3 shadow-sm" role="button">
                            <span class="btn-label fw-semibold">Login</span>
                        </a>

                    </div>
                </div>
            </div>

        </div>

        <!-- SYSTEM FOOTER INFO -->
        <div class="text-center mt-5">
            <p class="small mb-0" style="color: var(--color-blue-gray);">
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
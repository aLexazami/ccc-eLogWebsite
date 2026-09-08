<?php
// index.php
$pageTitle = "eSkueLog - CCC E-Log & Queue Management System";

// 1. Include the reusable Header (loads Bootstrap CSS & config)
require_once __DIR__ . '/includes/header.php';
?>

<div class="portal-bg text-white min-vh-100 d-flex flex-column justify-content-between">
    
    <!-- CCC BANNER -->
    <div class="banner-crop-container shadow-sm">
        <img 
            src="https://ccc.edu.ph/public/upload/images/Logo/header-backdrop.webp" 
            alt="City College of Calamba Header Banner" 
            class="banner-zoom-img" 
            loading="eager"
        >
    </div>

    <!-- MAIN PAGE CONTENT (Centered in Grid Container) -->
    <div class="container my-auto py-3">
        
        <!-- HEADER & MODERN LIVE CLOCK -->
        <header class="text-center mb-5">
            <!-- System Title -->
            <h1 class="fw-bold tracking-tight mb-3" style="color: var(--color-powder-blue); font-size: 2.75rem;">eSkueLog</h1>

            <!-- Centered Glassmorphic Clock Card Widget -->
            <div class="modern-clock-card mx-auto shadow-lg p-3 p-md-4 rounded-4">
                <div class="d-flex align-items-baseline justify-content-center gap-2 mb-1">
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
                        <div class="icon-badge rounded-4 mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-check fs-1" style="color: var(--color-powder-blue);"></i>
                        </div>
                        <h4 class="fw-bold text-white mb-2">Digital Logbook</h4>
                        <p class="small flex-grow-1" style="color: var(--color-powder-blue); opacity: 0.85;">
                            Record your visit details in the digital logbook, specify department purpose, and generate your queue ticket.
                        </p>
                        <a href="<?= BASE_URL; ?>/kiosk/" class="btn btn-portal btn-lg w-100 py-2 mt-3 rounded-3 shadow-sm">
                            Check-In <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. QUEUE MONITOR -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 custom-card shadow rounded-4">
                    <div class="card-body p-4 d-flex flex-column text-center">
                        <div class="icon-badge rounded-4 mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-broadcast fs-1" style="color: var(--color-powder-blue);"></i>
                        </div>
                        <h4 class="fw-bold text-white mb-2">Live Queue</h4>
                        <p class="small flex-grow-1" style="color: var(--color-powder-blue); opacity: 0.85;">
                            View current queue numbers and see which offices are currently serving.
                        </p>
                        <a href="<?= BASE_URL; ?>/monitor/" class="btn btn-portal btn-lg w-100 py-2 mt-3 rounded-3 shadow-sm">
                            View Queue <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. STAFF & ADMIN CONSOLE -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 custom-card shadow rounded-4">
                    <div class="card-body p-4 d-flex flex-column text-center">
                        <div class="icon-badge rounded-4 mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-shield-lock-fill fs-1" style="color: var(--color-powder-blue);"></i>
                        </div>
                        <h4 class="fw-bold text-white mb-2">User Login</h4>
                        <p class="small flex-grow-1" style="color: var(--color-powder-blue); opacity: 0.85;">
                            Access the staff and admin console to manage logs, queues, and system settings.
                        </p>
                        <a href="<?= BASE_URL; ?>/admin/" class="btn btn-portal btn-lg w-100 py-2 mt-3 rounded-3 shadow-sm">
                            Login <i class="bi bi-arrow-right ms-1"></i>
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

<!-- Vanilla JS for High-Precision Modern Digital Clock -->
<script>
    function updateClock() {
        const timeEl = document.getElementById('clockTime');
        const periodEl = document.getElementById('clockPeriod');
        const dateEl = document.getElementById('clockDate');

        if (!timeEl || !periodEl || !dateEl) return;

        const now = new Date();

        // Format Time Parts (12-Hour Format with seconds)
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const period = hours >= 12 ? 'PM' : 'AM';

        hours = hours % 12;
        hours = hours ? hours : 12; // Handle midnight (0 -> 12)
        const formattedHours = String(hours).padStart(2, '0');

        // Update DOM nodes
        timeEl.textContent = `${formattedHours}:${minutes}:${seconds}`;
        periodEl.textContent = period;

        // Format Date Part
        dateEl.textContent = now.toLocaleDateString('en-US', {
            weekday: 'long',
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    setInterval(updateClock, 1000);
    updateClock();
</script>

<?php
// 2. Include the reusable Footer (loads Bootstrap JS)
require_once __DIR__ . '/includes/footer.php';
?>
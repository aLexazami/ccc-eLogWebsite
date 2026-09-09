<?php
// queue_list/live_queue.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Live Queue Display";

$cssPath = __DIR__ . '/../assets/css/style.css';
$cssVersion = file_exists($cssPath) ? filemtime($cssPath) : time();

$baseUrl = defined('BASE_URL') ? BASE_URL : '..'; 
$extraCss = '<link rel="stylesheet" href="' . $baseUrl . '/assets/css/style.css?v=' . $cssVersion . '">';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="main-wrapper">

    <!-- TOP HEADER: Back Button, Title, and Clock in One Single Row -->
    <div class="top-header-row d-flex justify-content-between align-items-center mb-1">
        <!-- Left: Back Button -->
        <div class="header-left">
            <a href="<?= $baseUrl; ?>/index.php" class="btn-back">
                <span class="btn-back-icon"><i class="bi bi-arrow-left"></i></span>
                <span class="btn-back-label">Back to Main</span>
            </a>
        </div>

        <!-- Center: App Title -->
        <div class="header-center text-center">
            <h2 class="app-title mb-0">Live Queue Display</h2>
        </div>

        <!-- Right: Clock Widget -->
        <div class="header-right">
            <div class="modern-clock-card text-center">
                <div class="d-flex justify-content-center align-items-baseline gap-1">
                    <span id="liveClockTime" class="clock-time">12:00:00</span>
                    <span id="liveClockPeriod" class="clock-period">AM</span>
                </div>
                <div id="liveClockDate" class="clock-date">Wednesday, September 9, 2026</div>
            </div>
        </div>
    </div>

    <!-- MAIN DASHBOARD MIDDLE -->
    <div class="row g-2 top-dashboard-row my-1">
        
        <!-- LEFT: Waiting Queue Sidebar -->
        <div class="col-12 col-lg-3 h-100">
            <div class="waiting-card p-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Waiting Queue</h5>
                    <span class="badge badge-pill-custom rounded-pill px-2 py-1">7 Waiting</span>
                </div>

                <div class="waiting-list-scroll pe-1">
                    <div class="waiting-item d-flex justify-content-between align-items-center">
                        <span class="queue-num ps-1">AC-015</span>
                        <span class="office-name pe-1">Accounting Office</span>
                    </div>
                    <div class="waiting-item d-flex justify-content-between align-items-center">
                        <span class="queue-num ps-1">AD-008</span>
                        <span class="office-name pe-1">Admissions Office</span>
                    </div>
                    <div class="waiting-item d-flex justify-content-between align-items-center">
                        <span class="queue-num ps-1">G-011</span>
                        <span class="office-name pe-1">Guidance Office</span>
                    </div>
                    <div class="waiting-item d-flex justify-content-between align-items-center">
                        <span class="queue-num ps-1">L-012</span>
                        <span class="office-name pe-1">Library</span>
                    </div>
                    <div class="waiting-item d-flex justify-content-between align-items-center">
                        <span class="queue-num ps-1">C-006</span>
                        <span class="office-name pe-1">School Clinic</span>
                    </div>
                    <div class="waiting-item d-flex justify-content-between align-items-center">
                        <span class="queue-num ps-1">CA-018</span>
                        <span class="office-name pe-1">Cashier</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Hero Screen -->
        <div class="col-12 col-lg-9 h-100">
            <div class="now-calling-card text-center d-flex flex-column justify-content-center align-items-center">
                <div class="now-calling-title text-uppercase mb-1">NOW CALLING</div>
                <div class="now-calling-number my-1">R-024</div>
                <div class="now-calling-subtext text-uppercase mt-1">CURRENTLY BEING CALLED AT</div>
                <div class="now-calling-office mb-1">Registrar Office</div>
                <small class="text-white-50" style="font-size: 0.7rem;">Please proceed to the indicated office.</small>
            </div>
        </div>

    </div>

    <!-- BOTTOM SECTION: Office Status Matrix -->
    <div class="status-matrix-card">
        <div class="text-center mb-1">
            <h5 class="fw-bold mb-0">Current Office Queue Status</h5>
            <small class="text-white-50" style="font-size: 0.65rem;">25 offices currently serving</small>
        </div>

        <div class="row g-1">
            <div class="col-6 col-md-4 col-lg-2-4">
                <div class="status-box active-calling text-center">
                    <div class="dept-title text-truncate">Registrar Office</div>
                    <div class="ticket-no">R-024</div>
                    <div class="status-badge now-calling">● NOW CALLING</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2-4">
                <div class="status-box text-center">
                    <div class="dept-title text-truncate">Accounting Office</div>
                    <div class="ticket-no">AC-015</div>
                    <div class="status-badge serving">● SERVING</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2-4">
                <div class="status-box text-center">
                    <div class="dept-title text-truncate">Admissions Office</div>
                    <div class="ticket-no">AD-008</div>
                    <div class="status-badge serving">● SERVING</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2-4">
                <div class="status-box text-center">
                    <div class="dept-title text-truncate">Guidance Office</div>
                    <div class="ticket-no">G-011</div>
                    <div class="status-badge serving">● SERVING</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2-4">
                <div class="status-box text-center">
                    <div class="dept-title text-truncate">Library</div>
                    <div class="ticket-no">L-012</div>
                    <div class="status-badge serving">● SERVING</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2-4">
                <div class="status-box text-center">
                    <div class="dept-title text-truncate">School Clinic</div>
                    <div class="ticket-no">C-006</div>
                    <div class="status-badge serving">● SERVING</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2-4">
                <div class="status-box text-center">
                    <div class="dept-title text-truncate">Cashier</div>
                    <div class="ticket-no">CA-018</div>
                    <div class="status-badge serving">● SERVING</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2-4">
                <div class="status-box text-center">
                    <div class="dept-title text-truncate">Human Resources</div>
                    <div class="ticket-no">HR-004</div>
                    <div class="status-badge serving">● SERVING</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2-4">
                <div class="status-box text-center">
                    <div class="dept-title text-truncate">Student Affairs</div>
                    <div class="ticket-no">SA-009</div>
                    <div class="status-badge serving">● SERVING</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2-4">
                <div class="status-box text-center">
                    <div class="dept-title text-truncate">Scholarship Office</div>
                    <div class="ticket-no">S-005</div>
                    <div class="status-badge serving">● SERVING</div>
                </div>
            </div>
        </div>

        <div class="text-center mt-1">
            <small class="text-white-50" style="font-size: 0.6rem;">Showing Offices 1–10 of 25</small>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="text-center">
        <small class="text-white-50">
            Copyright &copy; <?= date('Y'); ?> City College of Calamba. All Rights Reserved.
        </small>
    </footer>
</div>

<script>
function updateClock() {
    const now = new Date();
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const period = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12;
    hours = hours ? hours : 12;
    const formattedHours = String(hours).padStart(2, '0');

    document.getElementById('liveClockTime').textContent = `${formattedHours}:${minutes}:${seconds}`;
    document.getElementById('liveClockPeriod').textContent = period;

    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('liveClockDate').textContent = now.toLocaleDateString('en-US', options);
}

setInterval(updateClock, 1000);
updateClock();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
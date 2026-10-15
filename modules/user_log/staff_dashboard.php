<?php
// user_log/staff_dashboard.php

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

// User Metadata Setup
$fullName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Juan Dela Cruz';
$userInitials = 'ST';
if (!empty($fullName)) {
    $nameParts = explode(' ', trim($fullName));
    $firstInitial = $nameParts[0][0] ?? '';
    $lastInitial = isset($nameParts[1]) ? $nameParts[count($nameParts) - 1][0] : '';
    $userInitials = strtoupper($firstInitial . $lastInitial);
}

// Include Global Header or local fallback
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
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl); ?>assets/css/bootstrap-icons.css">
</head>
<body style="background-color: #f4f6f9;">
<?php } ?>

<style>
    /* Default / Mobile Viewport: Natural page height with standard vertical scroll */
    html, body { 
        min-height: 100vh; 
        margin: 0; 
        padding: 0; 
        overflow-x: hidden; 
        overflow-y: auto; 
    }

    .hover-white:hover { color: #fff !important; }

    /* Custom Canvas & Cards */
    .bg-dashboard-canvas { background-color: #f0f4f9; min-height: 100vh; }
    .card-custom { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04); }
    
    /* Info-Tile with Added Subtle Box Shadow */
    .info-tile { 
        background-color: #f8fafc; 
        border-radius: 10px; 
        padding: 12px 16px; 
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); 
        border: 1px solid #e2e8f0; 
    }
    
    /* Compact Action Buttons with Descriptions */
    .btn-action-call { background-color: #0284c7; color: white; border: none; font-weight: 600; padding: 10px 8px; border-radius: 12px; }
    .btn-action-call:hover { background-color: #0369a1; color: white; }

    .btn-action-complete { background-color: #10b981; color: white; border: none; font-weight: 600; padding: 10px 8px; border-radius: 12px; }
    .btn-action-complete:hover { background-color: #059669; color: white; }

    .btn-action-recall { background-color: #f59e0b; color: white; border: none; font-weight: 600; padding: 12px; border-radius: 12px; }
    .btn-action-recall:hover { background-color: #d97706; color: white; }

    .btn-action-transfer { background-color: #8b5cf6; color: white; border: none; font-weight: 600; padding: 10px 8px; border-radius: 12px; }
    .btn-action-transfer:hover { background-color: #7c3aed; color: white; }

    .btn-desc {
        font-size: 0.65rem;
        opacity: 0.88;
        font-weight: 400;
        margin-top: 1px;
        line-height: 1;
    }

    /* Counter Status Pill */
    .status-pill { 
        background-color: rgba(25, 135, 84, 0.1); 
        color: #198754; 
        border-radius: 50rem; 
        font-weight: 700; 
        font-size: 0.75rem; 
        padding: 0.25rem 1rem; 
        border: 1px solid rgba(25, 135, 84, 0.25); 
        line-height: 1.5;
    }
    .status-pill:hover { background-color: rgba(25, 135, 84, 0.2); }

    /* Desktop Viewports (>= 992px): Lock layout strictly to 100vh without page scrollbars */
    @media (min-width: 992px) {
        html, body { 
            height: 100vh !important; 
            overflow: hidden !important; 
        }
        .bg-dashboard-canvas { height: 100vh !important; }
        .app-layout { flex-direction: row !important; height: 100vh !important; overflow: hidden !important; }
        .app-sidebar { width: 250px !important; min-width: 250px !important; height: 100vh !important; }
        .app-sidebar-collapse { display: block !important; }
        .app-main-content { height: 100vh !important; overflow: hidden !important; }
    }

    /* Mobile / Small Screens (< 992px): Allow sidebars & main content to expand naturally */
    @media (max-width: 991.98px) {
        .app-layout { flex-direction: column !important; height: auto !important; overflow: visible !important; }
        .app-sidebar { width: 100% !important; min-width: 100% !important; height: auto !important; }
        .app-main-content { height: auto !important; overflow: visible !important; }
    }
</style>

<div class="d-flex app-layout bg-dashboard-canvas">

    <!-- SIDEBAR NAVIGATION -->
    <aside class="text-white d-flex flex-column flex-shrink-0 p-0 app-sidebar" style="background-color: #002366;">

        <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <img src="<?= htmlspecialchars($baseUrl); ?>/../../assets/img/ccc_banner.webp" alt="City College of Calamba Banner" class="img-fluid" style="max-height: 40px; width: auto; object-fit: contain;" onerror="this.onerror=null; this.src='<?= htmlspecialchars($baseUrl); ?>/../../assets/img/ccc-logo.png';">
            </div>
            <button class="btn btn-outline-light d-lg-none py-1 px-2 border-opacity-50" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapseNav">
                <i class="bi bi-list fs-4"></i>
            </button>
        </div>

        <div class="collapse app-sidebar-collapse flex-grow-1" id="sidebarCollapseNav">
            <div class="px-3 pt-3 pb-1">
                <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">STAFF PORTAL</small>
            </div>

            <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
            <nav class="nav nav-pills flex-column px-2 gap-1 mt-1 pb-3">
                <a href="staff_dashboard.php" class="nav-link <?= ($currentPage === 'staff_dashboard.php') ? 'active bg-white bg-opacity-10 text-white' : 'text-white-50 hover-white'; ?> d-flex align-items-center gap-3 py-2 px-3 rounded">
                    <i class="bi bi-house-door fs-5"></i>
                    <span class="fw-medium">Dashboard</span>
                </a>
                <a href="staff_service.php" class="nav-link <?= ($currentPage === 'staff_service.php') ? 'active bg-white bg-opacity-10 text-white' : 'text-white-50 hover-white'; ?> d-flex align-items-center gap-3 py-2 px-3 rounded">
                    <i class="bi bi-clock-history fs-5"></i>
                    <span class="fw-medium">Service History</span>
                </a>
                <a href="staff_report.php" class="nav-link <?= ($currentPage === 'staff_report.php') ? 'active bg-white bg-opacity-10 text-white' : 'text-white-50 hover-white'; ?> d-flex align-items-center gap-3 py-2 px-3 rounded">
                    <i class="bi bi-bar-chart-line fs-5"></i>
                    <span class="fw-medium">Reports</span>
                </a>
            </nav>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-grow-1 d-flex flex-column app-main-content h-100 overflow-auto">

        <!-- TOP BAR HEADER -->
        <header class="bg-white px-4 py-2 d-flex justify-content-between align-items-center shadow-sm flex-shrink-0" style="min-height: 52px;">
            <div class="d-flex align-items-center gap-2 text-dark fw-bold small" id="liveHeaderClock">
                <i class="bi bi-calendar3 text-primary me-1"></i> <?= date('D | F j, Y'); ?>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none text-dark gap-2 dropdown-toggle" id="staffUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width: 34px; height: 34px; background-color: #002B66; font-size: 0.85rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="fw-bold small text-dark d-none d-sm-inline"><?= htmlspecialchars($fullName); ?> (SU)</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="staffUserDropdown">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>My Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="staff_login.php"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- MAIN CONTAINER -->
        <main class="px-3 py-2 px-md-4 flex-grow-1 d-flex flex-column justify-content-between overflow-auto">

            <!-- HERO CARD: NOW SERVING COUNTER -->
            <div class="card card-custom bg-white p-3 p-md-4 mb-2 flex-shrink-0">
                
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-people-fill text-primary fs-4"></i>
                    <h5 class="fw-extrabold text-dark mb-0 me-2" style="letter-spacing: 0.5px;">NOW SERVING COUNTER</h5>
                    
                    <!-- Counter Status Toggle Pill -->
                    <div class="dropdown">
                        <button class="btn status-pill dropdown-toggle d-flex align-items-center gap-2 shadow-sm" type="button" id="counterStatusBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="rounded-circle bg-success" style="width: 7px; height: 7px;"></span>
                            <span id="currentStatusText">ONLINE</span>
                        </button>
                        <ul class="dropdown-menu shadow border-0 mt-2" aria-labelledby="counterStatusBtn">
                            <li><a class="dropdown-item fw-semibold text-success d-flex align-items-center gap-2" href="javascript:void(0)" onclick="setStatus('ONLINE', 'bg-success')"><span class="rounded-circle bg-success" style="width: 8px; height: 8px;"></span> Online</a></li>
                            <li><a class="dropdown-item fw-semibold text-warning d-flex align-items-center gap-2" href="javascript:void(0)" onclick="setStatus('ON BREAK', 'bg-warning')"><span class="rounded-circle bg-warning" style="width: 8px; height: 8px;"></span> On Break</a></li>
                            <li><a class="dropdown-item fw-semibold text-secondary d-flex align-items-center gap-2" href="javascript:void(0)" onclick="setStatus('OFFLINE', 'bg-secondary')"><span class="rounded-circle bg-secondary" style="width: 8px; height: 8px;"></span> Offline</a></li>
                        </ul>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- LEFT SIDE: TICKET CARD WITH ENLARGED TICKET TEXT & LABEL -->
                    <div class="col-12 col-lg-5 col-xl-4">
                        <div class="h-100 rounded-4 p-3 text-center d-flex flex-column justify-content-center align-items-center shadow-sm" style="background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%); border: 1px solid #bae6fd;">
                            
                            <!-- Box Container with Enlarged Header and Ticket Code -->
                            <div class="bg-white rounded-4 shadow-sm border border-primary border-opacity-25 px-4 py-3 d-flex flex-column align-items-center justify-content-center" style="width: 95%; height: 195px;">
                                <div class="d-flex align-items-center gap-2 text-primary fw-bold mb-1" style="font-size: 0.92rem; letter-spacing: 1.2px;">
                                    <i class="bi bi-ticket-perforated-fill fs-4"></i> TICKET NUMBER
                                </div>
                                <h1 class="fw-extrabold text-primary mb-0" style="font-size: 4.4rem; letter-spacing: -2px; color: #0038a8 !important; line-height: 1;">CA-018</h1>
                            </div>

                        </div>
                    </div>

                    <!-- RIGHT SIDE: ORIGINAL CLIENT DATA GRID -->
                    <div class="col-12 col-lg-7 col-xl-8">
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <div class="info-tile d-flex align-items-center gap-3">
                                    <i class="bi bi-person-fill text-primary fs-4"></i>
                                    <div>
                                        <div class="text-muted small fw-medium" style="font-size: 0.72rem;">Full Name:</div>
                                        <strong class="text-dark d-block" style="font-size: 0.95rem;">Maria Clara Santos</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="info-tile d-flex align-items-center gap-3">
                                    <i class="bi bi-people-fill text-primary fs-4"></i>
                                    <div>
                                        <div class="text-muted small fw-medium" style="font-size: 0.72rem;">Visitor Type:</div>
                                        <strong class="text-dark d-block" style="font-size: 0.95rem;">GUEST / PUBLIC</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="info-tile d-flex align-items-center gap-3">
                                    <i class="bi bi-bullseye text-primary fs-4"></i>
                                    <div>
                                        <div class="text-muted small fw-medium" style="font-size: 0.72rem;">Primary Purpose:</div>
                                        <strong class="text-dark d-block" style="font-size: 0.95rem;">Document Claiming & Inquiries</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="info-tile d-flex align-items-center gap-3">
                                    <i class="bi bi-star-fill text-primary fs-4"></i>
                                    <div>
                                        <div class="text-muted small fw-medium" style="font-size: 0.72rem;">Priority Status:</div>
                                        <strong class="text-dark d-block" style="font-size: 0.95rem;">REGULAR</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="info-tile d-flex align-items-center gap-3">
                                    <i class="bi bi-telephone-fill text-primary fs-4"></i>
                                    <div>
                                        <div class="text-muted small fw-medium" style="font-size: 0.72rem;">Contact No.:</div>
                                        <strong class="text-dark d-block" style="font-size: 0.95rem;">0917-555-0192</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="info-tile d-flex align-items-center gap-3">
                                    <i class="bi bi-clock-fill text-primary fs-4"></i>
                                    <div>
                                        <div class="text-muted small fw-medium" style="font-size: 0.72rem;">Queue Entry Time:</div>
                                        <strong class="text-dark d-block" style="font-size: 0.95rem;">08:12 AM <span class="text-muted fw-normal small">(7m waiting)</span></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 ACTION CONTROL BUTTONS -->
                <div class="row g-2 mt-2 pt-1">
                    <div class="col-6 col-md-3">
                        <button class="btn btn-action-call w-100 d-flex flex-column align-items-center justify-content-center shadow-sm">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-megaphone-fill fs-6"></i>
                                <span>Call Next</span>
                            </div>
                            <span class="btn-desc">Serve next ticket</span>
                        </button>
                    </div>
                    <div class="col-6 col-md-3">
                        <button class="btn btn-action-complete w-100 d-flex flex-column align-items-center justify-content-center shadow-sm">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill fs-6"></i>
                                <span>Complete</span>
                            </div>
                            <span class="btn-desc">Finish transaction</span>
                        </button>
                    </div>
                    <div class="col-6 col-md-3">
                        <button class="btn btn-action-recall w-100 d-flex flex-column align-items-center justify-content-center shadow-sm">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-bell-fill fs-6"></i>
                                <span>Recall</span>
                            </div>
                            <span class="btn-desc">Announce again</span>
                        </button>
                    </div>
                    <div class="col-6 col-md-3">
                        <button class="btn btn-action-transfer w-100 d-flex flex-column align-items-center justify-content-center shadow-sm">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-arrow-left-right fs-6"></i>
                                <span>Transfer</span>
                            </div>
                            <span class="btn-desc">Reassign counter</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- LOWER SECTION: DYNAMICALLY STRETCHES TO FILL REMAINING SPACE -->
            <div class="row g-2 flex-grow-1 overflow-hidden" style="min-height: 0;">

                <!-- LEFT: HORIZONTAL WAITING QUEUE CARD (CENTERED TICKET COUNT) -->
                <div class="col-12 col-lg-5 d-flex flex-column h-100">
                    <div class="card card-custom bg-white p-3 h-100 d-flex flex-column justify-content-between">
                        
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-people-fill text-primary fs-5"></i>
                                <h6 class="fw-bold text-dark mb-0">Waiting Queue</h6>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="showDetailedListToggle">
                                <label class="form-check-label text-muted small" for="showDetailedListToggle">Show Detailed List</label>
                            </div>
                        </div>

                        <!-- Centered Layout for Tickets Remaining -->
                        <div class="d-flex align-items-center justify-content-center my-auto w-100 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="background-color: #e0f2fe; width: 54px; height: 54px;">
                                    <i class="bi bi-people-fill text-primary fs-3"></i>
                                </div>
                                <div class="lh-1">
                                    <div class="fw-extrabold text-primary" style="font-size: 2.5rem;">7</div>
                                    <span class="text-muted fw-semibold" style="font-size: 0.85rem;">Tickets Remaining</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- RIGHT: SHIFT SUMMARY CARD (VERTICAL BOXES FILLING ALL EMPTY SPACE) -->
                <div class="col-12 col-lg-7 d-flex flex-column h-100">
                    <div class="card card-custom bg-white p-3 h-100 d-flex flex-column justify-content-between">
                        
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-clock-history text-primary fs-5"></i>
                                <h6 class="fw-bold text-dark mb-0">Shift Summary</h6>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="showDetailsToggle">
                                <label class="form-check-label text-muted small" for="showDetailsToggle">Show Details</label>
                            </div>
                        </div>

                        <!-- 3 Vertical Boxes below the title filling full container height -->
                        <div class="row g-2 flex-grow-1 my-auto align-items-stretch">
                            <!-- Box 1: Completed -->
                            <div class="col-4 d-flex">
                                <div class="rounded-3 p-2 border bg-light w-100 d-flex flex-column align-items-center justify-content-center text-center shadow-sm">
                                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-1 flex-shrink-0" style="width: 36px; height: 36px;">
                                        <i class="bi bi-check-lg fs-5"></i>
                                    </div>
                                    <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Completed</div>
                                    <h2 class="fw-extrabold text-dark mb-0 lh-1" style="font-size: 1.8rem;">42</h2>
                                    <div class="mt-2 rounded-pill bg-success" style="height: 3px; width: 60%;"></div>
                                </div>
                            </div>

                            <!-- Box 2: Skipped -->
                            <div class="col-4 d-flex">
                                <div class="rounded-3 p-2 border bg-light w-100 d-flex flex-column align-items-center justify-content-center text-center shadow-sm">
                                    <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-1 flex-shrink-0" style="width: 36px; height: 36px;">
                                        <i class="bi bi-dash-lg fs-5"></i>
                                    </div>
                                    <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Skipped</div>
                                    <h2 class="fw-extrabold text-dark mb-0 lh-1" style="font-size: 1.8rem;">3</h2>
                                    <div class="mt-2 rounded-pill bg-warning" style="height: 3px; width: 60%;"></div>
                                </div>
                            </div>

                            <!-- Box 3: Avg Time -->
                            <div class="col-4 d-flex">
                                <div class="rounded-3 p-2 border bg-light w-100 d-flex flex-column align-items-center justify-content-center text-center shadow-sm">
                                    <div class="rounded-circle text-white d-inline-flex align-items-center justify-content-center mb-1 flex-shrink-0" style="width: 36px; height: 36px; background-color: #8b5cf6;">
                                        <i class="bi bi-clock fs-5"></i>
                                    </div>
                                    <div class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Avg. Time</div>
                                    <h2 class="fw-extrabold text-dark mb-0 lh-1" style="font-size: 1.8rem;">4m</h2>
                                    <div class="mt-2 rounded-pill" style="height: 3px; width: 60%; background-color: #8b5cf6;"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<!-- DYNAMIC SCRIPT FOR STATUS TOGGLE WITH AUTO-CLOSE DROPDOWN FIX -->
<script>
function setStatus(statusText, badgeClass) {
    const statusTextEl = document.getElementById('currentStatusText');
    const statusBtn = document.getElementById('counterStatusBtn');
    
    if (statusTextEl && statusBtn) {
        statusTextEl.textContent = statusText;
        
        if (statusText === 'ONLINE') {
            statusBtn.className = 'btn status-pill dropdown-toggle d-flex align-items-center gap-2 shadow-sm';
            statusBtn.style = '';
        } else if (statusText === 'ON BREAK') {
            statusBtn.className = 'btn dropdown-toggle d-flex align-items-center gap-2 shadow-sm';
            statusBtn.style.backgroundColor = '#fef3c7';
            statusBtn.style.color = '#d97706';
            statusBtn.style.border = '1px solid #fde68a';
            statusBtn.style.borderRadius = '50px';
            statusBtn.style.fontWeight = '700';
            statusBtn.style.fontSize = '0.75rem';
            statusBtn.style.padding = '0.25rem 1rem';
        } else {
            statusBtn.className = 'btn dropdown-toggle d-flex align-items-center gap-2 shadow-sm';
            statusBtn.style.backgroundColor = '#f1f5f9';
            statusBtn.style.color = '#475569';
            statusBtn.style.border = '1px solid #cbd5e1';
            statusBtn.style.borderRadius = '50px';
            statusBtn.style.fontWeight = '700';
            statusBtn.style.fontSize = '0.75rem';
            statusBtn.style.padding = '0.25rem 1rem';
        }

        // Auto-close the Bootstrap dropdown list after selection
        if (window.bootstrap && bootstrap.Dropdown) {
            const dropdownInstance = bootstrap.Dropdown.getInstance(statusBtn) || new bootstrap.Dropdown(statusBtn);
            if (dropdownInstance) {
                dropdownInstance.hide();
            }
        } else {
            statusBtn.click();
        }
    }
}
</script>

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
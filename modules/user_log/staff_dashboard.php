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
    <div class="flex-grow-1 d-flex flex-column app-main-content overflow-hidden">

        <!-- TOP BAR HEADER WITH DYNAMIC CLOCK -->
        <header class="bg-white border-bottom px-3 px-md-4 py-2 d-flex justify-content-between align-items-center shadow-sm flex-shrink-0" style="min-height: 56px;">
            <div class="text-muted small fw-medium text-truncate me-2" id="liveHeaderClock">
                <?= date('D | F j, Y g:i:s A'); ?>
            </div>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-dark gap-2 dropdown-toggle" id="staffUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width: 32px; height: 32px; background-color: #002B66; font-size: 0.8rem;">
                        <?= htmlspecialchars($userInitials); ?>
                    </div>
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

        <!-- MAIN CONTAINER -->
        <main class="p-3 p-md-4 flex-grow-1 d-flex flex-column overflow-auto">

            <!-- Clean Top Text & Header Controls -->
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-3 flex-shrink-0 gap-2">
                <div>
                    <h4 class="fw-bold text-dark mb-0">Staff Queue Dashboard</h4>
                    <p class="text-muted small mb-0">Manage active queue counters and monitor live queue status.</p>
                </div>
            </div>

            <!-- HERO CARD (CURRENTLY SERVING + ACTION BUTTONS) -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-3 flex-shrink-0 bg-white">
                <div class="card-body p-3">
                    <div class="row align-items-center g-3">

                        <!-- 1. Ticket Highlight Box -->
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="rounded-3 p-3 text-center border" style="background-color: #f8faff; border-color: #d0e1fd !important;">
                                <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    NOW CALLING
                                </div>
                                <h1 class="fw-extrabold text-primary mb-1" style="font-size: 2.2rem; letter-spacing: -1px;">CA-018</h1>
                                <span class="badge bg-success text-white rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">
                                    <i class="bi bi-record-fill me-1 text-warning"></i> IN SERVICE
                                </span>
                            </div>
                        </div>

                        <!-- 2. Student Details with Timers Below -->
                        <div class="col-12 col-md-8 col-lg-5 border-end-md pe-md-3">
                            <div class="d-flex align-items-center gap-3 mb-2.5">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold fs-4 flex-shrink-0" style="width: 44px; height: 44px; background-color: #e7f0ff;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h5 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 1.1rem;">Juan Dela Cruz</h5>
                                    <p class="text-muted small mb-0" style="font-size: 0.8rem;"><i class="bi bi-mortarboard me-1"></i>BSIT - 3A</p>
                                    <span class="badge bg-primary bg-opacity-10 mb-2 text-primary fw-semibold px-2 py-0.5 rounded-2" style="font-size: 0.72rem;">
                                        <i class="bi bi-credit-card me-1"></i> Payment Transaction
                                    </span>
                                </div>
                            </div>

                            <div class="pt-2 border-top">
                                <div class="row g-2 text-center">
                                    <div class="col-4">
                                        <div class="p-1">
                                            <div class="text-muted text-nowrap" style="font-size: 0.65rem;"><i class="bi bi-clock me-1 text-primary"></i>Called at</div>
                                            <strong class="text-dark d-block text-nowrap" style="font-size: 0.8rem;">10:20 AM</strong>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-1">
                                            <div class="text-muted text-nowrap" style="font-size: 0.65rem;"><i class="bi bi-hourglass-split me-1 text-warning"></i>Waiting</div>
                                            <strong class="text-dark d-block text-nowrap" style="font-size: 0.8rem;">3m</strong>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-1">
                                            <div class="text-muted text-nowrap" style="font-size: 0.65rem;"><i class="bi bi-stopwatch me-1 text-success"></i>Service</div>
                                            <strong class="text-dark d-block text-nowrap" style="font-size: 0.8rem;">12m</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Action Buttons -->
                        <div class="col-12 col-lg-4 ps-lg-3">
                            <div class="row g-2">
                                <div class="col-6 col-sm-3 col-lg-6">
                                    <button class="btn btn-primary w-100 py-2 rounded-3 fw-bold shadow-sm text-nowrap" style="font-size: 0.82rem;">
                                        <i class="bi bi-megaphone me-1"></i> Call Next
                                    </button>
                                </div>
                                <div class="col-6 col-sm-3 col-lg-6">
                                    <button class="btn btn-success w-100 py-2 rounded-3 fw-bold shadow-sm text-nowrap" style="font-size: 0.82rem;">
                                        <i class="bi bi-check-circle me-1"></i> Complete
                                    </button>
                                </div>
                                <div class="col-6 col-sm-3 col-lg-6">
                                    <button class="btn btn-warning text-dark w-100 py-2 rounded-3 fw-bold shadow-sm text-nowrap" style="font-size: 0.82rem;">
                                        <i class="bi bi-bell me-1"></i> Recall
                                    </button>
                                </div>
                                <div class="col-6 col-sm-3 col-lg-6">
                                    <button class="btn text-white w-100 py-2 rounded-3 fw-bold shadow-sm text-nowrap" style="background-color: #6f42c1; font-size: 0.82rem;">
                                        <i class="bi bi-arrow-return-right me-1"></i> Transfer
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- LOWER SECTION (WAITING QUEUE & OVERVIEW) -->
            <div class="row g-3 flex-grow-1">

                <!-- WAITING QUEUE TABLE -->
                <div class="col-12 col-lg-7 col-xl-8 d-flex flex-column">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100 d-flex flex-column">

                        <div class="d-flex align-items-center justify-content-between mb-3 flex-shrink-0">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-people-fill text-primary fs-5"></i>
                                <h6 class="fw-bold text-dark mb-0">Waiting Queue</h6>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">7 in queue</span>
                            </div>
                        </div>

                        <div class="row g-2 mb-3 flex-shrink-0">
                            <div class="col-12 col-sm-7">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control bg-light border-start-0" placeholder="Search ticket number or student name...">
                                </div>
                            </div>
                            <div class="col-12 col-sm-5">
                                <select class="form-select form-select-sm bg-light">
                                    <option selected>All Services</option>
                                    <option value="payment">Payment</option>
                                    <option value="enrollment">Enrollment</option>
                                    <option value="certification">Certification</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive flex-grow-1">
                            <table class="table table-hover align-middle mb-0 small text-nowrap">
                                <thead class="table-light text-muted sticky-top" style="font-size: 0.75rem;">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Ticket Number</th>
                                        <th scope="col">Student Name</th>
                                        <th scope="col">Service Type</th>
                                        <th scope="col">Waiting Time</th>
                                        <th scope="col" class="text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-muted">1</td>
                                        <td class="fw-bold text-primary">CA-019</td>
                                        <td>Maria Santos</td>
                                        <td>Payment</td>
                                        <td>2m</td>
                                        <td class="text-end"><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1">Waiting</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">2</td>
                                        <td class="fw-bold text-primary">CA-020</td>
                                        <td>Jose Reyes</td>
                                        <td>Enrollment</td>
                                        <td>4m</td>
                                        <td class="text-end"><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1">Waiting</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">3</td>
                                        <td class="fw-bold text-primary">CA-021</td>
                                        <td>Ana Garcia</td>
                                        <td>Certification</td>
                                        <td>6m</td>
                                        <td class="text-end"><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1">Waiting</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">4</td>
                                        <td class="fw-bold text-primary">CA-022</td>
                                        <td>Mark Villanueva</td>
                                        <td>Payment</td>
                                        <td>9m</td>
                                        <td class="text-end"><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1">Waiting</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">5</td>
                                        <td class="fw-bold text-primary">CA-023</td>
                                        <td>Sophia Lim</td>
                                        <td>Enrollment</td>
                                        <td>11m</td>
                                        <td class="text-end"><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1">Waiting</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <!-- QUEUE OVERVIEW -->
                <div class="col-12 col-lg-5 col-xl-4 d-flex flex-column">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100 d-flex flex-column">

                        <div class="d-flex align-items-center justify-content-between mb-3 flex-shrink-0">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-bar-chart-fill text-primary fs-5"></i>
                                <h6 class="fw-bold text-dark mb-0">Queue Overview</h6>
                            </div>
                            <a href="staff_service.php" class="text-decoration-none small text-primary fw-semibold d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                View All <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <div class="row g-2 mb-3 flex-shrink-0">
                            <div class="col-6">
                                <div class="p-3 rounded-3 border bg-light d-flex align-items-center gap-2">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center text-primary flex-shrink-0" style="width: 32px; height: 32px; background-color: #e7f0ff;">
                                        <i class="bi bi-people-fill" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 text-center overflow-hidden">
                                        <h5 class="fw-bold text-dark mb-0 lh-1">7</h5>
                                        <small class="text-muted fw-medium d-block text-truncate mt-1" style="font-size: 0.65rem;">Waiting</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 border bg-light d-flex align-items-center gap-2">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center text-success flex-shrink-0" style="width: 32px; height: 32px; background-color: #e6f7ed;">
                                        <i class="bi bi-check-circle-fill" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 text-center overflow-hidden">
                                        <h5 class="fw-bold text-dark mb-0 lh-1">42</h5>
                                        <small class="text-muted fw-medium d-block text-truncate mt-1" style="font-size: 0.65rem;">Completed</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 border bg-light d-flex align-items-center gap-2">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center text-warning flex-shrink-0" style="width: 32px; height: 32px; background-color: #fff8e6;">
                                        <i class="bi bi-arrow-clockwise" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 text-center overflow-hidden">
                                        <h5 class="fw-bold text-dark mb-0 lh-1">3</h5>
                                        <small class="text-muted fw-medium d-block text-truncate mt-1" style="font-size: 0.65rem;">Skipped</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 border bg-light d-flex align-items-center gap-2">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; background-color: #f2e9fc; color: #6f42c1;">
                                        <i class="bi bi-clock-history" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 text-center overflow-hidden">
                                        <h5 class="fw-bold text-dark mb-0 lh-1">4m</h5>
                                        <small class="text-muted fw-medium d-block text-truncate mt-1" style="font-size: 0.65rem;">Avg Time</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive flex-grow-1">
                            <table class="table table-borderless table-hover align-middle mb-0 text-nowrap" style="font-size: 0.73rem;">
                                <thead class="text-muted border-bottom" style="font-size: 0.68rem;">
                                    <tr>
                                        <th class="ps-0 py-1 fw-semibold">Time</th>
                                        <th class="py-1 fw-semibold">Ticket No.</th>
                                        <th class="py-1 fw-semibold">Action</th>
                                        <th class="pe-0 py-1 fw-semibold text-end">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-0 py-2 text-muted">10:20 AM</td>
                                        <td class="py-2 fw-bold text-dark">CA-018</td>
                                        <td class="py-2 text-muted">Called</td>
                                        <td class="pe-0 py-2 text-end"><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-0.5">In Service</span></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-0 py-2 text-muted">10:16 AM</td>
                                        <td class="py-2 fw-bold text-dark">CA-017</td>
                                        <td class="py-2 text-muted">Completed</td>
                                        <td class="pe-0 py-2 text-end"><span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0.5">Served</span></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-0 py-2 text-muted">10:12 AM</td>
                                        <td class="py-2 fw-bold text-dark">CA-016</td>
                                        <td class="py-2 text-muted">Skipped</td>
                                        <td class="pe-0 py-2 text-end"><span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-0.5">Skipped</span></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-0 py-2 text-muted">10:08 AM</td>
                                        <td class="py-2 fw-bold text-dark">CA-015</td>
                                        <td class="py-2 text-muted">Completed</td>
                                        <td class="pe-0 py-2 text-end"><span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0.5">Served</span></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-0 py-2 text-muted">10:03 AM</td>
                                        <td class="py-2 fw-bold text-dark">CA-014</td>
                                        <td class="py-2 text-muted">Called</td>
                                        <td class="pe-0 py-2 text-end"><span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0.5">Served</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<!-- DYNAMIC LIVE HEADER CLOCK SCRIPT -->
<script>
function updateHeaderClock() {
    const now = new Date();
    
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                    'July', 'August', 'September', 'October', 'November', 'December'];
    
    const dayName = days[now.getDay()];
    const monthName = months[now.getMonth()];
    const dayNum = now.getDate();
    const year = now.getFullYear();
    
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    
    hours = hours % 12;
    hours = hours ? hours : 12;

    const formattedDate = `${dayName} | ${monthName} ${dayNum}, ${year} ${hours}:${minutes}:${seconds} ${ampm}`;
    
    const clockEl = document.getElementById('liveHeaderClock');
    if (clockEl) {
        clockEl.textContent = formattedDate;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    updateHeaderClock();
    setInterval(updateHeaderClock, 1000);
});
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
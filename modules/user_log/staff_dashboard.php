<?php
// user_log/staff_dashboard.php

// 1. Session and path setup
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = defined('BASE_URL') ? BASE_URL : '../';

// 2. Security & Role Checks
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

// 3. Include Global Header (if available) or render local asset head
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
    <!-- Local Bootstrap & Icons from Assets -->
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl); ?>assets/css/bootstrap-icons.css">
</head>
<body style="background-color: #f4f6f9;">
<?php } ?>

<div class="d-flex vh-100 overflow-hidden" style="background-color: #f4f6f9;">

    <!-- SIDEBAR NAVIGATION -->
    <aside class="text-white d-flex flex-column flex-shrink-0 p-0" style="width: 250px; background-color: #002366; height: 100vh;">
        
        <!-- Header Branding -->
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <img 
                    src="<?= htmlspecialchars($baseUrl); ?>/../../assets/img/ccc_banner.webp" 
                    alt="City College of Calamba Banner" 
                    class="img-fluid"
                    style="max-height: 40px; width: auto; object-fit: contain;"
                    onerror="this.onerror=null; this.src='<?= htmlspecialchars($baseUrl); ?>/../../assets/img/ccc-logo.png';"
                >
            </div>
            <button class="btn btn-link text-white p-0 fs-5 border-0"><i class="bi bi-list"></i></button>
        </div>

        <div class="px-3 pt-2 pb-1">
            <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">STAFF PORTAL</small>
        </div>

        <!-- Navigation Links -->
        <nav class="nav nav-pills flex-column px-2 gap-1 mt-1">
            <a href="staff_dashboard.php" class="nav-link active bg-white bg-opacity-10 text-white d-flex align-items-center gap-3 py-2 px-3 rounded">
                <i class="bi bi-house-door fs-5"></i>
                <span class="fw-medium">Dashboard</span>
            </a>
            <a href="<?= htmlspecialchars($baseUrl); ?>modules/logbook/" class="nav-link text-white-50 hover-white d-flex align-items-center gap-3 py-2 px-3 rounded">
                <i class="bi bi-people fs-5"></i>
                <span class="fw-medium">User Information</span>
            </a>
            <a href="#" class="nav-link text-white-50 hover-white d-flex align-items-center gap-3 py-2 px-3 rounded">
                <i class="bi bi-list-task fs-5"></i>
                <span class="fw-medium">Activity Log</span>
            </a>
            <a href="#" class="nav-link text-white-50 hover-white d-flex align-items-center gap-3 py-2 px-3 rounded">
                <i class="bi bi-person-badge fs-5"></i>
                <span class="fw-medium">User Log</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-grow-1 d-flex flex-column h-100 overflow-hidden">

        <!-- TOP BAR HEADER -->
        <header class="bg-white border-bottom px-4 py-2 d-flex justify-content-between align-items-center shadow-sm flex-shrink-0" style="height: 56px;">
            <div class="text-muted small fw-medium">
                <?= date('D | F j, Y g:i:s A'); ?>
            </div>

            <!-- User Menu Profile -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-dark gap-2 dropdown-toggle" id="staffUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; background-color: #002B66; font-size: 0.8rem;">
                        <?= htmlspecialchars($userInitials); ?>
                    </div>
                    <div class="text-start lh-sm">
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
        <main class="p-3 flex-grow-1 d-flex flex-column overflow-hidden">

            <!-- Title & Compact Breadcrumb -->
            <div class="d-flex align-items-center justify-content-between mb-2 flex-shrink-0">
                <h4 class="fw-bold text-dark mb-0" style="color: #0d1b2a;">Staff Queue Dashboard</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Queue Management</li>
                    </ol>
                </nav>
            </div>

            <!-- HERO CARD (CURRENTLY SERVING + 4 EQUAL SIZED ACTION BUTTONS) -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-3 flex-shrink-0 bg-white">
                <div class="card-body p-3">
                    
                    <div class="row align-items-center g-3">
                        
                        <!-- 1. Ticket Highlight Box -->
                        <div class="col-md-3">
                            <div class="rounded-3 p-3 text-center border" style="background-color: #f8faff; border-color: #d0e1fd !important;">
                                <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    NOW CALLING
                                </div>
                                <h1 class="fw-extrabold text-primary mb-1" style="font-size: 2.5rem; letter-spacing: -1px;">CA-018</h1>
                                <span class="badge bg-success text-white rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">
                                    <i class="bi bi-record-fill me-1 text-warning"></i> IN SERVICE
                                </span>
                            </div>
                        </div>

                        <!-- 2. Student Details with Timers Below -->
                        <div class="col-md-5 border-end pe-md-4">
                            <!-- Student Info Top -->
                            <div class="d-flex align-items-center gap-3 mb-2.5">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold fs-4 flex-shrink-0" style="width: 44px; height: 44px; background-color: #e7f0ff;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h5 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 1.15rem;">Juan Dela Cruz</h5>
                                    <p class="text-muted small mb-0" style="font-size: 0.8rem;"><i class="bi bi-mortarboard me-1"></i>BSIT - 3A</p>
                                    <span class="badge bg-primary bg-opacity-10 mb-2 text-primary fw-semibold px-2 py-0.5 rounded-2" style="font-size: 0.72rem;">
                                        <i class="bi bi-credit-card me-1"></i> Payment Transaction
                                    </span>
                                </div>
                            </div>

                            <!-- Timers Below Student Info -->
                            <div class="pt-2 border-top">
                                <div class="row g-2 text-center">
                                    <div class="col-4">
                                        <div class="p-1.5">
                                            <div class="text-muted" style="font-size: 0.65rem;"><i class="bi bi-clock me-1 text-primary"></i>Called at</div>
                                            <strong class="text-dark d-block" style="font-size: 0.82rem;">10:20 AM</strong>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-1.5">
                                            <div class="text-muted" style="font-size: 0.65rem;"><i class="bi bi-hourglass-split me-1 text-warning"></i>Waiting</div>
                                            <strong class="text-dark d-block" style="font-size: 0.82rem;">3m</strong>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-1.5">
                                            <div class="text-muted" style="font-size: 0.65rem;"><i class="bi bi-stopwatch me-1 text-success"></i>Service</div>
                                            <strong class="text-dark d-block" style="font-size: 0.82rem;">12m</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Perfectly Aligned & Larger Action Buttons -->
                        <div class="col-md-4 ps-md-3">
                            <div class="row g-2 align-items-stretch">
                                <div class="col-6 d-flex">
                                    <button class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 h-100" style="font-size: 0.88rem;">
                                        <i class="bi bi-play-circle-fill fs-4"></i>
                                        <span>Call Next</span>
                                    </button>
                                </div>
                                <div class="col-6 d-flex">
                                    <button class="btn btn-success w-100 py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 h-100" style="font-size: 0.88rem;">
                                        <i class="bi bi-check-circle-fill fs-4"></i>
                                        <span>Complete</span>
                                    </button>
                                </div>
                                <div class="col-6 d-flex">
                                    <button class="btn btn-warning text-dark w-100 py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 h-100" style="font-size: 0.88rem;">
                                        <i class="bi bi-arrow-clockwise fs-4"></i>
                                        <span>Recall</span>
                                    </button>
                                </div>
                                <div class="col-6 d-flex">
                                    <button class="btn text-white w-100 py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 h-100" style="background-color: #6f42c1; font-size: 0.88rem;">
                                        <i class="bi bi-arrow-right-left fs-4"></i>
                                        <span>Transfer</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- LOWER SECTION (WAITING QUEUE & TODAY'S SUMMARY) -->
            <div class="row g-3 flex-grow-1 overflow-hidden">
                
                <!-- WAITING QUEUE TABLE (LEFT SIDE) -->
                <div class="col-lg-8 d-flex flex-column h-100">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100 d-flex flex-column overflow-hidden">
                        
                        <!-- Header & Badges -->
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-shrink-0">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-people-fill text-primary fs-5"></i>
                                <h6 class="fw-bold text-dark mb-0">Waiting Queue</h6>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">7 in queue</span>
                            </div>
                        </div>

                        <!-- Compact Filters -->
                        <div class="row g-2 mb-3 flex-shrink-0">
                            <div class="col-md-7">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control bg-light border-start-0" placeholder="Search ticket number or student name...">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <select class="form-select form-select-sm bg-light">
                                    <option selected>All Services</option>
                                    <option value="payment">Payment</option>
                                    <option value="enrollment">Enrollment</option>
                                    <option value="certification">Certification</option>
                                </select>
                            </div>
                        </div>

                        <!-- Scrollable Internal Table Container -->
                        <div class="table-responsive flex-grow-1 overflow-auto">
                            <table class="table table-hover align-middle mb-0 small">
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

                <!-- TODAY'S QUEUE OVERVIEW / SUMMARY (RIGHT SIDE) -->
                <div class="col-lg-4 d-flex flex-column h-100">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100 d-flex flex-column justify-content-between overflow-auto">
                        <div>
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart-fill me-1 text-primary"></i>Today's Queue Overview</h6>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light border border-primary border-opacity-25 rounded-3 text-center shadow-sm">
                                        <small class="text-primary fw-bold d-block mb-1" style="font-size: 0.75rem;">Waiting</small>
                                        <h2 class="fw-bold text-primary mb-0">7</h2>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light border border-success border-opacity-25 rounded-3 text-center shadow-sm">
                                        <small class="text-success fw-bold d-block mb-1" style="font-size: 0.75rem;">Served</small>
                                        <h2 class="fw-bold text-success mb-0">42</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <small class="text-muted fw-semibold" style="font-size: 0.75rem;">Avg. Service Time</small>
                                    <strong class="text-dark" style="font-size: 0.85rem;"><i class="bi bi-speedometer2 text-info me-1"></i> 4m 30s</strong>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<?php 
// 4. Include Global Footer or render local Bootstrap JS script from assets
$footerPath = __DIR__ . '/../../includes/footer.php';
if (file_exists($footerPath)) {
    require_once $footerPath;
} else {
?>
<!-- Local Bootstrap JS Bundle from Assets -->
<script src="<?= htmlspecialchars($baseUrl); ?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
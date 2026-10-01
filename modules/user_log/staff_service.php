<?php
// user_log/staff_service.php

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

$fullName = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Staff User';
$userInitials = 'SU';
if (!empty($fullName)) {
    $nameParts = explode(' ', trim($fullName));
    $firstInitial = $nameParts[0][0] ?? '';
    $lastInitial = isset($nameParts[1]) ? $nameParts[count($nameParts) - 1][0] : '';
    $userInitials = strtoupper($firstInitial . $lastInitial);
}

// Selected Date Filter (Defaults to Today)
$selectedDate = $_GET['date'] ?? date('Y-m-d');

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
    <title>Service History - City College of Calamba</title>
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

    .badge-completed { background-color: #d1e7dd; color: #0f5132; }
    .badge-skipped { background-color: #ffe69c; color: #664d03; }
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
        
        <!-- TOP BAR HEADER -->
        <header class="bg-white border-bottom px-3 px-md-4 py-2 d-flex justify-content-between align-items-center shadow-sm flex-shrink-0" style="min-height: 56px;">
            <div class="text-muted small fw-medium text-truncate me-2">
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
            
            <!-- Header & Per-Day Date Selector Controls -->
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-3 flex-shrink-0 gap-2">
                <div>
                    <h4 class="fw-bold text-dark mb-0">Service History</h4>
                    <p class="text-muted small mb-0">View today's transactions and past daily records.</p>
                </div>
                
                <!-- Per-Day Date Selector Form -->
                <form method="GET" action="staff_service.php" class="d-flex align-items-center gap-2">
                    <div class="btn-group btn-group-sm me-1" role="group">
                        <a href="staff_service.php?date=<?= date('Y-m-d'); ?>" class="btn btn-outline-secondary <?= ($selectedDate === date('Y-m-d')) ? 'active' : ''; ?>">Today</a>
                        <a href="staff_service.php?date=<?= date('Y-m-d', strtotime('-1 day')); ?>" class="btn btn-outline-secondary <?= ($selectedDate === date('Y-m-d', strtotime('-1 day'))) ? 'active' : ''; ?>">Yesterday</a>
                    </div>
                    <input type="date" name="date" class="form-control form-control-sm bg-white border-secondary-subtle fw-medium shadow-sm" value="<?= htmlspecialchars($selectedDate); ?>" onchange="this.form.submit()">
                </form>
            </div>

            <!-- Table Card Container -->
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 flex-grow-1 d-flex flex-column">
                
                <!-- Filters Row -->
                <div class="row g-2 mb-3 flex-shrink-0">
                    <div class="col-12 col-md-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control bg-light border-start-0" placeholder="Search by ticket number or student name..">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <select class="form-select form-select-sm bg-light border-secondary-subtle">
                            <option selected>All Services</option>
                            <option value="payment">Payment</option>
                            <option value="enrollment">Enrollment</option>
                            <option value="certification">Certification</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <select class="form-select form-select-sm bg-light border-secondary-subtle">
                            <option selected>All Statuses</option>
                            <option value="completed">Completed</option>
                            <option value="skipped">Skipped</option>
                        </select>
                    </div>
                </div>

                <!-- Scrollable Table -->
                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 0.85rem;">
                        <thead class="table-light text-muted sticky-top fw-semibold" style="font-size: 0.78rem;">
                            <tr>
                                <th scope="col" style="width: 40px;">#</th>
                                <th scope="col">Ticket Number</th>
                                <th scope="col">Student Name</th>
                                <th scope="col">Service Type</th>
                                <th scope="col">Called At</th>
                                <th scope="col">Completed At</th>
                                <th scope="col" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-muted">1</td>
                                <td class="fw-bold text-primary">CA-018</td>
                                <td>Juan Dela Cruz</td>
                                <td>Payment</td>
                                <td>10:20 AM</td>
                                <td>10:32 AM</td>
                                <td class="text-center"><span class="badge badge-completed rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">2</td>
                                <td class="fw-bold text-primary">CA-017</td>
                                <td>Maria Santos</td>
                                <td>Enrollment</td>
                                <td>10:16 AM</td>
                                <td>10:20 AM</td>
                                <td class="text-center"><span class="badge badge-completed rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">3</td>
                                <td class="fw-bold text-primary">CA-016</td>
                                <td>Pedro Cruz</td>
                                <td>Certification</td>
                                <td>10:12 AM</td>
                                <td class="text-muted">—</td>
                                <td class="text-center"><span class="badge badge-skipped rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">Skipped</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">4</td>
                                <td class="fw-bold text-primary">CA-015</td>
                                <td>Ana Reyes</td>
                                <td>Payment</td>
                                <td>10:08 AM</td>
                                <td>10:15 AM</td>
                                <td class="text-center"><span class="badge badge-completed rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">5</td>
                                <td class="fw-bold text-primary">CA-014</td>
                                <td>Luis Garcia</td>
                                <td>Enrollment</td>
                                <td>10:03 AM</td>
                                <td>10:10 AM</td>
                                <td class="text-center"><span class="badge badge-completed rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">6</td>
                                <td class="fw-bold text-primary">CA-013</td>
                                <td>Carla Mendoza</td>
                                <td>Certification</td>
                                <td>9:58 AM</td>
                                <td>10:04 AM</td>
                                <td class="text-center"><span class="badge badge-completed rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">7</td>
                                <td class="fw-bold text-primary">CA-012</td>
                                <td>Ramon Santos</td>
                                <td>Payment</td>
                                <td>9:52 AM</td>
                                <td>9:57 AM</td>
                                <td class="text-center"><span class="badge badge-completed rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">8</td>
                                <td class="fw-bold text-primary">CA-011</td>
                                <td>Bea Lim</td>
                                <td>Enrollment</td>
                                <td>9:47 AM</td>
                                <td>9:53 AM</td>
                                <td class="text-center"><span class="badge badge-completed rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">9</td>
                                <td class="fw-bold text-primary">CA-010</td>
                                <td>John dela Cruz</td>
                                <td>Certification</td>
                                <td>9:41 AM</td>
                                <td>9:48 AM</td>
                                <td class="text-center"><span class="badge badge-completed rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">Completed</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">10</td>
                                <td class="fw-bold text-primary">CA-009</td>
                                <td>Micaela Torres</td>
                                <td>Payment</td>
                                <td>9:35 AM</td>
                                <td>9:42 AM</td>
                                <td class="text-center"><span class="badge badge-completed rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem;">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination & Record Count -->
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center pt-3 border-top mt-auto gap-2 flex-shrink-0">
                    <span class="text-muted small">Showing 1–10 of 42 records for <strong><?= date('M j, Y', strtotime($selectedDate)); ?></strong></span>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                            <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                        </ul>
                    </nav>
                </div>

            </div>
        </main>
    </div>
</div>

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
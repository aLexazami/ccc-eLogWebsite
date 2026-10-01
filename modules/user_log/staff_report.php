<?php
// user_log/staff_report.php

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
    <title>Reports - City College of Calamba</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl); ?>assets/css/bootstrap-icons.css">
    <!-- Chart.js for Reports Visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    .metric-card {
        background-color: #ffffff;
        border-radius: 12px;
        transition: transform 0.15s ease-in-out;
    }
    .metric-card:hover {
        transform: translateY(-2px);
    }
    .icon-box {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    /* Standardized Royal Blue Styling for Content Components */
    .text-royal-blue {
        color: #4169E1 !important;
    }
    .bg-royal-blue-light {
        background-color: #eef2ff !important;
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
                <!-- 1. Dashboard -->
                <a href="staff_dashboard.php" class="nav-link <?= ($currentPage === 'staff_dashboard.php') ? 'active bg-white bg-opacity-10 text-white' : 'text-white-50 hover-white'; ?> d-flex align-items-center gap-3 py-2 px-3 rounded">
                    <i class="bi bi-house-door fs-5"></i>
                    <span class="fw-medium">Dashboard</span>
                </a>
                <!-- 2. Service History -->
                <a href="staff_service.php" class="nav-link <?= ($currentPage === 'staff_service.php') ? 'active bg-white bg-opacity-10 text-white' : 'text-white-50 hover-white'; ?> d-flex align-items-center gap-3 py-2 px-3 rounded">
                    <i class="bi bi-clock-history fs-5"></i>
                    <span class="fw-medium">Service History</span>
                </a>
                <!-- 3. Reports -->
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

            <!-- Title & Date Selector Header -->
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-3 flex-shrink-0 gap-2">
                <div>
                    <h4 class="fw-bold text-dark mb-0">Reports</h4>
                    <p class="text-muted small mb-0">View service statistics and performance data.</p>
                </div>

                <form method="GET" action="staff_report.php" class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm bg-white rounded-2 shadow-sm border">
                        <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-calendar-event"></i></span>
                        <input type="date" name="date" class="form-control border-0 bg-transparent fw-medium" value="<?= htmlspecialchars($selectedDate); ?>" onchange="this.form.submit()">
                    </div>
                </form>
            </div>

            <!-- TOP SUMMARY METRIC CARDS -->
            <div class="row g-3 mb-3 flex-shrink-0">
                <!-- Card 1: Total Served -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm p-3 metric-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="icon-box bg-royal-blue-light text-royal-blue">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <span class="text-muted small fw-semibold">Total Served</span>
                        </div>
                        <h2 class="fw-bold text-dark mb-1">42</h2>
                        <small class="text-success fw-semibold"><i class="bi bi-arrow-up-short"></i> 12% <span class="text-muted fw-normal">from yesterday</span></small>
                    </div>
                </div>

                <!-- Card 2: Total Skipped/Missed -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm p-3 metric-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="icon-box bg-royal-blue-light text-royal-blue">
                                <i class="bi bi-arrow-clockwise"></i>
                            </div>
                            <span class="text-muted small fw-semibold">Total Skipped/Missed</span>
                        </div>
                        <h2 class="fw-bold text-dark mb-1">3</h2>
                        <small class="text-success fw-semibold"><i class="bi bi-arrow-down-short"></i> 25% <span class="text-muted fw-normal">from yesterday</span></small>
                    </div>
                </div>

                <!-- Card 3: Average Waiting Time -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm p-3 metric-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="icon-box bg-royal-blue-light text-royal-blue">
                                <i class="bi bi-clock"></i>
                            </div>
                            <span class="text-muted small fw-semibold">Average Waiting Time</span>
                        </div>
                        <h2 class="fw-bold text-dark mb-1">4m</h2>
                        <small class="text-success fw-semibold"><i class="bi bi-arrow-down-short"></i> 33% <span class="text-muted fw-normal">from yesterday</span></small>
                    </div>
                </div>

                <!-- Card 4: Average Service Time -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm p-3 metric-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="icon-box bg-royal-blue-light text-royal-blue">
                                <i class="bi bi-stopwatch"></i>
                            </div>
                            <span class="text-muted small fw-semibold">Average Service Time</span>
                        </div>
                        <h2 class="fw-bold text-dark mb-1">12m</h2>
                        <small class="text-success fw-semibold"><i class="bi bi-arrow-down-short"></i> 20% <span class="text-muted fw-normal">from yesterday</span></small>
                    </div>
                </div>
            </div>

            <!-- CHARTS SECTION -->
            <div class="row g-3 mb-3 flex-shrink-0">
                <!-- Bar Chart: Tickets Served by Hour -->
                <div class="col-12 col-lg-7">
                    <div class="card border-0 shadow-sm p-3 bg-white h-100 rounded-3">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-bar-chart-fill text-royal-blue"></i>
                            <h6 class="fw-bold text-dark mb-0">Tickets Served by Hour</h6>
                        </div>
                        <div style="position: relative; height: 220px;">
                            <canvas id="ticketsByHourChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Doughnut Chart: Service Type Distribution -->
                <div class="col-12 col-lg-5">
                    <div class="card border-0 shadow-sm p-3 bg-white h-100 rounded-3">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-pie-chart-fill text-royal-blue"></i>
                            <h6 class="fw-bold text-dark mb-0">Service Type Distribution</h6>
                        </div>
                        
                        <div class="row align-items-center g-2 my-auto">
                            <div class="col-6 position-relative d-flex justify-content-center">
                                <div style="width: 150px; height: 150px;">
                                    <canvas id="serviceDistributionChart"></canvas>
                                </div>
                                <div class="position-absolute top-50 start-50 translate-middle text-center pointer-events-none">
                                    <span class="h4 fw-bold text-dark d-block mb-0 lh-1">42</span>
                                    <small class="text-muted" style="font-size: 0.65rem;">Total</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <ul class="list-unstyled mb-0 small pe-2">
                                    <li class="d-flex align-items-center justify-content-between mb-2">
                                        <span><i class="bi bi-circle-fill me-1 text-royal-blue" style="font-size: 0.65rem;"></i> Payment</span>
                                        <span class="fw-bold text-dark">18 <span class="text-muted fw-normal">(43%)</span></span>
                                    </li>
                                    <li class="d-flex align-items-center justify-content-between mb-2">
                                        <span><i class="bi bi-circle-fill me-1" style="color: #198754; font-size: 0.65rem;"></i> Enrollment</span>
                                        <span class="fw-bold text-dark">12 <span class="text-muted fw-normal">(29%)</span></span>
                                    </li>
                                    <li class="d-flex align-items-center justify-content-between mb-2">
                                        <span><i class="bi bi-circle-fill me-1" style="color: #ffc107; font-size: 0.65rem;"></i> Certification</span>
                                        <span class="fw-bold text-dark">8 <span class="text-muted fw-normal">(19%)</span></span>
                                    </li>
                                    <li class="d-flex align-items-center justify-content-between">
                                        <span><i class="bi bi-circle-fill me-1" style="color: #6f42c1; font-size: 0.65rem;"></i> Others</span>
                                        <span class="fw-bold text-dark">4 <span class="text-muted fw-normal">(9%)</span></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLES SECTION -->
            <div class="row g-3 flex-grow-1">
                <!-- Table 1: Daily Summary -->
                <div class="col-12 col-lg-5">
                    <div class="card border-0 shadow-sm p-3 bg-white h-100 rounded-3 d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-calendar-check text-royal-blue"></i>
                            <h6 class="fw-bold text-dark mb-0">Daily Summary</h6>
                        </div>
                        <div class="table-responsive flex-grow-1">
                            <table class="table align-middle mb-0 small">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th scope="col" class="fw-semibold">Metric</th>
                                        <th scope="col" class="text-end fw-semibold">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Total Tickets</td>
                                        <td class="text-end fw-bold text-dark">42</td>
                                    </tr>
                                    <tr>
                                        <td>Completed</td>
                                        <td class="text-end fw-bold text-dark">38</td>
                                    </tr>
                                    <tr>
                                        <td>Skipped</td>
                                        <td class="text-end fw-bold text-dark">3</td>
                                    </tr>
                                    <tr>
                                        <td>Transferred</td>
                                        <td class="text-end fw-bold text-dark">1</td>
                                    </tr>
                                    <tr>
                                        <td>Currently Waiting</td>
                                        <td class="text-end fw-bold text-dark">7</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Table 2: Peak Hours -->
                <div class="col-12 col-lg-7">
                    <div class="card border-0 shadow-sm p-3 bg-white h-100 rounded-3 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-clock-history text-royal-blue"></i>
                                <h6 class="fw-bold text-dark mb-0">Peak Hours</h6>
                            </div>
                        </div>
                        <div class="table-responsive flex-grow-1">
                            <table class="table align-middle mb-0 small">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th scope="col" class="fw-semibold">Time Range</th>
                                        <th scope="col" class="text-end fw-semibold">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>8:00 AM – 9:00 AM</td>
                                        <td class="text-end fw-bold text-dark">8</td>
                                    </tr>
                                    <tr>
                                        <td>9:00 AM – 10:00 AM</td>
                                        <td class="text-end fw-bold text-dark">15</td>
                                    </tr>
                                    <tr>
                                        <td>10:00 AM – 11:00 AM</td>
                                        <td class="text-end fw-bold text-dark">18</td>
                                    </tr>
                                    <tr>
                                        <td>11:00 AM – 12:00 PM</td>
                                        <td class="text-end fw-bold text-dark">12</td>
                                    </tr>
                                    <tr>
                                        <td>1:00 PM – 2:00 PM</td>
                                        <td class="text-end fw-bold text-dark">9</td>
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. Bar Chart - Tickets Served by Hour
    const ctxBar = document.getElementById('ticketsByHourChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM'],
            datasets: [{
                data: [4, 7, 10, 8, 3, 4, 3, 2, 1],
                backgroundColor: '#4169E1',
                hoverBackgroundColor: '#2b54c6',
                borderRadius: 4,
                barThickness: 18
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 12,
                    ticks: { stepSize: 3 },
                    grid: { color: '#f0f0f0' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // 2. Doughnut Chart - Service Type Distribution
    const ctxDoughnut = document.getElementById('serviceDistributionChart').getContext('2d');
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['Payment', 'Enrollment', 'Certification', 'Others'],
            datasets: [{
                data: [18, 12, 8, 4],
                backgroundColor: ['#4169E1', '#198754', '#ffc107', '#6f42c1'],
                borderWidth: 0,
                cutout: '72%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
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
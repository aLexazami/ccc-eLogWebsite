<?php
// logbook/index.php
$pageTitle = "Select Client Type";

// Include Header (Auto-loads config.php and starts <body>)
require_once __DIR__ . '/../includes/header.php';
?>

<div class="portal-bg min-vh-100 py-4 d-flex flex-column justify-content-between text-white">
    
    <!-- MAIN PAGE CONTAINER -->
    <div class="container my-auto py-2">
        
        <!-- Navigation Header -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="<?= BASE_URL; ?>/" class="btn btn-back text-decoration-none" role="button">
                    <span class="btn-back-icon" aria-hidden="true">
                        <i class="bi bi-arrow-left"></i>
                    </span>
                    <span class="btn-back-label fw-semibold">Back</span>
                </a>
            </div>
        </div>

        <!-- Page Header Title -->
        <div class="text-center mb-5">
            <h1 class="app-title fw-bold tracking-tight mb-2">Select Client Type</h1>
        </div>

        <!-- Selection Cards Grid -->
        <div class="row g-4 justify-content-center">
            
            <!-- Student Card -->
            <div class="col-md-6 col-lg-4">
                <div class="card custom-card h-100 shadow-lg rounded-4 p-4 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-badge mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-mortarboard fs-1" style="color: var(--color-powder-blue);" aria-hidden="true"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-2">Student</h3>
                    </div>
                    <div>
                        <a href="<?= BASE_URL; ?>/logbook/student_info.php" class="btn btn-portal mt-3 btn-oval w-100 shadow-sm" role="button">
                            <span class="btn-label fw-semibold">Select Student</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- School Personnel Card -->
            <div class="col-md-6 col-lg-4">
                <div class="card custom-card h-100 shadow-lg rounded-4 p-4 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-badge mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-badge fs-1" style="color: var(--color-powder-blue);" aria-hidden="true"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-2">School Personnel</h3>
                    </div>
                    <div>
                        <a href="<?= BASE_URL; ?>/logbook/personnel_info.php" class="btn btn-portal mt-3 btn-oval w-100 shadow-sm" role="button">
                            <span class="btn-label fw-semibold">Select Personnel</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Guest / Visitor Card -->
            <div class="col-md-6 col-lg-4">
                <div class="card custom-card h-100 shadow-lg rounded-4 p-4 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="icon-badge mx-auto mb-4 d-flex align-items-center justify-content-center">
                            <i class="bi bi-person fs-1" style="color: var(--color-powder-blue);" aria-hidden="true"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-2">Guest / Visitor</h3>
                    </div>
                    <div>
                        <a href="<?= BASE_URL; ?>/logbook/guest_info.php" class="btn btn-portal mt-3 btn-oval w-100 shadow-sm" role="button">
                            <span class="btn-label fw-semibold">Select Guest</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- IN-PAGE FOOTER CREDIT BLOCK -->
    <div class="text-center py-3 mt-auto">
        <p class="small mb-0" style="color: var(--color-blue-gray);">
            &copy; <?= date('Y'); ?> City College of Calamba &bull; All Rights Reserved
        </p>
    </div>

</div>

<?php
// Include Footer
require_once __DIR__ . '/../includes/footer.php';
?>
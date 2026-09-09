<?php
// kiosk/index.php
$pageTitle = "Select Client Type";
$pageScript = "/assets/js/kiosk.js";

// Include Header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="portal-bg py-5">
    <div class="container">
        
        <!-- Navigation Header -->
        <div class="row mb-4">
            <div class="col-12 d-flex align-items-center">
                <a href="javascript:history.back()" class="btn btn-back text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> BACK
                </a>
            </div>
        </div>

        <!-- Page Header Title -->
        <div class="text-center mb-5">
            <h1 class="fw-bold display-6 mb-2" style="color: #ffffff;">Select Client Type</h1>
            <p style="color: var(--color-blue-gray);">Please select the type of client making the transaction.</p>
        </div>

        <!-- Selection Cards Grid -->
        <div class="row g-4 justify-content-center">
            
            <!-- Student Card -->
            <div class="col-md-4">
                <div class="card client-type-card h-100 p-4 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="client-icon-wrapper d-flex align-items-center justify-content-center mb-4">
                            <i class="bi bi-mortarboard fs-1" style="color: var(--color-powder-blue);"></i>
                        </div>
                        <h3 class="fw-bold mb-3" style="color: #ffffff;">Student</h3>
                        <p class="small mb-4" style="color: var(--color-blue-gray);">
                            For currently enrolled students who need to inquire, request services, or visit a school office.
                        </p>
                    </div>
                    <div>
                        <a href="student_info.php" class="btn btn-select-type w-100 text-uppercase">
                            SELECT STUDENT <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- School Personnel Card -->
            <div class="col-md-4">
                <div class="card client-type-card h-100 p-4 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="client-icon-wrapper d-flex align-items-center justify-content-center mb-4">
                            <i class="bi bi-person-badge fs-1" style="color: var(--color-powder-blue);"></i>
                        </div>
                        <h3 class="fw-bold mb-3" style="color: #ffffff;">School Personnel</h3>
                        <p class="small mb-4" style="color: var(--color-blue-gray);">
                            For teachers, faculty, staff, administrators, and other school personnel.
                        </p>
                    </div>
                    <div>
                        <a href="personnel_info.php" class="btn btn-select-type w-100 text-uppercase">
                            SELECT PERSONNEL <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Guest / Visitor Card -->
            <div class="col-md-4">
                <div class="card client-type-card h-100 p-4 text-center d-flex flex-column justify-content-between">
                    <div>
                        <div class="client-icon-wrapper d-flex align-items-center justify-content-center mb-4">
                            <i class="bi bi-person fs-1" style="color: var(--color-powder-blue);"></i>
                        </div>
                        <h3 class="fw-bold mb-3" style="color: #ffffff;">Guest / Visitor</h3>
                        <p class="small mb-4" style="color: var(--color-blue-gray);">
                            For parents, visitors, applicants, partners, and other individuals from outside the school.
                        </p>
                    </div>
                    <div>
                        <a href="guest_info.php" class="btn btn-select-type w-100 text-uppercase">
                            SELECT GUEST <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
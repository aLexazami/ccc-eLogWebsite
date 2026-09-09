<?php
// personnel_info.php
$pageTitle = "Personnel Information";
$pageScript = "/assets/js/kiosk.js";

require_once __DIR__ . '/../includes/header.php';
?>

<div class="portal-bg py-5">
    <div class="container">
        
        <!-- Navigation Header -->
        <div class="row mb-4">
            <div class="col-12 col-lg-8 mx-auto d-flex align-items-center">
                <a href="index.php" class="btn btn-back text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> BACK
                </a>
            </div>
        </div>

        <!-- Main Form Card Container -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card custom-card p-4 p-md-5 rounded-4">
                    
                    <!-- Top Category Badge & Title -->
                    <div class="mb-4">
                        <span class="category-badge mb-3">SCHOOL PERSONNEL</span>
                        <h1 class="fw-bold mb-2" style="color: #ffffff;">Personnel Information</h1>
                        <p class="mb-0" style="color: var(--color-blue-gray);">
                            Please provide your faculty/staff details before proceeding.
                        </p>
                    </div>

                    <!-- Form -->
                    <form id="kioskForm" action="dept_select.php" method="POST" novalidate>
                        <input type="hidden" name="client_type" value="personnel">

                        <div class="mb-3">
                            <label for="employeeId" class="form-label-custom">Employee / Faculty ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control custom-glass-control" id="employeeId" name="employee_id" placeholder="Enter your employee ID" required>
                        </div>

                        <div class="mb-3">
                            <label for="fullName" class="form-label-custom">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control custom-glass-control" id="fullName" name="full_name" placeholder="Enter your full name" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="department" class="form-label-custom">Department / Office <span class="text-danger">*</span></label>
                                <select class="form-select custom-glass-control" id="department" name="department" required>
                                    <option value="" selected disabled>Select department</option>
                                    <option value="College of Computer Studies">College of Computer Studies</option>
                                    <option value="College of Business">College of Business</option>
                                    <option value="Administration">Administration</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="position" class="form-label-custom">Position / Role <span class="text-danger">*</span></label>
                                <input type="text" class="form-control custom-glass-control" id="position" name="position" placeholder="e.g. Instructor, Staff" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="contactNumber" class="form-label-custom">Contact Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control custom-glass-control" id="contactNumber" name="contact_number" placeholder="09XXXXXXXXX" required>
                        </div>

                        <div class="consent-card-box mb-4">
                            <div class="form-check d-flex align-items-start gap-2">
                                <input class="form-check-input mt-1" type="checkbox" id="dataConsent" name="data_consent" required>
                                <label class="form-check-label" for="dataConsent">
                                    I authorize the school to collect and use the information I have provided for 
                                    <strong class="text-white">school-related services, queue management, and record-keeping purposes</strong>. <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-submit-action w-100">
                            Continue to Office Selection <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
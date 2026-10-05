<?php
// select_inquiry.php - MUST BE AT THE VERY TOP BEFORE HEADER
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Capture and persist department ID immediately
if (isset($_GET['dept_id'])) {
    $_SESSION['dept_id'] = (int)$_GET['dept_id'];
}

// 2. Fallback check - if no dept_id exists at all, default to 1 (Admissions)
$deptId = $_SESSION['dept_id'] ?? 1;

// Master database mapping for all 16 departments with Custom Inquiry included
$deptData = [
    // --- ADMIN BUILDING ---
    1 => [
        'name' => 'Admissions Office',
        'code' => 'ADM',
        'icon' => 'bi-person-plus',
        'transactions' => [
            ['id' => 101, 'title' => 'Freshman Admission', 'desc' => 'Inquire about application requirements and submission.'],
            ['id' => 102, 'title' => 'Transferee Evaluation', 'desc' => 'Submit documents for credit evaluation.'],
            ['id' => 103, 'title' => 'Readmission', 'desc' => 'Apply for student readmission status.'],
            ['id' => 199, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for Admissions.']
        ]
    ],
    2 => [
        'name' => 'Accounting Office',
        'code' => 'ACC',
        'icon' => 'bi-cash-coin',
        'transactions' => [
            ['id' => 201, 'title' => 'Statement of Account', 'desc' => 'Request an updated copy of your tuition balance.'],
            ['id' => 202, 'title' => 'Payment Clearance', 'desc' => 'Clear outstanding balances for examination permits.'],
            ['id' => 203, 'title' => 'Refund Request', 'desc' => 'Apply for overpayment or tuition fee refunds.'],
            ['id' => 299, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for Accounting.']
        ]
    ],
    3 => [
        'name' => 'Cashier',
        'code' => 'CSH',
        'icon' => 'bi-credit-card',
        'transactions' => [
            ['id' => 301, 'title' => 'Tuition Payment', 'desc' => 'Pay tuition fees, entrance fees, or balance installments.'],
            ['id' => 302, 'title' => 'Document Request Fee', 'desc' => 'Pay for requested transcripts, diplomas, or certifications.'],
            ['id' => 399, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for Cashier.']
        ]
    ],
    4 => [
        'name' => 'Human Resources',
        'code' => 'HR',
        'icon' => 'bi-person-badge',
        'transactions' => [
            ['id' => 401, 'title' => 'Employment Inquiry', 'desc' => 'Inquire about open job positions or submit applications.'],
            ['id' => 402, 'title' => 'Personnel Records', 'desc' => 'Request COE or employment record updates.'],
            ['id' => 499, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for HR.']
        ]
    ],
    5 => [
        'name' => 'College of Business & Accountancy',
        'code' => 'CBA',
        'icon' => 'bi-briefcase',
        'transactions' => [
            ['id' => 501, 'title' => 'Academic Advising', 'desc' => 'Consult with CBA program head or advisers.'],
            ['id' => 502, 'title' => 'OJT / Internship', 'desc' => 'Submit internship endorsement requirements.'],
            ['id' => 599, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for CBA.']
        ]
    ],
    6 => [
        'name' => 'College of Education',
        'code' => 'CE',
        'icon' => 'bi-book',
        'transactions' => [
            ['id' => 601, 'title' => 'Practice Teaching Inquiry', 'desc' => 'Inquire about teaching internship deployments.'],
            ['id' => 602, 'title' => 'Academic Consultation', 'desc' => 'Speak with Education faculty advisers.'],
            ['id' => 699, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for CE.']
        ]
    ],
    7 => [
        'name' => 'MIS / IT Support',
        'code' => 'MIS',
        'icon' => 'bi-pc-display',
        'transactions' => [
            ['id' => 701, 'title' => 'Student Portal Reset', 'desc' => 'Reset forgotten portal password or credentials.'],
            ['id' => 702, 'title' => 'Wi-Fi Access Request', 'desc' => 'Register device for campus Wi-Fi connectivity.'],
            ['id' => 799, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for MIS.']
        ]
    ],
    8 => [
        'name' => 'Office of Student Affairs',
        'code' => 'OSA',
        'icon' => 'bi-people',
        'transactions' => [
            ['id' => 801, 'title' => 'Student Organization', 'desc' => 'Submit organization accreditation or activity permits.'],
            ['id' => 802, 'title' => 'Good Moral Certificate', 'desc' => 'Apply for a Certificate of Good Moral Character.'],
            ['id' => 899, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for OSA.']
        ]
    ],
    9 => [
        'name' => 'Property & Supply Office',
        'code' => 'PSO',
        'icon' => 'bi-box-seam',
        'transactions' => [
            ['id' => 901, 'title' => 'Uniform & Supplies', 'desc' => 'Claim or inquire regarding school uniforms and equipment.'],
            ['id' => 902, 'title' => 'Lost and Found', 'desc' => 'Inquire about or surrender lost personal items.'],
            ['id' => 999, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for Property & Supply.']
        ]
    ],
    10 => [
        'name' => 'Guidance & Counseling',
        'code' => 'GDC',
        'icon' => 'bi-heart-pulse',
        'transactions' => [
            ['id' => 1001, 'title' => 'Counseling Appointment', 'desc' => 'Schedule a personal or academic counseling session.'],
            ['id' => 1002, 'title' => 'Exit Interview', 'desc' => 'Complete required guidance exit clearance.'],
            ['id' => 1099, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for Guidance.']
        ]
    ],
    11 => [
        'name' => 'School Clinic',
        'code' => 'CLN',
        'icon' => 'bi-hospital',
        'transactions' => [
            ['id' => 1101, 'title' => 'Medical Clearance', 'desc' => 'Submit medical/dental examination forms.'],
            ['id' => 1102, 'title' => 'First Aid / Consultation', 'desc' => 'Receive medical assistance or consultations.'],
            ['id' => 1199, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for Clinic.']
        ]
    ],
    12 => [
        'name' => "Dean's Office",
        'code' => 'DO',
        'icon' => 'bi-building',
        'transactions' => [
            ['id' => 1201, 'title' => 'Overload / Cross-Enrolment', 'desc' => 'Submit permit for subject overload or cross-enrolment.'],
            ['id' => 1202, 'title' => 'General Inquiry', 'desc' => 'Inquire regarding college policies and concerns.'],
            ['id' => 1299, 'title' => 'Custom Inquiry', 'desc' => "Specify a unique concern or custom request for Dean's Office."]
        ]
    ],

    // --- RIZAL BUILDING ---
    13 => [
        'name' => 'College of Computer Studies',
        'code' => 'CCS',
        'icon' => 'bi-laptop',
        'transactions' => [
            ['id' => 1301, 'title' => 'Capstone / Thesis Concern', 'desc' => 'Consult regarding capstone defense or requirements.'],
            ['id' => 1302, 'title' => 'Lab Access Concern', 'desc' => 'Inquire about computer laboratory permissions.'],
            ['id' => 1399, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for CCS.']
        ]
    ],
    14 => [
        'name' => 'College of Industrial Technology',
        'code' => 'CIT',
        'icon' => 'bi-gear-wide-connected',
        'transactions' => [
            ['id' => 1401, 'title' => 'Shop / Tool Room Access', 'desc' => 'Request shop tools or technical equipment usage.'],
            ['id' => 1402, 'title' => 'Technical Advising', 'desc' => 'Consult with industrial technology faculty.'],
            ['id' => 1499, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for CIT.']
        ]
    ],

    // --- JMC BUILDING ---
    15 => [
        'name' => 'Library',
        'code' => 'LIB',
        'icon' => 'bi-journal-bookmark',
        'transactions' => [
            ['id' => 1501, 'title' => 'Library Card / Account', 'desc' => 'Apply for or renew library borrower account.'],
            ['id' => 1502, 'title' => 'Book Return & Fine Clearance', 'desc' => 'Return borrowed books or settle library fines.'],
            ['id' => 1599, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for Library.']
        ]
    ],
    16 => [
        'name' => 'Registrar Office',
        'code' => 'REG',
        'icon' => 'bi-file-earmark-text',
        'transactions' => [
            ['id' => 1601, 'title' => 'Request Transcript', 'desc' => 'Request an official copy of your academic transcript.'],
            ['id' => 1602, 'title' => 'Request Certification', 'desc' => 'Request enrollment or graduation certifications.'],
            ['id' => 1603, 'title' => 'Student Records', 'desc' => 'Inquire about student academic records and grades.'],
            ['id' => 1604, 'title' => 'Document Verification', 'desc' => 'Submit documents for verification and processing.'],
            ['id' => 1699, 'title' => 'Custom Inquiry', 'desc' => 'Specify a unique concern or custom request for Registrar.']
        ]
    ]
];

// Lookup department or fallback safely to Admissions (ID 1)
$currentDept = $deptData[$deptId] ?? $deptData[1];

// Persist selection to Session for queue_number.php
$_SESSION['dept_name'] = $currentDept['name'];
$_SESSION['dept_code'] = $currentDept['code'];

$baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';

// Cache-busting for kiosk-flow.js
$jsPath = __DIR__ . '/../../assets/js/kiosk-flow.js';
$jsVersion = file_exists($jsPath) ? filemtime($jsPath) : time();

$pageTitle = "Select Inquiry - " . $currentDept['name'];
// Inject kiosk-flow.js into the header
$extraJs = '<script src="' . $baseUrl . '/assets/js/kiosk-flow.js?v=' . $jsVersion . '"></script>';

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="portal-bg py-5 min-vh-100">
    <div class="container py-2">

        <!-- Selected Department Header Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card selected-dept-header-card p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="dept-icon-box flex-shrink-0">
                            <i class="bi <?= htmlspecialchars($currentDept['icon']) ?> fs-2"></i>
                        </div>
                        <div>
                            <h2 class="fw-black mb-1" style="color: var(--color-text-primary);"><?= htmlspecialchars($currentDept['name']) ?></h2>
                            <p class="mb-0 fw-semibold" style="color: var(--color-text-secondary);">Select the type of transaction you need.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Title -->
        <div class="mb-4">
            <h3 class="fw-black mb-1" style="color: var(--color-text-primary);">Select Transaction</h3>
            <p class="mb-0 fw-semibold" style="color: var(--color-text-secondary);">Choose the transaction that best matches your request.</p>
        </div>

        <!-- Transaction Options Grid -->
        <div class="row g-3">
            <?php 
            $totalTx = count($currentDept['transactions']); 
            foreach ($currentDept['transactions'] as $index => $tx): 
                $isCustom = (strpos($tx['title'], 'Custom Inquiry') !== false);
                $isLastItem = ($index === $totalTx - 1);
                $isOddTotal = ($totalTx % 2 !== 0);

                // If total items is odd (e.g., 3, 5) and this is the last card (Custom Inquiry),
                // center it neatly on the bottom row.
                if ($isOddTotal && $isLastItem) {
                    $colClass = "col-12 col-md-8 mx-auto";
                } else {
                    $colClass = "col-12 col-md-6";
                }
            ?>
                <div class="<?= $colClass ?>">
                    <button type="button" 
                            class="card transaction-card w-100 p-4 text-start border-0"
                            onclick="openInquiryModal('<?= $tx['id'] ?>', '<?= htmlspecialchars($tx['title'], ENT_QUOTES) ?>')">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="d-flex align-items-center gap-3">
                                <div class="dept-icon-box flex-shrink-0">
                                    <i class="bi <?= $isCustom ? 'bi-pencil-square' : 'bi-file-text' ?> fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: var(--color-text-primary);"><?= htmlspecialchars($tx['title']) ?></h5>
                                    <p class="small mb-0 text-muted-custom"><?= htmlspecialchars($tx['desc']) ?></p>
                                </div>
                            </div>
                            <i class="bi bi-arrow-right fs-4 arrow-icon ms-2"></i>
                        </div>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<!-- Modal for Additional Information / Custom Inquiry Notes -->
<div class="modal fade" id="inquiryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-card border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTransactionTitle" style="color: var(--color-text-primary);">Additional Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="queue_number.php" method="POST" id="inquiryForm">
                <div class="modal-body py-4">
                    <input type="hidden" name="transaction_id" id="modalTxId">
                    <input type="hidden" name="transaction_title" id="modalTxTitleInput">
                    
                    <p class="small mb-3 fw-semibold" id="modalInstructionText" style="color: var(--color-text-secondary);">
                        You may provide specific details or concerns about your transaction (Optional).
                    </p>

                    <div class="mb-3">
                        <label for="inquiryDetails" class="form-label-custom" id="modalDetailsLabel">Notes / Purpose Details</label>
                        <textarea class="form-control custom-glass-control" 
                                  id="inquiryDetails" 
                                  name="inquiry_details" 
                                  rows="4" 
                                  placeholder="e.g. Requesting Transcript of Records for Employment..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer border-0 pt-0 d-flex gap-2">
                    <button type="submit" id="btnSkipProceed" class="btn btn-outline-secondary rounded-3 px-3 py-2 text-decoration-none fw-semibold">
                        Skip & Proceed
                    </button>
                    <button type="submit" class="btn btn-submit-action flex-grow-1">
                        <span>Submit & Continue</span>
                        <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
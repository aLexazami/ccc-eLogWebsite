<?php
// dept_select.php - MUST BE AT THE VERY TOP BEFORE HEADER
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Store submitted client information in session if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        $_SESSION[$key] = $value;
    }
}

$pageTitle = "Select Department";
$pageScript = "/assets/js/kiosk.js";

require_once __DIR__ . '/../includes/header.php';

// Mock list of departments (Fetch this from your database in production)
$departments = [
    // Academic & Colleges
    ['id' => 1, 'name' => 'College of Computer Studies', 'code' => 'CCS', 'category' => 'academic', 'icon' => 'bi-laptop'],
    ['id' => 2, 'name' => 'College of Business & Accountancy', 'code' => 'CBA', 'category' => 'academic', 'icon' => 'bi-briefcase'],
    ['id' => 3, 'name' => 'College of Education', 'code' => 'CED', 'category' => 'academic', 'icon' => 'bi-book'],
    ['id' => 4, 'name' => "Dean's Office", 'code' => 'DO', 'category' => 'academic', 'icon' => 'bi-building'],
    
    // Administrative & Student Services
    ['id' => 5, 'name' => 'Registrar', 'code' => 'REG', 'category' => 'admin', 'icon' => 'bi-file-earmark-text'],
    ['id' => 6, 'name' => 'Accounting Office', 'code' => 'ACC', 'category' => 'finance', 'icon' => 'bi-cash-coin'],
    ['id' => 7, 'name' => 'Cashier', 'code' => 'CSH', 'category' => 'finance', 'icon' => 'bi-credit-card'],
    ['id' => 8, 'name' => 'Admissions Office', 'code' => 'ADM', 'category' => 'admin', 'icon' => 'bi-person-plus'],
    ['id' => 9, 'name' => 'Guidance & Counseling', 'code' => 'GDC', 'category' => 'services', 'icon' => 'bi-heart-pulse'],
    ['id' => 10, 'name' => 'Student Affairs (OSA)', 'code' => 'OSA', 'category' => 'services', 'icon' => 'bi-people'],
    ['id' => 11, 'name' => 'Library', 'code' => 'LIB', 'category' => 'services', 'icon' => 'bi-journal-bookmark'],
    ['id' => 12, 'name' => 'School Clinic', 'code' => 'CLN', 'category' => 'services', 'icon' => 'bi-hospital'],
    ['id' => 13, 'name' => 'MIS / IT Support', 'code' => 'IT', 'category' => 'admin', 'icon' => 'bi-pc-display'],
    ['id' => 14, 'name' => 'Human Resources', 'code' => 'HR', 'category' => 'admin', 'icon' => 'bi-person-badge'],
    ['id' => 15, 'name' => 'Property & Supply Office', 'code' => 'PSO', 'category' => 'admin', 'icon' => 'bi-box-seam'],
];
?>

<div class="portal-bg py-5">
    <div class="container">
        
        <!-- Navigation Header -->
        <div class="row mb-4">
            <div class="col-12 d-flex align-items-center justify-content-between">
                <a href="javascript:history.back()" class="btn btn-back text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> BACK
                </a>
            </div>
        </div>

        <!-- Section Title & Search -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h1 class="fw-bold display-6 mb-1" style="color: #ffffff;">Select Office / Department</h1>
                <p class="mb-0" style="color: var(--color-blue-gray);">Choose the office or department you need to visit.</p>
            </div>
            
            <!-- Real-time Search Bar -->
            <div class="col-md-6">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="deptSearch" class="form-control custom-glass-control ps-5" placeholder="Search office or department name...">
                </div>
            </div>
        </div>

        <!-- Filter Category Tabs -->
        <div class="d-flex gap-2 overflow-auto mb-4 pb-2" id="categoryFilters">
            <button class="btn btn-filter active" data-filter="all">All Offices</button>
            <button class="btn btn-filter" data-filter="admin">Administrative</button>
            <button class="btn btn-filter" data-filter="finance">Finance & Payment</button>
            <button class="btn btn-filter" data-filter="academic">Academic & Colleges</button>
            <button class="btn btn-filter" data-filter="services">Student Services</button>
        </div>

        <!-- Department Cards Grid -->
        <div class="row g-3" id="deptGrid">
            <?php foreach ($departments as $dept): ?>
                <div class="col-6 col-md-4 col-lg-3 dept-item" 
                     data-category="<?= htmlspecialchars($dept['category']) ?>" 
                     data-name="<?= strtolower(htmlspecialchars($dept['name'])) ?>"
                     data-code="<?= strtolower(htmlspecialchars($dept['code'])) ?>">
                     
                    <a href="select_inquiry.php?dept_id=<?= $dept['id'] ?>" class="card dept-card h-100 p-3 text-decoration-none text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="dept-icon-box mb-3">
                            <i class="bi <?= htmlspecialchars($dept['icon']) ?> fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-white mb-1 dept-title"><?= htmlspecialchars($dept['name']) ?></h6>
                        <span class="dept-code-badge"><?= htmlspecialchars($dept['code']) ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Found State -->
        <div id="noResults" class="text-center py-5 d-none">
            <i class="bi bi-search fs-1 text-muted mb-2 d-block"></i>
            <h5 class="text-white">No matching offices found</h5>
            <p style="color: var(--color-blue-gray);">Try searching for a different keyword or select another category.</p>
        </div>

    </div>
</div>

<!-- Simple Filtering & Search Logic -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('deptSearch');
    const filterBtns = document.querySelectorAll('.btn-filter');
    const deptItems = document.querySelectorAll('.dept-item');
    const noResults = document.getElementById('noResults');

    let currentCategory = 'all';

    function filterDepartments() {
        const query = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        deptItems.forEach(item => {
            const category = item.dataset.category;
            const name = item.dataset.name;
            const code = item.dataset.code;

            const matchesCategory = (currentCategory === 'all' || category === currentCategory);
            const matchesSearch = name.includes(query) || code.includes(query);

            if (matchesCategory && matchesSearch) {
                item.classList.remove('d-none');
                visibleCount++;
            } else {
                item.classList.add('d-none');
            }
        });

        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }

    searchInput.addEventListener('input', filterDepartments);

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.dataset.filter;
            filterDepartments();
        });
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
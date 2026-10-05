/**
 * Main Kiosk Flow Management Script
 * File: assets/js/kiosk-flow.js
 */

document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // 1. STUDENT INFO PAGE AUTOMATION LOGIC
    // ==========================================
    const studentIdInput = document.getElementById('studentId');
    if (studentIdInput) {
        const fullNameInput = document.getElementById('fullName');
        const courseProgramSelect = document.getElementById('courseProgram');
        const yearLevelSelect = document.getElementById('yearLevel');
        const contactInput = document.getElementById('contactNumber');

        const mockFirstNames = ['Juan', 'Maria', 'Jose', 'Angelica', 'Christian', 'Kaye', 'John Mark', 'Patricia'];
        const mockLastNames = ['Dela Cruz', 'Santos', 'Reyes', 'Gonzales', 'Ramos', 'Mendoza', 'Aquino', 'Garcia'];
        const courses = ['BSCS', 'BSIT', 'BSBA'];

        studentIdInput.addEventListener('input', function (e) {
            let rawVal = e.target.value.replace(/[^0-9-]/g, '');
            e.target.value = rawVal;

            const value = rawVal.trim();
            const match = value.match(/^(\d{4})-(\d{5})$/);

            if (match) {
                const entryYear = parseInt(match[1], 10);
                const studentNum = parseInt(match[2], 10);

                const yearLevelMap = {
                    2023: '4th Year',
                    2024: '3rd Year',
                    2025: '2nd Year',
                    2026: '1st Year'
                };

                const calculatedYear = yearLevelMap[entryYear] || '';
                if (yearLevelSelect && calculatedYear) {
                    yearLevelSelect.value = calculatedYear;
                }

                if (fullNameInput) {
                    const fnIndex = studentNum % mockFirstNames.length;
                    const lnIndex = (studentNum + entryYear) % mockLastNames.length;
                    fullNameInput.value = `${mockFirstNames[fnIndex]} ${mockLastNames[lnIndex]}`;
                }

                if (courseProgramSelect) {
                    const courseIndex = studentNum % courses.length;
                    courseProgramSelect.value = courses[courseIndex];
                }

                if (contactInput && !contactInput.value) {
                    const randomPhone = '09' + Math.floor(100000000 + Math.random() * 900000000);
                    contactInput.value = randomPhone;
                }

            } else {
                if (yearLevelSelect) yearLevelSelect.value = '';
                if (fullNameInput) fullNameInput.value = '';
                if (courseProgramSelect) courseProgramSelect.value = '';
            }
        });
    }

    // ==========================================
    // 2. DEPARTMENT SELECTION FILTER & SEARCH
    // ==========================================
    const deptItems = document.querySelectorAll('.dept-item');
    if (deptItems.length > 0) {
        const searchInput = document.getElementById('deptSearch');
        const filterBtns = document.querySelectorAll('.btn-filter');
        const noResults = document.getElementById('noResults');

        let currentBuildingFilter = 'all';

        function filterDepartments() {
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
            let visibleCount = 0;

            deptItems.forEach(item => {
                const building = item.dataset.building || '';
                const name = (item.dataset.name || '').toLowerCase();
                const code = (item.dataset.code || '').toLowerCase();

                const matchesBuilding = (currentBuildingFilter === 'all' || building === currentBuildingFilter);
                const matchesSearch = name.includes(query) || code.includes(query);

                if (matchesBuilding && matchesSearch) {
                    item.classList.remove('d-none');
                    visibleCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            if (noResults) {
                noResults.classList.toggle('d-none', visibleCount > 0);
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterDepartments);
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentBuildingFilter = this.dataset.filter || 'all';
                filterDepartments();
            });
        });
    }

});

// ==========================================
// 3. INQUIRY MODAL HANDLER
// ==========================================
/**
 * Global function callable via inline onclick attributes on inquiry buttons
 */
function openInquiryModal(txId, txTitle) {
    const modalTxId = document.getElementById('modalTxId');
    const modalTxTitleInput = document.getElementById('modalTxTitleInput');
    const modalTitle = document.getElementById('modalTransactionTitle');
    const detailsInput = document.getElementById('inquiryDetails');
    const instructionText = document.getElementById('modalInstructionText');
    const detailsLabel = document.getElementById('modalDetailsLabel');
    const skipBtn = document.getElementById('btnSkipProceed');

    if (!modalTxId || !modalTxTitleInput || !modalTitle) return;

    modalTxId.value = txId;
    modalTxTitleInput.value = txTitle;
    modalTitle.innerText = txTitle;

    if (detailsInput) {
        detailsInput.value = '';
    }

    // Check if Custom Inquiry was selected
    if (txTitle.includes('Custom Inquiry')) {
        if (instructionText) instructionText.innerText = 'Please describe your specific inquiry or concern below so the department can assist you.';
        if (detailsLabel) detailsLabel.innerText = 'Inquiry Details (Required)';
        if (detailsInput) {
            detailsInput.placeholder = 'Please state the reason for your visit or specific document needed...';
            detailsInput.required = true;
        }
        if (skipBtn) skipBtn.style.display = 'none'; // Hide skip option if custom inquiry
    } else {
        if (instructionText) instructionText.innerText = 'You may provide specific details or concerns about your transaction (Optional).';
        if (detailsLabel) detailsLabel.innerText = 'Notes / Purpose Details';
        if (detailsInput) {
            detailsInput.placeholder = 'e.g. Requesting Transcript of Records for Employment...';
            detailsInput.required = false;
        }
        if (skipBtn) skipBtn.style.display = 'inline-block'; // Show skip option for standard inquiries
    }

    const modalEl = document.getElementById('inquiryModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.show();
    }
}
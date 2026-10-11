/**
 * Live Queue Monitor JS
 * File: assets/js/live_queue.js
 */

document.addEventListener('DOMContentLoaded', function () {
    const baseUrl = window.BASE_URL || '';

    // 1. LIVE CLOCK AND DATE UPDATER
    function updateClock() {
        const now = new Date();
        
        const dateOptions = { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' };
        const dateStr = now.toLocaleDateString('en-US', dateOptions).toUpperCase();
        
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        const timeStr = now.toLocaleTimeString('en-US', timeOptions);

        const headerDate = document.getElementById('headerDate');
        const headerClock = document.getElementById('headerClock');

        if (headerDate) headerDate.innerText = dateStr;
        if (headerClock) headerClock.innerText = timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. FOOTER TICKER ROTATION
    const tickerItems = document.querySelectorAll('#tickerContainer .ticker-item');
    if (tickerItems.length > 1) {
        let currentTicker = 0;
        setInterval(() => {
            tickerItems[currentTicker].classList.remove('active');
            currentTicker = (currentTicker + 1) % tickerItems.length;
            tickerItems[currentTicker].classList.add('active');
        }, 6000);
    }

    // 3. FETCH AND UPDATE QUEUE DATA REAL-TIME
    let lastCalledTicket = null;

    function fetchQueueData() {
        fetch(`${baseUrl}/api/fetch_queue.php`)
            .then(res => res.json())
            .then(data => {
                if (data.status !== 'success') return;

                // A. UPDATE NOW CALLING
                const currentNumberEl = document.getElementById('currentNumber');
                const servingOfficeEl = document.getElementById('servingOffice');
                const servingBuildingEl = document.getElementById('servingBuilding');

                if (data.now_calling) {
                    if (currentNumberEl) currentNumberEl.innerText = data.now_calling.ticket_number;
                    if (servingOfficeEl) servingOfficeEl.innerText = data.now_calling.dept_name;
                    if (servingBuildingEl) servingBuildingEl.innerText = data.now_calling.transaction_title || 'Main Office';

                    // Optional: Play audio prompt if a new ticket is called
                    if (lastCalledTicket !== data.now_calling.ticket_number) {
                        lastCalledTicket = data.now_calling.ticket_number;
                        playCallChime();
                    }
                } else {
                    if (currentNumberEl) currentNumberEl.innerText = '---';
                    if (servingOfficeEl) servingOfficeEl.innerText = 'NO TICKET SERVED';
                    if (servingBuildingEl) servingBuildingEl.innerText = 'STANDBY';
                }

                // B. UPDATE WAITING QUEUE SIDEBAR
                const waitingListEl = document.getElementById('waitingList');
                const queueCountEl = document.getElementById('queueCount');

                if (queueCountEl) {
                    queueCountEl.innerText = `${data.waiting_queue.length} WAITING`;
                }

                if (waitingListEl) {
                    if (data.waiting_queue.length === 0) {
                        waitingListEl.innerHTML = `
                            <div class="text-center text-white-50 py-4 fw-semibold">
                                Queue is clear. No waiting tickets.
                            </div>`;
                    } else {
                        waitingListEl.innerHTML = data.waiting_queue.map((item, idx) => `
                            <div class="d-flex justify-content-between align-items-center p-2 mb-2 rounded bg-white bg-opacity-10 text-white">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-warning text-dark fw-bold rounded-circle">${idx + 1}</span>
                                    <div>
                                        <div class="fw-bold fs-6">${item.ticket_number}</div>
                                        <div class="small opacity-75">${item.dept_name}</div>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark text-truncate" style="max-width: 110px;">${item.transaction_title}</span>
                            </div>
                        `).join('');
                    }
                }

                // C. UPDATE OFFICE STATUS GRID USING BOOTSTRAP FLEX COLUMNS
                const officeGridEl = document.getElementById('officeGrid');
                const officeSubtitleEl = document.getElementById('officeSubtitle');

                const officesList = data.offices.length > 0 ? data.offices : window.DB_DEPARTMENTS || [];

                // Page 1: Display top 10 offices (5 rows x 2 columns)
                const currentPageOffices = officesList.slice(0, 10);
                const totalPages = Math.ceil(officesList.length / 10);

                if (officeSubtitleEl) {
                    officeSubtitleEl.innerText = `10 OFFICES (PG 1/${totalPages || 1})`;
                }

                if (officeGridEl && officesList.length > 0) {
                    const nowCallingDept = data.now_calling ? data.now_calling.dept_name : null;

                    const getOfficeIcon = (dept) => {
                        const name = (dept || '').toLowerCase();
                        if (name.includes('registrar')) return 'bi-journal-bookmark-fill text-primary';
                        if (name.includes('accounting')) return 'bi-calculator-fill text-success';
                        if (name.includes('admissions')) return 'bi-person-badge-fill text-warning';
                        if (name.includes('guidance')) return 'bi-compass-fill text-info';
                        if (name.includes('clinic')) return 'bi-heart-pulse-fill text-danger';
                        if (name.includes('library')) return 'bi-book-fill text-secondary';
                        if (name.includes('cashier')) return 'bi-cash-stack text-success';
                        if (name.includes('human') || name.includes('hr')) return 'bi-briefcase-fill text-dark';
                        if (name.includes('computer') || name.includes('it') || name.includes('mis')) return 'bi-laptop-fill text-primary';
                        return 'bi-building-fill text-primary';
                    };

                    const cardsHTML = currentPageOffices.map(off => {
                        const deptName = off.department_name || off.name || 'Office';
                        const isCalling = nowCallingDept && nowCallingDept.toLowerCase() === deptName.toLowerCase();
                        const iconClass = getOfficeIcon(deptName);
                        
                        const ticketNum = isCalling 
                            ? data.now_calling.ticket_number 
                            : (off.current_ticket || off.ticket_number || '---');

                        const statusText = isCalling ? '● NOW CALLING' : '● SERVING';

                        const cardBg = isCalling 
                            ? 'background-color: #fff9e6; border: 1.5px solid #ffe082;' 
                            : 'background-color: #ffffff; border: 1px solid #e2e8f0;';
                        
                        const statusColor = isCalling ? 'color: #d97706;' : 'color: #16a34a;';

                        return `
                            <div class="col-6">
                                <div class="card h-100 border-0 shadow-sm rounded-3 p-2 d-flex flex-column justify-content-between" style="${cardBg}">
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.72rem; line-height: 1.1;" title="${deptName}">
                                        ${deptName}
                                    </div>
                                    <div class="d-flex align-items-center gap-1 my-1">
                                        <i class="bi ${iconClass}" style="font-size: 0.75rem;"></i>
                                        <span class="fw-bold text-primary" style="font-size: 0.78rem;">
                                            ${ticketNum}
                                        </span>
                                    </div>
                                    <div class="fw-bold text-uppercase" style="font-size: 0.58rem; letter-spacing: 0.2px; ${statusColor}">
                                        ${statusText}
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');

                    officeGridEl.innerHTML = `<div class="row g-2 h-100 align-content-start">${cardsHTML}</div>`;
                }
            })
            .catch(err => console.error('Error fetching live queue:', err));
    }

    // Audio chime helper
    function playCallChime() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
            gain.gain.setValueAtTime(0.1, ctx.currentTime);
            osc.start();
            osc.stop(ctx.currentTime + 0.3);
        } catch (e) {
            // Audio context blocked until user interaction
        }
    }

    // Poll endpoint every 3 seconds
    setInterval(fetchQueueData, 3000);
    fetchQueueData();
});
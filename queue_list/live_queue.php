<?php
// queue_list/live_queue.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Live Queue Display";

$cssPath = __DIR__ . '/../assets/css/style.css';
$cssVersion = file_exists($cssPath) ? filemtime($cssPath) : time();

$baseUrl = defined('BASE_URL') ? BASE_URL : '..';
$extraCss = '<link rel="stylesheet" href="' . $baseUrl . '/assets/css/style.css?v=' . $cssVersion . '">';

require_once __DIR__ . '/../includes/header.php';
?>

<style>
  /* =====================================================
     1. DESKTOP / TV DISPLAY STYLES (992px and up)
     ===================================================== */
  @media (min-width: 992px) {
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden !important;
    }

    .portal-bg {
      height: 100vh !important;
      max-height: 100vh !important;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      position: relative;
    }

    .live-queue-wrapper {
      flex: 1 1 auto;
      min-height: 0;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .queue-main-content {
      flex: 1 1 auto;
      min-height: 0;
      overflow: hidden;
    }

    .queue-main-content .row {
      height: 100%;
      margin-right: -0.5rem;
      margin-left: -0.5rem;
    }

    .queue-main-content .col-12 {
      padding-right: 0.5rem;
      padding-left: 0.5rem;
      height: 100%;
      min-height: 0;
    }

    .hero-calling-card {
      height: 100% !important;
      max-width: 100% !important;
      min-height: 0 !important;
      overflow: hidden;
      z-index: 1;
    }

    .queue-card {
      height: 100% !important;
      min-height: 0 !important;
      overflow: hidden;
    }

    .office-status-grid-container {
      display: grid !important;
      grid-template-columns: repeat(2, 1fr);
      grid-template-rows: repeat(6, 1fr);
      gap: 0.35rem !important;
      height: 100%;
      min-height: 0;
      overflow: hidden;
    }

    .queue-footer-strip {
      height: 48px;
      min-height: 48px;
      max-height: 48px;
      z-index: 10;
      position: relative;
      flex-shrink: 0 !important;
    }
  }

  /* =====================================================
     2. MOBILE / SHRINK SCREEN STYLES (Below 992px)
     ===================================================== */
  @media (max-width: 991.98px) {
    html, body {
      height: auto !important;
      overflow-y: auto !important;
    }

    .portal-bg {
      min-height: 100vh;
      height: auto !important;
      overflow-y: auto !important;
    }

    .live-queue-wrapper {
      height: auto !important;
      min-height: 0;
      display: block;
    }

    .queue-main-content {
      overflow: visible !important;
    }

    .queue-main-content .row {
      height: auto !important;
    }

    .hero-calling-card {
      min-height: 380px !important;
      height: auto !important;
      margin-bottom: 1rem;
    }

    .queue-card {
      min-height: 350px !important;
      height: auto !important;
      margin-bottom: 1rem;
    }

    .office-status-grid-container {
      display: grid !important;
      grid-template-columns: repeat(2, 1fr);
      gap: 0.5rem !important;
      height: auto !important;
    }

    .queue-footer-strip {
      position: sticky;
      bottom: 0;
      width: 100%;
      min-height: 50px;
      z-index: 1000;
    }
  }

  /* Compact Office Card Utility Rules */
  .office-compact-card {
    min-height: 0 !important;
    padding: 0.35rem 0.5rem !important;
    overflow: hidden;
    box-sizing: border-box;
  }

  .office-compact-card .icon-circle {
    width: 26px !important;
    height: 26px !important;
    min-width: 26px !important;
    font-size: 0.75rem !important;
  }

  .office-compact-card .office-title {
    font-size: 0.65rem !important;
    line-height: 1.1 !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .office-compact-card .office-ticket {
    font-size: 0.75rem !important;
    line-height: 1.1 !important;
  }

  .office-compact-card .status-indicator {
    font-size: 0.55rem !important;
    line-height: 1 !important;
    white-space: nowrap;
  }
</style>

<div class="portal-bg">

  <!-- WRAPPER FOR HEADER AND MAIN CONTENT -->
  <div class="live-queue-wrapper">

    <!-- TOP HEADER -->
    <header class="queue-nav-header d-flex align-items-center justify-content-between px-3 px-md-4 py-2 flex-shrink-0">
      <!-- Left: Logo & Branding -->
      <div class="d-flex align-items-center gap-3">
        <a href="<?= $baseUrl; ?>/index.php" class="d-flex align-items-center gap-3 text-decoration-none text-white">
          <img src="<?= $baseUrl; ?>/assets/img/ccc-logo.png" alt="CCC Logo" class="queue-logo" onerror="this.style.display='none'">
          <div>
            <h3 class="fw-bold mb-0 text-white tracking-tight fs-5 fs-md-4">City College of Calamba</h3>
            <p class="small text-white-50 mb-0 fw-bold tracking-widest text-uppercase" style="font-size: 0.65rem;">STUDENT SERVICE QUEUE SYSTEM</p>
          </div>
        </a>
      </div>

      <!-- Center: Tagline Text -->
      <div class="d-none d-xl-flex align-items-center gap-3 text-white-50 fw-semibold tracking-wider text-uppercase" style="font-size: 0.85rem;">
        <div class="header-v-divider"></div>
        <span>LEARN &bull; GROW &bull; BUILD YOUR FUTURE</span>
        <div class="header-v-divider"></div>
      </div>

      <!-- Right: Live Date & Clock -->
      <div class="d-flex align-items-center gap-3 text-white">
        <div class="d-none d-sm-flex align-items-center gap-2 fw-bold small text-uppercase" style="font-size: 0.85rem; opacity: 0.9;">
          <i class="bi bi-calendar3"></i>
          <span id="headerDate">TUE, SEPTEMBER 15, 2026</span>
        </div>
        <div class="header-v-divider d-none d-sm-block"></div>
        <div class="d-flex align-items-center gap-2 fs-4 fs-md-3 fw-extrabold">
          <i class="bi bi-clock"></i>
          <span id="headerClock">7:39 AM</span>
        </div>
      </div>
    </header>

    <!-- MAIN DASHBOARD CONTENT -->
    <main class="queue-main-content p-2 p-md-3">
      <div class="row g-3 align-items-stretch">

        <!-- CENTER: Hero Screen (Now Calling) -> Reordered to Top on Mobile -->
        <div class="col-12 col-lg-6 order-1 order-lg-2 d-flex flex-column">
          <div class="hero-calling-card text-center text-white d-flex flex-column justify-content-between align-items-center p-4">

            <!-- Top Announcement Pill -->
            <div class="now-calling-pill rounded-pill px-4 px-md-5 py-2 text-white fw-extrabold text-uppercase d-inline-flex align-items-center gap-2 flex-shrink-0">
              <i class="bi bi-megaphone-fill"></i> NOW CALLING
            </div>

            <!-- Center Display -->
            <div class="my-auto w-100 position-relative py-3 py-md-2">
              <h1 class="hero-ticket-text mb-1" id="currentNumber">---</h1>
              <h2 class="hero-office-text mb-2" id="servingOffice">---</h2>
              <div class="divider-line mx-auto"></div>
            </div>

            <p class="proceed-notice fs-10 text-white mb-0 text-uppercase flex-shrink-0">
              Please proceed to the indicated office.
            </p>
          </div>
        </div>

        <!-- LEFT: Waiting Queue Sidebar -> Reordered to 2nd on Mobile -->
        <div class="col-12 col-lg-3 order-2 order-lg-1 d-flex flex-column">
          <div class="queue-card d-flex flex-column">
            <div class="card-blue-header d-flex justify-content-between align-items-center flex-shrink-0">
              <div class="d-flex align-items-center gap-2 fw-bold text-uppercase fs-6">
                <i class="bi bi-people-fill"></i> Waiting Queue
              </div>
              <span class="badge bg-white text-primary rounded-pill px-3 py-1 fw-bold fs-7" id="queueCount">0 WAITING</span>
            </div>

            <div class="waiting-list-scroll p-3 flex-grow-1 overflow-auto" id="waitingList">
              <!-- Dynamic items generated by JS -->
            </div>
          </div>
        </div>

        <!-- RIGHT: Office Status Grid -> Reordered to 3rd on Mobile -->
        <div class="col-12 col-lg-3 order-3 order-lg-3 d-flex flex-column">
          <div class="queue-card d-flex flex-column p-2">
            <div class="card-blue-header d-flex justify-content-between align-items-center mb-2 p-2 flex-shrink-0">
              <div class="d-flex align-items-center gap-2 fw-bold text-uppercase fs-6">
                <i class="bi bi-building-fill"></i> Office Status
              </div>
              <span class="badge bg-white text-primary rounded-pill px-2 py-1 fw-bold" style="font-size: 0.65rem;" id="officeSubtitle">12 OFFICES</span>
            </div>

            <div class="office-status-grid-container flex-grow-1" id="officeGrid">
              <!-- Dynamic 2-column office cards generated by JS -->
            </div>
          </div>
        </div>

      </div>
    </main>

  </div> <!-- END .live-queue-wrapper -->

  <!-- FOOTER STRIP AT ROOT LEVEL -->
  <footer class="queue-footer-strip d-flex align-items-center justify-content-between px-3 px-md-4 text-white">
    <!-- Background Campus Image Container -->
    <div class="footer-bg-image" style="background-image: url('<?= $baseUrl; ?>/assets/img/queue_footer.webp');"></div>
    <div class="footer-bg-overlay"></div>

    <!-- Left: Announcements Label -->
    <div class="d-flex align-items-center gap-2 fw-bold text-uppercase fs-7 text-warning pe-3 border-end border-secondary position-relative z-1" style="white-space: nowrap;">
      <i class="bi bi-megaphone-fill"></i> ANNOUNCEMENTS
    </div>

    <!-- Middle: Ticker Items -->
    <div class="overflow-hidden flex-grow-1 mx-3 position-relative z-1" style="height: 24px;" id="tickerContainer">
      <div class="ticker-item active d-flex align-items-center gap-2 fw-medium small text-white text-truncate">
        <i class="bi bi-info-circle text-info"></i>
        <span>Office hours are strictly from 7:00 AM to 6:00 PM, Monday to Thursday only. Ticket issuance automatically closes at 5:30 PM, and late requests will be rejected.</span>
      </div>
      <div class="ticker-item d-flex align-items-center gap-2 fw-medium small text-white text-truncate">
        <i class="bi bi-calendar-event text-warning"></i>
        <span>Skipped or missed queue numbers will be given one final call at the end of the batch queue. Unclaimed numbers will be automatically marked as cancelled.</span>
      </div>
      <div class="ticker-item d-flex align-items-center gap-2 fw-medium small text-white text-truncate">
        <i class="bi bi-card-checklist text-success"></i>
        <span>Please ensure all required documents, valid IDs, and official forms are prepared prior to approaching your designated transaction window.</span>
      </div>
    </div>

    <!-- Right: Voice Status Indicator -->
    <div class="d-none d-md-flex align-items-center gap-2 fw-bold small text-success ps-3 border-start border-secondary position-relative z-1" style="white-space: nowrap; font-size: 0.75rem;">
      <i class="bi bi-broadcast"></i> Voice Announcements ON
    </div>
  </footer>

</div>

<script>
  /* =====================================================
     DATA CONFIGURATION (12 Offices Total)
     ===================================================== */
  const queueData = [
    { number: "R-024", office: "Registrar Office" },
    { number: "AC-015", office: "Accounting Office" },
    { number: "AD-008", office: "Admissions Office" },
    { number: "G-011", office: "Guidance Office" },
    { number: "L-012", office: "Library" },
    { number: "C-006", office: "School Clinic" },
    { number: "CA-018", office: "Cashier" },
    { number: "HR-004", office: "Human Resources" },
    { number: "IT-009", office: "IT Center" },
    { number: "SA-021", office: "Student Affairs" },
    { number: "VP-003", office: "VPAA Office" },
    { number: "OS-001", office: "OIC Dean Office" }
  ];

  const officeStatus = [
    { office: "Registrar Office", queue: "R-024", status: "SERVING", icon: "bi-file-earmark-text-fill", color: "text-primary bg-primary-subtle" },
    { office: "Accounting Office", queue: "AC-015", status: "SERVING", icon: "bi-calculator-fill", color: "text-success bg-success-subtle" },
    { office: "Admissions Office", queue: "AD-008", status: "SERVING", icon: "bi-mortarboard-fill", color: "text-warning bg-warning-subtle" },
    { office: "Guidance Office", queue: "G-011", status: "SERVING", icon: "bi-people-fill", color: "text-info bg-info-subtle" },
    { office: "School Clinic", queue: "C-006", status: "SERVING", icon: "bi-hospital-fill", color: "text-danger bg-danger-subtle" },
    { office: "Library", queue: "L-012", status: "SERVING", icon: "bi-book-fill", color: "text-secondary bg-secondary-subtle" },
    { office: "Cashier", queue: "CA-018", status: "SERVING", icon: "bi-cash-coin", color: "text-success bg-success-subtle" },
    { office: "Human Resources", queue: "HR-004", status: "SERVING", icon: "bi-briefcase-fill", color: "text-dark bg-light-subtle" },
    { office: "IT Center", queue: "IT-009", status: "SERVING", icon: "bi-laptop-fill", color: "text-primary bg-primary-subtle" },
    { office: "Student Affairs", queue: "SA-021", status: "SERVING", icon: "bi-person-badge-fill", color: "text-info bg-info-subtle" },
    { office: "VPAA Office", queue: "VP-003", status: "SERVING", icon: "bi-award-fill", color: "text-warning bg-warning-subtle" },
    { office: "OIC Dean Office", queue: "OS-001", status: "SERVING", icon: "bi-building-gear", color: "text-danger bg-danger-subtle" }
  ];

  let currentIndex = 0;

  /* =====================================================
     VOICE ANNOUNCEMENT LOGIC
     ===================================================== */
  let singleFemaleVoice = null;

  function getBestFemaleVoice() {
    if (!('speechSynthesis' in window)) return null;
    const voices = window.speechSynthesis.getVoices();
    if (!voices || voices.length === 0) return null;

    const femaleNames = ["zira", "janny", "jenny", "samantha", "siri", "victoria", "karen", "aria", "ava", "emma", "hazel", "susan", "female", "google us english"];
    const maleNames = ["david", "mark", "george", "james", "richard", "male", "guy", "stefan"];

    let selected = voices.find(v => v.lang.startsWith('en') && femaleNames.some(fn => v.name.toLowerCase().includes(fn)));
    if (!selected) {
      selected = voices.find(v => v.lang.startsWith('en') && !maleNames.some(mn => v.name.toLowerCase().includes(mn)));
    }
    return selected || voices.find(v => v.lang.startsWith('en')) || voices[0];
  }

  function loadVoices() { singleFemaleVoice = getBestFemaleVoice(); }
  if ('speechSynthesis' in window) {
    window.speechSynthesis.onvoiceschanged = loadVoices;
    loadVoices();
  }

  function playBellDing() {
    try {
      const AudioContext = window.AudioContext || window.webkitAudioContext;
      if (!AudioContext) return;
      const ctx = new AudioContext();

      const osc1 = ctx.createOscillator();
      const gain1 = ctx.createGain();
      osc1.type = 'sine';
      osc1.frequency.setValueAtTime(659.25, ctx.currentTime);
      gain1.gain.setValueAtTime(0.3, ctx.currentTime);
      gain1.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 1.2);

      osc1.connect(gain1);
      gain1.connect(ctx.destination);
      osc1.start(ctx.currentTime);
      osc1.stop(ctx.currentTime + 1.2);

      const osc2 = ctx.createOscillator();
      const gain2 = ctx.createGain();
      osc2.type = 'sine';
      osc2.frequency.setValueAtTime(783.99, ctx.currentTime + 0.25);
      gain2.gain.setValueAtTime(0.35, ctx.currentTime + 0.25);
      gain2.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 1.6);

      osc2.connect(gain2);
      gain2.connect(ctx.destination);
      osc2.start(ctx.currentTime + 0.25);
      osc2.stop(ctx.currentTime + 1.6);
    } catch (e) {
      console.warn("Audio Context blocked until page interaction.", e);
    }
  }

  function speakNowCalling(ticketNumber, officeName) {
    if (!('speechSynthesis' in window)) return;
    window.speechSynthesis.cancel();
    if (!singleFemaleVoice) loadVoices();

    const spokenTicket = ticketNumber.split('').map(char => (char === '-' ? ' ' : char)).join(', ');
    const utterance = new SpeechSynthesisUtterance(`Now Calling, ${spokenTicket}, please proceed to ${officeName}.`);
    utterance.rate = 0.88;
    utterance.pitch = 1.1;
    utterance.lang = 'en-US';
    if (singleFemaleVoice) utterance.voice = singleFemaleVoice;

    setTimeout(() => window.speechSynthesis.speak(utterance), 500);
  }

  /* =====================================================
     DOM UPDATES
     ===================================================== */
  function updateCallingQueue() {
    if (!Array.isArray(queueData) || queueData.length === 0 || !queueData[currentIndex]) {
      const currentNumberEl = document.getElementById("currentNumber");
      const servingOfficeEl = document.getElementById("servingOffice");
      const queueCountEl = document.getElementById("queueCount");

      if (currentNumberEl) currentNumberEl.textContent = "---";
      if (servingOfficeEl) servingOfficeEl.textContent = "NO ACTIVE CALLS";
      if (queueCountEl) queueCountEl.textContent = "0 WAITING";
      return;
    }

    const current = queueData[currentIndex];

    const currentNumberEl = document.getElementById("currentNumber");
    const servingOfficeEl = document.getElementById("servingOffice");

    if (currentNumberEl) currentNumberEl.textContent = current.number || "---";
    if (servingOfficeEl) servingOfficeEl.textContent = current.office || "---";

    if (typeof playBellDing === "function") playBellDing();
    if (typeof speakNowCalling === "function") {
      speakNowCalling(current.number, current.office);
    }

    const waitingList = document.getElementById("waitingList");
    if (waitingList) {
      waitingList.innerHTML = "";
      const fragment = document.createDocumentFragment();

      for (let i = 1; i < queueData.length; i++) {
        const index = (currentIndex + i) % queueData.length;
        const queue = queueData[index];

        const item = document.createElement("div");
        item.className = "waiting-item d-flex justify-content-between align-items-center p-2 mb-2 rounded-3";
        item.innerHTML = `
        <span class="token-pill">${queue.number || "---"}</span>
        <span class="fw-bold text-dark small ms-2 text-truncate">${queue.office || "---"}</span>
      `;
        fragment.appendChild(item);
      }
      waitingList.appendChild(fragment);
    }

    const queueCountEl = document.getElementById("queueCount");
    if (queueCountEl) {
      queueCountEl.textContent = `${Math.max(0, queueData.length - 1)} WAITING`;
    }

    if (typeof updateOfficeStatus === "function") {
      updateOfficeStatus();
    }
  }

  function updateOfficeStatus() {
    const grid = document.getElementById("officeGrid");
    grid.innerHTML = "";

    const totalOffices = officeStatus.length;
    const currentTicket = queueData[currentIndex] ? queueData[currentIndex].number : "";

    for (let i = 0; i < totalOffices; i++) {
      const office = officeStatus[i];
      const isNowCalling = office.queue === currentTicket;

      const displayStatus = isNowCalling ? "NOW CALLING" : office.status;
      const badgeColor = isNowCalling ? "text-warning" : "text-success";

      const card = document.createElement("div");
      card.className = `office-status-card office-compact-card w-100 d-flex align-items-center gap-1 rounded-2 border ${isNowCalling ? 'active-calling border-warning bg-warning-subtle' : 'bg-white'}`;

      card.innerHTML = `
        <div class="icon-circle rounded-circle ${office.color} d-flex align-items-center justify-content-center flex-shrink-0">
          <i class="bi ${office.icon || 'bi-building'}"></i>
        </div>
        <div class="overflow-hidden flex-grow-1">
          <div class="fw-bold text-dark office-title">${office.office}</div>
          <div class="fw-extrabold text-primary office-ticket">${office.queue}</div>
          <span class="status-indicator ${badgeColor} d-block">
            <i class="bi bi-circle-fill me-1" style="font-size:0.35rem;"></i>${displayStatus}
          </span>
        </div>
      `;

      grid.appendChild(card);
    }

    document.getElementById("officeSubtitle").textContent = `${totalOffices} OFFICES`;
  }

  function updateClock() {
    const now = new Date();
    let hours = now.getHours();
    let minutes = now.getMinutes();
    const ampm = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12;
    minutes = minutes.toString().padStart(2, "0");

    document.getElementById("headerClock").textContent = `${hours}:${minutes} ${ampm}`;

    const options = { weekday: "short", month: "long", day: "numeric", year: "numeric" };
    document.getElementById("headerDate").textContent = now.toLocaleDateString("en-US", options).toUpperCase();
  }

  /* =====================================================
     INIT
     ===================================================== */
  updateClock();
  setInterval(updateClock, 1000);

  setTimeout(() => {
    updateCallingQueue();
    setInterval(() => {
      currentIndex = (currentIndex + 1) % queueData.length;
      updateCallingQueue();
    }, 10000);
  }, 300);

  function initTickerCycle() {
    const items = document.querySelectorAll('#tickerContainer .ticker-item');
    if (items.length <= 1) return;

    let activeIndex = 0;

    setInterval(() => {
      const currentItem = items[activeIndex];
      
      currentItem.classList.remove('active');
      currentItem.classList.add('exit');

      activeIndex = (activeIndex + 1) % items.length;
      const nextItem = items[activeIndex];

      nextItem.classList.remove('exit');
      nextItem.classList.add('active');

      setTimeout(() => {
        currentItem.classList.remove('exit');
      }, 500);

    }, 6000);
  }

  document.addEventListener('DOMContentLoaded', initTickerCycle);
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
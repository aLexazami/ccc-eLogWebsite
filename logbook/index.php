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

<div class="tv-queue-wrapper">

    <!-- TOP BAR: Single-Row Layout -->
    <header class="tv-header d-flex justify-content-between align-items-center">
        <div class="tv-header-left">
            <a href="<?= $baseUrl; ?>/index.php" class="btn btn-back text-decoration-none">
                <span class="btn-back-icon">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                </span>
                <span>BACK TO MAIN</span>
            </a>
        </div>

        <div class="tv-header-center text-center">
            <h1 class="tv-title mb-0">LIVE QUEUE DISPLAY</h1>
        </div>

        <div class="tv-header-right">
            <div class="tv-clock-box text-end">
                <div id="headerClock" class="tv-clock-time">12:00:00 AM</div>
                <div id="headerDate" class="tv-clock-date">Wednesday, September 9, 2026</div>
            </div>
        </div>
    </header>

    <!-- MIDDLE ROW: Up Next List (Left) + Big Hero Screen (Right) -->
    <div class="row g-3 tv-middle-row my-1">
        
        <!-- LEFT: Up Next Queue -->
        <div class="col-12 col-lg-4 col-xl-3 h-100">
            <div class="tv-card waiting-card p-3 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-shrink-0">
                    <h5 class="fw-bold mb-0 text-dark-slate">
                        <i class="bi bi-people-fill me-2 text-royal-blue"></i>Up Next
                    </h5>
                    <span class="badge badge-pill-custom" id="queueCount">0 Waiting</span>
                </div>

                <div class="waiting-list-scroll flex-grow-1" id="waitingList">
                    <!-- Populated dynamically by JS -->
                </div>
            </div>
        </div>

        <!-- RIGHT: Hero Now Calling -->
        <div class="col-12 col-lg-8 col-xl-9 h-100">
            <div class="tv-hero-card h-100 d-flex flex-column justify-content-center align-items-center p-3 text-center">
                <div class="now-calling-badge mb-1">
                    <i class="bi bi-megaphone-fill me-2"></i>NOW CALLING
                </div>
                
                <div class="now-calling-number" id="currentNumber">---</div>
                
                <div class="now-calling-destination text-center">
                    <span class="destination-label">PLEASE PROCEED TO</span>
                    <h2 class="destination-office mb-0" id="servingOffice">---</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- BOTTOM ROW: Office Queue Status Matrix -->
    <section class="tv-card status-matrix-card p-2 flex-shrink-0">
        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <h6 class="fw-bold mb-0 text-dark-slate">Office Queue Status</h6>
                <span class="badge bg-light text-secondary border fw-normal" id="officeSubtitle">0 offices active</span>
            </div>
            <small class="text-muted-custom fw-semibold" id="officePage">Showing Offices 0–0 of 0</small>
        </div>

        <div class="row g-2" id="officeGrid">
            <!-- Populated dynamically by JS -->
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="tv-footer text-center flex-shrink-0">
        <small class="text-muted-custom">
            Copyright &copy; <?= date('Y'); ?> City College of Calamba. All Rights Reserved.
        </small>
    </footer>
</div>

<script>
/* =====================================================
   DATA CONFIGURATION
   ===================================================== */
const queueData = [
  { number: "R-024", office: "Registrar Office" },
  { number: "AC-015", office: "Accounting Office" },
  { number: "AD-008", office: "Admissions Office" },
  { number: "G-011", office: "Guidance Office" },
  { number: "L-012", office: "Library" },
  { number: "C-006", office: "School Clinic" },
  { number: "CA-018", office: "Cashier" },
  { number: "HR-004", office: "Human Resources" }
];

const officeStatus = [
  { office: "Registrar Office", queue: "R-024", status: "SERVING" },
  { office: "Accounting Office", queue: "AC-015", status: "SERVING" },
  { office: "Admissions Office", queue: "AD-008", status: "SERVING" },
  { office: "Guidance Office", queue: "G-011", status: "SERVING" },
  { office: "Library", queue: "L-012", status: "SERVING" },
  { office: "School Clinic", queue: "C-006", status: "SERVING" },
  { office: "Cashier", queue: "CA-018", status: "SERVING" },
  { office: "Human Resources", queue: "HR-004", status: "SERVING" },
  { office: "Student Affairs", queue: "SA-009", status: "SERVING" },
  { office: "Scholarship Office", queue: "S-005", status: "SERVING" }
];

let currentIndex = 0;
const officesPerPage = 10;
let officePageIndex = 0;

/* =====================================================
   VOICE & AUDIO SYNTHESIS
   ===================================================== */
let singleFemaleVoice = null;

function getBestFemaleVoice() {
  if (!('speechSynthesis' in window)) return null;
  const voices = window.speechSynthesis.getVoices();
  if (!voices || voices.length === 0) return null;

  const femaleNames = ["zira", "janny", "jenny", "samantha", "siri", "victoria", "karen", "aria", "ava", "emma", "hazel", "susan", "female", "google us english"];
  
  return voices.find(v => v.lang.startsWith('en') && femaleNames.some(fn => v.name.toLowerCase().includes(fn))) ||
         voices.find(v => v.lang.startsWith('en')) || 
         voices[0];
}

function loadVoices() {
  singleFemaleVoice = getBestFemaleVoice();
}

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
  } catch(e) {
    console.warn("Audio Context blocked until interaction.", e);
  }
}

function speakNowCalling(ticketNumber, officeName) {
  if (!('speechSynthesis' in window)) return;
  window.speechSynthesis.cancel();
  if (!singleFemaleVoice) loadVoices();

  const spokenTicket = ticketNumber.split('').map(char => (char === '-' ? ' ' : char)).join(', ');
  const announcement = `Now Calling, ${spokenTicket}, please proceed to ${officeName}.`;

  const utterance = new SpeechSynthesisUtterance(announcement);
  utterance.rate = 0.88;
  utterance.pitch = 1.1;
  utterance.lang = 'en-US';

  if (singleFemaleVoice) utterance.voice = singleFemaleVoice;
  setTimeout(() => window.speechSynthesis.speak(utterance), 500);
}

/* =====================================================
   DOM UPDATES & LOGIC
   ===================================================== */
function updateCallingQueue() {
  const current = queueData[currentIndex];

  document.getElementById("currentNumber").textContent = current.number;
  document.getElementById("servingOffice").textContent = current.office;

  playBellDing();
  speakNowCalling(current.number, current.office);

  // Render Waiting Queue
  const waitingList = document.getElementById("waitingList");
  waitingList.innerHTML = "";

  for (let i = 1; i < queueData.length; i++) {
    const index = (currentIndex + i) % queueData.length;
    const queue = queueData[index];

    const item = document.createElement("div");
    item.className = "waiting-item d-flex justify-content-between align-items-center p-2 mb-2 rounded-3";
    item.innerHTML = `
      <span class="queue-num fw-bold">${queue.number}</span>
      <span class="office-name text-secondary fw-semibold small text-truncate ms-2">${queue.office}</span>
    `;
    waitingList.appendChild(item);
  }

  document.getElementById("queueCount").textContent = `${queueData.length - 1} Waiting`;
  updateOfficeStatus();
}

function updateOfficeStatus() {
  const grid = document.getElementById("officeGrid");
  grid.innerHTML = "";

  const totalOffices = officeStatus.length;
  const totalPages = Math.ceil(totalOffices / officesPerPage);
  if (officePageIndex >= totalPages) officePageIndex = 0;

  const start = officePageIndex * officesPerPage;
  const end = Math.min(start + officesPerPage, totalOffices);
  const currentTicket = queueData[currentIndex].number;

  for (let i = start; i < end; i++) {
    const office = officeStatus[i];
    const isNowCalling = office.queue === currentTicket;

    const col = document.createElement("div");
    col.className = "col-6 col-md-4 col-lg-2-4";

    const displayStatus = isNowCalling ? "NOW CALLING" : office.status;
    const activeCallingClass = isNowCalling ? " active-calling" : "";

    col.innerHTML = `
      <div class="tv-status-box p-2 text-center ${activeCallingClass}">
        <div class="dept-title text-truncate fw-semibold mb-1">${office.office}</div>
        <div class="ticket-no fw-bold mb-1">${office.queue}</div>
        <div class="status-indicator-badge d-inline-block px-2 py-0 rounded-pill">
          <span class="dot me-1"></span>${displayStatus}
        </div>
      </div>
    `;

    grid.appendChild(col);
  }

  document.getElementById("officePage").textContent = `Showing Offices ${start + 1}–${end} of ${totalOffices}`;
  const servingCount = officeStatus.filter((office) => office.status === "SERVING").length;
  document.getElementById("officeSubtitle").textContent = `${servingCount} offices serving`;
}

function updateClock() {
  const now = new Date();
  let hours = now.getHours();
  let minutes = now.getMinutes();
  const ampm = hours >= 12 ? "PM" : "AM";
  hours = hours % 12 || 12;
  minutes = minutes.toString().padStart(2, "0");

  document.getElementById("headerClock").textContent = `${hours}:${minutes} ${ampm}`;
  document.getElementById("headerDate").textContent = now.toLocaleDateString("en-US", { 
    weekday: "long", year: "numeric", month: "long", day: "numeric" 
  });
}

/* Initialize */
updateClock();
setInterval(updateClock, 1000);

setTimeout(() => {
  updateCallingQueue();
  setInterval(() => {
    currentIndex = (currentIndex + 1) % queueData.length;
    updateCallingQueue();
  }, 10000);
}, 300);
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
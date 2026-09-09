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

<div class="main-wrapper">

    <!-- TOP HEADER: Back Button, Title, and Clock in One Single Row -->
    <div class="top-header-row d-flex justify-content-between align-items-center mb-1">
        <!-- Left: Back Button -->
        <div class="header-left">
            <a href="<?= $baseUrl; ?>/index.php" class="btn-back">
                <span class="btn-back-icon"><i class="bi bi-arrow-left"></i></span>
                <span class="btn-back-label">Back to Main</span>
            </a>
        </div>

        <!-- Center: App Title -->
        <div class="header-center text-center">
            <h2 class="app-title mb-0">Live Queue Display</h2>
        </div>

        <!-- Right: Scoped Live Queue Clock Widget -->
        <div class="header-right">
            <div class="queue-clock-card text-center">
                <div class="d-flex justify-content-center align-items-baseline gap-1">
                    <span id="headerClock" class="queue-clock-time">12:00:00 AM</span>
                </div>
                <div id="headerDate" class="queue-clock-date">Wednesday, September 9, 2026</div>
            </div>
        </div>
    </div>

    <!-- MAIN DASHBOARD MIDDLE -->
    <div class="row g-0 top-dashboard-row my-1">
        
        <!-- LEFT: Waiting Queue Sidebar -->
        <div class="col-12 col-lg-3 h-100">
            <div class="waiting-card p-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Waiting Queue</h5>
                    <span class="badge badge-pill-custom rounded-pill px-2 py-1" id="queueCount">0 Waiting</span>
                </div>

                <div class="waiting-list-scroll pe-1" id="waitingList">
                    <!-- Populated dynamically by JS -->
                </div>
            </div>
        </div>

        <!-- RIGHT: Hero Screen -->
        <div class="col-12 col-lg-9 h-100">
            <div class="now-calling-card text-center d-flex flex-column justify-content-center align-items-center">
                <div class="now-calling-title text-uppercase mb-1">NOW CALLING</div>
                <div class="now-calling-number my-1" id="currentNumber">---</div>
                <div class="now-calling-subtext text-uppercase mt-1">CURRENTLY BEING CALLED AT</div>
                <div class="now-calling-office mb-1" id="servingOffice">---</div>
                <small class="text-white-50" style="font-size: 0.7rem;">Please proceed to the indicated office.</small>
            </div>
        </div>

    </div>

    <!-- BOTTOM SECTION: Office Status Matrix -->
    <div class="status-matrix-card">
        <div class="text-center mb-1">
            <h5 class="fw-bold mb-0">Current Office Queue Status</h5>
            <small class="text-white-50" id="officeSubtitle" style="font-size: 0.65rem;">0 offices currently serving</small>
        </div>

        <div class="row g-1" id="officeGrid">
            <!-- Populated dynamically by JS -->
        </div>

        <div class="text-center mt-1">
            <small class="text-white-50" id="officePage" style="font-size: 0.6rem;">Showing Offices 0–0 of 0</small>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="text-center">
        <small class="text-white-50">
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
  { office: "Scholarship Office", queue: "S-005", status: "SERVING" },
  { office: "IT Services", queue: "IT-013", status: "SERVING" },
  { office: "Registrar Extension", queue: "RE-006", status: "SERVING" },
  { office: "Cashier Extension", queue: "CE-011", status: "SERVING" },
  { office: "Records Office", queue: "RO-007", status: "SERVING" },
  { office: "Testing Center", queue: "TC-003", status: "SERVING" },
  { office: "Student Development", queue: "SD-014", status: "SERVING" },
  { office: "Research Office", queue: "RS-008", status: "SERVING" },
  { office: "Finance Office", queue: "F-019", status: "SERVING" },
  { office: "Procurement", queue: "P-006", status: "SERVING" },
  { office: "Human Capital", queue: "HC-004", status: "SERVING" },
  { office: "Alumni Office", queue: "AL-010", status: "SERVING" },
  { office: "Registrar Window 2", queue: "R2-017", status: "SERVING" },
  { office: "Accounting Window 2", queue: "A2-008", status: "SERVING" },
  { office: "Admissions Window 2", queue: "AD2-005", status: "SERVING" },
  { office: "General Services", queue: "GS-012", status: "SERVING" }
];

let currentIndex = 0;
const officesPerPage = 10;
let officePageIndex = 0;

const ROTATION_INTERVAL = 10000;
const NOW_CALLING_HOLD_TIME = 10000;
let pageRotationTimeout = null;
let pauseRotationUntil = 0;

/* =====================================================
   STRICT FEMALE VOICE INITIALIZATION
   ===================================================== */
let singleFemaleVoice = null;

function getBestFemaleVoice() {
  if (!('speechSynthesis' in window)) return null;

  const voices = window.speechSynthesis.getVoices();
  if (!voices || voices.length === 0) return null;

  const femaleNames = [
    "zira", "janny", "jenny", "samantha", "siri", "victoria", 
    "karen", "aria", "ava", "emma", "hazel", "susan", "female", "google us english"
  ];
  const maleNames = ["david", "mark", "george", "james", "richard", "male", "guy", "stefan"];

  let selected = voices.find(v => {
    const name = v.name.toLowerCase();
    return v.lang.startsWith('en') && femaleNames.some(fn => name.includes(fn));
  });

  if (!selected) {
    selected = voices.find(v => {
      const name = v.name.toLowerCase();
      return v.lang.startsWith('en') && !maleNames.some(mn => name.includes(mn));
    });
  }

  if (!selected) {
    selected = voices.find(v => v.lang.startsWith('en')) || voices[0];
  }

  return selected;
}

function loadVoices() {
  singleFemaleVoice = getBestFemaleVoice();
}

if ('speechSynthesis' in window) {
  window.speechSynthesis.onvoiceschanged = loadVoices;
  loadVoices();
}

/* =====================================================
   BELL CHIME AUDIO SYNTHESIS
   ===================================================== */
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
  } catch(e) {
    console.warn("Audio Context blocked until page interaction.", e);
  }
}

/* =====================================================
   FEMALE VOICE ANNOUNCEMENT
   ===================================================== */
function speakNowCalling(ticketNumber, officeName) {
  if (!('speechSynthesis' in window)) return;

  window.speechSynthesis.cancel();

  if (!singleFemaleVoice) {
    loadVoices();
  }

  const spokenTicket = ticketNumber
    .split('')
    .map(char => (char === '-' ? ' ' : char))
    .join(', ');

  const announcement = `Number ${spokenTicket}, please proceed to ${officeName}.`;

  const utterance = new SpeechSynthesisUtterance(announcement);
  utterance.rate = 0.88;
  utterance.pitch = 1.1;
  utterance.lang = 'en-US';

  if (singleFemaleVoice) {
    utterance.voice = singleFemaleVoice;
  }

  setTimeout(() => {
    window.speechSynthesis.speak(utterance);
  }, 500);
}

/* =====================================================
   CORE LOGIC FUNCTIONS
   ===================================================== */
function getNowCallingOfficeIndex() {
  const currentTicket = queueData[currentIndex].number;
  return officeStatus.findIndex((office) => office.queue === currentTicket);
}

function getNowCallingPage() {
  const officeIndex = getNowCallingOfficeIndex();
  if (officeIndex === -1) return null;
  return Math.floor(officeIndex / officesPerPage);
}

function showNowCallingOfficePage() {
  const nowCallingPage = getNowCallingPage();
  if (nowCallingPage !== null) {
    officePageIndex = nowCallingPage;
    pauseRotationUntil = Date.now() + NOW_CALLING_HOLD_TIME;
  }
}

function updateCallingQueue() {
  const current = queueData[currentIndex];

  const numEl = document.getElementById("currentNumber");
  const officeEl = document.getElementById("servingOffice");

  numEl.textContent = current.number;
  officeEl.textContent = current.office;

  playBellDing();
  speakNowCalling(current.number, current.office);

  // Render Waiting List Sidebar
  const waitingList = document.getElementById("waitingList");
  waitingList.innerHTML = "";

  for (let i = 1; i < queueData.length; i++) {
    const index = (currentIndex + i) % queueData.length;
    const queue = queueData[index];

    const item = document.createElement("div");
    item.className = "waiting-item d-flex justify-content-between align-items-center";
    item.innerHTML = `
      <span class="queue-num ps-1">${queue.number}</span>
      <span class="office-name pe-1">${queue.office}</span>
    `;
    waitingList.appendChild(item);
  }

  document.getElementById("queueCount").textContent = `${queueData.length - 1} Waiting`;

  showNowCallingOfficePage();
  updateOfficeStatus();
}

function updateOfficeStatus() {
  const grid = document.getElementById("officeGrid");
  grid.innerHTML = "";

  const totalOffices = officeStatus.length;
  const totalPages = Math.ceil(totalOffices / officesPerPage);

  if (officePageIndex >= totalPages) {
    officePageIndex = 0;
  }

  const start = officePageIndex * officesPerPage;
  const end = Math.min(start + officesPerPage, totalOffices);
  const currentTicket = queueData[currentIndex].number;

  for (let i = start; i < end; i++) {
    const office = officeStatus[i];
    const isNowCalling = office.queue === currentTicket;

    const col = document.createElement("div");
    col.className = "col-6 col-md-4 col-lg-2-4";

    const displayStatus = isNowCalling ? "NOW CALLING" : office.status;
    const badgeClass = isNowCalling ? "now-calling" : "serving";
    const activeCallingClass = isNowCalling ? " active-calling" : "";

    col.innerHTML = `
      <div class="status-box text-center${activeCallingClass}">
        <div class="dept-title text-truncate">${office.office}</div>
        <div class="ticket-no">${office.queue}</div>
        <div class="status-badge ${badgeClass}">● ${displayStatus}</div>
      </div>
    `;

    grid.appendChild(col);
  }

  document.getElementById("officePage").textContent = `Showing Offices ${start + 1}–${end} of ${totalOffices}`;

  const servingCount = officeStatus.filter((office) => office.status === "SERVING").length;
  document.getElementById("officeSubtitle").textContent = `${servingCount} offices currently serving`;
}

function scheduleNextPageRotation() {
  if (pageRotationTimeout) clearTimeout(pageRotationTimeout);

  const now = Date.now();
  const delay = Math.max(ROTATION_INTERVAL, pauseRotationUntil - now);

  pageRotationTimeout = setTimeout(() => {
    if (Date.now() >= pauseRotationUntil) {
      const totalPages = Math.ceil(officeStatus.length / officesPerPage);
      officePageIndex = (officePageIndex + 1) % totalPages;
      updateOfficeStatus();
    }
    scheduleNextPageRotation();
  }, delay);
}

function updateClock() {
  const now = new Date();
  let hours = now.getHours();
  let minutes = now.getMinutes();
  const ampm = hours >= 12 ? "PM" : "AM";

  hours = hours % 12 || 12;
  minutes = minutes.toString().padStart(2, "0");

  document.getElementById("headerClock").textContent = `${hours}:${minutes} ${ampm}`;

  const options = { weekday: "long", year: "numeric", month: "long", day: "numeric" };
  document.getElementById("headerDate").textContent = now.toLocaleDateString("en-US", options);
}

/* =====================================================
   INITIALIZATION & TIMERS
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

scheduleNextPageRotation();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
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
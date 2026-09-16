<?php
// queue_list/live_queue.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$pageTitle = "Live Queue Display";

$styleCssPath = __DIR__ . '/../../assets/css/style.css';
$styleCssVersion = file_exists($styleCssPath)
  ? filemtime($styleCssPath)
  : time();

$liveQueueCssPath = __DIR__ . '/../../assets/css/live_queue.css';
$liveQueueCssVersion = file_exists($liveQueueCssPath)
  ? filemtime($liveQueueCssPath)
  : time();

$baseUrl = defined('BASE_URL') ? BASE_URL : '';

$extraCss = '<link rel="stylesheet" href="' . $baseUrl . '/assets/css/style.css?v=' . $styleCssVersion . '">';
$extraCss .= '<link rel="stylesheet" href="' . $baseUrl . '/assets/css/live_queue.css?v=' . $liveQueueCssVersion . '">';

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="portal-bg">

  <!-- WRAPPER FOR HEADER AND MAIN CONTENT -->
  <div class="live-queue-wrapper">

    <!-- TOP HEADER -->
<header class="queue-nav-header px-3 px-md-4 py-2 flex-shrink-0">
  <div class="row align-items-center g-0 w-100">

    <!-- Left (col-lg-4): Logo & Branding -->
    <div class="col-12 col-lg-4 d-flex align-items-center justify-content-start">
      <a href="<?= $baseUrl; ?>/index.php" class="d-flex align-items-center gap-3 text-decoration-none text-white">
        <img src="<?= $baseUrl; ?>/assets/img/ccc-logo.webp" alt="CCC Logo" class="queue-logo"
          onerror="this.style.display='none'">
        <div>
          <h3 class="fw-bold mb-0 text-white tracking-tight fs-5 fs-md-4">City College of Calamba</h3>
          <p class="small text-white-50 mb-0 fw-bold tracking-widest text-uppercase" style="font-size: 0.65rem;">
            STUDENT SERVICE QUEUE SYSTEM</p>
        </div>
      </a>
    </div>

    <!-- Center (col-lg-4): Spacer for Desktop -->
    <div class="col-12 col-lg-4 d-none d-lg-block"></div>

    <!-- Right (col-lg-4): Date & Clock (Identical 30% Smaller Size) -->
    <div class="col-12 col-lg-4 d-flex align-items-center justify-content-start justify-content-lg-end gap-3 text-white header-right-container mt-2 mt-lg-0">
      <div class="d-flex align-items-center gap-2 header-date-text fw-bold text-uppercase flex-shrink-0">
        <i class="bi bi-calendar3 fs-5"></i>
        <span id="headerDate">TUE, SEPTEMBER 15, 2026</span>
      </div>
      <div class="header-v-divider flex-shrink-0"></div>
      <div class="d-flex align-items-center gap-2 header-clock-text fw-bold flex-shrink-0">
        <i class="bi bi-clock fs-5"></i>
        <span id="headerClock">7:39 AM</span>
      </div>
    </div>

  </div>
</header>

    <!-- MAIN DASHBOARD CONTENT -->
    <main class="queue-main-content p-2 p-md-3">
      <div class="row g-3 align-items-stretch">

        <!-- CENTER: Hero Screen (Now Calling) -->
        <div class="col-12 col-lg-6 order-1 order-lg-2 d-flex flex-column">
          <div
            class="hero-calling-card text-center text-white d-flex flex-column justify-content-between align-items-center p-4">

            <!-- Top Announcement Pill -->
            <div
              class="now-calling-pill rounded-pill px-4 px-md-5 py-2 text-white fw-extrabold text-uppercase d-inline-flex align-items-center gap-2 flex-shrink-0">
              <i class="bi bi-megaphone-fill"></i> NOW CALLING
            </div>

            <!-- Center Display -->
            <div
              class="my-auto w-100 position-relative py-3 py-md-2 d-flex flex-column align-items-center justify-content-center">
              <!-- Ticket Number -->
              <h1 class="hero-ticket-text mb-1 fw-extrabold tracking-tight" id="currentNumber">---</h1>

              <!-- Office Name -->
              <h2 class="hero-office-text mb-3 opacity-90" id="servingOffice">---</h2>

              <!-- Building Tag with Fluid Text -->
              <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill bg-white shadow-sm">
                <i class="bi bi-geo-alt-fill text-primary fw-bold fs-4"></i>
                <span class="text-dark fw-bold text-uppercase tracking-wider hero-building-tag mb-0"
                  id="servingBuilding">---</span>
              </div>
            </div>

            <p class="proceed-notice fs-10 text-white-50 mb-0 text-uppercase tracking-widest flex-shrink-0">
              Please proceed to the indicated office
            </p>
          </div>
        </div>

        <!-- LEFT: Waiting Queue Sidebar -->
        <div class="col-12 col-lg-3 order-2 order-lg-1 d-flex flex-column">
          <div class="queue-card d-flex flex-column">
            <div class="card-blue-header d-flex justify-content-between align-items-center flex-shrink-0">
              <div class="d-flex align-items-center gap-2 fw-bold text-uppercase fs-6">
                <i class="bi bi-people-fill"></i> Waiting Queue
              </div>
              <span class="badge bg-white text-primary rounded-pill px-3 py-1 fw-bold fs-7" id="queueCount">0
                WAITING</span>
            </div>

            <div class="waiting-list-scroll p-3 flex-grow-1 overflow-auto" id="waitingList">
              <!-- Dynamic items generated by JS -->
            </div>
          </div>
        </div>

        <!-- RIGHT: Office Status Grid -->
        <div class="col-12 col-lg-3 order-3 order-lg-3 d-flex flex-column">
          <div class="queue-card d-flex flex-column">
            <div class="card-blue-header d-flex justify-content-between align-items-center flex-shrink-0">
              <div class="d-flex align-items-center gap-2 fw-bold text-uppercase fs-6">
                <i class="bi bi-building-fill"></i> Office Status
              </div>
              <span class="badge bg-white text-primary rounded-pill px-2 py-1 fw-bold" style="font-size: 0.65rem;"
                id="officeSubtitle">0 OFFICES</span>
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
    <!-- Original Background Image Setup -->
    <div class="footer-bg-image" style="background-image: url('<?= $baseUrl; ?>/assets/img/queue_footer.webp');"></div>
    <div class="footer-bg-overlay"></div>

    <!-- Left: Announcements Label -->
    <div
      class="d-flex align-items-center gap-2 fw-extrabold text-uppercase footer-ticker-text text-warning pe-3 border-end border-secondary position-relative z-1"
      style="white-space: nowrap;">
      <i class="bi bi-megaphone-fill fs-5"></i> ANNOUNCEMENTS
    </div>

    <!-- Middle: Fluid Ticker Items -->
    <div class="overflow-hidden flex-grow-1 mx-3 position-relative z-1 text-uppercase" style="height: 28px;"
      id="tickerContainer">
      <div
        class="ticker-item active d-flex align-items-center gap-2 fw-bold footer-ticker-text text-white text-truncate">
        <i class="bi bi-info-circle-fill text-info fs-5 flex-shrink-0"></i>
        <span>OFFICE HOURS: MON–THU 7:00 AM TO 6:00 PM — TICKET ISSUANCE STRICTLY CLOSES AT 5:30 PM DAILY</span>
      </div>
      <div class="ticker-item d-flex align-items-center gap-2 fw-bold footer-ticker-text text-white text-truncate">
        <i class="bi bi-calendar-event-fill text-warning fs-5 flex-shrink-0"></i>
        <span>MISSED QUEUE NUMBERS ARE GIVEN ONE FINAL CALL AT THE BATCH END BEFORE AUTOMATIC CANCELLATION</span>
      </div>
      <div class="ticker-item d-flex align-items-center gap-2 fw-bold footer-ticker-text text-white text-truncate">
        <i class="bi bi-card-checklist text-success fs-5 flex-shrink-0"></i>
        <span>PLEASE READY ALL REQUIRED FORMS AND VALID IDS BEFORE APPROACHING YOUR ASSIGNED SERVICE COUNTER</span>
      </div>
    </div>

    <!-- Right: Voice Status Indicator -->
    <div
      class="d-none d-md-flex align-items-center gap-2 fw-bold footer-ticker-text text-success ps-3 border-start border-secondary position-relative z-1"
      style="white-space: nowrap;">
      <i class="bi bi-broadcast fs-5"></i> Voice Announcements ON
    </div>
  </footer>

</div>

<?php

$liveQueueJsPath = ROOT_PATH . '/assets/js/live_queue.js';

$liveQueueJsVersion = file_exists($liveQueueJsPath)
  ? filemtime($liveQueueJsPath)
  : time();

$extraJs = '<script src="../../assets/js/live_queue.js?v=' .
  $liveQueueJsVersion .
  '"></script>';

require_once __DIR__ . '/../../includes/footer.php';
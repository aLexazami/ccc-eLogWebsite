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

$extraCss  = '<link rel="stylesheet" href="' . $baseUrl . '/assets/css/style.css?v=' . $styleCssVersion . '">';
$extraCss .= '<link rel="stylesheet" href="' . $baseUrl . '/assets/css/live_queue.css?v=' . $liveQueueCssVersion . '">';

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="portal-bg">

  <!-- WRAPPER FOR HEADER AND MAIN CONTENT -->
  <div class="live-queue-wrapper">

    <!-- TOP HEADER -->
    <header class="queue-nav-header px-3 px-md-4 py-2 flex-shrink-0">
  <div class="row align-items-center g-0 w-100">

    <!-- Left (col-lg-3): Logo & Branding -->
    <div class="col-12 col-lg-3 d-flex align-items-center justify-content-start">
      <a href="<?= $baseUrl; ?>/index.php" class="d-flex align-items-center gap-3 text-decoration-none text-white">
        <img src="<?= $baseUrl; ?>/assets/img/ccc-logo.webp" alt="CCC Logo" class="queue-logo" onerror="this.style.display='none'">
        <div>
          <h3 class="fw-bold mb-0 text-white tracking-tight fs-5 fs-md-4">City College of Calamba</h3>
          <p class="small text-white-50 mb-0 fw-bold tracking-widest text-uppercase" style="font-size: 0.65rem;">STUDENT SERVICE QUEUE SYSTEM</p>
        </div>
      </a>
    </div>

    <!-- Center (col-lg-6): Process Flow aligned with NOW CALLING -->
    <div class="col-12 col-lg-6 d-none d-xl-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-between w-100 px-3 header-process-flow position-relative">
        
        <!-- Left Column Boundary Divider -->
        <div class="header-v-divider"></div>

        <!-- Step 1 -->
        <div class="d-flex align-items-center gap-2">
          <div class="step-circle-icon text-primary fs-5">
            <i class="bi bi-ticket-perforated-fill"></i>
            <span class="step-badge-number">1</span>
          </div>
          <div class="lh-1">
            <div class="step-title fw-extrabold text-uppercase">GET TICKET</div>
            <div class="step-subtitle">From the kiosk</div>
          </div>
        </div>

        <i class="bi bi-chevron-right step-arrow"></i>

        <!-- Step 2 -->
        <div class="d-flex align-items-center gap-2">
          <div class="step-circle-icon text-primary fs-5">
            <i class="bi bi-people-fill"></i>
            <span class="step-badge-number">2</span>
          </div>
          <div class="lh-1">
            <div class="step-title fw-extrabold text-uppercase">WAIT</div>
            <div class="step-subtitle">For your number</div>
          </div>
        </div>

        <i class="bi bi-chevron-right step-arrow"></i>

        <!-- Step 3 -->
        <div class="d-flex align-items-center gap-2">
          <div class="step-circle-icon text-primary fs-5">
            <i class="bi bi-megaphone-fill"></i>
            <span class="step-badge-number">3</span>
          </div>
          <div class="lh-1">
            <div class="step-title fw-extrabold text-uppercase">GET CALLED</div>
            <div class="step-subtitle">Via screen &amp; audio</div>
          </div>
        </div>

        <i class="bi bi-chevron-right step-arrow"></i>

        <!-- Step 4 -->
        <div class="d-flex align-items-center gap-2">
          <div class="step-circle-icon text-primary fs-5">
            <i class="bi bi-building-fill"></i>
            <span class="step-badge-number">4</span>
          </div>
          <div class="lh-1">
            <div class="step-title fw-extrabold text-uppercase">PROCEED</div>
            <div class="step-subtitle">To indicated office</div>
          </div>
        </div>

        <!-- Right Column Boundary Divider -->
        <div class="header-v-divider"></div>

      </div>
    </div>

    <!-- Right (col-lg-3): Live Date & Clock -->
    <div class="col-12 col-lg-3 d-flex align-items-center justify-content-end gap-3 text-white">
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
              Please proceed to the indicated office
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
          <div class="queue-card d-flex flex-column">
            <div class="card-blue-header d-flex justify-content-between align-items-center flex-shrink-0">
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

<?php

$liveQueueJsPath = ROOT_PATH . '/assets/js/live_queue.js';

$liveQueueJsVersion = file_exists($liveQueueJsPath)
    ? filemtime($liveQueueJsPath)
    : time();

$extraJs = '<script src="../../assets/js/live_queue.js?v=' .
    $liveQueueJsVersion .
    '"></script>';

require_once __DIR__ . '/../../includes/footer.php';
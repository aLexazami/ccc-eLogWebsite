<?php
// logbook/login.php

require_once __DIR__ . '/../config/config.php';

// Handle Logout Reset Parameter
if (isset($_GET['logout'])) {
    unset($_SESSION['logbook_authenticated'], $_SESSION['logbook_user_name'], $_SESSION['logbook_login_time']);
    header("Location: " . BASE_URL . "/logbook/login.php");
    exit();
}

// Redirect if already authenticated
if (!empty($_SESSION['logbook_authenticated'])) {
    header("Location: " . BASE_URL . "/logbook/");
    exit();
}

// --------------------------------------------------------------------------
// 1. HARDCODED OPERATOR CREDENTIALS
// --------------------------------------------------------------------------
defined('DEFAULT_LOGBOOK_USER') || define('DEFAULT_LOGBOOK_USER', 'admin');
defined('DEFAULT_LOGBOOK_PASS') || define('DEFAULT_LOGBOOK_PASS', 'admin123');

// --------------------------------------------------------------------------
// 2. POST PROCESSING (PRG Pattern)
// --------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUser = trim(filter_input(INPUT_POST, 'username', FILTER_DEFAULT) ?? '');
    $inputPass = trim(filter_input(INPUT_POST, 'password', FILTER_DEFAULT) ?? '');

    $validUser = !empty($inputUser) && hash_equals(DEFAULT_LOGBOOK_USER, $inputUser);
    $validPass = !empty($inputPass) && hash_equals(DEFAULT_LOGBOOK_PASS, $inputPass);

    if ($validUser && $validPass) {
        session_regenerate_id(true);
        $_SESSION['logbook_authenticated'] = true;
        $_SESSION['logbook_user_name'] = 'System Administrator';
        $_SESSION['logbook_login_time'] = time();

        header("Location: " . BASE_URL . "/logbook/");
        exit();
    }

    $_SESSION['flash_error'] = "Invalid username or password. Please try again.";
    header("Location: " . BASE_URL . "/logbook/login.php");
    exit();
}

// --------------------------------------------------------------------------
// 3. GET DISPLAY LOGIC
// --------------------------------------------------------------------------
$errorMsg = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);

$pageTitle = "Logbook Terminal Unlock";
$pageScript = '/assets/js/login-toggle.js';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="portal-bg min-vh-100 py-4 d-flex flex-column justify-content-between text-white">
    <div class="container my-auto">

        <!-- Glassmorphic Login Card -->
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <!-- Added border-0 class to strip card border completely -->
                <div class="card auth-card-static shadow-lg rounded-4 p-4 text-center">
                    
                    <div class="icon-badge mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-shield-lock-fill fs-1" style="color: var(--color-powder-blue);" aria-hidden="true"></i>
                    </div>

                    <h2 class="fw-bold text-white mb-5">Logbook Unlock</h2>

                    <!-- ERROR DISPLAY BLOCK -->
                    <?php if (!empty($errorMsg) || isset($_GET['error'])): ?>
                        <div class="alert alert-danger border-0 rounded-3 small py-2 mb-3" style="background-color: rgba(220, 53, 69, 0.25); color: #ffb3b8;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <?= !empty($errorMsg) ? htmlspecialchars($errorMsg) : "Logbook authorization required to proceed."; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL; ?>/logbook/login.php" method="POST" autocomplete="off">
                        
                        <!-- Username Field -->
                        <div class="form-floating mb-3 text-start">
                            <input 
                                type="text" 
                                class="form-control glass-input" 
                                id="username" 
                                name="username" 
                                placeholder="Username" 
                                required 
                                autofocus
                            >
                            <label for="username"><i class="bi bi-person me-1"></i> Username</label>
                        </div>

                        <!-- Password Field with Visibility Toggle -->
                        <div class="form-floating mb-3 text-start position-relative">
                            <input 
                                type="password" 
                                class="form-control glass-input pe-5" 
                                id="password" 
                                name="password" 
                                placeholder="Password" 
                                required
                            >
                            <label for="password"><i class="bi bi-key me-1"></i> Password</label>
                            
                            <!-- Password Visibility Toggle Button -->
                            <button 
                                type="button" 
                                class="btn password-toggle-btn position-absolute top-50 end-0 translate-middle-y me-2 border-0 bg-transparent text-white-50 p-2 shadow-none" 
                                id="togglePassword" 
                                aria-label="Toggle password visibility"
                                tabindex="-1"
                            >
                                <i class="bi bi-eye-slash-fill fs-5" id="togglePasswordIcon"></i>
                            </button>
                        </div>

                        <!-- 2-COLUMN ACTION BUTTON GRID -->
                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <a href="<?= BASE_URL; ?>/" class="btn btn-cancel btn-oval w-100 shadow-sm" role="button">
                                    <span class="btn-label fw-semibold">Cancel</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-portal btn-oval w-100 shadow-sm">
                                    <span class="btn-label fw-semibold">Unlock</span>
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>

    <!-- In-Page Footer Credit -->
    <div class="text-center py-3 mt-auto">
        <p class="small mb-0" style="color: var(--color-blue-gray);">
            &copy; <?= date('Y'); ?> City College of Calamba &bull; All Rights Reserved
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
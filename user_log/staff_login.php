<?php
// user_log/staff_login.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Calculate relative paths to core files
$baseUrl = defined('BASE_URL') ? BASE_URL : '..';
require_once __DIR__ . '/../includes/header.php'; // Includes DB connection & configurations

// 2. Security: Generate CSRF Token for form submission
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error_message = '';
$success_message = '';

// Helper function to resolve dynamic destination based on role
function getRedirectPathByRole(string $role, string $baseUrl): string {
    switch (strtolower($role)) {
        case 'system admin':
        case 'admin':
            return $baseUrl . '/admin/dashboard.php';
        case 'staff':
            return $baseUrl . '/staff/dashboard.php';
        default:
            return $baseUrl . '/index.php';
    }
}

// 3. Handle POST Request (Form Submission or AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // CSRF Protection Check
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        $error_message = 'Invalid request payload (CSRF token mismatch).';
    } else {
        // Sanitize and collect user input
        $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error_message = 'Please fill in all required fields.';
        } else {
            try {
                if (isset($pdo)) {
                    $stmt = $pdo->prepare("SELECT id, username, password_hash, role, full_name FROM users WHERE username = :username LIMIT 1");
                    $stmt->execute(['username' => $username]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user && password_verify($password, $user['password_hash'])) {
                        session_regenerate_id(true);

                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['user_role'] = $user['role'];
                        $_SESSION['full_name'] = $user['full_name'];

                        // Determine destination dynamically based on user role
                        $redirectUrl = getRedirectPathByRole($user['role'], $baseUrl);

                        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'success', 'redirect' => $redirectUrl]);
                            exit();
                        }

                        header("Location: " . $redirectUrl);
                        exit();
                    } else {
                        $error_message = 'Invalid username or password.';
                    }
                } else {
                    // Fallback stub for local testing without DB
                    if (($username === 'admin' || $username === 'staff') && $password === 'admin123') {
                        $_SESSION['user_id'] = 1;
                        $_SESSION['username'] = $username;
                        $_SESSION['user_role'] = ($username === 'admin') ? 'System Admin' : 'Staff';
                        $_SESSION['full_name'] = ucfirst($username) . ' User';

                        $redirectUrl = getRedirectPathByRole($_SESSION['user_role'], $baseUrl);

                        header("Location: " . $redirectUrl);
                        exit();
                    } else {
                        $error_message = 'Invalid credentials. (Demo: admin / admin123 or staff / admin123)';
                    }
                }
            } catch (PDOException $e) {
                error_log("Login error: " . $e->getMessage());
                $error_message = 'A system error occurred. Please try again later.';
            }
        }
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $error_message]);
        exit();
    }
}
?>

<style>
  .glass-card-readable {
    background: rgba(255, 255, 255, 0.88) !important;
    backdrop-filter: blur(16px) saturate(180%) !important;
    -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
    border: 1px solid rgba(255, 255, 255, 0.5) !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
  }

  .custom-login-input {
    background-color: #ffffff !important;
    color: #1e293b !important;
    border: 1px solid #cbd5e1 !important;
  }

  .custom-login-input:focus {
    border-color: #0284c7 !important;
    box-shadow: 0 0 0 0.25rem rgba(2, 132, 199, 0.15) !important;
  }

  .custom-input-group-text {
    background-color: #f1f5f9 !important;
    border: 1px solid #cbd5e1 !important;
    border-right: none !important;
    color: #475569 !important;
  }
</style>

<div class="portal-bg min-vh-100 d-flex flex-column justify-content-between position-relative">

    <!-- TOP HEADER / BANNER BACKDROP INTEGRATION -->
    <div class="banner-crop-container">
        <img
            src="<?= $baseUrl; ?>/assets/img/header-backdrop.png"
            alt="City College of Calamba Header Banner"
            class="banner-zoom-img"
            loading="eager"
        >
    </div>

    <!-- MAIN LOGIN CARD -->
    <div class="container my-auto py-5 position-relative z-1">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                
                <!-- Readable High-Contrast Card -->
                <div class="card glass-card-readable border-0 p-4 rounded-4">
                    
                    <div class="text-center mb-4">
                        <img src="<?= $baseUrl; ?>/assets/img/ccc-logo.png" alt="CCC Logo" class="mb-3" style="height: 70px;" onerror="this.style.display='none'">
                        <h2 class="fw-extrabold mb-1" style="color: #0d1b2a;">System Portal</h2>
                        <p class="small text-muted mb-0">Sign in as Staff or System Administrator</p>
                    </div>

                    <!-- Alert Feedback Messages -->
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2 px-3 mb-3 fw-medium" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" id="staffLoginForm">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                        <!-- Username Input -->
                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold small" style="color: #1e293b;">Username</label>
                            <div class="input-group">
                                <span class="input-group-text custom-input-group-text">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <input type="text" class="form-control custom-login-input" id="username" name="username" placeholder="Enter username" required>
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold small" style="color: #1e293b;">Password</label>
                            <div class="input-group">
                                <span class="input-group-text custom-input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" class="form-control custom-login-input" id="password" name="password" placeholder="Enter password" required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold rounded-3 shadow-sm mb-3" style="background-color: #0f4c81; border: none;">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                        </button>

                        <!-- Back to Portal Button -->
                        <div class="text-center">
                            <a href="<?= $baseUrl; ?>/index.php" class="text-decoration-none small fw-bold" style="color: #0284c7;">
                                <i class="bi bi-arrow-left me-1"></i> Return to Main Portal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- SYSTEM FOOTER -->
    <div class="text-center py-3 small fw-bold position-relative z-1" style="color: #ffffff; text-shadow: 0 1px 3px rgba(0,0,0,0.6);">
        &copy; <?= date('Y'); ?> City College of Calamba &bull; All Rights Reserved
    </div>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<?php
// kiosk/index.php
$pageTitle = "Visitor Check-in & Queue";
$pageScript = "/assets/js/kiosk.js";

// Include Header (Loads Bootstrap CSS)
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="card-title text-center text-primary mb-3">
                        <i class="bi bi-person-badge"></i> Visitor E-Log
                    </h3>
                    <p class="text-muted text-center mb-4">Fill out the details to get your queue ticket.</p>
                    
                    <form id="kioskForm">
                        <div class="mb-3">
                            <label for="visitorName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="visitorName" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose of Visit</label>
                            <select class="form-select" id="purpose" required>
                                <option value="" selected disabled>Select purpose...</option>
                                <option value="Inquiry">Inquiry</option>
                                <option value="Billing">Billing</option>
                                <option value="Technical Support">Technical Support</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-ticket-perforated"></i> Print / Generate Ticket
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
// Include Footer (Loads Bootstrap JS)
require_once __DIR__ . '/../includes/footer.php';
?>
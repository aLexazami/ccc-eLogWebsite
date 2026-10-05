<?php
// includes/footer.php
?>
    <!-- Local Bootstrap 5 JS Bundle -->
    <script src="<?= BASE_URL; ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Optional Page-Specific JS Injection -->
    <?= $extraJs ?? '' ?>

    <!-- Kiosk & Logbook Module JS -->
<?php if (basename($_SERVER['PHP_SELF']) === 'student_info.php' || strpos($_SERVER['PHP_SELF'], 'modules/logbook') !== false): ?>
    <script src="<?= htmlspecialchars($baseUrl); ?>assets/js/kiosk-flow.js"></script>
<?php endif; ?>

<!-- Staff Portal Module JS -->
<?php if (strpos($_SERVER['PHP_SELF'], 'user_log') !== false): ?>
    <script src="<?= htmlspecialchars($baseUrl); ?>assets/js/staff-portal.js"></script>
<?php endif; ?>

</body>
</html>
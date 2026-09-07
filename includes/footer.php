<?php
// includes/footer.php
?>
    <!-- Local Bootstrap 5 JS Bundle -->
    <script src="<?= BASE_URL; ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Optional Page-Specific JS Injection -->
    <?php if (isset($pageScript)): ?>
        <script src="<?= BASE_URL . htmlspecialchars($pageScript); ?>"></script>
    <?php endif; ?>
</body>
</html>
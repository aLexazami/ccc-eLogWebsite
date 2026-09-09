<?php
// includes/footer.php
?>
    <!-- Local Bootstrap 5 JS Bundle -->
    <script src="<?= BASE_URL; ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Page-Specific JS Injection with Dynamic Cache-Busting (Supports String or Array) -->
    <?php 
    if (isset($pageScript) && !empty($pageScript)): 
        $scripts = is_array($pageScript) ? $pageScript : [$pageScript];
        $rootPath = defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__);

        foreach ($scripts as $script):
            if (empty($script)) continue;

            $cleanPath = '/' . ltrim($script, '/');
            $scriptFullPath = $rootPath . $cleanPath;
            $scriptVersion = file_exists($scriptFullPath) ? filemtime($scriptFullPath) : time();
            $scriptUrl = htmlspecialchars(BASE_URL . $cleanPath . '?v=' . $scriptVersion, ENT_QUOTES, 'UTF-8');
    ?>
        <script src="<?= $scriptUrl; ?>"></script>
    <?php 
        endforeach;
    endif; 
    ?>
</body>
</html>
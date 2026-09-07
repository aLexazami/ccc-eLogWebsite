<?php
// config/config.php

// Option A: Explicit definition (Most reliable across local and production)
define('BASE_URL', '/ccc-website');

// Option B: Auto-detect subfolder dynamically (Works out of the box on XAMPP)
// $scriptDir = str_replace('\\', '/', dirname(__DIR__));
// $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
// define('BASE_URL', str_replace($docRoot, '', $scriptDir));
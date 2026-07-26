<?php
require_once dirname(__DIR__) . '/config/config.php';

$source = ROOT_PATH . '/app-release.apk';
$dest = PUBLIC_PATH . '/app-release.apk';

if (file_exists($source)) {
    if (copy($source, $dest)) {
        echo "SUCCESS: app-release.apk copied to public directory (" . filesize($dest) . " bytes).";
    } else {
        echo "ERROR: Failed to copy app-release.apk to public directory.";
    }
} else {
    echo "ERROR: Source app-release.apk does not exist at " . $source;
}

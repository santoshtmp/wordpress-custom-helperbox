<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}


spl_autoload_register(function ($class) {

    $prefix = 'wp_helperbox\\';
    $base_dir = __DIR__ . '/classes/';

    // Only load our own classes
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    // Remove namespace prefix
    $relative_class = substr($class, strlen($prefix));

    // Convert namespace to file path
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // check if file exist
    if (file_exists($file)) {
        require_once $file;
    }
});

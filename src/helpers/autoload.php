<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}


spl_autoload_register(function ($class) {

    $prefix = 'Helperbox_Plugin\\';
    $base_dir = helperbox_path . 'src/classes/';

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
    } else {
        $path = explode(
            '\\',
            str_replace('_', '-', strtolower($relative_class))
        );

        $last_key = array_key_last($path);
        $path[$last_key] = sprintf('class-%s', $path[$last_key]);

        $file = $base_dir . implode('/', $path) . '.php';

        if (is_readable($file)) {
            require_once $file;
        }
    }

});



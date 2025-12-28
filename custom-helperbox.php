<?php

/*
Plugin Name: Custom Helper Box
Description: Custom Helper Box provides the custom functions and features.
Contributors: santoshtmp7
Plugin URI: https://github.com/santoshtmp/wordpress-custom-helperbox
Tags: settings, functions, security
Version: 1.0
Author: santoshtmp7
Author URI: 
Requires WP: 6.0
Tested up to: 6.8
Requires PHP: 8.0
Text Domain: helperbox
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

use Helperbox_Plugin\admin\Settings;
use Helperbox_Plugin\Securities;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// define helperbox constant named
if (!defined('helperbox_url')) {
    define('helperbox_url', plugin_dir_url(__FILE__));
}
if (!defined('helperbox_path')) {
    define('helperbox_path', plugin_dir_path(__FILE__));
}
if (!defined('helperbox_basename')) {
    define('helperbox_basename', plugin_basename(__FILE__));
}

// autoload classes
require_once __DIR__ . '/src/helpers/autoload.php';

// 
if (class_exists(Settings::class)) {
    new Settings();
}
if (class_exists(Securities::class)) {
    new Securities();
}

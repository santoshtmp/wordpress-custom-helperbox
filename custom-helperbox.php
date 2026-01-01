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
use Helperbox_Plugin\Assets;
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
require_once __DIR__ . '/inc/helpers/autoload.php';

// 
if (class_exists(Settings::class)) {
    new Settings();
}
if (class_exists(Securities::class)) {
    new Securities();
}
if (class_exists(Assets::class)) {
    new Assets();
}




add_action('admin_notices', function () {
    $screen = get_current_screen();

    if (!$screen || $screen->id !== 'plugins') {
        return;
    }

    // check setting
    if (get_option('helperbox_disallow_file', '1') != '1') {
        return;
    }

    $updatestatus = Settings::helperbox_render_update_status_list(true);

?>
    <div class="notice notice-success ">
        <p>
            <strong>HelperBox:</strong>
            <a href="/wp-admin/options-general.php?page=helperbox&tab=security&check_update_status=true" target="_blank">
                check available update versions status :
            </a>
        <ul>
            <li> <?php echo $updatestatus['plugin_count']; ?> Plugin update available</li>
            <li> <?php echo $updatestatus['theme_count']; ?> Theme update available</li>
        </ul>
        </p>
    </div>
<?php
});

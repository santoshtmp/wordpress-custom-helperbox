<?php

/**
 * Helperbox
 *
 * @package helperbox
 * 
 */

namespace Helperbox_Plugin;

use Helperbox_Plugin\admin\Settings;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Helperbox class
 */
class HelperBox {

    /**
     * construction
     */
    function __construct() {

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
        if (class_exists(User_Role::class)) {
            new User_Role();
        }

        // General hooks
        add_action('admin_notices', [$this, 'helperbox_admin_notices']);
        add_filter('theme_page_templates', [$this, 'register_page_templates']);
        add_filter('page_template_hierarchy', [$this, 'page_template_to_subdir']);

        // include api path
        $include_dir_paths = [
            helperbox_path . 'endpoint/rest',
            helperbox_path . 'endpoint/ajax',

        ];
        self::requires_dir_paths_files($include_dir_paths);
    }


    /**
     * requires all ".php" files from dir defined in "include_dir_paths" at first level.
     * @param array $include_dir_paths will be [__DIR__.'/inc'];
     */
    public static function requires_dir_paths_files($include_dir_paths) {
        foreach ($include_dir_paths as $key => $file_path) {
            if (!file_exists($file_path)) {
                continue;
            }
            foreach (new \DirectoryIterator($file_path) as $file) {
                if ($file->isDot() || $file->isDir()) {
                    continue;
                }
                $fileExtension = $file->getExtension(); // Get the current file extension
                if ($fileExtension != "php") {
                    continue;
                }
                // $fileName = $file->getFilename(); // Get the full name of the current file.
                $filePath = $file->getPathname(); // Get the full path of the current file
                if ($filePath) {
                    require_once $filePath;
                }
            }
        }
    }

    /**
     * ==============================
     * https://developer.wordpress.org/reference/hooks/theme_page_templates/ 
     * https://developer.wordpress.org/themes/template-files-section/page-template-files/
     * https://www.wpexplorer.com/wordpress-page-templates-plugin/
     * @param array $post_templates Array of page templates. Keys are filenames, values are translated names.
     * @return array Filtered array of page templates.
     * ==============================
     */
    function register_page_templates($post_templates) {
        // check setting
        $theme_templates_dir = get_option('helperbox_custom_theme_templates_dir', Settings::CUSTOM_THEME_TEMP_DIR);
        if (!$theme_templates_dir) {
            return $post_templates;
        }
        $templates_dir = get_stylesheet_directory() . '/' . trim($theme_templates_dir, "/");
        if (!is_dir($templates_dir)) {
            return $post_templates;
        }

        $template_files = scandir($templates_dir);
        foreach ($template_files as $filename) {
            if ($filename === '.' || $filename === '..') {
                continue;
            }
            $path_info = pathinfo($filename);
            if ($path_info['extension'] === 'php') {
                $full_path = $templates_dir . '/' . $filename;
                if (preg_match('|Template Name:(.*)$|mi', file_get_contents($full_path), $header)) {
                    $template_name = trim(_cleanup_header_comment($header[1]));
                    $template_path = $theme_templates_dir . '/' . $filename;
                    $post_templates[$template_path] = $template_name;
                }
            }
        }
        return $post_templates;
    }


    /*
        ==============================
        https://developer.wordpress.org/reference/hooks/type_template_hierarchy/
        https://developer.wordpress.org/themes/basics/template-hierarchy/
        https://wordpress.stackexchange.com/a/227006/110572
        ==============================
        */
    function page_template_to_subdir($templates = array()) {
        // check setting
        $theme_templates_dir = get_option('helperbox_custom_theme_templates_dir', Settings::CUSTOM_THEME_TEMP_DIR);
        $theme_templates_dir = trim($theme_templates_dir, "/");
        if (!$theme_templates_dir) {
            return $templates;
        }
        $templates_dir = get_stylesheet_directory() . '/' . trim($theme_templates_dir, "/");
        if (!is_dir($templates_dir)) {
            return $templates;
        }

        // Generally this doesn't happen, unless another plugin / theme does modifications of their own.
        // In that case, it's better not to mess with it again with our code.
        if (empty($templates) || !is_array($templates) || count($templates) < 3) {
            return $templates;
        }

        $page_tpl_idx = 0;
        $cnt = count($templates);
        if ($templates[0] === get_page_template_slug()) {
            // if there is custom template, then our page-{slug}.php template is at the next index 
            $page_tpl_idx = 1;
        }

        // the last one in $templates is page.php, so
        // all but the last one in $templates starting from $page_tpl_idx will be moved to sub-directory
        for ($i = $page_tpl_idx; $i < $cnt - 1; $i++) {
            $templates[$i] = $theme_templates_dir . '/' . $templates[$i];
        }

        return $templates;
    }

    /**
     * Admin Notices
     */
    function helperbox_admin_notices() {
        $screen = get_current_screen();

        /**
         * Information Notice in plugin page
         */
        if ($screen && $screen->id == 'plugins') {
            // check setting
            if (get_option('helperbox_disallow_file', '1') != '1') {
                return;
            }
            if (!current_user_can('manage_options')) {
                return;
            }

            include_once ABSPATH . 'wp-admin/includes/update.php';
            // plugin
            wp_update_plugins();
            $plugin_updates = get_site_transient('update_plugins');
            // theme
            wp_update_themes();
            $theme_updates  = get_site_transient('update_themes');
            // count
            $plugin_count = count($plugin_updates->response);
            $theme_count = count($theme_updates->response);

?>
            <div class="notice notice-success ">
                <p>
                    <strong>HelperBox:</strong>
                    <a href="/wp-admin/options-general.php?page=helperbox&tab=security&check_update_status=true" target="_blank">
                        Check available update versions status
                    </a>
                <ul>
                    <li> <?php echo $plugin_count; ?> Plugin update available</li>
                    <li> <?php echo $theme_count; ?> Theme update available</li>
                </ul>
                </p>
            </div>
            <?php
        }

        /**
         * Information notice in helperbox update status check page 
         */
        if ($screen && $screen->id == 'settings_page_helperbox') {
            $check_update_status = $_GET['check_update_status'] ?? 'false';
            $active_tab = $_GET['tab'] ?? 'general';
            if ($active_tab == 'security' && $check_update_status == 'true'):
            ?>
                <div class="notice notice-success ">
                    <p>Updates are shown for reference only. File modifications are disabled.</p>
                    <p>To apply updates, uncheck "Disallow file modifications through admin interface" option from Helperbox security settings</p>
                    <p>
                        <a class="wp-core-ui button" href="/wp-admin/options-general.php?page=helperbox&tab=security">Check Helper Box Security Settings</a>
                    </p>
                </div>
<?php
            endif;
        }
    }


    /**
     * === END ===
     */
}

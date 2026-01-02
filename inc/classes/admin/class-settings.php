<?php

/**
 * Helperbox admin settings
 *
 * @package helperbox
 * 
 */

namespace Helperbox_Plugin\admin;


/**
 * Reference: 
 * https://developer.wordpress.org/reference/functions/register_setting/
 * https://developer.wordpress.org/reference/hooks/admin_menu/ 
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Settings class
 */
class Settings {

    public const ADMIN_PAGE_SLUG = 'helperbox';
    public const DEFAULT_LOGIN_BG = '#f1f1f1';
    public const DEFAULT_FORMLOGIN_BG = '#fff';

    /**
     * construction
     */
    function __construct() {
        add_filter('plugin_action_links_' . helperbox_basename, [$this, 'helperbox_settings_link']);
        add_action('admin_init', [$this, 'helperbox_settings_init']);
        add_action('admin_menu', [$this, 'helperbox_submenu']);
        add_action('update_option_helperbox_disable_phpexecution_upload_dir', [$this, 'update_option_helperbox_disable_phpexecution_upload_dir'], 10, 2);
    }

    /**
     * Get the URL for the settings page
     *
     * @return string The URL for the settings page
     */
    public static function get_settings_page_url() {
        return 'options-general.php?page=' . self::ADMIN_PAGE_SLUG;
    }


    // Hook into the plugin action links filter
    public function helperbox_settings_link($links) {
        // Create the settings link
        $settings_link = '<a href="' . self::get_settings_page_url() . '">Settings</a>';
        // Append the link to the existing links array
        array_unshift($links, $settings_link);
        return $links;
    }

    // Register and define the settings.
    public function helperbox_settings_init() {

        $helperbox_general_settings_group = 'helperbox_general_settings_group';

        // General settings
        register_setting(
            $helperbox_general_settings_group,
            'helperbox_custom_theme_templates_dir',
            [
                'type' => 'text',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ""
            ]
        );

        register_setting(
            $helperbox_general_settings_group,
            'helperbox_user_role_name',
            [
                'type' => 'text',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ""
            ]
        );

        // breadcrumb feature settings
        $helperbox_breadcrumb_settings_group = 'helperbox_breadcrumb_settings_group';
        register_setting(
            $helperbox_breadcrumb_settings_group,
            'helperbox_breadcrumb_feature',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        register_setting(
            $helperbox_breadcrumb_settings_group,
            'helperbox_breadcrumb_exclude_post_type',
            [
                'type' => 'array',
                'sanitize_callback' => [$this, 'helperbox_sanitize_array_text_field'],
                'default' => []
            ]
        );

        // login feature settings
        $helperbox_adminlogin_settings_group = 'helperbox_adminlogin_settings_group';
        register_setting(
            $helperbox_adminlogin_settings_group,
            'helperbox_custom_adminlogin',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        register_setting(
            $helperbox_adminlogin_settings_group,
            'helperbox_adminlogin_bgcolor',
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_hex_color', // Ensures valid hex color
                'default'           => self::DEFAULT_LOGIN_BG,
            ]
        );
       
        register_setting(
            $helperbox_adminlogin_settings_group,
            'helperbox_adminlogin_formbgcolor',
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_hex_color', // Ensures valid hex color
                'default'           => self::DEFAULT_LOGIN_BG,
            ]
        );

        register_setting(
            $helperbox_adminlogin_settings_group,
            'helperbox_adminlogin_bgimages',
            [
                'type'              => 'array',
                'sanitize_callback' => [$this, 'helperbox_sanitize_image_ids'],
                'default'           => [],
            ]
        );

        register_setting(
            $helperbox_adminlogin_settings_group,
            'helperbox_adminlogin_logo',
            [
                'type'              => 'array',
                'sanitize_callback' => [$this, 'helperbox_sanitize_image_ids'],
                'default'           => [],
            ]
        );

        // security settings 
        $helperbox_security_settings_group = 'helperbox_security_settings_group';
        register_setting(
            $helperbox_security_settings_group,
            'helperbox_comment_feature',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        register_setting(
            $helperbox_security_settings_group,
            'helperbox_disable_restapi_unauthenticated_user',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        register_setting(
            $helperbox_security_settings_group,
            'helperbox_disallow_file',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        register_setting(
            $helperbox_security_settings_group,
            'helperbox_disable_phpexecution_upload_dir',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        register_setting(
            $helperbox_security_settings_group,
            'helperbox_disable_emojicons',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );
    }

    // Sanitize the selected post types
    function helperbox_sanitize_array_text_field($input) {
        if (!is_array($input)) {
            return [];
        }
        return array_map('sanitize_text_field', $input);
    }

    /**
     * Sanitize background image IDs (ensure they are integers and valid attachments)
     */
    public function helperbox_sanitize_image_ids($input) {
        if (!$input || $input == NULL) {
            return;
        }
        if (is_string($input)) {
            $input = explode(",", $input);
        }
        $sanitized = array_map('absint', $input); // Convert to integers
        // Optional: verify each is a valid attachment
        return array_filter($sanitized, function ($id) {
            return wp_attachment_is_image($id);
        });
    }


    // Register the menu page.
    public function helperbox_submenu() {
        add_options_page(
            'Custom Helper Box', // Page title.
            'Custom Helper Box', // Menu title.
            'manage_options',     // Capability required to see the menu.
            self::ADMIN_PAGE_SLUG, // Menu slug.
            [$this, 'helperbox_admin_setting_page_content_callback'] // Function to display the page content.
        );
    }

    /**
     * Render the settings page.
     * Callback function to display the content of the submenu page.
     */
    public function helperbox_admin_setting_page_content_callback() {

        // Register metaboxes right before rendering (since add_meta_boxes won't fire)
        add_meta_box(
            'helperbox_general_settings',
            'Custom Helper Box Settings',
            [$this, 'render_helperbox_general_settings_box'],
            'helperbox_settings_page',
            'normal',
            'default'
        );
        $check_update_status = $_GET['check_update_status'] ?? 'false';
        $active_tab = $_GET['tab'] ?? 'general';

?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Custom Helper Box</h1>
            <?php
            if ($active_tab == 'security' && $check_update_status == 'true'):
                SettingsTemp::temp_helperbox_available_update_list();
            else: ?>
                <form method="post" action="options.php">
                    <div id="poststuff">
                        <div id="post-body" class="metabox-holder columns-2">
                            <div id="post-body-content">
                                <?php
                                do_meta_boxes('helperbox_settings_page', 'normal', null);
                                submit_button();
                                ?>
                            </div>
                        </div>
                    </div>
                </form>
            <?php
            endif;
            ?>
        </div>
<?php
    }

    /**
     * 
     */
    function render_helperbox_general_settings_box() {
        $active_tab = $_GET['tab'] ?? 'general';
        SettingsTemp::temp_helperbox_settings_group_nav_tab($active_tab);
        if ($active_tab === 'general') :
            SettingsTemp::temp_helperbox_general_settings_group();
        elseif ($active_tab === 'breadcrumb'):
            SettingsTemp::temp_helperbox_breadcrumb_settings_group();
        elseif ($active_tab === 'adminlogin'):
            SettingsTemp::temp_helperbox_adminlogin_settings_group();
        elseif ($active_tab === 'security'):
            SettingsTemp::temp_helperbox_security_settings_group();
        endif;
    }


    /**
     *  Runs ONLY when 'helperbox_disable_phpexecution_upload_dir' option is updated via options.php
     */
    public function update_option_helperbox_disable_phpexecution_upload_dir($old_value, $new_value) {
        if ($old_value === $new_value) {
            return; // Nothing changed
        }

        if ($old_value !== $new_value) {
            $uploads_dir = WP_CONTENT_DIR . '/uploads';
            $htaccess_file = $uploads_dir . '/.htaccess';
            $htaccess_content = <<<HTACCESS
# ---- Start Edit by Custom Helperbox ----
# Disable PHP execution in uploads directory
php_flag engine off
<FilesMatch "\.php$">
    Deny from all
</FilesMatch>
# ---- End Edit by Custom Helperbox ----
HTACCESS;

            // Add htaccess content when enabling
            if ($new_value === true || $new_value == '1') {
                // Create uploads dir if not exists
                if (!is_dir($uploads_dir)) {
                    mkdir($uploads_dir, 0755, true);
                }
                // Append or replace your block in .htaccess
                if (file_exists($htaccess_file)) {
                    $current = file_get_contents($htaccess_file);
                    // Remove old block if exists
                    $current = preg_replace(
                        '/# ---- Start Edit by Custom Helperbox ----.*# ---- End Edit by Custom Helperbox ----\s*/s',
                        '',
                        $current
                    );
                    $current .= $htaccess_content . "\n";
                    file_put_contents($htaccess_file, $current);
                } else {
                    file_put_contents($htaccess_file, $htaccess_content);
                }
            }

            // Remove htaccess content when disabling
            if ($new_value === false || $new_value === '' || $new_value === '0') {
                //    remove htaccess content
                if (file_exists($htaccess_file)) {
                    $current = file_get_contents($htaccess_file);
                    $current = preg_replace(
                        '/# ---- Start Edit by Custom Helperbox ----.*# ---- End Edit by Custom Helperbox ----\s*/s',
                        '',
                        $current
                    );

                    if (trim($current) === '') {
                        // Delete file if empty
                        unlink($htaccess_file);
                    } else {
                        // Otherwise, save without your block
                        file_put_contents($htaccess_file, $current);
                    }
                }
            }
        }
    }

    /**
     * 
     * ===== END ======
     */
}

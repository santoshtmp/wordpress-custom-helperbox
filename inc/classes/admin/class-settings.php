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

        $settings_option_group = 'helperbox_settings_group';

        // Sanitize the character limit as an integer
        register_setting(
            $settings_option_group,
            'ptreq_character_limit',
            [
                'type' => 'integer',
                'sanitize_callback' => 'absint',
                'default' => 100
            ]
        );

        // Sanitize the post types as an array of strings
        register_setting(
            $settings_option_group,
            'ptreq_post_types',
            [
                'type' => 'array',
                'sanitize_callback' => [$this, 'helperbox_sanitize_post_types'],
                'default' => []
            ]
        );

        // breadcrumb feature settings
        register_setting(
            $settings_option_group,
            'helperbox_breadcrumb_feature',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        // login feature settings
        register_setting(
            $settings_option_group,
            'helperbox_custom_adminlogin',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        // Login page background color
        register_setting(
            $settings_option_group,
            'helperbox_adminlogin_bgcolor',
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_hex_color', // Ensures valid hex color
                'default'           => self::DEFAULT_LOGIN_BG,
            ]
        );

        // Login page background images (array of attachment IDs)
        register_setting(
            $settings_option_group,
            'helperbox_adminlogin_bgimages',
            [
                'type'              => 'array',
                'sanitize_callback' => [$this, 'helperbox_sanitize_image_ids'],
                'default'           => [],
            ]
        );

        // security settings 
        register_setting(
            $settings_option_group,
            'helperbox_comment_feature',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        register_setting(
            $settings_option_group,
            'helperbox_disable_restapi_unauthenticated_user',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        register_setting(
            $settings_option_group,
            'helperbox_disallow_file',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        register_setting(
            $settings_option_group,
            'helperbox_disable_phpexecution_upload_dir',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );
    }

    /**
     * Sanitize background image IDs (ensure they are integers and valid attachments)
     */
    public function helperbox_sanitize_image_ids($input) {
        $input = explode(",", $input);
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
                $this->helperbox_render_update_status_list();
            else: ?>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('helperbox_settings_group');
                    ?>
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

    ?>
        <h3 class="nav-tab-wrapper">
            <a href="?page=helperbox&tab=general"
                class="nav-tab <?php echo ($active_tab === 'general') ? 'nav-tab-active' : ''; ?>">
                General
            </a>

            <a href="?page=helperbox&tab=breadcrumb"
                class="nav-tab <?php echo ($active_tab === 'breadcrumb') ? 'nav-tab-active' : ''; ?>">
                Breadcrumb
            </a>

            <a href="?page=helperbox&tab=adminlogin"
                class="nav-tab <?php echo ($active_tab === 'adminlogin') ? 'nav-tab-active' : ''; ?>">
                Admin Login
            </a>

            <a href="?page=helperbox&tab=security"
                class="nav-tab <?php echo ($active_tab === 'security') ? 'nav-tab-active' : ''; ?>">
                Security
            </a>
        </h3>

        <?php if ($active_tab === 'general') { ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="ptreq_post_types">
                            Select Post Types To Apply Title Character Limit.
                        </label>
                    </th>
                    <td>
                        <?php
                        $option = (get_option('ptreq_post_types')) ?: [];
                        $post_types = get_post_types(['public'   => true], 'objects');
                        // unset($post_types['attachment']);
                        foreach ($post_types  as $key => $value) {
                            $checked = '';
                            if (in_array($value->name, $option)) {
                                $checked = 'Checked';
                            }
                        ?>
                            <label for="post-type-<?php echo esc_attr($key); ?>">
                                <input type="checkbox" name="ptreq_post_types[]" id="post-type-<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value->name); ?>" <?php echo esc_attr($checked); ?> <?php checked(in_array($value->name, $option)); ?>>
                                <?php echo esc_attr($value->label); ?>
                            </label>
                        <?php
                        }
                        echo '<p class="description">Title required character limit will only apply to selected post type. If all post type are unchecked, it will apply to all post type.</p>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <p>Other availables add on plugins:</p>

                    </th>
                    <td>
                        <ol>
                            <li>"Post Title Required" available at
                                <a href="https://wordpress.org/plugins/post-title-required/" target="_blank">wordpress.org</a>
                                and
                                <a href="https://github.com/santoshtmp/wordpress-post-title-required" target="_blank">Github</a>
                            </li>
                            <li>"Citation Note" available at
                                <a href="https://wordpress.org/plugins/citation-note/" target="_blank">wordpress.org</a>
                                and
                                <a href="https://github.com/santoshtmp/wordpress-citation-note" target="_blank">Github</a>
                            </li>
                            <li>"CSF - Custom Search Filter" available at <a href="https://github.com/santoshtmp/wordpress-custom-search-filter" target="_blank">Github</a> </li>
                            <li>"Restore & Clean Media" available at <a href="https://github.com/santoshtmp/wordpress-restore-media-clean-data" target="_blank">Github</a> </li>

                        </ol>
                    </td>
                </tr>

            </table>
        <?php } elseif ($active_tab === 'breadcrumb') { ?>
            <table class="form-table" table-tab="breadcrumb">
                <tr>
                    <th scope="row">
                        <label for="helperbox_breadcrumb_feature">
                            Acivate Helperbox Breadcrumb
                        </label>
                    </th>
                    <td>
                        <input
                            type="checkbox"
                            name="helperbox_breadcrumb_feature"
                            id="helperbox_breadcrumb_feature"
                            value="1"
                            <?php checked(get_option('helperbox_breadcrumb_feature', '1')); ?>>
                    </td>
                </tr>

            </table>
        <?php } elseif ($active_tab === 'adminlogin') {
            $custom_adminlogin = get_option('helperbox_custom_adminlogin', '1');
        ?>
            <table class="form-table" table-tab="breadcrumb">
                <tr>
                    <th scope="row">
                        <label for="helperbox_custom_adminlogin">
                            Custom Login Page
                        </label>
                    </th>
                    <td>
                        <input
                            type="checkbox"
                            name="helperbox_custom_adminlogin"
                            id="helperbox_custom_adminlogin"
                            value="1"
                            <?php checked($custom_adminlogin); ?>>
                        <div class="description">
                            <p> This will give options to customize login page.</p>
                        </div>
                    </td>
                </tr>
                <?php
                if ($custom_adminlogin == '1'):
                ?>
                    <tr>
                        <th scope="row">
                            <label for="helperbox_adminlogin_bgcolor">
                                Background Color
                            </label>
                        </th>
                        <td>
                            <input
                                type="text"
                                name="helperbox_adminlogin_bgcolor"
                                id="helperbox_adminlogin_bgcolor"
                                value="<?php echo esc_attr(get_option('helperbox_adminlogin_bgcolor', self::DEFAULT_LOGIN_BG)); ?>"
                                class="helperbox-color-picker" />
                            <p class="description">
                                Choose the background color for the login page. Default: <?php echo self::DEFAULT_LOGIN_BG; ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="helperbox_adminlogin_bgimages">Background Images</label>
                        </th>
                        <td>
                            <div class="helperbox-bg-images-upload">
                                <div class="helperbox-bg-images-preview">
                                    <?php
                                    // https://rudrastyh.com/wordpress/customizable-media-uploader.html
                                    $image_ids = get_option('helperbox_adminlogin_bgimages', []);
                                    $image_ids = is_array($image_ids) ? $image_ids : [];
                                    foreach ($image_ids as $image_id) {
                                        echo wp_get_attachment_image(
                                            $image_id,
                                            'thumbnail',
                                            false,
                                            [
                                                'style' => 'margin:5px;'
                                            ]
                                        );
                                    }
                                    ?>
                                </div>
                                <p>
                                    <input type="button" class="button helperbox-add-bg-images" value="Add / Select Background Images" />
                                    <input type="button" class="button helperbox-remove-all-bg-images" value="Remove All" style="display:<?php echo empty($image_ids) ? 'none' : 'inline-block'; ?>;" />
                                </p>
                                <input type="hidden" name="helperbox_adminlogin_bgimages" id="helperbox_adminlogin_bgimages" value="<?php echo esc_attr(implode(',', $image_ids)); ?>" />
                                <p class="description">
                                    Select one or more images to use as background on the login page. Multiple images will cycle (optional fade effect via CSS).
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php
                endif;
                ?>

            </table>
        <?php } elseif ($active_tab === 'security') { ?>
            <table class="form-table" table-tab='security'>
                <tr>
                    <th scope="row">
                        <label for="helperbox_comment_feature">
                            Disable comment feature completely
                        </label>
                    </th>
                    <td>
                        <input
                            type="checkbox"
                            name="helperbox_comment_feature"
                            id="helperbox_comment_feature"
                            value="1"
                            <?php checked(get_option('helperbox_comment_feature', '1')); ?>>
                        <p class="description">This will remove edit-comments.php page and close comments feature completely.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="helperbox_disable_restapi_unauthenticated_user">
                            Disable REST API for unauthenticated users
                        </label>
                    </th>
                    <td>
                        <input
                            type="checkbox"
                            name="helperbox_disable_restapi_unauthenticated_user"
                            id="helperbox_disable_restapi_unauthenticated_user"
                            value="1"
                            <?php checked(get_option('helperbox_disable_restapi_unauthenticated_user', '1')); ?>>
                        <p class="description">This will disable REST API for unauthenticated user. if "_nonce" is verified, it won't restrict.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="helperbox_disallow_file">
                            Disallow file modifications through admin interface
                        </label>
                    </th>
                    <td>
                        <input
                            type="checkbox"
                            name="helperbox_disallow_file"
                            id="helperbox_disallow_file"
                            value="1"
                            <?php checked(get_option('helperbox_disallow_file', '1')); ?>>
                        <div class="description">
                            <p> This option prevents all file modifications from the WordPress admin area. Plugin and theme installation, updates, and deletion will be disabled.</p>
                            <ul>
                                <li>You can still view available update versions on the <a href="/wp-admin/options-general.php?page=helperbox&tab=security&check_update_status=true" target="_blank"> Update Status</a> page.</li>
                                <li>To apply updates, disable this option and then check for <a href="/wp-admin/update-core.php" target="_blank"> core, plugin, or theme updates.</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="helperbox_disable_phpexecution_upload_dir">
                            Disable PHP execution through uploads directory
                        </label>
                    </th>
                    <td>
                        <?php
                        $check_nginx = stripos($_SERVER['SERVER_SOFTWARE'] ?? '', 'nginx');
                        if ($check_nginx === 0 && $check_nginx !== false) {
                        ?>
                            <div class="description">
                                <p> Your server is running Nginx.
                                    PHP execution in uploads cannot be disabled automatically.
                                    Please add this rule to your Nginx config:</p>
                                <pre>location ~* ^/wp-content/uploads/.*\.php$ { deny all; }</pre>
                            </div>
                        <?php
                        } else {
                        ?>
                            <input
                                type="checkbox"
                                name="helperbox_disable_phpexecution_upload_dir"
                                id="helperbox_disable_phpexecution_upload_dir"
                                value="1"
                                <?php checked(get_option('helperbox_disable_phpexecution_upload_dir')); ?>>
                            <div class="description">
                                <p> This will disable PHP execution through uploads directory.</p>
                            </div>

                        <?php
                        }

                        ?>
                    </td>
                </tr>
            </table>
        <?php
            //
        }
    }


    /**
     * 
     */
    function helperbox_render_update_status_list() {

        if (!current_user_can('manage_options')) {
            return;
        }
        include_once ABSPATH . 'wp-admin/includes/update.php'; ?>

        <div class="wrap">

            <h2 class="wp-heading-inline">Update Status</h2>
            <hr class="wp-header-end">

            <!-- WordPress Core -->
            <h3>WordPress Core</h3>
            <?php
            // Force refresh
            wp_version_check();
            $core_updates   = get_site_transient('update_core');
            ?>
            <table class="widefat striped">
                <tbody>
                    <tr>
                        <th>Current Version</th>
                        <td><?php echo esc_html($GLOBALS['wp_version']); ?></td>
                    </tr>
                    <?php if (!empty($core_updates->updates[0]->current)) : ?>
                        <tr>
                            <th>Available Version</th>
                            <td><?php echo esc_html($core_updates->updates[0]->current); ?></td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <th>Status</th>
                            <td>Up to date</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Plugins -->
            <h3 style="margin-top:30px;">Plugin Updates</h3>
            <?php
            $installed_plugins = get_plugins();
            wp_update_plugins();
            $plugin_updates = get_site_transient('update_plugins');
            if (!empty($plugin_updates->response)) : ?>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>Plugin</th>
                            <th>Current Version</th>
                            <th>New Version</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plugin_updates->response as $key => $plugin) :
                        ?>
                            <tr>
                                <td><?php echo esc_html($installed_plugins[$key]['Name']); ?></td>
                                <td><?php echo esc_html($installed_plugins[$key]['Version']); ?></td>
                                <td><?php echo esc_html($plugin->new_version); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No plugin updates available.</p>
            <?php endif; ?>

            <!-- Themes -->
            <h3 style="margin-top:30px;">Theme Updates</h3>
            <?php
            $installed_themes = wp_get_themes();
            wp_update_themes();
            $theme_updates  = get_site_transient('update_themes');
            if (!empty($theme_updates->response)) : ?>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>Theme</th>
                            <th>Current Version</th>
                            <th>New Version</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($theme_updates->response as $key => $theme) : ?>
                            <tr>
                                <td><?php echo esc_html($installed_themes[$key]['Name']); ?></td>
                                <td><?php echo esc_html($installed_themes[$key]['Version']); ?></td>
                                <td><?php echo esc_html($theme['new_version']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No theme updates available.</p>
            <?php endif; ?>

            <p style="margin-top:20px; color:#666;">
                Updates are shown for reference only. File modifications are disabled.
            </p>
        </div>
<?php
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

    // Sanitize the selected post types
    function helperbox_sanitize_post_types($input) {
        if (!is_array($input)) return [];
        return array_map('sanitize_text_field', $input);
    }

    /**
     * 
     * ===== END ======
     */
}

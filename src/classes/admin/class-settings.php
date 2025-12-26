<?php

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


    /**
     * construction
     */
    function __construct() {
        add_filter('plugin_action_links_' . helperbox_basename, [$this, 'helperbox_settings_link']);
        add_action('admin_init', [$this, 'helperbox_settings_init']);
        add_action('admin_menu', [$this, 'helperbox_submenu']);

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

        // Sanitize the helperbox_comment_feature as an boolen
        register_setting(
            $settings_option_group,
            'helperbox_comment_feature',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
            ]
        );

        // Sanitize the helperbox_disallow_file as an boolen
        register_setting(
            $settings_option_group,
            'helperbox_disallow_file',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => true,
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
            'General Settings',
            [$this, 'render_helperbox_general_settings_box'],
            'helperbox_settings_page',
            'normal',
            'default'
        ); ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Custom Helper Box</h1>
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
        </div>
    <?php
    }


    /**
     * 
     */
    function render_helperbox_general_settings_box() {

    ?>
        <table class="form-table" role="presentation">
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
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="ptreq_character_limit">Minimun Post Title Character Limit</label>
                </th>
                <td>
                    <?php $option = (int)get_option('ptreq_character_limit');
                    if (!$option) {
                        $option = 100;
                    } ?>
                    <input type="number" name="ptreq_character_limit" id="ptreq_character_limit" value="<?php echo esc_attr($option); ?>" class="regular-text" placeholder="100">
                    <p class="description">Default title character limit is 100.</p>
                </td>
            </tr>
        </table>
<?php
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

<?php

/**
 * Helperbox admin Templates
 *
 * @package helperbox
 * 
 */

namespace Helperbox_Plugin\admin;

use Helperbox_Plugin\User_Role;

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Templates class
 */
class Templates {

    /**
     * 
     */
    public static function get_template_helperbox_settings_group_nav_tab($active_tab = 'general') { ?>
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
    <?php
    }

    /**
     * 
     */
    public static function get_template_helperbox_general_settings_group() {
        settings_fields('helperbox_general_settings_group'); ?>
        <table class="form-table form-table-general" table-tab="general">

            <tr>
                <th scope="row">
                    <label for="helperbox_custom_theme_templates_dir">
                        Custom theme template dir
                    </label>
                </th>
                <td>
                    <?php
                    $value = get_option('helperbox_custom_theme_templates_dir', Settings::CUSTOM_THEME_TEMP_DIR);
                    ?>
                    <input
                        type="text"
                        name="helperbox_custom_theme_templates_dir"
                        id="helperbox_custom_theme_templates_dir"
                        value="<?php echo esc_attr($value); ?>"
                        class="regular-text">
                    <div class="description">
                        <p>
                            This will define the active theme custom template dir.
                            Example <code>app/templates</code>.
                        </p>
                        <p>If empty default root dir will be used.</p>
                        <?php
                        $templates_dir = get_stylesheet_directory() . '/' . trim($value, "/");
                        if (is_dir($templates_dir)) {
                            echo "<p>Theme template is located at template dir:<code>" . str_replace(ABSPATH, '', $templates_dir) . "</code> </p>";
                        } else {
                            echo "<p>Incorrect template dir, There is no such dir:<code>" . str_replace(ABSPATH, '', $templates_dir) . "</code> </p>";
                        }
                        ?>
                        <P>Default: <?php echo esc_html(Settings::CUSTOM_THEME_TEMP_DIR); ?> </P>
                    </div>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="helperbox_user_role_name">
                        Helperbox Client Role Name
                    </label>
                </th>
                <td>
                    <?php
                    $value = get_option('helperbox_user_role_name', User_Role::CLIENT_NAME);
                    ?>
                    <input
                        type="text"
                        name="helperbox_user_role_name"
                        id="helperbox_user_role_name"
                        value="<?php echo esc_attr($value); ?>"
                        class="regular-text">
                    <div class="description">
                        <p>Change client role name.</p>
                        <P>Default: <?php echo esc_html(User_Role::CLIENT_NAME); ?> </P>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="helperbox_load_remote_block_patterns">
                        Should load remote block patterns
                    </label>
                </th>
                <td>
                    <input
                        type="checkbox"
                        name="helperbox_load_remote_block_patterns"
                        id="helperbox_load_remote_block_patterns"
                        value="1"
                        <?php checked(get_option('helperbox_load_remote_block_patterns', '')); ?>>
                    <div class="description">
                        <P>Default: unchecked </P>
                    </div>
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
    <?php
    }

    /**
     * 
     */
    public static function get_template_helperbox_breadcrumb_settings_group() {
        settings_fields('helperbox_breadcrumb_settings_group');
        $breadcrumb_featureminlogin = get_option('helperbox_breadcrumb_feature', Settings::DEFAULT_BREADCRUMB_FEATURE); ?>
        <table class="form-table form-table-breadcrumb" table-tab="breadcrumb">
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
                        <?php checked($breadcrumb_featureminlogin); ?>>
                    <div class="description">
                        <P>Default: unchecked </P>
                    </div>
                </td>
            </tr>
            <?php
            if ($breadcrumb_featureminlogin == '1'):
            ?>
                <tr>
                    <th scope="row">
                        <label for="helperbox_breadcrumb_exclude_post_type">
                            Exclude post type
                        </label>
                    </th>
                    <td>
                        <?php
                        $option = get_option('helperbox_breadcrumb_exclude_post_type', []);
                        $option = is_array($option) ? $option : [];

                        $post_types = get_post_types(['public' => true], 'objects');
                        unset($post_types['attachment']);
                        foreach ($post_types as $post_type) :
                        ?>
                            <label for="post-type-<?php echo esc_attr($post_type->name); ?>">
                                <input
                                    type="checkbox"
                                    name="helperbox_breadcrumb_exclude_post_type[]"
                                    id="post-type-<?php echo esc_attr($post_type->name); ?>"
                                    value="<?php echo esc_attr($post_type->name); ?>"
                                    <?php checked(in_array($post_type->name, $option, true)); ?>>
                                <?php echo esc_html($post_type->label); ?>
                            </label>
                        <?php endforeach; ?>

                        <div class="description">
                            <p>This will exclude breadcrumbs for the selected post types.</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="helperbox_breadcrumb_exclude_post_slug">
                            Exclude Post Items
                        </label>
                    </th>
                    <td>
                        <textarea
                            id="helperbox_breadcrumb_exclude_post_slug"
                            name="helperbox_breadcrumb_exclude_post_slug"
                            rows="5"
                            cols="50"
                            class="regular-text"><?php echo esc_textarea(get_option('helperbox_breadcrumb_exclude_post_slug', '')); ?>
                        </textarea>
                        <div class="description">
                            <p>
                                This will exclude breadcrumbs for the selected post item slugs.
                                Enter one post slug per line.
                            </p>
                            <p>
                                Example value:
                                <?php echo "<pre>regional-overview" . "\n" . "digital-id-region</pre>" ?>
                            </p>
                        </div>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="helperbox_breadcrumb_remove_condition">
                            Remove breadcrumb based on given conditions.
                        </label>
                    </th>
                    <td>
                        <?php
                        $breadcrumb_remove_condition = get_option('helperbox_breadcrumb_remove_condition', '');
                        $placeholderRemoveCondition = [
                            'url' => [
                                '/url/path...'
                            ],
                            'post_type' => [
                                'post_type_to_check...' => [
                                    [
                                        'meta_key' => 'meta_key_to_check...',
                                        'meta_value' => 'meta_value_to_check...',
                                    ]
                                ]
                            ]
                        ];
                        $exampleValue = [
                            'post_type' => [
                                'region' => [
                                    [
                                        'meta_key' => 'type_of_region',
                                        'meta_value' => 'region',
                                    ],
                                    [
                                        'meta_key' => 'type_of_region',
                                        'meta_value' => 'sub-region',
                                    ]
                                ],
                                'digital-id' => [
                                    [
                                        'meta_key' => 'type_of_region',
                                        'meta_value' => 'region',
                                    ],
                                    [
                                        'meta_key' => 'type_of_region',
                                        'meta_value' => 'sub-region',
                                    ]
                                ]
                            ]
                        ];
                        ?>
                        <textarea
                            id="helperbox_breadcrumb_remove_condition"
                            name="helperbox_breadcrumb_remove_condition"
                            style="display: none;">
                            <?php echo esc_textarea(trim($breadcrumb_remove_condition)); ?>
                        </textarea>
                        <div id="helperbox_breadcrumb_remove_condition_editor"><?php echo esc_textarea(trim($breadcrumb_remove_condition)); ?></div>
                        <br>
                        <p class="button" id="set_jsonformat_remove_condition"> Format JSON value</p>
                        <div class="description">
                            <p>This will exclude/remove breadcrumbs based on the given conditions pages.</p>
                            <p>Enter conditions in <strong>valid JSON format as:</strong>.</p>
                            <pre>
<?php echo json_encode($placeholderRemoveCondition, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
                            </pre>
                            <p>Example value:</p>
                            <pre>
<?php echo json_encode($exampleValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>
                            </pre>

                            <p>To display breadcrumb:</p>
                            <ol>
                                <li>
                                    Using shortcode
                                    <pre>do_shortcode('[helperbox_breadcrumb_shortcode]');</pre>
                                </li>
                                <li>
                                    Using PHP function
                                    <pre>\Helperbox_Plugin\Breadcrumb::get_helperbox_breadcrumb();</pre>
                                </li>
                            </ol>
                        </div>

                    </td>
                </tr>



            <?php
            endif;
            ?>
        </table>
    <?php
    }

    /**
     * 
     */
    public static function get_template_helperbox_adminlogin_settings_group() {
        settings_fields('helperbox_adminlogin_settings_group');
        $custom_adminlogin = get_option('helperbox_custom_adminlogin', Settings::DEFAULT_CUSTOM_LOGINADMIN); ?>
        <table class="form-table form-table-adminlogin" table-tab="adminlogin">
            <tr class="tr-helperbox_custom_adminlogin">
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
                        <div class="description">
                            <P>Default: checked </P>
                        </div>
                    </div>
                </td>
            </tr>
            <?php
            if ($custom_adminlogin == '1'):
            ?>
                <tr class="tr-helperbox_adminlogin_bgcolor">
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
                            value="<?php echo esc_attr(get_option('helperbox_adminlogin_bgcolor', Settings::DEFAULT_LOGIN_BG)); ?>"
                            class="helperbox-color-picker" />
                        <p class="description">
                            Choose the background color for the login page. Default: <?php echo Settings::DEFAULT_LOGIN_BG; ?>
                        </p>
                    </td>
                </tr>
                <tr class="tr-helperbox_adminlogin_bgimages">
                    <th scope="row">
                        <label for="helperbox_adminlogin_bgimages">Background Images</label>
                    </th>
                    <td>
                        <div class="helperbox-bg-images-upload">

                            <div class="helperbox-media-preview helperbox_adminlogin_bgimages-preview">
                                <?php
                                $image_ids = get_option('helperbox_adminlogin_bgimages', []);
                                $image_ids = is_array($image_ids) ? $image_ids : [];
                                foreach ($image_ids as $image_id) {
                                    echo "<div class='selected-image selected-image-" . $image_id . "' >";
                                    echo wp_get_attachment_image(
                                        $image_id,
                                        'thumbnail',
                                        false,
                                        [
                                            'style' => 'margin:5px;'
                                        ]
                                    );
                                    echo '<input type="hidden" name="helperbox_adminlogin_bgimages[]" value="' . esc_attr($image_id) . '" />';
                                    echo '<a href="#" class="remove-image button button-secondary button-small" data-attachment-id="' . esc_attr($image_id) . '" title="Remove image">×</a>';
                                    echo "</div>";
                                }
                                ?>
                            </div>
                            <p>
                                <button type="button" class="button button-secondary" id="helperbox_adminlogin_bgimages_addBtn" field-name="helperbox_adminlogin_bgimages">Upload / Select Image</button>
                                <button type="button" class="button button-link-delete helperbox-delete-all-media" field-name="helperbox_adminlogin_bgimages" style="display: none;">Remove All</button>
                            </p>
                            <p class="description">
                                Select images to use as background on the login page.
                            </p>
                        </div>
                    </td>
                </tr>
                <tr class="tr-helperbox_adminlogin_logo">
                    <th scope="row">
                        <label for="helperbox_adminlogin_logo">Login Form Logo</label>
                    </th>
                    <td>
                        <div class="helperbox-bg-images-upload">

                            <div class="helperbox-media-preview helperbox_adminlogin_logo-preview">
                                <?php
                                $image_ids = get_option('helperbox_adminlogin_logo', []);
                                $image_ids = is_array($image_ids) ? $image_ids : [];
                                foreach ($image_ids as $image_id) {
                                    echo "<div class='selected-image selected-image-" . $image_id . "' >";
                                    echo wp_get_attachment_image(
                                        $image_id,
                                        'thumbnail',
                                        false,
                                        [
                                            'style' => 'margin:5px;'
                                        ]
                                    );
                                    echo '<input type="hidden" name="helperbox_adminlogin_logo[]" value="' . esc_attr($image_id) . '" />';
                                    echo '<a href="#" class="remove-image button button-secondary button-small" data-attachment-id="' . esc_attr($image_id) . '" title="Remove image">×</a>';
                                    echo "</div>";
                                }
                                ?>
                            </div>
                            <p>
                                <button type="button" class="button button-secondary" id="helperbox_adminlogin_logo_addBtn" field-name="helperbox_adminlogin_logo">Upload / Select Image</button>
                                <button type="button" class="button button-link-delete helperbox-delete-all-media " field-name="helperbox_adminlogin_logo" style="display: none;">Remove All</button>
                            </p>
                            <p class="description">
                                Select images to use as login page logo. If empty logo defined in logo from theme will be used.
                            </p>
                        </div>
                    </td>
                </tr>
                <tr class="tr-helperbox_adminlogin_formbgcolor">
                    <th scope="row">
                        <label for="helperbox_adminlogin_formbgcolor">
                            Form Background Color
                        </label>
                    </th>
                    <td>
                        <input
                            type="text"
                            name="helperbox_adminlogin_formbgcolor"
                            id="helperbox_adminlogin_formbgcolor"
                            value="<?php echo esc_attr(get_option('helperbox_adminlogin_formbgcolor', Settings::DEFAULT_FORMLOGIN_BG)); ?>"
                            class="helperbox-color-picker" />
                        <p class="description">
                            Choose the background color for the login Form. Default: <?php echo Settings::DEFAULT_FORMLOGIN_BG; ?>
                        </p>
                    </td>
                </tr>
            <?php
            endif;
            ?>
        </table>
    <?php
    }


    /**
     * 
     */
    public static function get_template_helperbox_security_settings_group() {
        settings_fields('helperbox_security_settings_group'); ?>
        <table class="form-table form-table-security" table-tab='security'>
            <tr>
                <th scope="row">
                    <label for="helperbox_disable_comment_feature">
                        Disable comment feature completely
                    </label>
                </th>
                <td>
                    <input
                        type="checkbox"
                        name="helperbox_disable_comment_feature"
                        id="helperbox_disable_comment_feature"
                        value="1"
                        <?php checked(get_option('helperbox_disable_comment_feature', '1')); ?>>
                    <div class="description">
                        <p>This will remove edit-comments.php page and close comments feature completely.</p>
                        <P>Default: checked </P>
                    </div>
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
                    <div class="description">
                        <p>This will disable REST API for unauthenticated user. if "_nonce" is verified, it won't restrict.</p>
                        <P>Default: checked </P>
                    </div>

                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="helperbox_restapi_url_prefix">
                        REST API URL prefix
                    </label>
                </th>
                <td>
                    <?php
                    $value = get_option('helperbox_restapi_url_prefix', 'api');
                    ?>
                    <input
                        type="text"
                        name="helperbox_restapi_url_prefix"
                        id="helperbox_restapi_url_prefix"
                        value="<?php echo esc_attr($value); ?>"
                        class="regular-text">
                    <div class="description">
                        <p>This will change the REST API URL prefix from default value "wp-json" to new custom value <?php echo esc_html($value); ?></p>
                        <p>If empty default value "wp-json" will be used.</p>
                        <P>Default: api </P>
                    </div>
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
                        <P>Default: checked </P>
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
                            <?php checked(get_option('helperbox_disable_phpexecution_upload_dir', '1')); ?>>
                        <div class="description">
                            <p> This will disable PHP execution through uploads directory.</p>
                        </div>

                    <?php
                    }

                    ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="helperbox_disable_emojicons">
                        Disable emojicons
                    </label>
                </th>
                <td>
                    <input
                        type="checkbox"
                        name="helperbox_disable_emojicons"
                        id="helperbox_disable_emojicons"
                        value="1"
                        <?php checked(get_option('helperbox_disable_emojicons', '1')); ?>>
                    <div class="description">
                        <p>
                            This will disable wp emojicons.
                        </p>
                        <P>Default: checked </P>
                    </div>

                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="helperbox_disable_emojicons">
                        Other Setting that are applied by default:
                    </label>
                </th>
                <td>
                    <ol>
                        <li>Hide wordpress version</li>
                        <li>Nonce life to 30 min</li>
                        <li>Disable xmlrpc and pingback</li>
                        <li>
                            Added header protection like:
                            <code>
                                header('X-FRAME-OPTIONS: SAMEORIGIN');
                                header('X-XSS-Protection: 1; mode=block');
                                header("Content-Security-Policy: frame-ancestors 'self';");
                                header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
                            </code>
                        </li>
                        <li>
                            Modified top admin bar menu:
                            <ol>
                                <li>Customizer and New content button are removed</li>
                            </ol>
                        </li>
                        <li>Remove dashboard widgets like: Activity, Quick Draft, Wordpress Event and News, and other</li>
                    </ol>
                </td>
            </tr>

        </table>
    <?php
    }


    /**
     * 
     */
    public static function get_template_helperbox_available_update_list() {

        if (!current_user_can('manage_options')) {
            return;
        }

        include_once ABSPATH . 'wp-admin/includes/update.php';
        // core
        wp_version_check();
        $core_updates   = get_site_transient('update_core');
        // plugin
        $installed_plugins = get_plugins();
        wp_update_plugins();
        $plugin_updates = get_site_transient('update_plugins');
        // theme
        $installed_themes = wp_get_themes();
        wp_update_themes();
        $theme_updates  = get_site_transient('update_themes');    ?>
        <div class="wrap">

            <h2 class="wp-heading-inline">Available Update Versions Status</h2>
            <hr class="wp-header-end">

            <!-- WordPress Core -->
            <h3>WordPress Core</h3>
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
            if (!empty($plugin_updates->response)) : ?>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>S.N.</th>
                            <th>Plugin</th>
                            <th>Current Version</th>
                            <th>New Version</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $countplugin = 0;
                        foreach ($plugin_updates->response as $key => $plugin) :
                            $countplugin++;
                        ?>
                            <tr>
                                <td><?php echo esc_html($countplugin); ?></td>
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
            if (!empty($theme_updates->response)) : ?>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>S.N.</th>
                            <th>Theme</th>
                            <th>Current Version</th>
                            <th>New Version</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counttheme = 0;
                        foreach ($theme_updates->response as $key => $theme) :
                            $counttheme++;
                        ?>
                            <tr>
                                <td><?php echo esc_html($counttheme); ?></td>
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
     * 
     */
    public static function get_template_notification_update_status_count() {
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
     * 
     */
    public static function get_template_notification_file_mod_disable() {
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
    /**
     * ==== END ====
     */
}

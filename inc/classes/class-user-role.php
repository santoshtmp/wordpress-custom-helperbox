<?php

/**
 * Helperbox User Role
 *
 * @package helperbox
 * 
 */

namespace Helperbox_Plugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * main class
 * 
 */
class User_Role {

    public const CLIENT_NAME = 'Helperbox Editor';


    /**
     * @var string $client_role
     */
    public static $client_role = 'client_role';

    /**
     * @var string helperbox_role_display_name
     */
    public static $helperbox_role_display_name = SELF::CLIENT_NAME;

    /**
     * construction
     */
    function __construct() {
        // 
        add_action('init', [$this, 'add_client_role'], 10);
        add_action('admin_head', [$this, 'admin_head_action'], 11);
        add_action('admin_menu', [$this, 'admin_menu_action'], 999999);
        add_filter('acf/settings/show_admin', [$this, 'acf_show_admin_filter'], 10, 1);

        // 
        $roleName = get_option('helperbox_user_role_name', '');
        if ($roleName) {
            self::$helperbox_role_display_name = $roleName;
        }
    }
    /**
     * Get client role display name
     * 
     * @return string
     */
    public static function get_helperbox_role_display_name() {
        return self::$helperbox_role_display_name;
    }

    /**
     * Add client role
     */
    public static function add_client_role() {
        $role = self::$client_role;
        $role_display_name = self::get_helperbox_role_display_name();
        add_role(
            $role,
            $role_display_name,
            array(
                'read' => true,
                'edit_posts' => true,
                'upload_files' => true,
            ),
        );
        // 
        self::add_client_role_capabilities($role);
        // 
        self::change_role_display_name($role, $role_display_name);
    }

    /**
     * Add capabilities to client role
     * 
     * @param string $role
     */
    public static function add_client_role_capabilities($role) {

        $role = get_role($role);

        // = general =
        $role->add_cap('moderate_comments', true);
        $role->add_cap('manage_links', true);
        $role->add_cap('manage_options', true);

        // = posts =
        $role->add_cap('edit_others_posts', true);
        $role->add_cap('delete_posts', true);
        $role->add_cap('publish_posts', true);
        $role->add_cap('read_private_posts', true);
        $role->add_cap('delete_private_posts', true);
        $role->add_cap('delete_published_posts', true);
        $role->add_cap('delete_others_posts', true);
        $role->add_cap('edit_private_posts', true);
        $role->add_cap('edit_published_posts', true);

        // = pages =
        $role->add_cap('edit_pages', true);
        $role->add_cap('edit_others_pages', true);
        $role->add_cap('delete_pages', true);
        $role->add_cap('publish_pages', true);
        $role->add_cap('read_private_pages', true);
        $role->add_cap('delete_private_pages', true);
        $role->add_cap('delete_published_pages', true);
        $role->add_cap('delete_others_pages', true);
        $role->add_cap('edit_private_pages', true);
        $role->add_cap('edit_published_pages', true);

        // = taxonomies =
        $role->add_cap('manage_categories', true);

        // = appearance =
        $role->add_cap('edit_theme_options', true);

        // === users ===
        $role->add_cap('list_users', true);
        $role->add_cap('create_users', true);
        $role->add_cap('delete_users', true);
        $role->add_cap('promote_users', true);
        $role->add_cap('remove_users', true);

        // wp-rocket capabilities
        $role->add_cap('rocket_edit_cache', true);
        $role->add_cap('rocket_manage_options', true);
        $role->add_cap('rocket_purge_cache', true);
        $role->add_cap('rocket_purge_posts', true);
        $role->add_cap('rocket_purge_terms', true);
        $role->add_cap('rocket_purge_users', true);
        $role->add_cap('rocket_manage_cache');

        // 
        $sitekit_capabilities = [
            'googlesitekit_read_shared_module_data',
            'googlesitekit_manage_options',
            'googlesitekit_authenticate',
            'googlesitekit_setup',
            'view_sitekit_dashboard',
            'VIEW_WP_DASHBOARD_WIDGET',
            'VIEW_ADMIN_BAR_MENU'
        ];

        foreach ($sitekit_capabilities as $capability) {
            if (!$role->has_cap($capability)) {
                $role->add_cap($capability);
            }
        }
    }

    /**
     * Change role display name
     * 
     * @param string $role
     * @param string $role_display_name
     */
    public static function change_role_display_name($role, $role_display_name) {
        global $wp_roles;

        if (isset($wp_roles)) {
            $roles = $wp_roles->roles;
            if (isset($roles[$role])) {
                $roles[$role]['name'] = $role_display_name;
                $wp_roles->roles = $roles;
            }
        }
    }


    /**
     * Admin head action
     * 
     * @return void
     */
    public function admin_head_action() {
        //Hide admin notices for client role
        $current_user = wp_get_current_user();
        if (in_array(self::$client_role, (array) $current_user->roles)) {
            remove_all_actions('admin_notices');
        }
    }

    /**
     * Admin menu action
     * 
     * @return void
     */
    public function admin_menu_action() {
        $current_user = wp_get_current_user();
        // Check if the user has the client role
        if (!isset(self::$client_role) || !isset($current_user->roles)) {
            return;
        }
        // If the user has the client role, remove unnecessary admin pages
        if (empty($current_user->roles)) {
            return;
        }
        // Check if the user has the client role
        if (in_array(self::$client_role, (array) $current_user->roles)) {
            // === remove unnecessary admin pages ===//
            remove_menu_page('options-general.php');
            remove_menu_page('edit.php?post_type=acf-field-group');
            remove_menu_page('tools.php');
            remove_menu_page('users.php');
            remove_menu_page('themes.php');
            remove_menu_page('maxmegamenu');
            remove_menu_page('analogwp_templates');
            remove_menu_page('happy-addons');
            remove_menu_page('ai1wm_export');
            remove_menu_page('postman');
            remove_menu_page('members-settings');
            remove_menu_page('rank-math');
            // remove_submenu_page('rank-math', 'rank-math-status');
        }
    }

    /**
     * ACF show admin filter
     * 
     * @param bool $show
     * @return bool
     */
    public function acf_show_admin_filter($show) {
        return current_user_can('administrator');
        // // If the user has the client role, hide ACF admin
        // $current_user = wp_get_current_user();
        // if (in_array(self::$client_role, (array) $current_user->roles)) {
        //     return false; // Hide ACF admin for client role
        // }
        // return $show; // Show ACF admin for other roles
    }

    // ========= END ==========
}

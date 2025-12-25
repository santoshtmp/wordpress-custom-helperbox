<?php
namespace wp_helperbox;

use WP_Error;

/**
 *  
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


if (! class_exists('helperbox_securities')) {
    /**
     * helperbox_securities')) { main class
     * 
     */
    class helperbox_securities {

        /**
         * construction
         */
        function __construct() {
            // 
            add_action('send_headers', [$this, 'header_protection'], 10);
            add_filter('rest_authentication_errors',  [$this, 'lib_rest_authentication_auth']);
            add_action('admin_bar_menu', [$this, 'modify_top_admin_bar_menu'], 99);
            add_action('wp_dashboard_setup', [$this, 'remove_dashboard_widgets'], 99999);

            $this->lib_disable_comment_feature();
            /**
             * =========================================
             * nonce_life
             * https://developer.wordpress.org/reference/hooks/nonce_life/
             *=========================================
             */
            add_filter('nonce_life', function () {
                return 30 * MINUTE_IN_SECONDS;
            });
            /**
             *==============================
             * Hide wordpress version
             *==============================
             */
            add_filter('the_generator', '__return_empty_string');
            remove_action('wp_head', 'wp_generator');

            /**
             * =========================================================
             * Disable RSS, RSD, WLWManifest
             * https://developer.wordpress.org/reference/hooks/rss_version/
             * https://developer.wordpress.org/reference/hooks/rsd_link/
             * https://developer.wordpress.org/reference/hooks/wlwmanifest_link/
             * =========================================================
             */
            remove_action('wp_head', 'wlwmanifest_link');


            /**
             *==============================
             * Disable xmlrpc and pingback
             *==============================
             */
            add_filter('xmlrpc_enabled', '__return_false');
            add_filter('wp_headers', function ($headers) {
                unset($headers['X-Pingback']);
                return $headers;
            });

            // Disable Self Pingbacks
            add_action('pre_ping', function (&$links) {
                $home = get_option('home');
                foreach ($links as $l => $link) {
                    if (0 === strpos($link, $home)) {
                        unset($links[$l]);
                    }
                }
            });
        }

        /**
         * =========================================================
         * header protection
         * https://developer.wordpress.org/reference/hooks/send_headers/
         * Add header to stop site loading in an iFrame.
         * =========================================================
         **/
        function header_protection() {
            header('X-FRAME-OPTIONS: SAMEORIGIN');
            header('Referrer-Policy: strict-origin-when-cross-origin');
            header('X-Content-Type-Options: nosniff');
            header('X-XSS-Protection: 1; mode=block');
            header("Content-Security-Policy: frame-ancestors 'self';");
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
            header("Permissions-Policy: geolocation=(self), microphone=(), camera=(), payment=(), fullscreen=*");
            header("X-Permitted-Cross-Domain-Policies: none");
            // header("Access-Control-Allow-Origin: " . home_url());
        }


        /**
         * decryptValue
         * https://www.php.net/manual/en/function.openssl-encrypt.php
         */
        function decryptValue($value) {
            $encryptionAlgorithm = "AES-256-CBC";
            $openssl_decrypt_key = "3uPSO9hQ/2KgLJ5iJXU03Lhaef5SWT4YghGtZGC43AExF6/eLagf2OeB3E1";
            $openssl_iv_key = ")]KK[P2Qv7G!9p-a";
            $decryptedValue = openssl_decrypt($value, $encryptionAlgorithm, $openssl_decrypt_key, 0, $openssl_iv_key);

            return $decryptedValue;
        }

        /**
         * encryptValue
         * https://www.php.net/manual/en/function.openssl-encrypt.php
         */
        function encryptValue($value) {
            $encryptionAlgorithm = "AES-256-CBC";
            $openssl_decrypt_key = "3uPSO9hQ/2KgLJ5iJXU03Lhaef5SWT4YghGtZGC43AExF6/eLagf2OeB3E1";
            $openssl_iv_key = ")]KK[P2Qv7G!9p-a";

            return openssl_encrypt($value, $encryptionAlgorithm, $openssl_decrypt_key, 0, $openssl_iv_key);
        }


        /**
         * lib_rest_authentication_auth
         * Disable rest api for not logged in user if the _nonce is not verified
         * https://developer.wordpress.org/reference/hooks/rest_authentication_errors/
         */
        function lib_rest_authentication_auth($access) {
            if (true === $access || is_wp_error($access)) {
                return $access;
            }

            $_nonce = (isset($_REQUEST['_nonce'])) ? $_REQUEST['_nonce'] : '';
            if (!wp_verify_nonce($_nonce, home_url(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)))) {
                if (!is_user_logged_in()) {
                    return new WP_Error(
                        'rest_disabled',
                        __('The WordPress REST API has been disabled.'),
                        array(
                            'status' => rest_authorization_required_code(),
                        )
                    );
                }
            }
        }


        /**
         * lib_disable_comment_feature
         * Disable comment feature completely
         * https://developer.wordpress.org/reference/hooks/comments_open/
         * https://developer.wordpress.org/reference/hooks/pings_open/
         * https://developer.wordpress.org/reference/hooks/comment_form_default_fields/
         */
        function lib_disable_comment_feature() {
            add_action('admin_init', function () {
                // // Redirect any user trying to access comments page
                global $pagenow;
                if ($pagenow === 'edit-comments.php') {
                    wp_safe_redirect(admin_url());
                    exit;
                }
                // Remove comments metabox from dashboard
                remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

                // Disable support for comments and trackbacks in post types
                foreach (get_post_types() as $post_type) {
                    if ($post_type != 'update') {
                        if (post_type_supports($post_type, 'comments')) {
                            remove_post_type_support($post_type, 'comments');
                            remove_post_type_support($post_type, 'trackbacks');
                        }
                    }
                }

                // Remove comments page in menu
                remove_menu_page('edit-comments.php');
            });

            // // Close comments on the front-end
            add_filter('comments_open', '__return_false', 20, 2);
            add_filter('pings_open', '__return_false', 20, 2);

            // // Hide existing comments
            add_filter('comments_array', '__return_empty_array', 10, 2);

            // Remove comments links from admin bar
            add_action('init', function () {
                if (is_admin_bar_showing()) {
                    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
                }
            });

            // Disable Comment Form Website URL
            add_filter('comment_form_default_fields', function ($fields) {
                if (isset($fields['url'])) {
                    unset($fields['url']);
                }
                return $fields;
            }, 150);
        }

        /**
         * ================================================
         * https://developer.wordpress.org/reference/classes/wp_admin_bar/add_menu/
         * https://developer.wordpress.org/reference/classes/wp_admin_bar/remove_menu/
         * ================================================
         */
        function modify_top_admin_bar_menu($wp_admin_bar) {
            $wp_admin_bar->remove_menu('customize');
            $wp_admin_bar->remove_node('updates');
            $wp_admin_bar->remove_menu('comments');
            $wp_admin_bar->remove_node('new-content');
        }


        /**
         * ================================================
         * Remove dashboard widgets
         * https://developer.wordpress.org/reference/hooks/wp_dashboard_setup/
         * https://developer.wordpress.org/reference/functions/unregister_widget/
         * ================================================
         */
        function remove_dashboard_widgets() {
            global $wp_meta_boxes;

            unset($wp_meta_boxes['dashboard']['normal']['core']['happy_addons_news_update']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['e-dashboard-overview']);
            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);

            unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
            unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
        }
    }
}

// Instantiate the class
if (class_exists('helperbox_securities')) {
    new helperbox_securities();
} else {
    error_log('helperbox_securities class does not exist.');
}
// End of class helperbox_securities




/**
 * =========================================================
 * Hide style and script version 
 * https://developer.wordpress.org/reference/hooks/style_loader_src/
 * https://developer.wordpress.org/reference/hooks/script_loader_src/
 * =========================================================
 */
function remove_version_from_scripts_styles($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'remove_version_from_scripts_styles', 9999);
add_filter('script_loader_src', 'remove_version_from_scripts_styles', 9999);



/**
 * =========================================================
 * Disable plugin installation, update
 * To re-enable plugin installations or updates need to remove disable_plugin_modifications
 * =========================================================
 */
// add_action('admin_init', 'disable_plugin_modifications');
function disable_plugin_modifications() {
    // Disable plugin and theme editor
    define('DISALLOW_FILE_EDIT', true);

    // Disable plugin and theme installation, updates, and deletion
    define('DISALLOW_FILE_MODS', true);
}

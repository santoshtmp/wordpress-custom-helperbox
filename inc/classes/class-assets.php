<?php

/**
 * Helperbox Enqueue assets
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

class Assets {

    /**
     * construction
     */
    function __construct() {
        add_action('init', [$this, 'register_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 11);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts'], 20);
        add_action('login_enqueue_scripts', [$this, 'login_scripts'], 10);
        add_filter('login_headerurl', [$this, 'logo_url'], 10);
    }

    /**
     * 
     */
    function register_scripts() {
    }

    /**
     * 
     */
    function enqueue_scripts() {
    }

    /**
     * Admin script
     * 
     */
    public function admin_scripts($hook) {

        if (file_exists(helperbox_path . 'assets/build/css/admin.css')) {
            wp_enqueue_style(
                'helperbox-admin-style',
                helperbox_url . 'assets/build/css/admin.css',
                [],
                null,
                'screen'
            );
        }

        if (file_exists(helperbox_path . 'assets/build/js/admin.js')) {
            wp_enqueue_script(
                'helperbox-admin-script',
                helperbox_url . 'assets/build/js/admin.js',
                ['jquery', 'wp-color-picker', 'media-views'], // Dependencies
                filemtime(helperbox_path . 'assets/build/js/admin.js'),
                array(
                    'strategy' => 'defer',
                    'in_footer' => true,
                )
            );
        }

        // Only load on your settings page
        if ('settings_page_helperbox' == $hook) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');

            // Enqueue media uploader
            // wp_enqueue_media();

            // Initialize the color picker
            wp_add_inline_script(
                'helperbox-admin-script',
                '
                    jQuery(document).ready(function($){
                        $("#helperbox_adminlogin_bgcolor").wpColorPicker();
                    });
                '
            );
        }

        // localize script 
        wp_localize_script('helperbox-admin-script', 'helperboxAjax', [
            'hook' => $hook,
        ]);
    }

    /**
     * Login scripts
     * 
     * @return void
     */
    function login_scripts() {
        $loginstyle = "";

        $custom_logo_id = get_theme_mod('custom_logo');
        $url = $custom_logo_id ? wp_get_attachment_image_src($custom_logo_id, 'full') : '';
        if ($url) {
            $loginstyle .= "
                .helperbox-login #login h1 a,
                .login h1 a {
                    background-image: url('" . $url[0] . "');
                }
            ";
        }

        $bgcolor = get_option('helperbox_adminlogin_bgcolor', Settings::DEFAULT_LOGIN_BG);
        $loginstyle .= "
            body.login{
                  background-color: " . esc_attr($bgcolor) . " !important;
            }
        ";

        echo "<style type='text/css'>" . $loginstyle . "</style>";

        if (file_exists(helperbox_path . '/assets/build/css/login.css')) {
            wp_enqueue_style(
                'helperbox-login',
                helperbox_url . '/assets/build/css/login.css'
            );
        }

        if (file_exists(helperbox_path . '/assets/build/js/login.js')) {
            wp_enqueue_script(
                'helperbox-login',
                helperbox_url . '/assets/build/js/login.js',
                ['jquery'],
                filemtime(helperbox_path . '/assets/build/js/login.js'),
                array(
                    'strategy' => 'defer',
                    'in_footer' => true,
                )
            );
        }
    }

    /**
     * Logo URL
     * 
     * @return string Home URL
     */
    function logo_url() {
        return home_url();
    }
}

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
                ['jquery', 'wp-color-picker', 'media-views'],
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
            // https://developer.wordpress.org/reference/functions/wp_enqueue_media/
            wp_enqueue_media();

            // Initialize the color picker
            wp_add_inline_script(
                'helperbox-admin-script',
                '
                    jQuery(document).ready(function($){
                        $("#helperbox_adminlogin_bgcolor").wpColorPicker();
                    });
                '
            );
            // localize script 
            wp_localize_script('helperbox-admin-script', 'helperboxJS', [
                'settings_page_helperbox' => true,
            ]);
        }
    }

    /**
     * Login scripts
     * 
     * @return void
     */
    function login_scripts() {
        $loginstyle = "";

        // logo
        $custom_logo_id = get_theme_mod('custom_logo');
        $url = $custom_logo_id ? wp_get_attachment_image_src($custom_logo_id, 'full') : '';
        if ($url) {
            $loginstyle .= ".helperbox-login #login h1 a, .login h1 a { background-image: url('" . $url[0] . "'); } ";
            $loginstyle .= "\n";
        }

        // BG color
        $bgcolor = get_option('helperbox_adminlogin_bgcolor', Settings::DEFAULT_LOGIN_BG);
        $loginstyle .= ".helperbox-login-style{ background-color: " . esc_attr($bgcolor) . " !important; } ";

        // BG images
        $image_ids = get_option('helperbox_adminlogin_bgimages', []);
        $image_ids = is_array($image_ids) ? $image_ids : [];
        if ($image_ids) {
            $styleImageURL = [];
            foreach ($image_ids as $image_id) {
                $imageURL = wp_get_attachment_image_url($image_id, 'large');
                $styleImageURL[] = 'url("' . $imageURL . '")';
            }
            $loginstyle .= "\n";
            $loginstyle .= ".helperbox-login-style{ background-image: " . implode(", ", $styleImageURL) . " !important; background-size: cover;  }";
        }

        // Add style css
        if (file_exists(helperbox_path . '/assets/build/css/login.css')) {
            wp_enqueue_style(
                'helperbox-login',
                helperbox_url . '/assets/build/css/login.css'
            );
        }

        // Add script js
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

        // Add setting login style
        wp_add_inline_style('helperbox-login', $loginstyle);

        // Add body class
        add_filter('login_body_class', function ($classes) {
            $classes[] = 'helperbox-login-style';
            return $classes;
        });
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

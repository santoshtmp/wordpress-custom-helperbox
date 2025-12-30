<?php

/**
 * Helperbox Enqueue assets
 *
 * @package helperbox
 * 
 */

namespace Helperbox_Plugin;

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
    public function admin_scripts() {
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
                ['jquery'],
                filemtime(helperbox_path . 'assets/build/js/admin.js'),
                array(
                    'strategy' => 'defer',
                    'in_footer' => true,
                )
            );
        }
    }

    /**
     * Login scripts
     * 
     * @return void
     */
    function login_scripts() {
        $custom_logo_id = get_theme_mod('custom_logo');
        $url = $custom_logo_id ? wp_get_attachment_image_src($custom_logo_id, 'full') : '';
        if ($url) {
            echo "
            <style type='text/css'>
                #login h1 a,
                .login h1 a {
                    background-image: url('" . $url[0] . "');
                }
            </style>
            ";
        }

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

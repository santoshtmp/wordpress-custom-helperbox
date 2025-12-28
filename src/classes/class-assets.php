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
        $admin_style = helperbox_url . '/assets/build/admin/admin.css';
        $admin_script = helperbox_url . '/assets/build/admin/admin.js';
        if (file_exists($admin_style)) {
            wp_enqueue_style(
                'helperbox-admin-style',
                $admin_style,
                [],
                null,
                'screen'
            );
        }
        if (file_exists($admin_style)) {
            wp_enqueue_script(
                'helperbox-admin-script',
                $admin_script,
                array('jquery'),
                filemtime($admin_script),
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
        $login_style = helperbox_url . '/assets/build/login/login.css';
        $login_script = helperbox_url . '/assets/build/login/login.js';
        if (file_exists($login_style)) {
            wp_enqueue_style('helperbox-login', $login_style);
        }
        if (file_exists($login_script)) {
            wp_enqueue_script(
                'helperbox-login',
                $login_script,
                array('jquery'),
                filemtime($login_script),
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

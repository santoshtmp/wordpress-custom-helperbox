<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}


if (! class_exists('YIPL_EXTRA')) {
    /**
     * YIPL_EXTRA main class
     * 
     */
    class YIPL_EXTRA {

        /**
         * construction
         */
        function __construct() {
            // 
            add_action('init', [$this, 'init_action'], 10);
            add_action('login_enqueue_scripts', [$this, 'login_logo'], 10);
            add_filter('login_headerurl', [$this, 'logo_url'], 10);
            add_filter('theme_page_templates', [$this, 'register_page_templates']);
            add_filter('page_template_hierarchy', [$this, 'page_template_to_subdir']);
        }
        /**
         * Init action
         * 
         * @return void
         */
        public function init_action() {
            // Disable emojis
            $this->disable_wp_emojicons();
        }

        /**
         * Login logo
         * 
         * @return void
         */
        function login_logo() {
            $custom_logo_id = get_theme_mod('custom_logo');
            $url = $custom_logo_id ? wp_get_attachment_image_src($custom_logo_id, 'full') : '';
            wp_enqueue_style('yi-login', get_stylesheet_directory_uri() . '/assets/login/login.css');

            if ($url) { ?>
                <style type="text/css">
                    #login h1 a,
                    .login h1 a {
                        background-image: url('<?php echo $url[0]; ?>');
                    }
                </style>
<?php
            }

            wp_enqueue_script(
                'custom-login',
                get_stylesheet_directory_uri() . '/assets/login/login.js',
                array('jquery'),
                filemtime(get_stylesheet_directory() . '/assets/login/login.js'),
                array(
                    'strategy' => 'defer',
                    'in_footer' => true,
                )
            );
        }

        /**
         * Logo URL
         * 
         * @return string Home URL
         */
        function logo_url() {
            return home_url();
        }

        /**
         * ==============================
         * https://developer.wordpress.org/reference/hooks/theme_page_templates/ 
         * https://developer.wordpress.org/themes/template-files-section/page-template-files/
         * https://www.wpexplorer.com/wordpress-page-templates-plugin/
         * @param array $post_templates Array of page templates. Keys are filenames, values are translated names.
         * @return array Filtered array of page templates.
         * ==============================
         */
        function register_page_templates($post_templates) {
            $app_templates = 'app/templates';
            $app_templates_path = get_stylesheet_directory() . '/' . $app_templates;
            $template_files = scandir($app_templates_path);
            foreach ($template_files as $filename) {
                if ($filename === '.' || $filename === '..') {
                    continue;
                }
                $path_info = pathinfo($filename);
                if ($path_info['extension'] === 'php') {
                    $full_path = $app_templates_path . '/' . $filename;
                    if (preg_match('|Template Name:(.*)$|mi', file_get_contents($full_path), $header)) {
                        $template_name = trim(_cleanup_header_comment($header[1]));
                        $template_path = $app_templates . '/' . $filename;
                        $post_templates[$template_path] = $template_name;
                    }
                }
            }
            return $post_templates;
        }


        /*
        ==============================
        https://developer.wordpress.org/reference/hooks/type_template_hierarchy/
        https://developer.wordpress.org/themes/basics/template-hierarchy/
        https://wordpress.stackexchange.com/a/227006/110572
        ==============================
        */
        function page_template_to_subdir($templates = array()) {
            //theme template dir
            $app_templates = 'app/templates';

            // Generally this doesn't happen, unless another plugin / theme does modifications of their own.
            // In that case, it's better not to mess with it again with our code.
            if (empty($templates) || !is_array($templates) || count($templates) < 3) {
                return $templates;
            }

            $page_tpl_idx = 0;
            $cnt = count($templates);
            if ($templates[0] === get_page_template_slug()) {
                // if there is custom template, then our page-{slug}.php template is at the next index 
                $page_tpl_idx = 1;
            }

            // the last one in $templates is page.php, so
            // all but the last one in $templates starting from $page_tpl_idx will be moved to sub-directory
            for ($i = $page_tpl_idx; $i < $cnt - 1; $i++) {
                $templates[$i] = $app_templates . '/' . $templates[$i];
            }

            return $templates;
        }

        /**
         * Disable emojis in WordPress
         * 
         * @return void
         */
        function disable_wp_emojicons() {
            // Remove emoji script from header
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_styles', 'print_emoji_styles');


            // Remove emoji from TinyMCE editor
            remove_filter('the_content', 'wp_staticize_emoji');
            remove_filter('the_excerpt', 'wp_staticize_emoji');
            remove_filter('comment_text', 'wp_staticize_emoji');
            remove_filter('widget_text_content', 'wp_staticize_emoji');

            // Remove emoji from RSS feed
            remove_action('wp_mail', 'wp_staticize_emoji_for_email');
            remove_action('the_content_feed', 'wp_staticize_emoji');
            remove_action('comment_text_rss', 'wp_staticize_emoji');

            // Remove emoji CDN path
            add_filter('tiny_mce_plugins', [$this, 'disable_emojicons_tinymce']);
            add_filter('wp_resource_hints', [$this, 'disable_emojis_remove_dns_prefetch'], 10, 2);
        }

        /**
         * Disable emojis in TinyMCE editor
         * 
         * @param array $plugins 
         * @return array Difference betwen the two arrays
         */
        function disable_emojicons_tinymce($plugins) {
            if (is_array($plugins)) {
                return array_diff($plugins, array('wpemoji'));
            }
            return array();
        }


        /**
         * Remove emoji CDN hostname from DNS prefetching hints.
         *
         * @param array $urls URLs to print for resource hints.
         * @param string $relation_type The relation type the URLs are printed for.
         * @return array Difference betwen the two arrays.
         */
        function disable_emojis_remove_dns_prefetch($urls, $relation_type) {
            if ('dns-prefetch' == $relation_type) {
                /** This filter is documented in wp-includes/formatting.php */
                $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

                $urls = array_diff($urls, array($emoji_svg_url));
            }

            return $urls;
        }
    }
}
// Instantiate the class
if (class_exists('YIPL_EXTRA')) {
    new YIPL_EXTRA();
} else {
    error_log('YIPL_EXTRA class does not exist.');
}
// End of class YIPL_EXTRA

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
            add_filter('theme_page_templates', [$this, 'register_page_templates']);
            add_filter('page_template_hierarchy', [$this, 'page_template_to_subdir']);
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

      

       
    }
}
// Instantiate the class
if (class_exists('YIPL_EXTRA')) {
    new YIPL_EXTRA();
} else {
    error_log('YIPL_EXTRA class does not exist.');
}
// End of class YIPL_EXTRA

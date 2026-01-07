<?php

/**
 * Block Types
 *
 * @package Helperbox
 */

namespace Helperbox_Plugin;

use Helperbox_Plugin\admin\Check_Settings;
use WP_Block_Type_Registry;

/*
 * Blocks Class 
 * 
 */

class Blocks {

    /**
     * construction
     */
    public function __construct() {

        add_action('init', [$this, 'init_action']);
        add_filter('block_categories_all', [$this, 'add_block_category']);
        add_filter('allowed_block_types_all', [$this, 'helperbox_allowed_restrict_block_types'], 10, 2);

        // Filters whether block styles should be loaded separately.
        // https://developer.wordpress.org/reference/hooks/should_load_separate_core_block_assets/
        add_filter('should_load_separate_core_block_assets', '__return_true');
    }

    public function init_action() {
        $this->register_block_types();
    }

    /**
     * Adding a new (custom) block category
     * https://developer.wordpress.org/reference/hooks/block_categories_all/
     */
    function add_block_category($block_categories) {
        // show the category at the top
        array_unshift(
            $block_categories,
            [
                'slug' => 'helperbox_blocks',
                'title' => 'Helperbox Blocks'
            ]
        );
        return $block_categories;
    }

    /**
     * Restrict allowed blocks
     * https://developer.wordpress.org/reference/hooks/allowed_block_types_all/
     * https://developer.wordpress.org/news/2024/01/29/how-to-disable-specific-blocks-in-wordpress/
     * 
     * @return void
     */
    public function helperbox_allowed_restrict_block_types($allowed_block_types, $block_editor_context) {
        try {
            $allowed_block_types = [];
            $disallowed_blocks = [
                'core/legacy-widget',
                'core/widget-group',
                'core/archives',
                'core/avatar',
                'core/block',
                'core/calendar',
                'core/categories',
                'core/footnotes',
                'core/navigation',
                'core/query',
                'core/query-title',
                'core/latest-posts',
                'core/page-list',
                'core/tag-cloud',
                'core/post-terms',
                'core/freeform'
            ];
            $registered_blocks   = WP_Block_Type_Registry::get_instance()->get_all_registered();
            foreach ($registered_blocks as $key => $value) {
                // check comment setting
                if (str_contains($key, 'comment')) {
                    if (Check_Settings::is_helperbox_disable_comment()) {
                        $disallowed_blocks[] = $key;
                    } else {
                        $allowed_block_types[] = $key;
                    }
                } else {
                    $allowed_block_types[] = $key;
                }
            }
            $filtered_blocks = array();
            foreach ($allowed_block_types as $block) {
                if (!in_array($block, $disallowed_blocks, true)) {
                    $filtered_blocks[] = $block;
                }
            }
            return $filtered_blocks;
        } catch (\Throwable $th) {
            return true; //$allowed_block_types;
        }
    }

    /**
     * Register Block Types
     * https://developer.wordpress.org/block-editor/getting-started/fundamentals/registration-of-a-block/
     * https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
     * https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/
     * 
     * npx @wordpress/create-block@latest alertbox --variant dynamic --no-plugin
     * 
     * https://www.advancedcustomfields.com/resources/acf-block-configuration-via-block-json/
     * 
     */
    public function register_block_types() {
        // Ensure the function exists.
        if (function_exists('register_block_type')) {
            // Register a block.
            $block_json_files = glob(helperbox_path . 'blocks/*/block.json');
            foreach ($block_json_files as $file) {
                // register_block_type($file);
                // Get the block folder path (useful for enqueuing assets, etc.)
                $block_folder = dirname($file);

                // Register the block using block.json
                $status =  register_block_type($block_folder);
                // var_dump($status);die;
            }
        }
    }


    /**
     * Register Block Assets
     * 
     * @return void
     */
    public static function register_block_assets() {
        // // register acf block assets from blocks folder
        // $block_js_files = glob(helperbox_path . 'assets/build/js/blocks/*/*.js');
        // foreach ($block_js_files as $file) {
        //     $block_dir = dirname($file);
        //     $block_name = basename($block_dir);
        //     $file_name = basename($file);
        //     $file_url = home_url(str_replace(ABSPATH, '', $file));
        //     $file_name_only = pathinfo($file_name, PATHINFO_FILENAME);
        //     wp_register_script(
        //         $block_name . '-' . $file_name_only,
        //         $file_url,
        //         ['jquery'],
        //         filemtime($file),
        //         [
        //             'in_footer' => true,
        //             'strategy' => 'defer',
        //         ]
        //     );
        // }

        // $block_css_files = glob(helperbox_path . 'assets/build/js/blocks/*/*.css');
        // foreach ($block_css_files as $file) {
        //     $block_dir = dirname($file);
        //     $block_name = basename($block_dir);
        //     $file_name = basename($file);
        //     $file_url = home_url(str_replace(ABSPATH, '', $file));
        //     $file_name_only = pathinfo($file_name, PATHINFO_FILENAME);
        //     wp_register_style(
        //         $block_name . '-' . $file_name_only,
        //         $file_url,
        //         [],
        //         filemtime($file),
        //         'all'
        //     );
        // }


        $blocks = [
            'services-carousel',
        ];
        // var_dump($blocks);
        $theme_dir = helperbox_path;
        $theme_uri = helperbox_url;
        foreach ($blocks as $block) {
            $block_path = "{$theme_dir}blocks/{$block}";
            $block_uri  = "{$theme_uri}blocks/{$block}";
            // Register editor script
            $block_js_path = "{$block_path}/block.js";
            if (file_exists($block_js_path)) {
                wp_register_script(
                    "sophia-{$block}-editor",
                    "{$block_uri}/block.js",
                    ['wp-hooks', 'wp-data', 'wp-core-data', 'wp-blocks', 'wp-element', 'wp-editor', 'wp-block-editor', 'wp-components', 'wp-api-fetch', 'wp-i18n'],
                    filemtime($block_js_path),
                    true
                );
                // Inject dynamic preview path
                $preview_image_url = "{$block_uri}/preview.png";
                wp_add_inline_script(
                    "sophia-{$block}-editor",
                    "window.sophiaBlockData = window.sophiaBlockData || {}; window.sophiaBlockData['{$block}'] = { preview: '" . esc_url($preview_image_url) . "' };",
                    'before'
                );
            }
            // Register frontend style (we'll enqueue manually in render)
            $style_css_path = "{$block_path}/style.css";
            wp_register_style(
                "sophia-{$block}-style",
                "{$block_uri}/style.css",
                [],
                file_exists($style_css_path) ? filemtime($style_css_path) : false
            );
            // Register frontend script (optional)
            $script_js_path = "{$block_path}/script.js";
            if (file_exists($script_js_path)) {
                wp_register_script(
                    "sophia-{$block}-script",
                    "{$block_uri}/script.js",
                    [],
                    filemtime($script_js_path),
                    true
                );
            }
            // Load render callback if it exists
            $render_file = "{$block_path}/render.php";
            if (file_exists($render_file)) {
                require_once $render_file;
            }
            $args = [
                'editor_script' => "sophia-{$block}-editor",
            ];
            switch ($block) {
                case 'services-carousel':
                    if (function_exists('sophia_render_services_carousel_block')) {
                        $args['render_callback'] = 'sophia_render_services_carousel_block';
                    }
                    break;
            }
            register_block_type("{$block_path}", $args);
        }


        // :white_check_mark: Localize script data for JavaScript blocks
        function sophia_localize_block_data() {
            $blocks = [
                'services-carousel',
                'reviews-scroller',
                'info-accordion',
                'video-gallery',
                'meet-the-team',
                'product-carousel',
                'before-after-gallery',
                'pricing',
                'gallery-tabs',
                'mega-menus'
            ];
            foreach ($blocks as $block) {
                wp_localize_script(
                    "sophia-{$block}-editor",
                    'sophiaBlockData',
                    [
                        'themeUrl' => get_template_directory_uri(),
                        'blockUrl' => get_template_directory_uri() . "/blocks/{$block}",
                        'previewImage' => get_template_directory_uri() . "/blocks/{$block}/preview.png"
                    ]
                );
            }
        }
        add_action('wp_enqueue_scripts', 'sophia_localize_block_data');
        add_action('admin_enqueue_scripts', 'sophia_localize_block_data');
    }

    public static function get_block_template($block_name, $attributes = array()) {
        $block_template = '';
        $block_folder = helperbox_path . 'blocks/' . $block_name . '/';
        $template_file = $block_folder . 'template.php';

        if (file_exists($template_file)) {
            // Start output buffering
            ob_start();

            // Make attributes available in the template file
            if (!empty($attributes) && is_array($attributes)) {
                extract($attributes);
            }

            // Include the template file
            include $template_file;

            // Get the contents of the buffer and clean it
            $block_template = ob_get_clean();
        }

        return $block_template;
    }
}

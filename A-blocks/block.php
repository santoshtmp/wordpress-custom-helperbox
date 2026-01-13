<?php


/**
 * Include block files
 */
function sophia_register_all_blocks() {
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
        // 'mega-menus'
    ];
    // var_dump($blocks);
    $theme_dir = HELPERBOX_PATH;
    $theme_uri = HELPERBOX_URL;
    foreach ($blocks as $block) {
        $block_path = "{$theme_dir}/A-blocks/{$block}";
        $block_uri = "{$theme_uri}/A-blocks/{$block}";
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
            case 'reviews-scroller':
                if (function_exists('sophia_render_reviews_scroller_block')) {
                    $args['render_callback'] = 'sophia_render_reviews_scroller_block';
                }
                break;
            case 'info-accordion':
                if (function_exists('sophia_render_info_accordion_block')) {
                    $args['render_callback'] = 'sophia_render_info_accordion_block';
                }
                break;
            case 'video-gallery':
                if (function_exists('sophia_render_video_gallery_block')) {
                    $args['render_callback'] = 'sophia_render_video_gallery_block';
                }
                break;
            case 'meet-the-team':
                if (function_exists('sophia_render_meet_the_team_block')) {
                    $args['render_callback'] = 'sophia_render_meet_the_team_block';
                }
                break;
            case 'product-carousel':
                if (function_exists('sophia_render_product_carousel_block')) {
                    $args['render_callback'] = 'sophia_render_product_carousel_block';
                }
                break;
            case 'before-after-gallery':
                if (function_exists('sophia_render_before_after_gallery_block')) {
                    $args['render_callback'] = 'sophia_render_before_after_gallery_block';
                }
                break;
            case 'pricing':
                if (function_exists('sophia_render_pricing_block')) {
                    $args['render_callback'] = 'sophia_render_pricing_block';
                }
                break;
            case 'gallery-tabs':
                if (function_exists('sophia_render_gallery_tabs_block')) {
                    $args['render_callback'] = 'sophia_render_gallery_tabs_block';
                }
                break;
                // case 'mega-menus':
                // if (function_exists('sophia_render_mega_menu_block')) {
                // $args['render_callback'] = 'sophia_render_mega_menu_block';
                // }
                // break;
        }
        register_block_type("{$block_path}", $args);
    }
}
add_action('init', 'sophia_register_all_blocks');
//  Block Category
add_filter('block_categories_all', function ($categories, $post) {
    array_unshift($categories, [
        'slug' => 'sophia-blocks',
        'title' => __('SOPHIA BLOCKS', 'sophia-pro'),
    ]);
    return $categories;
}, 10, 2);
//  Star Rating Helper
function sophia_render_star_rating($rating) {
    $rating = floatval($rating);
    $output = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($rating >= $i) {
            $output .= '<span class="star full">&#9733;</span>';
        } elseif ($rating >= $i - 0.5) {
            $output .= '<span class="star half">&#9733;</span>';
        } else {
            $output .= '<span class="star empty">&#9734;</span>';
        }
    }
    return $output;
}
//  Localize script data for JavaScript blocks
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
                'themeUrl' => HELPERBOX_URL,
                'blockUrl' => HELPERBOX_URL . "/blocks/{$block}",
                'previewImage' => HELPERBOX_URL . "/blocks/{$block}/preview.png"
            ]
        );
    }
}
add_action('wp_enqueue_scripts', 'sophia_localize_block_data');
add_action('admin_enqueue_scripts', 'sophia_localize_block_data');
//  Add custom template part area: "Menu" for mega menus
// add_filter('default_wp_template_part_areas', function(array $areas) {
// $areas[] = array(
// 'area'    => 'menu',
// 'area_tag'    => 'div',
// 'description' => __('Menu templates are used to create sections of a mega menu.', 'sophia-pro'),
// 'icon'    => 'menu',
// 'label'   => __('Menu', 'sophia-pro'),
// );
// return $areas;
// });

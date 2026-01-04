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
 * https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/
 * https://developer.wordpress.org/reference/functions/register_block_type/
 * 
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
        $this->register_acf_block_types();
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
     * 
     */
    public function register_acf_block_types() {
        // Ensure the function exists.
        if (function_exists('register_block_type')) {
            // Register a block.
            $block_json_files = glob(helperbox_path . 'blocks/*/block.json');
            foreach ($block_json_files as $file) {
                register_block_type($file);
            }
        }
    }
}

<?php

/**
 * Block Types
 *
 * @package Helperbox
 */

namespace Helperbox_Plugin;


/*
 * Block_Types Class
 * https://developer.wordpress.org/reference/functions/register_block_type/
 * 
 */

class Block_Types {

    /**
     * construction
     */
    public function __construct() {

        add_action('init', [$this, 'init_action']);
        add_filter('block_categories_all', [$this, 'add_block_category']);
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
     * Register Block Types
     */
    public function register_block_types() {
        // Ensure the function exists.
        if (function_exists('register_block_type')) {
        }
    }
}

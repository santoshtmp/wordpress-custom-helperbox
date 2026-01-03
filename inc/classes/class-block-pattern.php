<?php

/**
 * Block Patterns
 *
 * @package Helperbox
 */

namespace Helperbox_Plugin;

/**
 * Block_Pattern Class
 * https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
 * https://developer.wordpress.org/reference/functions/register_block_pattern/
 * https://developer.wordpress.org/reference/functions/register_block_pattern_category/
 * 
 */
class Block_Pattern {

    /**
     * construction
     */
    public function __construct() {

        add_action('init', [$this, 'init_action']);

        // check setting
        if (get_option('helperbox_load_remote_block_patterns', '') == '1') {
            /**
             * remove remote block patterns
             * https://developer.wordpress.org/reference/hooks/should_load_remote_block_patterns/
             */
            add_filter('should_load_remote_block_patterns', '__return_false');
        }
    }

    public function init_action() {
        $this->register_block_patterns();
        $this->register_block_pattern_categories();
    }

    /**
     * Register Block Patterns
     */
    public function register_block_patterns() {
        // Ensure the function exists.
        if (function_exists('register_block_pattern')) {
            // pattern directory
            $block_patterns = helperbox_path . 'block-patterns';
            if (file_exists($block_patterns)) {
                foreach (new \DirectoryIterator($block_patterns) as $file) {
                    if ($file->isDot() || !$file->isFile() || $file->getExtension() !== 'php') {
                        continue;
                    }
                    $filename = $file->getBasename('.php');
                    $filepath = $file->getPathname();
                    $slug = 'helperbox/' . $filename;
                    $patternTitle = ucwords(str_replace('-', ' ', $filename));
                    register_block_pattern(
                        $slug,
                        [
                            'title'      => $patternTitle,
                            'description' => '',
                            'categories' => ['helperbox_pattern'],
                            'content'    => $this->get_pattern_content($filepath),
                        ]
                    );
                }
            }
        }
    }

    /**
     * Get Pattern Content
     *
     * @param string $pattern_path Path to the pattern file.
     * @return string Pattern content.
     */
    public function get_pattern_content($pattern_path) {
        if (file_exists($pattern_path)) {
            ob_start();
            include_once($pattern_path);
            $content = ob_get_contents();
            ob_end_clean();
        } else {
            $content = '
                <!-- wp:paragraph -->
                <p>Pattern missing</p>
                <!-- /wp:paragraph -->
            ';
        }

        return $content;
    }

    /**
     * Register Block Pattern Categories
     * https://developer.wordpress.org/reference/functions/register_block_pattern_category/
     */

    public function register_block_pattern_categories() {

        $pattern_categories = [
            'helperbox_pattern' => __('Helperbox Pattern', 'helperbox'),
        ];

        if (! empty($pattern_categories) && is_array($pattern_categories)) {
            foreach ($pattern_categories as $pattern_category => $pattern_category_label) {
                register_block_pattern_category(
                    $pattern_category,
                    ['label' => $pattern_category_label]
                );
            }
        }
    }

    /**
     *  === END ===
     */
}

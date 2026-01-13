<?php

/**
 * helperbox Patterns
 *
 * @package Helperbox
 */

namespace Helperbox_Plugin;

/**
 * Patterns Class
 * https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
 * https://developer.wordpress.org/reference/functions/register_block_pattern/
 * https://developer.wordpress.org/reference/functions/register_block_pattern_category/
 * 
 */
class Block_Patterns {

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
            $patterns_path = HELPERBOX_PATH . 'block-patterns';
            if (file_exists($patterns_path)) {
                foreach (new \DirectoryIterator($patterns_path) as $file) {
                    if ($file->isDot() || !$file->isFile() || $file->getExtension() !== 'php') {
                        continue;
                    }
                    $filename = $file->getBasename('.php');
                    $filepath = $file->getPathname();
                    // pattern slug
                    $slug = 'helperbox/' . str_replace([' ', '-'], '_', strtolower($filename));
                    // pattern title
                    preg_match('|Pattern Template Name:(.*)$|mi', file_get_contents($filepath), $header);
                    if (!empty($header)) {
                        $patternTitle = trim(_cleanup_header_comment($header[1]));
                    } else {
                        $patternTitle = ucwords(str_replace(['_', '-'], ' ', $filename));
                    }
                    // pattern description
                    preg_match('|Pattern Description:(.*)$|mi', file_get_contents($filepath), $desc_header);
                    if (!empty($desc_header)) {
                        $patternDescription = trim(_cleanup_header_comment($desc_header[1]));
                    } else {
                        $patternDescription = '';
                    }
                    // register pattern
                    register_block_pattern(
                        $slug,
                        [
                            'title'      => $patternTitle,
                            'description' => $patternDescription ?? '',
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

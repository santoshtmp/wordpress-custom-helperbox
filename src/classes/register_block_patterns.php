<?php

/**
 * https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class register_block_patterns{

}

function get_pattern_content($pattern_path) {
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
 * https://developer.wordpress.org/reference/functions/register_block_pattern/
 */
function pnghub_register_block_pattern() {
    $block_patterns = [
        'app/pattern/list'
    ];
    foreach ($block_patterns as $key_path => $block_pattern) {
        $path = get_template_directory() . '/' . $block_pattern;
        if (file_exists($path)) {
            foreach (new \DirectoryIterator($path) as $file) {
                if ($file->isDot())
                    continue;
                if ($file->isDir()) {
                    continue;
                }

                $path_info = pathinfo($file);
                $block_pattern_path = get_template_directory() . '/' . $block_pattern . '/' . $file;
                if (file_exists($block_pattern_path)) {
                    register_block_pattern(
                        'seap/' . $path_info['filename'],
                        array(
                            'title'      => str_replace(["-", "_"], " ", $path_info['filename']),
                            'categories' => ['seap_pattern'],
                            'content'    => get_pattern_content($block_pattern_path),
                        )
                    );
                }
            }
        }
    }
}
add_action('init', 'pnghub_register_block_pattern');

/**
 * remove remote block patterns
 * https://developer.wordpress.org/reference/hooks/should_load_remote_block_patterns/
 */
add_filter('should_load_remote_block_patterns', '__return_false');


/**
 * https://developer.wordpress.org/reference/functions/register_block_pattern_category/
 */
register_block_pattern_category(
    'seap_pattern',
    ['label' => 'SEAP Pattern']
);

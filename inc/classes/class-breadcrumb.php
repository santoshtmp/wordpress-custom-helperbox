<?php

/**
 * Helperbox Breadcrumb
 *
 * @package helperbox
 * 
 */

namespace Helperbox_Plugin;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * breadcrumb main class
 * 
 */
class Breadcrumb {

    public static $excludePostType = [];
    public static $excludePostSlug = [];

    /**
     * construction
     */
    function __construct() {
        self::get_exclude_post_type();
        self::get_exclude_post_slug();
    }

    /**
     * this post type is not included in breadcrumb
     */
    public static function get_exclude_post_type() {
        self::$excludePostType = get_option('helperbox_breadcrumb_exclude_post_type', self::$excludePostType);
        self::$excludePostType = is_array(self::$excludePostType) ? self::$excludePostType : [];
        return self::$excludePostType;
    }

    /**
     *this post item is not included in breadcrumb
     */
    public static function get_exclude_post_slug() {
        $value = get_option('helperbox_breadcrumb_exclude_post_slug', '');
        self::$excludePostSlug = array_values(array_filter(array_map('trim', explode("\n", $value))));
        return self::$excludePostSlug;
    }


    /**
     * helperbox_breadcrumb_value
     * Get breadcrumb array values
     */
    public static function helperbox_breadcrumb_value($removeCondition = []) {
        // check setting
        if (get_option('helperbox_breadcrumb_feature', '1') != '1') {
            return;
        }

        //don't show the breadcrumb in front page 
        if (is_front_page()) {
            return '';
        }

        // check removeCondition
        if (isset($removeCondition['post_type'])) {
            if (is_array($removeCondition['post_type']) && in_array(get_post_type(), array_keys($removeCondition['post_type']))) {
                $currentPostCondition = isset($removeCondition['post_type'][get_post_type()]) ? $removeCondition['post_type'][get_post_type()] : [];
                foreach ($currentPostCondition as $key => $condition) {
                    if (is_array($condition)) {
                        $meta_key = $condition['meta_key'];
                        $meta_value = $condition['meta_value'];
                        $get_meta_value = get_post_meta(get_the_ID(), $meta_key, true);
                        if ($get_meta_value == $meta_value) {
                            return;
                        }
                    }
                }
            }
        }
        // // URL based removal
        // if (!empty($conditions['url'])) {
        //     $current_path = '/' . trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        //     if (in_array($current_path, $conditions['url'], true)) {
        //         return false; // remove breadcrumb
        //     }
        // }

        // 
        $breadcrumbs = [
            [
                'title' => 'Home',
                'url' => home_url(),
                'slug' => '/',
                'type' => 'home',
            ]
        ];
        if (is_singular()) {
            $post_type = get_post_type();
            if (!in_array($post_type, self::get_exclude_post_type())) {
                $this_post_type_breadcrumb = [
                    'title' => get_post_type_object($post_type)->label,
                    'url' => (get_post_type_archive_link($post_type)) ?: '#',
                    'slug' => $post_type,
                    'type' => 'archive',
                ];
                array_push($breadcrumbs, $this_post_type_breadcrumb);
            }
            $parent_present = true;
            $child_post_id = get_the_ID();
            $child_breadcrumb = [];
            while ($parent_present == true) {
                if ($parent = get_post_parent($child_post_id)) {
                    $child_post_id = $parent->ID;
                    $slug = get_post_field('post_name', $child_post_id);
                    $this_breadcrumb = [
                        'title' => get_the_title($child_post_id),
                        'url' => get_permalink($child_post_id),
                        'slug' => $slug,
                        'type' => 'singular',
                    ];
                    if (in_array($slug, self::get_exclude_post_slug())) {
                        continue;
                    }
                    array_unshift($child_breadcrumb, $this_breadcrumb);
                } else {
                    $parent_present = false;
                    $this_breadcrumb = [
                        'title' => get_the_title(),
                        'url' => get_permalink(),
                        'slug' => get_post_field('post_name'),
                        'type' => 'singular',
                    ];
                    if (in_array(get_post_field('post_name'), self::get_exclude_post_slug())) {
                        continue;
                    }
                    array_push($child_breadcrumb, $this_breadcrumb);
                }
            }
            $breadcrumbs = array_merge($breadcrumbs, $child_breadcrumb);
        } elseif (is_archive()) {
            $queried_object = get_queried_object();
            if (is_tax()) {
                $post_type = get_post_type();
                $this_post_type_breadcrumb = [
                    'title' => get_post_type_object($post_type)->label,
                    'url' => (get_post_type_archive_link($post_type)) ?: '#',
                    'slug' => $post_type,
                    'type' => 'tax',
                ];
                array_push($breadcrumbs, $this_post_type_breadcrumb);
                array_push($breadcrumbs, ['title' => $queried_object->name]);
            } else {
                $post_type = $queried_object->name;
                if ($post_type) {
                    $this_breadcrumb = [
                        'title' => $queried_object->label,
                        'url' => (get_post_type_archive_link($post_type)) ?: '#',
                        'slug' => $post_type,
                        'type' => 'archive',
                    ];
                    array_push($breadcrumbs, $this_breadcrumb);
                }
            }
        } elseif (is_search()) {
            $this_breadcrumb = [
                'title' => get_the_title(),
                'url' => get_permalink(),
                'slug' => '/?s',
                'type' => 'search',
            ];
            array_push($breadcrumbs, $this_breadcrumb);
        }

        return $breadcrumbs;
    }



    /**
     * get_helperbox_breadcrumb
     * Get breadcrumb html content
     * @return html breadcrumbs content
     */
    //    $removeCondition = [
    //             'url'=>['/url1','/url2'],
    //             'post_type' => [
    //                 'region' => [
    //                     [
    //                         'meta_key' => 'type_of_region',
    //                         'meta_value' => 'country',
    //                     ]
    //                 ]
    //             ]
    //         ];
    public static function get_helperbox_breadcrumb($removeCondition = []) {

        // get breadcrumb array value
        $breadcrumbs = self::helperbox_breadcrumb_value($removeCondition);
        if (!$breadcrumbs) {
            return;
        }
        ob_start(); ?>
        <div class="breadcrumb">
            <ul>
                <?php
                foreach ($breadcrumbs as $key => $breadcrumb) {
                ?>
                    <li class="breadcrumb-item">
                        <?php
                        if (end($breadcrumbs) === $breadcrumb) {
                            echo $breadcrumb['title'];
                        } else {
                        ?>
                            <a href="<?php echo $breadcrumb['url']; ?>">
                                <?php echo $breadcrumb['title']; ?>
                            </a>
                        <?php
                        }
                        ?>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
<?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}

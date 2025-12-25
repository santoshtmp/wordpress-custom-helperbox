<?php

namespace wp_helperbox;


// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}


if (! class_exists('helperbox_breadcrumb')) {
    /**
     * helperbox_breadcrumb main class
     * 
     */
    class helperbox_breadcrumb {

        /**
         * @var array $exclude_post_type this post type is not included in breadcrumb
         */
        public static $exclude_post_type = [ 'page'];

        /**
         * @var array $exclude_post_slug this post item is not included in breadcrumb
         */
        public static $exclude_post_slug = ['regional-overview', 'digital-id-region'];


        /**
         * helperbox_breadcrumb_value
         * Get breadcrumb array values
         */
        public static function helperbox_breadcrumb_value() {
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
                if (!in_array($post_type, self::$exclude_post_type)) {
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
                        if (in_array($slug, self::$exclude_post_slug)) {
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
        public static function get_helperbox_breadcrumb() {
            //don't show the breadcrumb in front page 
            if (is_front_page()) {
                return '';
            }
            // don't show the breadcrumb in post type "region" or "digital-id" with type_of_region not equal to Country
            if (get_post_type() === 'region' || get_post_type() === 'digital-id') {
                $type_of_region = get_post_meta(get_the_ID(), 'type_of_region', true);
                if ($type_of_region != 'country') {
                    return "";
                }
            }
            // get breadcrumb array value
            $breadcrumbs = self::helperbox_breadcrumb_value();
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
}

<?php

/**
 * =================================================
 * Support custom "anchor" values.
 * =================================================
 */
$id = '';
if (!empty($block['anchor'])) {
    $id = esc_attr($block['anchor']);
}

// use generated id if custom anchor is not provided
$id = $block['id'];

// Create class attribute allowing for custom "className" and "align" values.
$class_name = '';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}


/**
 * =================================================
 * Get acf field values
 * =================================================
 */
$use_glossary_post_type = get_field('use_glossary_post_type');
if ($use_glossary_post_type) {
    $glossary = [];
    $glossary_arg = array(
        'post_type' => 'glossary',
        'posts_per_page' => -1,
        'post_status' => array('publish'),
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
    $glossary_posts = get_posts($glossary_arg);
    foreach ($glossary_posts  as $key => $value) {
        $data = [];
        $data['term'] = get_the_title($value->ID);
        $data['description'] = get_field('description', $value->ID);
        $glossary[] = $data;
    }
} else {
    $glossary = get_field('glossary');
}


/**
 * =================================================
 * HTML output of the glossary block
 * =================================================
 */
wp_enqueue_style('dataTables');
wp_enqueue_script('dataTables');
wp_enqueue_script('glossary-front');

?>

<section id="<?php echo $id; ?>" class="<?php echo esc_attr($class_name); ?> glossary">
    <?php
    if ($glossary) {
    ?>
        <?php seap_search_box(['class' => 'glossary-search', 'id' => 'glossary-search-box']); ?>
        <div class="px-4">
            <table id="glossary-table">
                <thead class=" table-heading font-semibold text-base tracking-[2.8px] opacity-[0.4] ">
                    <tr class="">
                        <th class="p-0">Terms</th>
                        <th class="p-0">Description</th>
                    </tr>
                </thead>
                <tbody class="table-data">
                    <?php
                    foreach ($glossary as $key => $value) {
                        $term = ($value['term']) ?: '';
                        $description = ($value['description']) ?: '';
                    ?>
                        <tr class="text-n-60">
                            <td class="title font-bold sm:font-semibold;">
                                <?php echo $term; ?>
                            </td>
                            <td>
                                <?php echo $description; ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
    }
    ?>
</section>
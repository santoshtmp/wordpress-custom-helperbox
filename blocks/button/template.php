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
// single : Single Button
// expand_drop_down : Expand Drop Down Button
$button_type = (get_field('button_type')) ?: '';
if ($button_type == 'single') {
    $single_button = (get_field('single_button')) ?: [];
    $label = isset($single_button['label']) ? $single_button['label'] : 'Label';
    $link = isset($single_button['link']) ? $single_button['link'] : '#';
    $id_attributes = isset($single_button['id_attributes']) ? $single_button['id_attributes'] : '';
    $class_attributes = isset($single_button['class_attributes']) ? $single_button['class_attributes'] : '';
    $other_attributes = isset($single_button['other_attributes']) ? $single_button['other_attributes'] : '';
} elseif ($button_type == 'expand_drop_down') {
    $explore_drop_down_buttons = (get_field('explore_drop_down_buttons')) ?: [];
} else {
}


/**
 * =================================================
 * HTML output of the button block
 * =================================================
 */
if ($button_type == 'single') {
    ?>
    <section id="<?php echo $id; ?>" class="<?php echo esc_attr($class_name); ?> seap-button ">
        <div
            class="wp-block-buttons is-content-justification-center is-layout-flex wp-container-core-buttons-layout-1 wp-block-buttons-is-layout-flex">
            <div class="wp-block-button is-style-fill">
                <a id="<?php echo $id_attributes; ?>"
                    class="wp-block-button__link wp-element-button button-primary <?php echo $class_attributes; ?>"
                    href="<?php echo $link; ?>" <?php echo $other_attributes; ?>>
                    <?php echo $label; ?>
                </a>
            </div>
        </div>
    </section>
    <?php

} elseif ($button_type == 'expand_drop_down') {
    ?>
    <section id="<?php echo $id; ?>" class="<?php echo esc_attr($class_name); ?> seap-button ">
        <div
            class="wp-block-buttons is-content-justification-center is-layout-flex wp-container-core-buttons-layout-1 wp-block-buttons-is-layout-flex">
            <?php echo explore_drop_down_buttons($explore_drop_down_buttons); ?>
        </div>
    </section>

    <?php

} else {
    echo '<p class="error">Please select a button type.</p>';
}
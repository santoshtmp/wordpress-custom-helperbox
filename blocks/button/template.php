<?php

/**
 * Block attributes
 */
$button_text = $attributes['buttonText'] ?? '';
$url = $attributes['url'] ?? '';
$opens_in_new_tab = $attributes['opensInNewTab'] ?? false;

/**
 * Wrapper attributes
 */
$wrapper_attributes = get_block_wrapper_attributes(['class' => 'helperbox-button-wrapper']);

$target = $opens_in_new_tab ? ' target="_blank"' : '';
$rel = $opens_in_new_tab ? ' rel="noreferrer noopener"' : '';
$href = !empty($url) ? esc_url($url) : '#';

?>
<a
    <?php echo $wrapper_attributes; ?>
    href="<?php echo $href; ?>"
    class="helperbox-button"
    <?php echo $target; ?>
    <?php echo $rel; ?>>
    <?php echo wp_kses_post($button_text); ?>
</a>
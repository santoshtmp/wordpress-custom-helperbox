<?php

// Example dynamic content – you can customize this however you like.
$classes = 'wp-block-my-plugin-server-block';

if (! empty($block->classes)) {
    $classes .= ' ' . $block->classes;
}

?>
<div class="<?php echo esc_attr($classes); ?>">
    <p><?php echo esc_html("This content is rendered on the server."); ?></p>
</div>


<?php
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'helperbox-button-wrapper'
]);

$target = ! empty($attributes['opensInNewTab']) ? ' target="_blank"' : '';
$rel    = ! empty($attributes['rel']) ? ' rel="' . esc_attr($attributes['rel']) . '"' : '';
$url    = ! empty($attributes['url']) ? esc_url($attributes['url']) : '#';
$text   = ! empty($attributes['text']) ? esc_html($attributes['text']) : 'Click';

?>
<div <?php echo $wrapper_attributes; ?>>
    <a class="helperbox-button" href="<?php echo $url; ?>" <?php echo $target . $rel; ?>>
        <?php echo $text; ?>
    </a>
</div>
<?php

/**
 * Helperbox Button – Dynamic Render
 */

if (!defined('ABSPATH')) {
    exit;
}

$tag    = $attributes['tagName'] ?? 'a';
$text   = $attributes['text'] ?? '';
$url    = $attributes['url'] ?? '';
$title  = $attributes['title'] ?? '';
$target = $attributes['linkTarget'] ?? '';
$rel    = $attributes['rel'] ?? '';
$width  = $attributes['width'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'wp-block-button helperbox-button'
]);

$attrs = [];

if ($tag === 'a' && $url) {
    $attrs['href'] = esc_url($url);
}

if ($title) {
    $attrs['title'] = esc_attr($title);
}

if ($target) {
    $attrs['target'] = esc_attr($target);
    $rel = $rel ?: 'noopener noreferrer';
}

if ($rel) {
    $attrs['rel'] = esc_attr($rel);
}

if ($width) {
    $attrs['style'] = 'width:' . intval($width) . 'px;';
}

$attr_html = '';
foreach ($attrs as $key => $value) {
    $attr_html .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <<?php echo esc_html($tag); ?>
        class="wp-block-button__link"
        <?php echo $attr_html; ?>>
        <?php echo esc_html($text); ?>
    </<?php echo esc_html($tag); ?>>
</div>
<?php



/**
 * Block attributes
 */
$buttonText   = $attributes['buttonText'] ?? '';
// $button_text = ! empty($attributes['buttonText']) ? esc_html($attributes['buttonText']) : 'Click';

/**
 * Wrapper attributes
 */
$wrapper_attributes = get_block_wrapper_attributes([]);

?>
<div <?php echo $wrapper_attributes; ?>>
    <a class="helperbox-button">
        <?php echo $buttonText; ?>
    </a>
</div>
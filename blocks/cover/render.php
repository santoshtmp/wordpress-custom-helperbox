<?php

/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

if (!defined('ABSPATH')) {
    exit;
}

/* =========================
 * Block attributes
 * ========================= */
$heading   = $attributes['heading'] ?? '';
$text      = $attributes['text'] ?? '';
$ctas      = $attributes['ctas'] ?? [];
$minHeight = (int) ($attributes['minHeight'] ?? 640);

$bgImage   = $attributes['bgImage']['url'] ?? '';
$defaultBg = $attributes['defaultBg'] ?? true;

/* =========================
 * Background handling
 * ========================= */
if ($defaultBg && empty($bgImage)) {
    $bgImage = HELPERBOX_IMG_URL . '/patterns/cover.jpg';
}

/* =========================
 * Wrapper attributes
 * ========================= */
$style = sprintf(
    'background-image:url(%s); min-height:%dpx;',
    esc_url($bgImage),
    $minHeight
);

$wrapper_attributes = get_block_wrapper_attributes([
    'style' => $style,
    'class' => 'wp-block-cover helperbox-cover',
]);


?>
<div <?php echo $wrapper_attributes; ?>>
    <div class="wp-block-cover__inner-container">

        <?php if ($heading || $text) : ?>
            <div class="wp-block-cover__heading-text">
                <?php if ($heading) : ?>
                    <h1 class="block-heading has-text-align-center">
                        <strong><?php echo esc_html($heading); ?></strong>
                    </h1>
                <?php endif; ?>

                <?php if ($text) : ?>
                    <p class="block-text has-text-align-center has-cyan-bluish-gray-color has-text-color">
                        <?php echo esc_html($text); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>


        <?php if (!empty($ctas) && is_array($ctas)) : ?>
            <div class="wp-block-buttons aligncenter">
                <?php foreach ($ctas as $cta) : ?>
                    <?php
                    $cta_text = trim($cta['text'] ?? '');
                    $cta_url  = trim($cta['url'] ?? '');

                    if (!$cta_text) {
                        continue;
                    }

                    $variant = $cta['variant'] ?? 'primary';
                    $new_tab = !empty($cta['newTab']);

                    $target = $new_tab ? ' target="_blank" rel="noopener noreferrer"' : '';

                    // Map variant to core button styles
                    $button_classes = 'wp-block-button';

                    if ($variant === 'outline') {
                        $button_classes .= ' is-style-outline';
                    }
                    ?>

                    <div class="<?php echo esc_attr($button_classes); ?>">
                        <a
                            class="wp-block-button__link has-cyan-bluish-gray-color has-text-color"
                            href="<?php echo esc_url($cta_url); ?>"
                            <?php echo $target; ?>>
                            <?php echo esc_html($cta_text); ?>
                        </a>
                    </div>

                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>
</div>
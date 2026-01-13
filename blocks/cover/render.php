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


/**
 * Render callback for Helperbox Cover block
 */
$heading    = $attributes['heading'] ?? '';
$text       = $attributes['text'] ?? '';
$buttonText = $attributes['buttonText'] ?? '';
$buttonUrl  = $attributes['buttonUrl'] ?? '';
$minHeight  = (int) ($attributes['minHeight'] ?? 640);

$bgImage = $attributes['bgImage']['url'];
$defaultBg = $attributes['defaultBg'];
if ($defaultBg) {
    $bgImage = $bgImage ?? HELPERBOX_IMG_URL . '/patterns/cover.jpg';
}

$wrapper_attributes = get_block_wrapper_attributes(
    [
        "style" => "background-image:url($bgImage); min-height:" . $minHeight . "px",
        'class' => 'wp-block-cover helperbox-cover',
    ]
);

?>
<div <?php echo $wrapper_attributes; ?>>
    <div class="wp-block-cover__inner-container">

        <h1 class="has-text-align-center">
            <strong><?php echo esc_html($heading); ?></strong>
        </h1>

        <p class="has-text-align-center has-cyan-bluish-gray-color has-text-color">
            <?php echo esc_html($text); ?>
        </p>

        <div class="wp-block-buttons aligncenter">
            <div class="wp-block-button is-style-outline">
                <a
                    class="wp-block-button__link has-cyan-bluish-gray-color has-text-color"
                    href="<?php echo esc_url($buttonUrl); ?>">
                    <?php echo esc_html($buttonText); ?>
                </a>
            </div>
        </div>

    </div>
</div>
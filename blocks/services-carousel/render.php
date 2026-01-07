<?php
function sophia_render_services_carousel_block($attributes, $content, $block)
{
    $sectionHeading     = $attributes['sectionHeading'] ?? 'Tailored solutions to address your unique challenges and achieve success.';
    $sectionDescription = $attributes['sectionDescription'] ?? 'Start your journey to business excellence. Experience the difference.';
    $bulletPoints       = $attributes['bulletPoints'] ?? [];
    $services           = $attributes['services'] ?? [];

    // Generate unique ID for this block instance
    $block_id = 'services-carousel-' . uniqid();

    // Correct usage: inject custom classes + allow editor styles (e.g., colors)
    $wrapper_attributes = get_block_wrapper_attributes([
        'class' => 'gallery-container alignfull wp-block-section section-has-animation has-body-background-background-color has-background has-global-padding is-layout-constrained',
        'id' => $block_id
    ]);

    ob_start(); ?>

    <div <?php echo $wrapper_attributes; ?>>
        <div class="wp-block-group alignwide wp-block-group-heading">
            <div class="wp-block-columns alignwide are-vertically-aligned-center is-layout-flex">
                <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:70%">
                    <div class="wp-block-group wp-block-heading-content   animate-element is-layout-flow">
                        <?php if (!empty($sectionHeading)): ?>
                            <h2 class="wp-block-heading has-tan-brown-color has-text-color has-link-color has-serif-font-family has-section-title-font-size"
                                style="font-style:normal;font-weight:600">
                                <?php echo wp_kses_post($sectionHeading); ?>
                            </h2>
                        <?php endif; ?>

                        <?php if (!empty($sectionDescription)): ?>
                            <p style="margin-top:var(--wp--preset--spacing--30);font-size:1.125rem;font-style:normal;font-weight:500;line-height:1.75">
                                <?php echo wp_kses_post($sectionDescription); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($bulletPoints)): ?>
                    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:30%">
                        <ul style="padding-left:1.5rem;font-size:1rem;line-height:1.6875" class="wp-block-list  animate-element">
                            <?php foreach ($bulletPoints as $point): ?>
                                <li><?php echo esc_html($point); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="wp-block-group alignwide">
            <!-- Services Carousel -->
            <div class="services-carousel  " data-carousel-id="<?php echo esc_attr($block_id); ?>">
                <div class="carousel-container">
                    <div class="carousel-overflow">
                        <div class="carousel-track" id="<?php echo esc_attr($block_id); ?>-track">
                            <?php
                            $delay = 1; // Start from 1
                            foreach ($services as $service):
                            ?>
                                <div class="service-card animate-element fade-in-left delay<?php echo $delay; ?>">
                                    <a href="<?php echo esc_url($service['url'] ?? '#'); ?>"
                                        class="service-image-link"
                                        title="<?php echo esc_attr($service['title'] ?? ''); ?>">
                                        <?php if (!empty($service['image'])): ?>
                                            <img src="<?php echo esc_url($service['image']); ?>"
                                                alt="<?php echo esc_attr($service['title'] ?? ''); ?>"
                                                class="service-image"
                                                loading="lazy">
                                        <?php endif; ?>
                                        <div class="service-overlay"></div>
                                    </a>
                                    <a href="<?php echo esc_url($service['url'] ?? '#'); ?>"
                                        class="service-name-link"
                                        title="<?php echo esc_attr($service['title'] ?? ''); ?>">
                                        <p class="service-name"><?php echo esc_html($service['title'] ?? ''); ?></p>
                                    </a>
                                </div>
                            <?php
                                $delay++;
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Navigation Arrows -->
                <div class="carousel-navigation">
                    <button class="nav-button prev-btn" id="<?php echo esc_attr($block_id); ?>-prev" aria-label="Previous services">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 18L9 12L15 6" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button class="nav-button next-btn" id="<?php echo esc_attr($block_id); ?>-next" aria-label="Next services">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 18L15 12L9 6" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php
    return ob_get_clean();
}

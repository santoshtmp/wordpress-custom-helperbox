<?php
$id = '' . $block['id'];
$className = '';

if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= 'home-banner align' . $block['align'];
}


$banner_title = (get_field('banner_title')) ?: "Unmasking Asia Pacific's Stateless Reality Through Data";
$banner_excerpt = (get_field('excerpt')) ?: "Statelessness Encyclopedia Asia Pacific (SEAP) - A report of over two years of research and consultations on Asia Pacific’s statelessness by <span class='font-bold'> Nationality for All (NFA).</span>";
$buttons = (get_field('buttons')) ?: [];
$logo = (get_field('logo')) ?: get_template_directory_uri() . '/assets/images/nfa-logo-full.svg';
$logo_url = (get_field('logo_url')) ?: '';

$sub_regions_num = count(get_all_sub_regions());
$countries_num = count(get_all_countries());
$stakeholders_num = count(get_all_stakeholder());

?>


<section id="<?php echo $id; ?>" class="<?php echo esc_attr($className); ?> !mt-0">

    <div class="hero-section relative pt-[74px]">
        <div class="relative hero-banner-wrapper">
            <div class="relative z-40 pointer-events-none container grid md:grid-cols-12">
                <div
                    class="pointer-events-auto mt-6 sm:mt-14 md:mt-24  2xl:mt-28 hero-section__content  lg:col-start-2 md:col-span-7 lg:col-span-6">
                    <div class="max-sm:max-w-[298px] max-md:max-w-[340px]">
                        <h1 class="max-w-md text-b-40 leading-none">
                            <?php echo $banner_title; ?>
                        </h1>
                        <div class="mt-5 md:mt-4 leading-loose paragraph-small">
                            <?php echo $banner_excerpt; ?>
                        </div>
                    </div>
                   <?php echo explore_drop_down_buttons($buttons);?>
                </div>
            </div>


            <div class="absolute overflow-hidden inset-0">
                <div
                    class="relative top-[88px] sm:top-0 left-[50%] max-w-[90%] sm:max-w-[65%] lg:max-w-[50%] min-[1920px]:max-w-[45%] h-auto sm:h-full">
                    <div class="mask" id="move-area">
                        <img src="<?php echo get_template_directory_uri() .
                                        '/assets/images/seap-boy.png'; ?>" alt="" id="skew-image" class="boy-img" loading="lazy">
                        <img src="<?php echo get_template_directory_uri() .
                                        '/assets/images/seap-boy.png;' ?>" alt="" id="skew-image2" class="boy-img" loading="lazy">
                        <img src="<?php echo get_template_directory_uri() .
                                        '/assets/images/seap-boy.png;' ?>" alt="" id="skew-image3" class="boy-img" loading="lazy">
                        <img src="<?php echo get_template_directory_uri() .
                                        '/assets/images/seap-bg.png;' ?>" class="seap-bg">
                    </div>
                </div>
            </div>

            <img src="<?php echo get_template_directory_uri() .
                            '/assets/images/left-map.png'; ?>" alt="Map image"
                class="absolute bottom-0 max-lg:hidden  max-md:max-h-[440px] mix-blend-multiply">
        </div>
    </div>

    <div class="grid gap-y-12 md:grid-cols-12 justify-center max-md:mt-20 mb-20 md:mb-36 container">
        <div class="flex md:items-end gap-x-4 xs:gap-x-12 md:col-start-2 md:col-span-6">
            <div>
                <p class="display-small md:text-[42px] md:leading-none text-b-40">
                    <?php echo sprintf("%02d", $sub_regions_num); ?>
                </p>
                <p class="mt-0.5 label-medium text-n-60">Sub-Regions</p>
            </div>
            <div>
                <p class=" display-small md:text-[42px] md:leading-none  text-b-40">
                    <?php echo sprintf("%02d", $countries_num); ?>
                </p>
                <p class="mt-0.5 label-medium text-n-60">Countries</p>
            </div>
            <div>
                <p class="display-small md:text-[42px] md:leading-none text-b-40">
                    <?php echo sprintf("%02d", $stakeholders_num); ?>
                </p>
                <p class="mt-0.5 label-medium text-n-60">Stakeholders</p>
            </div>
        </div>
        <div class="md:col-span-5 max-md:text-center ">
            <p class="label-medium text-n-60">An initiative by</p>
            <a href="<?php echo $logo_url; ?>">
                <img src="<?php echo $logo; ?>" alt="Logo full" class=" mt-2 sm:mt-4 h-10 sm:h-14">
            </a>
        </div>
    </div>
</section>
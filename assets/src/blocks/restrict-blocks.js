/**
 * Unregister specific embed block variations.
 *  https://developer.wordpress.org/news/2024/01/29/how-to-disable-specific-blocks-in-wordpress/
 *  https://developer.wordpress.org/reference/hooks/allowed_block_types_all/
 * 
 */
wp.domReady(function () {
    // 
    const unregisterBlocks = [
        'core/legacy-widget',
        'core/widget-group',
        'core/archives',
        'core/avatar',
        'core/block',
        'core/calendar',
        'core/categories',
        'core/footnotes',
        'core/navigation',
        'core/query',
        'core/query-title',
        'core/latest-posts',
        'core/page-list',
        'core/tag-cloud',
        'core/post-terms',
        'core/freeform'
    ];
    unregisterBlocks.forEach(block => {
        if (wp.blocks.getBlockType(block)) {
            wp.blocks.unregisterBlockType(block);
        }
    });

    // Disable block variation for the core/group block
    const groupVariations = [
        'group-stack',
        'group-row'
    ];
    groupVariations.forEach(variation => {
        wp.blocks.unregisterBlockVariation('core/group', variation);
    });
    // Disable block variations for the core/embed block
    const embedVariations = [
        'wordpress',
        'crowdsignal',
        'soundcloud',
        'spotify',
        'flickr',
        'vimeo',
        'animoto',
        'cloudup',
        'collegehumor',
        'dailymotion',
        'funnyordie',
        'hulu',
        'imgur',
        'issuu',
        'kickstarter',
        'meetup-com',
        'mixcloud',
        'photobucket',
        'polldaddy',
        'reddit',
        'reverbnation',
        'screencast',
        'scribd',
        'slideshare',
        'smugmug',
        'speaker',
        'ted',
        'tumblr',
        'videopress',
        'wordpress-tv',
        'speaker-deck',
        'amazon-kindle',
        'wolfram-cloud'
    ];
    embedVariations.forEach(variation => {
        wp.blocks.unregisterBlockVariation('core/embed', variation);
    });


});
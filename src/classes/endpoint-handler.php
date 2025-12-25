<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


/**
 * 
 * custom api endpoint and slug
 * 1. https://developer.wordpress.org/apis/
 * 2. https://developer.wordpress.org/rest-api/reference/
 * 3. https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/
 * 
 */
add_filter('rest_url_prefix', function () {
    return 'api';
});

/**
 * 
 * include api path
 * 
 */
$include_paths = [
    __DIR__ . '/api',
    __DIR__ . '/ajax',

];
requires_paths_files($include_paths);

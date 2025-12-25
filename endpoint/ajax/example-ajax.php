<?php


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Example ajax 
 */
function ajax_call_example()
{

    // // Verify _nonce
    // if (!isset($_GET['_nonce']) || !wp_verify_nonce($_GET['_nonce'])) {
    //     echo "session timout ".$_GET['_nonce'];
    //     wp_die();
    // }
    // $project_id =    isset($_POST['project_id']) ? $_POST['project_id'] : '';

    echo "ajax_call_example Example";
    // Always die in functions echoing AJAX content
    wp_die();
}
add_action('wp_ajax_ajax_call_example', 'ajax_call_example');
add_action('wp_ajax_nopriv_ajax_call_example', 'ajax_call_example');

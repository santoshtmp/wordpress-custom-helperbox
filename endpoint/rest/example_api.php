<?php



if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * 
 * https://developer.wordpress.org/rest-api/reference/comments/
 * https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/
 * 
 * API =  /api/data/v1/example_api?_nonce=
 * 
 */
// 

add_action('rest_api_init', function () {
    register_rest_route(
        'data/v1',
        '/example_api',
        array(
            [
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => 'get_example_api_content',
                'permission_callback' => '__return_true',
                'args' => [
                    'project_id' => [
                        "description" => 'project id.',
                        "type" => "integer",
                        'required' => true,
                        "sanitize_callback" => "absint",
                        "validate_callback" => "rest_validate_request_arg",
                        "minimum" => 1
                    ]
                ]
            ],
        ),
    );
});



/**
 * Function to get rest api data
 */
function get_example_api_content($request)
{
    try {
        // get the parameters
        $project_id = $request['project_id'];
        $data =  [];
        $response_info = [
            "status" => true,
            "data" => $data,
            'message' => 'successfully access API data',
            'meta' => [
                'project_id' => $project_id,
            ]
        ];
    } catch (\Throwable $th) {
        $response_info = [
            'status' => false,
            'data' => false,
            'message' => $th->getMessage()
        ];
    }

    return $response_info;
}

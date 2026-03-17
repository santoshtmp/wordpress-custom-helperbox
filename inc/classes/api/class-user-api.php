<?php

class User_API {

   /**
     * Execute request to Moodle for SSO.
     * 
     * @param array $query Query parameters.
     * @return array Result with success status.
     */
    private function execute_moodle_request($query)
    {
        // Get Moodle URL and token
        $eb_moodle_url = eb_get_mdl_url();
        $sso_secret_key = eb_get_mdl_token();

        if (empty($eb_moodle_url)) {
            return array(
                'success' => false,
                'error' => 'Moodle URL is not configured'
            );
        }

        // Encrypt the data
        $details = http_build_query($query);
        $wdm_data = encryptString($details, $sso_secret_key);

        // Prepare request arguments
        $request_args = array(
            'body' => array('wdm_data' => $wdm_data),
            'timeout' => 100,
        );

        // Send request to Moodle
        $response = wp_remote_post($eb_moodle_url . '/auth/edwiserbridge/login.php', $request_args);

        // Handle errors
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();

            // Log error if logging function exists
            if (function_exists('\app\wisdmlabs\edwiserBridge\wdm_log_json')) {
                global $current_user;
                wp_get_current_user();
                $error_data = array(
                    'url' => $eb_moodle_url . '/auth/edwiserbridge/login.php',
                    'arguments' => $request_args,
                    'user' => isset($current_user) ? $current_user->user_login . '(' . $current_user->first_name . ' ' . $current_user->last_name . ')' : '',
                    'responsecode' => '',
                    'exception' => '',
                    'errorcode' => '',
                    'message' => $error_message,
                    'backtrace' => wp_debug_backtrace_summary(null, 0, false),
                );
                \app\wisdmlabs\edwiserBridge\wdm_log_json($error_data);
            }

            return array(
                'success' => false,
                'error' => $error_message
            );
        }

        return array(
            'success' => true
        );
    }
    
    /**
     * Handle Moodle SSO process.
     * 
     * @param object $user User object.
     * @param string $redirect_url Redirect URL.
     * @return array Result with success status and redirect URL.
     */
    private function handle_moodle_sso($user, $redirect_url = '') {
        // Ensure this method only works when pro plugin and SSO are active
        if (!$this->is_edwiser_bridge_pro_active() || !$this->is_sso_feature_enabled()) {
            return array(
                'success' => false,
                'enabled' => false,
                'reason' => 'pro_plugin_or_sso_disabled',
                'moodle_url' => '',
                'redirect_url' => $redirect_url
            );
        }

        // Check if user has a Moodle ID
        $moodle_user_id = get_user_meta($user->ID, 'moodle_user_id', true);
        if (empty($moodle_user_id)) {
            return array(
                'success' => false,
                'enabled' => false,
                'reason' => 'no_moodle_id',
                'moodle_url' => '',
                'redirect_url' => $redirect_url
            );
        }

        // Generate one-time hash for verification
        $hash = hash('md5', wp_rand(10, 1000));

        // Build query for Moodle
        $query = array(
            'moodle_user_id' => $moodle_user_id,
            'login_redirect' => $redirect_url,
            'wp_one_time_hash' => $hash,
        );

        // Execute Moodle request
        $result = $this->execute_moodle_request($query);

        if (!$result['success']) {
            return array(
                'success' => false,
                'enabled' => true,
                'reason' => 'request_failed',
                'error' => $result['error'],
                'moodle_url' => '',
                'redirect_url' => $redirect_url
            );
        }

        // Generate final Moodle URL with verification hash
        $eb_moodle_url = eb_get_mdl_url();
        $final_url = $eb_moodle_url . '/auth/edwiserbridge/login.php?login_id=' . $moodle_user_id . '&veridy_code=' . $hash;

        return array(
            'success' => true,
            'enabled' => true,
            'moodle_url' => $final_url,
            'redirect_url' => $redirect_url
        );
    }
}

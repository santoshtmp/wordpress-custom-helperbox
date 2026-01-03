<?php

/**
 * Helperbox admin settings
 *
 * @package helperbox
 * 
 */

namespace Helperbox_Plugin\admin;

/*
 * Check Settings Class
 * 
 */

class Check_Settings {
    /**
     * Check if comment feature is enabled
     * @return bool if enabled return true else false
     */
    public static function is_helperbox_disable_comment() {
        if (get_option('helperbox_disable_comment_feature', '1') == '1') {
            return true;
        }
        return false;
    }

    /**
     * Check if custom admin login is disabled
     * @return bool if disabled return true else false
     */
    public static function is_helperbox_adminlogin_enable() {
        if (get_option('helperbox_custom_adminlogin', '1') != '1') {
            return true;
        }
        return false;
    }
}

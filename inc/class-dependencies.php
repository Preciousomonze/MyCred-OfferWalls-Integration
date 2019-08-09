<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
class PK_MC_OW_Dependencies{
    private static $active_plugins;
    
    public static function init() {
        self::$active_plugins = (array) get_option('active_plugins', array());
        if (is_multisite()) {
            self::$active_plugins = array_merge(self::$active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
    }
    
    /**
     * Check if mycred exist
     * @return Boolean
     */
    public static function mycred_active_check() {
        if (!self::$active_plugins) {
            self::init();
        }
        return in_array('mycred/mycred.php', self::$active_plugins) || array_key_exists('mycred/mycred.php', self::$active_plugins);
    }

    /**
     * Check if mycred is active
     * @return Boolean
     */
    public static function is_mycred_active() {
        return self::mycred_active_check();
    }
}
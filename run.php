<?php
/**
* Plugin Name: MyCred Offer Walls Integration
* Plugin URI: https://github.com/Preciousomonze/MyCred-OfferWalls-Integration
* Description: Helps integrates mycred with some offer walls platform well :), use the shortcode <code>[mycred_ow_display_username]</code> to show the username of the current logged in user. this can be passed as tracking_id in some offerwalls scripts :)
* Author: Precious Omonze @ CodeExplorer
* Author URI: https://twitter.com/preciousomonze
* Version: 1.0
* License: GPLv2 or later
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// include dependencies file
if(!class_exists('PK_MC_OW_Dependencies')){
    include_once dirname(__FILE__) . '/inc/class-dependencies.php';
}

if(PK_MC_OW_Dependencies::is_mycred_active()){
    define('PK_MC_OW_USERNAME_SHORTCODE','mycred_ow_display_username');

    // Include the main class.
    if(!class_exists('PK_MC_OW')){
        include_once dirname(__FILE__) . '/inc/class-webhooks.php';
    }
    if(!class_exists('PK_MC_OW_Shortcodes')){
        include_once dirname(__FILE__) . '/inc/class-shortcodes.php';
    }
}
else{
    function pk_mc_ow_notice(){
        echo '<div class="error"><p>';
        _e('<strong>MyCred Offer Walls Integration</strong> plugin requires <a href="#" target="_blank">My creds</a> plugin to be active!', '');
        echo '</p></div>';
    }
    add_action('admin_notices', 'pk_mc_ow_notice', 15);
    add_action('network_admin_notices', 'pk_mc_ow_notice', 10);
}

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}    
    class PK_MC_OW_Shortcodes {
        public function __construct() {
            add_action('init', function(){
                add_shortcode( PK_MC_OW_USERNAME_SHORTCODE, array( $this, 'current_username' ) );  
            });
        }

        /**
         * Shows username
         *
         * @param array $atts
         * @param string $content
         * @return string
         */
         public function current_username($atts,$content = null){
             $result = '';
            $user = get_user_by('id',get_current_user_id());
            if($user){
                return $user->user_login;
            }
        }
    }
new PK_MC_OW_Shortcodes();

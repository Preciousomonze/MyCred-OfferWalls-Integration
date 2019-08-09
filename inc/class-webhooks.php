<?php
/**
 * Webhook class to handle custom webhook
 *
 * Helps create the webhook url and also handling whatever is sent to the url :)
 *
 * @author Precious Omonze (Code Explorer) <https://github.com/Preciousomonze>
 * License: GPLv2 or later
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'PK_MC_OW_Webhook' ) ) {
    
    class PK_MC_OW_Webhook {
    	private static $_instance = null;
        
        // These paramaters are for custom webhook
		//your url will look like https://site.com/offerwalls/post_hook
		// network_site_url( self::$webhook . DIRECTORY_SEPARATOR . self::$webhook_tag ) //this helps return the full url
   
		/**
		 * Parent wekbhook
		 * replace with a unique value you want
		 * 
		 * @var string
		 */
        private static $webhook = 'offerwalls';
		
		/**
		 * webhook tag
		 * replace with a unique value you want
		 * 
		 * @var string
		 */
        private static $webhook_tag = 'post_hook';
		/**
		 * Action to be triggered when the url is loaded
		 * replace with a unique value you want
		 * 
		 * @var string
		 */
        private static $webhook_action = 'postback_action';
		
		/**
		 * Construdor :)
		 */
        public function __construct() {
        	add_action( 'init', array( $this, 'setup' ) );
            add_action( 'parse_request', array( $this, 'parse_request' ) );            
            add_action( self::$webhook_action, array( $this, 'webhook_handler' ) );
        }
        public function setup() {
            $this->add_rewrite_rules_tags();
            $this->add_rewrite_rules();
        }
		/**
         * Handles the HTTP Request sent to your site's webhook
         */
        public function webhook_handler() {
            $input = $_POST;
            //start your payload processing here
            $wall = sanitize_text_field($input['wall']);
            //payload condition
            $p_c = false;
            switch(strtolower($wall)){
                case 'cpagrip':
                    $p_c = $this->cpa_grip_handler($input);
                break;
                case 'adscendmedia':
                //    $p_c = $this->adscend_media_handler($input);
                break;
            }
            if($p_c){//passed
                $points = sanitize_text($_GET['points']);
                $this->award_points($points);
            }
        }
        /** 
         * CPAGRIP handler
         * 
         * @return bool
         */
        public function cpa_grip_handler($input){
            $pass = 'freerobux-cpa';
            if($input['password'] == $pass){//accept

            }
        }
        /**
         * Awards points
         * 
         * @return bool
         */
        public function award_points($points){

        }

        public function parse_request( &$wp ) {
			$ini = 'pekky_';
            if( array_key_exists( self::$webhook_tag, $wp->query_vars ) ) {                
                do_action( $ini.self::$webhook_action );
                die(0);
            }
        }
        protected function add_rewrite_rules_tags() {
        	add_rewrite_tag( '%' . self::$webhook_tag . '%', '([^&]+)' );
        }
        protected function add_rewrite_rules() {
            add_rewrite_rule( '^' . self::$webhook . '/([^/]*)/?', 'index.php?' .  self::$webhook_tag . '=$matches[1]', 'top' );
        }
    }
}
new PK_MC_OW_Webhook();
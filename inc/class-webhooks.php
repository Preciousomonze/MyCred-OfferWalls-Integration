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
		 * ini prefix, leave as it is :)
		 * 
		 * @var string
		 */
        private static $ini_hook_prefix = 'pekky_';

		/**
		 * Action to be triggered when the url is loaded
		 * replace with a unique value you want
		 * 
		 * @var string
		 */
        private static $webhook_action = 'postback_action';

        /**
         * @var string
         */
        private $comment = '';

        /**
         * @var string
         */
        private $a_comment = '';

        /**
         * @var int
         */
        private $tracked_user_id = 0;

		/**
		 * Construdor :)
		 */
        public function __construct() {
        	add_action( 'init', array( $this, 'setup' ) );
            add_action( 'parse_request', array( $this, 'parse_request' ) );            
            add_action( self::$ini_hook_prefix.self::$webhook_action, array( $this, 'webhook_handler' ) );
        }
        public function setup() {
            $this->add_rewrite_rules_tags();
            $this->add_rewrite_rules();
        }
		/**
         * Handles the HTTP Request sent to your site's webhook
         */
        public function webhook_handler() {
            $input = (strpos($_SERVER['SERVER_NAME'],'localhost') !== false) ? $_GET : $_POST;
            //start your payload processing here
            foreach ($input as $key => $value){
                $input[$key] = sanitize_text_field($value);
            }
            $wall = $input['wall'];
            //payload condition
            $p_c = false;
            $cmt = 'offerwall point awarded: ';
            $a_comment = '';
            switch(strtolower($wall)){
                case 'cpagrip':
                    $p_c = $this->cpa_grip_handler($input);
                    $cmt .= $this->comment;
                break;
                case 'adscend_media':
                    $p_c = $this->adscend_media_handler($input);
                    $a_comment = 'Adscend Media';
                break;
            }
            if($p_c){//passed
                $a_comment = $this->a_comment;
                $points = sanitize_text_field($_GET['points']);
                $this->get_tracked_user($input);
                $this->award_points($points,$a_comment,$cmt);
            }
        }
        /**
         * Adscend media handler
         * 
         * @param array $input
         * @return bool
         */
        public function adscend_media_handler($input){
            $ip = '54.204.57.82';//ip from adscend media
            if($input['ip'] == $ip){//pass through
                $this->a_comment = 'adscend_media_'.$input['offerid'];
                $this->comment = $input['name'];
                return true;
            }
            return false;
        }
        /** 
         * CPAGRIP handler
         * 
         * @param array $input
         * @return bool
         */
        public function cpa_grip_handler($input){
            $pass = 'freerobux-cpa';
            $e_user_agent = trim(strtolower('CPAGRIP/Postback Tool/2.0'));
            $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? trim(strtolower($_SERVER['HTTP_USER_AGENT'])) : '';
            if($input['password'] == $pass && $e_user_agent == $user_agent){//accept
                $this->a_comment = 'cpa_grip_'.$input['offer_id'];
                $this->comment = $input['offer_name'];
                return true;
            }
            return false;
        }
        /**
         * Awards points
         * 
         * @param int $points
         * @param string $approved_comment (optional)
         * @param string $comment (optional)
         * @return bool
         */
        public function award_points($points,$approved_comment = 'offerwall_point',$comment = '10 points for completion'){
            $user_id = $this->tracked_user_id;
            return mycred_add( $approved_comment, $user_id, $points, $comment );
        }

        /**
         * Gets the tracked user data that got the offer :)
         * 
         * should either be the user_id or username
         * 
         * @param array $input
         * @return int user id
         */
        public function get_tracked_user($input){
            $t_id = $input['tracking_id'];
            $user = '';
            $field = 'login';
            if(is_int($t_id))
                $field = 'id';
            $user = get_user_by($field,$t_id);
            if($user){
<<<<<<< HEAD
                $this->tracked_user_id = $user->ID;
                return $user->ID;
            }
=======
$this->tracked_user_id = $user->ID;
                return $user->ID;
}
>>>>>>> b6e5e093e46ffb6a06292ba4afa873436a4b5173
            return 0;
        }
        
        public function parse_request( &$wp ) {
			$ini = self::$ini_hook_prefix;
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

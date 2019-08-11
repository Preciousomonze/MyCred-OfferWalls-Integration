<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}    
    class PK_MC_OW_Shortcodes {
        public function __construct() {
            add_action('init', function(){
                //add_action( 'rest_api_init', array($this, 'rest_api'));
                //add_filter('the_content', 'do_shortcode');//for wp_bakery to run shortcodes
                add_shortcode( PK_MC_OW_USERNAME_SHORTCODE, array( $this, 'current_username' ) );  
            }); 
            /*add_action( 'after_setup_theme', function(){
                add_filter('the_content', 'do_shortcode');//for wp_bakery to run shortcodes
            });*/
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
            return $result;
        }
        /**
        * Modify REST API content for pages to force
        * shortcodes to render since Visual Composer does not do this :)
        * Thanks to some github guys @https://github.com/WP-API/WP-API/issues/2578
        */
        public function rest_api(){
            register_rest_field(
            'page',
            'content',
            array(
                 'get_callback'    => array($this,'do_shortcodes'),
                 'update_callback' => null,
                 'schema'          => null,
            )
            );
        }

        /**
         * Callback function for the api
         */
        public function do_shortcodes( $object, $field_name, $request ){
            global $post;
            $post = get_post ($object['id']);
            if(class_exists('WPBMap')){
                WPBMap::addAllMappedShortcodes(); // This does all the work
                $output['rendered'] = apply_filters( 'the_content', $post->post_content );
                return $output;
            }
            else{// do nothing
                return apply_filters( 'the_content', $post->post_content );
            }
        }
    }
new PK_MC_OW_Shortcodes();

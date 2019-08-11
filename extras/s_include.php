<?php
error_reporting(E_ERROR | E_PARSE);
$pubid = 33129;
$using_cloudflare = false; //change this to true if you are using cloudflare to host this site.
$custom_domain = ''; //this is the url used for the offer links and exit splash(if enabled) leave blank to allow cpagrip to choose best domain. (domain must belong to cpagrip since assets and click-through logic is managed by cpagrip servers)
/**
 * added by preciousomonze(https://twitter.com/preciousomonze)
 * This was addedd because when you include this script in the raw html/js element of wp bakery
 * passing a tracking_id param with value of current logged in username with shortcode isnt working,
 * because the wpbakery element doesnt read shortcodes, so we needed to put the tracking id value here
 * 
 * So leave this for now, contact me for stuff :).
 */
if(!isset($_REQUEST['tracking_id'])){
    include_once('../../../wp-config.php');//location based on where this file is, getting the wp-config.pgp file
    $user = get_user_by('id',get_current_user_id());
    if($user){
        $_REQUEST['tracking_id'] = $user->user_login;
    }
}
/** added part ended */
//--------------------------------
//DO NOT EDIT BELOW THIS LINE.
$id = $_REQUEST['id'];
if($using_cloudflare){
	$visitor_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
}else{
	$visitor_ip = $_SERVER['REMOTE_ADDR'];
}
$ref = getenv("HTTP_REFERER");
$ref = base64_encode($ref);
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$user_agent = base64_encode($user_agent);
$script_remote_url = 'http://www.cpagrip.com/script_include_proxy.php?custom_domain='.$custom_domain.'&id='.$id.'&visitor_ip='.$visitor_ip.'&pubid='.$pubid.'&ref='.$ref.'&user_agent='.$user_agent.'&tracking_id='.$_REQUEST['tracking_id'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $script_remote_url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$return_string = curl_exec($ch);
curl_close($ch);
echo $return_string;
?>
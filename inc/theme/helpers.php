<?php

/**
 * Changes the tile count
 *
 * @return null
 * @author  @sameast
 */
if ( ! function_exists ( 's3bubble_tile_count' ) ) {
    function s3bubble_tile_count() {
        return (int) get_theme_mod( 'streamium_tile_count', 6 );
    }
}

/**
 * Adds the main menu
 *
 * @return null
 * @author  @sameast
 */
function streamium_register_menu() {
    register_nav_menu('streamium-header-menu',__( 'Header Menu', 'streamium' ));
}
add_action( 'init', 'streamium_register_menu' );

/**
 * Fix for the main menu
 *
 * @return null
 * @author  @sameast
 */
function streamium_remove_ul( $menu ){
    return preg_replace( array( '#^<ul[^>]*>#', '#</ul>$#' ), '', $menu );
}
add_filter( 'wp_nav_menu', 'streamium_remove_ul' );

/**
 * Webview only style for native app
 *
 * @return null
 * @author  @sameast
 */
if ( ! function_exists ( 'streamium_check_webview' ) ) {
    function streamium_check_webview() {

        if (isset($_GET['webview']) || isset($_COOKIE["webview"])) {
            
            setcookie('webview', true);
            wp_enqueue_style('streamium-webview', get_template_directory_uri() . '/production/css/webview.min.css', array(), s3bubble_cache_version());

        }else{
            unset($_COOKIE['webview']);
        }

    }
    add_action( 'init', 'streamium_check_webview' );
}

/*
* Recommended plugins managment and notifications
* @author sameast
* @none
*/
require_once get_template_directory() . '/inc/recommended-plugins/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'sb_register_required_plugins' );

function sb_register_required_plugins() {
    $plugins = array(
      array(
        'name'      => 'Easy Theme and Plugin Upgrades',
        'slug'      => 'easy-theme-and-plugin-upgrades',
        'required'  => false,
      ),
      array(
        'name'      => 'WP Extended Search',
        'slug'      => 'wp-extended-search',
        'required'  => false,
  	  ),
      array(
        'name'      => 'Post Types Order',
        'slug'      => 'post-types-order',
        'required'  => false,
      ),
      array(
        'name'      => 'Force Regenerate Thumbnails',
        'slug'      => 'force-regenerate-thumbnails',
        'required'  => false,
      )
    );

    $config = array(
      'id'           => 's3bubble',
      'default_path' => '',
      'menu'         => 'tgmpa-install-plugins',
      'parent_slug'  => 'themes.php',
      'capability'   => 'edit_theme_options',
      'has_notices'  => true,
      'dismissable'  => true,
      'dismiss_msg'  => '',
      'is_automatic' => false
    );

    tgmpa( $plugins, $config );
}

/**
 * Make sure the self hosted plugin is not installed
 */
function streamium_check_for_active_plugins() {

  function streamium_check_plugin_isnot_active_notice__error() {

      $class = 'notice notice-error notice-demo-data';
      $message = __( '!IMPORTANT you have the S3Bubble self hosted plugin installed this is not needed with this theme all functionality is built in please remove the S3Bubble AWS Self Hosted Plugin', 'streamium' );

      printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ));
  }
  if ( is_plugin_active( 's3bubble-amazon-web-services-oembed-media-streaming-support/s3bubble-oembed.php' ) ) {
    add_action( 'admin_notices', 'streamium_check_plugin_isnot_active_notice__error' );
  }

}
add_action( 'admin_init', 'streamium_check_for_active_plugins' );


/*
* Adds a notice to the admin to install demo data
* @author sameast
* @none
*/
function streamium_dummy_xml_admin_notice__error() {
    $class = 'notice notice-info notice-demo-data is-dismissible';
    $pluginUrl = admin_url( 'plugin-install.php?s=WooCommerce&tab=search&type=term' );
    $message = __( 'Get setup quickly by installing our demo data', 'streamium' );

    printf( '<div class="%1$s"><p>%2$s <a id="demo-data" href="%3$s">Install demo data</a></p></div>', esc_attr( $class ), esc_html( $message ), admin_url('themes.php?page=streamium_demo_installer'));
}

if(get_option('notice_demo_data') == 1) {
  add_action( 'admin_notices', 'streamium_dummy_xml_admin_notice__error' );
}

/**
 * Adds a notice to the admin if premium is not enabled
 *
 * @return null
 * @author  @sameast
 */
function premium_admin_notice__error() {

	$class = 'notice notice-info notice-premium is-dismissible';
	$message = __( 'Upgrade to Premium to unlock some great features. Ratings, Video Resume, Self hosted, Background Videos, Trailers and much more', 'streamium' );

	printf( '<div class="%1$s"><p>%2$s<a href="%3$s">Upgrade Now!</a></p></div>', esc_attr( $class ), esc_html( $message ), esc_url( 'https://s3bubble.com/pricing' ) );
}

if(!get_theme_mod( 'streamium_enable_premium' ) && get_option('notice_premium') == 1) {
	add_action( 'admin_notices', 'premium_admin_notice__error' );
}

/**
 * Dismiss premium notice with ajax
 *
 * @return null
 * @author  @sameast
 */
if ( ! function_exists ( 'dismiss_premium_notice' ) ) {
    function dismiss_premium_notice(){
        update_option('notice_premium', 0);
        echo json_encode(array('success' => true, 'message' => __('Notice dismissed', 'streamium' )));
        die();
    }
    // Enable the user with no privileges to run dismiss_premium_notice() in AJAX
    add_action('wp_ajax_ajaxnopremium', 'dismiss_premium_notice');
    add_action('wp_ajax_nopriv_ajaxnopremium', 'dismiss_premium_notice');
}

/**
 * Dismiss demo data notice with ajax
 *
 * @return null
 * @author  @sameast
 */
if ( ! function_exists ( 'dismiss_demo_data_notice' ) ) {
    function dismiss_demo_data_notice()
    {
        update_option('notice_demo_data', 0);
        echo json_encode(array('success' => true, 'message' => __('Notice dismissed','streamium')));
        die();
    }
    // Enable the user with no privileges to run dismiss_demo_data_notice() in AJAX
    add_action('wp_ajax_ajaxnodemo', 'dismiss_demo_data_notice');
    add_action('wp_ajax_nopriv_ajaxnodemo', 'dismiss_demo_data_notice');
}

/**
 * Fix to flush urls
 *
 * @return null
 * @author  @sameast
 */
if ( ! function_exists ( 'streamium_flush_rewrite_rules' ) ) {
    function streamium_flush_rewrite_rules(){
        flush_rewrite_rules();
    }
    add_action( 'admin_init', 'streamium_flush_rewrite_rules' );
}

/**
 * Check for connected website
 *
 * @return null
 * @author  @sameast
 */
function streamium_connection_checks() {

 	$nonce = $_REQUEST['connected_nonce'];
 	$state = $_REQUEST['connected_state'];
	if ( ! wp_verify_nonce( $nonce, 'streamium_connected_nonce' ) ) {

	    die( 'Security check failed' . $nonce );

	}

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	if($state === "false"){

    		set_theme_mod( "streamium_enable_premium", true );
    		echo json_encode(
		    	array(
		    		'error' => false,
		    		'message' => 'Premium has been added'
		    	)
		    );

    	}else{

    		set_theme_mod( "streamium_enable_premium", false );
    		echo json_encode(
		    	array(
		    		'error' => false,
		    		'message' => 'Premium has been removed'
		    	)
		    );
    	}

        die();

    }
    else {

        exit();

    }

}

add_action( 'wp_ajax_streamium_connection_checks', 'streamium_connection_checks' );

/**
 * Is mobile check for theme styling
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_device($type){

	// include classes
 	if( wp_is_mobile() ){
	 	$device = array('count' => 2);
	}else{
		$device = array('count' => 6);
	}
	return $device[$type];

}

/**
 * appends the stramium reviews query for search
 *
 * @return null
 * @author  @sameast
 */
function streamium_search_distinct() {
	return "wp_posts.*, COUNT(wp_streamium_reviews.post_id) AS reviews";
}

/**
 * joins the stramium reviews query for search
 *
 * @return null
 * @author  @sameast
 */
function streamium_search_join($join) {
    global $wpdb;
    $posts_stats_view_join = "LEFT JOIN wp_streamium_reviews ON ($wpdb->posts.ID = wp_streamium_reviews.post_id)";
    $join .= $posts_stats_view_join;
    return $join;
}

/**
 * groups the stramium reviews query for search
 *
 * @return null
 * @author  @sameast
 */
function streamium_search_groupby($groupby) {
    global $wpdb;
    $groupby = "wp_streamium_reviews.post_id";
    return $groupby;
}

/**
 * joins the stramium reviews query for search
 *
 * @return null
 * @author  @sameast
 */
function streamium_search_orderby($orderby_statement) {
	global $wpdb;
	$orderby_statement = "reviews DESC";
	return $orderby_statement;
}

/**
 *
 * @param Array $list
 * @param int $p
 * @return multitype:multitype:
 * @link http://www.php.net/manual/en/function.array-chunk.php#75022
 */
function partition(Array $list, $p) {
    $listlen = count($list);
    $partlen = floor($listlen / $p);
    $partrem = $listlen % $p;
    $partition = array();
    $mark = 0;
    for($px = 0; $px < $p; $px ++) {
        $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
        $partition[$px] = array_slice($list, $mark, $incr);
        $mark += $incr;
    }
    return $partition;
}

/**
 *
 * @param Array $list
 * @param int $p
 * @return streamGroupSeasons:
 */
function streamGroupSeasons($array, $key) {
    $return = array();
    foreach($array as $val) {
        $return[$val[$key]][] = $val;
    }
    ksort($return);
    return $return;
}

/**
 *
 * @param Array $list
 * @param int $p
 * @return multitype:multitype:
 */
function orderCodes($postId) {

  $episodes = get_post_meta($postId, 'repeatable_fields' , true);

  if(empty($episodes)){
    return false;
  }

  // Group by seasons
  $firstSort = streamGroupSeasons($episodes,'seasons');

  $codes = [];
  foreach ($firstSort as $key => $flatten) {
    foreach ($flatten as $key => $value) {
      
      $codes[] = (isset($value['service']) && $value['service'] != '') ? $value['service'] : $value['codes'];

    }
  }

  return array(
    "seasons" => count($firstSort),
    "episodes" => count($codes),
    "codes" => $codes
  );

}

/**
 *
 * @param  Filter to fix Wordpress not using https for urls
 * @param int $p
 * @return filter
 */
function ssl_post_thumbnail_urls($url, $post_id) {

  //Skip file attachments
  if(!wp_attachment_is_image($post_id)) {
    return $url;
  }

  //Correct protocol for https connections
  list($protocol, $uri) = explode('://', $url, 2);

  if(is_ssl()) {
    if('http' == $protocol) {
      $protocol = 'https';
    }
  } else {
    if('https' == $protocol) {
      $protocol = 'http';
    }
  }

  return $protocol.'://'.$uri;
}
add_filter('wp_get_attachment_url', 'ssl_post_thumbnail_urls', 10, 2);

/**
 *
 * @param  Add a body class for fixed nav
 * @param int $p
 * @return filter
 */
function streamium_body_class( $classes ) {

    // Add fixed nav class for home only
    if(is_home() ){
      //$classes[] = 'nav-is-fixed';
    }

    return $classes;
}
add_action( 'body_class', 'streamium_body_class');

/**
 *
 * @param Checks for ssl returns https if needed
 * @param int $p
 * @return filter
 */
function get_theme_mod_ssl($mod_name){
    if (is_ssl()) {
      return str_replace(array('http:', 'https:'), '', get_theme_mod($mod_name));
    }else{
      return get_theme_mod($mod_name);
    }
}
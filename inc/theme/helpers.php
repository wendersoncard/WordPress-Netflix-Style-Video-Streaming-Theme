<?php

// remove admin bar
add_filter('show_admin_bar', '__return_false');

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

/*
* Recommended plugins managment and notifications
* @author jonathansmith
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
      ), 
      array(
        'name'      => 's2member',
        'slug'      => 's2member',
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
      $message = __( '!IMPORTANT you have the S3Bubble self hosted plugin installed this is not needed with this theme all functionality is built in please remove the S3Bubble AWS Self Hosted Plugin. ', 'streamium' );

      printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ));
  }
  if ( is_plugin_active( 's3bubble-amazon-web-services-oembed-media-streaming-support/s3bubble-oembed.php' ) ) {
    add_action( 'admin_notices', 'streamium_check_plugin_isnot_active_notice__error' );
  }

}
add_action( 'admin_init', 'streamium_check_for_active_plugins' );


/*
* Adds a notice to the admin to install demo data
* @author jonathansmith
* @none
*/
function streamium_dummy_xml_admin_notice__error() {
    $class = 'notice notice-info notice-demo-data is-dismissible';
    $pluginUrl = admin_url( 'plugin-install.php?s=WooCommerce&tab=search&type=term' );
    $message = __( 'Get setup quickly by installing our demo data. ', 'streamium' );

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
	$message = __( 'Upgrade to Premium to unlock some great features. Ratings, Video Resume, Self hosted, Background Videos, Trailers and much more. ', 'streamium' );

	printf( '<div class="%1$s"><p>%2$s<a href="%3$s">Upgrade Now!</a></p></div>', esc_attr( $class ), esc_html( $message ), esc_url( 'https://s3bubble.com/pricing' ) );
}

if(!get_theme_mod( 'streamium_enable_premium' ) && get_option('notice_premium') == 1) {
	add_action( 'admin_notices', 'premium_admin_notice__error' );
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
 * @return multitype:multitype:
 * @link http://www.php.net/manual/en/function.array-chunk.php#75022
 */
function orderCodes($postId) {

  $episodes = get_post_meta($postId, 'repeatable_fields' , true);

  if(empty($episodes)){
    return false;
  }

  // Order the list
  $positions = array();
  foreach ($episodes as $key => $row){
      $positions[$key] = $row['positions'];
  }
  array_multisort($positions, SORT_ASC, $episodes);

  // Sort the seasons
  $result = array();
  $codes = array();
  foreach ($episodes as $v) {
      $seasons = $v['seasons'];
      if (!isset($result[$seasons])) $result[$seasons] = array();
      $result[$seasons][] = $v;
  }

  $codes = [];
  foreach (array_reverse($result) as $key => $flatten) {
    foreach ($flatten as $key => $value) {
      $codes[] = $value['codes'];
    }
  }

  return array(
    "seasons" => count($result),
    "episodes" => count($codes),
    "codes" => $codes
  );

}
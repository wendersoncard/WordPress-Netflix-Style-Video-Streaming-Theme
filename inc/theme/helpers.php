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
        'name'      => 'Search Everything',
        'slug'      => 'search-everything',
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
      'is_automatic' => false,
      'message'      => 'To get the most out of Streamium we recommend that you install the following plugins',
    );

    tgmpa( $plugins, $config );
}


/*
* Adds a notice to the admin to install demo data
* @author jonathansmith
* @none
*/
function streamium_dummy_xml_admin_notice__error() {
    $class = 'notice notice-info notice-demo-data is-dismissible';
    $pluginUrl = admin_url( 'plugin-install.php?s=WooCommerce&tab=search&type=term' );
    $message = __( 'Get setup quickly by installing our demo data. ', 'sample-text-domain' );

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
function streamium_extra_body_class( $classes ) {

 	// include classes
 	$detect = new Mobile_Detect;
    if ( $detect->isTablet() ) {
 		$classes[] = 'streamium-tablet';
	}else if( $detect->isMobile() ){
	 	$classes[] = 'streamium-mobile';
	}else{
		$classes[] = 'streamium-desktop';
	}
    return $classes;

}

add_filter( 'body_class','streamium_extra_body_class' );


/**
 * Is mobile check for theme styling
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_device($type){

	// include classes
 	$detect = new Mobile_Detect;
    if ( $detect->isTablet() ) {
 		$device = array('count' => 4, 'class' => 'col-xs-3', 'device' => 'tablet');
	}else if( $detect->isMobile() ){
	 	$device = array('count' => 2, 'class' => 'col-xs-6', 'device' => 'mobile');
	}else{
		$device = array('count' => 5, 'class' => 'col-xs-5ths', 'device' => 'desktop');
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

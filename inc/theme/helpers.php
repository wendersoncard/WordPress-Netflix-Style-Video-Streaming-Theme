<?php

// remove admin bar
add_filter('show_admin_bar', '__return_false');

/**
 * Is mobile check for theme styling
 *
 * @return bool
 * @author  @sameast
 */
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
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


/*
* Adds a main nav menu if needed
* @author sameast
* @none
*/ 
function streamium_run_plugin_checks() {

	// admin alert
	function easy_theme_upgrades_admin_notice__error() {
		$class = 'notice notice-info is-dismissible';
		$pluginUrl = admin_url( 'plugin-install.php?s=Easy+Theme+and+Plugin+Upgrades&tab=search&type=term' );
		$message = __( 'We highly recommend installing the Easy Theme and Plugin Upgrades plugin to make theme upgrades go smoothly. ', 'sample-text-domain' );

		printf( '<div class="%1$s"><p>%2$s<a href="%3$s">Install Now!</a> | <a href="%3$s">How to Upgrade!</a></p></div>', esc_attr( $class ), esc_html( $message ), esc_url( $pluginUrl ) ); 
	}

	// admin alert
	function wooCommerce_admin_notice__error() {
		$class = 'notice notice-info is-dismissible';
		$pluginUrl = admin_url( 'plugin-install.php?s=WooCommerce&tab=search&type=term' );
		$message = __( 'Streamium works in conjunction with WooCommerce to allow you to take online payments. ', 'sample-text-domain' );

		printf( '<div class="%1$s"><p>%2$s<a href="%3$s">Install Now!</a></p></div>', esc_attr( $class ), esc_html( $message ), esc_url( $pluginUrl ) ); 
	}

	// admin alert
	function wooCommerce_membership_subscriptio_admin_notice__error() {
		$class = 'notice notice-info is-dismissible';
		$pluginUrl = admin_url( 'plugin-install.php?s=WooCommerce&tab=search&type=term' );
		$message = __( 'The WooCommerce Membership plugin and the Subscriptio are required to allow you to take online payments with this theme. ', 'sample-text-domain' );

		printf( '<div class="%1$s"><p>%2$s<a href="%3$s">Install Subscriptio!</a> | <a href="%4$s">Install Woocommerce Membership!</a></p></div>', esc_attr( $class ), esc_html( $message ), esc_url( 'https://codecanyon.net/item/subscriptio-woocommerce-subscriptions/8754068' ), esc_url( 'https://codecanyon.net/item/woocommerce-membership/8746370' ) ); 
	}

	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$all_plugins = get_plugins();

	// loop through and run checks
	$formatArray = array();
	foreach ($all_plugins as $key => $value) {
		array_push($formatArray, $value['Name']);
	}

	if (!in_array("Easy Theme and Plugin Upgrades", $formatArray)) {
	    add_action( 'admin_notices', 'easy_theme_upgrades_admin_notice__error' );
	}
	if (!in_array("WooCommerce", $formatArray)) {
	    add_action( 'admin_notices', 'wooCommerce_admin_notice__error' );
	}
	if ((!in_array("WooCommerce Membership", $formatArray)) || (!in_array("Subscriptio", $formatArray))) {
	    add_action( 'admin_notices', 'wooCommerce_membership_subscriptio_admin_notice__error' );
	}

	// Dummy content
	// admin alert
	function streamium_dummy_xml_admin_notice__error() {
		$class = 'notice notice-info is-dismissible';
		$pluginUrl = admin_url( 'plugin-install.php?s=WooCommerce&tab=search&type=term' );
		$message = __( 'Get setup quickly download this xml file and import using the WordPress importer under the tools menu. ', 'sample-text-domain' );

		printf( '<div class="%1$s"><p>%2$s Right click and save link as <a href="%3$s">Download Dummy XML!</a></p></div>', esc_attr( $class ), esc_html( $message ), esc_url( 'https://s3.amazonaws.com/streamium-theme-cdn/themexml/streamium.wordpress.2017-04-11.xml' ) ); 
	}

	add_action( 'admin_notices', 'streamium_dummy_xml_admin_notice__error' );

}

add_action( 'init', 'streamium_run_plugin_checks' );

function streamium_checks() {

    // Setup premium
    $streamium_connected_website = get_option("streamium_connected_website");
    $streamium_connected_nonce = wp_create_nonce( 'streamium_connected_nonce' );
    wp_enqueue_script('streamium-checks', get_template_directory_uri() . '/dist/js/player.min.js', array('jquery'), '', false);
    wp_localize_script( 'streamium-checks', 'streamium_checks_object', 
        array(
        	'ajax_url' => admin_url( 'admin-ajax.php'), 
            'connected_website' => $streamium_connected_website,
            'connected_nonce' => $streamium_connected_nonce
        )
    );

}

add_action('admin_enqueue_scripts', 'streamium_checks');

/**
 * Adds a notice to the admin if premium is not enabled
 *
 * @return null
 * @author  @sameast
 */
function premium_admin_notice__error() {

	$class = 'notice notice-info is-dismissible';
	$message = __( 'Upgrade to Premium to unlock some great features. Ratings, Video Resume, Self hosted, Background Videos, Trailers and much more. ', 'streamium' );

	printf( '<div class="%1$s"><p>%2$s<a href="%3$s">Upgrade Now!</a></p></div>', esc_attr( $class ), esc_html( $message ), esc_url( 'https://s3bubble.com/pricing' ) ); 
}

if(!get_theme_mod( 'streamium_enable_premium' )){

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
		    		'message' => 'Premmium has been added'
		    	)
		    );
    		
    	}else{

    		set_theme_mod( "streamium_enable_premium", false );
    		echo json_encode(
		    	array(
		    		'error' => false,
		    		'message' => 'Premmium has been removed'
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
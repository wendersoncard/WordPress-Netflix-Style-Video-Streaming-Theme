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

/*
* Adds a main nav menu if needed
* @author sameast
* @none
*/ 
function streamium_register_menu() {
    register_nav_menu('streamium-header-menu',__( 'Header Menu', 'streamium' ));
}
add_action( 'init', 'streamium_register_menu' );

/*
* Fix needed for man menu
* @author sameast
* @none
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
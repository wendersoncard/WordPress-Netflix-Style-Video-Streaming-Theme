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
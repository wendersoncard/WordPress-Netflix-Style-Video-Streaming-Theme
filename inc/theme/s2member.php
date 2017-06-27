<?php

// You only need one membership level
//define('MEMBERSHIP_LEVELS', 1);

// Add a login button to the menu
function streamium_add_login_logout_register_menu( $items, $args ) {

	if ( $args->theme_location != 'streamium-header-menu' ) {
		return $items;
 	}
 
 	if ( is_user_logged_in() ) {
 
 		$items .= '<li><a href="' . wp_logout_url() . '">' . __( 'Logout' ) . '</a></li>';
 
 	} else {
 	
 		$items .= '<li><a href="' . wp_login_url() . '">' . __( 'Login' ) . '</a></li>';

 	}
 	return $items;

}
 
add_filter( 'wp_nav_menu_items', 'streamium_add_login_logout_register_menu', 199, 2 );
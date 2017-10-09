<?php

function streamium_s2member_auth_menu( $items, $args ) {

	if ( $args->theme_location != 'streamium-header-menu' ) {
		return $items;
 	}
 	if ( is_user_logged_in() ) {
 		$items .= '<li><a class="s2member-auth" href="' . wp_logout_url() . '">' . __( 'Log Out' ) . '</a></li>';
 	} else {
 		$items .= '<li><a class="s2member-auth" href="' . wp_login_url() . '">' . __( 'Log In' ) . '</a></li>';
 	}
 	return $items;

}
 
add_filter( 'wp_nav_menu_items', 'streamium_s2member_auth_menu', 10, 2 );
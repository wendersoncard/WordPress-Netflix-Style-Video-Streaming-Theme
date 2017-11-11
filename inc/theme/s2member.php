<?php

<<<<<<< HEAD
function streamium_add_login_logout_register_menu( $items, $args ) {
=======
function streamium_s2member_auth_menu( $items, $args ) {
>>>>>>> version2

	if ( $args->theme_location != 'streamium-header-menu' ) {
		return $items;
 	}
 	if ( is_user_logged_in() ) {
 		$items .= '<li><a class="streamium-auth" href="' . wp_logout_url() . '">' . __( 'Log Out' ) . '</a></li>';
 	} else {
 		$items .= '<li><a class="streamium-auth" href="' . wp_login_url() . '">' . __( 'Log In' ) . '</a></li>';
 	}
 	return $items;

}

// Can be disabled in the site identity shown by default
if ( !get_theme_mod( 'streamium_disable_login' ) ) {
    add_filter( 'wp_nav_menu_items', 'streamium_s2member_auth_menu', 10, 2 );
}
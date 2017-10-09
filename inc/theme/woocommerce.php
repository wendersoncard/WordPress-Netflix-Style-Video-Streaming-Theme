<?php

/**
 * Add woo support
 *
 * @return bool
 * @author  @sameast
 */
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'woocommerce_support' );

// Remove some unwanted links
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

/**
 * Print the customer avatar in My Account page, after the welcome message
 */
function streamium_myaccount_customer_avatar() {

    $current_user = wp_get_current_user();
    $url = md5( strtolower( trim( $current_user->user_email ) ) );
    echo '<div class="myaccount_avatar"><a href="https://www.gravatar.com/avatar/' . $url . '?s=200" target="_blank">' . get_avatar( $current_user->user_email, 72, '', $current_user->display_name ) . '</a></div>';

}

add_action( 'woocommerce_before_my_account', 'streamium_myaccount_customer_avatar', 50 );

/*
* Add login logout link for Woo
* @author sameast
* @none
*/ 
function streamium_woo_auth_menu( $items, $args ) {
    if (is_user_logged_in() && $args->theme_location == 'streamium-header-menu') {
            $items .= '<li><a class="s2member-auth" href="'. wp_logout_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ) .'">Log Out</a></li>';
    }
    elseif (!is_user_logged_in() && $args->theme_location == 'streamium-header-menu') {
            $items .= '<li><a class="s2member-auth" href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '">Log In</a></li>';
    }
    return $items;
}

add_filter( 'wp_nav_menu_items', 'streamium_woo_auth_menu', 10, 2 );
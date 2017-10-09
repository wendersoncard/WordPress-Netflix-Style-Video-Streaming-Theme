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
* Allows filters to be removed on the memebership plugin
* @author sameast
* @none
*/ 
if ( ! function_exists( 'remove_anonymous_object_filter' ) )
{
    /**
     * Remove an anonymous object filter.
     *
     * @param  string $tag    Hook name.
     * @param  string $class  Class name
     * @param  string $method Method name
     * @return void
     */
    function remove_anonymous_object_filter( $tag, $class, $method )
    {
        $filters = $GLOBALS['wp_filter'][ $tag ];

        if ( empty ( $filters ) )
        {
            return;
        }

        foreach ( $filters as $priority => $filter )
        {
            foreach ( $filter as $identifier => $function )
            {
                if ( is_array( $function)
                    and is_a( $function['function'][0], $class )
                    and $method === $function['function'][1]
                )
                {
                    remove_filter(
                        $tag,
                        array ( $function['function'][0], $method ),
                        $priority
                    );
                }
            }
        }
    }
}


if (class_exists('WooCommerce_Membership_Post')) {

  add_action( 'init', 'kill_anonymous_example', 0 );

  /**
   * Remove memebership filter and then create a new one.
   *
   * @param  string $tag    Hook name.
   * @param  string $class  Class name
   * @param  string $method Method name
   * @return void
   */
  function kill_anonymous_example()
  {
      remove_anonymous_object_filter(
          'posts_where',
          'WooCommerce_Membership_Post',
          'expand_posts_where'
      );

      remove_anonymous_object_filter(
          'the_posts',
          'WooCommerce_Membership_Post',
          'filter_posts'
      );

      function streamium_plugin_fixes( $posts ) {

          // Iterate over posts
          foreach ($posts as $post_key => $post) {

              // Check if user has access to current post
              if (!WooCommerce_Membership_Post::user_has_access_to_post($post->ID)) {

                  // Filter out restricted post
                  $meta = get_post_meta( $post->ID );
                  $post->premium = false;
                  if (array_key_exists("_rpwcm_post_restriction_method",$meta)){
                    if(isset($meta['_rpwcm_post_restriction_method'][0])){
                      $post->premium = true;
                    }
                  }

                  $post->plans = null;
                  if (array_key_exists("_rpwcm_only_caps",$meta)){
                    $post->plans = $meta['_rpwcm_only_caps'];
                  }

              }

          }

          // Return filtered posts
          return $posts;

      }
      
      add_filter( 'the_posts', 'streamium_plugin_fixes' );

  }

}

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
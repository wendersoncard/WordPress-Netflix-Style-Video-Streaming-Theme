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
          // Maybe modify $example in some way.

          //$obj = new WooCommerce_Membership_Post();

          // Iterate over posts
          foreach ($posts as $post_key => $post) {

              // Check if user has access to current post
              if (!WooCommerce_Membership_Post::user_has_access_to_post($post->ID)) {

                  // Filter out restricted post
                  $meta = get_post_meta( $post->ID );
                  $post->premium = (!empty($meta['_rpwcm_post_restriction_method'][0] == "none") ? false : true );
                  $post->plans = $meta['_rpwcm_only_caps'];

              }

          }

          // Return filtered posts
          return $posts;

      }
      add_filter( 'the_posts', 'streamium_plugin_fixes' );

  }

}
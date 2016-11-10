<?php

/*-----------------------------------------------------------------------------------

	Here we have all the custom functions for the theme.
	Please be extremely cautious editing this file,
	When things go wrong, they tend to go wrong in a big way.
	You have been warned!

-------------------------------------------------------------------------------------*/

if ( ! isset( $content_width ) ) $content_width = 900;

// Remove paragraph tags from around the content
remove_filter('the_content', 'wpautop');

// woocommerce fixes
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );


/*-----------------------------------------------------------------------------------*/
/*	Theme set up
/*-----------------------------------------------------------------------------------*/

if (!function_exists('streamium_theme_setup')) {
    function streamium_theme_setup() {	
    	/* Configure WP 2.9+ Thumbnails ---------------------------------------------*/
    	add_theme_support('post-thumbnails');
        add_theme_support( 'automatic-feed-links' );
        add_image_size( 'streamium-video-poster', 600, 338, true ); // (cropped)
        add_image_size( 'streamium-video-category', 292, 164, true );
        add_image_size( 'streamium-home-slider', 1600, 900, true ); 
        add_image_size( 'streamium-site-logo', 0, 56, true ); 
        add_theme_support( 'title-tag' );
    } 
}
add_action('after_setup_theme', 'streamium_theme_setup');

/*-----------------------------------------------------------------------------------*/
/*	Register javascript and css
/*-----------------------------------------------------------------------------------*/

if (!function_exists('streamium_enqueue_scripts')) {
	function streamium_enqueue_scripts() {

        wp_enqueue_script( 'streamium-bootstrap', get_template_directory_uri() . '/dist/js/bootstrap.min.js', array( 'jquery') );
	      wp_enqueue_script( 'streamium-slick', get_template_directory_uri() . '/dist/plugins/slick/slick.min.js', array( 'jquery') );
        wp_enqueue_script( 'streamium-modernizr', get_template_directory_uri() . '/dist/js/modernizr.min.js', array( 'jquery') );
        wp_enqueue_script( 'streamium-jquery.mobile.custom', get_template_directory_uri() . '/dist/js/jquery.mobile.custom.min.js', array( 'jquery') );
        wp_enqueue_script( 'streamium-menu', get_template_directory_uri() . '/dist/js/menu.js', array( 'jquery') );
        wp_enqueue_script( 'streamium-modal', get_template_directory_uri() . '/dist/js/comments.js', array( 'jquery') );
        wp_enqueue_script( 'streamium-info', get_template_directory_uri() . '/dist/js/jquery.flexslider-min.js', array( 'jquery') );
        wp_enqueue_script( 'streamium-velocity', get_template_directory_uri() . '/dist/js/masonry.pkgd.min.js', array( 'jquery') );
        wp_enqueue_script( 'streamium-scripts', get_template_directory_uri() . '/dist/js/main.js', array( 'jquery') );
        wp_localize_script( 'streamium-scripts', 'streamium_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

        // player one hls
        //wp_enqueue_script( 'streamium-player1-video', 'http://vjs.zencdn.net/5.7.1/video.js', array( 'jquery') );
        //wp_enqueue_script( 'streamium-player1-video', get_template_directory_uri() . '/dist/plugins/player1/video.js', array( 'jquery') );
        //wp_enqueue_script( 'streamium-player1-video-hls', get_template_directory_uri() . '/dist/plugins/player1/videojs.hls.js', array( 'jquery') );
        //wp_enqueue_script( 'streamium-player1-main', get_template_directory_uri() . '/dist/plugins/player1/main.js', array( 'jquery') );

        if( is_singular() ) {
            wp_enqueue_script('comment-reply'); // loads the javascript required for threaded comments
        }

        /* Register styles -----------------------------------------------------*/
        wp_enqueue_style( 'streamium-styles', get_stylesheet_uri() );
        wp_enqueue_style('streamium-reset', get_template_directory_uri() . '/dist/css/bootstrap.min.css');
        wp_enqueue_style('streamium-menu', get_template_directory_uri() . '/dist/css/menu.css');
        wp_enqueue_style('streamium-modal', get_template_directory_uri() . '/dist/css/comments.css');
        wp_enqueue_style('streamium-info', get_template_directory_uri() . '/dist/css/info.css');
        wp_enqueue_style('streamium-slick', get_template_directory_uri() . '/dist/plugins/slick/slick.css');
        wp_enqueue_style('streamium-slick-theme', get_template_directory_uri() . '/dist/plugins/slick/slick-theme.css');
        wp_enqueue_style('streamium-main', get_template_directory_uri() . '/dist/css/main.css');       
        wp_enqueue_style( 'streamium-main_font', 'https://fonts.googleapis.com/css?family=Open+Sans:400,700,300,600,800', false );
        wp_enqueue_style('streamium-woocommerce', get_template_directory_uri() . '/dist/css/woocommerce.css');  
        wp_enqueue_style('streamium-fontawesome', get_template_directory_uri() . '/dist/css/font-awesome.css');  
        // player one hls
        //wp_enqueue_style('streamium-player1-video', get_template_directory_uri() . '/dist/plugins/player1/video-js.css');

	}
}

add_action('wp_enqueue_scripts', 'streamium_enqueue_scripts');

 
function so_27023433_disable_checkout_script(){
    wp_dequeue_script( 'wc-checkout' );
}
//add_action( 'wp_enqueue_scripts', 'so_27023433_disable_checkout_script' );

/*-----------------------------------------------------------------------------------*/
/*  New theme customizer options
/*-----------------------------------------------------------------------------------*/
class Streamium_Customize {
   /**
    * This hooks into 'customize_register' (available as of WP 3.4) and allows
    * you to add new sections and controls to the Theme Customize screen.
    * 
    * Note: To enable instant preview, we have to actually write a bit of custom
    * javascript. See live_preview() for more.
    *  
    * @see add_action('customize_register',$func)
    * @param \WP_Customize_Manager $wp_customize
    * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
    * @since Streamium 1.0
    */
   public static function register ( $wp_customize ) {

      $wp_customize->remove_control('display_header_text');

      $wp_customize->add_section( 'streamium_options', 
         array(
            'title' => __( 'Streamium Options', 'streamium' ), 
            'priority' => 35, 
            'capability' => 'edit_theme_options', 
            'description' => __('Allows you to customize some example settings for Streamium.', 'streamium'),
         ) 
      );

      $wp_customize->add_section( 'streamium_logo_section' , array(
          'title'       => __( 'Logo', 'streamium' ),
          'priority'    => 30,
          'description' => 'Upload a logo to replace the default site name and description in the header',
      ) );

      $wp_customize->add_setting( 'streamium_logo' );

      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'streamium_logo', array(
          'label'    => __( 'Logo', 'streamium' ),
          'section'  => 'streamium_logo_section',
          'settings' => 'streamium_logo',
      ) ) );

      $wp_customize->add_setting( 'link_textcolor', 
         array(
            'default' => '#2BA6CB', 
            'type' => 'theme_mod', 
            'capability' => 'edit_theme_options', 
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color'
         ) 
      );      
          
      $wp_customize->add_control( new WP_Customize_Color_Control( 
         $wp_customize, 
         'streamium_link_textcolor', 
         array(
            'label' => __( 'Main Theme Color', 'streamium' ), 
            'section' => 'colors', 
            'settings' => 'link_textcolor', 
            'priority' => 10, 
         ) 
      ) );

      $wp_customize->add_section('streamium_payment_section' , array(
          'title'     => __('Payment Settings', 'streamium'),
          'priority'  => 1020
      ));

      $wp_customize->add_setting('redirect_too_signup', array(
          'default'    => '0'
      ));

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'redirect_too_signup',
              array(
                  'label'     => __('Redirect to signup', 'streamium'),
                  'section'   => 'streamium_payment_section',
                  'settings'  => 'redirect_too_signup',
                  'type'      => 'checkbox',
              )
          )
      );
  
   }

   /**
    * This will output the custom WordPress settings to the live theme's WP head.
    * 
    * Used by hook: 'wp_head'
    * 
    * @see add_action('wp_head',$func)
    * @since Streamium 1.0
    */
   public static function header_output() {
      ?>
      <!--Customizer CSS--> 
      <style type="text/css">
           <?php self::generate_css('a', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('a:focus', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('a:hover', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.slick-dots li.slick-active button', 'background-color', 'link_textcolor'); ?>
           <?php self::generate_css('.progress-bar', 'background-color', 'link_textcolor'); ?>
           <?php self::generate_css('.button', 'background', 'link_textcolor'); ?>
           <?php self::generate_css('.label.heart', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.progress-bar .progress', 'background', 'link_textcolor'); ?>
           <?php self::generate_css('.cd-main-header .cd-logo', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.cd-primary-nav .cd-secondary-nav a:hover', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.cd-overlay', 'background-color', 'link_textcolor'); ?>
           <?php self::generate_css('.cd-primary-nav>li>a:hover', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.cd-primary-nav .cd-nav-gallery .cd-nav-item h3', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.cd-primary-nav .cd-nav-icons .cd-nav-item h3', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.has-children > a:hover::before, .has-children > a:hover::after, .go-back a:hover::before, .go-back a:hover::after', 'background', 'link_textcolor'); ?>
           <?php self::generate_css('#submit', 'background', 'link_textcolor'); ?>
      </style> 
      <!--/Customizer CSS-->
      <?php
   }
   
   /**
    * This outputs the javascript needed to automate the live settings preview.
    * Also keep in mind that this function isn't necessary unless your settings 
    * are using 'transport'=>'postMessage' instead of the default 'transport'
    * => 'refresh'
    * 
    * Used by hook: 'customize_preview_init'
    * 
    * @see add_action('customize_preview_init',$func)
    * @since Streamium 1.0
    */
   public static function live_preview() {
      wp_enqueue_script( 
           'streamium-themecustomizer', // Give the script a unique ID
           get_template_directory_uri() . '/dist/js/theme-customizer.js', // Define the path to the JS file
           array(  'jquery', 'customize-preview' ), // Define dependencies
           '', // Define a version (optional) 
           true // Specify whether to put in footer (leave this true)
      );
   }

    /**
     * This will generate a line of CSS for use in header output. If the setting
     * ($mod_name) has no defined value, the CSS will not be output.
     * 
     * @uses get_theme_mod()
     * @param string $selector CSS selector
     * @param string $style The name of the CSS *property* to modify
     * @param string $mod_name The name of the 'theme_mod' option to fetch
     * @param string $prefix Optional. Anything that needs to be output before the CSS property
     * @param string $postfix Optional. Anything that needs to be output after the CSS property
     * @param bool $echo Optional. Whether to print directly to the page (default: true).
     * @return string Returns a single line of CSS with selectors and a property.
     * @since Streamium 1.0
     */
    public static function generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }
}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'Streamium_Customize' , 'register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'Streamium_Customize' , 'header_output' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init' , array( 'Streamium_Customize' , 'live_preview' ) );

/*
* Adds a main nav menu if needed
* @author sameast
* @none
*/ 
function streamium_register_menu() {
    register_nav_menu('streamium-header-menu',__( 'Header Menu', 'streamium' ));
}
add_action( 'init', 'streamium_register_menu' );

function streamium_remove_ul( $menu ){
    return preg_replace( array( '#^<ul[^>]*>#', '#</ul>$#' ), '', $menu );
}
add_filter( 'wp_nav_menu', 'streamium_remove_ul' );


/**
 * Adds a new user bucket
 *
 * @return bool
 * @author  @sameast
 */
function streamium_user_reviews_callback(){

    global $withcomments;
    $post_id = $_POST['pid'];
    $comments = get_comments(array( 'post_id' =>  $post_id ));
    if ( $comments )
    {
        $comms = "<ul>";
        foreach ( $comments as $comment )
        {
             $comms .= '<li class="cd-testimonials-item">
                <p>' . $comment->comment_content . '</p>
                <div class="cd-author">
                  ' . get_avatar( $comment, 32 ) . '
                  <ul class="cd-author-info">
                    <li>' . $comment->comment_author . '</li>
                    <li>' . get_post_field('post_title', $post_id) . '</li>
                  </ul>
                </div>
              </li>';
        }
        $comms .= "</ul>";
    }

    echo $comms;
  
    wp_die(); // this is required to return a proper result
}

add_action('wp_ajax_streamium_user_reviews', 'streamium_user_reviews_callback');
add_action( 'wp_ajax_nopriv_streamium_user_reviews', 'streamium_user_reviews_callback' );


add_filter('show_admin_bar', '__return_false');



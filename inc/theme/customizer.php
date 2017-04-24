<?php

/**
 * Add option to the WordPress theme customizer
 *
 * @return null
 * @author  @sameast
 */
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

      // Remove options
      $wp_customize->remove_section('colors');

      // STREAMIUM STYLES SECTION
      $wp_customize->add_section( 'streamium_styles', array(
          'title'       => __( 'Streamium Styles', 'streamium' ),
          'priority'    => 30,
          'description' => 'Here you can set the Streamium styles',
      ) );

      // Remove tutorial block
      $wp_customize->add_setting('tutorial_btn', array(
          'default' => false
      ));
      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'tutorial_btn',
              array(
                  'label'     => __('Remove tutorial header', 'streamium'),
                  'section'   => 'streamium_styles',
                  'settings'  => 'tutorial_btn',
                  'type'      => 'checkbox',
              )
          )
      );

      // Logo block
      $wp_customize->add_setting( 'streamium_logo' );

      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'streamium_logo', array(
          'label'    => __( 'Logo', 'streamium' ),
          'section'  => 'streamium_styles',
          'settings' => 'streamium_logo',
      ) ) );
      
      // Full background block
      $wp_customize->add_setting( 'streamium_plans_bg' );

      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'streamium_plans_bg', array(
          'label'    => __( 'Background', 'streamium' ),
          'section'  => 'streamium_styles',
          'settings' => 'streamium_plans_bg',
      ) ) );

      // Link text color
      $wp_customize->add_setting(
          'link_textcolor',
          array(
              'default'     => '#999999',
              'sanitize_callback' => 'sanitize_hex_color'
          )
      );
   
      $wp_customize->add_control(
          new WP_Customize_Color_Control(
              $wp_customize,
              'link_textcolor',
              array(
                  'label'      => __( 'Main Link Color', 'streamium' ),
                  'section'    => 'streamium_styles',
                  'settings'   => 'link_textcolor'
              ) 
          )
      );

      // Caro heading text color
      $wp_customize->add_setting(
          'streamium_carousel_heading_color',
          array(
              'default'     => '#999999',
              'sanitize_callback' => 'sanitize_hex_color'
          )
      );
   
      $wp_customize->add_control(
          new WP_Customize_Color_Control(
              $wp_customize,
              'streamium_carousel_heading_color',
              array(
                  'label'      => __( 'Carousel Headings Color', 'streamium' ),
                  'section'    => 'streamium_styles',
                  'settings'   => 'streamium_carousel_heading_color'
              ) 
          )
      );
      
      // Main background color
      $wp_customize->add_setting(
          'streamium_background_color',
          array(
              'default'     => '#141414',
              'sanitize_callback' => 'sanitize_hex_color'
          )
      );
   
      $wp_customize->add_control(
          new WP_Customize_Color_Control(
              $wp_customize,
              'streamium_background_color',
              array(
                  'label'      => __( 'Background Color', 'streamium' ),
                  'section'    => 'streamium_styles',
                  'settings'   => 'streamium_background_color'
              ) 
          )
      );

      // Use google font
      $wp_customize->add_setting('streamium_google_font');
      
      $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'streamium_google_font',
        array(
          'label' => 'Google Font Url',
          'description' => 'Please only enter the full google font url<br>Leave blank to use "Helvetica Neue"',
          'section' => 'streamium_styles',
          'settings' => 'streamium_google_font',
        )) 
      );          
      

      // SITE IDENTITY SECTION
      $wp_customize->add_setting('streamium_remove_powered_by_s3bubble');
      
      $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'streamium_remove_powered_by_s3bubble',
        array(
          'label' => 'Replace Powered By S3Bubble Text',
          'section' => 'title_tagline',
          'settings' => 'streamium_remove_powered_by_s3bubble',
        )) 
      );

      $wp_customize->add_setting('streamium_genre_text');

      $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'streamium_genre_text',
        array(
          'label' => 'Replace Genre Text',
          'section' => 'title_tagline',
          'settings' => 'streamium_genre_text',
        )) 
      );

      $wp_customize->remove_control('display_header_text');

      $wp_customize->add_setting('streamium_global_options_homepage_desktop', array(
          'default'  => '-1'
      ));

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'streamium_global_options_homepage_desktop',
              array(
                  'label'     => __('Maximum carousel videos - Desktop', 'streamium'),
                  'section'   => 'title_tagline',
                  'settings'  => 'streamium_global_options_homepage_desktop',
                  'type'      => 'select',
                  'choices' => array(
                      '-1'  => __( '-1' ),
                      '6'   => __( '6' ),
                      '12'  => __( '12' ),
                      '18'  => __( '18' ),
                      '24'  => __( '24' ),
                      '30'  => __( '30' )
                  )
              )
          )
      );

      // PREMIUM SECTION
      /*
      $wp_customize->add_section('streamium_premium_section' , array(
          'title'     => __('Streamium Premium Options', 'streamium'),
          'priority'  => 1020
      ));

      $wp_customize->add_setting('streamium_enable_premium', array(
          'default'    => false
      ));

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'streamium_enable_premium',
              array(
                  'label'     => __('Enable Premium', 'streamium'),
                  'section'   => 'streamium_premium_section',
                  'settings'  => 'streamium_enable_premium',
                  'type'      => 'checkbox',
              )
          )
      );*/

      // AWS MEDIA SECTION
      $wp_customize->add_section('streamium_aws_media_uploader_section' , array(
          'title'     => __('AWS Media Uploader', 'streamium'),
          'priority'  => 1020
      ));

      $wp_customize->add_setting('streamium_aws_media_uploader_access_key');
      
      $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'streamium_aws_media_uploader_access_key',
        array(
          'label' => 'AWS Access Key',
          'section' => 'streamium_aws_media_uploader_section',
          'settings' => 'streamium_aws_media_uploader_access_key',
          'type' => 'password',
          'input_attrs' => array( 'id' => 'streamium_aws_media_uploader_access_key' )
        )) 
      );

      $wp_customize->add_setting('streamium_aws_media_uploader_secret_key');

      $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'streamium_aws_media_uploader_secret_key',
        array(
          'label' => 'AWS Secret Key',
          'section' => 'streamium_aws_media_uploader_section',
          'settings' => 'streamium_aws_media_uploader_secret_key',
          'type' => 'password',
          'input_attrs' => array( 'id' => 'streamium_aws_media_uploader_secret_key' )
        )) 
      );

      $wp_customize->add_setting('streamium_aws_media_uploader_notification_email');

      $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'streamium_aws_media_uploader_notification_email',
        array(
          'label' => 'Notification Email',
          'section' => 'streamium_aws_media_uploader_section',
          'settings' => 'streamium_aws_media_uploader_notification_email',
        )) 
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

           /* Theme colors */
           <?php self::generate_css('.archive, .home, .search, .single', 'background', 'streamium_background_color','',' !important'); ?>
           <?php self::generate_css('.video-header h3', 'color', 'streamium_carousel_heading_color','',' !important'); ?>

           /* link and background colors */
           <?php self::generate_css('a, a:focus, a:hover, .cd-main-header .cd-logo, .play-icon-wrap i, .cd-primary-nav .cd-secondary-nav a:hover, .cd-primary-nav>li>a:hover, .cd-primary-nav .cd-nav-gallery .cd-nav-item h3, .cd-primary-nav .cd-nav-icons .cd-nav-item h3, .woocommerce-message:before, .woocommerce-info::before', 'color', 'link_textcolor','',' !important'); ?>
           <?php self::generate_css('#place_order, .pagination a:hover, .pagination .current, .slick-dots li.slick-active button, .progress-bar, .button, .cd-overlay, .has-children > a:hover::before, .has-children > a:hover::after, .go-back a:hover::before, .go-back a:hover::after, #submit, #place_order, .checkout-button, .woocommerce-thankyou-order-received, .add_to_cart_button, .confirm', 'background-color', 'link_textcolor','',' !important'); ?>

           <?php self::generate_css('.post-type-archive, .woocommerce-cart, .woocommerce-account, .woocommerce-checkout, .woocommerce-page', 'background-image', 'streamium_plans_bg', 'url(', ')'); ?>

           <?php self::generate_css('.tile', 'border-color', 'link_textcolor','',' !important'); ?>
           <?php self::generate_css('.woocommerce-message, .woocommerce-info', 'border-top-color', 'link_textcolor','',' !important'); ?>
           
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


function streamium_google_font() {

  // Setup params
  $url = get_theme_mod( 'streamium_google_font' );
  $parts = parse_url($url);
  parse_str($parts['query'], $query);
  $family = $query['family'];
  if (strpos($family, ':') !== false) {
    $family = substr($family, 0, strrpos($family, ':'));
  }
  if(get_theme_mod( 'streamium_google_font' )){
    ?>
    <style type="text/css">
      @import url('<?php echo $url; ?>');
      html, body {
        font-family: '<?php echo $family; ?>', sans-serif !important;
      }
    </style>
    <?php
  }

}

add_action ( 'wp_head' , 'streamium_google_font');

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'Streamium_Customize' , 'register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'Streamium_Customize' , 'header_output' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init' , array( 'Streamium_Customize' , 'live_preview' ) );
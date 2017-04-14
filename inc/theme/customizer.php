<?php
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

      // allow the user to remove the powered by link
      $wp_customize->add_setting('streamium_remove_powered_by_s3bubble');
      
      $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'streamium_remove_powered_by_s3bubble',
        array(
          'label' => 'Replace Powered By S3Bubble Text',
          'section' => 'title_tagline',
          'settings' => 'streamium_remove_powered_by_s3bubble',
        )) 
      );

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
          'title'       => __( 'Streamium Styles', 'streamium' ),
          'priority'    => 30,
          'description' => 'Upload a logo to replace the default site name and description in the header',
      ) );

       $wp_customize->add_setting('tutorial_btn', array(
          'default' => false
      ));
      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'tutorial_btn',
              array(
                  'label'     => __('Remove tutorial header', 'streamium'),
                  'section'   => 'streamium_logo_section',
                  'settings'  => 'tutorial_btn',
                  'type'      => 'checkbox',
              )
          )
      );

      $wp_customize->add_setting( 'streamium_logo' );

      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'streamium_logo', array(
          'label'    => __( 'Logo', 'streamium' ),
          'section'  => 'streamium_logo_section',
          'settings' => 'streamium_logo',
      ) ) );
      
      // plans background image
      $wp_customize->add_setting( 'streamium_plans_bg' );

      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'streamium_plans_bg', array(
          'label'    => __( 'Background', 'streamium' ),
          'section'  => 'streamium_logo_section',
          'settings' => 'streamium_plans_bg',
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

      // Add site options
      $wp_customize->add_section('streamium_global_options' , array(
          'title'     => __('Global Options', 'streamium'),
          'priority'  => 1
      ));

      $wp_customize->add_setting('streamium_global_options_homepage_desktop', array(
          'default'  => '-1'
      ));

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'streamium_global_options_homepage_desktop',
              array(
                  'label'     => __('Maximum carousel videos - Desktop', 'streamium'),
                  'section'   => 'streamium_global_options',
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
      );

      /* CREATE A NEW SECTION */
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
           <?php self::generate_css('a', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('a:focus', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('a:hover', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('#place_order', 'background-color', 'link_textcolor'); ?>
           <?php self::generate_css('.pagination a:hover', 'background-color', 'link_textcolor'); ?>
           <?php self::generate_css('.pagination .current', 'background-color', 'link_textcolor'); ?>
           <?php self::generate_css('.slick-dots li.slick-active button', 'background-color', 'link_textcolor'); ?>
           <?php self::generate_css('.progress-bar', 'background-color', 'link_textcolor'); ?>
           <?php self::generate_css('.button', 'background', 'link_textcolor'); ?>
           <?php self::generate_css('.label.heart', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.progress-bar .progress', 'background', 'link_textcolor'); ?> 
           <?php self::generate_css('.cd-main-header .cd-logo', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.play-icon-wrap i', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.cd-primary-nav .cd-secondary-nav a:hover', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.cd-overlay', 'background-color', 'link_textcolor'); ?>
           <?php self::generate_css('.cd-primary-nav>li>a:hover', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.cd-primary-nav .cd-nav-gallery .cd-nav-item h3', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.cd-primary-nav .cd-nav-icons .cd-nav-item h3', 'color', 'link_textcolor'); ?>
           <?php self::generate_css('.has-children > a:hover::before, .has-children > a:hover::after, .go-back a:hover::before, .go-back a:hover::after', 'background', 'link_textcolor'); ?>
           <?php self::generate_css('#submit, #place_order', 'background', 'link_textcolor'); ?>
           <?php self::generate_css('.post-type-archive, .woocommerce-cart, .woocommerce-account, .woocommerce-checkout, .woocommerce-page', 'background-image', 'streamium_plans_bg', 'url(', ')'); ?>
           <?php self::generate_css('.checkout-button, .woocommerce-thankyou-order-received, .add_to_cart_button', 'background', 'link_textcolor','',' !important'); ?>
           <?php self::generate_css('.tile', 'border-color', 'link_textcolor','',' !important'); ?>
           <?php self::generate_css('.woocommerce-message, .woocommerce-info', 'border-top-color', 'link_textcolor','',' !important'); ?>
           <?php self::generate_css('.woocommerce-message:before, .woocommerce-info::before', 'color', 'link_textcolor','',' !important'); ?>
           <?php self::generate_css('.confirm', 'background-color', 'link_textcolor','',' !important'); ?>

           
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
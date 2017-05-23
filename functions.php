<?php

/*-----------------------------------------------------------------------------------*/
/*	Theme set up
/*-----------------------------------------------------------------------------------*/
if (!function_exists('streamium_theme_setup')) {
    
    function streamium_theme_setup() {	

        // Create aspects based on average
        $width = 1920/6;
        $height = $width/16*9;
    	add_theme_support('post-thumbnails');
        add_theme_support( 'automatic-feed-links' );
        add_image_size( 'streamium-video-tile', $width, $height, true ); //, 285, 160
        add_image_size( 'streamium-video-tile-expanded', ($width*2), ($height*2), true ); //, 285, 160
        add_image_size( 'streamium-video-multi-thumb', 320, 180, true ); //, 285, 160
        add_image_size( 'streamium-home-slider', 1600, 900 ); 
        add_image_size( 'streamium-site-logo', 0, 56, true ); 
        add_theme_support( 'title-tag' );
    }

    //* Add new image sizes to post or page editor
    function streamium_extra_image_sizes( $sizes ) {

        $mythemesizes = array(
            'streamium-video-tile'    => __( 'Video Tile' ), 
            'streamium-video-tile-expanded'   => __( 'Video Tile Expanded' ),
            'streamium-video-multi-thumb'   => __( 'Multi Video Thumb' ),
            'streamium-home-slider'   => __( 'Main Slider' ),  
        );
        $sizes = array_merge( $sizes, $mythemesizes );

        return $sizes;
    }

    add_filter( 'image_size_names_choose', 'streamium_extra_image_sizes' ); 

}

add_action('after_setup_theme', 'streamium_theme_setup');

/*-----------------------------------------------------------------------------------*/
/*	Register javascript and css
/*-----------------------------------------------------------------------------------*/
if (!function_exists('streamium_enqueue_scripts')) {
	
    function streamium_enqueue_scripts() {
        
        /* Register styles -----------------------------------------------------*/
        wp_enqueue_style( 'streamium-styles', get_stylesheet_uri() );
        wp_enqueue_style('streamium-production', get_template_directory_uri() . '/production/css/streamium.min.css');  
 
        /* Register scripts -----------------------------------------------------*/
        wp_enqueue_script( 'plupload' );
        wp_enqueue_script( 'streamium-production', get_template_directory_uri() . '/production/js/streamium.min.js', array( 'jquery' ),'1.1', true);
        wp_localize_script( 'streamium-production', 'streamium_object', 
            array( 
                'ajax_url' => admin_url( 'admin-ajax.php')
            )
        );

        //wp_enqueue_style('streamium-s3bubble-cdn', 'http://local.hosted.com/assets/hosted/s3bubble-hosted-cdn.min.css');
        //wp_enqueue_script( 'streamium-s3bubble-cdn', 'http://local.hosted.com/assets/hosted/s3bubble-hosted-cdn.min.js', array( 'jquery'),'1.1', true );

        // not valid
        wp_enqueue_style('streamium-s3bubble-cdn', 'https://s3.amazonaws.com/s3bubble-cdn/dev/s3bubble-hosted-cdn.min.css');
        wp_enqueue_script( 'streamium-s3bubble-cdn', 'https://s3.amazonaws.com/s3bubble-cdn/dev/s3bubble-hosted-cdn.min.js','','1.1', true );

	}

    add_action('wp_enqueue_scripts', 'streamium_enqueue_scripts');
    
}

if (!function_exists('streamium_enqueue_admin_scripts')) {

    /**
     * Include the scripts for the meta boxes
     *
     * @return null
     * @author  @sameast 
     */
    function streamium_enqueue_admin_scripts(){
      
      $streamium_connected_website = get_option("streamium_connected_website");
      $streamium_connected_nonce = wp_create_nonce( 'streamium_connected_nonce' );
      wp_enqueue_style( 'streamium-admin', get_template_directory_uri() . '/production/css/admin.min.css', array() );
      wp_enqueue_script( 'streamium-admin', get_template_directory_uri() . '/production/js/admin.min.js', array( 'jquery'),'1.1', true );
      wp_localize_script('streamium-admin', 'streamium_meta_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php'),
        'api' => 'https://s3bubbleapi.com', // https://s3bubbleapi.com http://local.hosted.com leave of the trailing slash 
        'streamiumPremium' => get_theme_mod( 'streamium_enable_premium' ),
        'connected_website' => (!empty($streamium_connected_website) ? $streamium_connected_website : ""),
        'connected_nonce' => $streamium_connected_nonce
      ));

    }

    add_action( 'admin_enqueue_scripts', 'streamium_enqueue_admin_scripts' );

}

/*-----------------------------------------------------------------------------------*/
/*  Include the Streamium Framework
/*-----------------------------------------------------------------------------------*/
$tempdir = get_template_directory();
require_once($tempdir .'/inc/init.php');
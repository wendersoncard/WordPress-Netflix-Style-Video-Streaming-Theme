<?php

/*-----------------------------------------------------------------------------------*/
/*	Theme set up
/*-----------------------------------------------------------------------------------*/
if (!function_exists('streamium_theme_setup')) {
    
    function streamium_theme_setup() {	
    	add_theme_support('post-thumbnails');
        add_theme_support( 'automatic-feed-links' );
        add_image_size( 'streamium-video-poster', 600, 338, true ); // (cropped)
        add_image_size( 'streamium-video-category', 285, 160 );
        add_image_size( 'streamium-home-slider', 1600, 900 ); 
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
        
        /* Register styles -----------------------------------------------------*/
        wp_enqueue_style( 'streamium-styles', get_stylesheet_uri() );
        wp_enqueue_style('streamium-reset', get_template_directory_uri() . '/dist/css/bootstrap.min.css');  
        wp_enqueue_style('streamium-menu', get_template_directory_uri() . '/dist/css/menu.min.css');
        wp_enqueue_style('streamium-info', get_template_directory_uri() . '/dist/css/info.min.css');
        wp_enqueue_style('streamium-slick', get_template_directory_uri() . '/dist/extras/slick/slick.min.css');
        wp_enqueue_style('streamium-slick-theme', get_template_directory_uri() . '/dist/extras/slick/slick-theme.min.css');
        wp_enqueue_style('streamium-sweetalert', get_template_directory_uri() . '/dist/extras/sweetalert/sweetalert.min.css');
        wp_enqueue_style('streamium-main', get_template_directory_uri() . '/dist/css/main.min.css');       
        wp_enqueue_style('streamium-woocommerce', get_template_directory_uri() . '/dist/css/woocommerce.min.css');
        wp_enqueue_style('streamium-s2member', get_template_directory_uri() . '/dist/css/s2member.min.css');
 
        /* Register scripts -----------------------------------------------------*/
        wp_enqueue_script( 'streamium-fontawesome', get_template_directory_uri() . '/dist/js/fontawesome.min.js', array( 'jquery' ),'1.1', true);
        wp_enqueue_script( 'streamium-bootstrap', get_template_directory_uri() . '/dist/js/bootstrap.min.js', array( 'jquery' ),'1.1', true );
	      wp_enqueue_script( 'streamium-slick', get_template_directory_uri() . '/dist/extras/slick/slick.min.js', array( 'jquery' ),'1.1', true );
        wp_enqueue_script( 'streamium-modernizr', get_template_directory_uri() . '/dist/js/modernizr.min.js', array( 'jquery' ),'1.1', true );
        wp_enqueue_script( 'jquery-mobile-custom', get_template_directory_uri() . '/dist/js/jquery.mobile.custom.min.js', array( 'jquery' ),'1.1', true );
        wp_enqueue_script( 'streamium-menu', get_template_directory_uri() . '/dist/js/menu.min.js', array( 'jquery' ),'1.1', true );
        wp_enqueue_script( 'streamium-sweetalert', get_template_directory_uri() . '/dist/extras/sweetalert/sweetalert.min.js', array( 'jquery' ),'1.1', true );

        wp_enqueue_script( 'streamium-scripts', get_template_directory_uri() . '/dist/js/main.min.js', array( 'jquery' ),'1.1', true );
        wp_localize_script( 'streamium-scripts', 'streamium_object', 
            array( 
                'ajax_url' => admin_url( 'admin-ajax.php')
            )
        );

        if( is_singular() ) {
            wp_enqueue_script('comment-reply'); // loads the javascript required for threaded comments
        } 

        // not valid
        wp_enqueue_style('streamium-s3bubble-cdn', 'https://s3.amazonaws.com/s3bubble-cdn/latest/s3bubble-hosted-cdn.min.css');
        wp_enqueue_script( 'streamium-s3bubble-cdn', 'https://s3.amazonaws.com/s3bubble-cdn/latest/s3bubble-hosted-cdn.min.js','','1.1', true );

	}
    
}

add_action('wp_enqueue_scripts', 'streamium_enqueue_scripts');

/*-----------------------------------------------------------------------------------*/
/*  Include the Streamium Framework
/*-----------------------------------------------------------------------------------*/
$tempdir = get_template_directory();
require_once($tempdir .'/inc/init.php');
<?php

/*-----------------------------------------------------------------------------------*/
/*	Theme set up
/*-----------------------------------------------------------------------------------*/
if (!function_exists('streamium_theme_setup')) {
    function streamium_theme_setup()
    {
 
        // Add translation support
        load_theme_textdomain( 'streamium', get_template_directory() . '/languages' );

        // Create aspects based on average
        $averageBrowserWidth = 1366;
        $width = round($averageBrowserWidth/s3bubble_tile_count()); 
        $height = round($width/16*9);
 
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');

        // Check for orientation and switch if needed
        if(get_theme_mod( 'streamium_poster_orientation', '56.25' ) === '56.25'){
            // Landscape
            add_image_size('streamium-video-tile', ($width*2), ($height*2), false); 
            add_image_size('streamium-video-tile-expanded', ($width*2), ($height*2), false);
        }else{
            // Portrait
            add_image_size('streamium-video-tile', ($height*2), ($width*2), false); 
            add_image_size('streamium-video-tile-expanded', ($height*2), ($width*2), false); 
        }
        
        add_image_size('streamium-video-tile-large-expanded', 9999, 411, false); 
        add_image_size('streamium-home-slider', 9999, 540, false);
        add_image_size('streamium-site-logo', 0, 56, true);
        add_theme_support('title-tag');

        add_option('notice_premium', 1);
        add_option('notice_demo_data', 1);

        add_filter('image_size_names_choose', 'streamium_extra_image_sizes');

        if ( function_exists('register_sidebar') ) { 
            register_sidebar(array( 
                'name' => __('Page Sidebar', 'streamium'), 
                'id' => 'page-sidebar', 
                'description' => 'Appears as the sidebar on a page',
                'before_widget' => '<div id="%1$s" class="widget %2$s">', 
                'after_widget' => '</div>', 
                'before_title' => '<h4 class="widgettitle">', 
                'after_title' => '</h4>'
            ));
            register_sidebar(array( 
                'name' => __('Post Sidebar', 'streamium'), 
                'id' => 'post-sidebar', 
                'description' => 'Appears as the sidebar on a post',
                'before_widget' => '<div id="%1$s" class="widget %2$s">', 
                'after_widget' => '</div>', 
                'before_title' => '<h4 class="widgettitle">', 
                'after_title' => '</h4>'
            ));
            register_sidebar(array( 
                'name' => __('Forum Sidebar', 'streamium'), 
                'id' => 'forum-sidebar', 
                'description' => 'Appears as the sidebar on a forum',
                'before_widget' => '<div id="%1$s" class="widget %2$s">', 
                'after_widget' => '</div>', 
                'before_title' => '<h4 class="widgettitle">', 
                'after_title' => '</h4>'
            ));  
        }

    }

}

add_action('after_setup_theme', 'streamium_theme_setup');

//* Add new image sizes to post or page editor
function streamium_extra_image_sizes($sizes)
{
    $streamiumThemeSizes = array(
        'streamium-video-tile'    => __('Video Tile', 'streamium'),
        'streamium-video-tile-expanded'   => __('Video Tile Expanded', 'streamium'),
        'streamium-video-tile-large-expanded'   => __('Video Tile Large Expanded', 'streamium'),
        'streamium-video-multi-thumb'   => __('Multi Video Thumb', 'streamium'),
        'streamium-home-slider'   => __('Main Slider', 'streamium'),
    );
    $sizes = array_merge($sizes, $streamiumThemeSizes);

    return $sizes;
}

/*-----------------------------------------------------------------------------------*/
/*  Needed when updating
/*-----------------------------------------------------------------------------------*/
function s3bubble_cache_version() {
    return 15;
}

function s3bubble_tile_count() {
    return (int) get_theme_mod( 'streamium_tile_count', 6 );
}

/*-----------------------------------------------------------------------------------*/
/*	Register javascript and css
/*-----------------------------------------------------------------------------------*/
if (!function_exists('streamium_enqueue_scripts')) {
    function streamium_enqueue_scripts()
    {     

        global $wp_query;
        $query = $wp_query->get_queried_object();

        /* Register styles -----------------------------------------------------*/
        wp_enqueue_style('streamium-styles', get_stylesheet_uri());
        wp_enqueue_style('streamium-production', get_template_directory_uri() . '/production/css/streamium.min.css', array(), s3bubble_cache_version());

        /* Register scripts -----------------------------------------------------*/
        wp_enqueue_script('plupload');
        wp_enqueue_script('streamium-production', get_template_directory_uri() . '/production/js/streamium.min.js', array( 'jquery' ), s3bubble_cache_version(), true);

        wp_localize_script('streamium-production', 'streamium_object',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'recently_watched_api_nonce' => wp_create_nonce("recently_watched_api_nonce"),
                'custom_api_nonce' => wp_create_nonce("custom_api_nonce"),
                'home_api_nonce' => wp_create_nonce("home_api_nonce"),
                'tax_api_nonce' => wp_create_nonce("tax_api_nonce"),
                'search_api_nonce' => wp_create_nonce("search_api_nonce"),
                'extra_api_nonce' => wp_create_nonce("extra_api_nonce"),
                'query' => $query,
                'search' => isset($_GET) ? $_GET : false,
                'is_home' => is_home(),
                'is_archive' => is_archive(),
                'is_tax' => is_tax(),
                'is_search' => is_search(),
                'tile_count' => s3bubble_tile_count(),
                'read_more' => __('read more', 'streamium')
            )
        );

        // Local
        //wp_enqueue_style('streamium-s3bubble-cdn', 'http://local.hosted.com/assets/hosted/s3bubble.min.css');
        //wp_enqueue_script( 'streamium-s3bubble-cdn', 'http://local.hosted.com/assets/hosted/s3bubble.min.js', array( 'jquery'),s3bubble_cache_version(), true );

        // Live
        wp_enqueue_style('streamium-s3bubble-cdn', '//s3.amazonaws.com/aws-hosted/s3bubble.min.css');
        wp_enqueue_script('streamium-s3bubble-cdn', '//s3.amazonaws.com/aws-hosted/s3bubble.min.js', '', '1.2', true);
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
    function streamium_enqueue_admin_scripts()
    {
        $streamium_connected_website = get_option("streamium_connected_website");
        $streamium_connected_nonce = wp_create_nonce('streamium_connected_nonce');
        wp_enqueue_style('streamium-admin', get_template_directory_uri() . '/production/css/admin.min.css', array());
        wp_enqueue_script('streamium-admin', get_template_directory_uri() . '/production/js/admin.min.js', array( 'jquery'), '1.2', true);
        wp_localize_script('streamium-admin', 'streamium_meta_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'api' => 'https://s3bubbleapi.com', // https://s3bubbleapi.com http://local.hosted.com leave of the trailing slash
        'connected_website' => (!empty($streamium_connected_website) ? $streamium_connected_website : ""),
        'connected_nonce' => $streamium_connected_nonce
      ));
    }

    add_action('admin_enqueue_scripts', 'streamium_enqueue_admin_scripts');
}

// Dismiss premium notice with ajax
function dismiss_premium_notice()
{
    update_option('notice_premium', 0);
    echo json_encode(array('success' => true, 'message' => __('Notice dismissed', 'streamium' )));
    die();
}

// Enable the user with no privileges to run dismiss_premium_notice() in AJAX
add_action('wp_ajax_ajaxnopremium', 'dismiss_premium_notice');
add_action('wp_ajax_nopriv_ajaxnopremium', 'dismiss_premium_notice');

// Dismiss demo data notice with ajax
function dismiss_demo_data_notice()
{
    update_option('notice_demo_data', 0);
    echo json_encode(array('success' => true, 'message' => __('Notice dismissed','streamium')));
    die();
}

// Enable the user with no privileges to run dismiss_demo_data_notice() in AJAX
add_action('wp_ajax_ajaxnodemo', 'dismiss_demo_data_notice');
add_action('wp_ajax_nopriv_ajaxnodemo', 'dismiss_demo_data_notice');

/*-----------------------------------------------------------------------------------*/
/*  Include the Streamium Framework
/*-----------------------------------------------------------------------------------*/
$tempdir = get_template_directory();
require_once($tempdir .'/inc/init.php');
include_once( 'inc/import/init.php' );

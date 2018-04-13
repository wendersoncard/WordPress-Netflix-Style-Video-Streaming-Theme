<?php

/**
 * Remove admin bar
 *
 * @return null
 * @author  @sameast
 */
add_filter('show_admin_bar', '__return_false');

/**
 * Clears the cache
 *
 * @return null
 * @author  @sameast
 */
if ( ! function_exists ( 's3bubble_cache_version' ) ) {
    function s3bubble_cache_version() {
        return 23;
    }
}

/**
 * Setup the theme
 *
 * @return null
 * @author  @sameast
 */
if (!function_exists('streamium_theme_setup')) {
    function streamium_theme_setup() {
 
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
        
        add_image_size('streamium-video-tile-large-expanded', 9999, 585, false); 
        add_image_size('streamium-home-slider', 9999, 585, false); 
        add_image_size('streamium-site-logo', 0, 60, true);
        
        // Roku images
        add_image_size('streamium-roku-thumbnail', 816, 459, true); 

        add_theme_support('title-tag');

        add_option('notice_premium', 1);
        add_option('notice_demo_data', 1);

        if ( function_exists('register_sidebar') ) { 
            register_sidebar(array( 
                'name' => __('Page Sidebar', 'streamium'), 
                'id' => 'page-sidebar', 
                'class' => 'list-group',
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
    add_action('after_setup_theme', 'streamium_theme_setup');
}

/**
 * Add new image sizes to post or page editor
 *
 * @return null
 * @author  @sameast
 */
if ( ! function_exists ( 'streamium_extra_image_sizes' ) ) {
    function streamium_extra_image_sizes($sizes){
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
    add_filter('image_size_names_choose', 'streamium_extra_image_sizes');
}

/**
 * Include the main js and css files
 *
 * @return null
 * @author  @sameast
 */
if (!function_exists('streamium_enqueue_scripts')) {
    function streamium_enqueue_scripts() {     

        global $wp_query;
        $query = $wp_query->get_queried_object();

        /* Register styles -----------------------------------------------------*/
        wp_enqueue_style('streamium-styles', get_stylesheet_uri());
        wp_enqueue_style('streamium-production', get_template_directory_uri() . '/production/css/streamium.min.css', array(), s3bubble_cache_version());
        
        // Remove custom payment overlay 
        if(get_theme_mod( 'streamium_remove_payment_overlay', false )){
            $custom_css = ".tile_payment_details{display: none !important;}";
            wp_add_inline_style( 'streamium-styles', $custom_css );
        }

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
                'tag_api_nonce' => wp_create_nonce("tag_api_nonce"),
                'query' => $query,
                'search' => isset($_GET) ? $_GET : false,
                'is_home' => is_home(),
                'is_archive' => is_archive(),
                'is_tax' => is_tax(),
                'is_tag' => is_tag(),
                'is_search' => is_search(),
                'tile_count' => s3bubble_tile_count(),
                'read_more' => __('read more', 'streamium'),
                'autoplay_slider' => get_theme_mod( 'streamium_autoplay_home_slider', false ),
                'continue_watching' => __('Continue Watching', 'streamium'),
                'view_all' => __('View all', 'streamium'),
                'swal_error' => __('Error', 'streamium'),
                'swal_success' => __('Success', 'streamium'),
                'swal_ok' => __('Ok', 'streamium'),
                'swal_cancel' => __('Cancel', 'streamium'),
                'swal_ok_got_it' => __('Ok, got it!', 'streamium'),
                'swal_glad_you_liked_it' => __('Great Glad You Liked It', 'streamium'),
                'swal_tell_us_why' => __('Please tell us why', 'streamium'),
                'swal_write_something' => __('Write something', 'streamium'),
                'swal_enter_chars' => __('Please enter over 100 characters!', 'streamium'),
                'brand_sliders' => get_theme_mod( 'link_textcolor', 'red' ),
                'brand_control' => get_theme_mod( 'streamium_background_color', 'black' ),
                'brand_icons' => get_theme_mod( 'streamium_carousel_heading_color', 'white' ),
                'brand_social' => get_theme_mod( 'streamium_enable_video_social', false ),
            )
        );
 
        // Include main s3bubble js framework
        wp_enqueue_style('streamium-s3bubble-cdn', get_template_directory_uri() . '/production/css/s3bubble.min.css', array(), s3bubble_cache_version());
        wp_enqueue_script('streamium-s3bubble-cdn', get_template_directory_uri() . '/production/js/s3bubble.min.js', '', s3bubble_cache_version(), true);

    }

    add_action('wp_enqueue_scripts', 'streamium_enqueue_scripts');
}

/**
 * Include the scripts for the meta boxes
 *
 * @return null
 * @author  @sameast
 */
if (!function_exists('streamium_enqueue_admin_scripts')) {
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

function show_template() {
    if( is_super_admin() ){
        global $template;
        print_r($template);
    } 
}
//add_action('wp_footer', 'show_template');

/*-----------------------------------------------------------------------------------*/
/*  Include the Streamium Framework
/*-----------------------------------------------------------------------------------*/
$tempdir = get_template_directory();
require_once($tempdir .'/inc/init.php');
include_once( 'inc/import/init.php' );

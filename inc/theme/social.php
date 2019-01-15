<?php

/**
 * Social cards Twitter and Facebook
 *
 * @return bool
 * @author  @sameast
 */
function social_cards(){

    // Setup default params
    $social_url    = get_permalink();
    if(is_single()){
        
        $social_title  = get_the_title();
        $social_desc   = wp_trim_words( strip_tags(get_post_field('post_content', get_the_ID())), $num_words = 21, $more = null );
        $social_image  = wp_get_attachment_url( get_post_thumbnail_id() );

    }else{

        $social_title  = get_bloginfo( 'name' );
        $social_desc   = wp_trim_words( strip_tags(get_bloginfo( 'description' )), $num_words = 21, $more = null );
        $social_image  = esc_url( get_theme_mod_ssl( 'streamium_social_default_image' ) );
    
    }
    
    // Get handlers from the customizer
    $twitter_handler  = get_theme_mod( 'streamium_social_twitter_handler', '@s3bubble' );
    $facebook_handler = get_theme_mod( 'streamium_social_facebook_handler', 'https://www.facebook.com/s3bubble' );
    $social_site      = get_bloginfo( 'name' );

    // Check if video is in a series
    if(isset($_GET['v'])){

        $id           = $_GET['v'];
        $episodes     = get_post_meta(get_the_ID(), 'repeatable_fields' , true);
        $social_url   = get_permalink() . '?v=' . $id;
        $social_title = $episodes[$id]['titles'];
        $social_desc  = wp_trim_words( strip_tags($episodes[$id]['descriptions']), $num_words = 21, $more = null );
        $social_image = $episodes[$id]['thumbnails'];

    }

     // Twitter meta
    if(get_theme_mod( 'streamium_social_twitter_enabled', false )){

        echo '<meta name="twitter:card" value="summary_large_image" />
        <meta name="twitter:url" value="' . $social_url . '" />
        <meta name="twitter:title" value="' . $social_title . '" />
        <meta name="twitter:description" value="'. $social_desc .'" />
        <meta name="twitter:image" value="'. $social_image .'" />
        <meta name="twitter:site" value="' . $social_site . '" />
        <meta name="twitter:creator" value="' . $twitter_handler .'" />';

    }

    // Facebook mate
    if(get_theme_mod( 'streamium_social_facebook_enabled', false )){

        echo '<meta property="og:locale" content="en_US"/>
        <meta property="og:type" content="article"/>
        <meta property="og:title" content="' . $social_title . '"/>
        <meta property="og:description" content="'. $social_desc .'"/>
        <meta property="og:url" content="' . $social_url . '"/>
        <meta property="og:site_name" content="' . $social_site . '"/>
        <meta property="article:publisher" content="' . $facebook_handler . '"/>
        <meta property="article:tag" content="' . $social_title . '"/>
        <meta property="og:image" content="' . $social_image . '"/>
        <meta property="og:image:secure_url" content="' . $social_image . '"/>
        <meta property="og:image:width" content="1000"/>
        <meta property="og:image:height" content="562"/>';

    }

}

add_action('wp_head','social_cards');
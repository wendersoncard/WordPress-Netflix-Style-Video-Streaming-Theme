<?php

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function home_api_post() {

    // NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

    // Cat id
    $ind = (int)$_REQUEST['index'];

    // GET TAXONOMY URL::
    $rewriteGenresTax = get_theme_mod( 'streamium_global_settings_taxonomy_genres', 'genres' );

    // BUILD::
    $data = [];

    $customTaxonomy = get_terms(array(
        'order'             => 'ASC',
        'taxonomy'          => array('genres'),
        'hide_empty'        => false,
        'fields'            => 'all', 
    ));

    $homeTaxonomies = array();
    foreach ( $customTaxonomy as $cterm ) {

        $term_id = $cterm->term_id;
        $home_page = get_term_meta($term_id,'streamium_meta_homepage', true);
        if($home_page === 'on'){
            array_push($homeTaxonomies, $cterm);
        }

    }

    $categories = array_chunk($homeTaxonomies, 10);

    if(!isset($categories[$ind])){

       	wp_send_json(array(
            'error' => true,
            'message' => 'Finished'
        ));     

    }

    // Loop through cats and return the movies
    foreach ($categories[$ind] as $category) :

        $dataPosts = [];

        switch (true) {
            case get_term_meta($category->term_id,'streamium_recently_watched', true):
                
                $args = array(
                    'posts_per_page' => (int)get_theme_mod('streamium_global_settings_post_count', 20),
                    'post_type'      => 'content',
                    'post_status'    => 'publish',
                    'post__in'       => streamium_watched_get_streams(),
                    'orderby'        => 'post__in' 
                );

                // HEADER META::
                $profile = streamium_get_current_user_profile();
                $title = ucfirst($category->name);
                if(!empty($profile->profile)){
                    $title .= ' ' . ucwords($profile->profile);
                }

                break;

            case get_term_meta($category->term_id,'streamium_most_watched', true):

                $args = array(
                    'posts_per_page' => (int)get_theme_mod('streamium_global_settings_post_count', 20),
                    'post_type'      => 'content',
                    'post_status'    => 'publish',
                    'meta_key'       => 'media_views_count',
                    'orderby'        => 'meta_value_num',
                );

                // HEADER META::
                $title = ucfirst($category->name);
            
                break;

            case $_REQUEST['isHome']:
                
                $args = array(
                    'posts_per_page' => (int)get_theme_mod('streamium_global_settings_post_count', 20),
                    'post_status'    => 'publish',
                    'orderby'        => 'menu_order', 
                    'order'          => 'ASC', 
                    'tax_query'      => array(
                        'relation' => 'AND', 
                        array( 
                            'taxonomy'  => 'genres',
                            'field'     => 'term_id',
                            'terms'     => $category->term_id
                        )
                    )
                );

                // HEADER META::
                $title = ucfirst($category->name);
            
                break;
            
            default:
                
                if(isset($_REQUEST['query']['term_taxonomy_id'])){

                    $content_types_id = (int) $_REQUEST['query']['term_taxonomy_id'];
                    $args = array(
                        'posts_per_page' => (int)get_theme_mod('streamium_global_settings_post_count', 20),
                        'post_status'    => 'publish',
                        'orderby'        => 'menu_order',
                        'order'          => 'ASC', 
                        'tax_query'      => array(
                            'relation' => 'AND', 
                            array(
                                'taxonomy'  => 'genres',
                                'field'     => 'term_id',
                                'terms'     => $category->term_id
                            ),
                            array(
                                'taxonomy'  => 'content_types',
                                'field'     => 'term_id',
                                'terms'     => $content_types_id
                            )
                        )
                    );

                    // HEADER META::
                    $title = ucfirst($category->name);
                    
                }

                break;
        }

        $loop = new WP_Query($args);

        // CHECK OVERRIDES::
        $orientation = get_theme_mod( 'streamium_global_tile_orientation', 'landscape' );
        if(get_term_meta($category->term_id,'streamium_meta_orientation', true)){
        	$orientation = get_term_meta($category->term_id,'streamium_meta_orientation', true);
        }

        $tileType = get_theme_mod( 'streamium_global_tile_types', 'hover' );
        if(get_term_meta($category->term_id,'streamium_meta_tile_type', true)){
            $tileType = get_term_meta($category->term_id,'streamium_meta_tile_type', true);
        } 

        $tileCount = get_theme_mod( 'streamium_global_tile_count', 6 );
        if(get_term_meta($category->term_id,'streamium_meta_tile_count', true)){
        	$tileCount = get_term_meta($category->term_id,'streamium_meta_tile_count', true);
        }

        if ($loop->have_posts()):

            while ($loop->have_posts()) : $loop->the_post();
                
                // PLACEHOLDERS IF NOTHING IS SET::
                $tile  = "https://via.placeholder.com/300x169";
                $tileExpanded   = "https://via.placeholder.com/500x281";

                // DEFAULT::
                if (has_post_thumbnail()) : 

                    $tile         =  get_the_post_thumbnail_url(
                        get_the_ID(), 
                        'content_tile_full_width_landscape'
                    );

                    $tileExpanded =  get_the_post_thumbnail_url(
                        get_the_ID(), 
                        'content_tile_full_width_landscape'
                    );

                endif;

                // THESE SHOULD IDEALLY BE SET::
                if (class_exists('MultiPostThumbnails')) {                              
                    
                    // SET ORIENTATION::
                    $thumbs = new MultiPostThumbnails();
                    if (MultiPostThumbnails::has_post_thumbnail(get_post_type( get_the_ID() ), 'content_tile_' . $orientation)) { 
                        $tile         = $thumbs->get_post_thumbnail_url(
                            'content',
                            'content_tile_' . $orientation, 
                            get_the_ID(), 
                            'content_tile_' . $orientation
                        );
                        $tileExpanded = $thumbs->get_post_thumbnail_url(
                            'content',
                            'content_tile_expanded_' . $orientation, 
                            get_the_ID(), 
                            'content_tile_expanded_' . $orientation
                        );
                    }
                                       
                }

                $extraMeta = "";
                $streamium_extra_meta = get_post_meta( get_the_ID(), 'streamium_premium_meta_box_extra_meta_text', true );
                if ( ! empty( $streamium_extra_meta ) ) {
                    $extraMeta = '<h5>' . $streamium_extra_meta . '</h5>';
                }

                // GET COMMENTS::
                $comments_count = wp_count_comments(get_the_ID());

                // GET PERCENTAGE WATCHED::
                $progressBar = streamium_watched_get_stream_percentage(get_the_ID());

                // TESTS::
                $tileVideo =   ['http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreetAndDirt.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/VolkswagenGTIReview.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4',
                                'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WhatCarCanYouGetForAGrand.mp4'
                            ];

                $price = 'Free';
                if ( class_exists( 'WooCommerce' ) ) {
                    
                    $woo_product = get_post_meta( get_the_ID(), 'streamium_premium_meta_box_woo_product', true );
                    if(!empty($woo_product)){
                        $woo = wc_get_product($woo_product)->get_data();
                        if(isset($woo['price'])){
                            $price = '$' . $woo['price'];
                        }
                    }
                    
                }

                $dataPosts[] = array(
                    'id'              => get_the_ID(),
                    'post'            => $loop->post,
                    'tileUrl'         => esc_url($tile),
                    'tileUrlExpanded' => esc_url($tileExpanded),
                    'tileVideo'       => $tileVideo[array_rand($tileVideo)],
                    'link'            => get_the_permalink(),
                    'title'           => get_the_title(),
                    'text'            => wp_trim_words(get_the_content(), 18, '...'),
                    'content'         => get_the_content(),
                    'price'           => $price,
                    'progressBar'     => (int)$progressBar,
                    'extraMeta'       => $extraMeta,
                    'reviews'         => $comments_count->approved,
                    'nonce'           => wp_create_nonce('streamium_likes_nonce')
                );

            endwhile;
        endif;
        wp_reset_query();

        $data[] = array(
            'meta' => array(
                'count'       => (int)$loop->post_count,
                'name'        => $title,
                'home'        => esc_url(home_url()),
                'link'        => esc_url(home_url()) . '/' . $rewriteGenresTax . '/' . $category->slug,
                'catSlug'     => $category->slug,
                'orientation' => $orientation,
                'orientation' => $orientation,
                'tileCount'   => $tileCount,
                'tileType'    => $tileType
            ),
            'data' => $dataPosts
        );

    endforeach;

    wp_send_json(array(
        'error' => false,
        'data' => $data,
        'message' => 'Success'
    ));     

    die(); 

}

add_action( "wp_ajax_home_api_post", "home_api_post" );
add_action( "wp_ajax_nopriv_home_api_post", "home_api_post" );

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function tax_api_post() {

    // NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

    // Get params
    $userId  = get_current_user_id();
    $tax     = isset($_REQUEST['query']['taxonomy']) ? $_REQUEST['query']['taxonomy'] : "";
    $term_id = isset($_REQUEST['query']['term_id']) ? $_REQUEST['query']['term_id'] : "";
    $name    = isset($_REQUEST['query']['name']) ? $_REQUEST['query']['name'] : "";

    switch (isset($_REQUEST['search']) ? $_REQUEST['search'] : 'all') {
        case 'reviewed':

            remove_all_filters('posts_fields');
            remove_all_filters('posts_join');
            remove_all_filters('posts_groupby');
            remove_all_filters('posts_orderby');
            add_filter( 'posts_fields', 'streamium_search_distinct' );
            add_filter( 'posts_join','streamium_search_join');
            add_filter( 'posts_groupby', 'streamium_search_groupby' );
            add_filter( 'posts_orderby', 'streamium_search_orderby' );
            
            $args = array(
                'posts_per_page'   => -1,
                'post_status' => 'publish',
                'tax_query' => array(
                    array(
                        'taxonomy'  => $tax,
                        'field'     => 'term_id',
                        'terms'     => $term_id,
                    )
                ),
                'orderby' => 'date',
                'order'   => 'DESC', 
            );

            break;

        case 'newest':
            
            remove_all_filters('posts_fields');
            remove_all_filters('posts_join');
            remove_all_filters('posts_groupby');
            remove_all_filters('posts_orderby');
           
            $args = array(
                'posts_per_page'   => -1,
                'post_status' => 'publish',
                'tax_query' => array(
                    array(
                        'taxonomy'  => $tax,
                        'field'     => 'term_id',
                        'terms'     => $term_id,
                    )
                ),
                'orderby' => 'date',
                'order'   => 'DESC', 
            );
        
            break;

        case 'oldest':

            remove_all_filters('posts_fields');
            remove_all_filters('posts_join');
            remove_all_filters('posts_groupby');
            remove_all_filters('posts_orderby');
            
            $args = array(
                'posts_per_page'   => -1,
                'post_status' => 'publish',
                'tax_query' => array(
                    array(
                        'taxonomy'  => $tax,
                        'field'     => 'term_id',
                        'terms'     => $term_id,
                    )
                ),
                'orderby' => 'date',
                'order'   => 'ASC', 
            ); 
        
            break;
        
        default:

            $recently_watched = get_term_meta($term_id,'streamium_recently_watched', true);
            if(get_term_meta($term_id,'streamium_recently_watched', true)){
                
                $args = array(
                    'posts_per_page' => (int)get_theme_mod('streamium_global_settings_post_count', 20),
                    'post_type'      => 'content',
                    'post_status'    => 'publish',
                    'post__in'       => streamium_watched_get_streams(),
                    'orderby'        => 'post__in' 
                );

            }else if(get_term_meta($term_id,'streamium_most_watched', true)){

                $args = array(
                    'posts_per_page' => (int)get_theme_mod('streamium_global_settings_post_count', 20),
                    'post_type'      => 'content',
                    'post_status'    => 'publish',
                    'meta_key'       => 'media_views_count',
                    'orderby'        => 'meta_value_num',
                );

            }else{
                
                $args = array(
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                    'tax_query' => array(
                        array(
                            'taxonomy'  => $tax,
                            'field'     => 'term_id',
                            'terms'     => $term_id,
                        )
                    )
                );

            }

            break;
    }


    $loop = new WP_Query($args);

    // Setup empty array
    $dataPosts = [];

    // CHECK OVERRIDES::
    $orientation = get_theme_mod( 'streamium_meta_orientation', 'landscape' );
    if(get_term_meta($term_id,'streamium_meta_orientation', true)){
        $orientation = get_term_meta($term_id,'streamium_meta_orientation', true);
    }

    $tileType = get_theme_mod( 'streamium_global_tile_types', 'hover' );
    if(get_term_meta($term_id,'streamium_meta_tile_type', true)){
        $tileType = get_term_meta($term_id,'streamium_meta_tile_type', true);
    } 

    $tileCount = get_theme_mod( 'streamium_tile_count', 6 );
    if(get_term_meta($term_id,'streamium_meta_tile_count', true)){
        $tileCount = get_term_meta($term_id,'streamium_meta_tile_count', true);
    }

    if ( $loop->have_posts() ) { 

        $count = 0;
        $cat_count = 0; 
        $total_count = $loop->post_count;

        while ( $loop->have_posts() ) : $loop->the_post(); 

            // PLACEHOLDERS IF NOTHING IS SET::
            $tile  = "https://via.placeholder.com/300x169";
            $tileExpanded   = "https://via.placeholder.com/500x281";

            // DEFAULT::
            if (has_post_thumbnail()) : // thumbnail check

                $tile         =  get_the_post_thumbnail_url(
                    get_the_ID(), 
                    'content_tile_full_width_landscape'
                );
                $tileExpanded =  get_the_post_thumbnail_url(
                    get_the_ID(), 
                    'content_tile_full_width_landscape'
                );

            endif;

            // THESE SHOULD IDEALLY BE SET::
            if (class_exists('MultiPostThumbnails')) {                              
                
                // SET ORIENTATION::
                $thumbs = new MultiPostThumbnails();
                if (MultiPostThumbnails::has_post_thumbnail(get_post_type( get_the_ID() ), 'content_tile_' . $orientation)) { 
                    $tile         = $thumbs->get_post_thumbnail_url(
                        'content',
                        'content_tile_' . $orientation, 
                        get_the_ID(), 
                        'content_tile_' . $orientation
                    );
                    $tileExpanded = $thumbs->get_post_thumbnail_url(
                        'content',
                        'content_tile_expanded_' . $orientation, 
                        get_the_ID(), 
                        'content_tile_expanded_' . $orientation
                    );
                }
                                   
            }

            $extraMeta = "";
            $streamium_extra_meta = get_post_meta( get_the_ID(), 'streamium_premium_meta_box_extra_meta_text', true );
            if ( ! empty( $streamium_extra_meta ) ) {
                $extraMeta = '<h5>' . $streamium_extra_meta . '</h5>';
            }

            // GET COMMENTS::
            $comments_count = wp_count_comments(get_the_ID());

            $price = 'Free';
            $productLink = 'javascript:;';
            if ( class_exists( 'WooCommerce' ) ) {
                $productId = get_post_meta( get_the_ID(), 'streamium_premium_meta_box_woo_product', true );
                if(!empty($productId)){
                    $current_user = wp_get_current_user();
                    if(!wc_customer_bought_product($current_user->user_email,$current_user->ID, $productId)){
                        
                        $woo_product = get_post_meta( get_the_ID(), 'streamium_premium_meta_box_woo_product', true );
                        if(!empty($woo_product)){
                            $woo = wc_get_product($woo_product)->get_data();
                            if(isset($woo['price'])){
                                $price = '$' . $woo['price'];
                            }
                        }
                        $productLink = get_permalink( $productId );

                    }else{
                        $price = 'Purchased';
                    }
                }
            }

            // GET PERCENTAGE WATCHED::
            $progressBar = streamium_watched_get_stream_percentage(get_the_ID());

            $dataPosts[] = array(
                'id'              => get_the_ID(),
                'post'            => $loop->post,
                'tileUrl'         => esc_url($tile),
                'tileUrlExpanded' => esc_url($tileExpanded),
                'link'            => get_the_permalink(),
                'title'           => get_the_title(),
                'text'            => wp_trim_words(get_the_content(), 18, '...'),
                'price'           => $price,
                'productLink'     => $productLink,
                'progressBar'     => (int)$progressBar,
                'extraMeta'       => $extraMeta,
                'reviews'         => $comments_count->approved,
                'nonce'           => wp_create_nonce('streamium_likes_nonce')
            );

        endwhile;
        wp_reset_query();

        wp_send_json(array(
            'error' => false,
            'message' => 'Success',
            'meta' => array(
                'term_id'     => (int)$term_id,
                'title'       => ucwords($name),
                'name'        => $name,
                'tax'         => $tax,
                'orientation' => $orientation,
                'tileCount'   => (int)$tileCount,
                'tileType'    => $tileType,
                'catSlug'     => $term_id
            ),
            'data' => $dataPosts,
            'count' => (int)$loop->post_count,
            
        ));

    }else{

        // user is not logged in
        wp_send_json(array(
            'error' => true,
            'message' => 'No results found' 
        ));
        
    }     

    die(); 

}

add_action( "wp_ajax_tax_api_post", "tax_api_post" );
add_action( "wp_ajax_nopriv_tax_api_post", "tax_api_post" );

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function cats_api_post() {

    // NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

    // Cat id
    $catID = $_REQUEST['cat'];

    // Get options
    $setType = get_theme_mod('streamium_main_post_type', 'movie');
    $setTax = get_theme_mod('streamium_main_tax', 'movies'); 

	// Get params
	$userId = get_current_user_id();

    $typeTitle =  get_theme_mod('streamium_section_input_posttype_' . $setType, $setType);
    $taxUrl =  get_theme_mod('streamium_section_input_taxonomy_' . $setTax, $setTax);   

    $data = [];
    $dataPosts = [];

    $args = array(
        'posts_per_page' => (int)get_theme_mod('streamium_global_settings_post_count', 20),
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC', 
        'tax_query'      => array(
                array(
                        'taxonomy'  => $setTax,
                        'field'     => 'term_id',
                        'terms'     => $catID
                )
        )
    );

    $loop = new WP_Query($args);

    if ($loop->have_posts()):

        while ($loop->have_posts()) : $loop->the_post();
            
            // PLACEHOLDERS IF NOTHING IS SET::
            $tile  = "https://via.placeholder.com/300x169";
            $tileExpanded   = "https://via.placeholder.com/500x281";

            // DEFAULT::
            if (has_post_thumbnail()) : // thumbnail check

                $tile         =  get_the_post_thumbnail_url(
                    get_the_ID(), 
                    'content_tile_full_width_landscape'
                );
                $tileExpanded =  get_the_post_thumbnail_url(
                    get_the_ID(), 
                    'content_tile_full_width_landscape'
                );

            endif;

            // THESE SHOULD IDEALLY BE SET::
            if (class_exists('MultiPostThumbnails')) {                              
                
                // SET ORIENTATION::
                $thumbs = new MultiPostThumbnails();
                $type   = get_theme_mod( 'streamium_poster_orientation', 'landscape' );
                if (MultiPostThumbnails::has_post_thumbnail(get_post_type( get_the_ID() ), 'content_tile_' . $type)) { 
                    $tile         = $thumbs->get_post_thumbnail_url(
                        'content',
                        'content_tile_' . $type, 
                        get_the_ID(), 
                        'content_tile_' . $type
                    );
                    $tileExpanded = $thumbs->get_post_thumbnail_url(
                        'content',
                        'content_tile_expanded_' . $type, 
                        get_the_ID(), 
                        'content_tile_expanded_' . $type
                    );
                }
                                   
            }

            $paid = false;
            if($loop->post->premium){
                $paidTileText = str_replace(array("_"), " ", $loop->post->plans[0]);
                $paid = array(
                    'service' => 'woo',
                    'html'    => '<div class="tile_payment_details"><h2>' .  __( 'Available on', 'streamium' ) . ' <br/>' . $paidTileText . ' ' .  __( 'plan', 'streamium' ) . '</h2></div>',
                );

            }

            $extraMeta = "";
            $streamium_extra_meta = get_post_meta( get_the_ID(), 'streamium_premium_meta_box_extra_meta_text', true );
            if ( ! empty( $streamium_extra_meta ) ) {
                $extraMeta = '<h5>' . $streamium_extra_meta . '</h5>';
            }

            // GET COMMENTS::
            $comments_count = wp_count_comments(get_the_ID());

            $dataPosts[] = array(
                'id'              => get_the_ID(),
                'post'            => $loop->post,
                'tileUrl'         => esc_url($tile),
                'tileUrlExpanded' => esc_url($tileExpanded),
                'link'            => get_the_permalink(),
                'title'           => get_the_title(),
                'text'            => wp_trim_words(get_the_content(), 18, '...'),
                'paid'            => $paid,
                'extraMeta'       => $extraMeta,
                'reviews'         => $comments_count->approved,
                'nonce'           => wp_create_nonce('streamium_likes_nonce')
            );

        endwhile;
    endif;
    wp_reset_query();

    $category = get_term( $catID, $setTax );

    $data[] = array(
        'meta' => array(
            'id' => (int)$catID,
            'count' => (int)$loop->post_count,
            'title' => ucwords($typeTitle),
            'name' => ucfirst($category->name),
            'home' => esc_url(home_url()),
            'link' => esc_url(home_url()) . '/' . $taxUrl . '/' . $category->slug,
            'catSlug' => $category->slug
        ),
        'data' => $dataPosts
    );


    wp_send_json(array(
        'error' => false,
        'data' => $data,
        'message' => 'Success'
    ));     

    die(); 

}

add_action( "wp_ajax_cats_api_post", "cats_api_post" );
add_action( "wp_ajax_nopriv_cats_api_post", "cats_api_post" );

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function tag_api_post() {

    // NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

	// Get params
	$userId = get_current_user_id();
    $dataPosts = [];

    $s = sanitize_text_field($_REQUEST['query']['slug']);
    $args = array(
        'post_type'    => 'content',
        'post_status'  => 'publish',
        'orderby'      => 'menu_order',
        'order'        => 'ASC', 
        'tag_slug__in' => $s
    );

    $loop = new WP_Query($args);
    if ($loop->post_count > 0) {

    	// Only run if user is logged in
        if ($loop->have_posts()):
            while ($loop->have_posts()) : $loop->the_post();

                // PLACEHOLDERS IF NOTHING IS SET::
            $tile  = "https://via.placeholder.com/300x169";
            $tileExpanded   = "https://via.placeholder.com/500x281";

            // DEFAULT::
            if (has_post_thumbnail()) : // thumbnail check

                $tile         =  get_the_post_thumbnail_url(
                    get_the_ID(), 
                    'content_tile_full_width_landscape'
                );
                $tileExpanded =  get_the_post_thumbnail_url(
                    get_the_ID(), 
                    'content_tile_full_width_landscape'
                );

            endif;

            // THESE SHOULD IDEALLY BE SET::
            if (class_exists('MultiPostThumbnails')) {                              
                
                // SET ORIENTATION::
                $thumbs = new MultiPostThumbnails();
                $type   = get_theme_mod( 'streamium_poster_orientation', 'landscape' );
                if (MultiPostThumbnails::has_post_thumbnail(get_post_type( get_the_ID() ), 'content_tile_' . $type)) { 
                    $tile         = $thumbs->get_post_thumbnail_url(
                        'content',
                        'content_tile_' . $type, 
                        get_the_ID(), 
                        'content_tile_' . $type
                    );
                    $tileExpanded = $thumbs->get_post_thumbnail_url(
                        'content',
                        'content_tile_expanded_' . $type, 
                        get_the_ID(), 
                        'content_tile_expanded_' . $type
                    );
                }
                                   
            }

            $paid = false;
            if($loop->post->premium){
                $paidTileText = str_replace(array("_"), " ", $loop->post->plans[0]);
                $paid = array(
                    'service' => 'woo',
                    'html'    => '<div class="tile_payment_details"><h2>' .  __( 'Available on', 'streamium' ) . ' <br/>' . $paidTileText . ' ' .  __( 'plan', 'streamium' ) . '</h2></div>',
                );

            }

            $extraMeta = "";
            $streamium_extra_meta = get_post_meta( get_the_ID(), 'streamium_premium_meta_box_extra_meta_text', true );
            if ( ! empty( $streamium_extra_meta ) ) {
                $extraMeta = '<h5>' . $streamium_extra_meta . '</h5>';
            }

            // GET COMMENTS::
            $comments_count = wp_count_comments(get_the_ID());

            $dataPosts[] = array(
                'id'              => get_the_ID(),
                'post'            => $loop->post,
                'tileUrl'         => esc_url($tile),
                'tileUrlExpanded' => esc_url($tileExpanded),
                'link'            => get_the_permalink(),
                'title'           => get_the_title(),
                'text'            => wp_trim_words(get_the_content(), 18, '...'),
                'paid'            => $paid,
                'extraMeta'       => $extraMeta,
                'reviews'         => $comments_count->approved,
                'nonce'           => wp_create_nonce('streamium_likes_nonce')
            );

            endwhile;
        endif;
        wp_reset_query();

        wp_send_json(array(
    		'error' => false,
    		'data' => $dataPosts,
            'count' => (int)$loop->post_count,
            'message' => 'Success' 
    	));

    }else{

    	// user is not logged in
    	wp_send_json(array(
    		'error' => false,
            'data' => $dataPosts,
            'count' => 0,
    		'message' => 'Not taxs found' 
    	));

    }       

    die(); 

}

add_action( "wp_ajax_tag_api_post", "tag_api_post" );
add_action( "wp_ajax_nopriv_tag_api_post", "tag_api_post" );

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function search_api_post() {

    // NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

    // Get params
    $userId = get_current_user_id();

    if(isset($_REQUEST['search']['date'])){

        $date = $_REQUEST['search']['date'];

        if($_REQUEST['search']['date'] === 'day'){

            $loop = new WP_Query(array(
                'post_type' => 'content',
                'posts_per_page'   => -1,
                'post_status' => 'publish',
                'date_query' => array(
                     array(
                        'after' => '1 day ago'
                     ),
                 ),
        
            ));

        }else if($date === 'week'){

            $loop = new WP_Query(array(
                'post_type' => 'content',
                'posts_per_page'   => -1,
                'post_status' => 'publish',
                'date_query' => array(
                     array(
                        'after' => '1 week ago'
                     ),
                 ),
        
            ));

        }else{

            if(strpos($date, '/') !== false) {

                $date = explode('/', $date);
                $year  = $date[0];
                $month = $date[1];
                $day   = $date[2];
                $loop = new WP_Query(array(
                    'post_type' => 'content',
                    'posts_per_page'   => -1,
                    'post_status' => 'publish',
                    'date_query' => array(
                        array(
                          'year' => $year,
                          'month' => $month,
                          //'day' => $day 
                          ),
                        ),
            
                ));

            }else{

                $loop = new WP_Query(array(
                    'post_type' => 'content',
                    'posts_per_page'   => -1,
                    'post_status' => 'publish',
                    'date_query' => array(
                        array(
                          'year' => $date
                          ),
                        ),
            
                ));

            }
        }

    }else{

        $s = sanitize_text_field($_REQUEST['search']['s']);
        $loop = new WP_Query(array(
            'post_type' => 'content', 
            'posts_per_page'   => -1,
            'post_status' => 'publish',
            's' => $s
        )); 

    }

    // Setup empty array
    $dataPosts = [];

    // CHECK OVERRIDES::
    $orientation = get_theme_mod( 'streamium_meta_orientation', 'landscape' );
    if(get_term_meta($term_id,'streamium_meta_orientation', true)){
        $orientation = get_term_meta($term_id,'streamium_meta_orientation', true);
    }

    $tileCount = get_theme_mod( 'streamium_tile_count', 6 );
    if(get_term_meta($term_id,'streamium_meta_tile_count', true)){
        $tileCount = get_term_meta($term_id,'streamium_meta_tile_count', true);
    }

    if ( $loop->have_posts() ) { 

        $count = 0;
        $cat_count = 0; 
        $total_count = $loop->post_count;

        while ( $loop->have_posts() ) : $loop->the_post(); 

            // PLACEHOLDERS IF NOTHING IS SET::
            $tile  = "https://via.placeholder.com/300x169";
            $tileExpanded   = "https://via.placeholder.com/500x281";

            // DEFAULT::
            if (has_post_thumbnail()) : // thumbnail check

                $tile         =  get_the_post_thumbnail_url(
                    get_the_ID(), 
                    'content_tile_full_width_landscape'
                );
                $tileExpanded =  get_the_post_thumbnail_url(
                    get_the_ID(), 
                    'content_tile_full_width_landscape'
                );

            endif;

            // THESE SHOULD IDEALLY BE SET::
            if (class_exists('MultiPostThumbnails')) {                              
                
                // SET ORIENTATION::
                $thumbs = new MultiPostThumbnails();
                $type   = get_theme_mod( 'streamium_poster_orientation', 'landscape' );
                if (MultiPostThumbnails::has_post_thumbnail(get_post_type( get_the_ID() ), 'content_tile_' . $type)) { 
                    $tile         = $thumbs->get_post_thumbnail_url(
                        'content',
                        'content_tile_' . $type, 
                        get_the_ID(), 
                        'content_tile_' . $type
                    );
                    $tileExpanded = $thumbs->get_post_thumbnail_url(
                        'content',
                        'content_tile_expanded_' . $type, 
                        get_the_ID(), 
                        'content_tile_expanded_' . $type
                    );
                }
                                   
            }

            $paid = false;
            if($loop->post->premium){
                $paidTileText = str_replace(array("_"), " ", $loop->post->plans[0]);
                $paid = array(
                    'service' => 'woo',
                    'html'    => '<div class="tile_payment_details"><h2>' .  __( 'Available on', 'streamium' ) . ' <br/>' . $paidTileText . ' ' .  __( 'plan', 'streamium' ) . '</h2></div>',
                );

            }

            $extraMeta = "";
            $streamium_extra_meta = get_post_meta( get_the_ID(), 'streamium_premium_meta_box_extra_meta_text', true );
            if ( ! empty( $streamium_extra_meta ) ) {
                $extraMeta = '<h5>' . $streamium_extra_meta . '</h5>';
            }

            // GET COMMENTS::
            $comments_count = wp_count_comments(get_the_ID());

            $dataPosts[] = array(
                'id'              => get_the_ID(),
                'post'            => $loop->post,
                'tileUrl'         => esc_url($tile),
                'tileUrlExpanded' => esc_url($tileExpanded),
                'link'            => get_the_permalink(),
                'title'           => get_the_title(),
                'text'            => wp_trim_words(get_the_content(), 18, '...'),
                'paid'            => $paid,
                'extraMeta'       => $extraMeta,
                'reviews'         => $comments_count->approved,
                'nonce'           => wp_create_nonce('streamium_likes_nonce')
            );

        endwhile;
        wp_reset_query();

        wp_send_json(array(
            'error' => false,
            'message' => 'Success', 
            'data' => $dataPosts,
            'meta' => array(
                'title'       => ucwords('Search'),
                'name'        => 'Search',
                'tax'         => $tax,
                'orientation' => 'landscape',
                'tileCount'   => 6,
                'catSlug'     => 0
            ),
            'count' => (int)$loop->post_count
        ));

    }else{

        // user is not logged in
        wp_send_json(array(
            'error' => true,
            'message' => 'No results found' 
        ));

    }  

    die(); 

}

add_action( "wp_ajax_search_api_post", "search_api_post" );
add_action( "wp_ajax_nopriv_search_api_post", "search_api_post" );
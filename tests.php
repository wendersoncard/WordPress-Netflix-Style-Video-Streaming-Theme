<?php

	/*
	 Template Name: Tests Page Template
	 */


$querystr = "
    SELECT DISTINCT wp_posts.* FROM wp_posts LEFT JOIN wp_term_relationships tr ON (wp_posts.ID = tr.object_id) LEFT JOIN wp_term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) LEFT JOIN wp_terms t ON (tt.term_id = t.term_id) WHERE 1=1 AND ( ((wp_posts.post_title LIKE '%smith%') OR (wp_posts.post_content LIKE '%smith%') OR (tt.taxonomy = 'category' AND t.name LIKE '%smith%') OR (tt.taxonomy = 'post_tag' AND t.name LIKE '%smith%') OR (tt.taxonomy = 'movies' AND t.name LIKE '%smith%') OR (tt.taxonomy = 'programs' AND t.name LIKE '%smith%') OR (tt.taxonomy = 'sports' AND t.name LIKE '%smith%'))) AND wp_posts.post_type IN ('post', 'page', 'attachment', 'movie', 'tv', 'sport', 'kid', 'stream', 'product') AND (wp_posts.post_status = 'publish' OR wp_posts.post_author = 1 AND wp_posts.post_status = 'private') ORDER BY (CASE WHEN wp_posts.post_title LIKE '%will+smith%' THEN 1 WHEN wp_posts.post_title LIKE '%smith%' THEN 2 WHEN wp_posts.post_excerpt LIKE '%will+smith%' THEN 4 WHEN wp_posts.post_content LIKE '%will+smith%' THEN 5 ELSE 6 END), wp_posts.post_date DESC
 ";

 $pageposts = $wpdb->get_results($querystr, OBJECT);

 print_r($pageposts);


$userId = get_current_user_id();

        $loop = new WP_Query(array(
            'post_type' => array('movie', 'tv','sport','kid','stream'), 
            'posts_per_page'   => -1,
            's' => 'war'
        )); 

//print_r($loop);

        // Setup empty array
    $dataPosts = [];

    if ( $loop->have_posts() ) { 

        $count = 0;
        $cat_count = 0; 
        $total_count = $loop->post_count;

        while ( $loop->have_posts() ) : $loop->the_post(); 

            // Add some placeholder images
                $image  = "https://via.placeholder.com/300x169";
                $imageExpanded   = "https://via.placeholder.com/500x281";

                if (has_post_thumbnail()) : // thumbnail check

                    $image  = wp_get_attachment_image_url(get_post_thumbnail_id(), 'streamium-video-tile');
                    $imageExpanded   = wp_get_attachment_image_url(get_post_thumbnail_id(), 'streamium-video-tile-expanded');

                endif;

                // Allow a extra image to be added
                if (class_exists('MultiPostThumbnails')) {                              
                    
                    if (MultiPostThumbnails::has_post_thumbnail(get_post_type( get_the_ID() ), 'tile-expanded-image')) { 
                        
                        $image_id = MultiPostThumbnails::get_post_thumbnail_id( get_post_type( get_the_ID() ), 'tile-expanded-image', get_the_ID() );  // use the MultiPostThumbnails to get the image ID
                        $imageExpanded = wp_get_attachment_image_url( $image_id,'streamium-video-tile-expanded' ); // define full size src based on image ID

                    }                            
                 
                }; // end if MultiPostThumbnails 

                $paid = false;
                if($loop->post->premium){
                    $paidTileText = str_replace(array("_"), " ", $loop->post->plans[0]);
                    $paid = array(
                        'service' => 'woo',
                        'html' => '<div class="tile_payment_details"><h2>' .  __( 'Available on', 'streamium' ) . ' <br/>' . $paidTileText . ' ' .  __( 'plan', 'streamium' ) . '</h2></div>',
                    );

                }
                if (function_exists('is_protected_by_s2member')) {
                    $check = is_post_protected_by_s2member(get_the_ID());
                    if($check) { 
                        $ccaps = get_post_meta(get_the_ID(), 's2member_ccaps_req', true);
                        if(!empty($ccaps)){
                            $paidTileText = implode(",", $ccaps);
                        }else{
                            $paidTileText = implode(",", $check);
                        }
                        $paid = array(
                            'service' => 's2member',
                            'html' => '<div class="tile_payment_details"><h2>' .  __( 'Available on', 'streamium' ) . ' <br/>' . $paidTileText . ' ' .  __( 'plan', 'streamium' ) . '</h2></div>',
                        );
                    }
                }

                $progressBar = false;
                if(get_theme_mod( 'streamium_enable_premium' )) {
                    $progressBar = get_post_meta( get_the_ID(), 'user_' . $userId, true );
                }

                $extraMeta = "";
                $streamium_extra_meta = get_post_meta( get_the_ID(), 'streamium_extra_meta_meta_box_text', true );
                if ( ! empty( $streamium_extra_meta ) ) {
                    $extraMeta = '<h5>' . $streamium_extra_meta . '</h5>';
                }
                $dataPosts[] = array(
                    'id' => get_the_ID(),
                    'post' => $loop->post,
                    'tileUrl' => esc_url($image),
                    'tileUrlExpanded' => esc_url($imageExpanded),
                    'link' => get_the_permalink(),
                    'title' => get_the_title(),
                    'text' => wp_trim_words(get_the_content(), $num_words = 18, $more = '...'),
                    'paid' => $paid,
                    'progressBar' => (int)$progressBar,
                    'extraMeta' => $extraMeta,
                    'reviews' => get_streamium_likes(get_the_ID()),
                    'nonce' => wp_create_nonce('streamium_likes_nonce')
                );

        endwhile;
        wp_reset_query();

        echo json_encode(
            array(
                'error' => false,
                'data' => $dataPosts,
                'count' => (int)$loop->post_count,
                'message' => 'Sucesfully returning results' 
            )
        );

        die(); 

    }else{

        // user is not logged in
        echo json_encode(
            array(
                'error' => true,
                'message' => 'No results found' 
            )
        );

    }  

    die(); 
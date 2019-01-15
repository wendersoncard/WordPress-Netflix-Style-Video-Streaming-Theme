<?php

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function recently_watched_api_post() {

    // Run a nonce check
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "recently_watched_api_nonce")) {

        echo json_encode(
            array(
                'error' => true,
                'message' => 'No naughty business please' 
            )
        );     
        die();

    }   

	// Get params
	$userId = get_current_user_id();
    $dataPosts = [];

	// globally loop through post types.
	$args = array(
        'posts_per_page' => (int)get_theme_mod('streamium_global_options_homepage_desktop'),
        'post_type' => streamium_global_meta(),
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'recently_watched_user_id',
                'value' => get_current_user_id()
            )
        )
    );
    $loop = new WP_Query($args);
    if (is_user_logged_in() && $loop->post_count > 0) {

    	// Only run if user is logged in
        if ($loop->have_posts()):
            while ($loop->have_posts()) : $loop->the_post();

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

                $extraMeta = "";
                $streamium_extra_meta = get_post_meta( get_the_ID(), 'streamium_extra_meta_meta_box_text', true );
                if ( ! empty( $streamium_extra_meta ) ) {
                    $extraMeta = '<h5>' . $streamium_extra_meta . '</h5>';
                }

                $progressBar = false;
                if(get_theme_mod( 'streamium_enable_premium' )) {
                    $progressBar = get_post_meta( get_the_ID(), 'user_' . $userId, true );
                }

                if($progressBar < 95){

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

                }

            endwhile;
        endif;
        wp_reset_query();

        echo json_encode(
	    	array(
	    		'error' => false,
	    		'data' => $dataPosts,
                'count' => (int)$loop->post_count,
                'message' => 'Successfully returning results' 
	    	)
	    );

    }else{

    	// user is not logged in
    	echo json_encode(
	    	array(
	    		'error' => false,
                'data' => $dataPosts,
                'count' => 0,
	    		'message' => 'Not taxs found' 
	    	)
	    );

    }       

    die(); 

}

add_action( "wp_ajax_recently_watched_api_post", "recently_watched_api_post" );
add_action( "wp_ajax_nopriv_recently_watched_api_post", "recently_watched_api_post" );
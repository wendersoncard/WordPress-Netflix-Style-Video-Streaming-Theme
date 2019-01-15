<?php

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function search_api_post() {

    // Run a nonce check
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "search_api_nonce")) {

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

    //error_log(print_r($_REQUEST,true));

    if(isset($_REQUEST['search']['date'])){

        $date = $_REQUEST['search']['date'];

        if($_REQUEST['search']['date'] === 'day'){

            $loop = new WP_Query(array(
                'post_type' => streamium_global_meta(),
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
                'post_type' => streamium_global_meta(),
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
                    'post_type' => streamium_global_meta(),
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
                    'post_type' => streamium_global_meta(),
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
            'post_type' => streamium_global_meta(), 
            'posts_per_page'   => -1,
            'post_status' => 'publish',
            's' => $s
        )); 

    }

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

                    //error_log(print_r($imageExpanded,true));
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
                'message' => 'Succesfully returning results' 
            )
        );

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

}

add_action( "wp_ajax_search_api_post", "search_api_post" );
add_action( "wp_ajax_nopriv_search_api_post", "search_api_post" );
<?php

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function home_api_post() {

    // Run a nonce check
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "home_api_nonce")) {

        echo json_encode(
            array(
                'error' => true,
                'message' => 'No naughty business please' 
            )
        );     
        die(); 

    }

    // Get options
    $setType = get_theme_mod('streamium_main_post_type', 'movie');
    $setTax = get_theme_mod('streamium_main_tax', 'movies');    

	// Get params
	$userId = get_current_user_id();
    if(isset($_REQUEST['query']) && $_REQUEST['query'] != ""){
        $setTax = $_REQUEST['query']['taxonomies'][1];
        $rewrite = (get_theme_mod( 'streamium_section_input_taxonomy_' . $setTax )) ? get_theme_mod( 'streamium_section_input_taxonomy_' . $setTax ) : $setTax; 
    }

    $args = array(
      'parent' => 0,
      'hide_empty' => true
    );
    $categories = get_terms($setTax, $args);

    $data = [];

    foreach ($categories as $category) :

        $dataPosts = [];
        $typeTitle =  get_theme_mod('streamium_section_input_posttype_' . $setType, $setType);
        $taxUrl =  get_theme_mod('streamium_section_input_taxonomy_' . $setTax, $setTax);

        $args = array(
                'posts_per_page' => (int)get_theme_mod('streamium_global_options_homepage_desktop'),
                'tax_query' => array(
                        array(
                                'taxonomy'  => $setTax,
                                'field'     => 'term_id',
                                'terms'     => $category->term_id,
                        )
                )
        );

        $loop = new WP_Query($args);

        if ($loop->have_posts()):

            while ($loop->have_posts()) : $loop->the_post();
                
                // Add some placeholder images
                $image  = "http://via.placeholder.com/300x169";
                $imageExpanded   = "http://via.placeholder.com/500x281";

                if (has_post_thumbnail()) : // thumbnail check

                    $image  = wp_get_attachment_image_url(get_post_thumbnail_id(), 'streamium-video-tile');
                    $imageExpanded   = wp_get_attachment_image_url(get_post_thumbnail_id(), 'streamium-video-tile-expanded');

                endif;

                // This has been removed
                $trimexcerpt = !empty(get_the_excerpt()) ? get_the_excerpt() : get_the_content();

                $paid = false;
                if($loop->post->premium){
                    $paidTileText = str_replace(array("_"), " ", $loop->post->plans[0]);
                    $paid = array(
                        'service' => 'woo',
                        'html' => '<div class="tile_payment_details"><h2>Available on <br/>' . $paidTileText . ' plan</h2></div>',
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
                            'html' => '<div class="tile_payment_details"><h2>Available on <br/>plan ' . $paidTileText . '</h2></div>',
                        );
                    }
                }

                $progressBar = false;
                if(get_theme_mod( 'streamium_enable_premium' )) {
                    $progressBar = get_post_meta( get_the_ID(), 'user_' . $userId, true );
                }
                $dataPosts[] = array(
                    'id' => get_the_ID(),
                    'post' => $loop->post,
                    'tileUrl' => esc_url($image),
                    'tileUrlExpanded' => esc_url($imageExpanded),
                    'link' => get_the_permalink(),
                    'title' => get_the_title(),
                    'text' => wp_trim_words($trimexcerpt, $num_words = 18, $more = '...'),
                    'paid' => $paid,
                    'progressBar' => (int)$progressBar,
                    'reviews' => get_streamium_likes(get_the_ID()),
                    'nonce' => wp_create_nonce('streamium_likes_nonce')
                );

            endwhile;
        endif;
        wp_reset_query();

        $data[] = array(
            'meta' => array(
                'count' => (int)$loop->post_count,
                'title' => ucwords($typeTitle),
                'name' => ucfirst($category->name),
                'home' => esc_url(home_url()),
                'link' => esc_url(home_url()) . '/' . $taxUrl . '/' . $category->slug,
                'catSlug' => $category->slug
            ),
            'data' => $dataPosts
        );

    endforeach;

    echo json_encode(
        array(
            'error' => false,
            'data' => $data,
            'message' => 'User not logged in' 
        )
    );     

    die(); 

}

add_action( "wp_ajax_home_api_post", "home_api_post" );
add_action( "wp_ajax_nopriv_home_api_post", "home_api_post" );
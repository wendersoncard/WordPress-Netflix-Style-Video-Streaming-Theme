<?php

/**
 *
 * @param Returns related posts to a given post based on a specific taxonomy
 * @return filter
 */
function streamium_get_related_posts_by_common_terms( $post_id, $number_posts = 0, $taxonomy = 'post_tag', $post_type = 'content' ) {

    global $wpdb;

    $post_id = (int) $post_id;
    $number_posts = (int) $number_posts;

    $limit = $number_posts > 0 ? ' LIMIT ' . $number_posts : '';

    $related_posts_records = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT tr.object_id, count( tr.term_taxonomy_id ) AS common_tax_count
             FROM wp_term_relationships AS tr
             INNER JOIN wp_term_relationships AS tr2 ON tr.term_taxonomy_id = tr2.term_taxonomy_id
             INNER JOIN wp_term_taxonomy as tt ON tt.term_taxonomy_id = tr2.term_taxonomy_id
             INNER JOIN wp_posts as p ON p.ID = tr.object_id
             WHERE
                tr2.object_id = %d
                AND tt.taxonomy = %s
                AND p.post_type = %s
             GROUP BY tr.object_id
             HAVING tr.object_id != %d
             ORDER BY common_tax_count DESC" . $limit,
             $post_id, $taxonomy, $post_type, $post_id
        )
    );

    if ( count( $related_posts_records ) === 0 )
        return false;

    $related_posts = array();

    foreach( $related_posts_records as $record ){

      $post                   = get_post( (int) $record->object_id );
      $post->common_tax_count = $record->common_tax_count;
      $post->post_tag_count   = count(get_the_tags($post_id));
      $post->percentage       = ($record->common_tax_count/count(get_the_tags($post_id))) * 100 . '% match';
      $related_posts[]        = $post;

    }

    return $related_posts;

}

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function filtering_api_post() {

    // NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' ); 

    // Get params
	// GET CURRENT TERM ID::
    if(isset($_REQUEST['query']) && $_REQUEST['query'] != ""){
        if(isset($_REQUEST['query']['ID'])){
            $postId = (int) $_REQUEST['query']['ID'];
        }
    }
    $userId = get_current_user_id();
    $dataPosts = [];
    
    foreach (streamium_get_related_posts_by_common_terms(49) as $key => $post) {

        // PLACEHOLDERS IF NOTHING IS SET::
        $tile  = "https://via.placeholder.com/300x169";
        $tileExpanded   = "https://via.placeholder.com/500x281";

        // DEFAULT::
        if (has_post_thumbnail()) : // thumbnail check

            $tile         =  get_the_post_thumbnail_url(
                $post->ID, 
                'content_tile_full_width_landscape'
            );
            $tileExpanded =  get_the_post_thumbnail_url(
                $post->ID, 
                'content_tile_full_width_landscape'
            );

        endif;

        // THESE SHOULD IDEALLY BE SET::
        if (class_exists('MultiPostThumbnails')) {                              
            
            // SET ORIENTATION::
            $thumbs = new MultiPostThumbnails();
            $type   = get_theme_mod( 'streamium_poster_orientation', 'landscape' );
            if (MultiPostThumbnails::has_post_thumbnail(get_post_type( $post->ID ), 'content_tile_' . $type)) { 
                $tile         = $thumbs->get_post_thumbnail_url(
                    'content',
                    'content_tile_' . $type, 
                    $post->ID, 
                    'content_tile_' . $type
                );
                $tileExpanded = $thumbs->get_post_thumbnail_url(
                    'content',
                    'content_tile_expanded_' . $type, 
                    $post->ID, 
                    'content_tile_expanded_' . $type
                );
            }
                               
        }

        $extraMeta = "";
        $streamium_extra_meta = get_post_meta( $post->ID, 'streamium_premium_meta_box_extra_meta_text', true );
        if ( ! empty( $streamium_extra_meta ) ) {
            $extraMeta = '<h5>' . $streamium_extra_meta . '</h5>';
        }

        // GET COMMENTS::
        $comments_count = wp_count_comments($post->ID);

        $dataPosts[] = array(
            'id'              => $post->ID,
            'post'            => $loop->post,
            'tileUrl'         => esc_url($tile),
            'tileUrlExpanded' => esc_url($tileExpanded),
            'link'            => get_the_permalink($post->ID),
            'title'           => get_the_title($post->ID),
            'text'            => wp_trim_words(get_the_content($post->ID), 18, '...'),
            'paid'            => false,
            'extraMeta'       => $extraMeta,
            'reviews'         => $comments_count->approved,
            'nonce'           => wp_create_nonce('streamium_likes_nonce')
        );

    }

    wp_send_json(array(
        'error' => false,
        'data' => $dataPosts,
        'meta' => array(
            'title' => 'Related',
            'name' => ucfirst('Related'),
            'home' => esc_url(home_url()),
            'link' => esc_url(home_url()) . '/' . $taxUrl . '/' . $category->slug,
            'orientation' => 'landscape',
            'tileCount'   => 6,
            'catSlug'     => 'related'
        ),
        'message' => 'Success' 
    ));     

    die(); 

}

add_action( "wp_ajax_filtering_api_post", "filtering_api_post" );
add_action( "wp_ajax_nopriv_filtering_api_post", "filtering_api_post" );
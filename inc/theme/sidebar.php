<?php

/**
 * Gets all the extra video information
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_sidebar() {

	global $wpdb;

	// NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

	// Get params
	$postId = (int) $_REQUEST['postId'];

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$post_object = get_post( $postId );

    	if(!empty($post_object)){

    		//error_log(print_r($post_object, true));

            //  STARING::
			$posttags = get_the_tags($postId);
			$staring  = '';
			if ($posttags) {
				$staring = __( 'Cast', 'streamium' ) . ": ";
				$numItems = count($posttags);
				$i = 0;
			  	foreach($posttags as $tag) {

				  	$staring .= '<a href="' . esc_url(get_tag_link ($tag->term_id)) . '">' . ucwords($tag->name) . '</a>';
				  	if(++$i !== $numItems) {
			    		$staring .= ', ';
			  		}

			    }
			    $staring = '<li class="synopis-meta-spacer">' . $staring . '</li>';
			}

			// GENRES::
			$categories = get_the_terms( $postId, 'genres' );
			$genres     = '';
			if ($categories) {

				$genres = __( 'Genres', 'streamium' ) . ': ';
				$numItems = count($categories);
				$i = 0;
			  	foreach($categories as $cat) {

			  		$genres .= '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . ucwords($cat->name) . '</a>';
			  		if(++$i !== $numItems) {
			    		$genres .= ', ';
			  		}

			  	}

			  	$genres = '<li>' . $genres . '</li>';

			}

			// RELEASED::
			$releaseDate = get_post_meta( $postId, 'streamium_release_date_meta_box_text', true );
			$released = '';
			if(!empty($releaseDate)){
				$released = '<li>' . __( 'Released', 'streamium' ) . ': ' . $releaseDate . '</li>';
			}	

			// RATING::
			$rating = '';
			$streamium_ratings = get_post_meta( $postId, 'streamium_premium_meta_box_ratings_text', true );
			if ( ! empty( $streamium_ratings ) ) {
				$rating = '<li>' . __( 'Rating', 'streamium' ) . ': ' . $streamium_ratings . '</a></li>';
			}

			// GET MAIN LANSCAPE IMAGE::
            $poster = wp_get_attachment_image_url( get_post_thumbnail_id( $postId ), 'content_tile_full_width_landscape' );

            // SEASONS::
            $seasons = get_post_meta( $post_object->ID, 'repeatable_fields', true);
            
	    	wp_send_json(array(
	    		'error'        => false,
	    		'id'           => $post_object->ID,
	    		'title'        => $post_object->post_title,
	    		'movie_code'   => get_post_meta( $post_object->ID, 'streamium_meta_box_movie_code', true ),
	    		'trailer_code' => get_post_meta( $post_object->ID, 'streamium_premium_meta_box_trailer_code', true ),
	    		'content'      => $post_object->post_content,
	    		'genres'       => $genres,
	    		'released'     => $released,
	    		'rating'       => $rating,
	    		'seasons'      => $seasons,
	    		'poster'       => isset($poster) ? $poster : "",
	    		'href'         => get_permalink($postId)
	    	));

	    }else{

	    	wp_send_json(array(
	    		'error' => true,
	    		'message' => 'We could not find this post.'
	    	));

	    }

        die();

    }
    else {
        
        wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
        exit();

    }

}

add_action( 'wp_ajax_nopriv_streamium_get_sidebar', 'streamium_get_sidebar' );
add_action( 'wp_ajax_streamium_get_sidebar', 'streamium_get_sidebar' );

/**
 * Gets all the extra video information
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_sidebar_season_episodes() {

	global $wpdb;

	// NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

	// Get params
	$postId = (int) $_REQUEST['postId'];
	$seasonId = (int) $_REQUEST['seasonId'];

	$args = array(
        'posts_per_page' => -1,
        'post_type'      => 'content',
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'meta_query'     => array(
            array(
                'key'     => 'season_post_id',
                'value'   => $postId,
                'compare' => '='
            ),
            array(
                'key'     => 'season_id',
                'value'   => $seasonId,
                'compare' => '='
            )  
        )
    );

    $query = new WP_Query($args);

    if ( $query->have_posts() ) { 

    	// ADD IMAGE::
		foreach ($query->posts as $key => $post) {
			
			if(has_post_thumbnail($post->ID)){
				$post->post_image = get_the_post_thumbnail_url($post->ID, 'sidebar_episodes_tile');
			}else{
				$post->post_image = 'https://via.placeholder.com/210x118';
			}
			$post->post_text = wp_trim_words(strip_tags($post->post_content), 10, '...');
			$post->post_href = get_the_permalink($post->ID); 

		}

    	wp_send_json(array(
    		'error'    => false,
    		'message'  => 'Success',
    		'data' => $query->posts
    	));

    }else{

    	wp_send_json(array(
    		'error'    => true,
    		'message'  => 'No posts'
    	));

    }

}

add_action( 'wp_ajax_nopriv_streamium_get_sidebar_season_episodes', 'streamium_get_sidebar_season_episodes' );
add_action( 'wp_ajax_streamium_get_sidebar_season_episodes', 'streamium_get_sidebar_season_episodes' );

/**
 * Gets all the extra video information
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_sidebar_more_like() {

	global $wpdb;

	// NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

	// Get params
	$postId = (int) $_REQUEST['postId'];

	$posts = streamium_get_related_posts_by_common_terms($postId);

	// ADD IMAGE::
	foreach ($posts as $key => $post) {

		if(has_post_thumbnail($post->ID)){
			$post->post_image = get_the_post_thumbnail_url($post->ID, 'sidebar_episodes_tile');
		}else{
			$post->post_image = 'https://via.placeholder.com/210x118';
		}
		$post->post_text = wp_trim_words(strip_tags($post->post_content), 10, '...');
		$post->post_href = get_the_permalink($post->ID); 

	}


	wp_send_json(array(
		'error'    => false,
		'message'  => 'Success',
		'data' => $posts
	));

}

add_action( 'wp_ajax_nopriv_streamium_get_sidebar_more_like', 'streamium_get_sidebar_more_like' );
add_action( 'wp_ajax_streamium_get_sidebar_more_like', 'streamium_get_sidebar_more_like' );

/**
 * Gets all the extra video information
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_sidebar_cast() {

	global $wpdb;

	// NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

	// Get params
	$postId = (int) $_REQUEST['postId']; 	
 	$credit_ids = get_post_meta( $postId, '_credit_id', false );

	$args = array(
	    'post_type' => 'credits',
	    'posts_per_page' => -1,
        'post__in' => $credit_ids
	);
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {

		// ADD IMAGE::
		foreach ($query->posts as $key => $post) {
			
			if(has_post_thumbnail($post->ID)){
				$post->post_image = get_the_post_thumbnail_url($post->ID, 'sidebar_cast_tile');
			}else{
				$post->post_image = 'https://via.placeholder.com/128x128';
			}
			$post->post_text = wp_trim_words(strip_tags($post->post_content), 20, '...');
			$post->post_href = get_the_permalink($post->ID); 

		}

    	wp_send_json(array(
    		'error'    => false,
    		'message'  => 'Success',
    		'data' => $query->posts
    	));

	}

}

add_action( 'wp_ajax_nopriv_streamium_get_sidebar_cast', 'streamium_get_sidebar_cast' );
add_action( 'wp_ajax_streamium_get_sidebar_cast', 'streamium_get_sidebar_cast' );


/**
 * Gets all the extra video information
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_sidebar_reviews() {

	global $wpdb;

	// NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

	// Get params
	$postId = (int) $_REQUEST['postId'];

	$posts = get_comments('post_id=' . $postId);

	// ADD IMAGE::
	foreach ($posts as $key => $post) {

		$post->post_title = $post->comment_author;

		if(has_post_thumbnail($post->ID)){
			$post->post_image = get_the_post_thumbnail_url($post->ID, 'sidebar_cast_tile');
		}else{
			$post->post_image = 'https://via.placeholder.com/128x128';
		}
		$post->post_text = wp_trim_words(strip_tags($post->comment_content), 20, '...');

	}


	wp_send_json(array(
		'error'    => false,
		'message'  => 'Success',
		'data' => $posts
	));

}

add_action( 'wp_ajax_nopriv_streamium_get_sidebar_reviews', 'streamium_get_sidebar_reviews' );
add_action( 'wp_ajax_streamium_get_sidebar_reviews', 'streamium_get_sidebar_reviews' );
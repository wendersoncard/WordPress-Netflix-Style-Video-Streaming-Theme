<?php

function streamium_comment_columns( $columns ){
	
	$columns['rating'] = __( 'Rating', 'streamium' );
	return $columns;

}
add_filter( 'manage_edit-comments_columns', 'streamium_comment_columns' );

function streamium_comment_column( $column, $comment_ID ){

	if ( 'rating' == $column ) {
		if ( $meta = get_comment_meta( $comment_ID, $column, true ) ) {
			echo $meta;
		}
	}

}
add_filter( 'manage_comments_custom_column', 'streamium_comment_column', 10, 2 );

/**
 * Streamium rating ajax
 *
 * @return bool
 * @author  @sameast
 */
function streamium_likes() {

	global $wpdb;

	//error_log(print_r($_REQUEST,true));

	// PARAMS::
	$userId   = get_current_user_id();
	$postId   = $_REQUEST['post_id'];
	$rating   = $_REQUEST['rating'];
	$message  = $_REQUEST['message'];
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'streamium_likes_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }

    // Check if user is logged in
    if ( !is_user_logged_in() ) {

    	echo json_encode(
	    	array(
	    		'error' => true,
	    		'message' => __( 'You must be logged in to like or dislike', 'streamium' ) 
	    	)
	    );

	    die();

    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$time = current_time('mysql');
		$data = array(
		    'comment_post_ID' => $postId,
		    'comment_author' => $currentUser->user_login,
		    'comment_author_email' => $currentUser->user_email,
		    'comment_content' => $message,
		    'comment_type' => get_post_type($postId),
		    'user_id' => $userId,
		    'comment_date' => $time,
		    'comment_approved' => 0
		);

		$comment_id = wp_insert_comment($data);

		if($comment_id){

			// ADD RATING::
			add_comment_meta($comment_id, 'rating', $rating);

			// GET COMMENTS::
            $comments_count = wp_count_comments($postId);
            
			wp_send_json(array(
	            'error' => false,
		    	'likes' => __( 'Thanks', 'streamium' ),
		    	'message' => 'Successfully added your rating' 
	        ));

		}else{

			wp_send_json(array(
	            'error' => true,
		    	'message' => 'Error adding review'
	        ));

		}

        die();

    }
    else {
        
        wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
        exit();

    }

}

add_action( 'wp_ajax_nopriv_streamium_likes', 'streamium_likes' );
add_action( 'wp_ajax_streamium_likes', 'streamium_likes' );

/**
 * Ajax sidebar get reviews
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_reviews() {

	global $wpdb;

	// PARAMS::
	$postId = $_REQUEST['post_id'];
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'streamium_likes_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }

    // Check if user is logged in
    if ( !is_user_logged_in() ) {

    	echo json_encode(
	    	array(
	    		'error' => true,
	    		'message' => __( 'You must be logged in to view reviews', 'streamium' )
	    	)
	    );

	    die();

    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$comments = get_comments('post_id=' . $postId . '&status=approve');
    	if($comments){

    		$buildGetReviews = [];

    		error_log(count($comments));

    		$totalComments = count($comments);
    		$tallyComments = 0;
    		foreach($comments as $comment) : 

    			$rating         = (int) get_comment_meta( $comment->comment_ID, 'rating', true );
    			$tallyComments  += $rating;

				array_push($buildGetReviews, array(
		    		'username' => $comment->comment_author,
		    		'avatar'   => get_avatar_url($comment->user_id, array( "size" => 64 ) ),
		    		'post_id'  => $comment->comment_post_ID,
		    		'rating'   => $rating,
		    		'message'  => $comment->comment_content,
		    		'time'     => $comment->comment_date
		    	));
			endforeach;

			error_log((($totalComments*5)/$tallyComments));

			wp_send_json(array(
	            'error' => false,
	    		'title' => get_the_title($postId),
	    		'data' => $buildGetReviews,
	    		'totalRating' => (($totalComments*5)/$tallyComments),
	    		'message' => __( 'Successfully added your rating', 'streamium' ) 
	        ));

    	}else{

    		wp_send_json(array(
	            'error' => true,
		    	'message' => __( 'No reviews', 'streamium' )
	        ));

    	}

        die();

    }
    else {
        
        wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
        exit();

    }

}

add_action( 'wp_ajax_nopriv_streamium_get_reviews', 'streamium_get_reviews' );
add_action( 'wp_ajax_streamium_get_reviews', 'streamium_get_reviews' );

/**
 * Get like count for post
 *
 * @return bool
 * @author  @sameast
 */
function get_streamium_likes($post_id) {

	// GET COMMENTS::
    $comments_count = wp_count_comments($post_id);
    if($comments_count->approved == 0){
    	return __( 'Rate', 'streamium' );
    }else if($comments_count->approved == 1){
    	return $comments_count->approved . ' ' . __( 'Rating', 'streamium' );
    }else{
    	return $comments_count->approved . ' ' . __( 'Ratings', 'streamium' );
    }
    

}
<?php


/**
 * Streamium rating ajax
 *
 * @return bool
 * @author  @sameast
 */
function streamium_add_review() {

	global $wpdb;

	// Get params
	$currentUser = wp_get_current_user();
	$postId      = $_REQUEST['post_id'];
	$message     = $_REQUEST['message'];
 
    // NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

    // Check if user is logged in
    if ( !is_user_logged_in() ) {

    	wp_send_json(array(
    		'error' => true,
    		'message' => __( 'You must be logged in to like or dislike', 'streamium' ) 
    	));

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
		    'comment_approved' => 1,
		);

		$comment = wp_insert_comment($data);

		if($comment){

			// GET COMMENTS::
            $comments_count = wp_count_comments($postId);
            
			wp_send_json(array(
	            'error' => false,
		    	'likes' => $comments_count->approved,
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

add_action( 'wp_ajax_nopriv_streamium_add_review', 'streamium_add_review' );
add_action( 'wp_ajax_streamium_add_review', 'streamium_add_review' );

/**
 * Ajax sidebar get reviews
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_reviews() {

	global $wpdb;

	// Get params
	$postId = $_REQUEST['post_id'];
 
    // NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );

    // Check if user is logged in
    if ( !is_user_logged_in() ) {

    	wp_send_json(array(
    		'error' => true,
    		'message' => __( 'You must be logged in to view reviews', 'streamium' )
    	));

	    die();

    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$comments = get_comments('post_id=' . $postId);
    	if($comments){

    		$buildGetReviews = [];
    		foreach($comments as $comment) :
				array_push($buildGetReviews, array(
		    		'username' => $comment->comment_author,
		    		'avatar' => get_avatar_url($comment->user_id, array( "size" => 64 ) ),
		    		'post_id' => $comment->comment_post_ID,
		    		'message' => $comment->comment_content,
		    		'time' => $comment->comment_date
		    	));
			endforeach;

			wp_send_json(array(
	            'error' => false,
	    		'title' => get_the_title($postId),
	    		'data' => $buildGetReviews,
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
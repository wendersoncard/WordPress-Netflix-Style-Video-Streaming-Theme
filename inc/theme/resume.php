<?php

function streamium_create_resume() {

	global $wpdb;

	// Get params
	$postId = $_REQUEST['post_id'];
	$userId = get_current_user_id();
	$percentage = $_REQUEST['percentage'];
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'single_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }

    // Check if user is logged in
    if ( !is_user_logged_in() ) {

    	echo json_encode(
	    	array(
	    		'error' => true,
	    		'message' => 'You must be logged in to view reviews' 
	    	)
	    );

	    die();

    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	update_post_meta( $postId, 'user_' . $userId, $percentage );

    	echo json_encode(
	    	array(
	    		'error' => false,
	    		'percentage' => $percentage,
	    		'message' => 'Successfully added user resume' 
	    	)
	    );

        die();

    }
    else {
        
        wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
        exit();

    }

}

add_action( 'wp_ajax_streamium_create_resume', 'streamium_create_resume' );
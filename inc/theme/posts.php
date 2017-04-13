<?php

function load_scripts() {

    if( is_page() || is_single() )
    {
    	$nonce = wp_create_nonce( 'single_nonce' );
    	$s3videoid = get_post_meta( get_the_ID(), 's3bubble_video_code_meta_box_text', true );
    	$streamiumVideoTrailer = get_post_meta( get_the_ID(), 'streamium_video_trailer_meta_box_text', true );
    	
    	if(is_user_logged_in()){
    		$userId = get_current_user_id();
    		$percentageWatched = get_post_meta( get_the_ID(), 'user_' . $userId, true );
    	}

    	if ( get_theme_mod( 'streamium_enable_premium' ) ) {

    		// Setup premium
	        wp_enqueue_script('video-post', get_template_directory_uri() . '/dist/js/single.premium.min.js', array('jquery'), '', false);
	        wp_localize_script( 'video-post', 'video_post_object', 
	            array( 
	                'ajax_url' => admin_url( 'admin-ajax.php'),
	                'post_id' => !empty(get_the_ID()) ? get_the_ID() : false,
	                'percentage' => !empty($percentageWatched) ? $percentageWatched : 0,
	                'code' => (isset($_GET['trailer'])) ? $streamiumVideoTrailer : $s3videoid,
	                'trailer' => $streamiumVideoTrailer,
	                'nonce' => $nonce
	            )
	        );

        }else{

        	//setup standard
        	wp_enqueue_script('video-post', get_template_directory_uri() . '/dist/js/single.standard.min.js', array('jquery'), '', false);
	        wp_localize_script( 'video-post', 'video_post_object', 
	            array( 
	                'ajax_url' => admin_url( 'admin-ajax.php'),
	                'post_id' => !empty(get_the_ID()) ? get_the_ID() : false,
	                'code' => ( ! empty( $s3videoid ) && filter_var($s3videoid, FILTER_VALIDATE_URL) ) ? $s3videoid : NULL
	            )
	        );

        } 

    } 

}

add_action('wp_enqueue_scripts', 'load_scripts');

function streamium_get_dynamic_content() {

	global $wpdb;

	// Get params
	$cat = $_REQUEST['cat'];
	$postId = $_REQUEST['post_id'];
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'pt_like_it_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$post_object = get_post( $postId );

    	if(!empty($post_object)){

    		$content = (isMobile()) ? $post_object->post_excerpt : $post_object->post_content;
	    	$fullImage  = wp_get_attachment_image_src( get_post_thumbnail_id( $postId ), 'streamium-home-slider' ); 
	    	$streamiumVideoTrailer = get_post_meta( $postId, 'streamium_video_trailer_meta_box_text', true );
	    	$nonce = wp_create_nonce( 'pt_like_it_nonce' );
	    	$link = admin_url('admin-ajax.php?action=pt_like_it&post_id='.get_the_ID().'&nonce='.$nonce);
	        $likes = get_post_meta( $postId, '_pt_likes', true );
	        $likes = ( empty( $likes ) ) ? 0 : $likes;

	    	echo json_encode(
		    	array(
		    		'error' => false,
		    		'cat' => $cat,
		    		'title' => $post_object->post_title,
		    		'content' => $content,
		    		'bgimage' =>  $fullImage[0],
		    		'trailer' => $streamiumVideoTrailer,
		    		'href' => get_permalink($postId),
		    		'likes' => $likes,
		    		'link' => $link,
		    		'id' => $postId,
		    		'nonce' => $nonce
		    	)
		    );

	    }else{

	    	echo json_encode(
		    	array(
		    		'error' => true,
		    		'message' => 'We could not find this post.'
		    	)
		    );

	    }

        die();

    }
    else {
        
        wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
        exit();

    }

}

add_action( 'wp_ajax_nopriv_streamium_get_dynamic_content', 'streamium_get_dynamic_content' );
add_action( 'wp_ajax_streamium_get_dynamic_content', 'streamium_get_dynamic_content' );

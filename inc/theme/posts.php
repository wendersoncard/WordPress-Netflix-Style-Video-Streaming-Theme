<?php

/**
 * Ajax post scipts for single post
 *
 * @return bool
 * @author  @sameast
 */
function streamium_single_video_scripts() {

    if( is_single() )
    {
    	$nonce = wp_create_nonce( 'single_nonce' );
    	$s3videoid = get_post_meta( get_the_ID(), 's3bubble_video_code_meta_box_text', true );
    	$streamiumVideoTrailer = get_post_meta( get_the_ID(), 'streamium_video_trailer_meta_box_text', true );
    	
    	if(is_user_logged_in()){
    		$userId = get_current_user_id();
    		$percentageWatched = get_post_meta( get_the_ID(), 'user_' . $userId, true );
    	}

    	if ( get_theme_mod( 'streamium_enable_premium' ) ) {

    		if(pathinfo($s3videoid, PATHINFO_DIRNAME) !== "."){
			    $s3videoid = pathinfo($s3videoid, PATHINFO_BASENAME);
			}
    		// Setup premium
	        wp_enqueue_script('video-post', get_template_directory_uri() . '/dist/js/single.premium.min.js', array( 'streamium-s3bubble-cdn'),'1.1', true );
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
        	wp_enqueue_script('video-post', get_template_directory_uri() . '/dist/js/single.standard.min.js', array( 'streamium-s3bubble-cdn'),'1.1', true );
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

add_action('wp_enqueue_scripts', 'streamium_single_video_scripts');

/**
 * Ajax post scipts for content
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_dynamic_content() {

	global $wpdb;

	// Get params
	$cat = $_REQUEST['cat'];
	$postId = $_REQUEST['post_id'];
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'streamium_likes_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$post_object = get_post( $postId );

    	if(!empty($post_object)){

    		$like_text = '';
		    if ( get_theme_mod( 'streamium_enable_premium' ) ) {
		        $nonce = wp_create_nonce( 'streamium_likes_nonce' );
		    	$link = admin_url('admin-ajax.php?action=streamium_likes&post_id='. $postId .'&nonce='.$nonce);
		        $like_text = '<div class="synopis-premium-meta hidden-xs">
								<div class="streamium-review-like-btn">
			                        <a class="like-button"  data-id="' . $postId . '" data-nonce="' . $nonce . '">' . __( 'Like it' ) . '</a>
			                        <span id="like-count-' . $postId . '" class="like-count">' . get_streamium_likes($postId) . '</span>
			                    </div>
			                    <div class="streamium-review-reviews-btn">
			                        <a class="streamium-list-reviews" data-id="' . $postId . '" data-nonce="' . $nonce . '">Read reviews</a>
			                    </div>
							</div>';
		    }
    		$content = (isMobile()) ? (empty($post_object->post_excerpt) ? strip_tags($post_object->post_content) : $post_object->post_excerpt) : $post_object->post_content . $like_text;
	    	$fullImage  = wp_get_attachment_image_src( get_post_thumbnail_id( $postId ), 'streamium-home-slider' ); 
	    	$streamiumVideoTrailer = get_post_meta( $postId, 'streamium_video_trailer_meta_box_text', true );

	    	echo json_encode(
		    	array(
		    		'error' => false,
		    		'cat' => $cat,
		    		'title' => $post_object->post_title,
		    		'content' => $content,
		    		'bgimage' =>  $fullImage[0],
		    		'trailer' => $streamiumVideoTrailer,
		    		'href' => get_permalink($postId)
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
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

    	global $post;

    	$nonce = wp_create_nonce( 'single_nonce' );
    	$s3videoid = get_post_meta( $post->ID, 's3bubble_video_code_meta_box_text', true );
    	$streamiumVideoTrailer = get_post_meta( $post->ID, 'streamium_video_trailer_meta_box_text', true );
    	
    	if(is_user_logged_in()){
    		$userId = get_current_user_id();
    		$percentageWatched = get_post_meta( $post->ID, 'user_' . $userId, true );
    	}

    	if ( get_theme_mod( 'streamium_enable_premium' ) ) {

    		if(pathinfo($s3videoid, PATHINFO_DIRNAME) !== "."){
			    $s3videoid = pathinfo($s3videoid, PATHINFO_BASENAME);
			}

			$codes = [];
			$episodes = get_post_meta(get_the_ID(), 'repeatable_fields' , true);
			if(!empty($episodes)) {
				foreach ($episodes as $key => $value) : 
					$codes[] = $value['codes'];
				endforeach;
			}else{
				$codes = [$s3videoid];
			}

    		// Setup premium
	        wp_localize_script( 'streamium-production', 'video_post_object', 
	            array( 
	                'ajax_url' => admin_url( 'admin-ajax.php'),
	                'post_id' => $post->ID,
	                'percentage' => !empty($percentageWatched) ? $percentageWatched : 0,
	                'codes' => isset($_GET['trailer']) ? $streamiumVideoTrailer : $codes,
	                'trailer' => isset($streamiumVideoTrailer) ? $streamiumVideoTrailer : "",
	                'nonce' => $nonce
	            )
	        ); 

        }else{

        	//setup standard
	        wp_localize_script( 'streamium-production', 'video_post_object', 
	            array( 
	                'ajax_url' => admin_url( 'admin-ajax.php'),
	                'post_id' => $post->ID,
	                'code' => (!empty( $s3videoid ) && filter_var($s3videoid, FILTER_VALIDATE_URL)) ? $s3videoid : NULL
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
	$postId = (int) $_REQUEST['post_id'];
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'streamium_likes_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$post_object = get_post( $postId );

    	if(!empty($post_object)){

    		$like_text = '';
		    if ( get_theme_mod( 'streamium_enable_premium' ) ) {

		    	$buildMeta = '<ul>';

				// Tags
				$posttags = get_the_tags($postId);
				$staring = 'Staring: ';
				if ($posttags) {
					$numItems = count($posttags);
					$i = 0;
				  	foreach($posttags as $tag) {

					  	$staring .= '<a href="/?s=' . esc_html( $tag->name ) . '">' . $tag->name . '</a>';
					  	if(++$i !== $numItems) {
				    		$staring .= ', ';
				  		}

				    }
				    $buildMeta .= '<li class="synopis-meta-spacer">' . $staring . '</li>';
				}
				
				// Cats
				$categories = get_the_category($postId);
				$genres = 'Genres: ';
				if ($categories) {
					$numItems = count($categories);
					$g = 0;
				  	foreach($categories as $cats) {

				  		$genres .= '<a href="' . esc_url( get_category_link( $cats->term_id ) ) . '">' . $cats->name . '</a>';
				  		if(++$g !== $numItems) {
				    		$genres .= ', ';
				  		}

				  	}
				  	$buildMeta .= '<li class="synopis-meta-spacer">' . $genres . '</li>';
				}

				// Release date
				$buildMeta .= '<li class="synopis-meta-spacer">Released: <a href="/?s=all&date=' . get_the_date('Y/m/d', $postId) . '">' . get_the_date('l, F j, Y', $postId) . '</a></li></ul>';

				// Likes and reviews
		        $nonce = wp_create_nonce( 'streamium_likes_nonce' );
		    	$link = admin_url('admin-ajax.php?action=streamium_likes&post_id='. $postId .'&nonce='.$nonce);

		    	

		        $like_text = '<div class="synopis-premium-meta hidden-xs">
		        				<a id="like-count-' . $postId . '" class="streamium-review-like-btn streamium-btns streamium-reviews-btns" data-toggle="tooltip" title="CLICK TO LIKE!" data-id="' . $postId . '" data-nonce="' . $nonce . '">' . get_streamium_likes($postId) . '</a>
		        				<a class="streamium-list-reviews streamium-btns streamium-reviews-btns" data-id="' . $postId . '" data-nonce="' . $nonce . '">Read reviews</a>
							</div>';

		    }

		    $content = $post_object->post_content . $buildMeta . $like_text;
		    if(isMobile()){
		    	$content = (empty($post_object->post_excerpt) ? strip_tags($post_object->post_content) : $post_object->post_excerpt);
		    }
	    	$fullImage  = wp_get_attachment_image_src( get_post_thumbnail_id( $postId ), 'streamium-home-slider' ); 
	    	$streamiumVideoTrailer = get_post_meta( $postId, 'streamium_video_trailer_meta_box_text', true );

	    	echo json_encode(
		    	array(
		    		'error' => false,
		    		'cat' => $cat,
		    		'title' => $post_object->post_title,
		    		'content' => $content,
		    		'bgimage' =>  isset($fullImage) ? $fullImage[0] : "",
		    		'trailer' => $streamiumVideoTrailer,
		    		'href' => get_permalink($postId),
		    		'post' => $post_object
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

/**
 * Ajax post scipts for content
 *
 * @return bool
 * @author  @sameast
 */
function streamium_programs_get_dynamic_content() {

	global $wpdb;

	// Get params
	$cat = $_REQUEST['cat'];
	$termId =  (int) $_REQUEST['term_id'];
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'streamium_likes_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$post_object = get_term( $termId, 'tv' );

    	if(!empty($post_object)){

    		$like_text = '';
		    if ( get_theme_mod( 'streamium_enable_premium' ) ) {
		        $nonce = wp_create_nonce( 'streamium_likes_nonce' );
		    	$link = admin_url('admin-ajax.php?action=streamium_likes&post_id='. $termId .'&nonce='.$nonce);
		        $like_text = '<div class="synopis-premium-meta hidden-xs">
								<div class="streamium-review-like-btn">
			                        <a class="like-button"  data-id="' . $termId . '" data-nonce="' . $nonce . '">' . __( 'Like it' ) . '</a>
			                        <span id="like-count-' . $termId . '" class="like-count">' . get_streamium_likes($termId) . '</span>
			                    </div>
			                    <div class="streamium-review-reviews-btn">
			                        <a class="streamium-list-reviews" data-id="' . $termId . '" data-nonce="' . $nonce . '">Read reviews</a>
			                    </div>
							</div>';
		    }
    		$content = (isMobile()) ? strip_tags($post_object->description) : $post_object->description . $like_text;
	    	$fullImage  =get_tax_meta($termId,'streamium_program_image'); 
	    	//$streamiumVideoTrailer = get_post_meta( $termId, 'streamium_video_trailer_meta_box_text', true );

	    	echo json_encode(
		    	array(
		    		'error' => false,
		    		'cat' => $cat,
		    		'title' => $post_object->name,
		    		'content' => $content,
		    		'bgimage' =>  $fullImage[0]['url'],
		    		'trailer' => '',//$streamiumVideoTrailer,
		    		'href' => get_term_link( $termId )
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

add_action( 'wp_ajax_nopriv_streamium_programs_get_dynamic_content', 'streamium_programs_get_dynamic_content' );
add_action( 'wp_ajax_streamium_programs_get_dynamic_content', 'streamium_programs_get_dynamic_content' );
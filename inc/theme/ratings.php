<?php
 
global $streamium_reviews_db_version;
$streamium_reviews_db_version = '1.0';

function streamium_reviews_db_install() {

	global $wpdb;
	global $streamium_reviews_db_version;

	$table_name = $wpdb->prefix . 'streamium_reviews';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		user_id int NOT NULL,
		post_id int NOT NULL,
		message text NOT NULL,
		state boolean NOT NULL DEFAULT 0,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'streamium_reviews_db_version', $streamium_reviews_db_version );

}

add_action("after_switch_theme", "streamium_reviews_db_install");

function like_it_button_html( $content ) {
    $like_text = '';
    if ( is_singular() ) {
        $nonce = wp_create_nonce( 'pt_like_it_nonce' );
        $link = admin_url('admin-ajax.php?action=pt_like_it&post_id='.get_the_ID().'&nonce='.$nonce);
        $likes = get_post_meta( get_the_ID(), '_pt_likes', true );
        $likes = ( empty( $likes ) ) ? 0 : $likes;
        $like_text = '
                    <div class="pt-like-it">
                        <a class="like-button" href="'.$link.'" data-id="' . get_the_ID() . '" data-nonce="' . $nonce . '">' . 
                        __( 'Like it' ) .
                        '</a>
                        <span id="like-count-'.get_the_ID().'" class="like-count">' . $likes . '</span>
                    </div>';
    }
    return $content . $like_text;
}

//add_filter( 'the_content', 'like_it_button_html', 99 );

function checkIfArrayKeyValueExists($array, $key, $val) {
    foreach ($array as $item)
        if (isset($item[$key]) && $item[$key] == $val)
            return true;
    return false;
}

function pt_like_it() {

	global $wpdb;

	// Get params
	$userId = get_current_user_id();
	$postId = $_REQUEST['post_id'];
	$message = $_REQUEST['message'];
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'pt_like_it_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
        exit( "No naughty business please" );
    }

    // Check if user is logged in
    if ( !is_user_logged_in() ) {

    	echo json_encode(
	    	array(
	    		'error' => true,
	    		'message' => 'You must be logged in to like or dislike' 
	    	)
	    );

	    die();

    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$errors = false;
    	$errorMessage = "";

    	$table_name = $wpdb->prefix . 'streamium_reviews';

    	$checkIfExists = $wpdb->get_row( "SELECT * FROM $table_name WHERE user_id = $userId AND post_id = $postId", ARRAY_N );
    	if ( null !== $checkIfExists ) {

    		$errors = true;
    		$errorMessage = "You have already reviewed this video";

    	}else{

    		$wpdb->insert( 
				$table_name, 
				array( 
					'user_id' => $userId, 
					'post_id' => $postId,
					'message' => $message,
					'time' => current_time('mysql', 1)    
				), 
				array(
					'%d',
					'%d', 
					'%s', 
					'%s' 
				) 
			);
    	}

    	if($errors){

    		echo json_encode(
		    	array(
		    		'error' => true,
		    		'message' => $errorMessage
		    	)
		    );

		}else{

			$likes = get_post_meta( $_REQUEST['post_id'], '_pt_likes', true );
		    $likes = ( empty( $likes ) ) ? 0 : $likes;
		    $new_likes = $likes + 1;
		    update_post_meta( $_REQUEST['post_id'], '_pt_likes', $new_likes );

			echo json_encode(
		    	array(
		    		'error' => false,
		    		'likes' => $new_likes,
		    		'message' => 'Successfully added your rating' 
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

add_action( 'wp_ajax_nopriv_pt_like_it', 'pt_like_it' );
add_action( 'wp_ajax_pt_like_it', 'pt_like_it' );

function time2str($ts)
{
    if(!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = time() - $ts;
    if($diff == 0)
        return 'now';
    elseif($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) return 'Yesterday';
        if($day_diff < 7) return $day_diff . ' days ago';
        if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'last month';
        return date('F Y', $ts);
    }
    else
    {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 120) return 'in a minute';
            if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if($diff < 7200) return 'in an hour';
            if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
        }
        if($day_diff == 1) return 'Tomorrow';
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'next week';
        if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
        if(date('n', $ts) == date('n') + 1) return 'next month';
        return date('F Y', $ts);
    }
}

function streamium_get_reviews() {

	global $wpdb;

	// Get params
	$postId = $_REQUEST['post_id'];
 
    if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'pt_like_it_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
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

    	$table_name = $wpdb->prefix . 'streamium_reviews';

    	$getReviews = $wpdb->get_results( 
			"
			SELECT *
			FROM $table_name
			WHERE post_id = $postId 
			"
		);

		if ( $getReviews )
		{

			$buildGetReviews = [];
			foreach ( $getReviews as $post )
			{
				
				$userInfo = get_userdata($post->user_id);
				array_push($buildGetReviews, array(
		    		'username' => $userInfo->user_login,
		    		'avatar' => get_avatar_url($post->user_id, array( "size" => 64 ) ),
		    		'post_id' => $post->post_id,
		    		'message' => $post->message,
		    		'time' => time2str($post->time)
		    	));

			}

			echo json_encode(
		    	array(
		    		'error' => false,
		    		'title' => get_the_title($postId),
		    		'data' => $buildGetReviews,
		    		'message' => 'Successfully added your rating' 
		    	)
		    );

		}
		else
		{
			
			echo json_encode(
		    	array(
		    		'error' => true,
		    		'message' => 'No reviews' 
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

add_action( 'wp_ajax_nopriv_streamium_get_reviews', 'streamium_get_reviews' );
add_action( 'wp_ajax_streamium_get_reviews', 'streamium_get_reviews' );

function pt_like_it_scripts() {
    if( !is_single() ) {
 
        wp_enqueue_style( 'like-it', get_template_directory_uri() . '/dist/css/like-it.min.css' );
    	wp_enqueue_script( 'like-it', get_template_directory_uri() . '/dist/js/like-it.min.js', array('jquery'), '1.0', true );

    }
}

add_action( 'wp_enqueue_scripts', 'pt_like_it_scripts' );
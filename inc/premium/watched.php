<?php

/**
 * Add the database table
 *
 * @return bool
 * @author  @sameast
 */

global $streamium_watched_db_version;
$streamium_watched_db_version = '1.0';

function streamium_watched_db_install() {

	global $wpdb;
	global $streamium_watched_db_version;

	$table_name = $wpdb->prefix . 'watched';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		user_id int(11) NOT NULL,
	   	profile_id int(11) NOT NULL,
	   	post_id varchar(50) NOT NULL,
	   	code varchar(50) NOT NULL,
	   	stream_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	   	stream_time int(11) NOT NULL,
	   	stream_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'streamium_watched_db_version', $streamium_watched_db_version );
}

add_action("after_switch_theme", "streamium_watched_db_install");

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function streamium_watched_add_stream($post_id, $code, $stream_date, $stream_time) {

	
	if(!is_user_logged_in()){
		return false;
	}

	// GET THE USER PROFILE
	global $wpdb;

	// PARAMS::
	$user_id    = get_current_user_id();
	$profile_id = 0;
	if(get_user_meta(get_current_user_id(), 'user_profile', true)){
		$profile_id = get_user_meta(get_current_user_id(), 'user_profile', true);
	}

	$table  = $wpdb->prefix . 'watched';
	$stream = $wpdb->get_row( "SELECT * FROM $table WHERE post_id = " . $post_id . " AND profile_id = " . $profile_id . " AND user_id = " . $user_id, ARRAY_N );

	if ( null !== $stream ) {

		$wpdb->update( 
			$table, 
			array( 
				'code'        => $code,
				'stream_date' => $stream_date,
				'stream_time' => $stream_time
			), 
			array( 'post_id' => $post_id ), 
			array( 
				'%s',
				'%s',
				'%d'	
			), 
			array( '%d' ) 
		);

	}else{

		$wpdb->insert( 
			$table, 
			array( 
				'user_id'     => $user_id, 
				'profile_id'  => $profile_id,
				'post_id'     => $post_id,
				'code'        => $code,
				'stream_date' => $stream_date,
				'stream_time' => $stream_time
			), 
			array(
				'%d',
				'%d',
				'%d', 
				'%s',
				'%s', 
				'%d' 
			) 
		);

	}

	return true;

}

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function streamium_watched_get_streams() {

	if(!is_user_logged_in()){
		return false;
	}

	// GET THE USER PROFILE
	global $wpdb;

	// PARAMS::
	$user_id = get_current_user_id();

	$table      = $wpdb->prefix . 'watched';
	$profile_id = 0;
	if(get_user_meta(get_current_user_id(), 'user_profile', true)){
		$profile_id = get_user_meta(get_current_user_id(), 'user_profile', true);
	}
	$streams    = $wpdb->get_results( "SELECT * FROM $table WHERE user_id = " . $user_id . " AND profile_id = " . $profile_id . " ORDER BY stream_date DESC", OBJECT );

	if ( null !== $streams ) {

		$ids = [];
		foreach ( $streams as $stream ){
			$ids[] = $stream->post_id;
		}

		return $ids;

	}else{

		return false;

	}

}

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function streamium_watched_get_stream_percentage($post_id) {

	if(!is_user_logged_in()){
		return false;
	}

	// GET THE USER PROFILE
	global $wpdb;

	// PARAMS::
	$user_id = get_current_user_id();

	$table  = $wpdb->prefix . 'watched';
	$stream = $wpdb->get_row( "SELECT * FROM $table WHERE post_id = " . $post_id . " AND user_id = " . $user_id );

	if ( null !== $stream ) {

		return $stream->stream_time;

	}else{

		return 0;

	}

}

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function streamium_watched_update_stream() {

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

    	wp_send_json(array(
    		'error' => true,
    		'message' => 'You must be logged in to view reviews' 
    	));

	    die();

    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	streamium_watched_add_stream(
		    $postId,
		    get_post_meta( $postId, 'streamium_meta_box_movie_code', true ),
		    current_time('mysql'),
		    $percentage
		);

		wp_send_json(array(
            'error' => false,
    		'percentage' => $percentage,
    		'message' => 'Successfully added user resume' 
        ));

        die();

    }
    else {
        
        wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
        exit();

    }

}

add_action( 'wp_ajax_streamium_watched_update_stream', 'streamium_watched_update_stream' );

//////////////////////////////////// MEDIA VIEW COUNT ///////////////////////////////////

/**
 * Update the post media view count
 *
 * @return bool
 * @author  @sameast
 */
function set_media_views($post_id) {

    $count = get_post_meta($post_id, 'media_views_count', true);
    if($count === ''){
    
        $count = 0;
        delete_post_meta($post_id, 'media_views_count');
        add_post_meta($post_id, 'media_views_count', 0);
    
    }else{

        $count++;
        update_post_meta($post_id, 'media_views_count', $count);
    
    }

}
<?php

/**
 * Add the database table
 *
 * @return bool
 * @author  @sameast
 */

global $streamium_user_profiles_db_version;
$streamium_user_profiles_db_version = '1.0';

function streamium_user_profiles_db_install() {

	global $wpdb;
	global $streamium_user_profiles_db_version;

	$table_name = $wpdb->prefix . 'profiles';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		user_id int(11) NOT NULL,
	    profile varchar(250) NOT NULL,
	    avatar varchar(50) NOT NULL,
	    profile_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id)
	) $charset_collate;"; 

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'streamium_user_profiles_db_version', $streamium_user_profiles_db_version );
}

add_action("after_switch_theme", "streamium_user_profiles_db_install");

/**
 * Get the user profiles from database
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_user_profiles() {

	if(!is_user_logged_in()){
		return false;
	}

	// GET THE USER PROFILE
	global $wpdb;

	// PARAMS::
	$user_id = get_current_user_id();

	$table  = $wpdb->prefix . 'profiles';
	$profiles = $wpdb->get_results( "SELECT * FROM $table WHERE user_id = " . $user_id, OBJECT );

	if ( null !== $profiles ) {

		return $profiles;

	}else{

		return false;

	}

}


/**
 * Get the user profiles from database
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_current_user_profile() {

	if(!is_user_logged_in()){
		return false;
	}

	// GET THE USER PROFILE
	global $wpdb;

	// PARAMS::
	$user_id = get_current_user_id();

	$table      = $wpdb->prefix . 'profiles';
	$profile_id = get_user_meta($user_id, 'user_profile', true);
	$profiles   = $wpdb->get_row( "SELECT * FROM $table WHERE user_id = " . $user_id . " AND id = " . $profile_id );

	if ( null !== $profiles ) {

		return $profiles;

	}else{

		return false;

	}

}

/**
 * Get the user profiles from database
 *
 * @return bool
 * @author  @sameast
 */
function streamium_add_user_profile() {

	global $wpdb;

	// PARAMS::
	$user_id = get_current_user_id();
	$profile = $_REQUEST['profile'];

	if(empty($profile)){

		wp_send_json(array(
    		'error' => true,
    		'message' => __( 'Please enter a profile name', 'streamium' ) 
    	));

	}

	// NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );
 	
    // Check if user is logged in
    if ( !is_user_logged_in() ) {

    	wp_send_json(array(
    		'error' => true,
    		'message' => __( 'You must be logged in to create a profile', 'streamium' ) 
    	));

	    die();

    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$table  = $wpdb->prefix . 'profiles';
		$profiles = $wpdb->get_row( "SELECT * FROM $table WHERE profile = '" . $profile . " AND user_id = " . $user_id, ARRAY_N );

		if ( null !== $profiles ) {

			wp_send_json(array(
	            'error' => true,
		    	'message' => 'This profie name already exists please choose another one'
	        ));

		}else{

			$avatars = array('retro','monsterid','wavatar','mystery');
			$wpdb->insert( 
				$table, 
				array( 
					'user_id' => $user_id, 
					'profile' => $profile,
					'avatar'  => $avatars[array_rand($avatars)]
				), 
				array(
					'%d',
					'%s',
					'%s'
				) 
			);

			wp_send_json(array(
	            'error' => false,
		    	'message' => 'Success' 
	        ));

		}

        die();

    }
    else {
        
        exit( "No naughty business please" );

    }

	return true;

}

add_action( 'wp_ajax_streamium_add_user_profile', 'streamium_add_user_profile' );

/**
 * Get the user profiles from database
 *
 * @return bool
 * @author  @sameast
 */
function streamium_delete_user_profile() {

	global $wpdb;

	// PARAMS::
	$user_id = get_current_user_id();
	$id      = $_REQUEST['id'];

	if(empty($id)){

		wp_send_json(array(
    		'error' => true,
    		'message' => __( 'Error', 'streamium' ) 
    	));

	}

	// NONCE SECURITY:
    check_ajax_referer( 'wp_rest', 'security' );
 	
    // Check if user is logged in
    if ( !is_user_logged_in() ) {

    	wp_send_json(array(
    		'error' => true,
    		'message' => __( 'You must be logged in to create a profile', 'streamium' ) 
    	));

	    die();

    }

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

    	$table  = $wpdb->prefix . 'profiles';
		$profiles = $wpdb->delete( $table, array( 
			'user_id' => $user_id, 
			'id' => $id, 
		) );

		if ( null !== $profiles ) {

			wp_send_json(array(
	            'error' => false,
		    	'message' => 'Success' 
	        ));

		}else{

			wp_send_json(array(
	            'error' => true,
		    	'message' => 'This profie name already exists please choose another one'
	        ));

		}

        die();

    }
    else {
        
        exit( "No naughty business please" );

    }

	return true;

}

add_action( 'wp_ajax_streamium_delete_user_profile', 'streamium_delete_user_profile' );
<?php

/**
 * Ajax post scipts for content
 *
 * @return bool
 * @author  @sameast
 */
function streamium_get_dynamic_series_content() {

	global $wpdb;

	// Get params
	$postId = (int) $_REQUEST['postId'];

	$episodes = get_post_meta($postId, 'repeatable_fields' , true);

	if(empty($episodes)){

		echo json_encode(
	    	array(
	    		'error' => true,
	    		'message' => 'No series found.'
	    	)
	    );

	    die();

	}
	
	// Order the list
	$positions = array();
	foreach ($episodes as $key => $row){
	    $positions[$key] = $row['positions'];
	}
	array_multisort($positions, SORT_ASC, $episodes);

	// Sort the seasons
	$result = array();
	foreach ($episodes as $v) {
	    $seasons = $v['seasons'];
	    if (!isset($result[$seasons])) $result[$seasons] = array();
	    $v['link'] = get_permalink($postId);
	    $result[$seasons][] = $v;
	}

	echo json_encode(
    	array(
    		'error' => false,
    		'id' => $postId,
    		'data' => $result,
    		'message' => 'We could not find this post.'
    	)
    );

    die();

}

add_action( 'wp_ajax_nopriv_streamium_get_dynamic_series_content', 'streamium_get_dynamic_series_content' );
add_action( 'wp_ajax_streamium_get_dynamic_series_content', 'streamium_get_dynamic_series_content' );

/**
 * Ajax remove series from list
 *
 * @return bool
 * @author  @sameast
 */
function streamium_admin_series_remove_video() {

	global $wpdb;

	// Get params
	$postId = (int) $_REQUEST['postId'];
	$index  = (int) $_REQUEST['index'];	

	$data = get_post_meta($postId, 'repeatable_fields', true);

	// Delete current meta data
	delete_post_meta( $postId, 'repeatable_fields', $data );

	// Delete index
	unset($data[$index]);

	update_post_meta( $postId, 'repeatable_fields', $data );

	// Return sucessfully state
	echo json_encode(
    	array(
    		'error' => false,
    		'message' => 'Successfully remove series video.'
    	)
    );

    die();

}

add_action( 'wp_ajax_streamium_admin_series_remove_video', 'streamium_admin_series_remove_video' );
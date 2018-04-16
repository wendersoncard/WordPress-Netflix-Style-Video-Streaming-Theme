<?php

/**
 * Setup custom post type for TV Programs
 *
 * @return null
 * @author  @sameast
 */
function streamium_custom_post_types() {


	$postTypes = array(
				array(
                    'tax' => 'movies',
                    'type' => 'movie',
                    'menu' => 'Movies'
                ),
                array(
                    'tax' => 'programs',
                    'type' => 'tv',
                    'menu' => 'TV Programs'
                ),
                array(
                    'tax' => 'sports',
                    'type' => 'sport',
                    'menu' => 'Sport'
                ),
                array(
                    'tax' => 'kids',
                    'type' => 'kid',
                    'menu' => 'Kids'
                ),
                array(
                    'tax' => 'streams',
                    'type' => 'stream',
                    'menu' => 'Streams'
                )
            );

	foreach ($postTypes as $key => $value) {

		// Define additional "post thumbnails". Relies on MultiPostThumbnails to work
		if (class_exists('MultiPostThumbnails')) {
		    new MultiPostThumbnails(array(
		        'label' => 'Tile Expanded',
		        'id' => 'tile-expanded-image',
		        'post_type' => $value['type']
		        )
		    );
		    new MultiPostThumbnails(array(
		        'label' => 'Large Landscape',
		        'id' => 'large-landscape-image',
		        'post_type' => $value['type']
		        )
		    );

		    new MultiPostThumbnails(array(
		        'label' => 'Roku Thumbnail 16:9 at least (800x450)',
		        'id' => 'roku-thumbnail-image',
		        'post_type' => $value['type']
		        )
		    );     
		 
		};

    	// Check for modifications
    	$rewriteTax = get_theme_mod( 'streamium_section_input_taxonomy_' . $value['tax'], $value['tax'] );
    	$rewriteType = get_theme_mod( 'streamium_section_input_posttype_' . $value['type'], $value['type'] );
    	$menuLabel = get_theme_mod( 'streamium_section_input_menu_text_' . $value['type'], $value['menu'] );

    	// TV PROGRAM
		$setupTax = array(
	    	'hierarchical' => true,
	    	'labels' => array(
	      	'name' => __( ucfirst($rewriteTax) . ' Categories', 'streamium' ),
	    ),
	    	'rewrite' => array(
	      		'slug' => $rewriteTax, 
	      		'with_front' => false, 
	      		'hierarchical' => true 
	    	),
	  	);

	  	$setupArgs = array(
			'labels'              => array(
				'name'                => __( ucfirst($menuLabel), 'streamium' ),
			),
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'menu_icon'           => 'https://s3.amazonaws.com/s3bubble-cdn/theme-images/s3bubblelogo.png',
			'menu_position'       => 5,
			'taxonomies'          => array(  'post_tag', $value['tax'] ),
			//'supports'			  => array('title', 'editor', 'thumbnail', 'excerpt'),
			'rewrite'			  => array(
										'slug' => $rewriteType, 
										'with_front' => false
									),
		);

		// Registering your Custom Post Type
		register_taxonomy( $value['tax'], $value['type'], $setupTax); 
		register_post_type( $value['type'], $setupArgs );
		
  	}	

}

add_action( 'init', 'streamium_custom_post_types', 0 );

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
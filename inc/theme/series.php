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
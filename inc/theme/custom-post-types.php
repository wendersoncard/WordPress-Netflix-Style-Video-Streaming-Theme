<?php

/**
 * Sets up the custom post types for (movies|programs|sports|kids|streams)
 * These can be edited and changed in the theme customizer in the dashboard
 *
 * @return null
 * @author  @sameast
 */
function streamium_custom_post_types() {

	// TAXONOMIES ARRAY EXTRA ONES CAN SIMPLY BE ADDED HERE:
	foreach (streamium_global_post_types() as $key => $value) {

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

    	// THE USER CAN MODIFY THE NAME IN THE CUSTOMIZER SETTINGS:
    	$rewriteTax = get_theme_mod( 'streamium_section_input_taxonomy_' . $value['tax'], $value['tax'] );
    	$rewriteType = get_theme_mod( 'streamium_section_input_posttype_' . $value['type'], $value['type'] );
    	$menuLabel = get_theme_mod( 'streamium_section_input_menu_text_' . $value['type'], $value['menu'] );

    	// REGISTER TAXONOMIES:
		$taxonomyLabels = array(
	  		'name'              => __( $rewriteTax, 'streamium' ),
	  		'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
	  		'search_items'      => __( 'Search Categorys' ),
	  		'all_items'         => __( 'All Categorys' ),
	  		'parent_item'       => __( 'Parent Category' ),
	  		'parent_item_colon' => __( 'Parent Category:' ),
	  		'edit_item'         => __( 'Edit Category' ),
	  		'update_item'       => __( 'Update Category' ),
	  		'add_new_item'      => __( 'Add New Category' ),
	  		'new_item_name'     => __( 'New Genre Name' ),
	  		'menu_name'         => __( 'Categories' ),
	  	);
	  
	  	$taxonomyArgs = array(
	  		'hierarchical'      => true,
	  		'labels'            => $taxonomyLabels,
	  		'show_ui'           => true,
	  		'show_admin_column' => true,
	  		'query_var'         => true,
	  		'rewrite'           => array( 'slug' => $rewriteTax ),
	  		'show_in_rest'      => true
	  	);
	  	
	  	register_taxonomy( $value['tax'], $value['type'], $taxonomyArgs); 

	  	// REGISTER POST TYPES:
	  	$postTypeLabels = array(
	  		'name'               => _x( $menuLabel, 'post type general name', 'streamium' ),
	  		'singular_name'      => _x( $menuLabel, 'post type singular name', 'streamium' ),
	  		'menu_name'          => _x( $menuLabel, 'admin menu', 'streamium' ),
	  		'name_admin_bar'     => _x( $menuLabel, 'add new on admin bar', 'streamium' ),
	  		'add_new'            => _x( 'Add New', $menuLabel, 'streamium' ),
	  		'add_new_item'       => __( 'Add New ' . $menuLabel, 'streamium' ),
	  		'new_item'           => __( 'New ' . $menuLabel, 'streamium' ),
	  		'edit_item'          => __( 'Edit ' . $menuLabel, 'streamium' ),
	  		'view_item'          => __( 'View ' . $menuLabel, 'streamium' ),
	  		'all_items'          => __( 'All ' . $menuLabel, 'streamium' ),
	  		'search_items'       => __( 'Search ' . $menuLabel, 'streamium' ),
	  		'parent_item_colon'  => __( 'Parent :' . $menuLabel, 'streamium' ),
	  		'not_found'          => __( 'No ' . $menuLabel . ' found.', 'streamium' ),
	  		'not_found_in_trash' => __( 'No ' . $menuLabel . ' found in Trash.', 'streamium' )
	  	);

	  	$postTypeArgs = array(
			'labels'              => $postTypeLabels,
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
			'show_in_rest'        => true,
			'rewrite'			  => array(
										'slug' => $rewriteType, 
										'with_front' => false
									),
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields')
		);

		register_post_type( $value['type'], $postTypeArgs );
		
  	}	

}

add_action( 'init', 'streamium_custom_post_types', 0 );
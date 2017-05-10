<?php

/**
 * Setup custom post type for TV Programs
 *
 * @return null
 * @author  @sameast
 */
function streamium_custom_post_types() {

	$tax = (get_theme_mod( 'streamium_section_input_taxonomy_programs' ) ? get_theme_mod( 'streamium_section_input_taxonomy_programs' ) : 'programs');
	$type = (get_theme_mod( 'streamium_section_input_posttype_tv' ) ? get_theme_mod( 'streamium_section_input_posttype_tv' ) : 'tv');
	$menuText = (get_theme_mod( 'streamium_section_input_menu_text_tv' ) ? get_theme_mod( 'streamium_section_input_menu_text_tv' ) : 'TV Programs');

	// TV PROGRAM
	$tvTax = array(
    	'hierarchical' => true,
    	'labels' => array(
      	'name' => __( $menuText, 'streamium' ),
    ),
    	'rewrite' => array(
      		'slug' => $tax, 
      		'with_front' => false, 
      		'hierarchical' => true 
    	),
  	);
  	
	
	$tvArgs = array(
		'labels'              => array(
			'name'                => __( ucfirst($type), 'streamium' ),
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
		'taxonomies'          => array(  'post_tag', 'programs' ),
		'rewrite' => array('slug' => $type,'with_front' => false),
	);
	
	/* SPORTS */
	$taxSport = (get_theme_mod( 'streamium_section_input_taxonomy_sports' ) ? get_theme_mod( 'streamium_section_input_taxonomy_sports' ) : 'sports');
	$typeSport = (get_theme_mod( 'streamium_section_input_posttype_sport' ) ? get_theme_mod( 'streamium_section_input_posttype_sport' ) : 'sport');
	$menuTextSport = (get_theme_mod( 'streamium_section_input_menu_text_sport' ) ? get_theme_mod( 'streamium_section_input_menu_text_sport' ) : 'Sport');

	$sportTax = array(
    	'hierarchical' => true,
    	'labels' => array(
      	'name' => __( $menuTextSport, 'streamium' ),
    ),
    	'rewrite' => array(
      		'slug' => $taxSport, 
      		'with_front' => false, 
      		'hierarchical' => true 
    	),
  	);

	$sportArgs = array(
		'labels'              => array(
			'name'                => __( ucfirst($typeSport), 'streamium' ),
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
		'taxonomies'          => array(  'post_tag', 'sports' ),
		'rewrite' => array('slug' => $typeSport,'with_front' => false),
	);

	/* KIDS */
	$taxKids = (get_theme_mod( 'streamium_section_input_taxonomy_kids' ) ? get_theme_mod( 'streamium_section_input_taxonomy_kids' ) : 'kids');
	$typeKid = (get_theme_mod( 'streamium_section_input_posttype_kid' ) ? get_theme_mod( 'streamium_section_input_posttype_kid' ) : 'kid');
	$menuTextKids = (get_theme_mod( 'streamium_section_input_menu_text_kid' ) ? get_theme_mod( 'streamium_section_input_menu_text_kid' ) : 'Kids');

	$kidTax = array(
    	'hierarchical' => true,
    	'labels' => array(
      	'name' => __( $menuTextKids, 'streamium' ),
    ),
    	'rewrite' => array(
      		'slug' => $taxKids, 
      		'with_front' => false, 
      		'hierarchical' => true 
    	),
  	);
	
	$kidArgs = array(
		'labels'              => array(
			'name'                => __( ucfirst($typeKid), 'streamium' ),
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
		'taxonomies'          => array(  'post_tag', 'kids' ),
		'rewrite' => array('slug' => $typeKid,'with_front' => false),
	);

	/* LIVE */
	$taxStreams = (get_theme_mod( 'streamium_section_input_taxonomy_streams' ) ? get_theme_mod( 'streamium_section_input_taxonomy_streams' ) : 'streams');
	$typeStream = (get_theme_mod( 'streamium_stream_section_input_posttype' ) ? get_theme_mod( 'streamium_stream_section_input_posttype' ) : 'stream');
	$menuTextStreams = (get_theme_mod( 'streamium_section_input_menu_text_stream' ) ? get_theme_mod( 'streamium_section_input_menu_text_stream' ) : 'Streams');

	$streamTax = array(
    	'hierarchical' => true,
    	'labels' => array(
      	'name' => __( $menuTextStreams, 'streamium' ),
    ),
    	'rewrite' => array(
      		'slug' => $taxStreams, 
      		'with_front' => false, 
      		'hierarchical' => true 
    	),
  	);

	$streamArgs = array(
		'labels'              => array(
			'name'                => __( ucfirst($typeStream), 'streamium' ),
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
		'taxonomies'          => array(  'post_tag', 'streams' ),
		'rewrite' => array('slug' => $typeStream,'with_front' => false),
	);
	
	// Registering your Custom Post Type
	register_taxonomy('programs', 'tv', $tvTax); 
	register_post_type( 'tv', $tvArgs );
	register_taxonomy('kids', 'kid', $kidTax);
	register_post_type( 'kid', $kidArgs );
	register_taxonomy('sports', 'sport', $sportTax);
	register_post_type( 'sport', $sportArgs );
	register_taxonomy('streams', 'stream', $streamTax);
	register_post_type( 'stream', $streamArgs );

}

add_action( 'init', 'streamium_custom_post_types', 0 );
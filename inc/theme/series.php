<?php

/**
 * Setup custom post type for TV Programs
 *
 * @return null
 * @author  @sameast
 */
function streamium_custom_post_types() {

	$tax = (get_theme_mod( 'streamium_tv_section_input_taxonomy' ) ? get_theme_mod( 'streamium_tv_section_input_taxonomy' ) : 'programs');
	$type = (get_theme_mod( 'streamium_tv_section_input_posttype' ) ? get_theme_mod( 'streamium_tv_section_input_posttype' ) : 'tv');
	$menuText = (get_theme_mod( 'streamium_tv_section_input_menu_text' ) ? get_theme_mod( 'streamium_tv_section_input_menu_text' ) : 'TV Programs');

	// TV PROGRAMS
  	register_taxonomy('programs', 'tv', array(
    	'hierarchical' => true,
    	'labels' => array(
      	'name' => __( $menuText, 'streamium' ),
    ),
    	'rewrite' => array(
      		'slug' => $tax, 
      		'with_front' => false, 
      		'hierarchical' => true 
    	),
  	)); 
	
	$args = array(
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
	
	// Registering your Custom Post Type
	register_post_type( 'tv', $args );

	/* SPORTS */
	$taxSport = (get_theme_mod( 'streamium_sports_section_input_taxonomy' ) ? get_theme_mod( 'streamium_sports_section_input_taxonomy' ) : 'sports');
	$typeSport = (get_theme_mod( 'streamium_sports_section_input_posttype' ) ? get_theme_mod( 'streamium_sports_section_input_posttype' ) : 'sport');
	$menuTextSport = (get_theme_mod( 'streamium_sports_section_input_menu_text' ) ? get_theme_mod( 'streamium_sports_section_input_menu_text' ) : 'Sports');

  	register_taxonomy('sports', 'sport', array(
    	'hierarchical' => true,
    	'labels' => array(
      	'name' => __( $menuTextSport, 'streamium' ),
    ),
    	'rewrite' => array(
      		'slug' => $taxSport, 
      		'with_front' => false, 
      		'hierarchical' => true 
    	),
  	));

	$args = array(
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
	
	// Registering your Custom Post Type
	register_post_type( 'sport', $args );

	/* KIDS */
	$taxKids = (get_theme_mod( 'streamium_kids_section_input_taxonomy' ) ? get_theme_mod( 'streamium_kids_section_input_taxonomy' ) : 'kids');
	$typeKid = (get_theme_mod( 'streamium_kids_section_input_posttype' ) ? get_theme_mod( 'streamium_kids_section_input_posttype' ) : 'kid');
	$menuTextKids = (get_theme_mod( 'streamium_kids_section_input_menu_text' ) ? get_theme_mod( 'streamium_kids_section_input_menu_text' ) : 'Kids');

  	register_taxonomy('kids', 'kid', array(
    	'hierarchical' => true,
    	'labels' => array(
      	'name' => __( $menuTextKids, 'streamium' ),
    ),
    	'rewrite' => array(
      		'slug' => $taxKids, 
      		'with_front' => false, 
      		'hierarchical' => true 
    	),
  	));
	
	$args = array(
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
	
	// Registering your Custom Post Type
	register_post_type( 'kid', $args );


	/* LIVE */
	$taxStreams = (get_theme_mod( 'streamium_live_section_input_taxonomy' ) ? get_theme_mod( 'streamium_live_section_input_taxonomy' ) : 'streams');
	$typeStream = (get_theme_mod( 'streamium_live_section_input_posttype' ) ? get_theme_mod( 'streamium_live_section_input_posttype' ) : 'stream');
	$menuTextStreams = (get_theme_mod( 'streamium_live_section_input_menu_text' ) ? get_theme_mod( 'streamium_live_section_input_menu_text' ) : 'Streams');

  	register_taxonomy('streams', 'stream', array(
    	'hierarchical' => true,
    	'labels' => array(
      	'name' => __( $menuTextStreams, 'streamium' ),
    ),
    	'rewrite' => array(
      		'slug' => $taxStreams, 
      		'with_front' => false, 
      		'hierarchical' => true 
    	),
  	));

	$args = array(
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
	register_post_type( 'stream', $args );

}

add_action( 'init', 'streamium_custom_post_types', 0 );
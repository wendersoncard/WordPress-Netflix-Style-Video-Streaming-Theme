<?php

/**
 * Include admin scripts
 *
 * @return null
 * @author  @sameast
 */
function streamium_premium_admin_scripts( $hook_suffix ){

    if( in_array($hook_suffix, array('post.php', 'post-new.php') ) ){

        // Register, enqueue scripts and styles here
        wp_enqueue_script( 'streamium-premium-meta', get_template_directory_uri() . '/inc/premium/js/meta.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ), '1.1',  true );

    }
}

add_action( 'admin_enqueue_scripts', 'streamium_premium_admin_scripts');

/**
 * Include theme scripts
 *
 * @return null
 * @author  @sameast
 */
function streamium_premium_theme_scripts(){

    wp_enqueue_script( 'streamium-premium-profiles', get_template_directory_uri() . '/inc/premium/js/profiles.js', array( 'jquery' ), '1.1',  true );
    wp_enqueue_script( 'streamium-premium-reviews', get_template_directory_uri() . '/inc/premium/js/reviews.js', array( 'jquery' ), '1.1',  true );
    wp_enqueue_script( 'streamium-premium-credits', get_template_directory_uri() . '/inc/premium/js/credits.js', array( 'jquery' ), '1.1',  true );
    wp_enqueue_script( 'streamium-premium-filtering', get_template_directory_uri() . '/inc/premium/js/filtering.js', array( 'jquery' ), '1.1',  true );

}

add_action( 'wp_enqueue_scripts', 'streamium_premium_theme_scripts');

/**
 * Setup the theme
 *
 * @return null
 * @author  @sameast
 */
if (!function_exists('streamium_premium_setup_theme')) {

	/**
	 * Sets up the custom post types for (movies|programs|sports|kids|streams)
	 * These can be edited and changed in the theme customizer in the dashboard
	 *
	 * @return null
	 * @author  @sameast
	 */
	function streamium_premium_setup_theme() {

		register_taxonomy( 'role', 'credits', array(
	  		'hierarchical'      => true,
	  		'labels'            => array(
	  		'name'              => __( 'Roles', 'streamium' ),
	  		'singular_name'     => __( 'Role', 'streamium' ),
	  		'search_items'      => __( 'Search Roles', 'streamium' ),
	  		'all_items'         => __( 'All Roles', 'streamium' ),
	  		'parent_item'       => __( 'Parent Role', 'streamium' ),
	  		'parent_item_colon' => __( 'Parent Role:', 'streamium' ),
	  		'edit_item'         => __( 'Edit Role', 'streamium' ),
	  		'update_item'       => __( 'Update Role', 'streamium' ),
	  		'add_new_item'      => __( 'Add New Role', 'streamium' ),
	  		'new_item_name'     => __( 'New Role Name', 'streamium' ),
	  		'menu_name'         => __( 'Roles', 'streamium' ),
	  	),
	  		'show_ui'           => true,
	  		'show_in_nav_menus' => true,
	  		'show_admin_column' => true,
	  		'query_var'         => true,
	  		'show_in_rest'      => true
	  	));

	  	// SETUP PEOPLE POST TYPE:
		register_post_type( 'credits', array(
			'labels'              => array(
		  		'name'               => __( 'Credits', 'streamium' ),
		  		'singular_name'      => __( 'Credits', 'streamium' ),
		  		'menu_name'          => __( 'Credits', 'streamium' ),
		  		'name_admin_bar'     => __( 'Credits', 'streamium' ),
		  		'add_new'            => __( 'Add New Credits', 'streamium' ),
		  		'add_new_item'       => __( 'Add New Credits', 'streamium' ),
		  		'new_item'           => __( 'New Credits', 'streamium' ),
		  		'edit_item'          => __( 'Edit Credits', 'streamium' ),
		  		'view_item'          => __( 'View Credits', 'streamium' ),
		  		'all_items'          => __( 'All Credits', 'streamium' ),
		  		'search_items'       => __( 'Search Credits', 'streamium' ),
		  		'parent_item_colon'  => __( 'Parent Credits', 'streamium' ),
		  		'not_found'          => __( 'No Credits found.', 'streamium' ),
		  		'not_found_in_trash' => __( 'No Credits found in Trash.', 'streamium' )
		  	),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'menu_icon'           => 'https://s3.amazonaws.com/s3bubble-cdn/theme-images/s3bubblelogo.png',
			'menu_position'       => 6,
			'taxonomies'          => array(  'post_tag', 'roles' ),
			'show_in_rest'        => true,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields')
		));	

	}

	add_action( 'init', 'streamium_premium_setup_theme');

}
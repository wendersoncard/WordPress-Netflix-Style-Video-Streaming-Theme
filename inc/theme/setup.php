<?php


/**
 * Set the main connected website needed for all api calls
 *
 * @return null
 * @author  @sameast
 */

if (!function_exists('streamium_website_connection')) {

	function streamium_website_connection(){

	    if(isset($_SERVER['HTTP_HOST'])){

	        $host = preg_replace('#^www\.(.+\.)#i', '$1', $_SERVER['HTTP_HOST']); // remove the www
	        update_option("streamium_connected_website", $host);

	    }

	}

	add_action( 'init', 'streamium_website_connection' );

}

/**
 * Run checks for required plugins
 *
 * @return null
 * @author  @sameast
 */

if (!function_exists('streamium_required_plugin_checks')) {

	function streamium_required_plugin_checks() {

		if ( ! function_exists( 'get_plugins' ) ) {
		    require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( !is_plugin_active( 'easy-theme-and-plugin-upgrades/init.php' ) ) {

			echo '<div class="notice notice-info is-dismissible"><p>' . __( 'Recommended plugin (Easy Theme and Plugin Upgrades) not installed or activated.', 'streamium' ) . ' <a href="' . get_admin_url('','/plugin-install.php?s=easy-theme-and-plugin-upgrades&tab=search&type=term') . '">' . __( 'install/activate now', 'streamium' ) . '</a></p></div>';

		}

		if ( !is_plugin_active( 'post-types-order/post-types-order.php' ) ) {

			echo '<div class="notice notice-info is-dismissible"><p>' . __( 'Recommended plugin (Post Types Order) not installed or activated.', 'streamium' ) . ' <a href="' . get_admin_url('','/plugin-install.php?s=post-types-order&tab=search&type=term') . '">' . __( 'install/activate now', 'streamium' ) . '</a></p></div>';

		}

		if ( !is_plugin_active( 'taxonomy-terms-order/taxonomy-terms-order.php' ) ) {

			echo '<div class="notice notice-info is-dismissible"><p>' . __( 'Recommended plugin (Category Order and Taxonomy Terms Order) not installed or activated.', 'streamium' ) . ' <a href="' . get_admin_url('','/plugin-install.php?s=taxonomy-terms-order&tab=search&type=term') . '">' . __( 'install/activate now', 'streamium' ) . '</a></p></div>';

		}

		if ( !is_plugin_active( 'wp-extended-search/wp-es.php' ) ) {

			echo '<div class="notice notice-info is-dismissible"><p>' . __( 'Recommended plugin (WP Extended Search) not installed or activated.', 'streamium' ) . ' <a href="' . get_admin_url('','/plugin-install.php?s=wp-extended-search&tab=search&type=term') . '">' . __( 'install/activate now', 'streamium' ) . '</a></p></div>';

		}

		if ( is_plugin_active( 's3bubble-amazon-web-services-oembed-media-streaming-support/s3bubble-oembed.php' ) ) {

			echo '<div class="notice notice-error is-dismissible"><p>' . __( '!IMPORTANT you have the S3Bubble self hosted plugin installed this is not needed with this theme all functionality is built in please remove', 'streamium' ) . '</a></p></div>';

		}

	}

	add_action( 'admin_notices', 'streamium_required_plugin_checks' );

}

/**
 * Setup the theme
 *
 * @return null
 * @author  @sameast
 */
if (!function_exists('streamium_setup_theme')) {

	/**
	 * Sets up the custom post types for (movies|programs|sports|kids|streams)
	 * These can be edited and changed in the theme customizer in the dashboard
	 *
	 * @return null
	 * @author  @sameast
	 */
	function streamium_setup_theme() {

		// ADD TRANSLATIONS::
        load_theme_textdomain( 'streamium', get_template_directory() . '/languages' );

        // ADD THEME SUPPORT::
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_option('notice_premium', 1);

        // ADD FAVICONS::
        add_action( 'wp_head', 'wp_site_icon',  99    );
		add_action( 'login_head','wp_site_icon',  99    );

		// REGISTER MENUS::
		register_nav_menus( array(
			'header'             => __( 'Header Menu',       'streamium' ),
			'footer_company'     => __( 'Footer Company Menu',       'streamium' ),
			'footer_quick_links' => __( 'Footer Quick Links Menu',       'streamium' ),
			'social'             => __( 'Social Links Menu', 'streamium' ),
		) );

	  	// Define additional "post thumbnails". Relies on MultiPostThumbnails to work
		if (class_exists('MultiPostThumbnails')) {

			// Create aspects based on average
	        $averageBrowserWidth = 1680;
	        $width               = round($averageBrowserWidth/s3bubble_tile_count()); 
	        $lheight             = round($width/16*9);
	        $pheight             = round($width/9*16);

		    new MultiPostThumbnails(array( 
		        'label' => 'Tile Landscape',
		        'id' => 'content_tile_landscape',
		        'post_type' => 'content',
		        'text' => 'Tile images show be 16:9 aspect ratio this image will be resized to w' . $width . ' h' . $height
		    ));
		    add_image_size('content_tile_landscape', $width, $lheight, true); 

		    new MultiPostThumbnails(array(
		        'label' => 'Tile Landscape Expanded',
		        'id' => 'content_tile_expanded_landscape',
		        'post_type' => 'content',
		        'text' => 'Tile images show be 16:9 aspect ratio this image will be resized to w' . ($width*2) . ' h' . ($height*2)
		    ));
	        add_image_size('content_tile_expanded_landscape', ($width*2), ($lheight*2), true);

	        new MultiPostThumbnails(array(
		        'label' => 'Tile Portrait',
		        'id' => 'content_tile_portrait',
		        'post_type' => 'content',
		        'text' => 'Tile images show be 9:16 aspect ratio this image will be resized to w' . $height . ' h' . $width
		    ));
		    add_image_size('content_tile_portrait', $width, $pheight, true); 

		    error_log($width);
		     error_log($pheight);
		    new MultiPostThumbnails(array(
		        'label' => 'Tile Portrait Expanded',
		        'id' => 'content_tile_expanded_portrait',
		        'post_type' => 'content',
		        'text' => 'Tile images show be 9:16 aspect ratio this image will be resized to w' . ($height*2) . ' h' . ($width*2)
		    ));
	        add_image_size('content_tile_expanded_portrait', ($width*2), ($pheight*2), true);

		    new MultiPostThumbnails(array(
		        'label' => 'Roku Thumbnail',
		        'id' => 'content_tile_roku',
		        'post_type' => 'content',
		        'text' => 'Tile images show be 16:9 aspect ratio this image should be at least (800x450)'
		    ));
		 
		};

		// EXTRA IMAGES::
	    add_image_size('content_tile_roku', 800, 450, true); 
	    add_image_size('sidebar_episodes_tile', 210, 118, true); 
	    add_image_size('sidebar_cast_tile', 128, 128, true); 
	    add_image_size('content_tile_full_width_landscape', 800, 450, true); 
		add_image_size('site_logo', 0, 60, true);
        add_image_size('credits_avatar', 64, 64, true);


        /**
		 * Add new image sizes to post or page editor
		 *
		 * @return null
		 * @author  @sameast
		 */
		if ( ! function_exists ( 'streamium_extra_image_sizes' ) ) {

		    function streamium_extra_image_sizes($sizes){
		        $streamiumThemeSizes = array(
		            'content_tile_landscape'            => __('Video Tile', 'streamium'),
		            'content_tile_expanded_landscape'   => __('Video Tile Expanded', 'streamium'),
		            'content_tile_portrait'             => __('Video Tile', 'streamium'),
		            'content_tile_expanded_portrait'    => __('Video Tile Expanded', 'streamium'),
		            'content_tile_full_width_landscape' => __('Video Tile Large Expanded', 'streamium'),
		            'content_tile_roku'                 => __('Main Slider', 'streamium'),
		        );
		        $sizes = array_merge($sizes, $streamiumThemeSizes);
		        return $sizes;

		    }
		    add_filter('image_size_names_choose', 'streamium_extra_image_sizes');
		
		}

		// REGISTER TAXONOMIES:  
		$rewriteGenresTax = get_theme_mod( 'streamium_global_settings_taxonomy_genres', 'genres' );
	  	register_taxonomy( 'genres', 'content', array(
	  		'hierarchical'      => true,
	  		'labels'            => array(
	  		'name'              => __( 'Genres', 'streamium' ),
	  		'singular_name'     => __( 'Genre', 'streamium' ),
	  		'search_items'      => __( 'Search Genres', 'streamium' ),
	  		'all_items'         => __( 'All Genres', 'streamium' ),
	  		'parent_item'       => __( 'Parent Genre', 'streamium' ),
	  		'parent_item_colon' => __( 'Parent Genre:', 'streamium' ),
	  		'edit_item'         => __( 'Edit Genre', 'streamium' ),
	  		'update_item'       => __( 'Update Genre', 'streamium' ),
	  		'add_new_item'      => __( 'Add New Genre', 'streamium' ),
	  		'new_item_name'     => __( 'New Genre Name', 'streamium' ),
	  		'menu_name'         => __( 'Genres', 'streamium' ),
	  	),
	  		'show_ui'           => true,
	  		'show_in_nav_menus' => true,
	  		'show_admin_column' => true,
	  		'query_var'         => true,
	  		'rewrite'           => array( 'slug' => $rewriteGenresTax ),
	  		'show_in_rest'      => true
	  	));

	  	$rewriteContentTypesTax = get_theme_mod( 'streamium_global_settings_taxonomy_content_types', 'content_types' );
	  	register_taxonomy( 'content_types', 'content', array(
	  		'hierarchical'      => true,
	  		'labels'            => array(
	  		'name'              => __( 'Content Types', 'streamium' ),
	  		'singular_name'     => __( 'Content Type', 'streamium' ),
	  		'search_items'      => __( 'Search Content Types', 'streamium' ),
	  		'all_items'         => __( 'All Content Types', 'streamium' ),
	  		'parent_item'       => __( 'Parent Content Type', 'streamium' ),
	  		'parent_item_colon' => __( 'Parent Content Type:', 'streamium' ),
	  		'edit_item'         => __( 'Edit Content Type', 'streamium' ),
	  		'update_item'       => __( 'Update Content Type', 'streamium' ),
	  		'add_new_item'      => __( 'Add New Content Type', 'streamium' ),
	  		'new_item_name'     => __( 'New Content Type Name', 'streamium' ),
	  		'menu_name'         => __( 'Content Types', 'streamium' ),
	  	),
	  		'show_ui'           => true,
	  		'show_in_nav_menus' => true,
	  		'show_admin_column' => true,
	  		'query_var'         => true,
	  		'rewrite'           => array( 'slug' => $rewriteContentTypesTax ),
	  		'show_in_rest'      => true
	  	)); 

	  	// ADD EXTRA TAXONOMY FIELDS::
		require_once(get_template_directory() . '/inc/theme/Tax-meta-class/Tax-meta-class.php');
		$streamium_extra_meta = new Tax_Meta_Class(array(
		   'id' => 'streamium_extra_meta', // meta box id, unique per meta box
		   'title' => 'Streamium Extra Meta Box', // meta box title
		   'pages' => array('genres'), // taxonomy name, post_tag and custom taxonomies
		   'context' => 'normal', // where the meta box appear: normal (default), advanced, side; optional
		   'fields' => array(), // list of meta fields (can be added by field arrays)
		   'local_images' => false, // Use local or hosted images (meta box images for add/remove)
		   'use_with_theme' => get_template_directory_uri() . "/inc/theme/Tax-meta-class/" 
		));
		$prefix = 'streamium_';

		$repeater_fields[] = $streamium_extra_meta->addImage($prefix . 'meta_images',
			array(
				'name'=> __('Image ','streamium')
			),true);
  

		$streamium_extra_meta->addRepeaterBlock(
			$prefix . 're_meta_images',
			array(
				'inline' => true, 
				'name' => __('Genre Images (Mobile App)','streamium'),
				'fields' => $repeater_fields
			)
		);


		/*$streamium_extra_meta->addImage($prefix . 'meta_image',
			array(
				'name'=> __('Genre Image (Mobile App)','streamium')
			)	
		);*/

		$streamium_extra_meta->addCheckbox($prefix . 'recently_watched',
			array(
				'name' => __('Set as special recently watched','streamium')
			)
		);

		$streamium_extra_meta->addCheckbox($prefix . 'most_watched',
			array(
				'name' => __('Set as special most watched','streamium')
			)
		);

		$streamium_extra_meta->addCheckbox($prefix . 'meta_free',
			array(
				'name' => __('Bypass Woocommerce Redirect','streamium')
			)
		);

		$streamium_extra_meta->addCheckbox($prefix . 'meta_homepage',
			array(
				'name' => __('Display On Homepage','streamium')
			)
		);

		$orientation   = get_theme_mod( 'streamium_global_tile_orientation', 'landscape' );
		$streamium_extra_meta->addSelect($prefix . 'meta_orientation',
			array(
				$orientation => $orientation,
				'landscape' => 'Landscape',
				'portrait'=>'Portrait'
			),
			array(
				'name'=> __('Override Orientation','streamium')
			) 
		);

		$type   = get_theme_mod( 'streamium_global_tile_types', 'hover' );
		$streamium_extra_meta->addSelect($prefix . 'meta_tile_type',
			array(
				$type => $type,
				'hover' => 'Hover', 
				'text'  => 'Text'
			),
			array(
				'name'=> __('Override Tile Type','streamium')
			) 
		);

		$tileCount   = get_theme_mod( 'streamium_global_tile_count', '6' );
		$streamium_extra_meta->addSelect($prefix . 'meta_tile_count',
			array(
				$tileCount  => $tileCount,
				'4'  => '4',
                '5'  => '5',
                '6'  => '6',
                '7'  => '7',
                '8'  => '8'
            ),
			array(
				'name'=> __('Override Tile Count','streamium')
			)
		);

		$streamium_extra_meta->Finish(); 

		// SETUP CONTENT POST TYPE:
		register_post_type( 'content', array(
			'labels'              => array(
		  		'name'               => __( 'Content', 'streamium' ),
		  		'singular_name'      => __( 'Content', 'streamium' ),
		  		'menu_name'          => __( 'Content', 'streamium' ),
		  		'name_admin_bar'     => __( 'Content', 'streamium' ),
		  		'add_new'            => __( 'Add New Content', 'streamium' ),
		  		'add_new_item'       => __( 'Add New Content', 'streamium' ),
		  		'new_item'           => __( 'New Content', 'streamium' ),
		  		'edit_item'          => __( 'Edit Content', 'streamium' ),
		  		'view_item'          => __( 'View Content', 'streamium' ),
		  		'all_items'          => __( 'All Content', 'streamium' ),
		  		'search_items'       => __( 'Search Content', 'streamium' ),
		  		'parent_item_colon'  => __( 'Parent Content', 'streamium' ),
		  		'not_found'          => __( 'No Content found.', 'streamium' ),
		  		'not_found_in_trash' => __( 'No Content found in Trash.', 'streamium' )
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
			'menu_position'       => 5,
			'taxonomies'          => array( 'post_tag', 'genres', 'content_types' ),
			'show_in_rest'        => true,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields')
		));	

	}

	add_action( 'init', 'streamium_setup_theme');

}

// REGISTER SIDEBARS::
if (!function_exists('streamium_widgets_init') ) { 

	function streamium_widgets_init() {
	    
        register_sidebar(array( 
            'name' => __('Page Sidebar', 'streamium'), 
            'id' => 'page-sidebar', 
            'description' => 'Appears as the sidebar on a page',
            'before_widget' => '<div id="%1$s" class="widget %2$s">', 
            'after_widget' => '</div>',  
            'before_title' => '<h4 class="widgettitle">', 
            'after_title' => '</h4>'
        ));
        register_sidebar(array( 
            'name' => __('Blog Sidebar', 'streamium'), 
            'id' => 'blog-sidebar', 
            'description' => 'Appears as the sidebar on a blog',
            'before_widget' => '<div id="%1$s" class="widget %2$s">', 
            'after_widget' => '</div>', 
            'before_title' => '<h4 class="widgettitle">', 
            'after_title' => '</h4>'
        ));
        register_sidebar(array( 
            'name' => __('Forum Sidebar', 'streamium'), 
            'id' => 'forum-sidebar', 
            'description' => 'Appears as the sidebar on a forum',
            'before_widget' => '<div id="%1$s" class="widget %2$s">', 
            'after_widget' => '</div>', 
            'before_title' => '<h4 class="widgettitle">', 
            'after_title' => '</h4>'
        ));
        register_sidebar(array( 
            'name' => __('Footer Sidebar', 'streamium'), 
            'id' => 'footer-sidebar', 
            'description' => 'Appears in the footer far right',
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">', 
            'after_widget' => '</div>', 
            'before_title' => '<div class="footer-title">', 
            'after_title' => '</div>'
        ));   
	    
	}

	add_action( 'widgets_init', 'streamium_widgets_init' );

}
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
  	register_taxonomy('programs', 'post', array(
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

  	register_taxonomy('sports', 'post', array(
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

  	register_taxonomy('kids', 'post', array(
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

	$labels = array(
		'name'                => __( ucfirst($taxKids), 'streamium' ),
	);
	
	$args = array(
		'labels'              => $labels,
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

  	register_taxonomy('streams', 'post', array(
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
			'name'                => __( ucfirst($taxStreams), 'streamium' ),
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
		'rewrite' => array('slug' => $typeStream,'with_front' => false),
	);
	
	// Registering your Custom Post Type
	register_post_type( 'stream', $args );

}

add_action( 'init', 'streamium_custom_post_types', 0 );

/**
 * Setup custom repeater needed for post type for TV Programs
 *
 * @return null
 * @author  @sameast
 */
function streamium_add_meta_boxes() {

	add_meta_box( 'streamium-repeatable-fields', 'Multiple Videos', 'streamium_repeatable_meta_box_display', array('tv','sport','kids','live'), 'normal', 'high');
	add_meta_box( 'streamium-meta-box-main-slider', 'Featured Video', 'streamium_meta_box_main_slider', array('tv','sport','kids','live'), 'side', 'high' );
	add_meta_box( 'streamium-meta-box-trailer', 'Video Trailer', 'streamium_meta_box_trailer', array('tv','sport','kids','live'), 'side', 'high' );
 
}
add_action('admin_init', 'streamium_add_meta_boxes', 1);


/**
 * Setup custom repeater meta
 *
 * @return null
 * @author  @sameast
 */
function streamium_repeatable_meta_box_display() {

	global $post;

	$repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);

	wp_nonce_field( 'streamium_repeatable_meta_box_nonce', 'streamium_repeatable_meta_box_nonce' );

	?>  
	<table id="repeatable-fieldset-one" width="100%">
	<thead>
		<tr>
			<th align="left">Thumbnail</th>
			<th align="left">Code</th>
			<th align="left">Title</th>
			<th align="left">Description</th>
			<th align="left">Remove</th>
		</tr>
	</thead>
	<tbody>
	<?php
	
	if ( $repeatable_fields ) :
	
	foreach ( $repeatable_fields as $field ) {
	?>
	<tr>
		<td valign="top">
			<input class="streamium_upl_button button" type="button" value="Upload Image" />
			<input type="hidden" class="widefat" name="thumbnails[]" value="<?php if($field['thumbnails'] != '') echo esc_attr( $field['thumbnails'] ); ?>" />
			<img src="<?php if($field['thumbnails'] != '') echo esc_attr( $field['thumbnails'] ); ?>" style="width: 130px;" /> 
		</td>
		<td valign="top" width="60">
			<select class="streamium-theme-episode-select chosen-select" tabindex="1" name="codes[]">
				<option value="<?php echo $field['codes']; ?>">Select Video <?php echo $field['codes']; ?></option>
			</select>
		</td>
		<td valign="top">
			<input type="text" class="widefat" name="titles[]" value="<?php if($field['titles'] != '') echo esc_attr( $field['titles'] ); ?>" />
		</td>
		<td valign="top">
			<textarea rows="4" cols="50" class="widefat" name="descriptions[]" value=""><?php if ($field['descriptions'] != '') echo esc_attr( $field['descriptions'] ); else echo ''; ?></textarea>
		</td>
		<td valign="top">
			<a class="button remove-row" href="#">Remove</a>
		</td>
	</tr>
	<?php
	}
	else :
	// show a blank one
	?>
	<tr>
		<td valign="top">
			<input class="streamium_upl_button button" type="button" value="Upload Image" />
			<input type="hidden" class="widefat" name="thumbnails[]" />
			<img src="http://placehold.it/260x146" style="width: 130px;" />  
		</td>
	    <td valign="top" width="60">
			<select class="streamium-theme-episode-select chosen-select" tabindex="1" name="codes[]"></select>
		</td>

		<td valign="top"><input type="text" class="widefat" name="titles[]" /></td>
	
		<td valign="top">
			<textarea rows="4" cols="50" class="widefat" name="descriptions[]" value=""></textarea>
		</td>
	
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<?php endif; ?>
	
	
	</tbody>
	</table> 
	<p><a id="add-row" class="button add-program-row button-primary" href="#">Add another</a></p>
	<?php
}

/**
 * Setup custom repeater meta
 *
 * @return null
 * @author  @sameast
 */
function streamium_meta_box_main_slider() {
	
	global $post;

	$meta = get_post_meta( $post->ID );
	$streamium_tv_featured_checkbox_value = ( isset( $meta['streamium_tv_featured_checkbox_value'][0] ) &&  '1' === $meta['streamium_tv_featured_checkbox_value'][0] ) ? 1 : 0;
	wp_nonce_field( 'streamium_repeatable_meta_box_nonce', 'streamium_repeatable_meta_box_nonce' );
	
	?>
		<p>
			<label><input type="checkbox" name="streamium_tv_featured_checkbox_value" value="1" <?php checked( $streamium_tv_featured_checkbox_value, 1 ); ?> /><?php esc_attr_e( 'Show in the main feature slider', 'streamium' ); ?></label>
		</p>
	<?php

}

/**
 * Setup custom repeater meta box save
 *
 * @return null
 * @author  @sameast
 */
function streamium_repeatable_meta_box_save($post_id) {

	if ( ! isset( $_POST['streamium_repeatable_meta_box_nonce'] ) ||
	! wp_verify_nonce( $_POST['streamium_repeatable_meta_box_nonce'], 'streamium_repeatable_meta_box_nonce' ) )
		return;
	
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	
	if (!current_user_can('edit_post', $post_id))
		return;
	
	$old = get_post_meta($post_id, 'repeatable_fields', true);
	$new = array();
	
	$thumbnails   = $_POST['thumbnails'];
	$titles       = $_POST['titles'];
	$codes        = $_POST['codes'];
	$descriptions = $_POST['descriptions'];
	
	$count = count( $titles );
	
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $titles[$i] != '' ) :

			$new[$i]['thumbnails'] = stripslashes( strip_tags( $thumbnails[$i] ) );
			$new[$i]['titles'] = stripslashes( strip_tags( $titles[$i] ) );
			$new[$i]['codes'] = $codes[$i];
			$new[$i]['descriptions'] = stripslashes( $descriptions[$i] );

		endif;
	}

	if ( !empty( $new ) && $new != $old ) {
		update_post_meta( $post_id, 'repeatable_fields', $new );
	}
	elseif ( empty($new) && $old ) {
		delete_post_meta( $post_id, 'repeatable_fields', $old );
	}

	$streamium_tv_featured_checkbox_value = ( isset( $_POST['streamium_tv_featured_checkbox_value'] ) && '1' === $_POST['streamium_tv_featured_checkbox_value'] ) ? 1 : 0; 
	update_post_meta( $post_id, 'streamium_tv_featured_checkbox_value', esc_attr( $streamium_tv_featured_checkbox_value ) );

}
add_action('save_post', 'streamium_repeatable_meta_box_save');
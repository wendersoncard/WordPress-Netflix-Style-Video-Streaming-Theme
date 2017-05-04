<?php

/**
 * Setup custom post type for TV Programs
 *
 * @return null
 * @author  @sameast
 */
function streamium_tv_custom_post_type() {

	$labels = array(
		'name'                => _x( 'TV Programs', 'Post Type General Name', 'streamium' ),
		'singular_name'       => _x( 'Episode', 'Post Type Singular Name', 'streamium' ),
		'menu_name'           => __( 'TV Programs', 'streamium' ),
		'parent_item_colon'   => __( 'Parent Episode', 'streamium' ),
		'all_items'           => __( 'All Programs', 'streamium' ),
		'view_item'           => __( 'View Episode', 'streamium' ),
		'add_new_item'        => __( 'Add New Episode', 'streamium' ),
		'add_new'             => __( 'Add New', 'streamium' ),
		'edit_item'           => __( 'Edit Episode', 'streamium' ),
		'update_item'         => __( 'Update Episode', 'streamium' ),
		'search_items'        => __( 'Search Episode', 'streamium' ),
		'not_found'           => __( 'Not Found', 'streamium' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'streamium' ),
	);
	
	$args = array(
		'label'               => __( 'tv', 'streamium' ),
		'description'         => __( 'TV Programs', 'streamium' ),
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
		'taxonomies'          => array( 'category', 'post_tag', 'programs' ),
		//'rewrite' => array( 'slug' => 'sam', 'with_front' => FALSE ),
		'has_category' =>true,
	);
	
	// Registering your Custom Post Type
	register_post_type( 'programs', $args );

}

add_action( 'init', 'streamium_tv_custom_post_type', 0 );

/**
 * Setup custom repeater needed for post type for TV Programs
 *
 * @return null
 * @author  @sameast
 */
function streamium_add_meta_boxes() {
	add_meta_box( 'repeatable-fields', 'Program Videos', 'streamium_repeatable_meta_box_display', 'programs', 'normal', 'high');
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
	<script type="text/javascript">
	jQuery(document).ready(function( $ ){
		$( '#add-row' ).live('click', function() {

			$('#repeatable-fieldset-one > tbody:last-child').append('<tr><td>' +
				'<input class="streamium_upl_button button" type="button" value="Upload Image" />' +
				'<input type="hidden" class="widefat" name="thumbnails[]" />' +
				'<img src="http://placehold.it/260x146" style="width: 130px;" />' + 
				'</td>' +
				'<td><select class="streamium-theme-episode-select chosen-select" id="updateit" tabindex="1" name="codes[]"></select></td>' +
				'<td><input type="text" class="widefat" name="titles[]" /></td>' +
				'<td><input type="text" class="widefat" name="descriptions[]" value="" /></td>' +
				'<td><a class="button remove-row" href="#">Remove</a></td>' +
			'</tr>');
			return false;

		});
  	
		$( '.remove-row' ).live('click', function() {
			$(this).parents('tr').remove();
			return false;
		});
		$('.streamium_upl_button').live('click', function() {
			var that = $(this);
			//use here, because you may have multiple buttons, so `send_to_editor` needs fresh
			window.send_to_editor = function(html) {
				var imgurl = $(html).attr('src')
				that.next('input').val(imgurl);
				that.next().next('img').attr("src",imgurl);
				tb_remove();
			}
		
			//formfield = $('#streamium_image_URL').attr('name');
			tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
			return false;
		});
	});
	</script>
  
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
		<td valign="top">
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
		<td>
			<input class="streamium_upl_button button" type="button" value="Upload Image" />
			<input type="hidden" class="widefat" name="thumbnails[]" />
			<img src="http://placehold.it/260x146" style="width: 130px;" />  
		</td>
	    <td>
			<select class="streamium-theme-episode-select chosen-select" tabindex="1" name="codes[]"></select>
		</td>

		<td><input type="text" class="widefat" name="titles[]" /></td>
	
		<td><input type="text" class="widefat" name="descriptions[]" value="" /></td>
	
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<?php endif; ?>
	
	
	</tbody>
	</table> 
	<p><a id="add-row" class="button add-program-row button-primary" href="#">Add another</a></p>
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

}
add_action('save_post', 'streamium_repeatable_meta_box_save');
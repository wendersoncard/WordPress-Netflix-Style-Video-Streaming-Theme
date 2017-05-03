<?php

/**
 * Setup custom post type for TV Programs
 *
 * @return null
 * @author  @sameast
 */
function streamium_tv_custom_post_type() {

// Set UI labels for Custom Post Type
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
	
// Set other options for Custom Post Type
	
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
		
		// This is where we add taxonomies to our CPT
		'taxonomies'          => array( 'category', 'programs' ),
		//'rewrite' => array( 'slug' => 'sam', 'with_front' => FALSE ),
		'has_category' =>true,
	);
	
	// Registering your Custom Post Type
	register_post_type( 'programs', $args );

}

add_action( 'init', 'streamium_tv_custom_post_type', 0 );

/**
 * Repeatable Custom Fields in a Metabox
 * Author: Helen Hou-Sandi
 *
 * From a bespoke system, so currently not modular - will fix soon
 * Note that this particular metadata is saved as one multidimensional array (serialized)
 */

add_action('admin_init', 'hhs_add_meta_boxes', 1);
function hhs_add_meta_boxes() {
	add_meta_box( 'repeatable-fields', 'Program Videos', 'hhs_repeatable_meta_box_display', 'programs', 'normal', 'high');
}

function hhs_repeatable_meta_box_display() {
	global $post;

	$repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);

	wp_nonce_field( 'hhs_repeatable_meta_box_nonce', 'hhs_repeatable_meta_box_nonce' );
	?>
	<script type="text/javascript">
	jQuery(document).ready(function( $ ){
		$( '#add-row' ).on('click', function() {
			var row = $( '.empty-row.screen-reader-text' ).clone(true);
			row.removeClass( 'empty-row screen-reader-text' );
			row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
			return false;
		});
  	
		$( '.remove-row' ).on('click', function() {
			$(this).parents('tr').remove();
			return false;
		});
	});
	</script>
  
	<table id="repeatable-fieldset-one" width="100%">
	<thead>
		<tr>
			<th width="12%">Code</th>
			<th width="40%">Title</th>
			<th width="40%">Description</th>
			<th width="8%"></th>
		</tr>
	</thead>
	<tbody>
	<?php
	
	if ( $repeatable_fields ) :
	
	foreach ( $repeatable_fields as $field ) {
	?>
	<tr>
		
	
		<td>
			<select class="streamium-theme-episode-select chosen-select" tabindex="1" name="codes[]">
				<option value="<?php echo $field['codes']; ?>">Select Video <?php echo $field['codes']; ?></option>
            	<option value="">Remove Current Video</option>
				<?php /*foreach ( $options as $label => $value ) : ?>
				<option value="<?php echo $value; ?>"<?php selected( $field['select'], $value ); ?>><?php echo $label; ?></option>
				<?php endforeach;*/ ?>
			</select>
		</td>

		<td><input type="text" class="widefat" name="titles[]" value="<?php if($field['titles'] != '') echo esc_attr( $field['titles'] ); ?>" /></td>
	
		<td><textarea rows="4" cols="50" class="widefat" name="descriptions[]" value=""><?php if ($field['descriptions'] != '') echo esc_attr( $field['descriptions'] ); else echo ''; ?></textarea></td>
	
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<?php
	}
	else :
	// show a blank one
	?>
	<tr>

	    <td>
			<select class="streamium-theme-episode-select chosen-select" tabindex="1" name="codes[]"></select>
		</td>

		<td><input type="text" class="widefat" name="titles[]" /></td>
	
		<td><input type="text" class="widefat" name="descriptions[]" value="" /></td>
	
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<?php endif; ?>
	
	<!-- empty hidden one for jQuery -->
	<tr class="empty-row screen-reader-text">
	
		<td>
			<select class="streamium-theme-episode-select chosen-select" tabindex="1" name="codes[]"></select>
		</td>

		<td><input type="text" class="widefat" name="titles[]" /></td>
		
		<td><input type="text" class="widefat" name="descriptions[]" value="" /></td>
		  
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	</tbody>
	</table>
	
	<p><a id="add-row" class="button" href="#">Add another</a></p>
	<?php
}

add_action('save_post', 'hhs_repeatable_meta_box_save');
function hhs_repeatable_meta_box_save($post_id) {
	if ( ! isset( $_POST['hhs_repeatable_meta_box_nonce'] ) ||
	! wp_verify_nonce( $_POST['hhs_repeatable_meta_box_nonce'], 'hhs_repeatable_meta_box_nonce' ) )
		return;
	
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	
	if (!current_user_can('edit_post', $post_id))
		return;
	
	$old = get_post_meta($post_id, 'repeatable_fields', true);
	$new = array();
	
	$names = $_POST['titles'];
	$selects = $_POST['codes'];
	$urls = $_POST['descriptions'];
	
	$count = count( $names );
	
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $names[$i] != '' ) :

			$new[$i]['titles'] = stripslashes( strip_tags( $names[$i] ) );
			$new[$i]['codes'] = $selects[$i];
			$new[$i]['descriptions'] = stripslashes( $urls[$i] );

		endif;
	}

	if ( !empty( $new ) && $new != $old )
		update_post_meta( $post_id, 'repeatable_fields', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, 'repeatable_fields', $old );
}

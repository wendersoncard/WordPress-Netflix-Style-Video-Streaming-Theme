<?php

	/*
	 Template Name: Tests Page Template
	 */

	 global $post;

  $repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);

  wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );

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
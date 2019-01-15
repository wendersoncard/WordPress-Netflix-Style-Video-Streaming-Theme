<?php

/**
 * Adds meta boxes within a post
 *
 * @return null
 * @author  @sameast
 */
function streamium_video_code_meta_box_add(){

    // NEW SETUPS::
    add_meta_box( 'streamium-meta-box-movie', 'Main Video', 'streamium_meta_box_movie', 'content', 'side', 'high' );

    // OVERIDE RELEASE DATE::
    add_meta_box( 'streamium-meta-box-release-date', 'Release Date', 'streamium_meta_box_release_date', 'content', 'side', 'high' );

}

add_action( 'add_meta_boxes', 'streamium_video_code_meta_box_add' );

/**
 * Main move can be set here
 *
 * @return null
 * @author  @sameast
 */
function streamium_meta_box_movie(){

    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
    $text = isset( $values['streamium_meta_box_movie_code'] ) ? $values['streamium_meta_box_movie_code'][0] : '';
    wp_nonce_field( 'streamium_meta_security', 'streamium_meta_nonce' );

    ?>
    <p class="streamium-meta-box-wrapper">
        <select class="streamium-meta-box-movie-code-select-group chosen-select" tabindex="1" name="streamium_meta_box_movie_code" id="streamium_meta_box_movie_code">
            <option value="<?php echo $text; ?>"><?php echo (empty($text)) ? 'Select Main Video' : $text; ?></option>
            <option value="">Remove Current Video</option>
        </select>

        <div class="streamium-meta-box-alert" role="alert">
            <strong>Heads up!</strong> If your code is not showing please try and refresh your page to get the latest players. <a href='#' onclick='location.reload(true); return false;'>Refresh</a>
        </div>

    </p>



    <?php    

}

/**
 * Overide release date
 *
 * @return null
 * @author  @sameast
 */
function streamium_meta_box_release_date() {
  
    global $post;
    $values = get_post_custom( $post->ID );
    $text = isset( $values['streamium_release_date_meta_box_text'] ) ? $values['streamium_release_date_meta_box_text'][0] : '';
    wp_nonce_field( 'streamium_meta_security', 'streamium_meta_nonce' );
    ?>
    <p class="streamium-meta-box-wrapper">

        <input type="text" name="streamium_release_date_meta_box_text" class="widefat s3bubble-meta-datepicker" id="streamium_release_date_meta_box_text" value="<?php echo $text; ?>" />

    </p>

    <?php 

}

/**
 * Saves the meta box content
 *
 * @return null
 * @author  @sameast
 */
function streamium_meta_boxes_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['streamium_meta_nonce'] ) || !wp_verify_nonce( $_POST['streamium_meta_nonce'], 'streamium_meta_security' ) ) return;
     
    // if our current user can't edit this post, bail
    if ( ! current_user_can( 'edit_posts' ) ) return;
     
    // MAIN MOVIE CODE::
    if( isset( $_POST['streamium_meta_box_movie_code'] ) ){

        update_post_meta( $post_id, 'streamium_meta_box_movie_code', $_POST['streamium_meta_box_movie_code'] );
      
    }

    // OVERIDE RELEASE DATE::
    if( isset( $_POST['streamium_release_date_meta_box_text'] ) ){
        
        update_post_meta( $post_id, 'streamium_release_date_meta_box_text', $_POST['streamium_release_date_meta_box_text'] );

    }

}

add_action( 'save_post', 'streamium_meta_boxes_save', 10, 3 );
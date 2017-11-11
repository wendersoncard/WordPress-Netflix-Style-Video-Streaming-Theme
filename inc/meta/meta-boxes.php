<?php

/**
 * Adds meta boxes within a post
 *
 * @return null
 * @author  @sameast
 */
function streamium_video_code_meta_box_add(){

    add_meta_box( 'streamium-meta-box-movie', 'Main Video', 'streamium_meta_box_movie', array('movie', 'tv','sport','kid'), 'side', 'high' );

    add_meta_box( 'streamium-meta-box-trailer', 'Video Trailer/Preview', 'streamium_meta_box_trailer', array('movie', 'tv','sport','kid'), 'side', 'high' );

    add_meta_box( 'streamium-meta-box-bgvideo', 'Main Slider BG Video', 'streamium_meta_box_bgvideo', array('movie', 'tv','sport','kid'), 'side', 'high' );

    add_meta_box( 'streamium-meta-box-main-slider', 'Main Slider Video', 'streamium_meta_box_main_slider', array('movie', 'tv','sport','kid','stream'), 'side', 'high' );

    // Repeater for premium
    add_meta_box( 'streamium-repeatable-fields', 'Multiple Videos - Seasons/Episodes', 'streamium_repeatable_meta_box_display', array('movie', 'tv','sport','kid'), 'normal', 'high');

    // live stream meta
    add_meta_box( 'streamium-meta-box-live-streams', 'Live Streams', 'streamium_meta_box_live_streams', array('stream'), 'side', 'high' );

    // Global extra meta
    add_meta_box( 'streamium-meta-box-extra-meta', 'Extra Video Tile Meta', 'streamium_meta_box_extra_meta', array('movie', 'tv','sport','kid','stream'), 'normal', 'high' );

}

add_action( 'add_meta_boxes', 'streamium_video_code_meta_box_add' );

/**
 * Sets up the meta box content for the main video
 *
 * @return null
 * @author  @sameast
 */
function streamium_meta_box_movie(){

    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
    $text = isset( $values['s3bubble_video_code_meta_box_text'] ) ? $values['s3bubble_video_code_meta_box_text'][0] : '';
    $service = isset( $values['s3bubble_video_youtube_code_meta_box_text'] ) ? $values['s3bubble_video_youtube_code_meta_box_text'][0] : '';

    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );

    

    ?>
    <p class="streamium-meta-box-wrapper">
        <label>AWS Video</label>
        <select class="streamium-theme-main-video-select-group chosen-select" tabindex="1" name="s3bubble_video_code_meta_box_text" id="s3bubble_video_code_meta_box_text">
            <option value="<?php echo $text; ?>"><?php echo (empty($text)) ? 'Select Main Video' : $text; ?></option>
            <option value="">Remove Current Video</option>
        </select>
    </p>
    <p class="streamium-meta-box-wrapper">
        <label>Youtube/Vimeo/Dailymotion Direct Link</label>
        <input type="text" name="s3bubble_video_youtube_code_meta_box_text" class="widefat" id="s3bubble_video_youtube_code_meta_box_text" value="<?php echo $service; ?>" placeholder="Enter service url" />
    </p>

    <?php    

}

/**
 * Sets up the meta box content for the video trailer
 *
 * @return null
 * @author  @sameast
 */
function streamium_meta_box_trailer(){

    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
    $text = isset( $values['streamium_video_trailer_meta_box_text'] ) ? $values['streamium_video_trailer_meta_box_text'][0] : '';
    $service = isset( $values['s3bubble_video_trailer_youtube_code_meta_box_text'] ) ? $values['s3bubble_video_trailer_youtube_code_meta_box_text'][0] : '';
    $button = isset( $values['s3bubble_video_trailer_button_text_meta_box_text'] ) ? $values['s3bubble_video_trailer_button_text_meta_box_text'][0] : 'Watch Trailer';

    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );
    ?>
    <?php if(get_theme_mod( 'streamium_enable_premium' )) : ?>

        <p class="streamium-meta-box-wrapper">
            <select class="streamium-theme-video-trailer-select-group chosen-select" tabindex="1" name="streamium_video_trailer_meta_box_text" id="streamium_video_trailer_meta_box_text">
                <option value="<?php echo $text; ?>"><?php echo (empty($text)) ? 'Select Video Trailer' : $text; ?></option>
                <option value="">Remove Current Video</option>
            </select>
        </p>

        <p class="streamium-meta-box-wrapper">
            <label>Youtube/Vimeo/Dailymotion Direct Link</label>
            <input type="text" name="s3bubble_video_trailer_youtube_code_meta_box_text" class="widefat" id="s3bubble_video_trailer_youtube_code_meta_box_text" value="<?php echo $service; ?>" placeholder="Enter service url" />
        </p>

        <p class="streamium-meta-box-wrapper">
            <label>Change Button Text</label>
            <input type="text" name="s3bubble_video_trailer_button_text_meta_box_text" class="widefat" id="s3bubble_video_trailer_button_text_meta_box_text" value="<?php echo $button; ?>" placeholder="Enter button text" />
        </p>

    <?php else : ?>
          
            <div class='streamium-current-url-info'>This is only available with the Premium package. <a href="https://s3bubble.com/pricing/" target="_blank">Upgrade</a></div>
          
    <?php endif; ?>

    <?php    

}

/**
 * Sets up the meta box content for the video background on home slider
 *
 * @return null
 * @author  @sameast
 */
function streamium_meta_box_bgvideo(){

    global $post;
    $values = get_post_custom( $post->ID );
    $text = isset( $values['streamium_featured_video_meta_box_text'] ) ? $values['streamium_featured_video_meta_box_text'][0] : '';
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );
    ?>
    <p class="streamium-meta-box-wrapper">
        <?php if(get_theme_mod( 'streamium_enable_premium' )) : ?>

            <select class="streamium-theme-featured-video-select-group chosen-select" tabindex="1" name="streamium_featured_video_meta_box_text" id="streamium_featured_video_meta_box_text">
                <option value="<?php echo $text; ?>">Select Background Video</option>
                <option value="">Remove Current Video</option>
            </select>

            <?php echo !empty($text) ? "<div class='streamium-current-url'>Premium video code: " . $text . "</div>" : "<div class='streamium-current-url-info'>This will display a background video on the homepage slider.</div>"; ?>
          
        <?php else : ?>
          
          <div class='streamium-current-url-info'>This is only available with the Premium package. <a href="https://s3bubble.com/pricing/" target="_blank">Upgrade</a></div>
          
        <?php endif; ?>

    </p>

    <?php    

}

/**
 * Setup custom repeater meta
 *
 * @return null
 * @author  @sameast
 */
function streamium_repeatable_meta_box_display() {

    global $post;
    $repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );

    if(get_theme_mod( 'streamium_enable_premium' )) :
    
    ?> 
    
        <ul id="repeatable-fieldset-one" width="100%">
    
    <?php

        if ( $repeatable_fields ) :

        foreach ( streamGroupSeasons($repeatable_fields,'seasons') as $seasons ) {

            foreach ( $seasons as $field ) {
            
    ?>
        <li class="streamium-repeater-list">
            <div class="streamium-repeater-left">
                <p>
                    <label>Video Image</label>
                    <input type="hidden" class="widefat" name="thumbnails[]" value="<?php if($field['thumbnails'] != '') echo esc_attr( $field['thumbnails'] ); ?>" />
                    <img src="<?php if(isset($field['thumbnails']) && $field['thumbnails'] != '') echo esc_attr( $field['thumbnails'] ); ?>" />
                    <input class="streamium_upl_button button" type="button" value="Upload Image" />
                </p> 
            </div>
            <div class="streamium-repeater-right">
                <p>
                    <label>Video Season</label>
                    <select class="widefat" tabindex="1" name="seasons[]">
                        <option value="<?php echo $field['seasons']; ?>"><?php echo $field['seasons']; ?></option>
                    </select>
                </p>
                <p>
                    <label>Video List Position</label>
                    <input type="text" class="widefat" name="positions[]" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="<?php if(isset($field['positions']) && $field['positions'] != '') echo esc_attr( $field['positions'] ); ?>" />
                </p>
                <p>
                    <label>S3Bubble AWS Video</label>
                    <select class="streamium-theme-episode-select chosen-select" tabindex="1" name="codes[]">
                        <option value="<?php echo $field['codes']; ?>">Select Video <?php echo $field['codes']; ?></option>
                    </select>
                </p>
                <p>
                    <label>Alternative Service Url (This will overwrite the S3Bubble AWS video)</label>
                    <input type="text" class="widefat" name="service[]" value="<?php if(isset($field['service']) && $field['service'] != '') echo esc_attr( $field['service'] ); ?>" placeholder="Enter a full vimeo or youtube url" />
                </p>
                <p>
                    <label>Video Title</label>
                    <input type="text" class="widefat" name="titles[]" value="<?php if(isset($field['titles']) && $field['titles'] != '') echo esc_attr( $field['titles'] ); ?>" />
                </p>
                <p>
                    <label>Video Description</label>
                    <textarea rows="4" cols="50" class="widefat" name="descriptions[]" value=""><?php if (isset($field['descriptions']) && $field['descriptions'] != '') echo esc_attr( $field['descriptions'] ); else echo ''; ?></textarea>
                </p>
                <a class="button streamium-repeater-remove-row" href="#">Remove</a>
            </div>
        </li>
    <?php } } else : ?>
        <li class="streamium-repeater-list">
            <div class="streamium-repeater-left">
                <p>
                    <label>Video Image</label>
                    <input type="hidden" class="widefat" name="thumbnails[]" />
                    <img src="http://placehold.it/260x146" />
                    <input class="streamium_upl_button button" type="button" value="Upload Image" />
                </p> 
            </div>
            <div class="streamium-repeater-right">
                <p>
                    <label>Video Season</label>
                    <select class="widefat" tabindex="1" name="seasons[]">
                        <option value="1">Season 1</option>
                        <option value="2">Season 2</option>
                        <option value="3">Season 3</option>
                        <option value="4">Season 4</option>
                        <option value="5">Season 5</option>
                        <option value="6">Season 6</option>
                        <option value="7">Season 7</option>
                        <option value="8">Season 8</option>
                        <option value="9">Season 9</option>
                        <option value="10">Season 10</option>
                        <option value="11">Season 11</option>
                        <option value="12">Season 12</option>
                    </select>
                </p>
                <p>
                    <label>Video List Position</label>
                    <input type="text" class="widefat" name="positions[]" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="1" />
                </p>
                <p>
                    <label>Video Code</label>
                    <select class="streamium-theme-episode-select chosen-select" style="width: 50px !important;" tabindex="1" name="codes[]"></select>
                </p>
                <p>
                    <label>Alternative Service Url (This will overwrite the S3Bubble AWS video)</label>
                    <input type="text" class="widefat" name="service[]" placeholder="Enter a full vimeo or youtube url" />
                </p>
                <p> 
                    <label>Video Title</label>
                    <input type="text" class="widefat" name="titles[]" placeholder="Enter video title" />
                </p>
                <p>
                    <label>Video Description</label>
                    <textarea rows="4" cols="50" class="widefat" name="descriptions[]" placeholder="Enter video description"></textarea>
                </p>
                <a class="button streamium-repeater-remove-row" href="#">Remove</a>
            </div>
        </li>
    <?php endif; ?>
    
    </ul> 
    <div class="streamium-repeater-footer">
        <a id="streamium-add-repeater-row" class="button add-program-row button-primary" href="#">Add another</a>
    </div>

    <?php else : ?>
          
        <div class='streamium-current-url-info'>This is only available with the Premium package. <a href="https://s3bubble.com/pricing/" target="_blank">Upgrade</a></div>
          
    <?php endif; 

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
    
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );

    ?>
        <p class="streamium-meta-box-wrapper">
            <label>
                <input type="checkbox" name="streamium_slider_featured_checkbox_value" id="streamium_slider_featured_checkbox_value" value="yes" <?php if ( isset ( $meta['streamium_slider_featured_checkbox_value'] ) ) checked( $meta['streamium_slider_featured_checkbox_value'][0], 'yes' ); ?> />
                <?php esc_attr_e( 'Show in the main feature slider', 'streamium' ); ?>
            </label>
        </p>
    <?php

}

/**
 * Setup live stream meta
 *
 * @return null
 * @author  @sameast
 */
function streamium_meta_box_live_streams() {
  
    global $post;
    $values = get_post_custom( $post->ID );
    $text = isset( $values['streamium_live_stream_meta_box_text'] ) ? $values['streamium_live_stream_meta_box_text'][0] : '';
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );

    ?>
    <p class="streamium-meta-box-wrapper">
        
        <select class="streamium-theme-live-stream-select-group chosen-select" tabindex="1" name="streamium_live_stream_meta_box_text" id="streamium_live_stream_meta_box_text">
                <option value="<?php echo $text; ?>">Select Stream</option>
                <option value="">Remove Current Stream</option>
            </select>

        <?php echo !empty($text) ? "<div class='streamium-current-url'>Main live stream: " . $text . "</div>" : "<div class='streamium-current-url-info'>No stream selected.</div>"; ?>

    </p>

    <?php 

}

/**
 * Optional extra meta
 *
 * @return null
 * @author  @sameast
 */
function streamium_meta_box_extra_meta() {
  
    global $post;
    $values = get_post_custom( $post->ID );
    $text = isset( $values['streamium_extra_meta_meta_box_text'] ) ? $values['streamium_extra_meta_meta_box_text'][0] : '';
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );
    ?>
    <p class="streamium-meta-box-wrapper">

        <input type="text" name="streamium_extra_meta_meta_box_text" class="widefat" id="streamium_extra_meta_meta_box_text" value="<?php echo $text; ?>" />

    </p>

    <?php 

}


/**
 * Saves the meta box content
 *
 * @return null
 * @author  @sameast
 */
function streamium_post_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['streamium_meta_box_movie_nonce'] ) || !wp_verify_nonce( $_POST['streamium_meta_box_movie_nonce'], 'streamium_meta_box_movie' ) ) return;
     
    // if our current user can't edit this post, bail
    if ( ! current_user_can( 'edit_posts' ) ) return;
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['s3bubble_video_code_meta_box_text'] ) ){

      update_post_meta( $post_id, 's3bubble_video_code_meta_box_text', $_POST['s3bubble_video_code_meta_box_text'] );
      
    }

    // Save the trailer or preview
    if(get_theme_mod( 'streamium_enable_premium' )){ 
    
        if( isset( $_POST['streamium_video_trailer_meta_box_text'] ) ){

            update_post_meta( $post_id, 'streamium_video_trailer_meta_box_text', $_POST['streamium_video_trailer_meta_box_text'] );

        }

        if( isset( $_POST['s3bubble_video_trailer_youtube_code_meta_box_text'] ) ){

            update_post_meta( $post_id, 's3bubble_video_trailer_youtube_code_meta_box_text', $_POST['s3bubble_video_trailer_youtube_code_meta_box_text'] );

        }

        if( isset( $_POST['s3bubble_video_trailer_button_text_meta_box_text'] ) ){

            update_post_meta( $post_id, 's3bubble_video_trailer_button_text_meta_box_text', $_POST['s3bubble_video_trailer_button_text_meta_box_text'] );

        }

    }    

    // Save the featured video
    if( isset( $_POST['streamium_featured_video_meta_box_text'] ) ){

      if(get_theme_mod( 'streamium_enable_premium' )){ 
        
        update_post_meta( $post_id, 'streamium_featured_video_meta_box_text', $_POST['streamium_featured_video_meta_box_text'] );

      }else{
        
        update_post_meta( $post_id, 'streamium_featured_video_meta_box_text', $_POST['streamium_featured_video_meta_box_text'] );

      }
    }

    // Save the featured video
    if( isset( $_POST['streamium_live_stream_meta_box_text'] ) ){

      if(get_theme_mod( 'streamium_enable_premium' )){ 
        
        update_post_meta( $post_id, 'streamium_live_stream_meta_box_text', $_POST['streamium_live_stream_meta_box_text'] );

      }else{
        
        update_post_meta( $post_id, 'streamium_live_stream_meta_box_text', $_POST['streamium_live_stream_meta_box_text'] );

      }
    }

    // Get the old values
    $old = get_post_meta($post_id, 'repeatable_fields', true);
    $new = array();
    if(isset($_POST['thumbnails']) && isset($_POST['seasons']) && isset($_POST['positions']) && isset($_POST['titles']) && isset($_POST['codes']) && isset($_POST['descriptions'])){
    
        $thumbnails   = isset($_POST['thumbnails']) ? $_POST['thumbnails'] : "";
        $seasons      = isset($_POST['seasons']) ? $_POST['seasons'] : "";
        $positions    = isset($_POST['positions']) ? $_POST['positions'] : "";
        $titles       = isset($_POST['titles']) ? $_POST['titles'] : "";
        $codes        = isset($_POST['codes']) ? $_POST['codes'] : "";
        $service        = isset($_POST['service']) ? $_POST['service'] : "";
        $descriptions = isset($_POST['descriptions']) ? $_POST['descriptions'] : "";
        
        $count = count( $titles );
        
        for ( $i = 0; $i < $count; $i++ ) {
          if ( $thumbnails[$i] != '' && $titles[$i] != '' && $seasons[$i] != '' && $positions[$i] != '' && $codes[$i] != '' && $descriptions[$i] != '') :

            $new[$i]['thumbnails'] = esc_url(stripslashes( strip_tags( $thumbnails[$i] )));
            $new[$i]['seasons'] = trim(stripslashes( strip_tags( $seasons[$i] ) ));
            $new[$i]['positions'] = trim(stripslashes( strip_tags( $positions[$i] ) ));
            $new[$i]['titles'] = trim(stripslashes( strip_tags( $titles[$i] ) ));
            $new[$i]['codes'] = $codes[$i];
            $new[$i]['service'] = $service[$i];
            $new[$i]['descriptions'] = trim(stripslashes( $descriptions[$i] ));

          endif;
        }

        // Check and save repeater fields
        if ( !empty( $new ) && $new != $old ) {
        
          update_post_meta( $post_id, 'repeatable_fields', $new );
        
        } elseif ( empty($new) && $old ) {
        
          delete_post_meta( $post_id, 'repeatable_fields', $old );
        
        }

    }

    // Save the checkbox
    if( isset( $_POST[ 'streamium_slider_featured_checkbox_value' ] ) ) {

        update_post_meta( $post_id, 'streamium_slider_featured_checkbox_value', 'yes' );
    
    } else {
    
        update_post_meta( $post_id, 'streamium_slider_featured_checkbox_value', '' );
    
    }

    // Save extra meta
    if( isset( $_POST['streamium_extra_meta_meta_box_text'] ) ){
        
        update_post_meta( $post_id, 'streamium_extra_meta_meta_box_text', $_POST['streamium_extra_meta_meta_box_text'] );

    }

    // Save youtube code
    if( isset( $_POST['s3bubble_video_youtube_code_meta_box_text'] ) ){
        
        update_post_meta( $post_id, 's3bubble_video_youtube_code_meta_box_text', $_POST['s3bubble_video_youtube_code_meta_box_text'] );

    }
    

}

add_action( 'save_post', 'streamium_post_meta_box_save', 10, 3 );

/**
 * Get the websites domain needed for connected websites
 *
 * @return null
 * @author  @sameast
 */
function streamium_website_connection(){

    if(isset($_SERVER['HTTP_HOST'])){

        $host = preg_replace('#^www\.(.+\.)#i', '$1', $_SERVER['HTTP_HOST']); // remove the www
        update_option("streamium_connected_website", $host);

    }

}

add_action( 'init', 'streamium_website_connection' );
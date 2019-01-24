<?php

/**
 * Adds meta boxes within a post
 *
 * @return null
 * @author  @sameast
 */
function streamium_video_code_meta_box_add(){

    add_meta_box( 'streamium-meta-box-movie', 'Main Video', 'streamium_meta_box_movie', streamium_global_meta(), 'side', 'high' );

    add_meta_box( 'streamium-meta-box-trailer', 'Video Trailer/Preview', 'streamium_meta_box_trailer', streamium_global_meta(), 'side', 'high' );

    add_meta_box( 'streamium-meta-box-bgvideo', 'Main Slider BG Video', 'streamium_meta_box_bgvideo', streamium_global_meta(), 'side', 'high' );

    add_meta_box( 'streamium-meta-box-main-slider', 'Main Slider Video', 'streamium_meta_box_main_slider', streamium_global_meta(), 'side', 'high' );

    // Repeater for premium
    add_meta_box( 'streamium-repeatable-fields', 'Multiple Videos - Seasons/Episodes', 'streamium_repeatable_meta_box_display', streamium_global_meta(), 'normal', 'high');

    // Roku direct publisher
    add_meta_box( 'streamium-meta-box-roku', 'Roku Direct Publisher Integration', 'streamium_meta_box_roku', streamium_global_meta(), 'normal', 'high' );

    // Release date extra meta 
    add_meta_box( 'streamium-meta-box-release-date', 'Override Release Date', 'streamium_meta_box_release_date', streamium_global_meta(), 'side', 'high' );

    // Global extra meta
    add_meta_box( 'streamium-meta-box-extra-meta', 'Extra Video Tile Meta', 'streamium_meta_box_extra_meta', streamium_global_meta(), 'side', 'high' );

    // Video Ratings meta
    add_meta_box( 'streamium-meta-box-ratings', 'Set Video Rating (PG|R|G|PG-13|NC-17)', 'streamium_meta_box_ratings', streamium_global_meta(), 'side', 'high' );
       

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
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );

    ?>
    <p class="streamium-meta-box-wrapper">
        <select class="streamium-theme-main-video-select-group chosen-select" tabindex="1" name="s3bubble_video_code_meta_box_text" id="s3bubble_video_code_meta_box_text">
            <option value="<?php echo $text; ?>"><?php echo (empty($text)) ? 'Select Main Video' : $text; ?></option>
            <option value="">Remove Current Video</option>
        </select>
    </p>

    <?php    

}


/**
 * Sets up the meta box content for Roku
 *
 * @return null
 * @author  @sameast
 */
function streamium_meta_box_roku(){

    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
    $code = isset( $values['s3bubble_video_code_meta_box_text'] ) ? $values['s3bubble_video_code_meta_box_text'][0] : '';
    $url = isset( $values['s3bubble_roku_url_meta_box_text'] ) ? $values['s3bubble_roku_url_meta_box_text'][0] : '';
    $quality = isset( $values['s3bubble_roku_quality_meta_box_text'] ) ? $values['s3bubble_roku_quality_meta_box_text'][0] : '';
    $videoType = isset( $values['s3bubble_roku_videotype_meta_box_text'] ) ? $values['s3bubble_roku_videotype_meta_box_text'][0] : '';
    $duration = isset( $values['s3bubble_roku_duration_meta_box_text'] ) ? $values['s3bubble_roku_duration_meta_box_text'][0] : '';

    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );

    ?>
    <p class="streamium-meta-box-wrapper">
        <label>Video Url</label>
        <input type="url" name="s3bubble_roku_url_meta_box_text" class="widefat" id="s3bubble_roku_url_meta_box_text" value="<?php echo $url; ?>" placeholder="Enter video url" />
    </p>
    <p class="streamium-meta-box-wrapper">
        <label>Video Quality</label>
        <select tabindex="1" name="s3bubble_roku_quality_meta_box_text" id="s3bubble_roku_quality_meta_box_text">
            <option value="<?php echo $quality; ?>"><?php echo (empty($quality)) ? 'Select Video Quality' : $quality; ?></option>
            <option value="HD">HD – 720p</option>
            <option value="FHD">FHD – 1080p</option>
            <option value="UHD">UHD – 4K</option>
        </select>
    </p>
     <p class="streamium-meta-box-wrapper">
        <label>Video Type</label>
        <select tabindex="1" name="s3bubble_roku_videotype_meta_box_text" id="s3bubble_roku_videotype_meta_box_text">
            <option value="<?php echo $videoType; ?>"><?php echo (empty($videoType)) ? 'Select Video Type' : $videoType; ?></option>
            <option value="HLS">HLS</option>
            <option value="SMOOTH">SMOOTH</option>
            <option value="DASH">DASH</option>
            <option value="MP4">MP4</option>
            <option value="MOV">MOV</option>
            <option value="M4V">M4V</option>
        </select>
    </p>
    <p class="streamium-meta-box-wrapper">
        <label>Video Duration (Runtime in seconds)</label>
        <input type="text" name="s3bubble_roku_duration_meta_box_text" class="widefat" id="s3bubble_roku_duration_meta_box_text" value="<?php echo $duration; ?>" placeholder="Enter video duration" />
    </p>
    <p class="streamium-meta-box-wrapper">
        <a id="streamium-add-roku-data" class="button button-primary button-large" href="#" data-pid="<?php echo $post->ID; ?>">Generate Roku Data</a>
    </p>
    <p class="streamium-meta-box-wrapper">
        Make sure you update your Roku feed here. 
        <a href="https://developer.roku.com/en-gb/developer" target="_blank">https://developer.roku.com/en-gb/developer</a>
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
    $button = isset( $values['s3bubble_video_trailer_button_text_meta_box_text'] ) ? $values['s3bubble_video_trailer_button_text_meta_box_text'][0] : 'Watch Trailer';

    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );
    ?>

    <p class="streamium-meta-box-wrapper">
        <select class="streamium-theme-video-trailer-select-group chosen-select" tabindex="1" name="streamium_video_trailer_meta_box_text" id="streamium_video_trailer_meta_box_text">
            <option value="<?php echo $text; ?>"><?php echo (empty($text)) ? 'Select Video Trailer' : $text; ?></option>
            <option value="">Remove Current Video</option>
        </select>
    </p>

    <p class="streamium-meta-box-wrapper">
        <label>Change Button Text</label>
        <input type="text" name="s3bubble_video_trailer_button_text_meta_box_text" class="widefat" id="s3bubble_video_trailer_button_text_meta_box_text" value="<?php echo $button; ?>" placeholder="Enter button text" />
    </p>

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
        
        <select class="streamium-theme-featured-video-select-group chosen-select" tabindex="1" name="streamium_featured_video_meta_box_text" id="streamium_featured_video_meta_box_text">
            <option value="<?php echo $text; ?>"><?php echo (empty($text)) ? 'Select Background Video' : $text; ?></option>
            <option value="">Remove Current Video</option>
        </select>

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

    ?>
    
    <ul id="repeatable-fieldset-one" width="100%">
    
    <?php

        if ( $repeatable_fields ) {

        foreach ( streamGroupSeasons($repeatable_fields,'seasons') as $seasons ) {

            foreach ( $seasons as $key => $field ) {

                // Roku data
                $roku_url      = (isset($field['roku_url']) && $field['roku_url'] != '') ? esc_attr($field['roku_url']) : '';
                $roku_quality  = (isset($field['roku_quality']) && $field['roku_quality'] != '') ? esc_attr( $field['roku_quality']) : '';
                $roku_type     = (isset($field['roku_type']) && $field['roku_type'] != '') ? esc_attr( $field['roku_type']) : '';
                $roku_duration = (isset($field['roku_duration']) && $field['roku_duration'] != '') ? esc_attr( $field['roku_duration']) : '';

                $unique = $field['seasons'] . $key;
            
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
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
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
                    <label>Video Title</label>
                    <input type="text" class="widefat" name="titles[]" value="<?php if(isset($field['titles']) && $field['titles'] != '') echo esc_attr( $field['titles'] ); ?>" />
                </p>
                <p>
                    <label>Video Description</label>
                    <textarea rows="4" cols="50" class="widefat" name="descriptions[]" value=""><?php if (isset($field['descriptions']) && $field['descriptions'] != '') echo esc_attr( $field['descriptions'] ); else echo ''; ?></textarea>
                </p>
                <a class="button button-large streamium-repeater-remove-row" href="#" data-pid="<?php echo $post->ID; ?>" data-index="<?php echo $unique; ?>">Remove</a>

                <a id="streamium-repeater-add-roku-data" class="button button-primary button-large" href="#">Add Roku Data</a>

                <div class="streamium-meta-box-series-roku-wrapper">
                    <h4>Generate or Update Roku data below</h4>
                    <p>
                        <label>Video Url</label>
                        <input type="url" name="roku_url[]" class="widefat" id="series_roku_url_<?php echo $unique; ?>" value="<?php echo $roku_url; ?>" placeholder="Enter video url" />
                    </p>
                    <p>
                        <label>Video Quality</label>
                        <select tabindex="1" name="roku_quality[]" id="series_roku_quality_<?php echo $unique; ?>">
                            <option value="<?php echo $roku_quality; ?>"><?php echo (empty($roku_quality)) ? 'Select Video Quality' : $roku_quality; ?></option>
                            <option value="HD">HD – 720p</option>
                            <option value="FHD">FHD – 1080p</option>
                            <option value="UHD">UHD – 4K</option>
                        </select>
                    </p>
                     <p>
                        <label>Video Type</label>
                        <select tabindex="1" name="roku_type[]" id="series_roku_type_<?php echo $unique; ?>">
                            <option value="<?php echo $roku_type; ?>"><?php echo (empty($roku_type)) ? 'Select Video Type' : $roku_type; ?></option>
                            <option value="HLS">HLS</option>
                            <option value="SMOOTH">SMOOTH</option>
                            <option value="DASH">DASH</option>
                            <option value="MP4">MP4</option>
                            <option value="MOV">MOV</option>
                            <option value="M4V">M4V</option>
                        </select>
                    </p>
                    <p>
                        <label>Video Duration (Runtime in seconds)</label>
                        <input type="text" name="roku_duration[]" class="widefat" id="series_roku_duration_<?php echo $unique; ?>" value="<?php echo $roku_duration; ?>" placeholder="Enter video duration" />
                    </p>

                    <a id="streamium-repeater-generate-roku-data" class="button button-primary button-large" href="#" data-key="<?php echo $unique; ?>" data-code="<?php echo (empty($field['codes'])) ? '' : $field['codes']; ?>">Generate Roku Data</a>

                </div>

            </div>
        </li>
    <?php 
        
            } 
        } 

    }

    ?>
    
    </ul> 
    <div class="streamium-repeater-footer">
        <a id="streamium-add-repeater-row" class="button add-program-row button-primary" href="#">Add Series Video</a>
    </div>

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
 * Add video ratings PG etc
 *
 * @return null
 * @author  @sameast
 */
function streamium_meta_box_ratings() {
  
    global $post;
    $values = get_post_custom( $post->ID );
    $text = isset( $values['streamium_ratings_meta_box_text'] ) ? $values['streamium_ratings_meta_box_text'][0] : '';
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );
    ?>
    <p class="streamium-meta-box-wrapper">

        <input type="text" name="streamium_ratings_meta_box_text" class="widefat" id="streamium_ratings_meta_box_text" value="<?php echo $text; ?>" />

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
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );
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

    // ROKU VIDEO DATA
    if( isset( $_POST['s3bubble_roku_url_meta_box_text'] ) ){

      update_post_meta( $post_id, 's3bubble_roku_url_meta_box_text', $_POST['s3bubble_roku_url_meta_box_text'] );
      
    }
    if( isset( $_POST['s3bubble_roku_quality_meta_box_text'] ) ){

      update_post_meta( $post_id, 's3bubble_roku_quality_meta_box_text', $_POST['s3bubble_roku_quality_meta_box_text'] );
      
    }
    if( isset( $_POST['s3bubble_roku_videotype_meta_box_text'] ) ){

      update_post_meta( $post_id, 's3bubble_roku_videotype_meta_box_text', $_POST['s3bubble_roku_videotype_meta_box_text'] );
      
    }
    if( isset( $_POST['s3bubble_roku_duration_meta_box_text'] ) ){

      update_post_meta( $post_id, 's3bubble_roku_duration_meta_box_text', $_POST['s3bubble_roku_duration_meta_box_text'] );
      
    }

    // Save the trailer or preview
    if( isset( $_POST['streamium_video_trailer_meta_box_text'] ) ){

        update_post_meta( $post_id, 'streamium_video_trailer_meta_box_text', $_POST['streamium_video_trailer_meta_box_text'] );

    }

    if( isset( $_POST['s3bubble_video_trailer_youtube_code_meta_box_text'] ) ){

        update_post_meta( $post_id, 's3bubble_video_trailer_youtube_code_meta_box_text', $_POST['s3bubble_video_trailer_youtube_code_meta_box_text'] );

    }

    if( isset( $_POST['s3bubble_video_trailer_button_text_meta_box_text'] ) ){

        update_post_meta( $post_id, 's3bubble_video_trailer_button_text_meta_box_text', $_POST['s3bubble_video_trailer_button_text_meta_box_text'] );

    }    

    // Save the featured video
    if( isset( $_POST['streamium_featured_video_meta_box_text'] ) ){

        update_post_meta( $post_id, 'streamium_featured_video_meta_box_text', $_POST['    streamium_featured_video_meta_box_text'] );

    }

    // Save the featured video
    if( isset( $_POST['streamium_live_stream_meta_box_text'] ) ){

        update_post_meta( $post_id, 'streamium_live_stream_meta_box_text', $_POST['streamium_live_stream_meta_box_text'] );
        
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
        $descriptions = isset($_POST['descriptions']) ? $_POST['descriptions'] : "";
        $roku_url     = isset($_POST['roku_url']) ? $_POST['roku_url'] : "";
        $roku_quality = isset($_POST['roku_quality']) ? $_POST['roku_quality'] : "";
        $roku_type    = isset($_POST['roku_type']) ? $_POST['roku_type'] : "";
        $roku_duration= isset($_POST['roku_duration']) ? $_POST['roku_duration'] : "";
        
        $count = count( $titles );
        
        for ( $i = 0; $i < $count; $i++ ) {
          if ( $thumbnails[$i] != '' && $titles[$i] != '' && $seasons[$i] != '' && $positions[$i] != '' && $codes[$i] != '' && $descriptions[$i] != '') :

            $new[$i]['thumbnails'] = esc_url(stripslashes( strip_tags( $thumbnails[$i] )));
            $new[$i]['seasons'] = trim(stripslashes( strip_tags( $seasons[$i] ) ));
            $new[$i]['positions'] = trim(stripslashes( strip_tags( $positions[$i] ) ));
            $new[$i]['titles'] = trim(stripslashes( strip_tags( $titles[$i] ) ));
            $new[$i]['codes'] = $codes[$i];
            $new[$i]['descriptions'] = trim(stripslashes( $descriptions[$i] ));

            // Roku data if present
            $new[$i]['roku_url']      = trim(stripslashes( $roku_url[$i] ));
            $new[$i]['roku_quality']  = trim(stripslashes( $roku_quality[$i] ));
            $new[$i]['roku_type']     = trim(stripslashes( $roku_type[$i] ));
            $new[$i]['roku_duration'] = trim(stripslashes( $roku_duration[$i] ));

          endif;
        }

        // Check and save repeater fields
        if ( !empty( $new ) && $new != $old ) {
        
          update_post_meta( $post_id, 'repeatable_fields', $new );
        
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

    // Save video ratings
    if( isset( $_POST['streamium_ratings_meta_box_text'] ) ){
        
        update_post_meta( $post_id, 'streamium_ratings_meta_box_text', $_POST['streamium_ratings_meta_box_text'] );

    }

    // Save release date meta
    if( isset( $_POST['streamium_release_date_meta_box_text'] ) ){
        
        update_post_meta( $post_id, 'streamium_release_date_meta_box_text', $_POST['streamium_release_date_meta_box_text'] );

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
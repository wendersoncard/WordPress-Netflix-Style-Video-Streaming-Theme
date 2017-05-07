<?php

/**
 * Adds meta boxes within a post
 *
 * @return null
 * @author  @sameast
 */
function streamium_video_code_meta_box_add(){

    add_meta_box( 'streamium-meta-box-movie', 'Main Video', 'streamium_meta_box_movie', 'post', 'side', 'high' );
    add_meta_box( 'streamium-meta-box-trailer', 'Video Trailer', 'streamium_meta_box_trailer', 'post', 'side', 'high' );
    add_meta_box( 'streamium-meta-box-bgvideo', 'Featured BG Video', 'streamium_meta_box_bgvideo', 'post', 'side', 'high' );

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
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );
    ?>
    <p>
        <select class="streamium-theme-main-video-select-group chosen-select" tabindex="1" name="s3bubble_video_code_meta_box_text" id="s3bubble_video_code_meta_box_text">
          <option value="<?php echo $text; ?>">Select Main Video</option>
          <option value="">Remove Current Video</option>
        </select>
        <?php 

          if(get_theme_mod( 'streamium_enable_premium' )){

            echo !empty($text) ? "<div class='streamium-current-url'>Premium video code: " . $text . "</div>" : "<div class='streamium-current-url-info'>No video selected. Please select a video to show as your main movie.</div>";
          
          }else{
          
            echo !empty($text) ? "<div class='streamium-current-url'>Your current url is set to: " . $text . "</div>" : "";
          
          }

        ?>
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
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'streamium_meta_box_movie', 'streamium_meta_box_movie_nonce' );
    ?>
    <p>

        <?php if(get_theme_mod( 'streamium_enable_premium' )) : ?>

          <select class="streamium-theme-video-trailer-select-group chosen-select" tabindex="1" name="streamium_video_trailer_meta_box_text" id="streamium_video_trailer_meta_box_text">
            <option value="<?php echo $text; ?>">Select Video Trailer</option>
            <option value="">Remove Current Video</option>
          </select>
          <?php echo !empty($text) ? "<div class='streamium-current-url'>Premium video code: " . $text . "</div>" : "<div class='streamium-current-url-info'>No video selected. Select a trailer to allow your users to preview a video first via the watch trailer button.</div>"; ?>
          
        <?php else : ?>
          
          <div class='streamium-current-url-info'>This is only available with the Premium package. <a href="https://s3bubble.com/pricing/" target="_blank">Upgrade</a></div>
          
        <?php endif; ?>

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
    <p>
        <?php if(get_theme_mod( 'streamium_enable_premium' )) : ?>

          <select class="streamium-theme-featured-video-select-group chosen-select" tabindex="1" name="streamium_featured_video_meta_box_text" id="streamium_featured_video_meta_box_text">
            <option value="<?php echo $text; ?>">Select Background Video</option>
            <option value="">Remove Current Video</option>
          </select>

          <?php echo !empty($text) ? "<div class='streamium-current-url'>Premium video code: " . $text . "</div>" : "<div class='streamium-current-url-info'>No video selected. This will display a background video on the homepage slider if your post is set to Sticky.</div>"; ?>
          
        <?php else : ?>
          
          <div class='streamium-current-url-info'>This is only available with the Premium package. <a href="https://s3bubble.com/pricing/" target="_blank">Upgrade</a></div>
          
        <?php endif; ?>

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
    if( !current_user_can( 'edit_post' ) ) return;
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['s3bubble_video_code_meta_box_text'] ) ){

      if(get_theme_mod( 'streamium_enable_premium' )){ 
        
        update_post_meta( $post_id, 's3bubble_video_code_meta_box_text', $_POST['s3bubble_video_code_meta_box_text'] );

      }else{
        
        if (strpos($_POST['s3bubble_video_code_meta_box_text'],'s3bubble') !== false) {
        
          update_post_meta( $post_id, 's3bubble_video_code_meta_box_text', $_POST['s3bubble_video_code_meta_box_text'] );
        
        }

      }
      
    }

    // Save the trailer
    if( isset( $_POST['streamium_video_trailer_meta_box_text'] ) ){

      if(get_theme_mod( 'streamium_enable_premium' )){ 
        
        update_post_meta( $post_id, 'streamium_video_trailer_meta_box_text', $_POST['streamium_video_trailer_meta_box_text'] );

      }

    }

    // Save the featured video
    if( isset( $_POST['streamium_featured_video_meta_box_text'] ) ){

      if(get_theme_mod( 'streamium_enable_premium' )){ 
        
        update_post_meta( $post_id, 'streamium_featured_video_meta_box_text', $_POST['streamium_featured_video_meta_box_text'] );

      }else{
        
        if (strpos($_POST['streamium_featured_video_meta_box_text'],'s3bubble') !== false) {
        
          update_post_meta( $post_id, 'streamium_featured_video_meta_box_text', $_POST['streamium_featured_video_meta_box_text'] );
        
        }

      }
      

    }
        
}

add_action( 'save_post', 'streamium_post_meta_box_save' );

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
<?php

/**
 * Adds a meta box to the single post
 *
 * @return bool
 * @author  @sameast
 */
function streamium_video_code_meta_box_add(){

    add_meta_box( 's3bubble-meta-video-id', 'S3Bubble Video', 'streamium_meta_video_id', 'post', 'side', 'high' );

}

add_action( 'add_meta_boxes', 'streamium_video_code_meta_box_add' );


/**
 * Video is meta box content
 *
 * @return bool
 * @author  @sameast
 */
function streamium_meta_video_id(){

    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
    $text = isset( $values['streamium_video_code_meta_box_text'] ) ? $values['streamium_video_code_meta_box_text'] : '';
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'streamium_video_code_meta_box_nonce', 'meta_box_nonce' );
    ?>
    <p>
        <span class="streamium-theme-select-group"></span>
        <?php 

          if(get_theme_mod( 'streamium_enable_premium' )){

            echo isset($text[0]) ? "<div class='streamium-current-url'>Premium video code: " . $text[0] . "</div>" : "";
          
          }else{
          
            echo isset($text[0]) ? "<div class='streamium-current-url'>Your current url is set to: " . $text[0] . "</div>" : "";
          
          }

        ?>
    </p>

    <?php    

}

/**
 * Save the meta box data
 *
 * @return bool
 * @author  @sameast
 */
function streamium_video_code_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'streamium_video_code_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['streamium_video_code_meta_box_text'] ) ){

      if(get_theme_mod( 'streamium_enable_premium' )){
        
        update_post_meta( $post_id, 'streamium_video_code_meta_box_text', $_POST['streamium_video_code_meta_box_text'] );

      }else{
        
        if (strpos($_POST['streamium_video_code_meta_box_text'],'s3bubble') !== false) {
        
          update_post_meta( $post_id, 'streamium_video_code_meta_box_text', $_POST['streamium_video_code_meta_box_text'] );
        
        }

      }
      

    }
        
}

add_action( 'save_post', 'streamium_video_code_meta_box_save' );

/*
* Include the scripts needed for the meta box
* @author sameast
* @none
*/ 
function streamium_meta_box_admin_scripts(){
  
  $streamium_connected_website = get_option("streamium_connected_website");
  wp_enqueue_style( 'streamium-theme-chosen-css', get_template_directory_uri() . '/dist/css/chosen.min.css', array() );
  wp_enqueue_style( 'streamium-theme-admin-css', get_template_directory_uri() . '/dist/css/admin.min.css', array() );
  wp_enqueue_script( 'streamium-theme-chosen-js', get_template_directory_uri() . '/dist/js/chosen.jquery.min.js', array( 'jquery' ), true );
  wp_enqueue_script( 'streamium-theme-admin-js', get_template_directory_uri() . '/dist/js/admin.min.js', array( 'jquery' ), true );
  wp_localize_script('streamium-theme-admin-js', 'streamium_meta_object', array( 
    's3website' => (!empty($streamium_connected_website) ? $streamium_connected_website : ""),
    'streamiumPremium' => get_theme_mod( 'streamium_enable_premium' )
  ));

}

add_action( 'admin_enqueue_scripts', 'streamium_meta_box_admin_scripts' );

/*
 * Create a connected webiste option
 * @author sameast
 * @params none
 */ 
function streamium_website_connection(){

    if(isset($_SERVER['HTTP_HOST'])){

        $host = preg_replace('#^www\.(.+\.)#i', '$1', $_SERVER['HTTP_HOST']); // remove the www
        update_option("streamium_connected_website", $host);

    }

}

add_action( 'init', 'streamium_website_connection' );
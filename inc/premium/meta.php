<?php

/**
 * Add premium meta functionality 
 *
 * @return null
 * @author  @sameast
 */
function streamium_premium_meta_boxes(){

	// SLIDER BACKGROUND VIDEO::
    add_meta_box( 'streamium-premium-meta-box-background-video', 'Slider Background Video', 'streamium_premium_meta_box_background_video', 'content', 'side', 'high' );

    // VIDEO TRAILERS::
    add_meta_box( 'streamium-premium-meta-box-trailer', 'Video Trailer/Preview', 'streamium_premium_meta_box_trailer', 'content', 'side', 'high' );

    // EXTRA META::
    add_meta_box( 'streamium-premium-meta-box-extra-meta', 'Extra Video Tile Meta', 'streamium_premium_meta_box_extra_meta', 'content', 'side', 'high' );

    // RATINGS::
    add_meta_box( 'streamium-premium-meta-box-ratings', 'Set Video Rating (PG|R|G|PG-13|NC-17)', 'streamium_premium_meta_box_ratings', 'content', 'side', 'high' );

    // EPISODES::
    add_meta_box( 'streamium-premium-meta-box-episodes', 'Seasons', 'streamium_premium_meta_box_episodes', 'content', 'normal', 'high');

    // ROKU::
    add_meta_box( 'streamium-premium-meta-box-roku', 'Roku Direct Publisher Integration', 'streamium_premium_meta_box_roku', 'content', 'normal', 'high' );

    // EPISODES::
    add_meta_box( 'streamium-premium-meta-box-seasons', 'Seasons', 'streamium_premium_meta_box_seasons', 'content', 'side', 'high');

    // WOO::
    add_meta_box( 'streamium-premium-meta-box-woo', 'Woocommerce Product', 'streamium_premium_meta_box_woo', 'content', 'side', 'high');

}

add_action( 'add_meta_boxes', 'streamium_premium_meta_boxes' );


/**
 * Sets up the meta box content for the video background on home slider
 * https://docs.woocommerce.com/wc-apidocs/function-wc_get_product.html
 * @return null
 * @author  @sameast
 */
function streamium_premium_meta_box_woo(){

    // SECURITY::
    wp_nonce_field( 'streamium_premium_meta_security', 'streamium_premium_meta_nonce' );

    $query = new WC_Product_Query( array(
        'limit' => 10,
        'orderby' => 'date',
        'order' => 'DESC',
        'return' => 'ids',
    ) );
    $products = $query->get_products();

    ?>

        <p class="streamium-meta-box-wrapper"> 

            <select tabindex="1" name="streamium_premium_meta_box_woo_product">
                <option value="">Not attached</option>
                <?php foreach ($products as $key => $product) { $post_object = get_post($product); ?>
                    <option value="<?php echo $post_object->ID; ?>"><?php echo $post_object->post_title; ?></option>
                <?php } ?>
            </select>

        </p>

    <?php

}


/**
 * Sets up the meta box content for the video background on home slider
 *
 * @return null
 * @author  @sameast
 */
function streamium_premium_meta_box_seasons(){

    // GLOBALS::
    global $post;
    $originalpost = $post;
    
    // SECURITY::
    wp_nonce_field( 'streamium_premium_meta_security', 'streamium_premium_meta_nonce' );
    
    $values = get_post_custom( $post->ID );
    //$text   = isset( $values['streamium_premium_meta_box_background_video_text'] ) ? $values['streamium_premium_meta_box_background_video_text'][0] : '';

    

    $args = array(
        'posts_per_page' => -1,
        'post_type'      => 'content',
        'post_status'    => 'publish',
        /*'meta_query'     => array(
            array(
                'key'     => 'streamium_meta_box_movie_code',
                'value'   => '',
                'compare' => '='
            )
        )*/
    );

    $loop = new WP_Query($args);

    if ( $loop->have_posts() ) { ?>

        <p class="streamium-meta-box-wrapper"> 

            <select tabindex="1" name="streamium_premium_meta_box_seasons">
                <option value="">Not attached</option>
                <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                    <optgroup label="<?php echo get_the_title(); ?>">

                        <?php 

                            $repeatable_fields = get_post_meta(get_the_ID(), 'repeatable_fields', true); 
                            if(!empty( $repeatable_fields)){
                                foreach ( $repeatable_fields as $season ) { 
                        ?>
                                    <option value="<?php echo get_the_ID(); ?>|<?php echo $season['seasons']; ?>"><?php echo $season['titles']; ?></option>
                        
                        <?php
                                }
                            }
                        ?>
                        
                    </optgroup>
                <?php endwhile; ?>
            </select>

            <div class="streamium-meta-box-alert" role="alert">This will display a background video on the homepage slider</div>

        </p>

    <?php

        $post = $originalpost;
        wp_reset_postdata();

    }  

}


/**
 * Sets up the meta box content for the video background on home slider
 *
 * @return null
 * @author  @sameast
 */
function streamium_premium_meta_box_background_video(){

    // GLOBALS::
    global $post;
    
    // SECURITY::
    wp_nonce_field( 'streamium_premium_meta_security', 'streamium_premium_meta_nonce' );
    
    $values = get_post_custom( $post->ID );
    $text   = isset( $values['streamium_premium_meta_box_background_video_text'] ) ? $values['streamium_premium_meta_box_background_video_text'][0] : '';

    ?>

        <p class="streamium-meta-box-wrapper"> 

            <select class="streamium-premium-meta-box-background-video-select-group chosen-select" tabindex="1" name="streamium_premium_meta_box_background_video_text" id="streamium_premium_meta_box_background_video_text">
                <option value="<?php echo $text; ?>"><?php echo (empty($text)) ? 'Select Background Video' : $text; ?></option>
                <option value="">Remove Current Video</option>
            </select>

            <div class="streamium-meta-box-alert" role="alert">This will display a background video on the homepage slider</div>

        </p>

    <?php    

}

/**
 * Sets up the meta box content for the video trailer
 *
 * @return null
 * @author  @sameast
 */
function streamium_premium_meta_box_trailer(){

    // GLOBALS::
    global $post;
    
    // SECURITY::
    wp_nonce_field( 'streamium_premium_meta_security', 'streamium_premium_meta_nonce' );

    $values = get_post_custom( $post->ID );
    $text   = isset( $values['streamium_premium_meta_box_trailer_code'] ) ? $values['streamium_premium_meta_box_trailer_code'][0] : '';
    $button = isset( $values['streamium_premium_meta_box_trailer_button'] ) ? $values['streamium_premium_meta_box_trailer_button'][0] : 'Watch Trailer';

    ?>

        <p class="streamium-meta-box-wrapper">
            
            <select class="streamium-premium-meta-box-trailer-select-group chosen-select" tabindex="1" name="streamium_premium_meta_box_trailer_code" id="streamium_premium_meta_box_trailer_code">
                <option value="<?php echo $text; ?>"><?php echo (empty($text)) ? 'Select Video Trailer' : $text; ?></option>
                <option value="">Remove Current Video</option>
            </select>

        </p>

        <p class="streamium-meta-box-wrapper">

            <label>Change Button Text</label>
            <input type="text" name="streamium_premium_meta_box_trailer_button" class="widefat" id="streamium_premium_meta_box_trailer_button" value="<?php echo $button; ?>" placeholder="Enter button text" />

        </p>

    <?php  

}

/**
 * Optional extra meta
 *
 * @return null
 * @author  @sameast
 */
function streamium_premium_meta_box_extra_meta() {
  
    // GLOBALS::
    global $post;
    
    // SECURITY::
    wp_nonce_field( 'streamium_premium_meta_security', 'streamium_premium_meta_nonce' );

    $values = get_post_custom( $post->ID );
    $text   = isset( $values['streamium_premium_meta_box_extra_meta_text'] ) ? $values['streamium_premium_meta_box_extra_meta_text'][0] : '';

    ?>

        <p class="streamium-meta-box-wrapper">

            <input type="text" name="streamium_premium_meta_box_extra_meta_text" class="widefat" id="streamium_premium_meta_box_extra_meta_text" value="<?php echo $text; ?>" />

        </p>

    <?php 

}

/**
 * Add video ratings PG etc
 *
 * @return null
 * @author  @sameast
 */
function streamium_premium_meta_box_ratings() {
  
    // GLOBALS::
    global $post;
    
    // SECURITY::
    wp_nonce_field( 'streamium_premium_meta_security', 'streamium_premium_meta_nonce' );

    $values = get_post_custom( $post->ID );
    $text = isset( $values['streamium_premium_meta_box_ratings_text'] ) ? $values['streamium_premium_meta_box_ratings_text'][0] : '';

    ?>

        <p class="streamium-meta-box-wrapper">

            <input type="text" name="streamium_premium_meta_box_ratings_text" class="widefat" id="streamium_premium_meta_box_ratings_text" value="<?php echo $text; ?>" />

        </p>

    <?php 

}

/**
 * Setup custom repeater meta
 *
 * @return null
 * @author  @sameast
 */
function streamium_premium_meta_box_episodes() {

    // GLOBALS::
    global $post;
    
    // SECURITY::
    wp_nonce_field( 'streamium_premium_meta_security', 'streamium_premium_meta_nonce' );

    $repeatable_fields = get_post_meta($post->ID, 'repeatable_fields', true);

    ?> 
    
        <ul id="repeatable-fieldset-one" width="100%">
    
    <?php

        if ( $repeatable_fields ) :

            //echo '<pre>';
            //print_r($repeatable_fields);

        foreach ( streamGroupSeasons($repeatable_fields,'seasons') as $seasons ) {

            foreach ( $seasons as $key => $field ) {
            
    ?>
        <li class="streamium-repeater-list">
            <div class="streamium-repeater-left">
                <p>
                    <label>Season Image</label>
                    <input type="hidden" class="widefat" name="thumbnails[]" value="<?php if($field['thumbnails'] != '') echo esc_attr( $field['thumbnails'] ); ?>" />
                    <img src="<?php if(isset($field['thumbnails']) && $field['thumbnails'] != '') echo esc_attr( $field['thumbnails'] ); ?>" />
                    <input class="streamium_upl_button button" type="button" value="Upload Image" />
                </p> 
            </div>
            <div class="streamium-repeater-right">
                <p>
                    <label>Season ID</label>
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
                    <label>Season Title</label>
                    <input type="text" class="widefat" name="titles[]" value="<?php if(isset($field['titles']) && $field['titles'] != '') echo esc_attr( $field['titles'] ); ?>" />
                </p>
                <p>
                    <label>Season Description</label>
                    <textarea rows="4" cols="50" class="widefat" name="descriptions[]" value=""><?php if (isset($field['descriptions']) && $field['descriptions'] != '') echo esc_attr( $field['descriptions'] ); else echo ''; ?></textarea>
                </p>
                <a class="button button-large streamium-repeater-remove-row" href="#" data-pid="<?php echo $post->ID; ?>" data-index="<?php echo $key; ?>">Remove</a>

            </div>
        </li>
    <?php } } endif; ?>
    
    </ul> 

    <div class="streamium-repeater-footer">
        <a id="streamium-add-repeater-row" class="button add-program-row button-primary" href="#">Add Season</a>
    </div>

    <?php

}


/**
 * Sets up the meta box content for Roku
 *
 * @return null
 * @author  @sameast
 */
function streamium_premium_meta_box_roku(){

    // GLOBALS::
    global $post;
    
    // SECURITY::
    wp_nonce_field( 'streamium_premium_meta_security', 'streamium_premium_meta_nonce' );

    $values    = get_post_custom( $post->ID );
    $code      = isset( $values['s3bubble_video_code_meta_box_text'] ) ? $values['s3bubble_video_code_meta_box_text'][0] : '';
    $url       = isset( $values['s3bubble_roku_url_meta_box_text'] ) ? $values['s3bubble_roku_url_meta_box_text'][0] : '';
    $quality   = isset( $values['s3bubble_roku_quality_meta_box_text'] ) ? $values['s3bubble_roku_quality_meta_box_text'][0] : '';
    $videoType = isset( $values['s3bubble_roku_videotype_meta_box_text'] ) ? $values['s3bubble_roku_videotype_meta_box_text'][0] : '';
    $duration  = isset( $values['s3bubble_roku_duration_meta_box_text'] ) ? $values['s3bubble_roku_duration_meta_box_text'][0] : '';

    ?>
        <p class="streamium-meta-box-wrapper">
            
            <label>Video Url</label>
            <input type="url" name="s3bubble_roku_url_meta_box_text" class="widefat" id="s3bubble_roku_url_meta_box_text" value="<?php echo $url; ?>" placeholder="Enter video url" />

        </p>

        <p class="streamium-meta-box-wrapper">

            <label>Video Quality</label>
            <select tabindex="1" name="s3bubble_roku_quality_meta_box_text" id="s3bubble_roku_quality_meta_box_text">
                <option value="<?php echo $quality; ?>"><?php echo (empty($quality)) ? 'Select Video Quality' : $quality; ?></option>
                <option value="HD">HD 720p</option>
                <option value="FHD">FHD 1080p</option>
                <option value="UHD">UHD 4K</option>
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

            <a id="streamium-add-roku-data" class="button button-primary button-large" href="#" data-code="<?php echo (empty($code)) ? '' : $code; ?>">Generate Roku Data</a>

        </p>

        <p class="streamium-meta-box-wrapper">

            Make sure you update your Roku feed here. 
            <a href="https://developer.roku.com/en-gb/developer" target="_blank">https://developer.roku.com/en-gb/developer</a>

        </p>

    <?php    

}


/**
 * Saves the meta box content
 *
 * @return null
 * @author  @sameast
 */
function streamium_premium_meta_boxes_save( $post_id )
{
    // SECURITY::
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if( !isset( $_POST['streamium_premium_meta_nonce'] ) || !wp_verify_nonce( $_POST['streamium_premium_meta_nonce'], 'streamium_premium_meta_security' ) ) return;
     
    if ( ! current_user_can( 'edit_posts' ) ) return;
    
    // TRAILER SECTION  =>
    if( isset( $_POST['streamium_premium_meta_box_trailer_code'] ) ){

        update_post_meta( $post_id, 'streamium_premium_meta_box_trailer_code', $_POST['streamium_premium_meta_box_trailer_code'] );

    }
    if( isset( $_POST['streamium_premium_meta_box_trailer_button'] ) ){

        update_post_meta( $post_id, 'streamium_premium_meta_box_trailer_button', $_POST['streamium_premium_meta_box_trailer_button'] );

    }  
    // TRAILER SECTION <=

    // BACKGROUND SLIDER SECTION  =>
    if( isset( $_POST['streamium_premium_meta_box_background_video_text'] ) ){

        update_post_meta( $post_id, 'streamium_premium_meta_box_background_video_text', $_POST['streamium_premium_meta_box_background_video_text'] );

    }
    // BACKGROUND SLIDER SECTION  <=

    // EXTRA META SECTION  =>
    if( isset( $_POST['streamium_premium_meta_box_extra_meta_text'] ) ){
        
        update_post_meta( $post_id, 'streamium_premium_meta_box_extra_meta_text', $_POST['streamium_premium_meta_box_extra_meta_text'] );

    }
    // EXTRA META SECTION  <=

    // RATING SECTION  =>
    if( isset( $_POST['streamium_premium_meta_box_ratings_text'] ) ){
        
        update_post_meta( $post_id, 'streamium_premium_meta_box_ratings_text', $_POST['streamium_premium_meta_box_ratings_text'] );

    }
    // RATING SECTION  <=

    // ROKU SECTION  =>
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
    // ROKU SECTION <=

    // SEASONS SECTION  =>
    if( isset( $_POST['streamium_premium_meta_box_seasons'] ) ){

        // UPDATE::
        $seasons_blocks = explode('|', $_POST['streamium_premium_meta_box_seasons']);
        update_post_meta( $post_id, 'season_post_id', $seasons_blocks[0] );
        update_post_meta( $post_id, 'season_id', $seasons_blocks[1] );
      
    }
    // SEASONS SECTION  =>

    // WOO SECTION  =>
    if( isset( $_POST['streamium_premium_meta_box_woo_product'] ) ){

        update_post_meta( $post_id, 'streamium_premium_meta_box_woo_product', $_POST['streamium_premium_meta_box_woo_product'] );
      
    }
    // WOO SECTION  =>    

    // EPISODES SECTION  =>
    $old = get_post_meta($post_id, 'repeatable_fields', true);
    $new = array();
    if(isset($_POST['thumbnails']) && isset($_POST['seasons']) && isset($_POST['titles']) && isset($_POST['descriptions'])){

        $thumbnails   = isset($_POST['thumbnails']) ? $_POST['thumbnails'] : "";
        $seasons      = isset($_POST['seasons']) ? $_POST['seasons'] : "";
        $titles       = isset($_POST['titles']) ? $_POST['titles'] : "";
        $descriptions = isset($_POST['descriptions']) ? $_POST['descriptions'] : "";
        
        $count = count( $titles );
        
        for ( $i = 0; $i < $count; $i++ ) {
            
            if ( $thumbnails[$i] != '' && $titles[$i] != '' && $seasons[$i] != '' && $descriptions[$i] != '') :

                $new[$i]['thumbnails']   = esc_url(stripslashes( strip_tags( $thumbnails[$i] )));
                $new[$i]['seasons']      = trim(stripslashes( strip_tags( $seasons[$i] ) ));
                $new[$i]['titles']       = trim(stripslashes( strip_tags( $titles[$i] ) ));
                $new[$i]['descriptions'] = trim(stripslashes( $descriptions[$i] ));

            endif;

        }

        // Check and save repeater fields
        if ( !empty( $new ) && $new != $old ) {
        
            update_post_meta( $post_id, 'repeatable_fields', $new );
        
        }

    }
    // EPISODES SECTION  <=

}

add_action( 'save_post', 'streamium_premium_meta_boxes_save', 10, 3 );
<?php

	/*
 		Template Name: Profiles Template
 	*/

	// SWITCH PROFILES::
	if ( isset( $_GET['profile'] ) ){
		update_user_meta(get_current_user_id(), 'user_profile',  $_GET['profile']);
		$home = get_theme_mod( 'streamium_global_settings_join_redirect', 'content_types/movie' );
        wp_redirect( site_url( $home ) );
		exit;
  	}

	get_template_part( 'header', 'blank' ); 

    // Start the loop.
    while ( have_posts() ) : the_post();

		$image = wp_get_attachment_image_url( get_post_thumbnail_id(), 'content_tile_full_width_landscape' );
		$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );

?>
	
		<section class="full-hero user-profiles" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)), url(<?php echo esc_url( $image ); ?>)">
	        <div class="full-hero-inner">
	            <h1><?php the_title(); ?></h1>
	            <p><?php the_content(); ?></p>
	            <div class="d-flex justify-content-center">
	            <?php  

	            	$profiles = streamium_get_user_profiles();
	            	foreach ($profiles as $key => $value) {
	            		$avatar = esc_url(get_avatar_url(get_current_user_id(), array(
	            			'default' => $value->avatar,
	            			'force_default' => true
	            		)));
	            		echo '<div class="p-2 align center"><a href="?profile=' . $value->id .'"><img src="' . $avatar . '" class="img-fluid" /><br>Switch to ' . $value->profile .'</a><br><a href="javascript:;" data-id="' . $value->id .'" class="streamium-user-profiles-delete">Delete profile</a></div>';
	            	}
	            ?>
	            </div>
	            <a href="javascript:;" class="btn btn-primary btn-lg streamium-user-profiles-add">Add Profile</a>
	        </div>
	    </section>

<?php 
	endwhile; 
?>

<?php get_footer(); ?>
<?php get_template_part( 'header', 'video'); ?>

<div class="video-main">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    	<div id="s3bubble-aws" class="video-wrapper">
    		
		 	<?php //the_content(); ?>
		</div>

	 <?php endwhile; else : ?>

	 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

	 <?php endif; ?>

</div>

<?php
	if (is_user_logged_in()) : 
	   update_post_meta($post->ID,'recently_watched',current_time('mysql')); 
	   update_post_meta($post->ID,'recently_watched_user_id',get_current_user_id());
	endif;

	$s3videoid = get_post_meta( get_the_ID(), 's3bubble_video_code_meta_box_text', true );
	// Check if the custom field has a value.
	if ( ! empty( $s3videoid ) ) {
	    echo '<script type="text/javascript">S3BubbleAWS.init({id : "s3bubble-aws",code : "' . $s3videoid . '"});</script>';
	}

?>

<?php get_template_part( 'footer', 'video' ); ?>
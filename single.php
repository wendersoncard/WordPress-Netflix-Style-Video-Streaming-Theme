<?php get_template_part( 'header', 'video'); ?>

<div class="video-main">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    	<div id="s3bubble-aws" class="video-wrapper">

    	<?php if ( get_theme_mod( 'streamium_enable_premium' ) ) : $s3videoid = get_post_meta( get_the_ID(), 's3bubble_video_code_meta_box_text', true ); ?>

    		<?php if ( ! empty( $s3videoid ) ) : ?>
				
				<script type="text/javascript">S3BubbleAWS.init({id : "s3bubble-aws",code : "QKeQ28487"});</script>
			
			<?php else : ?>

				<h1>No video id has been added</h1>
			
			<?php endif; ?>

		<?php else : ?>

			<?php if ( ! empty( $s3videoid ) ) : ?>
				
				<div style="position: relative;padding-bottom: 56.25%;"><iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" src="<?php echo $s3videoid; ?>" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>
			
			<?php else : ?>

				<h1>No video id has been added</h1>
			
			<?php endif; ?>
		    
		<?php endif; ?>

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
?>

<?php get_template_part( 'footer', 'video' ); ?>
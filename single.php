<?php get_template_part( 'header', 'video'); ?>

<div class="video-main">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    	<div class="video-wrapper">
		 	<?php the_content(); ?>
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
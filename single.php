<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<?php 
		$episodes = get_post_meta(get_the_ID(), 'repeatable_fields' , true);
		if(!empty($episodes)) :

			get_header();
			get_template_part( 'templates/content', 'multi' );
			
			get_footer();

		else :

			get_template_part( 'header', 'video');
			
			$stream = get_post_meta(get_the_ID(), 'streamium_live_stream_meta_box_text' , true);
			if($stream){
				get_template_part( 'templates/content', 'live' );
			}else{
				get_template_part( 'templates/content', 'single' );
			}

			get_template_part( 'footer', 'video' );

		endif;

		if (is_user_logged_in()) : 
	   
		   update_post_meta($post->ID,'recently_watched',current_time('mysql')); 
		   update_post_meta($post->ID,'recently_watched_user_id',get_current_user_id());

		endif;

	?>

<?php endwhile; else : ?>

 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

<?php endif; ?>
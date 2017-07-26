<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<?php

		$type = get_post_type( get_the_ID() ); 

		if($type === "post"){

			get_header();
			get_template_part( 'templates/content', 'blog' );
			get_footer();

		}

		if($type === "stream"){

			get_template_part( 'header', 'video');
			get_template_part( 'templates/content', 'live' );
			get_template_part( 'footer', 'video' );
		
		}

		if (in_array($type, array('movie', 'tv','sport','kid'))) {
		    
		    $episodes = get_post_meta(get_the_ID(), 'repeatable_fields' , true);
			if(!empty($episodes)) :

				get_header();
				get_template_part( 'templates/content', 'multi' );
				get_footer();

			else :

				get_template_part( 'header', 'video');
				get_template_part( 'templates/content', 'single' );
				get_template_part( 'footer', 'video' );

			endif;

		}

		if (is_user_logged_in()) : 
	   
		   update_post_meta($post->ID,'recently_watched',current_time('mysql')); 
		   update_post_meta($post->ID,'recently_watched_user_id',get_current_user_id());

		endif;

	?>

<?php endwhile; else : ?>

 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

<?php endif; ?>
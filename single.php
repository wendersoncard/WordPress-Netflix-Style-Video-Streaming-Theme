<?php get_template_part( 'header', 'video'); ?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    	<?php the_content(); ?>

	 <?php endwhile; else : ?>

	 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

	 <?php endif; ?>
 <?php get_template_part( 'footer', 'video' ); ?>
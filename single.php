<?php get_template_part( 'header', 'video'); ?>
<<<<<<< HEAD

<div class="video-main">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    	<div class="video-wrapper">
		 	<?php the_content(); ?>
		</div>
=======
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    	<?php the_content(); ?>
>>>>>>> 17c526e13156d8c684e652449df69bee146e2646

	 <?php endwhile; else : ?>

	 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

	 <?php endif; ?>
<<<<<<< HEAD

</div>
<?php get_template_part( 'footer', 'video' ); ?>
=======
 <?php get_template_part( 'footer', 'video' ); ?>
>>>>>>> 17c526e13156d8c684e652449df69bee146e2646

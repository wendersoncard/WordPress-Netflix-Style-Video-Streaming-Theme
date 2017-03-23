<?php get_header(); ?>
<main class="cd-main-content-page">
	
	<div class="top-spacer"></div>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-xs-12">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			 	<?php the_content(); ?>					 

			<?php endwhile; else : ?>

			 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

			<?php endif; ?>
		</div>
	</div>
	
 <?php get_footer(); ?>
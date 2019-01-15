<?php get_header(); ?>

	<main class="cd-main-content streamium-page">
		
		<div class="container-fluid">
			<div class="row">

				<?php if ( ! is_active_sidebar( 'forum-sidebar' ) ) : ?>
					<div class="col-sm-12 col-xs-12">
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						 	<?php the_content(); ?>					 

						<?php endwhile; else : ?>

						 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="col-sm-9 col-xs-12">
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						 	<?php the_content(); ?>					 

						<?php endwhile; else : ?>

						 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

						<?php endif; ?>
					</div>
					<div class="col-sm-3 col-xs-12">
						<?php dynamic_sidebar('forum-sidebar'); ?>
					</div>
				<?php endif; ?>
				
			</div>
		</div>

	</main><!--/.main content-->
	
 <?php get_footer(); ?>
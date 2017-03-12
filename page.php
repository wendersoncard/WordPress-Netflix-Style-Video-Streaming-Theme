<?php get_header(); ?>
<main class="cd-main-content">
		<section class="videos">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12">
						<h3><p></p></h3>
					</div><!--/.col-sm-12-->
					<div class="col-sm-12">
						<!-- Start the Loop. -->
						 <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						 	<?php the_content(); ?>

						 <?php endwhile; else : ?>

						 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

						 <?php endif; ?>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->
		</section><!--/.videos-->
 <?php get_footer(); ?>
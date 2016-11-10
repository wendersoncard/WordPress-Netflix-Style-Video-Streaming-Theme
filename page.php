<?php get_header(); ?>
<main class="cd-main-content">
		<section class="videos">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<h3><p></p></h3>
					</div><!--/.col-sm-12-->
					<div class="col-sm-12">
						<!-- Start the Loop. -->
						 <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						 	<div class="post">

						 	
						 	<div class="entry">
						 		<?php the_content(); ?>
						 	</div>

						 	<?php paginate_comments_links(); ?> 

						    <?php comments_template(); ?> 

						 	<p>
								<?php previous_posts_link( __('Prev Entries', 'streamium' ) ) ?>
								<?php next_posts_link( __('Next Entries', 'streamium' ) ) ?>
						 	</p>
						 	</div> 

						 <?php endwhile; else : ?>

						 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

						 <?php endif; ?>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->
		</section><!--/.videos-->
 <?php get_footer(); ?>
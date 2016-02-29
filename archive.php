<?php get_header(); ?>
	<main class="cd-main-content">
		<section class="categories no-header-push">
			<?php if ( have_posts() ) : ?>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12">
						<h3><?php printf( __( 'Viewing: %s', 'streamium' ), single_tag_title( '', false ) ); ?></h3>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
				<div class="row">
					<?php while ( have_posts() ) : the_post(); if ( has_post_thumbnail() ) { ?>
						<div class="col-sm-3  <?php post_class(); ?>">
							<div class="category-block">
								<div class="category-block-header">
									<h4><?php the_time('F jS, Y'); ?> by <?php the_author_posts_link(); ?></h4>
								</div>
								<a class="icon-play" href="<?php the_permalink(); ?>"></a>
								<h4 class="video-title"><?php the_title(); ?></h4>
								<?php
									the_post_thumbnail('streamium-video-category', array('class' => 'img-responsive'));
								?><!--/.category-block-->
								<div class="category-block-footer">
									<?php _e( 'Posted in' ); ?> <?php the_category( ', ' ); ?><br>
									<?php the_tags(); ?>
								</div>
							</div>
						</div>
					<?php } endwhile; ?>
				</div><!--/.row-->
			</div><!--/.container-->
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</section><!--/.videos-->
<?php get_footer(); ?>
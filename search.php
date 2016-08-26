<?php get_header(); ?>
	<main class="cd-main-content">
		<section class="categories no-header-push">
			<?php if ( have_posts() ) : ?>
			<div class="container">
				<div class="row">
					<div class="col-sm-12 video-header">
						<h3 class="pull-left"><?php printf( __( 'Search Results for: %s', 'streamium' ), get_search_query() ); ?></h3>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
				<div class="row">
					<?php while ( have_posts() ) : the_post(); if ( has_post_thumbnail() ) { 
						$image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-poster' );
						$trimmed_content = wp_trim_words( get_the_excerpt(), 11 ); 
						?>
						<div <?php post_class("col-sm-3"); ?>>
							<div class="category-block">
								<img src="<?php echo esc_url($image[0]); ?>" />
								<div class="category-block-overlay">
									<?php if(get_comments_number()) : ?>
										<small><a href="#" class="cd-see-all" data-pid="<?php echo $post->ID; ?>"><?php comments_number( 'no reviews', 'one review', '% reviews' ); ?></a></small>
									<?php endif; ?>
									<div class="category-block-overlay-info">
										<h3><?php the_title(); ?></h3>
									</div>
									<a class="icon-play glyphicon glyphicon-play" href="<?php the_permalink(); ?>"></a>
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
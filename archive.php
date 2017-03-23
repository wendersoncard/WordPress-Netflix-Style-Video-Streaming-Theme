<?php get_header(); ?>
	<main class="cd-main-content-page">
	
		<div class="main-spacer"></div>

		<section class="categories">
			<?php if ( have_posts() ) : ?>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 video-header-archive">
						<h3><?php printf( __( 'Viewing: %s', 'streamium' ), single_cat_title( '', false ) ); ?></h3>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->
			<div class="container-fluid">
				<div class="row static-row static-row-first">
					<?php
						$cat_count = 0; 
						$count = 0;
						$total_count = $wp_query->post_count;
						while ( have_posts() ) : the_post(); if ( has_post_thumbnail() ) { 
						$image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-poster' );
						$fullImage  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-home-slider' );  
						$trimmed_content = wp_trim_words( get_the_excerpt(), 11 ); 
						?>
						<div class="col-sm-2 tile" data-link="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>" data-description="<?php echo htmlentities(get_the_content()); ?>" data-bgimage="<?php echo $fullImage[0]; ?>" data-cat="static-<?php echo $cat_count; ?>">
					        <div class="tile_media">
					        	<img class="tile_img" src="<?php echo esc_url($image[0]); ?>" alt=""  />
					        </div>
					        <a class="tile_play" href="<?php the_permalink(); ?>">
								<i class="fa fa-play fa-1x" aria-hidden="true"></i>
				        	</a>
					        <div class="tile_details">
					          	<div class="tile_meta">
					            	<h4><?php the_title(); ?></h4>						            	
					            	<a data-link="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>" data-description="<?php echo htmlentities(get_the_content()); ?>" data-bgimage="<?php echo $fullImage[0]; ?>" data-cat="static-<?php echo $cat_count; ?>" class="tile_meta_more_info hidden-xs"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
					          	</div>
					        </div>
						</div>
						<?php
							$count++;
  							if ($count % (isMobile() ? 1 : 6) == 0 || $count == $total_count) { 
  						?>
  						</div>
  						</div>
  						<section class="s3bubble-details-full static-<?php echo $cat_count; ?>">
							<div class="s3bubble-details-full-overlay"></div>
							<div class="container-fluid s3bubble-details-inner-content">
								<div class="row">
									<div class="col-sm-5 col-xs-5 rel">
										<div class="synopis-outer">
											<div class="synopis-middle">
												<div class="synopis-inner">
													<h2 class="synopis hidden-xs"></h2>
													<p class="synopis"></p>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-7 col-xs-7 rel">
										<a class="tile_play home-slider-play-icon synopis" href="#">
											<i class="fa fa-play fa-3x" aria-hidden="true"></i>
							        	</a>
							        	<a href="#" class="s3bubble-details-inner-close"><i class="fa fa-times" aria-hidden="true"></i></a>
									</div><!--/.col-sm-12-->
								</div><!--/.row-->
							</div><!--/.container-->
						</section><div class="container-fluid"><div class="row static-row">
					<?php $cat_count++; } ?>
					<?php } endwhile; ?>
				</div><!--/.row-->
				<div class="row">
					<div class="col-sm-12">
						<?php if (function_exists("streamium_pagination")) {
						    streamium_pagination();
						} ?>
					</div>
				</div><!--/.row-->
			</div><!--/.container-->
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</section><!--/.videos-->

		<div class="main-spacer"></div>
		
<?php get_footer(); ?>
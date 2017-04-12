<?php get_header(); ?>
	<main class="cd-main-content">
		<section class="hero">
			<button class="streamium-prev fa fa-angle-left" aria-hidden="true"></button>
			<div class="hero-slider">
				<?php

					$args = array(
						'post_status' => 'publish',
						'posts_per_page'      => -1,
						'post__in'            => get_option( 'sticky_posts' ),
						'ignore_sticky_posts' => 1
					);
					
					$loop = new WP_Query( $args ); 

					if($loop->have_posts()):
						while ( $loop->have_posts() ) : $loop->the_post();
							global $post;
						    $image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-home-slider' ); 
							$title   = wp_trim_words( get_the_title(), $num_words = 10, $more = '... ' );
							$excerpt = wp_trim_words( get_the_excerpt(), $num_words = 50, $more = '... ' );
							$percentage = get_post_meta( get_the_ID(), 'percentage', true );
							$streamiumVideoTrailer = get_post_meta( get_the_ID(), 'streamium_video_trailer_meta_box_text', true );
							$streamiumFeaturedVideo = get_post_meta( get_the_ID(), 'streamium_featured_video_meta_box_text', true );

					?>
					<div class="slider-block">
						<?php if ( ! empty( $streamiumVideoTrailer ) && !isMobile() ) : ?>
							<div id="streamium-featured-background-<?php echo get_the_ID(); ?>"></div>
							<script type="text/javascript">
								S3BubbleAWS.init({
									id : "streamium-featured-background-<?php echo get_the_ID(); ?>",
									code : "<?php echo $streamiumFeaturedVideo; ?>",
									poster: "<?php echo esc_url($image[0]); ?>",
									fluid: true,
									muted : true,
									loop : true,
									autoplay : true,
									controls: false,
									meta: false
								});
							</script>
						<?php else : ?>
							<img src="<?php echo esc_url($image[0]); ?>" />
						<?php endif; ?>
						<article class="content-overlay">
							<div class="container-fluid rel">
								<div class="row rel">
									<div class="col-sm-5 col-xs-5 rel">
										<div class="synopis-outer">
											<div class="synopis-middle">
												<div class="synopis-inner">
													<h2><?php echo (isset($title) ? $title : __( 'No Title', 'streamium' )); ?></h2>
													<span class="hidden-xs">
														<p><?php echo get_the_content(); ?></p>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-7 col-xs-7 rel">
										<a class="play-icon-wrap" href="<?php the_permalink(); ?>">
											<div class="play-icon-wrap-rel">
												<div class="play-icon-wrap-rel-ring"></div>
												<span class="play-icon-wrap-rel-play">
													<i class="fa fa-play fa-3x" aria-hidden="true"></i>
									        	</span>
								        	</div>
							        	</a>
							        	<?php if ( ! empty( $streamiumVideoTrailer ) ) : ?>
								        	<a class="synopis-video-trailer" href="<?php the_permalink(); ?>?trailer=true">Watch Trailer</a>
								        <?php endif; ?>
									</div>
								</div>
							</div>
						</article><!--/.content-overlay-->
					</div>
					<?php 
						endwhile; 
					else: 
					?>
					<div style="background:url(<?php echo esc_url(get_template_directory_uri()); ?>/dist/frontend/assets/tech-2-mobile.jpg);" class="slider-block">
						<article class="content-overlay">
							<h2><?php _e( 'S3Bubble Media Streaming', 'streamium' ); ?></h2>
							<p><?php _e( 'Please replace this by making a post sticky, when you have do this you new sticky post will be displayed here.', 'streamium' ); ?></p>
						</article><!--/.content-overlay-->
					</div>
					<?php
					endif;
					wp_reset_query(); 
				?>
			</div><!--/.hero-slider-->
			<button class="streamium-next fa fa-angle-right" aria-hidden="true"></button>
		</section><!--/.hero-->
		
		<?php 
			$args = array(
			    'posts_per_page' => (int)get_theme_mod( 'streamium_global_options_homepage_desktop' ),
			    'ignore_sticky_posts' => 1,
			    'meta_query' => array(
					array(
						'key' => 'recently_watched_user_id',
						'value' => get_current_user_id()
					)
				)
			);
			$loop = new WP_Query( $args ); 
			if(is_user_logged_in() && $loop->post_count > 0) : 
		?>
			<section class="videos">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-12 video-header">
							<h3>Recently Watched</h3>
						</div><!--/.col-sm-12-->
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="prev_next"></div>
							<div class="carousels">
								<?php
									if($loop->have_posts()):
										while ( $loop->have_posts() ) : $loop->the_post();
											if ( has_post_thumbnail() ) : // thumbnail check 
											$image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-category' );
											$fullImage  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-home-slider' );  
											$trimmed_content = wp_trim_words( get_the_excerpt(), 11 );
											$streamiumVideoTrailer = get_post_meta( get_the_ID(), 'streamium_video_trailer_meta_box_text', true );
							
								?>
								<div class="tile" data-link="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>" data-description="<?php echo htmlentities(get_the_content()); ?>" data-bgimage="<?php echo $fullImage[0]; ?>" data-cat="recent">
								        <div class="tile_media" style="background-image: url(<?php echo esc_url($image[0]); ?>);">
						       	 		</div> 
						       	 		<a class="play-icon-wrap hidden-xs" href="<?php the_permalink(); ?>">
											<div class="play-icon-wrap-rel">
												<div class="play-icon-wrap-rel-ring"></div>
												<span class="play-icon-wrap-rel-play">
													<i class="fa fa-play fa-1x" aria-hidden="true"></i>
									        	</span>
								        	</div>
							        	</a>
								        <div class="tile_details">
								          	<div class="tile_meta">
								            	<h4><?php the_title(); ?></h4>						            	
								            	<a data-trailer="<?php echo $streamiumVideoTrailer; ?>" data-link="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>" data-description="<?php echo htmlentities(get_the_content()); ?>" data-bgimage="<?php echo $fullImage[0]; ?>" data-cat="recent" class="tile_meta_more_info hidden-xs"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
								          	</div>
								        </div>
								    </div>
								<?php
											endif;  
										endwhile;
										endif;
									wp_reset_query();
								?>
							</div><!--/.carousel-->
						</div><!--/.col-sm-12-->
					</div><!--/.row-->
				</div><!--/.container-->
			</section><!--/.videos-->
			<section class="s3bubble-details-full recent">
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
							<a class="play-icon-wrap synopis" href="#">
								<div class="play-icon-wrap-rel">
									<div class="play-icon-wrap-rel-ring"></div>
									<span class="play-icon-wrap-rel-play">
										<i class="fa fa-play fa-3x" aria-hidden="true"></i>
						        	</span>
					        	</div>
				        	</a>
				        	<a href="#" class="synopis-video-trailer">Watch Trailer</a>
				        	<a href="#" class="s3bubble-details-inner-close"><i class="fa fa-times" aria-hidden="true"></i></a>
						</div><!--/.col-sm-12-->
					</div><!--/.row-->
				</div><!--/.container-->
			</section><!--/.videos-->
		<?php endif; ?>

		<?php 
		  	$categories = get_categories(); 
		  	foreach ($categories as $category) : ?>
	  	<section class="videos">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 video-header">
						<h3><?php echo ucfirst($category->cat_name); ?></h3>
						<a class="see-all" href="<?php echo esc_url( home_url() ); ?>/category/<?php echo $category->slug; ?>">View all</a>
					</div><!--/.col-sm-12-->
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="prev_next"></div>
						<div class="carousels">
					  	<?php
							$args = array(
								    'posts_per_page' => (int)get_theme_mod( 'streamium_global_options_homepage_desktop' ),
								    'cat' => $category->cat_ID
								);
								$loop = new WP_Query( $args ); 
								if($loop->have_posts()):
									while ( $loop->have_posts() ) : $loop->the_post();
									if ( has_post_thumbnail() ) : // thumbnail check 
									$image  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-category' );
									$fullImage  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-home-slider' );  
									$trimmed_content = wp_trim_words( get_the_excerpt(), 11 );
									$streamiumVideoTrailer = get_post_meta( get_the_ID(), 'streamium_video_trailer_meta_box_text', true );

						?>
							<div class="tile" data-link="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>" data-description="<?php echo htmlentities(get_the_content()); ?>" data-bgimage="<?php echo $fullImage[0]; ?>" data-cat="<?php echo $category->slug; ?>">
								<?php if($post->premium) : ?>
									<div class="tile_payment_details">
										<div class="tile_payment_details_inner">
											<h2>Available on <?php echo str_replace(array("_"), " ", $post->plans[0]); ?></h2>
										</div>
									</div>
								<?php endif; ?>
						        <div class="tile_media" style="background-image: url(<?php echo esc_url($image[0]); ?>);">
						        </div>
						        <?php if(!($post->premium)) : ?>
							        <a class="play-icon-wrap hidden-xs" href="<?php the_permalink(); ?>">
										<div class="play-icon-wrap-rel">
											<div class="play-icon-wrap-rel-ring"></div>
											<span class="play-icon-wrap-rel-play">
												<i class="fa fa-play fa-1x" aria-hidden="true"></i>
								        	</span>
							        	</div>
						        	</a>
					        	<?php endif; ?>
						        <div class="tile_details">
						          	<div class="tile_meta">
						            	<h4><?php the_title(); ?></h4>						            	
						            	<a data-trailer="<?php echo $streamiumVideoTrailer; ?>" data-link="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>" data-description="<?php echo htmlentities(get_the_content()); ?>" data-bgimage="<?php echo $fullImage[0]; ?>" data-cat="<?php echo $category->slug; ?>" class="tile_meta_more_info hidden-xs"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
						          	</div>
						        </div>
						    </div>
						<?php
								
							endif; 
							endwhile;
							endif;
							wp_reset_query();
						?>
						</div><!--/.carousel-->
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->
		</section><!--/.videos-->
		<section class="s3bubble-details-full <?php echo $category->slug; ?>">
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
						<a class="play-icon-wrap synopis" href="#">
							<div class="play-icon-wrap-rel">
								<div class="play-icon-wrap-rel-ring"></div>
								<span class="play-icon-wrap-rel-play">
									<i class="fa fa-play fa-3x" aria-hidden="true"></i>
					        	</span>
				        	</div>
			        	</a>
			        	<a href="#" class="synopis-video-trailer">Watch Trailer</a>
			        	<a href="#" class="s3bubble-details-inner-close"><i class="fa fa-times" aria-hidden="true"></i></a>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->
		</section><!--/.videos-->
			
		<?php  	
			endforeach; 
		?>

		<section class="videos">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 video-header">
						<h3></h3>
					</div><!--/.col-sm-12-->
				</div>
			</div>
		</section><!--/.videos-->

<?php get_footer(); ?>
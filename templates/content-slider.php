<section class="streamium-slider">
	<?php 

		$setType = get_theme_mod( 'streamium_main_post_type', 'movie');
		$setTax = get_theme_mod( 'streamium_main_tax', 'movies');

		$args = array(
		    'posts_per_page' => (int)get_theme_mod( 'streamium_global_options_homepage_desktop' ),
		    'post_type' => $setType, //array('movie', 'tv','sport','kid','stream'),
		    'meta_key' => 'streamium_slider_featured_checkbox_value',
			'meta_value' => 'yes'
		);
		 
		$loop = new WP_Query( $args ); 
		$sliderPostCount = 0;
		if($loop->have_posts()):
			while ( $loop->have_posts() ) : $loop->the_post();

			    $image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-home-slider' ); 
				$title   = wp_trim_words( get_the_title(), $num_words = 10, $more = '... ' );
				$streamiumVideoTrailer = get_post_meta( get_the_ID(), 'streamium_video_trailer_meta_box_text', true );
				$streamiumFeaturedVideo = get_post_meta( get_the_ID(), 'streamium_featured_video_meta_box_text', true );
				$nonce = wp_create_nonce( 'streamium_likes_nonce' );
		        $link = admin_url('admin-ajax.php?action=streamium_likes&post_id='.get_the_ID().'&nonce='.$nonce);
		        $content = (wp_is_mobile()) ? get_the_excerpt() : get_the_content();
		?>

		<?php if ( ! empty( $streamiumFeaturedVideo ) && (!wp_is_mobile()) && ($sliderPostCount < 1)  && get_theme_mod( 'streamium_enable_premium' ) ) : ?>

			<div>
				
				<div id="streamium-featured-background-<?php echo get_the_ID(); ?>" class="s3bubble streamium-featured-background" data-setup='{"codes": "<?php echo $streamiumFeaturedVideo; ?>","source":{"poster":"https://s3.amazonaws.com/s3bubble-cdn/theme-images/streamium-video-blank.png"},"options":{"background":true,"muted":true,"loop":true,"autoplay":true,"controls":false,"vpaid":""},"meta":{"skipButtons":false,"showSocial":false,"backButton":false,"subTitle": "","title": "","para": ""}}'></div>

				<article class="content-overlay">
					<div class="content-overlay-grad"></div>
					<div class="container-fluid rel">
						<div class="row rel">
							<div class="col-sm-5 col-xs-5 rel">
								<div class="synopis-outer">
									<div class="synopis-middle">
										<div class="synopis-inner">
											<h2><?php echo (isset($title) ? $title : __( 'No Title', 'streamium' )); ?></h2>
											<div class="synopis content hidden-xs">
												<?php echo $content; ?>
												<ul>
													<?php do_action('synopis_multi_meta'); ?>
												</ul>
											</div>
											
											<?php if(get_theme_mod( 'streamium_enable_premium' )) : ?>
												<div class="synopis-premium-meta hidden-xs">
													<a id="like-count-<?php echo get_the_ID(); ?>" class="streamium-review-like-btn streamium-btns streamium-reviews-btns" data-toggle="tooltip" title="CLICK TO LIKE!" data-id="<?php echo get_the_ID(); ?>" data-nonce="<?php echo $nonce; ?>">	<?php echo get_streamium_likes(get_the_ID()); ?>
													</a>
								                    <a class="streamium-list-reviews streamium-btns streamium-reviews-btns" data-id="<?php echo get_the_ID(); ?>" data-nonce="<?php echo $nonce; ?>">Read reviews</a>
												</div>
											<?php endif; ?>
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
					        	<?php if ( ! empty( $streamiumVideoTrailer ) && get_theme_mod( 'streamium_enable_premium' ) ) : ?>
						        	<a class="synopis-video-trailer streamium-btns hidden-xs" href="<?php the_permalink(); ?>?trailer=true">Watch Trailer</a>
						        <?php endif; ?>
							</div>
						</div>
					</div>
				</article><!--/.content-overlay-->

			</div>

		<?php else : ?>

			<div style="background-image: url(<?php echo esc_url($image[0]); ?>);">

				<article class="content-overlay">
					<div class="content-overlay-grad"></div>
					<div class="container-fluid rel">
						<div class="row rel">
							<div class="col-sm-5 col-xs-5 rel">
								<div class="synopis-outer">
									<div class="synopis-middle">
										<div class="synopis-inner">
											<h2><?php echo (isset($title) ? $title : __( 'No Title', 'streamium' )); ?></h2>
											<div class="synopis content hidden-xs">
												<?php echo $content; ?>
												<ul>
													<?php do_action('synopis_multi_meta'); ?>
												</ul>
											</div>
											
											<?php if(get_theme_mod( 'streamium_enable_premium' )) : ?>
												<div class="synopis-premium-meta hidden-xs">
													<a id="like-count-<?php echo get_the_ID(); ?>" class="streamium-review-like-btn streamium-btns streamium-reviews-btns" data-toggle="tooltip" title="CLICK TO LIKE!" data-id="<?php echo get_the_ID(); ?>" data-nonce="<?php echo $nonce; ?>">	<?php echo get_streamium_likes(get_the_ID()); ?>
													</a>
								                    <a class="streamium-list-reviews streamium-btns streamium-reviews-btns" data-id="<?php echo get_the_ID(); ?>" data-nonce="<?php echo $nonce; ?>">Read reviews</a>
												</div>
											<?php endif; ?>
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
					        	<?php if ( ! empty( $streamiumVideoTrailer ) && get_theme_mod( 'streamium_enable_premium' ) ) : ?>
						        	<a class="synopis-video-trailer streamium-btns hidden-xs" href="<?php the_permalink(); ?>?trailer=true">Watch Trailer</a>
						        <?php endif; ?>
							</div>
						</div>
					</div>
				</article><!--/.content-overlay-->

			</div>

		<?php endif; ?>

		<?php
		    $sliderPostCount++; 
			endwhile; 
		else: 
		?>
		<div style="background:url(<?php echo esc_url(get_template_directory_uri()); ?>/dist/frontend/assets/tech-2-mobile.jpg);" class="slider-block">
			<div class="slider-no-content">
				<h2><?php _e( 'S3Bubble Media Streaming', 'streamium' ); ?></h2>
				<p><?php _e( 'To display a image here go to your custom post and look for the metabox (Main Slider Video) and check it.', 'streamium' ); ?></p>
			</div><!--/.content-overlay-->
		</div>
		<?php
		endif;
		wp_reset_query(); 

	?>
</section><!--/.streamium-slider-->
<section class="streamium-slider">
	<?php 
			
			$query = $wp_query->get_queried_object(); 
			$tax = isset($query->taxonomies[1]) ? $query->taxonomies[1] : "";
			$rewrite = (get_theme_mod( 'streamium_section_input_taxonomy_' . $tax )) ? get_theme_mod( 'streamium_section_input_taxonomy_' . $tax ) : $tax; 

		$args = array(
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'post_type' => $query->name,
			'meta_key' => 'streamium_slider_featured_checkbox_value',
			'meta_value' => 'yes'
		);
		  
		$loop = new WP_Query( $args ); 
		$sliderPostCount = 0;
		if($loop->have_posts()):
			while ( $loop->have_posts() ) : $loop->the_post();
				global $post;
			    $image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-home-slider' ); 
				$title   = wp_trim_words( get_the_title(), $num_words = 10, $more = '... ' );
				$percentage = get_post_meta( get_the_ID(), 'percentage', true );
				$streamiumVideoTrailer = get_post_meta( get_the_ID(), 'streamium_video_trailer_meta_box_text', true );
				$streamiumFeaturedVideo = get_post_meta( get_the_ID(), 'streamium_featured_video_meta_box_text', true );
				$nonce = wp_create_nonce( 'streamium_likes_nonce' );
		        $link = admin_url('admin-ajax.php?action=streamium_likes&post_id='.get_the_ID().'&nonce='.$nonce);
		        $content = wp_trim_words( strip_tags(get_the_content()), 15, ' <a class="show-more-content" data-id="' . get_the_ID() . '">' . __( 'read more', 'streamium' ) . '</a>' );

		?>
		<div class="slider-block" style="background-image: url(<?php echo esc_url($image[0]); ?>);">
			<article class="content-overlay">
				<div class="content-overlay-grad"></div>
				<div class="container-fluid rel">
					<div class="row rel">
						<div class="col-sm-5 col-xs-5 rel">
							<div class="synopis-outer">
								<div class="synopis-middle">
									<div class="synopis-inner">
										<h2><?php echo (isset($title) ? $title : __( 'No Title', 'streamium' )); ?></h2>
										<div class="synopis content">
											<?php echo $content; ?>
											<ul>
												<?php do_action('synopis_multi_meta'); ?>
											</ul>
										</div>
										
										<?php if(get_theme_mod( 'streamium_enable_premium' )) : ?>
											<div class="synopis-premium-meta hidden-xs">
												<a id="like-count-<?php echo get_the_ID(); ?>" class="streamium-review-like-btn streamium-btns streamium-reviews-btns" data-toggle="tooltip" title="<?php _e( 'CLICK TO LIKE!', 'streamium' ); ?>" data-id="<?php echo get_the_ID(); ?>" data-nonce="<?php echo $nonce; ?>">	<?php echo get_streamium_likes(get_the_ID()); ?>
												</a>
							                    <a class="streamium-list-reviews streamium-btns streamium-reviews-btns" data-id="<?php echo get_the_ID(); ?>" data-nonce="<?php echo $nonce; ?>"><?php _e( 'Read reviews', 'streamium' ); ?></a>
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
										<i class="fa fa-play fa-3x brand-color" aria-hidden="true"></i>
						        	</span>
					        	</div>
				        	</a>
				        	<?php if ( ! empty( $streamiumVideoTrailer ) && get_theme_mod( 'streamium_enable_premium' ) ) : ?>
					        	<a class="synopis-video-trailer streamium-btns hidden-xs" href="<?php the_permalink(); ?>?trailer=true"><?php _e( 'Watch Trailer', 'streamium' ); ?></a>
					        <?php endif; ?>
						</div>
					</div>
				</div>
			</article><!--/.content-overlay-->
		</div>
		<?php
		    $sliderPostCount++; 
			endwhile; 
		else : 
		endif;
		wp_reset_query(); 
	?>
</section><!--/.hero-->
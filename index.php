<?php 
if ( get_theme_mod( 'redirect_too_signup' ) ) : 

	if ( wp_redirect( '/my-account/' ) ) {
	    exit;
	}

endif; 
?>
<?php get_header(); ?>

	<main class="cd-main-content">
		<section class="hero">
			<button class="streamium-prev glyphicon glyphicon-menu-left" aria-hidden="true"></button>
			<div class="hero-slider">
				<?php 	
					$args = array(
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

					?>
					<div class="slider-block">
						<img src="<?php echo esc_url($image[0]); ?>" />
						<article class="content-overlay">
							<div class="container-fluid rel">
								<div class="row rel">
									<div class="col-sm-6 rel hidden-xs">
										<div class="synopis-outer">
											<div class="synopis-middle">
												<div class="synopis-inner">
													<h2><?php echo (isset($title) ? $title : __( 'No Title', 'streamium' )); ?></h2>
													<p><?php echo (isset($excerpt) ? $excerpt : __( 'No Text', 'streamium' )); ?></p>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6 rel">
										<a class="icon-play glyphicon glyphicon-play" aria-hidden="true" href="<?php the_permalink(); ?>"></a>
									</div>
								</div>
							</div>
						</article><!--/.content-overlay-->
					</div>
					<?php 
						endwhile; 
					else: 
					?>
					<div style="background:url(<?php echo esc_url(get_template_directory_uri()); ?>/dist/frontend/assets/tech-2-mobile.jpg);background-size: cover;" class="slider-block">
						<article class="content-overlay">
							<h2><?php _e( 'S3Bubble Media Streaming', 'streamium' ); ?></h2>
							<p><?php _e( 'Please replace this by making a post sticky, when you have do this you new sticky post will be displayed here.', 'streamium' ); ?></p>
						</article><!--/.content-overlay-->
						<a class="icon-play glyphicon glyphicon-play" aria-hidden="true" href="<?php the_permalink(); ?>"></a>
					</div>
					<?php
					endif;
					wp_reset_query(); 
				?>
			</div><!--/.hero-slider-->
			<button class="streamium-next glyphicon glyphicon-menu-right" aria-hidden="true"></button>
		</section><!--/.hero-->

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
								$args = array(
								    'posts_per_page' => 20,
								    'meta_key' => '_last_viewed',
								    'orderby' => 'meta_value',
								    'order' => 'DESC'
								);
								$loop = new WP_Query( $args ); 
								if($loop->have_posts()):
									while ( $loop->have_posts() ) : $loop->the_post();
										if ( has_post_thumbnail() ) : // thumbnail check 
										$image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-poster' ); 
										$trimmed_content = wp_trim_words( get_the_excerpt(), 11 );
							?>
								<div>
									<img src="<?php echo esc_url($image[0]); ?>" />
									<div class="block-overlay">
										<?php if(get_comments_number()) : ?>
											<small><a href="#" class="cd-see-all" data-pid="<?php echo $post->ID; ?>"><?php comments_number( 'no reviews', 'one review', '% reviews' ); ?></a></small>
										<?php endif; ?>
										<div class="block-overlay-info">
											<h3><?php the_title(); ?></h3>
										</div>
										<a class="icon-play glyphicon glyphicon-play" href="<?php the_permalink(); ?>"></a>
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

		<?php 
		  	$categories = get_categories(); 
		  	foreach ($categories as $category) : ?>
	  	<section class="videos">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 video-header">
						<h3><?php echo ucfirst($category->cat_name); ?></h3>
						<a class="see-all" href="<?php echo esc_url( home_url() ); ?>/category/<?php echo $category->slug; ?>">See all</a>
					</div><!--/.col-sm-12-->
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="prev_next"></div>
						<div class="carousels">
					  	<?php
							$args = array(
								    'posts_per_page' => 20,
								    'cat' => $category->cat_ID
								);
								$loop = new WP_Query( $args ); 
								if($loop->have_posts()):
									while ( $loop->have_posts() ) : $loop->the_post();
							if ( has_post_thumbnail() ) : // thumbnail check 
							$image  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-poster' ); 
							$trimmed_content = wp_trim_words( get_the_excerpt(), 11 );
						?>
							<div>
								<img src="<?php echo esc_url($image[0]); ?>" />
								<div class="block-overlay">
									<?php if(get_comments_number()) : ?>
										<small><a href="#" class="cd-see-all" data-pid="<?php echo $post->ID; ?>"><?php comments_number( 'no reviews', 'one review', '% reviews' ); ?></a></small>
									<?php endif; ?>
									<div class="block-overlay-info">
										<h3><?php the_title(); ?></h3>
									</div>
									<a class="icon-play glyphicon glyphicon-play" href="<?php the_permalink(); ?>"></a>
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
		<?php  	
			endforeach; 
		?>


<?php get_footer(); ?>

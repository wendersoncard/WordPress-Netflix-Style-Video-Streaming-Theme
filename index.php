<?php get_header(); 
	
	$setType = get_theme_mod( 'streamium_main_post_type');
	$setTax = get_theme_mod( 'streamium_main_tax');

	?>
	<main class="cd-main-content">

		<?php get_template_part( 'templates/content', 'slider' ); ?>
		
		<?php 
			$args = array(
			    'posts_per_page' => (int)get_theme_mod( 'streamium_global_options_homepage_desktop' ),
			    'ignore_sticky_posts' => 1,
			    'post_type' => array('movie', 'tv','sport','kid','stream'),
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
			<section class="videos recently-watched">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-12 video-header">
							<h3>Recently Watched</h3>
						</div><!--/.col-sm-12-->
					</div>
					<div class="row">
						<div class="col-sm-12"> 
							<div class="carousels" id="recently">
								<?php
									if($loop->have_posts()):
										while ( $loop->have_posts() ) : $loop->the_post();
											if ( has_post_thumbnail() ) : // thumbnail check 
											$image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-tile' );
											$imageExpanded   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-tile-expanded' );
											$nonce = wp_create_nonce( 'streamium_likes_nonce' );

								?>
								<div class="tile" data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="recent">

									<div class="tile_inner" style="background-image: url(<?php echo esc_url($image[0]); ?>);">

										<div class="content">
									      	<div class="overlay" style="background-image: url(<?php echo esc_url($imageExpanded[0]); ?>);">
									      		<div class="overlay-gradient"></div>
									        	<a class="play-icon-wrap hidden-xs" href="<?php the_permalink(); ?>">
													<div class="play-icon-wrap-rel">
														<div class="play-icon-wrap-rel-ring"></div>
														<span class="play-icon-wrap-rel-play">
															<i class="fa fa-play fa-1x" aria-hidden="true"></i>
										        		</span>
									        		</div>
								        		</a>
									          	<div class="overlay-meta hidden-xs">
									            	<h4><?php the_title(); ?></h4>						            	
									            	<a data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="recent" class="tile_meta_more_info hidden-xs"><i class="icon-streamium" aria-hidden="true"></i></a>
									          	</div>
									      	</div>
									    </div>

									</div>

									<?php do_action('synopis_video_progress'); ?>
								        
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
										<div class="synopis content"></div>
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
				        	<a href="#" class="synopis-video-trailer streamium-btns hidden-xs">Watch Trailer</a>
				        	<a href="#" class="s3bubble-details-inner-close"><i class="fa fa-times" aria-hidden="true"></i></a>
						</div><!--/.col-sm-12-->
					</div><!--/.row-->
				</div><!--/.container-->
			</section><!--/.videos-->
		<?php endif; ?>

		<?php 

		$postTypes = array(
			array('tax' => 'movies','type' => 'movie','menu' => 'Movies'),
            array('tax' => 'programs','type' => 'tv','menu' => 'TV Programs'),
            array('tax' => 'sports','type' => 'sport','menu' => 'Sport'),
            array('tax' => 'kids','type' => 'kid','menu' => 'Kids'),
            array('tax' => 'streams','type' => 'stream','menu' => 'Streams')
        );

		foreach ($postTypes as $key => $value) : ?> 
			
			<?php 
				
				$tax = $value['tax'];
				$type = $value['type'];
				$menu = $value['menu'];

				if ( get_theme_mod( 'streamium_section_checkbox_enable_' . $tax ) && $type != $setType) :

					$taxTitle = (get_theme_mod( 'streamium_section_input_menu_text_' . $type )) ? get_theme_mod( 'streamium_section_input_menu_text_' . $type ) : $menu;
					$taxUrls =  (get_theme_mod( 'streamium_section_input_posttype_' . $type )) ? get_theme_mod( 'streamium_section_input_posttype_' . $type ) : $type;

			?>

				<section class="videos">
					<div class="container-fluid">
						<div class="row">
							<div class="col-sm-12 video-header">
								<h3><?php _e( $taxTitle, 'streamium' ); ?></h3>
								<a class="see-all" href="<?php echo esc_url( home_url('/' . $taxUrls ) ); ?>">View all</a>
							</div><!--/.col-sm-12-->
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="carousels" id="<?php echo $type; ?>">
							  	<?php
									$args = array(
										    'posts_per_page' => (int)get_theme_mod( 'streamium_global_options_homepage_desktop' ),
										    'post_type' => $type
										);
										$loop = new WP_Query( $args ); 
										if($loop->have_posts()):
											while ( $loop->have_posts() ) : $loop->the_post();
											
											$image  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-tile' );
											$imageExpanded   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-tile-expanded' );
											$nonce = wp_create_nonce( 'streamium_likes_nonce' ); 

								?>
									<div class="tile" data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="<?php echo $type; ?>">
										
										<div class="tile_inner" style="background-image: url(<?php echo esc_url($image[0]); ?>);">

											<?php do_action('streamium_video_payment'); ?>
											
											<div class="content">
										      	<div class="overlay" style="background-image: url(<?php echo esc_url($imageExpanded[0]); ?>);">
										      		<div class="overlay-gradient"></div>
										        	<a class="play-icon-wrap hidden-xs" href="<?php the_permalink(); ?>">
														<div class="play-icon-wrap-rel">
															<div class="play-icon-wrap-rel-ring"></div>
															<span class="play-icon-wrap-rel-play">
																<i class="fa fa-play fa-1x" aria-hidden="true"></i>
											        		</span>
										        		</div>
									        		</a>
										          	<div class="overlay-meta hidden-xs">
										            	<h4><?php the_title(); ?></h4>						            		
										            	<a data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="<?php echo $type; ?>" class="tile_meta_more_info hidden-xs"><i class="icon-streamium" aria-hidden="true"></i></a>
										          	</div>
										      	</div>
										    </div>

										</div>

										<?php do_action('synopis_video_progress'); ?>

								    </div>
								<?php
						
									endwhile;
									endif;
									wp_reset_query();
								?>
								</div><!--/.carousel-->
							</div><!--/.col-sm-12-->
						</div><!--/.row-->
					</div><!--/.container-->
				</section><!--/.videos-->
				<section class="s3bubble-details-full <?php echo $type; ?>">
					<div class="s3bubble-details-full-overlay"></div>
					<div class="container-fluid s3bubble-details-inner-content">
						<div class="row">
							<div class="col-sm-5 col-xs-5 rel">
								<div class="synopis-outer">
									<div class="synopis-middle">
										<div class="synopis-inner">
											<h2 class="synopis hidden-xs"></h2>
											<div class="synopis content"></div>
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
					        	<a href="#" class="synopis-video-trailer streamium-btns hidden-xs">Watch Trailer</a>
					        	<a href="#" class="s3bubble-details-inner-close"><i class="fa fa-times" aria-hidden="true"></i></a>
							</div><!--/.col-sm-12-->
						</div><!--/.row-->
					</div><!--/.container-->
					<div class="program-carousels"></div><!--/.program-carousels-->
				</section><!--/.videos-->

			<?php 
			
				endif; 
			
			?>
			
		<?php 

			endforeach;

		?>

		<?php 

			$args = array(
			  'parent' => 0,
			  'hide_empty' => false
			);
			$categories = get_terms( $setTax, $args );

		  	foreach ($categories as $category) : 

				$typeTitle =  (get_theme_mod( 'streamium_section_input_posttype_' . $setType )) ? get_theme_mod( 'streamium_section_input_posttype_' . $setType ) : $setType;

				$taxUrl =  (get_theme_mod( 'streamium_section_input_taxonomy_' . $setTax )) ? get_theme_mod( 'streamium_section_input_taxonomy_' . $setTax ) : $setTax;
		?>

	  	<section class="videos">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 video-header">
						<h3><?php echo ucwords($typeTitle); ?> <i class="fa fa-chevron-right" aria-hidden="true"></i> <?php echo ucfirst($category->name); ?></h3>
						<a class="see-all" href="<?php echo esc_url( home_url() ); ?>/<?php echo $taxUrl; ?>/<?php echo $category->slug; ?>">View all</a>
					</div><!--/.col-sm-12-->
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="carousels" id="<?php echo $category->slug; ?>">
					  	<?php

						  	$args = array(
							    'posts_per_page' => (int)get_theme_mod( 'streamium_global_options_homepage_desktop' ),
							    'tax_query' => array(
							        array(
							            'taxonomy'  => $setTax,
							            'field'     => 'term_id',
							            'terms'     => $category->term_id,
							        )
							    )
							);

							$loop = new WP_Query( $args ); 
								if($loop->have_posts()):
									while ( $loop->have_posts() ) : $loop->the_post();
									if ( has_post_thumbnail() ) : // thumbnail check 
									$image  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-tile' );
									$imageExpanded   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-tile-expanded' );
									$nonce = wp_create_nonce( 'streamium_likes_nonce' ); 

						?>
							<div class="tile" data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="<?php echo $category->slug; ?>">

								<div class="tile_inner" style="background-image: url(<?php echo esc_url($image[0]); ?>);">

									<?php do_action('streamium_video_payment'); ?>
									
									<div class="content">
								      <div class="overlay" style="background-image: url(<?php echo esc_url($imageExpanded[0]); ?>);">
								        <div class="overlay-gradient"></div>
								        <a class="play-icon-wrap hidden-xs" href="<?php the_permalink(); ?>">
											<div class="play-icon-wrap-rel">
												<div class="play-icon-wrap-rel-ring"></div>
												<span class="play-icon-wrap-rel-play">
													<i class="fa fa-play fa-1x" aria-hidden="true"></i>
									        	</span>
								        	</div>
							        	</a>
							          	<div class="overlay-meta hidden-xs">
							            	<h4><?php the_title(); ?></h4>						            	
							            	<a data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="<?php echo $category->slug; ?>" class="tile_meta_more_info hidden-xs"><i class="icon-streamium" aria-hidden="true"></i></a>
							          	</div>
								      </div>
								    </div>
								</div>

								<?php do_action('synopis_video_progress'); ?>

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
									<div class="synopis content"></div>
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
			        	<a href="#" class="synopis-video-trailer streamium-btns hidden-xs">Watch Trailer</a>
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
<?php get_header(); ?>
	<main class="cd-main-content">

		<div class="main-spacer"></div>

		<?php 
			$args = array(
			  'parent' => 0,
			  'type' => 'programs',
			  'hide_empty' => true
			);
		  	$categories = get_categories($args); 
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
								    'post_type' => 'programs',
								    'cat' => $category->cat_ID
								);
								$loop = new WP_Query( $args ); 
								if($loop->have_posts()):
									while ( $loop->have_posts() ) : $loop->the_post();
									if ( has_post_thumbnail() ) : // thumbnail check 
									$image  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-category' );
									$nonce = wp_create_nonce( 'streamium_likes_nonce' ); 

						?>
							<div class="tile" data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="<?php echo $category->slug; ?>">
								<?php if($post->premium) : ?>
									<div class="tile_payment_details">
										<div class="tile_payment_details_inner">
											<h2>Available on <?php echo str_replace(array("_"), " ", $post->plans[0]); ?></h2>
										</div>
									</div> 
								<?php endif; ?>
								<?php if (function_exists('is_protected_by_s2member')) :
									$check = is_protected_by_s2member(get_the_ID());
									if($check) : ?>
									<div class="tile_payment_details">
										<div class="tile_payment_details_inner">
											<h2>Available on <?php 
												$comma_separated = implode(",", $check);
												echo "plan " . $comma_separated; 
											?></h2>
										</div>
									</div>
								<?php endif; endif; ?>
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
						            	<a data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="<?php echo $category->slug; ?>" class="tile_meta_more_info hidden-xs"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
						          	</div>
						        </div>
						        <?php if(is_user_logged_in() && get_theme_mod( 'streamium_enable_premium' )):
							    		$userId = get_current_user_id();
							    		$percentageWatched = get_post_meta( get_the_ID(), 'user_' . $userId, true );
							    ?>
								    <div class="progress tile_progress">
									  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $percentageWatched; ?>"
									  aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentageWatched; ?>%">
									    <span class="sr-only"><?php echo $percentageWatched; ?>% Complete</span>
									  </div>
									</div>
								<?php endif; ?>
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
			        	<a href="#" class="synopis-video-trailer streamium-btns">Watch Trailer</a>
			        	<a href="#" class="s3bubble-details-inner-close"><i class="fa fa-times" aria-hidden="true"></i></a>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->
		</section><!--/.videos-->
			
		<?php  	
			endforeach; 
		?>

		<div class="main-spacer"></div>

<?php get_footer(); ?>
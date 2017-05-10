<?php get_header(); ?>
	<main class="cd-main-content">

		<div class="main-spacer"></div>

		<section class="categories">
			<?php 

				$query = $wp_query->get_queried_object(); 
     			$tax = isset($query->taxonomy) ? $query->taxonomy : "";

				switch (isset($_GET['sort']) ? $_GET['sort'] : 'all') {
					case 'reviewed':

						remove_all_filters('posts_fields');
					    remove_all_filters('posts_join');
					    remove_all_filters('posts_groupby');
					    remove_all_filters('posts_orderby');
					    add_filter( 'posts_fields', 'streamium_search_distinct' );
						add_filter( 'posts_join','streamium_search_join');
						add_filter( 'posts_groupby', 'streamium_search_groupby' );
						add_filter( 'posts_orderby', 'streamium_search_orderby' );
						$the_query = new WP_Query( 
							array(
								'posts_per_page' => -1, 
								'ignore_sticky_posts' => true,
							    'tax_query' => array(
							        array(
							            'taxonomy'  => $tax,
							            'field'     => 'term_id',
							            'terms'     => $query->term_id,
							        )
							    ),
								'orderby' => 'date',
								'order'   => 'DESC', 
							) 
						);

						break;

					case 'newest':
						
						remove_all_filters('posts_fields');
					    remove_all_filters('posts_join');
					    remove_all_filters('posts_groupby');
					    remove_all_filters('posts_orderby');
					   
						$the_query = new WP_Query( 
							array(
								'posts_per_page' => -1, 
								'ignore_sticky_posts' => true,
							    'tax_query' => array(
							        array(
							            'taxonomy'  => $tax,
							            'field'     => 'term_id',
							            'terms'     => $query->term_id,
							        )
							    ),
								'orderby' => 'date',
								'order'   => 'DESC', 
							) 
						);
					
						break;

					case 'oldest':

						remove_all_filters('posts_fields');
					    remove_all_filters('posts_join');
					    remove_all_filters('posts_groupby');
					    remove_all_filters('posts_orderby');
					    
						$the_query = new WP_Query( 
							array(
								'posts_per_page' => -1, 
								'ignore_sticky_posts' => true,
							    'tax_query' => array(
							        array(
							            'taxonomy'  => $tax,
							            'field'     => 'term_id',
							            'terms'     => $query->term_id,
							        )
							    ),
								'orderby' => 'date',
								'order'   => 'ASC', 
							) 
						);
					
						break;
					
					default:

						$the_query = new WP_Query( 
							array(
								'posts_per_page' => -1, 
								'ignore_sticky_posts' => true,
							    'tax_query' => array(
							        array(
							            'taxonomy'  => $tax,
							            'field'     => 'term_id',
							            'terms'     => $query->term_id,
							        )
							    )
							) 
						);

						break;
				}
				
				if ( $the_query->have_posts() ) : 
			?>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 video-header-archive">
						<h3><?php printf( __( 'Viewing: %s', 'streamium' ), single_cat_title( '', false ) ); ?></h3>
						<?php if(get_theme_mod( 'streamium_enable_premium' )) : ?>
							<div class="dropdown video-header-archive-dropdown">
							  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							    FILTER
							    <span class="caret"></span>
							  </button>
							  <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
							    <li><a href="?sort=all">View All</a></li>
							    <li><a href="?sort=reviewed">Most Reviews</a></li>
							    <li><a href="?sort=newest">Recently Added</a></li>
							    <li><a href="?sort=oldest">Oldest First</a></li>
							  </ul>
							</div>
						<?php endif; ?>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->
			<div class="container-fluid">
				<div class="row static-row static-row-first">
					<?php
						
						$count = 0;
						$cat_count = 0; 
						$total_count = $the_query->post_count;

						while ( $the_query->have_posts() ) : $the_query->the_post(); 
						$image  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-category' );
						$imageExpanded   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-tile-expanded' );
						$nonce = wp_create_nonce( 'streamium_likes_nonce' ); 
						?>
						<div class="col-xs-6 col-md-5ths tile" data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="static-<?php echo $cat_count; ?>">
							
							<div class="tile_inner" style="background-image: url(<?php echo esc_url($image[0]); ?>);">

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
						          	<div class="overlay-meta">
						            	<h4><?php the_title(); ?></h4>						            	
						            	<a data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="static-<?php echo $cat_count; ?>" class="tile_meta_more_info hidden-xs"><i class="icon-streamium" aria-hidden="true"></i></a>
						          	</div>
							      </div>
							    </div>

							</div>

							<?php do_action('synopis_video_progress'); ?>

						</div>
						<?php
							$count++;
  							if ($count % (isMobile() ? 2 : 5) == 0 || $count == $total_count) { 
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
						</section><div class="container-fluid"><div class="row static-row">
					<?php $cat_count++; } ?>
					<?php endwhile; ?>
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
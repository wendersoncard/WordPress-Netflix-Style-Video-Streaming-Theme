<?php get_header(); ?>
	<main class="cd-main-content">
		
		<div class="main-spacer"></div>

		<section class="categories">

			<?php

			switch (isset($_GET['date']) ? 'date' : 'all') {
				case 'date':

					if($_GET['date'] === 'day'){

						$the_query = new WP_Query(array(
						    'post_type' => 'post',
						    'posts_per_page' => -1, 
							'ignore_sticky_posts' => true, 
							'date_query' => array(
							     array(
							        'after'     => '1 day ago'
							     ),
							 ),
					
						));

					}else if($_GET['date'] === 'week'){

						$the_query = new WP_Query(array(
						    'post_type' => 'post',
						    'posts_per_page' => -1, 
							'ignore_sticky_posts' => true, 
							'date_query' => array(
							     array(
							        'after' => '1 week ago'
							     ),
							 ),
					
						));

					}else{

						if(strpos($_GET['date'], '/') !== false) {

							$date = explode('/', $_GET['date']);
							$year  = $date[0];
							$month = $date[1];
							$day   = $date[2];
							$the_query = new WP_Query(array(
							    'post_type' => 'post',
							    'posts_per_page' => -1, 
								'ignore_sticky_posts' => true, 
								'date_query' => array(
								    array(
								      'year' => $year,
								      'month' => $month,
								      //'day' => $day 
								      ),
								    ),
						
							));

						}else{

							$the_query = new WP_Query(array(
							    'post_type' => 'post',
							    'posts_per_page' => -1, 
								'ignore_sticky_posts' => true, 
								'date_query' => array(
								    array(
								      'year' => $_GET['date']
								      ),
								    ),
						
							));

						}
					}

					break;
				
				default:

					$the_query = new WP_Query(array(
					    'post_type' => 'post', 
						's' => $s
					)); 

					break;

			} 
			?>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 video-header-archive">
						<h3 class="pull-left"><?php printf( __( 'Search Results for: %s', 'streamium' ), get_search_query() ); ?></h3>
						<?php if(get_theme_mod( 'streamium_enable_premium' )) : ?>
							<div class="dropdown video-header-archive-dropdown">
							  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							    FILTER
							    <span class="caret"></span>
							  </button>
							  <ul class="dropdown-menu dropdown-menu-right streamium-menu-filter" aria-labelledby="dropdownMenu1">
							  	<li><a href="?s=all&date=day">1 Day Ago</a></li>
							  	<li><a href="?s=all&date=week">1 Week Ago</a></li>
							  	<?php
							  		$begin = new DateTime( '2015-01-01' );
									$end = new DateTime();
									$interval = DateInterval::createFromDateString('1 year');
									$period = new DatePeriod($begin, $interval, $end);
									foreach ( array_reverse(iterator_to_array($period)) as $dt ) : ?>
										<li><a href="?s=all&date=<?php echo $dt->format( "Y" ); ?>"><?php echo $dt->format( "Y" ); ?></a></li>
								<?php 
									endforeach; 
								?>
							  	<?php
							  		$begin = new DateTime( '2015-01-01' );
									$end = new DateTime();
									$interval = DateInterval::createFromDateString('1 month');
									$period = new DatePeriod($begin, $interval, $end);
									foreach ( array_reverse(iterator_to_array($period)) as $dt ) : ?>
										<li><a href="?s=all&date=<?php echo $dt->format( "Y/m/d" ); ?>"><?php echo $dt->format( "M Y" ); ?></a></li>
								<?php 
									endforeach; 
								?>
							  </ul>
							</div>
						<?php endif; ?>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->
			<?php if ( $the_query->have_posts() ) : ?> 
			<div class="container-fluid">
				<div class="row static-row static-row-first">
					<?php
						
						$count = 0;
						$cat_count = 0; 
						$total_count = $the_query->post_count;
						while ( $the_query->have_posts() ) : $the_query->the_post(); 

						$image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-poster' );
						$imageExpanded   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-tile-expanded' );
						$tileImage = empty($image[0]) ? 'http://placehold.it/260x146' : esc_url($image[0]);
						$nonce = wp_create_nonce( 'streamium_likes_nonce' ); 
						?>
						<div class="col-md-5ths tile" data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="static-<?php echo $cat_count; ?>">

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
						          	<div class="overlay-meta hidden-xs">
						            	<h4><?php the_title(); ?></h4>						            	
						            	<a data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="static-<?php echo $cat_count; ?>" class="tile_meta_more_info hidden-xs"><i class="icon-streamium" aria-hidden="true"></i></a>
						          	</div>
							      </div>
							    </div>

							</div>

							<?php if(is_user_logged_in() && get_theme_mod( 'streamium_enable_premium' )):
						    		$userId = get_current_user_id();
						    		$percentageWatched = get_post_meta( get_the_ID(), 'user_' . $userId, true );
						    ?>
							    <div class="progress tile_progress">
								  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $percentageWatched; ?>"
								  aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentageWatched; ?>%">
								  </div>
								</div>
							<?php endif; ?>

						</div>
						<?php
							$count++;
  							if ($count % (isMobile() ? 1 : 5) == 0 || $count == $total_count) { 
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
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 video-header-archive">
						<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'twentyseventeen' ); ?></p>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.row-->
			<?php endif; ?>
		</section><!--/.videos-->

		<div class="main-spacer"></div>
		
<?php get_footer(); ?>
<?php get_header(); ?>

	<header class="cd-main-header fixed">

		<?php if ( get_theme_mod( 'streamium_logo' ) ) : ?>

		    <a class="cd-logo" href="<?php echo esc_url( home_url('/') ); ?>"><img src='<?php echo esc_url( get_theme_mod( 'streamium_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a>

		<?php else : ?>

		    <a class="cd-logo" href="<?php echo esc_url( home_url('/') ); ?>"><?php bloginfo( 'name' ); ?></a>

		<?php endif; ?>

		<ul class="cd-header-buttons">
			<li><a class="cd-search-trigger" href="#cd-search"><?php _e( 'Search', 'streamium' ); ?><span></span></a></li>
			<li><a class="cd-nav-trigger" href="#cd-primary-nav"><?php _e( 'Menu', 'streamium' ); ?><span></span></a></li>
		</ul> <!-- cd-header-buttons -->
		<?php get_search_form(); ?>
		
	</header>

	<main class="cd-main-content">

		<section class="categories">
			<?php 

				$category = $wp_query->get_queried_object();
				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

				$the_query = new WP_Query( 
					array(
					    'cat' => $category->cat_ID,
						'paged' => $paged,
						'ignore_sticky_posts' => true
					) 
				);
				
				if ( $the_query->have_posts() ) : 
			?>
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
						
						$count = 0;
						$cat_count = 0; 
						$total_count = $the_query->post_count;

						while ( $the_query->have_posts() ) : $the_query->the_post(); 
						$image  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-tile' );
						$imageExpanded   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-tile-expanded' );
						$nonce = wp_create_nonce( 'streamium_likes_nonce' );
						$trimexcerpt = !empty(get_the_excerpt()) ? get_the_excerpt() : get_the_content(); 

						?>
						<div class="col-md-2 col-xs-6 tile" data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="static-<?php echo $cat_count; ?>">
							
							<div class="tile_inner" style="background-image: url(<?php echo esc_url($image[0]); ?>);">

								<div class="content">
							      	<div class="overlay" style="background-image: url(<?php echo esc_url($imageExpanded[0]); ?>);">
							        	<div class="overlay-gradient"></div>
							        	<a class="play-icon-wrap hidden-xs" href="<?php the_permalink(); ?>">
											<div class="play-icon-wrap-rel">
												<div class="play-icon-wrap-rel-ring"></div>
												<span class="play-icon-wrap-rel-play">
													<i class="fa fa-eye fa-1x" aria-hidden="true"></i>
								        		</span>
							        		</div>
						        		</a>
						          		<div class="overlay-meta">
						            		<h4><?php the_title(); ?></h4>
						            		<p><?php echo wp_trim_words( $trimexcerpt, $num_words = 30, $more = '...' ); ?></p>
						            		<a data-id="<?php the_ID(); ?>" data-nonce="<?php echo $nonce; ?>" data-cat="static-<?php echo $cat_count; ?>" class="tile_meta_more_info hidden-xs"><i class="icon-streamium" aria-hidden="true"></i></a>
						          		</div>
							      	</div>
							    </div>

							</div>

						</div>
						<?php
							$count++;
  							if ($count % streamium_get_device('count') == 0 || $count == $total_count) { 
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
													<i class="fa fa-eye fa-3x" aria-hidden="true"></i>
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
				
				<?php if (function_exists("streamium_pagination")) : ?>
					<div class="row">
						<div class="col-sm-12">
				    		<?php streamium_pagination(); ?>
				    	</div>
					</div><!--/.row-->
				<?php endif; ?>

			</div><!--/.container-->
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</section><!--/.videos-->

		<div class="main-spacer"></div>

		<div class="cd-overlay"></div>

	</main><!--/.main content-->

<?php get_footer(); ?>
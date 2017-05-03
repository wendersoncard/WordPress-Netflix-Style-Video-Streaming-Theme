<?php get_header(); ?>
	<main class="cd-main-content">

		<div class="main-spacer"></div>

		<section class="categories">
			<?php 

				$the_query = new WP_Query( array(
					'posts_per_page' => -1, 
				    'post_type' => 'programs',          // name of post type.
				    'tax_query' => array(
				        array(
				            'taxonomy' => 'tv',   // taxonomy name
				            'field' => 'slug',           // term_id, slug or name
				            'terms' => $term,                  // term id, term slug or term name
				        )
				    )
				) );
				
				if ( $the_query->have_posts() ) : 

				$program_meta = get_term_by( 'slug' , $term, 'tv' );
				$image = get_tax_meta($program_meta->term_id,'streamium_program_image');

			?>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 video-header-archive">
						<h3><?php printf( __( 'Viewing: %s', 'streamium' ), single_cat_title( '', false ) ); ?></h3>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->
			<div class="container-fluid">
				<div class="row">
				    <div class="col-md-4">
				    	<img src="<?php echo esc_url($image[0]['url']); ?>" class="img-responsive" />
				    	<p><?php echo $program_meta->description; ?></p>
				    	<span class="synopis-meta-spacer">Staring: <a href="/?s=Brad Pitt" tabindex="0">Brad Pitt</a>, <a href="/?s=Chris Pratt" tabindex="0">Chris Pratt</a>, <a href="/?s=Will Smith" tabindex="0">Will Smith</a></span>
				    	<span class="synopis-meta-spacer">Genres: <a href="http://local.streamium.com/category/action" tabindex="0">Action</a>, <a href="http://local.streamium.com/category/comedies" tabindex="0">Comedies</a>, <a href="http://local.streamium.com/category/comedy" tabindex="0">Comedy</a></span>
				    	<span class="synopis-meta-spacer">Released: May 1st 2017</span>
				    </div>
				    <div class="col-md-8">
					<?php
						
						$count = 0;
						$cat_count = 0; 
						$total_count = $the_query->post_count;

						while ( $the_query->have_posts() ) : $the_query->the_post(); 
						$image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-video-poster' );
						$tileImage = empty($image[0]) ? 'http://placehold.it/260x146' : esc_url($image[0]);
						$nonce = wp_create_nonce( 'streamium_likes_nonce' ); 
						?>
						<div class="media">
						  <a class="media-left media-top" href="<?php the_permalink(); ?>">
						    <img src="<?php echo $tileImage; ?>" class="media-object" style="width:130px">
						  </a>
						  <div class="media-body">
						    <h4 class="media-heading"><?php the_title(); ?></h4>
						    <p><?php the_content(); ?></p>
						  </div>
						</div>
					<?php endwhile; ?>
					</div>
				</div><!--/.row-->
			</div><!--/.container-->
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</section><!--/.videos-->

		<div class="main-spacer"></div>

<?php get_footer(); ?>
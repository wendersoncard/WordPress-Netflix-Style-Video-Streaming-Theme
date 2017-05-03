

<?php get_header(); ?>

	<main class="cd-main-content">

		<div class="main-spacer-program"></div>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
			$image   = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-home-slider' ); 
		?>

		<div id="s3bubble-<?php echo get_the_ID(); ?>"></div>

		<section class="categories">

			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-12 video-header-archive">
						<h3><?php the_title(); ?></h3>
					</div><!--/.col-sm-12-->
				</div><!--/.row-->
			</div><!--/.container-->
			<div class="container-fluid">
				<div class="row">
				    <div class="col-md-4">
				    	<img src="<?php echo esc_url($image[0]); ?>" class="img-responsive" />
				    	<p><?php the_content(); ?></p>
				    	<ul>
							<?php do_action('synopis_meta'); ?>
						</ul>
				    </div>
				    <div class="col-md-8">
					<?php
						foreach (get_post_meta(get_the_ID(), 'repeatable_fields' , true) as $key => $value) : 
							?>
							<div class="media">
							  <a class="media-left media-top streamium-program-update-video" href="<?php echo $value['codes']; ?>">
							    <img src="<?php echo esc_url($image[0]); ?>" class="media-object" style="width:130px">
							  </a>
							  <div class="media-body">
							    <h4 class="media-heading"><?php echo $value['titles']; ?></h4>
							    <p><?php echo $value['descriptions']; ?></p>
							  </div>
							</div>
					<?php 
						endforeach; 
					?>
					</div>
				</div><!--/.row-->
			</div><!--/.container-->
			<?php endwhile; else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</section><!--/.videos-->

		<div class="main-spacer"></div>

<?php get_footer(); ?>
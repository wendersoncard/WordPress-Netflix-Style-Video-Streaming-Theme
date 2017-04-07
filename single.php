<?php if($_GET['detailed']){ ?>
	
<?php get_header(); ?>
<main class="cd-main-content page-template">
	
	<div class="main-spacer"></div>
	
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

					<?php
				    	$s3videoid = get_post_meta( get_the_ID(), 's3bubble_video_code_meta_box_text', true );
						// Check if the custom field has a value.
						if ( ! empty( $s3videoid ) ) : ?>
							<div style="position: relative;padding-bottom: 56.25%;"><iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" src="<?php echo $s3videoid; ?>" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>
					<?php endif; ?>

				 	<?php the_content(); ?>					 

				<?php endwhile; else : ?>

				 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

				<?php endif; ?>

				<?php comments_template(); ?> 
				
			</div>
		</div>
	</div>

	<div class="main-spacer"></div>
	
<?php get_footer(); ?>

<?php } else{ ?>

<?php get_template_part( 'header', 'video'); ?>

<div class="video-main">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    	<div id="s3bubble-aws" class="video-wrapper">
    		<?php
		    	$s3videoid = get_post_meta( get_the_ID(), 's3bubble_video_code_meta_box_text', true );
				// Check if the custom field has a value.
				if ( ! empty( $s3videoid ) ) : ?>
					<div style="position: relative;padding-bottom: 56.25%;"><iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" src="<?php echo $s3videoid; ?>" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>
			<?php endif; ?>
		 	<?php //the_content(); ?>
		</div>

	 <?php endwhile; else : ?>

	 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

	 <?php endif; ?>

</div>

<?php
	if (is_user_logged_in()) : 
	   update_post_meta($post->ID,'recently_watched',current_time('mysql')); 
	   update_post_meta($post->ID,'recently_watched_user_id',get_current_user_id());
	endif;

	/*$s3videoid = get_post_meta( get_the_ID(), 's3bubble_video_code_meta_box_text', true );
	// Check if the custom field has a value.
	if ( ! empty( $s3videoid ) ) {
	    echo '<script type="text/javascript">S3BubbleAWS.init({id : "s3bubble-aws",code : "' . $s3videoid . '"});</script>';
	}*/

?>

<?php get_template_part( 'footer', 'video' ); ?>

<?php } ?>
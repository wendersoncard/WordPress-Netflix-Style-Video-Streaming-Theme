

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
						$episodes = get_post_meta(get_the_ID(), 'repeatable_fields' , true);
						if(!empty($episodes)) : 
							foreach ($episodes as $key => $value) : 
							?>
							<div class="media">
							  <a class="media-left media-top streamium-program-update-video" data-id="<?php echo $key; ?>">
							    <img src="<?php echo esc_url($value['thumbnails']); ?>" class="media-object" style="width:130px">
							  </a>
							  <div class="media-body">
							    <h4 class="media-heading"><?php echo $value['titles']; ?></h4>
							    <p><?php echo $value['descriptions']; ?></p>
							  </div>
							</div>
					<?php 
							endforeach; 
						endif;
					?>
					</div>
				</div><!--/.row-->
			</div><!--/.container-->
			<?php endwhile; else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>
		</section><!--/.videos-->

		<div class="main-spacer"></div>
<script type="text/javascript">

jQuery(document).ready(function($) {

	S3Bubble.player({
		id : "s3bubble-" + video_post_object.post_id,
		codes : video_post_object.codes,
		//startTime : null,
		playerEnded  : function(player){
			console.log("boosh");
		},
		playerLoaded  : function(player){

			player.on("timeupdate", function() {

			    var current = this.currentTime();
			    var duration = this.duration();
			    var percentage = current / duration * 100;
			    window.percentage = Math.round(parseInt(percentage));

			});

			(function updateResumePercentage() {

				$.ajax({
			        url: streamium_object.ajax_url,
			        type: 'post',
			        dataType: 'json',
			        data: {
			            action: 'streamium_create_resume',
			            percentage : (window.percentage) ? window.percentage : 0,
			            post_id: video_post_object.post_id,
			            nonce: video_post_object.nonce
			        },
			        success: function(response) {

			        	setTimeout(updateResumePercentage, 1000);

			        }
			    }); // end jquery 

			}());

			player.play();

		}
	});

});
</script>
<?php get_footer(); ?>
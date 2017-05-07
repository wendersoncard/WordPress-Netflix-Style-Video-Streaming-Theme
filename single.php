<?php get_template_part( 'header', 'video'); ?>

<div class="video-main">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    	<div id="s3bubble-<?php echo get_the_ID(); ?>" class="video-wrapper"></div>

	 <?php endwhile; else : ?>

	 	<p><?php _e( 'Sorry, no posts matched your criteria.', 'streamium' ); ?></p>

	 <?php endif; ?>

</div>

<?php
	if (is_user_logged_in()) : 
	   
	   update_post_meta($post->ID,'recently_watched',current_time('mysql')); 
	   update_post_meta($post->ID,'recently_watched_user_id',get_current_user_id());

	endif;
?>

<script type="text/javascript">

jQuery(document).ready(function($) {

	S3Bubble.player({
		id : "s3bubble-" + video_post_object.post_id,
		codes : video_post_object.codes,
		startTime : video_post_object.percentage,
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
<?php get_template_part( 'footer', 'video' ); ?>
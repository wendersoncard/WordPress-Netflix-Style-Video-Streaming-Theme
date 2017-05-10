<main class="cd-main-content">

<?php
	
	$mainImage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'streamium-home-slider' );
	$mainImage = isset( $mainImage[0] ) ? $mainImage[0] : "http://placehold.it/350x150";
	$streamiumVideoTrailer = get_post_meta( $post->ID, 'streamium_video_trailer_meta_box_text', true );
	$epInd = (isset($_GET['trailer']) && isset($streamiumVideoTrailer)) ? 1 : 0;
	$episodes = get_post_meta(get_the_ID(), 'repeatable_fields' , true);

?>

<div id="s3bubble-<?php echo get_the_ID(); ?>" class="program-default-height"></div>

<section class="programs">

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12 video-header-archive">
				<h3><?php the_title(); ?></h3>
			</div><!--/.col-sm-12-->
		</div><!--/.row-->
	</div><!--/.container-->
	<div class="container-fluid">
		<div class="row">
		    <div class="col-md-4 programs-synopis">
		    	<img src="<?php echo esc_url($mainImage); ?>" class="img-responsive" />
		    	<p><?php the_content(); ?></p>
		    	<ul>
					<?php 
						do_action('synopis_multi_meta'); 
					?>
				</ul>
		    </div>
		    <div class="col-md-8">
			<?php
	
				if(!empty($episodes)) :
					foreach ($episodes as $key => $value) :

						$thumbnail = !isset($value['thumbnails']) ? "http://placehold.it/260x146" : esc_url($value['thumbnails']);
						$title = !isset($value['titles']) ? "No Title" : esc_html($value['titles']); 
						$description = !isset($value['descriptions']) ? "No Description" : esc_html($value['descriptions']);  
					
			?>
					<div class="media episodes">
					  	<a class="media-left media-top <?php echo ($epInd === 0) ? "selected" : ""; ?>" data-id="<?php echo $epInd; ?>">
					    	<img src="<?php echo $thumbnail; ?>" class="media-object" />
					  	</a>
					  	<div class="media-body">
					    	<h4 class="media-heading"><?php echo $title; ?></h4>
					    	<p><?php echo $description; ?></p>
					  	</div>
					</div>
			<?php 
					$epInd++;
					endforeach; 
				endif;
			?>
			</div>
		</div><!--/.row-->
	</div><!--/.container-->
</section><!--/.videos-->
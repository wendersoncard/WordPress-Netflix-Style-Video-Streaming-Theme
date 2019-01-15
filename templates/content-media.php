<?php
	$image = get_the_post_thumbnail_url(get_the_ID(),'full'); 
?>

<div class="video-main video-player-streaming" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)), url(<?php echo esc_url( $image ); ?>)">

	<div id="s3bubble-<?php echo get_the_ID(); ?>" class="video-wrapper"></div>

</div>

<div id="filtering" class="carousels-blocks-wrapper"></div>
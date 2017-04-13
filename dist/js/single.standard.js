/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	if(video_post_object.code === null){

		$("#s3bubble-" + video_post_object.post_id).html('<div class="streamium-no-video-content"><h1>No Video</h1><p>A video link has not been added to this post to display a video. Please go to your post and in the right sidebar enter a S3Bubble video url no plugin is needed.</p></div>');

	}else{

		$("#s3bubble-" + video_post_object.post_id).html('<div style="position: relative;padding-bottom: 56.25%;"><iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" src="' + video_post_object.code + '" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>');

	}
	
});
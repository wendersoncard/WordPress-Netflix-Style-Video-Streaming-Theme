/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	function setupStreamiumPlayerLive(){

		console.log("setupStreamiumPlayerLive");
		
		S3Bubble.live({
			id : "s3bubble-" + video_post_object.post_id,
			stream : video_post_object.stream,
			poster : video_post_object.poster,
			meta : {
                backButton: true,
                subTitle: video_post_object.subTitle,
                title: video_post_object.title,
                para: video_post_object.para
            },
			playerEnded  : function(player){
				console.log("boosh");
			},
			playerLoaded  : function(player){

			}
		});

	}

	function setupStreamiumPlayer(){

		console.log("setupStreamiumPlayer");

		S3Bubble.player({
			id : "s3bubble-" + video_post_object.post_id,
			codes : video_post_object.codes,
			startTime : video_post_object.percentage,
			poster : video_post_object.poster,
			meta : {
                backButton: true,
                subTitle: video_post_object.subTitle,
                title: video_post_object.title,
                para: video_post_object.para
            },
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
 
		$('.episodes a').on('click',function(){

			$("html, body").animate({ scrollTop: 0 }, "slow");
			$('.episodes a').removeClass('selected');
			var ind = $(this).data('id');
			$(this).addClass('selected');
			S3Bubble.skip(ind);
			return false;

		});

		$('.streamium-season-filter').on('click', function (e) {
	        $(this).removeClass('active');
	    });

	}

	function setupStreamiumStandardPlayer(){

		if(video_post_object.code === null){

			$("#s3bubble-" + video_post_object.post_id).html('<div class="streamium-no-video-content"><h1>No Video</h1><p>A video link has not been added to this post to display a video. Please go to your post and in the right sidebar enter a S3Bubble video url no plugin is needed.</p></div>');

		}else{ 

			//video_post_object.code = "https://media.s3bubble.com/embed/hls/id/gqtm22989";
			$("#s3bubble-" + video_post_object.post_id).html('<div style="position: relative;padding-bottom: 56.25%;"><iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" src="' + video_post_object.code + '" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>');

		}

	}

	// Check if this is the single post page
    if ($(".streamium-standard .video-player-streaming")[0]){
    	setupStreamiumStandardPlayer();
    }
    if ($(".streamium-premium .video-player-streaming")[0]){
    	setupStreamiumPlayer();
    } 
    if ($(".streamium-premium .video-live-streaming")[0]){
    	setupStreamiumPlayerLive();
    }

});
/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	function setupStreamiumPlayerLive(){
		
		S3Bubble.live({
			id : "s3bubble-" + video_post_object.post_id,
			stream : video_post_object.stream,
			poster : video_post_object.poster,
			fluid : true,
			meta : {
                backButton: true,
                subTitle: video_post_object.subTitle,
                title: video_post_object.title,
                para: video_post_object.para
            },
            playerError  : function(_response){

            	// check for the error message
				if(_response.error){
					$("#" + _response.id).html(_response.message);
				}

			},
			playerEnded  : function(_player){
				
			},
			playerLoaded  : function(_player){

			}
		});
 
	}

	function setupStreamiumPlayer(){

		var setupPlayer = {
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
            playerError  : function(_response){

            	// check for the error message
				if(_response.error){
					$("#" + _response.id).html(_response.message);
				}

			},
			playerEnded  : function(_player){
				
			},
			playerLoaded  : function(_player){

				_player.on("timeupdate", function() {

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

				_player.play();
 
			}
		};

		// Apply youtube setting if it exists
		if(video_post_object.youtube){
			setupPlayer.youtube = true;
		}

		// Set the container to fluid for single video
		if(video_post_object.codes.length === 1){
			setupPlayer.fluid = true;
		}
		
		S3Bubble.player(setupPlayer);
 
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

	// Check if this is the single post page
    if ($(".video-player-streaming")[0]){
    	setupStreamiumPlayer();
    } 
    if ($(".video-live-streaming")[0]){
    	setupStreamiumPlayerLive();
    }

});
/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	function setupStreamiumPlayerLive(){
		 
		// Self hosted
    	s3bubble("s3bubble-" + video_post_object.post_id).live({
			stream : video_post_object.stream,
			source : {
				poster : video_post_object.poster,
			},
			options : {
				fluid : (video_post_object.codes.length === 1) ? true : false,
			},
			meta : {
                backButton: true,
                subTitle: video_post_object.subTitle,
                title: video_post_object.title,
                para: video_post_object.para
            },
        }, function(player) {

			//player.play();

        });
 
	}

	function setupStreamiumPlayer(){

		if(video_post_object.youtube){

			// Self hosted
	    	s3bubble("s3bubble-" + video_post_object.post_id).service({
	            codes : video_post_object.codes,
				startTime : video_post_object.percentage,
				source : {
					poster : video_post_object.poster,
				},
				options : {
					fluid : (video_post_object.codes.length === 1) ? true : false,
				},
				meta : {
	                backButton: true,
	                subTitle: video_post_object.subTitle,
	                title: video_post_object.title,
	                para: video_post_object.para
	            },
	        }, function(player) {

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

	        });

		}else{

			// Self hosted
	    	s3bubble("s3bubble-" + video_post_object.post_id).video({
	            codes : video_post_object.codes,
				startTime : video_post_object.percentage,
				source : {
					poster : video_post_object.poster,
				},
				options : {
					fluid : (video_post_object.codes.length === 1) ? true : false,
				},
				meta : {
	                backButton: true,
	                subTitle: video_post_object.subTitle,
	                title: video_post_object.title,
	                para: video_post_object.para
	            },
	        }, function(player) {

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

				//player.play();
				$('.episodes a').on('click',function(){

					$("html, body").animate({ scrollTop: 0 }, "slow");
					$('.episodes a').removeClass('selected');
					var ind = $(this).data('code');
					$(this).addClass('selected');
					player.playlistSkip(ind);
					return false;

				});

	        });

	    }
 
		

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
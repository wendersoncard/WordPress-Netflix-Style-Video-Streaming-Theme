/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	var ind = 0;
	var code = video_post_object.code;
	var percentage = video_post_object.percentage;
	if(video_post_object.codes.length > 0){
		code = video_post_object.codes[ind];
		percentage = 0;
	}

	streamiumStartVideo(code);

	$('.streamium-program-update-video').on('click',function(){

		ind = $(this).data('id');
		streamiumStartVideo(video_post_object.codes[ind]);
		return false;

	});

	function streamiumStartVideo(code){

		console.log("code",code);
		S3BubbleAWS.init({
			id : "s3bubble-" + video_post_object.post_id,
			code : code,
			startTime : percentage,
			playerLoaded  : function(player){

				player.on("timeupdate", function() {

				    var current = this.currentTime();
				    var duration = this.duration();
				    var percentage = current / duration * 100;
				    window.percentage = Math.round(parseInt(percentage));

				});

				player.on('ended', function() {
				    
				    ind++;
				    streamiumStartVideo(video_post_object.codes[ind]);

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

	}

});
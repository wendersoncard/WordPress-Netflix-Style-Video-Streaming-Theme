/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	S3BubbleAWS.init({
		id : "s3bubble-" + video_post_object.post_id,
		code : video_post_object.code,
		startTime : video_post_object.percentage,
		playerLoaded  : function(player){

			player.on("timeupdate", function() {

			    var current = this.currentTime();
			    var duration = this.duration();
			    var percentage = current / duration * 100;
			    window.percentage = Math.round(parseInt(percentage));

			});

			// ping the database to add a resume time
			window.onbeforeunload = function(){

				$.ajax({
			        url: streamium_object.ajax_url,
			        type: 'post',
			        dataType: 'json',
			        data: {
			            action: 'streamium_create_resume',
			            percentage : window.percentage,
			            post_id: video_post_object.post_id,
			            nonce: video_post_object.nonce
			        },
			        success: function(response) {

			        	console.log("response",response);

			        }

			    }); // end jquery 

			};

		}
	});

});
/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {
 
	function setupStreamiumPlayer(){

		if(video_post_object.youtube){

			s3bubble("s3bubble-" + video_post_object.post_id).service({
	            "codes" : video_post_object.codes,
				"source" : {
					"poster" : video_post_object.poster,
				},
				"options" : {
					"autoplay" : true, 
					"fluid" : true
				},
				"meta" : {
					"showSocial": false,
	                "backButton": true,
	                "backButtonUrl": video_post_object.back,
	                "subTitle": video_post_object.subTitle,
	                "title": video_post_object.title,
	                "para": video_post_object.para
	            },
	            "brand": {
			 		"controlbar": streamium_object.brand_control, // Controlbar background
			 		"icons": streamium_object.brand_icons, // Icon color
			 		"sliders": streamium_object.brand_sliders // Slider color
			 	}
	        }, function(player) {

	        	var runOnce = true;
	        	player.on("timeupdate", function() {

				    var current = this.currentTime();
				    var duration = this.duration();
				    var percentage = current / duration * 100;
				    window.percentage = Math.round(parseInt(percentage));

			    	if(duration && runOnce){
			    		hasDuration(duration);
			    		runOnce = false;
			    	}				    	

				});

				player.on("ended", function() {

					if(video_post_object.hasOwnProperty('index')){

						var ind = parseInt(video_post_object.index);
						if(ind < (parseInt(video_post_object.count)-1)){
							window.location.href = window.location.origin + window.location.pathname + '?v=' + (ind+1);
						}
 
					} 

				});

				function hasDuration(duration){

			    	// Setup some skiptime
	                if (parseInt(video_post_object.percentage) > 0) {
	                    var skipToPercentage = ((duration / 100) * video_post_object.percentage);
	                    player.currentTime(Math.round(parseInt(skipToPercentage)));
	                }

				} 

				(function updateResumePercentage() {

					// Stop if paused
					if(player.paused()){

						setTimeout(updateResumePercentage, 5000);
						return;
					
					}

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

				        	setTimeout(updateResumePercentage, 5000);

				        }
				    }); // end jquery 

				}());

	        });

		}else{  
 
			// Self hosted
	    	s3bubble("s3bubble-" + video_post_object.post_id).video({
	            "codes" : video_post_object.codes,
				"startTime" : video_post_object.percentage,
				"source" : {
					"poster" : video_post_object.poster,
				},
				"options" : {
					"autoplay" : true,
					"fluid" : true,
					"vpaid" : video_post_object.vpaid
				},
				"meta" : {
					"showSocial": video_post_object.brand_social,
	                "backButton": true,
	                "backButtonUrl": video_post_object.back,
	                "subTitle": video_post_object.subTitle,
	                "title": video_post_object.title,
	                "para": video_post_object.para
	            },
	            "brand": {
			 		"controlbar": streamium_object.brand_control, // Controlbar background
			 		"icons": streamium_object.brand_icons, // Icon color
			 		"sliders": streamium_object.brand_sliders // Slider color
			 	}
	        }, function(player) {

	        	player.on("timeupdate", function() {

				    var current = this.currentTime();
				    var duration = this.duration();
				    var percentage = current / duration * 100;
				    window.percentage = Math.round(parseInt(percentage));

				});

				player.on("ended", function() {

					if(video_post_object.hasOwnProperty('index')){

						var ind = parseInt(video_post_object.index);
						if(ind < (parseInt(video_post_object.count)-1)){
							window.location.href = window.location.origin + window.location.pathname + '?v=' + (ind+1);
						}
 
					} 

				});

				(function updateResumePercentage() {

					// Stop if paused
					if(player.paused()){

						setTimeout(updateResumePercentage, 5000);
						return;
					
					}

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

				        	setTimeout(updateResumePercentage, 5000);

				        }
				    }); // end jquery 

				}());
  
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

});
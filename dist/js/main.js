/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	/**
     * Mobile checks throughout
     * @public
     */
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

	$(window).scroll(function (event) {
	    var scroll = $(window).scrollTop();
	    if(scroll > 100){
	    	$(".home .cd-main-header").css("background","rgba(0,0,0,0.9)");
	    }else{
	    	$(".home .cd-main-header").css("background","rgba(0,0,0,0)");
	    }
	});

	// Remove some elements on load
	$(".subscriptio_list_product a").contents().unwrap();
	$(".product-name a").contents().unwrap();
	$(".subscriptio_frontend_items_list_item a").contents().unwrap();
	$(".subscriptio_list_id a").contents().unwrap();
	$(".product-thumbnail").remove();

	
	var tileCount = 5;
	var tileWidth = Math.round($('.container-fluid').width()/tileCount);
	var numberItems = 5;
	var growFactor = 2; 
	var moveDistance = ((tileWidth / 2)-2);
    var currentCat;
	var view_height = Math.round(($(window).innerWidth()/21*9));

	// Set the number of carousel items based on width
	if ($("body.streamium-tablet")[0]){
		numberItems = 4;
	}
	if ($("body.streamium-mobile")[0]){
		numberItems = 2;
	}

	$('.streamium-slider .slick-slide').height(view_height);

	function resizeVideoJS(){
        view_height = Math.round(($(window).innerWidth()/21*9));
		if($('section.recently-watched').length > 0){
			var hrh = parseInt($(".video-header").height())-2;
			$(".streamium-slider").css("margin-bottom", "-" + hrh + "px");
		}
		$('.streamium-slider .slick-slide').height(view_height);
    }

	// Initialise Slider
	$('.streamium-slider').slick({ 
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: false,
      	autoplay: false
	});

	$('.streamium-slider .slick-slide').height(view_height);
	resizeVideoJS();
    window.onresize = resizeVideoJS;

	var itemWidth = Math.round($('.container-fluid').width()/numberItems);
	
	$('.carousels').each(function(i, obj) {

		// setup a caro id for individual arrows
		var sliderId = "#" + $(this).attr("id");

	    $(sliderId).slick({
			slidesToShow: 5,
			slidesToScroll: 5,
			infinite: true,
			adaptiveHeight: true,
			responsive: [{ 
		      breakpoint: 1024,
		      settings: {
		      	appendArrows: false,
		        slidesToShow: 4,
		        slidesToScroll: 4
		      }
		    },
		    {
		      breakpoint: 600,
		      settings: {
		      	appendArrows: false,
		        dots: false
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		      	appendArrows: false,
		        slidesToShow: 2,
		        slidesToScroll: 2
		      }
		    }
		]
		});

	    // hide all slides initally
		$(this).find('.slick-prev').addClass('hidden');

		$(this).on('setPosition', function (event, slick, currentSlide) {

			$(this).find(".slick-active:first").addClass( "far-left" );
			if(slick.slideCount > 5){ // Get the slide count
				$(this).find(".slick-active:last").addClass( "far-right" );
			}

	    });

		$(this).on('beforeChange', function(event, slick, currentSlide, nextSlide){
		 	
		});

		$(this).on('afterChange', function (event, slick, currentSlide) {


			$(this).find(".tile").removeClass( "far-left" ).removeClass( "far-right" );
			$(this).find(".slick-active:first").addClass( "far-left" );
			$(this).find(".slick-active:last").addClass( "far-right" );
	        if(currentSlide === 0) {
	            $(this).find('.slick-prev').addClass('hidden');
	        }
	        else {
	            $(this).find('.slick-prev').removeClass('hidden');
	        }

	    });
 
	});

    $('.s3bubble-details-inner-close').on('click',function(event) {

    	event.preventDefault();
    	var div = $(this).parent().parent().parent();
    	div.animate({
		    opacity: 0,
		}, 250, function() {
			div.parent().animate({
			    height: 0
			}, 250, function() {

			});
		});

    });

	if($("body.streamium-desktop")[0]){

		$('.tile_meta_more_info').on("click",function(event) {

	    	event.preventDefault(); 

	    	var cat = $(this).data('cat');
	    	var post_id = $(this).data('id');
	    	var nonce = $(this).data('nonce');

	    	$.ajax({
	            url: streamium_object.ajax_url,
	            type: 'post',
	            dataType: 'json',
	            data: {
	                action: 'streamium_get_dynamic_content',
	                cat : cat,
	                post_id: post_id,
	                nonce: nonce
	            },
	            success: function(response) {

	                if (response.error) {

	                    swal({
                            title: "Error",
                            text: response.message,
                            type: "info",
                            showCancelButton: true,
                            confirmButtonColor: "#d86c2d",
                            confirmButtonText: "Ok, got it!",
                            closeOnConfirm: true
                        },
                        function() {

                        });
                        
	                    return;

	                }

	                currentCat = "." + response.cat;	                

	                // Populate the expanded view
			    	var twidth = $(currentCat).width();
			    	var theight = Math.floor(twidth/21*8);
			    	$(currentCat).find('h2.synopis').text(response.title);
			    	$(currentCat).find('div.synopis').html(response.content);
			    	$(currentCat).find('a.synopis').attr( "href", response.href);
			    	$(currentCat).css("background-image", "url(" + response.bgimage + ")");
			    	if(response.trailer === ""){
			    		$(currentCat).find('a.synopis-video-trailer').hide();
			    	}else{
			    		$(currentCat).find('a.synopis-video-trailer').fadeIn().attr( "href", response.href + "?trailer=true");
			    	}

			    	var vmiddle = Math.round($('.cd-main-header').height());
					var voff = Math.round($(currentCat).offset().top);
			    	$('html, body').animate({scrollTop: (voff-vmiddle)}, 500);

			        $(currentCat).animate({
					    height: theight
					}, 250, function() {

						$(currentCat + ' .s3bubble-details-inner-content').animate({
						    opacity: 1,
						}, 500, function() {

						});

						// Initailise the tooltips
						$('[data-toggle="tooltip"]').tooltip(); 

					});

	            }

	        }); // end jquery 

		});

		$('head').append('<style type="text/css">' +
			
			'.shiftLeft { transform: translate3d(-' + moveDistance +'px, 0, 0);}' +
			'.shiftRight { transform: translate3d(' + moveDistance +'px, 0, 0);}' +
			'.shiftLeftFirst { transform: translate3d(' + (moveDistance*2) +'px, 0, 0);}' +
			'.shiftRightFirst { transform: translate3d(-' + (moveDistance*2) +'px, 0, 0);}' +

		'</style>');

		$('.tile_inner').hover(function() {

			$(this).find('.streamium-extra-meta').hide();

			if($(this).parent().hasClass("far-left")){
				$(this).parent().nextAll().addClass( "shiftLeftFirst" );
			}else if($(this).parent().hasClass("far-right")){
				$(this).parent().prevAll().addClass( "shiftRightFirst" );
			}else{
				$(this).parent().nextAll().addClass( "shiftRight" );
				$(this).parent().prevAll().addClass( "shiftLeft" );
			}

			$(this).css('transform', 'scale(2)');    	

		}, function() {

			$(this).find('.streamium-extra-meta').fadeIn();

			if($(this).parent().hasClass("far-left")){
				$(this).parent().nextAll().removeClass( "shiftLeftFirst" );
			}else if($(this).parent().hasClass("far-right")){
				$(this).parent().prevAll().removeClass( "shiftRightFirst" );
			}else{
				$(this).parent().nextAll().removeClass( "shiftRight" );
				$(this).parent().prevAll().removeClass( "shiftLeft" );
			}

			$(this).css('transform', 'scale(1)');

		});

	}else{

		$('.tile').on("click",function(event) {

			event.preventDefault(); 

	    	var cat = $(this).data('cat');
	    	var post_id = $(this).data('id');
	    	var nonce = $(this).data('nonce');

	    	$.ajax({
	            url: streamium_object.ajax_url,
	            type: 'post',
	            dataType: 'json',
	            data: {
	                action: 'streamium_get_dynamic_content',
	                cat : cat,
	                post_id: post_id,
	                nonce: nonce
	            },
	            success: function(response) {

	                if (response.error) {

	                    swal({
                            title: "Error",
                            text: response.message,
                            type: "info",
                            showCancelButton: true,
                            confirmButtonColor: "#d86c2d",
                            confirmButtonText: "Ok, got it!",
                            closeOnConfirm: true
                        },
                        function() {

                        });
	                    return;

	                }

	                currentCat = "." + response.cat;

	                // Setup the like and reviews buttons
	                $(currentCat).find('.streamium-list-reviews').attr('data-id', response.id); 
	                $(currentCat).find('.streamium-list-reviews').attr('data-nonce', response.nonce);
	                $(currentCat).find('.like-button').attr('href', response.href);
	                $(currentCat).find('.like-button').attr('data-id', response.id); 
	                $(currentCat).find('.like-button').attr('data-nonce', response.nonce);
	                $(currentCat).find('.like-count').text(response.likes);
	                $(currentCat).find('.like-count').attr('id', 'like-count-' + response.id);
	                

	                // Populate the expanded view
			    	var twidth = $(currentCat).width();
			    	var theight = Math.floor(twidth/21*8);
			    	$(currentCat).find('h2.synopis').text(response.title);
			    	$(currentCat).find('div.synopis').html(response.content).css("max-height",theight + "px");
			    	$(currentCat).find('a.synopis').attr( "href", response.href);
			    	$(currentCat).css("background-image", "url(" + response.bgimage + ")");
			    	if(response.trailer === ""){
			    		$(currentCat).find('a.synopis-video-trailer').hide();
			    	}else{
			    		$(currentCat).find('a.synopis-video-trailer').fadeIn().attr( "href", response.href + "?trailer=true");
			    	}

			    	var vmiddle = Math.round($('.cd-main-header').height());
					var voff = Math.round($(currentCat).offset().top);
			    	$('html, body').animate({scrollTop: (voff-vmiddle)}, 500);

			        $(currentCat).animate({
					    height: theight
					}, 250, function() {

						$(currentCat + ' .s3bubble-details-inner-content').animate({
						    opacity: 1,
						}, 500, function() {

						});

					});

	            }

	        }); // end jquery 

		});

		// Run on mobile
		$('.content .overlay').hide();

	}	

	//setTimeout(function(){ $(".streamium-loading").fadeOut(); },1000);
	$('[data-toggle="tooltip"]').tooltip();

});
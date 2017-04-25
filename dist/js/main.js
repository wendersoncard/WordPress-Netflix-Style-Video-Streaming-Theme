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

	
	var vh = Math.round($(window).innerWidth()/21*9);
	var wh = Math.round($(window).innerWidth());

	function resizeVideoJS(){
        vh = Math.round($(window).innerWidth()/21*9);
		wh = Math.round($(window).innerWidth());
		$('.hero-slider .slider-block').css({'height' : vh,'width' : wh});
		$(".hero").css("margin-bottom", "-" + $(".video-header").height() + "px");
    }  
      
    resizeVideoJS();
    window.onresize = resizeVideoJS; 

	// Initialise Slider
	$('.hero-slider').slick({
		appendArrows: $(this).prev(),
		prevArrow: $('.streamium-prev'),
		nextArrow: $('.streamium-next'),
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: true,
      	autoplay: false,
      	adaptiveHeight: true,
      	//centerMode: true,
        //variableWidth: true,
      	autoplaySpeed: 8000,
      	//mobileFirst: true,
		pauseOnHover: true,
		responsive: [
		    {
		      breakpoint: 1024,
		      settings: {
		        dots: true
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
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    }
		]
	});
	
	$('.carousels').each(function(i, obj) {

		if(wh > 1024){
			numberItems = 6;
		}else if(wh < 1024 && wh > 600){
			numberItems = 4;
		}else if(wh <= 480){
			numberItems = 2;
		}else{
			numberItems = 2;
		}
		var itemWidth = Math.floor($(this).width()/numberItems);
		$('.tile_media').height((Math.floor(itemWidth/16*9)-2));
		$('.tile_payment_details').height((Math.floor(itemWidth/16*9)-2));

	    $(this).slick({
	    	appendArrows: $(this).prev(),
			prevArrow: '<button class="streamium-carousel-prev fa fa-angle-left" aria-hidden="true"></button>',
			nextArrow: '<button class="streamium-carousel-next fa fa-angle-right" aria-hidden="true"></button>',
			slidesToShow: 6,
			slidesToScroll: 6,
			//mobileFirst: true,
				responsive: [{
			      breakpoint: 1024,
			      settings: {
			      	appendArrows: false,
			        slidesToShow: 4,
			        slidesToScroll: 4
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
 
	});

	var tileCount = 6;
	if ($('body').hasClass('category')) {
		tileCount = 5;
	}
	var tileWidth = Math.floor($(window).innerWidth()/tileCount);
	var growFactor = 1.4; 
	var moveLeft = -(tileWidth * (growFactor - 1) / 2);
    var moveRight = (tileWidth-15) * (growFactor - 1);
    var currentCat;

    // adjust the height for payment checks
    $('.static-row .tile_payment_details').height($('.static-row .tile').height());

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

	if(!(isMobile.any())){

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
			    	var theight = twidth/21*8;
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

					});

	            }

	        }); // end jquery 

		});

	    $('.tile').hover(function() {

            $(this).parent().parent().find('.tile').css('opacity', '0.8');
	    	$(this).css('opacity', '1');
		    $(this).find('.tile_details').css('opacity', '1');
		    $(this).find('.play-icon-wrap').css('opacity', '1');

		}, function() {

			$('.tile').css('opacity', '1');
		    $(this).find('.tile_details').css('opacity', '0');
		    $(this).find('.play-icon-wrap').css('opacity', '0');

		});

		$('head').append('<style type="text/css">' +
			'.carousels:hover {transform: translate3d(0px, 0, 0);}' +
			'.carousels:hover .tile:hover {transform: scale(' + growFactor + ');opacity: 1;transition-delay: 0.1s;}' +
			'.carousels .tile:hover ~ .tile {transform: translate3d(' + moveRight +'px, 0, 0);}' +
			'.static-row:hover {transform: translate3d(0px, 0, 0);transition-delay: 0.1s;}' +
			'.static-row:hover .tile:hover {transform: scale(' + growFactor + ');opacity: 1;transition-delay: 0.1s;}' +
			'.static-row .tile:hover ~ .tile {transform: translate3d(' + moveRight +'px, 0, 0);}' +
		'</style>');

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
			    	var theight = Math.round(twidth/21*8);
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
		$('.tile_details').css('opacity', '1');

	}	

	setTimeout(function(){
		$(".streamium-loading").fadeOut();
	},1000);

});
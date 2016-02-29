/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	console.log("loaded...");

	// Initialise Slider
	$('.hero-slider').slick({
		prevArrow: $('.streamium-prev'),
		nextArrow: $('.streamium-next'),
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: true,
      	autoplay: true,
      	adaptiveHeight: true,
      	autoplaySpeed: 8000,
      	mobileFirst: true,
		pauseOnHover: false,
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
		        dots: false
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		        dots: false
		      }
		    }
		]
	});

	var vh = $(window).innerWidth()/21*9;
	var wh = $(window).innerWidth();
	$('.hero-slider .slider-block').css({'height' : vh,'width' : wh});


	$('.carousels').each(function(i, obj) {

	    $(this).slick({
	    	appendArrows: $(this).prev(),
			prevArrow: '<button class="streamium-carousel-prev glyphicon glyphicon-menu-left" aria-hidden="true"></button>',
			nextArrow: '<button class="streamium-carousel-next glyphicon glyphicon-menu-right" aria-hidden="true"></button>',
			slidesToShow: 7,
			slidesToScroll: 7,
			mobileFirst: true,
			responsive: [
			    {
			      breakpoint: 1024,
			      settings: {
			        slidesToShow: 7,
			        slidesToScroll: 7
			      }
			    },
			    {
			      breakpoint: 600,
			      settings: {
			        slidesToShow: 5,
			        slidesToScroll: 5
			      }
			    },
			    {
			      breakpoint: 480,
			      settings: {
			        slidesToShow: 3,
			        slidesToScroll: 3
			      }
			    }
			]
		});
        
        var breakit = 7;
		var iwid = $(this).innerWidth();
		if(iwid < 991){
			breakit = 5;
		}else if(iwid < 481){
			breakit = 5;
		}

		$('.carousels .slick-slide').css({'height' : Math.round((iwid/breakit)/16*9)});
		$('.carousels .slick-slider').css({'height' : Math.round((iwid/breakit)/16*9)});
 
	});

	$('.slick-slide').mouseenter(function() {

        $(this).parent().parent().parent().addClass("liftIt");
	    $(this).find('.block-overlay').show();
		$(this).find('.block-overlay').animate({ opacity: 1 }, 500);

	}).mouseleave(function() {

		$(this).parent().parent().parent().removeClass("liftIt");
		$(this).find('.block-overlay').hide();
    	$(this).find('.block-overlay').animate({ opacity: 0 }, 250);

	}); 

});
/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	$(window).scroll(function (event) {
	    var scroll = $(window).scrollTop();
	    if(scroll > 50){
	    	$(".streamium-install-instructions").hide();
	    }else{
	    	$(".streamium-install-instructions").show();
	    }
	    if(scroll > 100){
	    	$(".cd-main-header").css("background","rgba(0,0,0,0.6)");
	    }else{
	    	$(".cd-main-header").css("background","rgba(0,0,0,0)");
	    }
	});

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
			slidesToShow: 5,
			slidesToScroll: 5,
			//mobileFirst: true,
			responsive: [
			    {
			      breakpoint: 1024,
			      settings: {
			        slidesToShow: 5,
			        slidesToScroll: 5
			      }
			    },
			    {
			      breakpoint: 600,
			      settings: {
			        slidesToShow: 3,
			        slidesToScroll: 3
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
        
        var breakit = 7;
		var iwid = $(this).innerWidth();

		if(iwid < 991){
			breakit = 5;
		}else if(iwid < 481){
			breakit = 3;
		}

		//$('.carousels .slick-slide').css({'height' : Math.round((iwid/breakit)/16*9)});
		//$('.carousels .slick-slider').css({'height' : Math.round((iwid/breakit)/16*9)});
 
	});

	setTimeout(function(){
		$(".streamium-loading").fadeOut();
	},2000);

});
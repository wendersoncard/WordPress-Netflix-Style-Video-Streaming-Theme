/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	$(window).scroll(function (event) {
	    var scroll = $(window).scrollTop();
	    if(scroll > 100){
	    	$(".cd-main-header").css("background","rgba(0,0,0,0.8)");
	    }else{
	    	$(".cd-main-header").css("background","rgba(0,0,0,0)");
	    }
	});

	// Initialise Slider
	$('.hero-slider').slick({
		appendArrows: $(this).prev(),
		prevArrow: $('.streamium-prev'),
		nextArrow: $('.streamium-next'),
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: true,
      	autoplay: true,
      	adaptiveHeight: true,
      	autoplaySpeed: 8000,
      	//mobileFirst: true,
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

	var vh = $(window).innerWidth()/21*9;
	var wh = $(window).innerWidth();
	$('.hero-slider .slider-block').css({'height' : vh,'width' : wh});
	
	$('.carousels').each(function(i, obj) {

		var numberItems = 6;
		if(wh < 1024){
			numberItems = 5;
		}else if(wh < 600){
			numberItems = 3;
		}else if(wh < 480){
			numberItems = 2;
		}
		var itemWidth = Math.floor($(this).width()/numberItems);
		$('.tile_media').height((Math.floor(itemWidth/16*9)-2));
	    $(this).slick({
	    	appendArrows: $(this).prev(),
			prevArrow: '<button class="streamium-carousel-prev fa fa-angle-left" aria-hidden="true"></button>',
			nextArrow: '<button class="streamium-carousel-next fa fa-angle-right" aria-hidden="true"></button>',
			slidesToShow: 6,
			slidesToScroll: 6,
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
 
	});

	if(wh > 481){

		var tileWidth = Math.floor($(window).innerWidth()/6);
		var growFactor = 1.4; 
		var moveLeft = -(tileWidth * (growFactor - 1) / 2);
	    var moveRight = (tileWidth-15) * (growFactor - 1);
	    var currentCat;

	    $('.s3bubble-details-inner-close').on('click',function() {

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

	    $('.tile_meta_more_info').on('click',function(event) {
	    	
	    	event.preventDefault();
	    	
	    	var title = $(this).data('title');
	    	var desc = $(this).data('description');
	    	var bgimage = $(this).data('bgimage');
	    	var cat = $(this).data('cat');
	    	var href = $(this).data('link');

	    	currentCat = "." + cat;
	    	$('.tile').css('border','none');
	    	$(this).parent().parent().parent().css('border','1px solid #fff');

	    	var twidth = $(currentCat).width();
	    	var theight = twidth/21*8;
	    	$(currentCat).find('h2').text(title);
	    	$(currentCat).find('span').html(decodeURI(desc));
	    	$(currentCat).find('a').attr( "href", href);
	    	$(currentCat).css("background-image", "url(" + bgimage + ")");

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

	    });

	    $('.tile').hover(function() {

	    	$(this).parent().parent().find('.tile').css('opacity', '0.3');
	    	$(this).css('opacity', '1');
		    $(this).find('.tile_details').css('opacity', '1');
		    $(this).find('.tile_play').delay( 800 ).css('opacity', '1');

		}, function() {

			//$(currentCat).height(0);
			$('.tile').css('opacity', '1');
		    $(this).find('.tile_details').css('opacity', '0');
		    $(this).find('.tile_play').delay( 800 ).css('opacity', '0');

		});
		
		$('head').append('<style type="text/css">' +
			'.carousels:hover {transform: translate3d(0px, 0, 0);}' +
			'.carousels:hover .tile:hover {transform: scale(' + growFactor + ');opacity: 1;}' +
			'.carousels .tile:hover ~ .tile {transform: translate3d(' + moveRight +'px, 0, 0);}' +
		'</style>');

	}else{

		$('.tile_details').css('opacity', '1');

	}	

	setTimeout(function(){
		$(".streamium-loading").fadeOut();
	},2000);

});
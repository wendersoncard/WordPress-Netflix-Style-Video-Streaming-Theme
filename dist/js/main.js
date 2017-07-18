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

	// Remove some elements on load
	$(".subscriptio_list_product a").contents().unwrap();
	$(".product-name a").contents().unwrap();
	$(".subscriptio_frontend_items_list_item a").contents().unwrap();
	$(".subscriptio_list_id a").contents().unwrap();
	$(".product-thumbnail").remove();

	var tileCount = streamium_object.tile_count;
	var tileWidth = Math.round($('.container-fluid').width()/tileCount);
	var growFactor = 2;
	var moveDistance = ((tileWidth / 2)-2);
    var currentCat;
	var view_height = Math.round(($(window).innerWidth()/21*9));

	$('.streamium-slider .slick-slide').height(view_height);

	function resizeVideoJS(){
        view_height = Math.round(($(window).innerWidth()/21*9));
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

    $('head').append('<style type="text/css">' +

		'.shiftLeft { transform: translate3d(-' + moveDistance +'px, 0, 0);}' +
		'.shiftRight { transform: translate3d(' + moveDistance +'px, 0, 0);}' +
		'.shiftLeftFirst { transform: translate3d(' + (moveDistance*2) +'px, 0, 0);}' +
		'.shiftRightFirst { transform: translate3d(-' + (moveDistance*2) +'px, 0, 0);}' +

	'</style>');

	function animateResults() {
		var count = $('.carousels').length;
		$('.carousels').each(function(index) {
			$(this).delay(200*index).fadeTo(10, 1);
		}).promise().done( function(){ 
			$(".streamium-loading").fadeOut();
		});
	}

	animateResults();

	$('[data-toggle="tooltip"]').tooltip();

});

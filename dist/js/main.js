/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	$(window).scroll(function (event) {
	    var scroll = $(window).scrollTop();
	    if(scroll > 100){
	    	$(".home .cd-main-header").css("background","rgba(20,20,20,.7)");
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
      	autoplay: false,
      	adaptiveHeight: true,
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

	$('[data-toggle="tooltip"]').tooltip();

	var clickClass = "home-arrow";
    if(isMobile.any()){
        clickClass = "tile"; 
    }

    $('.' + clickClass).live( "click", function(event) {

        event.preventDefault();

        var cat = $(this).data('cat');
        var post_id = $(this).data('id');
        var nonce = $(this).data('nonce');

        // Fadeout episodes
        $('.series-watched').fadeOut();
        $('.tile-white-selected').hide();
        $('.tile-white-selected').removeClass('tile-white-is-selected');       

        getMovieData({
            action: 'streamium_get_dynamic_content',
            cat : cat,
            post_id: post_id,
            nonce: nonce
        },function(){

        });

        return false;

    });

    $('.s3bubble-details-inner-close').live( "click", function(event) {

        event.preventDefault();

        var div = $(this).parent().parent().parent();

        $(".series-watched").fadeOut();
        $(".tile-white-selected").hide();

        div.animate({
            opacity: 0,
        }, 250, function() {
            div.parent().animate({
                height: 0
            }, 250, function() {

            });
        });

    });

    if(!isMobile.any()){

        $('.tile_inner-home').live('mouseenter', function() { 

            // Setup the hover
            if (($(this).find('.tile-white-is-selected').length === 1)) {
                $(this).find('.content').hide();
                return;
            }else{
                $(this).find('.content').show();
            }

            if (!$(this).parent().hasClass('filler')) {

                $(this).addClass('remove-background');
                $(this).find('.streamium-extra-meta').hide();

                if ($(this).parent().hasClass("far-left")) {
                    $(this).parent().nextAll().addClass("shiftLeftFirst");
                } else if ($(this).parent().hasClass("far-right")) {
                    $(this).parent().prevAll().addClass("shiftRightFirst");
                } else {
                    $(this).parent().nextAll().addClass("shiftRight");
                    $(this).parent().prevAll().addClass("shiftLeft");
                }

                $(this).css('transform', 'scale(2)');

            }
        }).live('mouseleave', function () {

            $(this).removeClass('remove-background');
            $(this).find('.streamium-extra-meta').fadeIn();

            if ($(this).parent().hasClass("far-left")) {
                $(this).parent().nextAll().removeClass("shiftLeftFirst");
            } else if ($(this).parent().hasClass("far-right")) {
                $(this).parent().prevAll().removeClass("shiftRightFirst");
            } else {
                $(this).parent().nextAll().removeClass("shiftRight");
                $(this).parent().prevAll().removeClass("shiftLeft");
            }

            $(this).css('transform', 'scale(1)');

        });

    }

});
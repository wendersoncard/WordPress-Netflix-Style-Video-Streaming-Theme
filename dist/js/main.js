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

	var tileCount = 6;
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

	if(!isMobile.any()){

        $('.tile_inner-php').hover(function() {
 
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
        }, function() {

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

    var clickClass = "php-arrow";
    if(isMobile.any()){
        clickClass = "tile";
    }

    $('.' + clickClass).on("click",function(event) {

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

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

    $.ajax({
        type: "post",
        dataType: "json",
        url: streamium_object.ajax_url,
        data: {
            action: "recently_watched_api_post",
            nonce: streamium_object.recently_watched_api_nonce
        },
        success: function(response) {
            if (response.error) {

                console.log('Got this from the server: ' + response);

            } else { 

                var tiles = response.data;
                var count = response.count;

                if (tiles.length > 0) {
                    
                    var tile = '';
                    for (i = 0; i < tiles.length; i++) { 

                        var classPush = "";
                        if(i === 0){
                            classPush = "far-left";
                        }else if(i === 5){
                            classPush = "far-right";
                        }
                        tile += '<div class="tile ' + classPush + '" data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="recent">' +
                            '<div class="tile_inner tile_inner-recently" style="background-image: url(' + tiles[i].tileUrl + ');">' +
                            '<div class="content">' +
                            '<div class="overlay" style="background-image: url(' + tiles[i].tileUrlExpanded + ');">' +
                            '<div class="overlay-gradient"></div>' +
                            '<a class="play-icon-wrap hidden-xs" href="' + tiles[i].link + '">' +
                            '<div class="play-icon-wrap-rel">' +
                            '<div class="play-icon-wrap-rel-ring"></div>' +
                            '<span class="play-icon-wrap-rel-play">' +
                            '<i class="fa fa-play fa-1x" aria-hidden="true"></i>' +
                            '</span>' +
                            '</div>' +
                            '</a>' +
                            '<div class="overlay-meta hidden-xs">' +
                            '<h4>' + tiles[i].title + '</h4>' +
                            '<p>' + tiles[i].text + '</p>' +
                            '<a data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="recent" class="tile_meta_more_info recently-arrow hidden-xs"><i class="icon-streamium" aria-hidden="true"></i></a>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="progress tile_progress"><div class="progress-bar" role="progressbar" aria-valuenow="' + tiles[i].progressBar + '" aria-valuemin="0" aria-valuemax="100" style="width:' + tiles[i].progressBar + '%"></div></div>' +
                            '</div>';
                    }

                    if(count < streamium_object.tile_count){
                        for (c = 0; c < ((streamium_object.tile_count)-count); c++) { 
                            tile += '<div class="tile filler"><div class="tile_inner"></div></div>';
                        }
                    }
  
                }

                var expand = '<section class="s3bubble-details-full recent">' + 
                    '<div class="s3bubble-details-full-overlay"></div>' + 
                    '<div class="container-fluid s3bubble-details-inner-content">' + 
                        '<div class="row">' + 
                            '<div class="col-sm-5 col-xs-5 rel">' + 
                                '<div class="synopis-outer">' + 
                                    '<div class="synopis-middle">' + 
                                        '<div class="synopis-inner">' + 
                                            '<h2 class="synopis hidden-xs"></h2>' + 
                                            '<div class="synopis content"></div>' + 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                            '</div>' + 
                            '<div class="col-sm-7 col-xs-7 rel">' + 
                                '<a class="play-icon-wrap synopis" href="#">' + 
                                    '<div class="play-icon-wrap-rel">' + 
                                        '<div class="play-icon-wrap-rel-ring"></div>' + 
                                        '<span class="play-icon-wrap-rel-play">' + 
                                            '<i class="fa fa-play fa-3x" aria-hidden="true"></i>' + 
                                        '</span>' + 
                                    '</div>' + 
                                '</a>' + 
                                '<a href="#" class="synopis-video-trailer streamium-btns hidden-xs">Watch Trailer</a>' + 
                                '<a href="#" class="s3bubble-details-inner-close"><i class="fa fa-times" aria-hidden="true"></i></a>' + 
                            '</div>' + 
                        '</div>' + 
                    '</div>' + 
                '</section>'; 

                $("#recently-watched").append('<section class="videos"><div class="container-fluid"><div class="row"><div class="col-sm-12"><div class="video-header"><h3>Recently Watched</h3></div></div></div><div class="row"><div class="col-sm-12"><div class="carousels" id="recently">' + tile + '</div></div></div></div></section>' + expand);
                
                var sliderCaro = $("#recently");
                sliderCaro.slick({
                    slidesToShow: streamium_object.tile_count,
                    slidesToScroll: streamium_object.tile_count,
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
                sliderCaro.find('.slick-prev').addClass('hidden');

                sliderCaro.on('setPosition', function (event, slick, currentSlide) {

                    $(this).find(".slick-active:first").addClass( "far-left" );
                    if(slick.slideCount > 6){ // Get the slide count
                        $(this).find(".slick-active:last").addClass( "far-right" );
                    }

                });  

                sliderCaro.on('afterChange', function (event, slick, currentSlide) {

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

                if(!isMobile.any()){

                    $('.tile_inner-recently').hover(function() {

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

                var clickClass = "recently-arrow";
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

            }
        }
    });

});
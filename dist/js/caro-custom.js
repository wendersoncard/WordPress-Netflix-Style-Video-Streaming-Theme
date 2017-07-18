jQuery(document).ready(function($) {

    var buildIt = function(response){

        var cats = response.data;
        if (cats.length > 0) { 

            for (a = 0; a < cats.length; a++) { 
            
                var catName = cats[a].meta.name;
                var catSlug = cats[a].meta.catSlug;
                var type = cats[a].meta.type;
                var link = cats[a].meta.link;
                var home = cats[a].meta.home;
                var taxUrl = cats[a].meta.taxUrl;
                var taxTitle = cats[a].meta.title;
                var count = cats[a].meta.count;

                var tiles = cats[a].data;
                var tile = '';
                for (i = 0; i < tiles.length; i++) { 

                    // buildTiles located in global.js
                    tile += buildTilesTemplate(tiles,i,type);

                }

                if(count < streamium_object.tile_count){
                    for (c = 0; c < ((streamium_object.tile_count)-count); c++) { 
                        tile += '<div class="tile filler"><div class="tile_inner"></div></div>';
                    }
                }

                $("#custom-watched").append('<section class="videos"><div class="container-fluid"><div class="row"><div class="col-sm-12"><div class="video-header"><h3>' + taxTitle + '</h3><a class="see-all" href="' + link + '">View all</a></div></div></div><div class="row"><div class="col-sm-12"><div class="carousels" id="custom-slick-' + a + '">' + tile + '</div></div></div></div></section>' + buildExpandedTemplate(type));
                
                var sliderCaro = $("#custom-slick-" + a);
                sliderCaro.slick(streamiumGlobals.slick);

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

            }

        }

        if(!isMobile.any()){ 

            $('.tile_inner-custom').hover(function() {

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

        var clickClass = "custom-arrow";
        if(isMobile.any()){
            clickClass = "tile";
        }

        $('.' + clickClass).on("click",function(event) {

            event.preventDefault();

            var cat = $(this).data('cat');
            var post_id = $(this).data('id');
            var nonce = $(this).data('nonce');

            // Fadeout episodes
            $('.series-watched').fadeOut();
            $('.tile-white-selected').hide();
            $('.tile-white-selected').removeClass('tile-white-is-selected');       
 
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

                    // Run some edits for mobile
                    var content = response.content;
                    if(isMobile.any()){
                        content = limitWords(content, 15);
                    } 

                    // Set the current can to populate
                    var currentCat = "." + response.cat;
                    var currentCatId = "#series-watched-caro-" + response.cat;
                    var currentCatWrapId = "#series-watched-" + response.cat;
                    var seriesTitle = response.title;
                    

                    // Populate the expanded view
                    var twidth = $(currentCat).width();
                    var theight = Math.floor(twidth/21*8);
                    $(currentCat).find('h2.synopis').text(response.title);
                    $(currentCat).find('div.synopis').html(content);
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

                        // Set the selected block
                        $('#tile-white-selected-' + response.cat + '-' + post_id).show();
                        $('#tile-white-selected-' + response.cat + '-' + post_id).addClass("tile-white-is-selected"); 

                        // Initailise the tooltips
                        $('[data-toggle="tooltip"]').tooltip();

                    });

                    var seriesContainer = $(currentCat).next().find('div.series-watched-caro');

                    // Check for series
                    getData({
                        action: "streamium_get_dynamic_series_content",
                        postId: post_id,
                        nonce: streamium_object.home_api_nonce
                    },function(response){

                        if (response.error) { 
                            console.log("Error: ",response.message);
                            return;
                        }

                        var series = response.data;
                        var serie = '';

                        if(Object.keys(series).length > 0) {

                            for (var a = 0; a < Object.keys(series).length; a++) {

                                var episodes = series[(a+1)];

                                if(episodes.length > 0) {
                                    
                                    for (var i = 0; i < episodes.length; i++) { 
                                        
                                        serie += '<div class="tile"><div class="tile_inner" style="background-image: url(' + episodes[i].thumbnails + ');">' +
                                            '<div class="overlay-gradient"></div>' +
                                            '<a class="play-icon-wrap hidden-xs" href="' + episodes[i].link + '">' +
                                            '<div class="play-icon-wrap-rel">' +
                                            '<span class="play-icon-wrap-rel-play">' +
                                            '<i class="fa fa-play fa-1x" aria-hidden="true"></i>' +
                                            '</span>' +
                                            '</div>' +
                                            '</a>' +
                                            '<h4><b>S' + episodes[i].seasons + ':E' + episodes[i].positions + '</b> ' + episodes[i].titles + '</h4>' +
                                        '</div></div>';
                                    }

                                }

                            }

                        }

                        $(currentCatWrapId).fadeIn();

                        if ($(currentCatId).hasClass('slick-initialized') === false) {

                            //$(currentCatWrapId).prepend('<h4>' + seriesTitle + ' Episodes</h4>');
                            seriesContainer.html(serie);

                            $(currentCatId).slick(streamiumGlobals.slickSeries);

                        }

                    }); // end series

                }

            }); // end jquery

        });

        $('.s3bubble-details-inner-close').on('click',function(event) {

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

    };

    if(!streamium_object.is_tax){
        
        getData({
            action: "custom_api_post",
            nonce: streamium_object.custom_api_nonce
        },function(response){

            if (response.error) { 
                console.log("Error: ",response.message);
                return;
            }

            buildIt(response);

        });

    }

});
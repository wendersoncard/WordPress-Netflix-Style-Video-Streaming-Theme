jQuery(document).ready(function($) {

    var buildIt = function(response){

        var cats = response.data;
        if (cats.length > 0) {

            for (a = 0; a < cats.length; a++) { 
                
                var catParent = cats[a].meta.title;
                var catName = cats[a].meta.name;
                var type = cats[a].meta.catSlug;
                var link = cats[a].meta.link;
                var home = cats[a].meta.home;
                var count = cats[a].meta.count;

                var tiles = cats[a].data;
                if(tiles.length > 0) {

                    var tile = '';
                    for (var i = 0; i < tiles.length; i++) {

                        // buildTiles located in global.js
                        tile += buildTilesTemplate(tiles,i,type);

                    }

                    if(count < streamium_object.tile_count){
                        for (c = 0; c < ((streamium_object.tile_count)-count); c++) { 
                            tile += '<div class="tile filler"><div class="tile_inner"></div></div>';
                        }
                    }

                    $("#home-watched").append('<section class="videos"><div class="container-fluid"><div class="row"><div class="col-sm-12"><div class="video-header"><h3>' + catParent + ' <i class="fa fa-chevron-right" aria-hidden="true"></i> ' + catName + '</h3><a class="see-all" href="' + link + '">View all</a></div></div></div><div class="carousels" id="home-slick-' + a + '">' + tile + '</div></div></section>' + buildExpandedTemplate(type));
                    
                    var sliderCaro = $("#home-slick-" + a);
                    sliderCaro.slick(streamiumGlobals.slick);

                    // hide all slides initally
                    sliderCaro.find('.slick-prev').addClass('hidden');

                    sliderCaro.on('setPosition', function (event, slick, currentSlide) {

                        $(this).find(".slick-active:first").addClass( "far-left" );
                        if(slick.slideCount > streamium_object.tile_count){ // Get the slide count
                            $(this).find(".slick-active:last").addClass( "far-right" );
                        }

                    });  

                    sliderCaro.on('afterChange', function (event, slick, currentSlide) {

                        $(this).find(".tile").removeClass( "far-left" ).removeClass( "far-right" );
                        $(this).find(".slick-active:first").addClass( "far-left" );
                        $(this).find(".slick-active:last").addClass( "far-right" );
                        if(currentSlide === 0) {
                            $(this).find('.slick-prev').addClass('hidden');
                        }else {
                            $(this).find('.slick-prev').removeClass('hidden');
                        }
                        if(slick.currentSlide >= slick.slideCount - slick.options.slidesToShow){
                            $(this).find('.slick-next').addClass('hidden');
                        }else {
                            $(this).find('.slick-next').removeClass('hidden');
                        }

                    });

                }  

            }

        }

    };

    // Only run this on homepage
    if(streamium_object.is_home || streamium_object.is_archive){
        
        getData({
            action: "home_api_post",
            nonce: streamium_object.home_api_nonce
        },function(response){

            if (response.error) { 
                console.log("Error: ",response.message);
                return;
            }

            buildIt(response);

        });

    }

});
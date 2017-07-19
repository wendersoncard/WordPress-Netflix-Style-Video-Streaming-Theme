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

                $("#custom-watched").append('<section class="videos"><div class="container-fluid"><div class="row"><div class="col-sm-12"><div class="video-header"><h3>' + taxTitle + '</h3><a class="see-all" href="' + link + '">View all</a></div></div></div><div class="carousels" id="custom-slick-' + a + '">' + tile + '</div></div></section>' + buildExpandedTemplate(type));
                
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
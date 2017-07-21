jQuery(document).ready(function($) {

    var buildIt = function(response){

        var tiles = response.data;
        var count = response.count;
        var type = "recent";

        if (tiles.length > 0) {
            
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

        }

        $("#recently-watched").append('<section class="videos"><div class="container-fluid"><div class="row"><div class="col-sm-12"><div class="video-header"><h3>Recently Watched</h3></div></div></div><div class="carousels" id="recently">' + tile + '</div></div></section>' + buildExpandedTemplate(type));
         
        var sliderCaro = $("#recently");
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

    };

    // Only run this on homepage
    if(streamium_object.is_home){

        console.log("recently_watched_api_post");
        
        getData({
            action: "recently_watched_api_post",
            nonce: streamium_object.recently_watched_api_nonce
        },function(response){

            if (response.error) { 
                console.log("Error: ",response.message);
                return;
            }

            buildIt(response);

        });

    }

});
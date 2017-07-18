jQuery(document).ready(function($) {

    var buildIt = function(response){

        var tiles = response.data;
        var count = response.count;

        if(isMobile.any()){
            streamium_object.tile_count = 2;
        }

        if (tiles.length > 0) {
            
            var tile = '';
            var cat_count = 0;
            for (i = 0; i < tiles.length; i++) {

                var changeInd = (i+1);

                if(i % streamium_object.tile_count === 0){
                    tile += '<div class="container-fluid"><div class="row static-row ' + ((i === 0) ? 'static-row-first' : '') + '">';
                    cat_count++;
                }

                // Paid
                var html = "";
                if(tiles[i].paid){
                    html = tiles[i].paid.html;
                } 

                var classPush = "";
                if(changeInd === 1 || i % (streamium_object.tile_count) === 0){
                    classPush = "far-left";
                }else if(changeInd % (streamium_object.tile_count) === 0){
                    classPush = "far-right";
                }

                tile += '<div class="col-md-2 col-xs-6 tile ' + classPush + '" data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="tax-' + cat_count + '">' +
                    '<div class="tile_inner tile_inner-recently" style="background-image: url(' + tiles[i].tileUrl + ');">' +
                    html +
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
                    '<a data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="tax-' + cat_count + '" class="tile_meta_more_info recently-arrow hidden-xs"><i class="icon-streamium" aria-hidden="true"></i></a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="progress tile_progress"><div class="progress-bar" role="progressbar" aria-valuenow="' + tiles[i].progressBar + '" aria-valuemin="0" aria-valuemax="100" style="width:' + tiles[i].progressBar + '%"></div></div>' +
                    '</div>';

                var check = false;
                if(isMobile.any()){
                    if(isOdd(i)){
                        check = true;
                    }
                }else{
                    if(changeInd % (streamium_object.tile_count) === 0){
                        check = true;
                    }
                }

                if(check || i === (count-1)){
                    tile += '</div></div><section class="s3bubble-details-full tax-' + cat_count + '">' + 
                        '<div class="s3bubble-details-full-overlay"></div>' + 
                        '<div class="container-fluid s3bubble-details-inner-content">' + 
                            '<div class="row">' + 
                                '<div class="col-sm-5 col-xs-5 rel">' + 
                                    '<div class="synopis-outer">' + 
                                        '<div class="synopis-middle">' + 
                                            '<div class="synopis-inner">' + 
                                                '<h2 class="synopis"></h2>' + 
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
                }

            }

            /*if(count < streamium_object.tile_count){
                for (c = 0; c < ((streamium_object.tile_count)-count); c++) { 
                    tile += '<div class="tile filler"><div class="tile_inner"></div></div>';
                }
            }*/

        }

        $("#tax-watched").html(tile);

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

    };

    if(streamium_object.is_tax){

        getData({
            action: "tax_api_post",
            search: "all",
            nonce: streamium_object.tax_api_nonce
        },function(response){

            if (response.error) { 
                console.log("Error: ",response.message);
                return;
            }

            buildIt(response);

        });

        $(".tax-search-filter").on("click",function(){

            event.preventDefault();

            // Get the search type
            var query = $(this).data("type");

            getData({
                action: "tax_api_post",
                search: query,
                nonce: streamium_object.tax_api_nonce
            },function(response){

                if (response.error) { 
                    console.log("Error: ",response.message);
                    return;
                }

                buildIt(response);

            });

        });

    }

});
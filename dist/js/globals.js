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

/**
 * Globals streamium calls
 * @public
 */
window.streamiumGlobals = {
    tileCount: (isMobile.any()) ? 2 : 6,
    slick: {
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
    },
    slickSeries: {
        slidesToShow: (streamium_object.tile_count-1),
        slidesToScroll: (streamium_object.tile_count-1),
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
    }
};

var isOdd = function(num) { 
    return num % 2;
}

function limitWords(textToLimit, wordLimit) {
    var finalText = "";

    var text2 = textToLimit.replace(/\s+/g, ' ');

    var text3 = text2.split(' ');

    var numberOfWords = text3.length;

    var i = 0;

    if (numberOfWords > wordLimit) {
        for (i = 0; i < wordLimit; i++)
            finalText = finalText + " " + text3[i] + " ";

        return finalText + "...";
    } else return textToLimit;
}

function buildTilesTemplate(tiles,i,type){

    // Paid
    var html = "";
    if(tiles[i].paid){
        html = tiles[i].paid.html;
    }

    // Set left and right class fixes
    var classPush = "";
    if(i === 0){
        classPush = "far-left";
    }else if(i === (streamium_object.tile_count-1)){
        classPush = "far-right";
    } 

    return '<div class="tile ' + classPush + '" data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="' + type + '">' +
                    '<div class="tile_inner tile_inner-home" style="background-image: url(' + tiles[i].tileUrl + ');">' +
                    html +
                    '<div id="tile-white-selected-' + type + '-' + tiles[i].id + '" class="tile-white-selected"></div>' +
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
                        '<span class="top-meta-watched">' + ((tiles[i].progressBar > 0) ? tiles[i].progressBar + "% watched" : "") + '</span>' +
                        '<h4>' + tiles[i].title + '</h4>' +
                        '<p>' + tiles[i].text + '</p>' +
                        '<span class="top-meta-reviews">' + tiles[i].reviews + '</span>' +
                        '<a data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="' + type + '" class="tile_meta_more_info home-arrow hidden-xs"><i class="icon-streamium" aria-hidden="true"></i></a>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            //'<div class="progress tile_progress"><div class="progress-bar" role="progressbar" aria-valuenow="' + tiles[i].progressBar + '" aria-valuemin="0" aria-valuemax="100" style="width:' + tiles[i].progressBar + '%"></div></div>' +
        '</div>' +
    '</div>';

}

function buildStaticTilesTemplate(tiles,i,type,changeInd){

    // Paid
    var html = "";
    if(tiles[i].paid){
        html = tiles[i].paid.html;
    }

    // Set left and right class fixes
    var classPush = "";
    if(changeInd === 1 || i % (streamium_object.tile_count) === 0){
        classPush = "far-left";
    }else if(changeInd % (streamium_object.tile_count) === 0){
        classPush = "far-right";
    }

    return '<div class="col-md-2 col-xs-6 tile ' + classPush + '" data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="' + type + '">' +
                    '<div class="tile_inner tile_inner-home" style="background-image: url(' + tiles[i].tileUrl + ');">' +
                    html +
                    '<div id="tile-white-selected-' + type + '-' + tiles[i].id + '" class="tile-white-selected"></div>' +
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
                        '<span class="top-meta-watched">' + ((tiles[i].progressBar > 0) ? tiles[i].progressBar + "% watched" : "") + '</span>' +
                        '<h4>' + tiles[i].title + '</h4>' +
                        '<p>' + tiles[i].text + '</p>' +
                        '<span class="top-meta-reviews">' + tiles[i].reviews + '</span>' +
                        '<a data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="' + type + '" class="tile_meta_more_info home-arrow hidden-xs"><i class="icon-streamium" aria-hidden="true"></i></a>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            //'<div class="progress tile_progress"><div class="progress-bar" role="progressbar" aria-valuenow="' + tiles[i].progressBar + '" aria-valuemin="0" aria-valuemax="100" style="width:' + tiles[i].progressBar + '%"></div></div>' +
        '</div>' +
    '</div>';

}

function buildExpandedTemplate(type){

    return '<section class="s3bubble-details-full ' + type + '">' + 
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
        '</section>' + 
        '<section id="series-watched-' + type + '" class="series-watched"><div id="series-watched-caro-' + type + '" class="series-watched-caro"></div></div></section>';

}

function getData(data,callback){

    // add the query object
    data.query = streamium_object.query;

    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: streamium_object.ajax_url,
        data: data,
        success: function(response) {

            callback(response);

        }
    });

}


function getMovieData(data,callback){

    jQuery.ajax({
        url: streamium_object.ajax_url,
        type: 'post',
        dataType: 'json',
        data: data,
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
            var tileSelected = '#tile-white-selected-' + response.cat + '-' + data.post_id;
            var seriesTitle = response.title;
            
 
            // Populate the expanded view
            var twidth = jQuery(currentCat).width();
            var theight = Math.floor(twidth/21*8);
            jQuery(currentCat).find('h2.synopis').text(response.title);
            jQuery(currentCat).find('div.synopis').html(content);
            jQuery(currentCat).find('a.synopis').attr( "href", response.href);
            jQuery(currentCat).css("background-image", "url(" + response.bgimage + ")");

            if(response.trailer === ""){
                jQuery(currentCat).find('a.synopis-video-trailer').hide();
            }else{
                jQuery(currentCat).find('a.synopis-video-trailer').fadeIn().attr( "href", response.href + "?trailer=true");
            }

            // Animate to the white overlay
            var vmiddle = Math.round(jQuery('.cd-main-header').height());
            var vtile = Math.round(jQuery(tileSelected).outerHeight())+(4);
            var voff = Math.round(jQuery(currentCat).offset().top);
            jQuery('html, body').animate({scrollTop: (voff-(vtile+vmiddle))}, 500);
            
            // Animate out the details
            jQuery(currentCat).animate({
                height: theight 
            }, 250, function() {

                jQuery(currentCat + ' .s3bubble-details-inner-content').animate({
                    opacity: 1,
                }, 500, function() {
                    
                });

                // Set the selected block
                jQuery(tileSelected).show();
                jQuery(tileSelected).addClass("tile-white-is-selected"); 

                // Initailise the tooltips
                jQuery('[data-toggle="tooltip"]').tooltip();

            });

            var seriesContainer = jQuery(currentCat).next().find('div.series-watched-caro');

            // Check for series
            getData({
                action: "streamium_get_dynamic_series_content",
                postId: data.post_id,
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

                jQuery(currentCatWrapId).fadeIn();

                if (jQuery(currentCatId).hasClass('slick-initialized') === false) {

                    //jQuery(currentCatWrapId).prepend('<h4>' + seriesTitle + ' Episodes</h4>');
                    seriesContainer.html(serie);

                    jQuery(currentCatId).slick(streamiumGlobals.slickSeries);

                }

            }); // end series

        }

    }); // end jquery

}
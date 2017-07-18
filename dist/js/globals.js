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
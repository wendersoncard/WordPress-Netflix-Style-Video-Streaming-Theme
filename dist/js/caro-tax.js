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

                // Change index
                var changeInd = (i+1);
                var type = 'tax-' + cat_count;
                if(i % streamium_object.tile_count === 0){
                    tile += '<div class="container-fluid"><div class="row static-row ' + ((i === 0) ? 'static-row-first' : '') + '">';
                }

                tile += buildStaticTilesTemplate(tiles,i,type,changeInd);

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
                    tile += '</div></div>' + buildExpandedTemplate(type);
                    cat_count++;
                }

            }

        }

        $("#tax-watched").html(tile);

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

        $(".tax-search-filter").on("click",function(event){

            event.preventDefault();

            // Get the search type
            var query = $(this).data("type");

            streamium_object.search = query;

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
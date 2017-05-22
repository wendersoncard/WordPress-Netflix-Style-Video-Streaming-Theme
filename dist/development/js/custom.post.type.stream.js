jQuery( document ).ready(function( $ ) {
  
    (function populateSelectBox() {

        // get user streams
        $.post( streamium_meta_object.api + "/api/streams/", {
            website: streamium_meta_object.connected_website
        }, function(response) {
    
            if(response.error){ 

                $(".streamium-theme-select-group").html("<div class='streamium-current-url-error'>" + response.message + "</div>");
                return;
                
            } 

            function baseName(str){

               var base = str.replace(/\//gi, " ")
               base = base.replace(/-/gi, " ");
               base = base.toLowerCase();
               return base;

            }

            var html = '';
            $.each(response.results, function (i, item) {

                var stream = item.stream;

                if(parseInt(streamium_meta_object.streamiumPremium) === 1){
                    html += '<option id="' + stream + '"  value="' + stream + '">' + stream + '</option>';  
                }else{
                    html += '<option id="https://s3bubble.com/secure/#/video_playlist/' + stream + '"  value="https://media.s3bubble.com/embed/playlist/id/' + stream + '">Video Playlist: ' + title + '</option>'; 
                }
                
            });
            html += '';
            
            // Custom episode code
            $('.streamium-theme-live-stream-select-group').append(html);
            
            var config = {
              '.chosen-select'           : {search_contains:true},
              '.chosen-select-deselect'  : {allow_single_deselect:true},
              '.chosen-select-no-single' : {disable_search_threshold:10},
              '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
              '.chosen-select-width'     : {width:"95%"}
            }
            for (var selector in config) {
              $(selector).chosen(config[selector]);
            }
                            
        },'json');

    }());

});
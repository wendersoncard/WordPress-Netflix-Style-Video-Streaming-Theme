jQuery( document ).ready(function( $ ) {
  
    (function populateSelectBox() {

        // get user streams
        $.post( streamium_meta_object.api + "/api/streams", {
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
                html += '<option id="' + stream + '"  value="' + stream + '">' + stream + '</option>';  
                
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

    $('#streamium-add-roku-data-stream').live('click', function() {

        var code = $(this).data('code');
        $.post( streamium_meta_object.api + "/mrss/stream", {
            code: code
        }, function(response) {

            if(response.error){  

                alert(response.message);
                return;
                
            } 

            // Set the data
            $('#s3bubble_roku_url_meta_box_text').val(response.source.url);
            $('#s3bubble_roku_quality_meta_box_text').val(response.source.quality);
            $('#s3bubble_roku_videotype_meta_box_text').val(response.source.videoType);

            // Tell the user about image needed and duration
            alert("Data successfully generated. !Important you will need to manually enter the video duration and please make sure you have added a Roku thumbnail 16:9 at least 800x450 in the thumbnail section below.");
            
                            
        },'json').fail(function(e){

           alert('Unkown error please contact support!');
        
        });

        return false;
        
    });

});
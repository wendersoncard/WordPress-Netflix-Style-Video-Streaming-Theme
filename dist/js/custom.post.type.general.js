jQuery( document ).ready(function( $ ) {

    $( '#streamium-add-repeater-row' ).live('click', function() {

        $('#repeatable-fieldset-one').append('<li>' + 
            '<div class="streamium-repeater-left">' + 
                '<p>' + 
                    '<label>Video Image</label>' + 
                    '<input type="hidden" class="widefat" name="thumbnails[]" />' + 
                    '<img src="http://placehold.it/260x146" />' + 
                    '<input class="streamium_upl_button button" type="button" value="Upload Image" />' + 
                '</p>' +  
            '</div>' + 
            '<div class="streamium-repeater-right">' +
                '<p>' +
                    '<label>Video Season</label>' +
                    '<select class="widefat" tabindex="1" name="seasons[]">' +
                        '<option value="1">Season 1</option>' +
                        '<option value="2">Season 2</option>' +
                        '<option value="3">Season 3</option>' +
                        '<option value="4">Season 4</option>' +
                        '<option value="5">Season 5</option>' +
                        '<option value="6">Season 6</option>' +
                        '<option value="7">Season 7</option>' +
                        '<option value="8">Season 8</option>' +
                        '<option value="9">Season 9</option>' +
                        '<option value="10">Season 10</option>' +
                        '<option value="11">Season 11</option>' +
                        '<option value="12">Season 12</option>' +
                    '</select>' +
                '</p>' +
                '<p>' +
                    '<label>Video List Position</label>' +
                    '<input type="text" class="widefat" name="positions[]" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="1" />' +
                '</p>' + 
                '<p>' + 
                    '<label>Video Code</label>' + 
                    '<select class="streamium-theme-episode-select chosen-select" tabindex="1" name="codes[]"></select>' + 
                '</p>' + 
                '<p>' + 
                    '<label>Video Title</label>' + 
                    '<input type="text" class="widefat" name="titles[]" />' + 
                '</p>' + 
                '<p>' + 
                    '<label>Video Description</label>' + 
                    '<textarea rows="4" cols="50" class="widefat" name="descriptions[]" value=""></textarea>' + 
                '</p>' + 
                '<a class="button streamium-repeater-remove-row" href="#">Remove</a>' + 
            '</div>' + 
        '</li>');
        return false;

    });

    $( '.streamium-repeater-remove-row' ).live('click', function() {
        
        $(this).parents('li').remove();
        return false;

    });

    $('.streamium_upl_button').live('click', function() {

        var thisInput = $(this).prev().prev();
        var thisImage = $(this).prev();
        var send_attachment_bkp = wp.media.editor.send.attachment;

        wp.media.editor.send.attachment = function(props, attachment) {

            thisInput.attr('value', attachment.url);
            thisImage.attr('src', attachment.url);
            wp.media.editor.send.attachment = send_attachment_bkp;

        }

        wp.media.editor.open();
        return false;

    });
  
    (function populateSelectBox() {

        $.post( streamium_meta_object.api + "/api/codes/", {
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

                var code = item.code;
                var bucket = item.bucket;
                var key = item.key;
                var title = item.title;
                var ext = item.ext;
                var type = item.type;

                if(ext === "m3u8"){
                    html += '<option id="' + code + '"  value="' + code + '">' + baseName(key) + ' ' + code + '</option>';  
                }
                if(ext === "mp4" || ext === "m4v"){
                    html += '<option id="' + code + '"  value="' + code + '">' + baseName(key) + ' ' + code + '</option>';  
                }
                
            });
            html += '';
            $('.streamium-theme-main-video-select-group').append(html);
            $('.streamium-theme-video-trailer-select-group').append(html);
            $('.streamium-theme-featured-video-select-group').append(html);

            // Custom episode code
            $('.streamium-theme-episode-select').append(html);

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

        $('.add-program-row').on('click',function(){
            populateSelectBox();
        });            

    }()); 

    resizeChosen();
    $(window).on('resize', resizeChosen);
    function resizeChosen() {
       $(".chosen-container").each(function() {
           $(this).attr('style', 'width: 100%');
       });          
    }

});
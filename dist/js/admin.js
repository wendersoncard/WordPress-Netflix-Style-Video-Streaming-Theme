jQuery( document ).ready(function( $ ) {

    $.post("http://local.hosted.com/api/codes/", {
        website: streamium_meta_object.s3website
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

            if(parseInt(streamium_meta_object.streamiumPremium) === 1){
                if(ext === "m3u8"){
                    html += '<option id="' + code + '"  value="' + code + '">' + baseName(key) + ' ' + code + '</option>';  
                }
                if(ext === "mp4" || ext === "m4v"){
                    html += '<option id="' + code + '"  value="' + code + '">' + baseName(key) + ' ' + code + '</option>';  
                }
            }else{
                if(ext === "mp4" || ext === "m4v"){
                    html += '<option id="https://s3bubble.com/secure/#/single_video/' + bucket + '/' + key.replace(/\//g, "+") + '" value="https://media.s3bubble.com/embed/progressive/id/' + code + '">' + key + '</option>'; 
                }
                if(ext === "mp3" || ext === "m4a"){
                    html += '<option id="https://s3bubble.com/secure/#/single_audio/' + bucket + '/' + key.replace(/\//g, "+") + '"  value="https://media.s3bubble.com/embed/aprogressive/id/' + code + '">' + key + '</option>';   
                }
                if(ext === "m3u8"){
                    html += '<option id="https://s3bubble.com/secure/#/single_hls/' + bucket + '/' + key.replace(/\//g, "+") + '"  value="https://media.s3bubble.com/embed/hls/id/' + code + '">' + key + '</option>';  
                }
                if(type === "audio"){
                    html += '<option id="https://s3bubble.com/secure/#/audio_playlist/' + code + '"  value="https://media.s3bubble.com/embed/aplaylist/id/' + code + '">Audio Playlist: ' + title + '</option>';    
                }
                if(type === "video"){
                    html += '<option id="https://s3bubble.com/secure/#/video_playlist/' + code + '"  value="https://media.s3bubble.com/embed/playlist/id/' + code + '">Video Playlist: ' + title + '</option>'; 
                }
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

});
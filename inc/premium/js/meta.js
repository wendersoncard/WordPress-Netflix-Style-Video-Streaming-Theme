jQuery( document ).ready(function( $ ) {

	// EPISODES SECTION  =>
	$('body').on('click', '#streamium-add-repeater-row', function() {

        $('#repeatable-fieldset-one').append('<li>' + 
            '<div class="streamium-repeater-left">' + 
                '<p>' + 
                    '<label>Season Image</label>' + 
                    '<input type="hidden" class="widefat" name="thumbnails[]" />' + 
                    '<img src="http://placehold.it/260x146" />' + 
                    '<input class="streamium_upl_button button" type="button" value="Upload Image" />' + 
                '</p>' +  
            '</div>' + 
            '<div class="streamium-repeater-right">' +
                '<p>' +
                    '<label>Season ID</label>' +
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
                        '<option value="13">Season 13</option>' +
                        '<option value="14">Season 14</option>' +
                        '<option value="15">Season 15</option>' +
                        '<option value="16">Season 16</option>' +
                        '<option value="17">Season 17</option>' +
                        '<option value="18">Season 18</option>' +
                        '<option value="19">Season 19</option>' +
                        '<option value="20">Season 20</option>' +
                        '<option value="21">Season 21</option>' +
                        '<option value="22">Season 22</option>' +
                        '<option value="23">Season 23</option>' +
                        '<option value="24">Season 24</option>' +
                        '<option value="25">Season 25</option>' +
                    '</select>' +
                '</p>' +
                '<p>' + 
                    '<label>Season Title</label>' + 
                    '<input type="text" class="widefat" name="titles[]" placeholder="Enter title" />' + 
                '</p>' + 
                '<p>' + 
                    '<label>Season Description</label>' + 
                    '<textarea rows="4" cols="50" class="widefat" name="descriptions[]" placeholder="Enter description"></textarea>' + 
                '</p>' + 
                '<a class="button streamium-repeater-remove-row" href="#">Remove</a>' + 
            '</div>' + 
        '</li>');
        return false;

    });

	$('body').on('click', '.streamium-repeater-remove-row', function() {
        
        // Setup required params
        var that = $(this);
        var ind = that.data('index');
        var pid = that.data('pid');

        var r = confirm("Please confirm you would like to delete this video from your series this is not reversible");
        if (r == true) {

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: streamium_meta_object.ajax_url,
                data: {
                    action: 'streamium_admin_series_remove_video',
                    postId: pid,
                    index: ind
                },
                success: function(data){

                    // Check there is no error and remove
                    if(!data.error){
                        that.parents('li').remove();
                    }
                    
                },
                error: function(err) {

                    console.log(err);
                
                }
            });
        }

        return false;

    });
    // EPISODES SECTION  <=

    // ROKU SECTION  =>
    $('#streamium-add-roku-data').live('click', function() {

        var code = $(this).data('code');
        $.post( streamium_meta_object.api + "/mrss/url", {
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

    // Toggle the series Roku data
    $('#streamium-repeater-add-roku-data').live('click', function() {

        $(this).next().fadeToggle( "slow", "linear" );
        return false;

    });

    $('#streamium-repeater-generate-roku-data').live('click', function() {

        var code = $(this).data('code');
        $.post( streamium_meta_object.api + "/mrss/url", {
            code: code
        }, function(response) {

            if(response.error){  

                alert(response.message);
                return;
                 
            } 

            // Set the data
            $('#series_roku_url_' + key).val(response.source.url);
            $('#series_roku_quality_' + key).val(response.source.quality);
            $('#series_roku_type_' + key).val(response.source.videoType);

            // Tell the user about image needed and duration
            alert("Data successfully generated. !Important you will need to manually enter the video duration and please make sure you have added a Roku thumbnail 16:9 at least 800x450 in the thumbnail section below.");
            
                            
        },'json').fail(function(e){

           alert('Unkown error please contact support!');
        
        });

        return false;

    });
    // ROKU SECTION  <=

    // CODES SECTION  =>
    (function populatePremiumSelectBoxes() {

        $.post( streamium_meta_object.api + "/api/codes", {
            website: streamium_meta_object.connected_website
        }, function(response) {

            if(response.error){ 

                $(".streamium-theme-select-group").html("<div class='streamium-current-url-error'>" + response.message + "</div>");
                return;
                
            }

            var html = '';
            $.each(response.results, function (i, item) {

                var code = item.code;
                var key  = item.key;
                if(item.title){
                    key = item.title;
                }
                html += '<option id="' + code + '"  value="' + code + '">' + code + ' - ' + key + '</option>';
                
            });
            html += '';

            // POPULATE BACKGROUND VIDEO CODES::
            $('.streamium-premium-meta-box-background-video-select-group').append(html);

            // POPULATE VIDEO TRAILER CODES::
            $('.streamium-premium-meta-box-trailer-select-group').append(html);

            $('.chosen-select').chosen({
                search_contains:true
            });
            $('.chosen-select').trigger("chosen:updated");
            
                            
        },'json').fail(function(e){

           alert('Unkown error please contact support!');
        
        });

        $('.add-program-row').on('click',function(){
            populatePremiumSelectBoxes();
        });            

    }()); 

    // WIDTH FIX::
    resizeChosen();
    $(window).on('resize', resizeChosen);
    function resizeChosen() {
       $(".chosen-container").each(function() {
           $(this).attr('style', 'width: 100%');
       });          
    }
    // CODES SECTION  <=

});
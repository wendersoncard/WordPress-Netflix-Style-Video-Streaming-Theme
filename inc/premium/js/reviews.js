jQuery( document ).ready(function( $ ) {

    $('body').on('click', '.streamium-open-reviews-panel', function() {

        event.preventDefault();

        // Clear any previous content
        $('.streamium-review-panel-content').empty();
        $('.streamium-review-panel-header h1').empty();

        var post_id = $(this).attr('data-id');

        $.ajax({
            url: streamium_object.ajax_url,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'streamium_get_reviews',
                post_id: post_id,
                security: streamium_object.nonce
            },
            success: function(response) {

                if (response.error) {

                    swal({
                        title: streamium_object.swal_error,
                        text: response.message,
                        type: "info",
                        showCancelButton: false,
                        confirmButtonText: streamium_object.swal_ok_got_it,
                        closeOnConfirm: true
                    },
                    function() {

                    });
                    return;

                }

                var html = '<ul class="media-list">';
                $.each(response.data, function (i, item) {

                    var avatar = item.avatar;
                    var message = item.message;
                    var date = item.time;
                    var username = item.username;
                    html += '<li class="media">' + 
                          '<a href="#">' + 
                            '<img width="64" class="mr-3" src="' + avatar + '" alt="' + date + '">' + 
                          '</a>' + 
                        '<div class="media-body">' + 
                          '<h5 class="mt-0 mb-1">' + username +'</h5>' + 
                          '<p>' + message + '</p>' +
                        '</div>' + 
                    '</li>';
                    
                });
                html += '</ul>';
                $('.streamium-review-panel-content').html(html);
                $('.streamium-review-panel-header h1').html(response.title + " Reviews");
                $('.streamium-review-panel').addClass('is-visible');
                $('html, body').addClass('overflow-hidden');

            }

        }); // end jquery 

    });

    //close the lateral panel
    $('body').on('click', '.streamium-review-panel', function() {
        
        if ($(event.target).is('.streamium-review-panel') || $(event.target).is('.streamium-review-panel-close')) {
            $('.streamium-review-panel').removeClass('is-visible');
            $('html, body').removeClass('overflow-hidden');
            event.preventDefault();
        }

    });

    $('body').on('click', '.streamium-review-panel-close', function() {
        
        $('.streamium-review-panel').removeClass('is-visible');
        $('html, body').removeClass('overflow-hidden');
        event.preventDefault();

    });

    $('body').on('click', '.streamium-review-like-btn', function() {

        var post_id = $(this).attr('data-id');

        swal({
            title: streamium_object.swal_glad_you_liked_it,
            text: streamium_object.swal_tell_us_why,
            type: "input",
            showCancelButton: true, 
            closeOnConfirm: false,
            cancelButtonText: streamium_object.swal_cancel,      
            confirmButtonText: streamium_object.swal_ok, 
            animation: "slide-from-top",
            inputPlaceholder: streamium_object.swal_write_something
        },
        function(inputValue) {
            
            // If no value isset still allow it to be submitted
            if (inputValue === "") {
                inputValue = streamium_object.swal_enter_chars;
            }

            $.ajax({
                url: streamium_object.ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'streamium_add_review',
                    post_id: post_id,
                    message: inputValue,
                    security: streamium_object.nonce
                },
                success: function(response) {

                    if (response.error) {

                        swal({
                            title: streamium_object.swal_error,
                            text: response.message,
                            type: "info",
                            showCancelButton: true,
                            confirmButtonText: streamium_object.swal_ok_got_it,
                            closeOnConfirm: true
                        },
                        function() {

                        });
                        return;

                    }

                    swal({
                        title: streamium_object.swal_success,
                        text: response.message,
                        type: "success",
                        showCancelButton: true,
                        confirmButtonText: streamium_object.swal_ok_got_it,
                        closeOnConfirm: true
                    },
                    function() {

                        $('#like-count-' + post_id).html(response.likes);

                    });
                    return;

                }

            }); // end jquery 
        });

        return false;

    });

});
/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

    $('.streamium-list-reviews').live('click', function(event) {

        event.preventDefault();

        // Clear any previous content
        $('.streamium-review-panel-content').empty();
        $('.streamium-review-panel-header h1').empty();

        var post_id = $(this).attr('data-id'),
            nonce = $(this).attr("data-nonce");

        $.ajax({
            url: streamium_object.ajax_url,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'streamium_get_reviews',
                post_id: post_id,
                nonce: nonce
            },
            success: function(response) {

                if (response.error) {

                    swal({
                            title: "Error",
                            text: response.message,
                            type: "info",
                            showCancelButton: false,
                            confirmButtonText: "Ok, thanks!",
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
                        '<div class="media-left">' + 
                          '<a href="#">' + 
                            '<img class="media-object" src="' + avatar + '" alt="' + date + '">' + 
                          '</a>' + 
                        '</div>' + 
                        '<div class="media-body">' + 
                          '<h4 class="media-heading">' + username +'</h4><small class="media-time">' + date +'</small>' + 
                          '<p>' + message + '</p>' + 
                        '</div>' + 
                    '</li>';
                    
                });
                html += '</ul>';
                $('.streamium-review-panel-content').html(html);
                $('.streamium-review-panel-header h1').html(response.title + " Reviews");

                $('.streamium-review-panel').addClass('is-visible');


            }

        }); // end jquery 

    });

    //clode the lateral panel
    $('.streamium-review-panel').live('click', function(event) {
        
        if ($(event.target).is('.streamium-review-panel') || $(event.target).is('.streamium-review-panel-close')) {
            $('.streamium-review-panel').removeClass('is-visible');
            event.preventDefault();
        }

    });

    $('.streamium-review-like-btn').live('click', function(event) {

        var post_id = $(this).find('.like-button').attr('data-id'),
            nonce = $(this).find('.like-button').attr("data-nonce");

        swal({
                title: "Great Glad You Liked It",
                text: "Please tell us why",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Write something"
            },
            function(inputValue) {
                
                if (inputValue === false) return false;

                if (inputValue === "") {
                    swal.showInputError("You need to write something!");
                    return false
                }

                $.ajax({
                    url: streamium_object.ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'streamium_likes',
                        post_id: post_id,
                        message: inputValue,
                        nonce: nonce
                    },
                    success: function(response) {

                        if (response.error) {

                            swal({
                                    title: "Error",
                                    text: response.message,
                                    type: "info",
                                    showCancelButton: true,
                                    confirmButtonText: "Ok, got it!",
                                    closeOnConfirm: true
                                },
                                function() {

                                });
                            return;

                        }

                        swal({
                                title: "Success",
                                text: response.message,
                                type: "success",
                                showCancelButton: true,
                                confirmButtonText: "Ok, got it!",
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
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

                    var avatar   = item.avatar;
                    var message  = item.message;
                    var rating   = parseInt(item.rating);
                    var date     = item.time;
                    var username = item.username;

                    var ratingHtml = '<div class="streamium-rating">';
                    for (var i = 1; i < 6; i++) {  
                        ratingHtml += '<span class="streamium-rating-star-static ' + ((rating >= i) ? 'checked' : '') + '" data-value="' + i + '"></span>';
                    }
                    ratingHtml += '</div>';

                    html += '<li class="media">' + 
                        '<div class="media-left">' + 
                          '<a href="#">' + 
                            '<img class="media-object" src="' + avatar + '" alt="' + date + '">' + 
                          '</a>' + 
                        '</div>' + 
                        '<div class="media-body">' + 
                          '<h4 class="media-heading">' + ratingHtml + '</h4>' + 
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
    $('.streamium-review-panel').live('click', function(event) {
        
        if ($(event.target).is('.streamium-review-panel') || $(event.target).is('.streamium-review-panel-close')) {
            $('.streamium-review-panel').removeClass('is-visible');
            $('html, body').removeClass('overflow-hidden');
            event.preventDefault();
        }

    });

    $('.streamium-review-panel-close').live('click', function(event) {
        
        $('.streamium-review-panel').removeClass('is-visible');
        $('html, body').removeClass('overflow-hidden');
        event.preventDefault();

    });

    $('.streamium-review-like-btn').live('click', function(event) {

        var post_id = $(this).attr('data-id'),
            nonce = $(this).attr("data-nonce");

            $(document).on('click', ".streamium-rating-star", function() {

                $(this).parents('.streamium-rating').find('.streamium-rating-star').removeClass('checked');
                $(this).addClass('checked');
                var submitStars = $(this).attr('data-value');

            });
 
            swal({
                title: streamium_object.swal_glad_you_liked_it,
               html: true,
               text: '<div class="streamium-rating">' +
                    '<span class="streamium-rating-star" data-value="5"></span>' +
                    '<span class="streamium-rating-star" data-value="4"></span>' +
                    '<span class="streamium-rating-star" data-value="3"></span>' +
                    '<span class="streamium-rating-star" data-value="2"></span>' +
                    '<span class="streamium-rating-star" data-value="1"></span>' +
                '</div>', 
                type: "input",
                showCancelButton: true, 
                closeOnConfirm: false,
                cancelButtonText: streamium_object.swal_cancel,      
                confirmButtonText: streamium_object.swal_ok, 
                animation: "slide-from-top",
                inputPlaceholder: streamium_object.swal_write_something
            },
            function(inputValue) {

                var rating = $('.streamium-rating').find(".checked").attr('data-value');
                
                if (inputValue === false) return false;

                if (inputValue === "" || inputValue.length < 30 || rating === undefined) {
                    swal.showInputError(streamium_object.swal_enter_chars);
                    return false
                }

                $.ajax({
                    url: streamium_object.ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'streamium_likes',
                        post_id: post_id,
                        rating: rating,
                        message: inputValue,
                        nonce: nonce
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
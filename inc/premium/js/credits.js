jQuery( document ).ready(function( $ ) {

    $('body').on("click", '.streamium-view-cast', function(event) {

        event.preventDefault();

        var postId = $(this).data("id");

        // Check for series
        window.streamium.getData({
            action: "streamium_get_player_credits",
            postId: postId, 
            security: streamium_object.nonce
        }, function(response) {

            if (response.error) {
                console.log("ERROR:", response.message);
            }



            var html = '<ul class="list-unstyled">';
            $.each(response.data, function (i, item) {

                var avatar   = item.avatar;
                var message  = item.message;
                var date     = item.time;
                var username = item.username;
                var link     = item.link;
                html += '<li class="media">' + 
                      '<a href="#">' + 
                        '<img width="64" class="mr-3" src="' + avatar + '" alt="' + date + '">' + 
                      '</a>' + 
                    '<div class="media-body">' + 
                      '<h5 class="mt-0 mb-1">' + username +'</h5>' + 
                      '<p>' + message + ' - <a href="' + link + '">View all connections</a></p>' +
                    '</div>' + 
                '</li>';
                   
            });
            html += '</ul>';

            // open extra window
            $('.streamium-review-panel-content').html(html);
            $('.streamium-review-panel-header h1').html(response.title);
            $('.streamium-review-panel').addClass('is-visible');
            $('html, body').addClass('overflow-hidden');

        });

    });

    if (streamium_object.hasOwnProperty('query') && streamium_object.query.post_type === 'credits') {

        window.streamium.getData({
            action: "credits_api_post",
            query: streamium_object.query, // This is needed for custom post types
            security: streamium_object.nonce
        }, function(response) {

            if (response.error) {
                console.log("Error: ", response.message);
                return;
            }

            response.id = "credits";
            streamium.staticTemplate(response, function() {

                // Init ui this must be done after the dom and elements are fully set
                var setMargin = Math.round($('.tile').height() / 2);
                $('.carousels-blocks').css('margin-top', (setMargin+40) + 'px');
                $('.lazy').Lazy(streamium.lazy);

            });

        });

    }

});
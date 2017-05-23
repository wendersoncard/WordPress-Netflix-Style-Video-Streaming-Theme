jQuery(document).ready(function( $ ) {
    $.ajax({
        url: streamium_meta_object.api + "/api/connected_check",
        type: 'post',
        dataType: 'json',
        data: { 
            website: streamium_meta_object.connected_website
        },
        success: function(response) { 

            $.ajax({
                url: streamium_meta_object.ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'streamium_connection_checks',
                    connected_nonce: streamium_meta_object.connected_nonce,
                    connected_state: (response.error) ? true : false
                },
                success: function(response) {

                    console.info(response.message);

                }

            }); // end jquery 

        }

    }); // end jquery

    $('.notice-premium .notice-dismiss').on('click', function() {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: streamium_meta_object.ajax_url,
            data: {
                action: 'ajaxnopremium'
            },
            success: function(data){
                console.log(data);
            },
            error: function(err) {
                console.log(err);
            }
        });
    });
});
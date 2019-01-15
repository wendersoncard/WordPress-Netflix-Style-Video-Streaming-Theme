jQuery(document).ready(function($) {

    // NEEDED TO VALIDATE THE POST TYPES::
    /*if(window.hasOwnProperty('wp')){

        if(window.wp.hasOwnProperty('customize')){

            window.wp.customize.bind( 'saved', function () {

                $.ajax({
                    type: 'POST',
                    dataType: 'json', 
                    url: streamium_meta_object.ajax_url,
                    data: {
                        action: 'streamium_customizer_valid_types'
                    },
                    success: function(data){

                        if(!data.status){
                            alert(data.message);
                        }

                    },
                    error: function(err) {
                        console.log(err);
                    }
                });

            });
 
        }

    }*/

    $('#_customize-input-streamium_generate_mrss_key').on('click',function(event){
        event.preventDefault();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: streamium_meta_object.ajax_url,
            data: {
                action: 'mrss_generate_key'
            },
            success: function(data){
                if(data.status){
                    // SAVE THE KEY USING THE CUSTOMIZER API::
                    wp.customize( 'streamium_mrss_key', function ( obj ) {
                        obj.set( data.key );
                    });
                    alert('Key generated! Make sure you publish this update!');
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

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
                success: function(response) {} 
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

    $('.notice-demo-data .notice-dismiss').on('click', function() {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: streamium_meta_object.ajax_url,
            data: {
                action: 'ajaxnodemo'
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
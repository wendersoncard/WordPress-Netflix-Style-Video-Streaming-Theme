jQuery(document).ready(function($) {

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

});
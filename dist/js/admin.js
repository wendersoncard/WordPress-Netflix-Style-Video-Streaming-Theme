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

    // import demo data
    $('#demo-data').on('click', function(e) {
        $import_true = confirm('Are you sure you want to import the demo data? This will overwrite any existing data');
        if($import_true == false) return;

        $('.demo-data-notice p').html('Data is being imported please be patient as this can sometimes take a few minutes.');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: streamium_meta_object.ajax_url,
            data: {
                action: 'ajaximportdemo'
            },
            success: function(data){
                console.log(data);
                $('.demo-data-notice p').html('Data was successfully imported.');
            },
            error: function(err) {
                console.log(err);
            }
        });

        e.preventDefault();
    });
});

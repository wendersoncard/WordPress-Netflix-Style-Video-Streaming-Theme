jQuery( document ).ready(function( $ ) {

	$('body').on('click', '.streamium-user-profiles-add', function() {
        
        swal({
            title: streamium_object.swal_add_profile,
            text: streamium_object.swal_add_profile_para,
            type: "input",
            showCancelButton: true, 
            closeOnConfirm: false,
            cancelButtonText: streamium_object.swal_cancel,      
            confirmButtonText: streamium_object.swal_ok, 
            animation: "slide-from-top",
            inputPlaceholder: streamium_object.swal_add_profile_input
        },
        function(inputValue) {

            if (inputValue === false) return false;
            
            // If no value isset still allow it to be submitted
            if (inputValue === "") {
                inputValue = streamium_object.swal_enter_chars;
            }

            $.ajax({
                url: streamium_object.ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'streamium_add_user_profile',
                    profile: inputValue,
                    security: streamium_object.nonce
                },
                success: function(response) {

                    if (response.error) {

                        swal({
                            title: streamium_object.swal_error,
                            text: response.message,
                            type: "error"
                        },
                        function() {

                        });

                    }else{

                        swal({
                            title: streamium_object.swal_success,
                            text: response.message,
                            type: "success",
                            showCancelButton: true,
                            confirmButtonText: streamium_object.swal_ok_got_it,
                            closeOnConfirm: true
                        },
                        function() {

                            location.reload();

                        });

                    }
                    
                    return;

                }

            }); // end jquery 
        });
       
        return false;

    });

    $('body').on('click', '.streamium-user-profiles-delete', function() {
        
        var id = $(this).data('id');

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this profile history!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, I am sure!',
            cancelButtonText: "Cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm){

            if (isConfirm){
                
                $.ajax({
                    url: streamium_object.ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'streamium_delete_user_profile',
                        id: id,
                        security: streamium_object.nonce
                    },
                    success: function(response) {

                        if (response.error) {

                            swal({
                                title: streamium_object.swal_error,
                                text: response.message,
                                type: "error"
                            },
                            function() {

                            });

                        }else{

                            swal({
                                title: streamium_object.swal_success,
                                text: response.message,
                                type: "success",
                                showCancelButton: true,
                                confirmButtonText: streamium_object.swal_ok_got_it,
                                closeOnConfirm: true
                            },
                            function() {

                                location.reload();

                            });

                        }
                        
                        return;

                    }

                }); // end jquery 

            } else {
                swal("Cancelled", "Your profile remains", "info");
            }
            
        });
       
        return false;

    });

});
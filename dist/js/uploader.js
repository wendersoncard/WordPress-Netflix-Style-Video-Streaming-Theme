/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {

	console.log("uploader");

    var s3bubbleUploader = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        url : 'https://12s3dewvwev2222.s3.amazonaws.com/',
        drop_element: 'streamium-uploader',
        browse_button: 'streamium-add-to-queue',
        container: document.getElementById('streamium-uploader'),
        flash_swf_url: '/wp-includes/js/plupload/plupload.flash.swf',
        silverlight_xap_url : '/wp-includes/js/plupload/plupload.silverlight.xap',
        urlstream_upload: true,
        file_data_name: 'file', 
        multipart : true,
        multipart_params: {
            'acl': 'private',
            'success_action_status': '201',
            'key': '${filename}',
            'Filename': '${filename}',
            'AWSAccessKeyId' : streamium_uploader.app,		
			'policy': streamium_uploader.policy,
			'signature': streamium_uploader.signature
        },
        filters : {
			max_file_size : streamium_uploader.filesize + 'mb',
			mime_types: [
				{title : 'Allowed files', extensions : streamium_uploader.filetypes}
			]
		},
		init: {
	        PostInit: function() {
	 
	        },
	 
	        FilesAdded: function(up, files) {
	          
	        },

	        QueueChanged: function(up, files) {
	            if ( up.files.length ){
	                up.start();
	            }
	        },

	        BeforeUpload: function(up, file) {

	        },

	        UploadProgress: function(up, file) {

	        	$( '#streamium-uploader span.streamium-uploader-percent' ).text( file.percent );
	            $('#streamium-uploader span.streamium-uploader-label').html('Started');
	            $('.streamium-uploader-progressbar').css('width', file.percent + '%');
	            $('#streamium-uploader span.streamium-uploader-standby').html( file.percent + '%' );

	        },

	        FileUploaded: function(up, file) {

	            if (up.files.length == ((up.total.uploaded) + up.total.failed)) {

	            	$('#streamium-uploader span.streamium-uploader-label').html('Uploaded');
				    $('.streamium-uploader-standby').html('Files successfully uploaded');
	            }

	        },

	        StateChanged: function(up, err) {

	        },
	 
	        Error: function(up, err) {
	            
	        }
	    }
    });

    s3bubbleUploader.init();

});
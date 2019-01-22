/*--------------------------------------------------------*/
/* jQuery functions
/*--------------------------------------------------------*/
jQuery(document).ready(function($) {
    
    var IsMobile = false;
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        IsMobile = true;
    }
	
	function setupStreamiumUploader(){

		if(streamium_uploader.length === 0){
			return;
		}
 
	    var s3bubbleUploader = new plupload.Uploader({
	        runtimes: 'html5,flash,silverlight,html4',
	        url : 'https://' + streamium_uploader.bucket + '.s3.amazonaws.com/',
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
				max_file_size : streamium_uploader.filesize,
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

		        	if(IsMobile){
						var rand = (Math.floor(Math.random() * (10000 - 1 + 1)) + 1) + '_';
			            if(streamium_uploader.folder != ''){
			            	up.settings.multipart_params.key = ( streamium_uploader.folder + '/' + rand + file.name);
			            	up.settings.multipart_params.Filename = ( streamium_uploader.folder + '/' + rand + file.name);
			            }else{
			            	up.settings.multipart_params.key = rand + file.name;
			            	up.settings.multipart_params.Filename = rand + file.name;
			            }
	                }else{
						if(streamium_uploader.folder != ''){
			            	up.settings.multipart_params.key = ( streamium_uploader.folder + '/' + file.name);
			            	up.settings.multipart_params.Filename = ( streamium_uploader.folder + '/' + file.name);
			            }else{
			            	up.settings.multipart_params.key = file.name;
			            	up.settings.multipart_params.Filename = file.name;
			            }
	                }

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

					    $.ajax({
			                url: streamium_object.ajax_url,
			                type: 'post',
			                dataType: 'json',
			                data: {
			                    action: 'streamium_user_content_uploader_email',
								bucket: streamium_uploader.bucket,
								folder: streamium_uploader.folder,
								security: streamium_uploader.nonce
			                },
			                success: function(response) {

			                	if(response.error){
			                	
			                		console.log(response.message);
			                	
			                	}
			                    
			                }

			            }); // end jquery 

		            }

		        },

		        StateChanged: function(up, err) {

		        },
		 
		        Error: function(up, err) {
		            
		        }
		    }
	    });
    	s3bubbleUploader.init();

	}
    
    // Check if the uploader exists before initialising
    if ($(".streamium-uploader")[0]){
    	setupStreamiumUploader();
    }

});
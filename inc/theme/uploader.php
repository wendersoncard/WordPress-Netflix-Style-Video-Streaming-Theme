<?php

function streamium_user_content_uploader(){

	$accessKeyId = "AKIAIHSEX5ZA6DUXT56Q"; //get_option("s3-s3bubble_uploader_access_key");
	$secret	     = "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"; //get_option("s3-s3bubble_uploader_secret_key");

	// Check for email
	$ajax_nonce = wp_create_nonce( "s3bubble-nonce-security" );

	extract( shortcode_atts( array(
		'bucket'   => '12s3dewvwev2222',
		'folder'   => 'userid'
	), $atts, 'streamium_uploader' ) );

	$current_user = wp_get_current_user();
	if($folder == "userid"){
		$folder = $current_user->ID;
	}else if($folder == "username"){
		$folder = $current_user->user_login;
	}

	// prepare policy
	$policy = base64_encode(json_encode(array(
		'expiration' => date('Y-m-d\TH:i:s.000\Z', strtotime('+1 day')),  
		'conditions' => array(
			array('bucket' => $bucket),
			array('acl' => 'private'),
			array('success_action_status' => '201'),
			array('starts-with', '$key', ''),
			array('starts-with', '$Filename', ''), 
			array('starts-with', '$name', '')
		)
	)));

	// sign policy
	$signature = base64_encode(hash_hmac('sha1', $policy, $secret, true));

	// include the uploader script
	wp_enqueue_style( 'streamium-uploader', get_template_directory_uri() . '/dist/css/uploader.css' );
    wp_enqueue_script( 'plupload' );
    wp_enqueue_script( 'streamium-uploader', get_template_directory_uri() . '/dist/js/uploader.js', array( 'jquery') );
    wp_localize_script( 'streamium-uploader', 'streamium_uploader', 
        array( 
            'nonce' => $ajax_nonce,
            'bucket' => $bucket,
            'folder' => $folder,
            'app' => $accessKeyId,
            'policy' => $policy,
            'signature' => $signature,
            'filetypes' => "*", //$filetypes,
            'filesize' => "200", //$filesize
        )
    );

	if ( is_user_logged_in() ) {

		return "<div id='streamium-uploader' class='streamium-uploader'>
				<input type='button' id='streamium-add-to-queue' class='streamium-uploader-label' value='Select Files' />
				<div class='streamium-uploader-progressbar'></div>
				<span class='streamium-uploader-standby'>Waiting for files...</span>
				<span class='streamium-uploader-progress'>Uploading - <span class='streamium-uploader-percent'></span>%</span>
			</div>";
	
	}else{
	
		return "<div class='s3bubble-uploader-loggedout'><h2>Please login to upload</h2></div>";
	
	}

}

add_shortcode( 'streamium_uploader', 'streamium_user_content_uploader' );
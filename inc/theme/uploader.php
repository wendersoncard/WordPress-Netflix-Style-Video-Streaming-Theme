<?php

/**
 * Direct aws uploader
 *
 * @return bool
 * @author  @sameast
 */
function streamium_user_content_uploader($atts){
    
    // Check for email
	$ajax_nonce = wp_create_nonce( "streamium-uploader-nonce-security" );
	$accessKeyId = get_theme_mod( 'streamium_aws_media_uploader_access_key' );
	$secret	     = get_theme_mod( 'streamium_aws_media_uploader_secret_key' );
    
    if(!get_theme_mod( 'streamium_enable_premium' )) {
    	return "<span style='color:black;'>ERROR: only available on the premium package please upgrade.</span>";
    }

    if(empty($accessKeyId) || empty($secret)){
		return "<span style='color:black;'>ERROR: Please add your keys within the Appearance -> Customizer menu.</span>";
	}

	extract( shortcode_atts( array(
		'bucket'   => '',
		'folder'   => 'userid',
		'filesize' => '20',
		'filetypes' => '*'
	), $atts, 'streamium_uploader' ) );

	if(empty($bucket)){
		return "<span style='color:black;'>ERROR: Please add a bucket param to your shortcode.</span>";
	}

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
	wp_enqueue_style( 'streamium-uploader', get_template_directory_uri() . '/dist/css/uploader.min.css' );
    wp_enqueue_script( 'plupload' );
    wp_enqueue_script( 'streamium-uploader', get_template_directory_uri() . '/dist/js/uploader.min.js', array( 'jquery' ),'1.1', true );
    wp_localize_script( 'streamium-uploader', 'streamium_uploader', 
        array( 
            'nonce' => $ajax_nonce,
            'bucket' => $bucket,
            'folder' => $folder,
            'app' => $accessKeyId,
            'policy' => $policy,
            'signature' => $signature,
            'filetypes' => $filetypes,
            'filesize' => $filesize,
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

/**
 * notify user whe upload takes place
 *
 * @return bool
 * @author  @sameast
 */
function streamium_user_content_uploader_email(){
    
    check_ajax_referer( 'streamium-uploader-nonce-security', 'security' );
    $s3bubble_uploader_email = get_theme_mod( 'streamium_aws_media_uploader_notification_email' );
    $s3bubble_subject = get_bloginfo() . ' ' . date('m/d/y H:i:s', time()) . ' new upload';
    $bucket  = empty($_POST['bucket']) ? 'no bucket' : $_POST['bucket'];
    $folder  = empty($_POST['folder']) ? 'no folder' : $_POST['folder'];

    // send attached mysql to user
	$headers = "From: support@s3bubble.com\r\n";
	$headers .= "Reply-To: support@s3bubble.com\r\n";
	wp_mail($s3bubble_uploader_email, $s3bubble_subject, 'S3Bubble new upload to bucket ' . $bucket . ' and folder ' . $folder, $headers);
	echo json_encode(array(
		'error' => false,
		'message' => 'Notification email sent'
	));

	wp_die();	
	
}

add_action( 'wp_ajax_streamium_user_content_uploader_email', 'streamium_user_content_uploader_email' );
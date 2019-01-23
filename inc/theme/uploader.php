<?php

/**
 * Direct aws uploader
 *
 * @return bool
 * @author  @sameast
 */
function streamium_user_content_uploader($atts){
    
    // Check for email
	$ajax_nonce  = wp_create_nonce( "streamium-uploader-nonce-security" );
	$accessKeyId = get_theme_mod( 'streamium_aws_media_uploader_access_key' );
	$secret	     = get_theme_mod( 'streamium_aws_media_uploader_secret_key' );
	$loginOnly	 = get_theme_mod( 'streamium_aws_media_uploader_login' );

    if(empty($accessKeyId) || empty($secret)){
		return "<span style='color:black;'>ERROR: Please add your keys within the Appearance -> Customizer menu.</span>";
	}

	extract( shortcode_atts( array(
		'bucket'   => '',
		'folder'   => 'userid',
		'filesize' => '1gb',  
		'filetypes' => '*'
	), $atts, 'streamium_uploader' ) );
  
	if(empty($bucket)){
		return "<span style='color:black;'>ERROR: Please add a bucket param to your shortcode.</span>";
	}

	if ( is_user_logged_in() ) {

		$current_user = wp_get_current_user();
		if($folder == "userid"){
			$folder = $current_user->ID;
		}else if($folder == "username"){
			$folder = $current_user->user_login;
		}
 
	}else{

		$folder = "loggedout";

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

	// Pass the signed objects to the dom
    wp_localize_script( 'streamium-production', 'streamium_uploader', 
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

    if($loginOnly){

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

    }else{

    	return "<div id='streamium-uploader' class='streamium-uploader'>
				<input type='button' id='streamium-add-to-queue' class='streamium-uploader-label' value='Select Files' />
				<div class='streamium-uploader-progressbar'></div>
				<span class='streamium-uploader-standby'>Waiting for files...</span>
				<span class='streamium-uploader-progress'>Uploading - <span class='streamium-uploader-percent'></span>%</span>
			</div>";

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

    if ( ! wp_verify_nonce( $_REQUEST['security'], 'streamium-uploader-nonce-security' ) || ! isset( $_REQUEST['security'] ) ) {
        exit( "No naughty business please" );
    }

    $s3bubble_uploader_email = get_theme_mod( 'streamium_aws_media_uploader_notification_email' );
    $s3bubble_subject        = 'New Upload ' . date('m/d/y H:i:s', time());
    $bucket                  = empty($_POST['bucket']) ? 'no bucket' : $_POST['bucket'];
    $folder                  = empty($_POST['folder']) ? 'no folder' : $_POST['folder'];

    // send attached mysql to user
    $headers = array(
    	'Content-Type: text/html; charset=UTF-8',
	  	'From: Streamium Theme ' . "\r\n"
	);
	$headers = "From: support@s3bubble.com\r\n";
	$headers .= "Reply-To: support@s3bubble.com\r\n";
	wp_mail($s3bubble_uploader_email, $s3bubble_subject, 'S3Bubble new upload to bucket ' . $bucket . ' and folder ' . $folder, $headers);

	wp_send_json(array(
        'error'   => false,
        'to'      => $s3bubble_uploader_email,
    	'message' => __( 'Notification email sent', 'streamium' ) 
    ));
	
}

add_action( 'wp_ajax_streamium_user_content_uploader_email', 'streamium_user_content_uploader_email' );
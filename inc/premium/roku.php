<?php

/**
 * Resume video time ajax
 *
 * @return bool
 * @author  @sameast
 */
function mrss_generate_key() {

   // Generate a random salt
    $salt = base_convert(bin2hex(random_bytes(64)), 16, 36);

    // If an error occurred, then fall back to the previous method
    if ($salt === FALSE)
    {
        $salt = hash('sha256', time() . mt_rand());
    }

    $new_key = substr($salt, 0, 40);
    
	wp_send_json(array(
		'status' => true,
		'message' => 'Success',
		'key' => $new_key,
	));      

    die(); 

}

add_action( 'wp_ajax_mrss_generate_key', 'mrss_generate_key' );


?>
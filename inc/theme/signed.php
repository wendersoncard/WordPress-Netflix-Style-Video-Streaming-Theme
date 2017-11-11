<?php

class StreamiumSignedCookies {

	protected $key_pair_id;

	protected $private_key_file;

	public function __construct($key_pair_id, $private_key_file) {
		$this->key_pair_id = $key_pair_id;
		$this->private_key_file = $private_key_file;
	}

	private function sign($policy){
    	$private_key = file_get_contents($this->private_key_file);
        $signature = '';
        openssl_sign($policy, $signature, $private_key);

        return $signature;
    }

	private function encode($policy){
        return strtr(base64_encode($policy), '+=/', '-_~');
    }

	public function get_custom_policy_stream_name($cname, $expires) {

		$protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
		$video_path = $protocol . '://' . $cname . '.' . $_SERVER['HTTP_HOST'] . '/*';
		$policy = <<<POLICY
{
"Statement": [
    {
        "Resource": "{$video_path}",
        "Condition": {
            "IpAddress": {"AWS:SourceIp": "{$_SERVER['REMOTE_ADDR']}/32"},
            "DateLessThan": {"AWS:EpochTime": {$expires}}
        }
    }
]
}
POLICY;

		$policy = preg_replace('/\s/s', '', $policy);
		$encoded_policy = $this->encode($policy);
		$encoded_signature = $this->encode($this->sign($policy)); 

		setcookie('CloudFront-Policy', $encoded_policy, 0, "", $_SERVER['HTTP_HOST']);
		setcookie('CloudFront-Signature', $encoded_signature, 0, "", $_SERVER['HTTP_HOST']);
		setcookie('CloudFront-Key-Pair-Id', $this->key_pair_id, 0, "", $_SERVER['HTTP_HOST']);

	}

}

	
function streamium_set_signed_cookies() {

	if(get_theme_mod( 'streamium_enable_signed_cookies', false )){

		if (file_exists(WP_CONTENT_DIR . '/pem/pk-' .  get_theme_mod( 'streamium_aws_signed_cookies_key_pair' ) . '.pem')) {

			// Create a signed cookie for the resource using a custom policy
			$signCookies = new StreamiumSignedCookies('APKAIVH6YKQNSIPLB2BQ',WP_CONTENT_DIR . '/pem/pk-' .  get_theme_mod( 'streamium_aws_signed_cookies_key_pair' ) . '.pem');
		    $signedCookieCustomPolicy = $signCookies->get_custom_policy_stream_name(get_theme_mod( 'streamium_aws_signed_cookies_cname' ), time() + 10);
		    
		}
	}

}
add_action( 'init', 'streamium_set_signed_cookies' );
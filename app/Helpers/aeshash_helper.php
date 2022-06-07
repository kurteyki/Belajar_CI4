<?php 

/**
* https://gist.github.com/joashp/a1ae9cb30fa533f4ad94
*/
function aeshash($action, $string, $aeskey) {
	$output = false;
	$encrypt_method = "AES-256-CBC";
	$secret_key = $aeskey;
	$secret_iv = $aeskey;
	// hash
	$key = hash('sha256', $secret_key);

	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	if ( $action == 'enc' ) {
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
	} else if( $action == 'dec' ) {
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	}
	return $output;
}
<?php

// Do recaptcha validation here so we can only load for php 5.3 and above.
require_once FL_BUILDER_DIR . 'includes/vendor/recaptcha/autoload.php';

if ( function_exists( 'curl_exec' ) ) {
	$recaptcha = new \ReCaptcha\ReCaptcha( $recaptcha_secret_key, new \ReCaptcha\RequestMethod\CurlPost() );
} else {
	$recaptcha = new \ReCaptcha\ReCaptcha( $recaptcha_secret_key );
}

if ( 'invisible_v3' === $recaptcha_validate_type ) {
	// @codingStandardsIgnoreStart
	// V3
	$resp = $recaptcha->setExpectedHostname( $_SERVER['SERVER_NAME'] )
					  ->setExpectedAction( 'Form' )
					  ->setScoreThreshold( 0.5 )
					  ->verify( $recaptcha_response, $_SERVER['REMOTE_ADDR'] );
	// @codingStandardsIgnoreEnd
} else {
	// V2
	$resp = $recaptcha->verify( $recaptcha_response, $_SERVER['REMOTE_ADDR'] );
}

if ( $resp->isSuccess() ) {
	$this->is_success = false;
} else {
	$error_codes = array();
	foreach ( $resp->getErrorCodes() as $code ) {
		$error_codes[] = $code;
	}
	// translators: %s: reCAPTCHA Error
	wp_send_json_error( array(
		'code'	=> 'recaptcha',
		'message' => sprintf( __( '<strong>reCAPTCHA Error: </strong> %s', 'bb-powerpack' ), implode( ' | ', $error_codes ) ),
	) );
}

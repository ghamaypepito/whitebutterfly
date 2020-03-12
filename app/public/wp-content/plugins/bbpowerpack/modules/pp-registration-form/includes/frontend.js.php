<?php $fields = $module->get_form_fields( $id ); ?>
;(function($) {

	$(function() {
		new PPRegistrationForm({
			id: '<?php echo $id; ?>',
			min_pass_length: '<?php echo $module->password_length; ?>',
			pws_meter: <?php echo 'yes' === $settings->enable_pws_meter ? 'true' : 'false';  ?>,
			i18n: {
				messages: {
					error: {
						invalid_username: '<?php _e( 'This username is invalid because it uses illegal characters. Please enter a valid username.', 'bb-powerpack' ); ?>',
						username_exists: '<?php _e( 'This username is already registered. Please choose another one.', 'bb-powerpack' ); ?>',
						empty_email: '<?php _e( 'Please type your email address.', 'bb-powerpack' ); ?>',
						invalid_email: '<?php _e( 'The email address isn&#8217;t correct!', 'bb-powerpack' ) ?>',
						email_exists: '<?php _e( 'The email is already registered, please choose another one.', 'bb-powerpack' ); ?>',
						password: '<?php echo sprintf( __( 'Password must not contain the character %s', 'bb-powerpack' ), json_encode( "\\" ) ); // translators: %s denotes forward slash character. ?>',
						password_length: '<?php echo sprintf( __( 'Your password should be at least %d characters long.', 'bb-powerpack' ), $module->password_length ); // translators: %d denotes password length. ?>',
						password_mismatch: '<?php _e( 'Password does not match.', 'bb-powerpack' ); ?>',
						invalid_url: '<?php _e( 'URL seems to be invalid.', 'bb-powerpack' ); ?>',
						recaptcha_php_ver: '<?php _e( 'reCAPTCHA API requires PHP version 5.3 or above.', 'bb-powerpack' ); ?>',
						recaptcha_missing_key: '<?php _e( 'Your reCAPTCHA Site or Secret Key is missing!', 'bb-powerpack' ); ?>',
					},
					success: '<?php echo $settings->success_message; ?>',
				},
				pw_toggle_text: {
					show: '<?php _e( 'Show password', 'bb-powerpack' ); ?>',
					hide: '<?php _e( 'Hide password', 'bb-powerpack' ); ?>',
				},
			},
			ajaxurl: '<?php echo admin_url( 'admin-ajax.php' ); ?>'
		});
	});

})(jQuery);

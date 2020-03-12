;(function($) {

	new PPLoginForm({
		id: '<?php echo $id; ?>',
		page_url: '<?php echo get_permalink(); ?>',
		messages: {
			empty_username: '<?php _e( 'Enter a username or email address.', 'bb-powerpack' ); ?>',
			empty_password: '<?php _e( 'Enter password.', 'bb-powerpack' ); ?>',
			empty_password_1: '<?php _e( 'Enter a password.', 'bb-powerpack' ); ?>',
			empty_password_2: '<?php _e( 'Re-enter password.', 'bb-powerpack' ); ?>',
			email_sent: '<?php _e( 'A password reset email has been sent to the email address for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'bb-powerpack' ); ?>',
			reset_success: '<?php _e( 'You password has been reset successfully.', 'bb-powerpack' ); ?>',
		},
	});

})(jQuery);
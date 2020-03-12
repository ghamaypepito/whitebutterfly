<?php

defined( 'ABSPATH' ) || exit;

do_action( 'pp_login_form_before_lost_password_form', $settings, $id );
?>
<form method="post" class="pp-login-form--lost-pass">
	<p><?php echo apply_filters( 'pp_login_form_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'bb-powerpack' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>
	<div class="pp-login-form-fields">
		<div class="pp-login-form-field pp-field-group pp-field-type-text">
			<?php if ( $show_label ) { ?>
			<label for="user_login"><?php esc_html_e( 'Username or Email', 'bb-powerpack' ); ?></label>
			<?php } ?>
			<input class="pp-login-form--input" type="text" name="user_login" id="user_login" size="1" autocomplete="username" />
		</div>
		<div class="pp-field-group pp-field-type-submit">
			<button type="submit" name="pp-login-form-lost-pw" class="pp-login-form--button">
				<span class="pp-login-form--button-text"><?php esc_html_e( 'Reset password', 'bb-powerpack' ); ?></span>
			</button>
		</div>
	</div>

	<?php wp_nonce_field( 'lost_password', 'pp-lf-lost-password-nonce' ); ?>
</form>
<?php
do_action( 'pp_login_form_after_lost_password_form', $settings, $id );
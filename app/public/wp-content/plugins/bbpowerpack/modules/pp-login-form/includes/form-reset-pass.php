<?php

defined( 'ABSPATH' ) || exit;

$key = esc_attr( wp_unslash( $_GET['key'] ) );
$user_id = esc_attr( wp_unslash( $_GET['id'] ) );
$userdata = get_userdata( absint( $user_id ) );
$user_login = $userdata ? $userdata->user_login : '';

if ( ! $module->check_password_reset_key( $key, $user_login ) ) {
	echo '<span class="pp-lf-error">' . $module->get_error_message() . '</span>';
	return;
}

do_action( 'pp_login_form_before_reset_password_form', $settings, $id );
?>
<form method="post" class="pp-login-form--reset-pass">
	<p><?php echo apply_filters( 'pp_login_form_reset_password_message', esc_html__( 'Enter a password below.', 'bb-powerpack' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>
	<div class="pp-login-form-fields">
		<div class="pp-login-form-field pp-field-group pp-field-type-text">
			<label for="password_1"><?php esc_html_e( 'New Password', 'bb-powerpack' ); ?></label>
			<input class="pp-login-form--input" type="password" name="password_1" id="password_1" size="1" autocomplete="new-password" />
		</div>
		<div class="pp-login-form-field pp-field-group pp-field-type-text">
			<label for="password_2"><?php esc_html_e( 'Re-enter New Password', 'bb-powerpack' ); ?></label>
			<input class="pp-login-form--input" type="password" name="password_2" id="password_2" size="1" autocomplete="new-password" />
		</div>
		<div class="pp-field-group pp-field-type-submit">
			<button type="submit" name="pp-login-form-reset-pw" class="pp-login-form--button">
				<span class="pp-login-form--button-text"><?php esc_html_e( 'Save', 'bb-powerpack' ); ?></span>
			</button>
		</div>
	</div>

	<input type="hidden" name="reset_key" value="<?php echo $key; ?>" />
	<input type="hidden" name="reset_login" value="<?php echo $user_login; ?>" />

	<?php wp_nonce_field( 'reset_password', 'pp-lf-reset-password-nonce' ); ?>
</form>
<?php
do_action( 'pp_login_form_after_reset_password_form', $settings, $id );
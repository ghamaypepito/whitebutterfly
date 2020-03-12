<?php
$template_attrs = array();

if ( isset( $module->template_id ) ) {
	$template_attrs['data-template-id'] = $module->template_id;
	$template_attrs['data-template-node-id'] = $module->template_node_id;
}

$fields = $module->get_form_fields( $id );
?>
<?php if ( count( $fields ) ) : ?>
	<div class="pp-rf-wrap">

	<?php if ( is_user_logged_in() && ! FLBuilderModel::is_builder_active() ) { ?>
		<div class="pp-rf-logged_in">
			<p><?php echo $module->get_custom_messages( 'already_registered' ); ?></p>
		</div>
	<?php } else { ?>

		<?php if ( get_option( 'users_can_register' ) ) { ?>

			<form <?php echo $module->get_form_attrs( $id, $template_attrs ); ?>>
				<?php
				/**
				 * Hook to add custom content just after form opening tag.
				 * 
				 * @since 2.7.10
				 * 
				 * @param object $settings	Module settings.
				 */
				do_action( 'pp_rf_form_start', $settings );
				?>

				<div class="pp-rf-fields-wrap">
					<?php
					$fields_rendered = array();
					$fields_missed = array();

					foreach ( $fields as $field ) {
						if ( ! in_array( $field->name, $fields_rendered ) ) {
							$fields_rendered[] = $field->name;
						} else {
							echo '<span class="pp-rf-failed-error pp-rf-field-exist">';
							// translators: %1$s - field label, %2$s - field type.
							echo sprintf( __( '%1$s (%2$s) field is already exist.', 'bb-powerpack' ), $field->field_label, $field->field_type );
							echo '</span>';
							continue;
						}
						?>
						<div <?php echo $module->get_field_wrap_attrs( $field ); ?>>
							<?php $module->render_field_label( $field ); ?>
							<?php $module->render_field_control( $field ); ?>
							<?php $module->render_validation_msg( $field ); ?>
						</div>
					<?php
					}

					if ( ! in_array( 'user_email', $fields_rendered ) ) {
						echo '<span class="pp-rf-failed-error pp-error-fields-count">Email field is required!</span>';
						$fields_missed[] = 'user_email';
					}
					if ( ! in_array( 'user_pass', $fields_rendered ) ) {
						echo '<span class="pp-rf-failed-error pp-error-fields-count">Password field is required!</span>';
						$fields_missed[] = 'user_pass';
					}

					// Render reCAPTCHA field.
					if ( 'yes' === $settings->enable_recaptcha ) {
						$module->render_recaptcha_field( $id );
					}

					// Render button.
					if ( count( $fields_missed ) < 1 ) {
						$module->render_button();
					}
					?>
				</div>

				<div class="pp-after-submit-action">
					<?php if ( 'no' === $settings->redirect && 'no' === $settings->show_success_message ) { ?>
						<span class="pp-rf-success-none" style="display:none;"><?php echo $module->get_custom_messages( 'no_message' ); ?></span>
					<?php }; ?>

					<span class="pp-rf-failed-error" style="display:none;"><?php echo $module->get_custom_messages( 'on_fail' ); ?></span>
				</div>

				<?php
				/**
				 * Hook to add custom content just before form closing tag.
				 * 
				 * @since 2.7.10
				 * 
				 * @param object $settings	Module settings.
				 */
				do_action( 'pp_rf_form_end', $settings );
				?>
			</form>

			<?php if ( 'no' === $settings->redirect && 'yes' === $settings->show_success_message ) : ?>
				<div class="pp-after-submit-action pp-success-custom-message" style="display:none;">
					<span class="pp-rf-success-msg"><?php echo $settings->success_message; ?></span>
				</div>
			<?php endif; ?>

		<?php } else { ?>

			<?php if ( is_multisite() ) { ?>
				<div class="pp-rf-register-error">
					<?php echo __( 'You must enable "User accounts may be registered" setting under Network Admin > Dashboard > Settings > "Allow new registrations"', 'bb-powerpack' ); ?>
				</div>
			<?php } else { ?>
				<div class="pp-rf-register-error">
					<?php echo __( 'You must enable "Anyone can register" setting under Dashboard > Settings > General > Membership.', 'bb-powerpack' ); ?>
				</div>
			<?php } ?>

		<?php } ?>

	<?php } ?>
	</div>
<?php endif; ?>

<?php
/*************************************
 * Form Wrap
*************************************/
?>
<?php
// Form - Padding
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'form_padding',
		'selector'     => ".fl-node-$id .pp-rf-wrap",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'form_padding_top',
			'padding-right'  => 'form_padding_right',
			'padding-bottom' => 'form_padding_bottom',
			'padding-left'   => 'form_padding_left',
		),
	)
);
// Form - Border
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'form_border',
		'selector'     => ".fl-node-$id .pp-rf-wrap",
	)
);
?>
<?php if ( 'color' === $settings->form_bg_type ) { ?>
	<?php if ( isset( $settings->form_bg_color ) && ! empty( $settings->form_bg_color ) ) { ?>
		.fl-node-<?php echo $id; ?> .pp-rf-wrap {
			background: <?php echo pp_get_color_value( $settings->form_bg_color ); ?>;
		}
	<?php } ?>
<?php } else { ?>
	.fl-node-<?php echo $id; ?> .pp-rf-wrap {
		<?php if ( $settings->form_bg_image ) { ?>
			background-image: url( '<?php echo $settings->form_bg_image_src; ?>' );
		<?php } ?>
		<?php if ( $settings->form_bg_size ) { ?>
			background-size: <?php echo $settings->form_bg_size; ?>;
		<?php } ?>
		<?php if ( $settings->form_bg_repeat ) { ?>
			background-repeat: <?php echo $settings->form_bg_repeat; ?>;
		<?php } ?>
	}
<?php } ?>

<?php
/*************************************
 * Password Strength Meter
*************************************/
?>
<?php if ( isset( $settings->enable_pws_meter ) && 'yes' === $settings->enable_pws_meter ) { ?>
.fl-node-<?php echo $id; ?> .pp-rf-field .pp-rf-pws-status.short {
	<?php if ( ! empty( $settings->short_pw_color ) ) { ?>
		color: #<?php echo $settings->short_pw_color; ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-field .pp-rf-pws-status.bad {
	<?php if ( ! empty( $settings->weak_pw_color ) ) { ?>
		color: #<?php echo $settings->weak_pw_color; ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-field .pp-rf-pws-status.good {
	<?php if ( ! empty( $settings->good_pw_color ) ) { ?>
		color: #<?php echo $settings->good_pw_color; ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-field .pp-rf-pws-status.strong {
	<?php if ( ! empty( $settings->strong_pw_color ) ) { ?>
		color: #<?php echo $settings->strong_pw_color; ?>;
	<?php } ?>
}
<?php } ?>

<?php
/*************************************
 * Button
*************************************/
?>
<?php
// Button Padding
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_padding',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-button-wrap .pp-button",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'button_padding_top',
			'padding-right'  => 'button_padding_right',
			'padding-bottom' => 'button_padding_bottom',
			'padding-left'   => 'button_padding_left',
		),
	)
);
// Button Margin
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_margin',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-button-wrap .pp-button",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'button_margin_top',
			'margin-right'  => 'button_margin_right',
			'margin-bottom' => 'button_margin_bottom',
			'margin-left'   => 'button_margin_left',
		),
	)
);
// Button Border - Settings
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_border_group',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-button-wrap .pp-button",
	)
);
// Button Hover Border - Settings
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_border_hover',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-button-wrap .pp-button:hover",
	)
);
// Button Typography
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_typography',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-button-wrap .pp-button",
	)
);
?>

.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap {
	justify-content: <?php echo $settings->button_alignment; ?>;
}

.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button {
	<?php if ( isset( $settings->button_text_color ) && ! empty( $settings->button_text_color ) ) { ?>
		color: <?php echo pp_get_color_value( $settings->button_text_color ); ?>;
	<?php } ?>
	<?php if ( isset( $settings->button_bg_color ) && ! empty( $settings->button_bg_color ) ) { ?>
		background: <?php echo pp_get_color_value( $settings->button_bg_color ); ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button:hover {
	<?php if ( isset( $settings->button_hover_text_color ) && ! empty( $settings->button_hover_text_color ) ) { ?>
		color: <?php echo pp_get_color_value( $settings->button_hover_text_color ); ?>;
	<?php } ?>
	<?php if ( isset( $settings->button_hover_bg_color ) && ! empty( $settings->button_hover_bg_color ) ) { ?>
		background: <?php echo pp_get_color_value( $settings->button_hover_bg_color ); ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button {
	<?php if ( 'auto' === $settings->button_width ) { ?>
		width: auto;
	<?php } elseif ( 'full' === $settings->button_width ) { ?>
		width: 100%;
	<?php } else { ?>
		width: <?php echo isset( $settings->button_custom_width ) && ! empty( $settings->button_custom_width ) ? $settings->button_custom_width . $settings->button_custom_width_unit : ''; ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button-icon {
	<?php if ( isset( $settings->button_icon_color ) && ! empty( $settings->button_icon_color ) ) { ?>
		color: <?php echo pp_get_color_value( $settings->button_icon_color ); ?>;
	<?php } ?>
	<?php if ( isset( $settings->button_icon_size ) && ! empty( $settings->button_icon_size ) ) { ?>
		font-size: <?php echo $settings->button_icon_size; ?>px;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button:hover .pp-button-icon {
	<?php if ( isset( $settings->button_hover_icon_color ) && ! empty( $settings->button_hover_icon_color ) ) { ?>
		color: <?php echo pp_get_color_value( $settings->button_hover_icon_color ); ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button-icon.pp-button-icon-after {
	<?php if ( isset( $settings->button_icon_spacing ) && ! empty( $settings->button_icon_spacing ) ) { ?>
		margin-left: <?php echo $settings->button_icon_spacing; ?>px;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button-icon.pp-button-icon-before {
	<?php if ( isset( $settings->button_icon_spacing ) && ! empty( $settings->button_icon_spacing ) ) { ?>
		margin-right: <?php echo $settings->button_icon_spacing; ?>px;
	<?php } ?>
}

<?php
/*************************************
 * Fields group
*************************************/
?>
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-fields-wrap {
	<?php if ( isset( $settings->col_spacing ) ) { ?>
		<?php if ( absint( $settings->col_spacing ) < 1 ) { ?>
			margin-right: 0px;
			margin-left: 0px;
		<?php } else { ?>
			margin-right: calc( -<?php echo absint( $settings->col_spacing ); ?>px/2 );
			margin-left: calc( -<?php echo absint( $settings->col_spacing ); ?>px/2 );
		<?php } ?>
	<?php } ?>
	<?php if ( isset( $settings->rows_spacing ) && $settings->rows_spacing >= 0 ) { ?>
		margin-bottom: -<?php echo $settings->rows_spacing; ?>px;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field {
	<?php if ( isset( $settings->col_spacing ) ) { ?>
		<?php if ( absint( $settings->col_spacing ) < 1 ) { ?>
			padding-right: 0px;
			padding-left: 0px;
		<?php } else { ?>
			padding-right: calc( <?php echo absint( $settings->col_spacing ); ?>px/2 );
			padding-left: calc( <?php echo absint( $settings->col_spacing ); ?>px/2 );
		<?php } ?>
	<?php } ?>
	<?php if ( isset( $settings->rows_spacing ) && $settings->rows_spacing >= 0 ) { ?>
		margin-bottom: <?php echo $settings->rows_spacing; ?>px;
	<?php } ?>
}

<?php
/*************************************
 * Input control
*************************************/
?>
<?php
$input_classes = '.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field input[type=text],
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field input[type=password],
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field input[type=email],
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field input[type=tel],
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field input[type=date],
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field input[type=month],
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field input[type=week],
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field input[type=time],
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field input[type=number],
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field input[type=search],
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field input[type=url],
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field textarea,
				.fl-node-' . $id . ' .pp-rf-wrap .pp-rf-field select';
?>
<?php echo $input_classes; ?> {
	<?php if ( isset( $settings->input_field_text_color ) && ! empty( $settings->input_field_text_color ) ) { ?>
		color: <?php echo pp_get_color_value( $settings->input_field_text_color ); ?>;
	<?php } ?>
	<?php if ( isset( $settings->input_field_bg_color ) && ! empty( $settings->input_field_bg_color ) ) { ?>
		background: <?php echo pp_get_color_value( $settings->input_field_bg_color ); ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input:not([type="checkbox"]),
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input:not([type="radio"]),
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field select {
	<?php if ( ! empty( $settings->input_field_height ) ) { ?>
	height: <?php echo $settings->input_field_height; ?>px;
	<?php } ?>
	width: 100%;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field textarea {
	<?php if ( isset( $settings->input_textarea_height ) && ! empty( $settings->input_textarea_height ) ) { ?>
	height: <?php echo $settings->input_textarea_height; ?>px;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field-error .pp-rf-control,
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-validation-error .pp-rf-control {
	<?php if ( isset( $settings->input_error_border_color ) && ! empty( $settings->input_error_border_color ) ) { ?>
		border-color: #<?php echo $settings->input_error_border_color; ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field .pp-rf-control:focus {
	<?php if ( isset( $settings->input_focus_border_color ) && ! empty( $settings->input_focus_border_color ) ) { ?>
		border-color: #<?php echo $settings->input_focus_border_color; ?>;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field.pp-rf-field-pw-toggle input[name="user_pass"],
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field.pp-rf-field-pw-toggle + .pp-rf-field[data-field-type="confirm_user_pass"] input[name="confirm_user_pass"] {
	<?php if ( isset( $settings->input_field_padding_right ) && ! empty( absint( $settings->input_field_padding_right ) ) ) { ?>
		<?php $input_padding_right = absint( $settings->input_field_padding_right ); ?>
		padding-right: <?php echo 1 > $input_padding_right ? 32 : ( $input_padding_right + 20 ); ?>px;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field button.pp-rf-toggle-pw:focus {
	<?php if ( isset( $settings->input_focus_border_color ) && ! empty( $settings->input_focus_border_color ) ) { ?>
		color: #<?php echo $settings->input_focus_border_color; ?>;
		opacity: 1;
	<?php } ?>
}
<?php
// Input Padding
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'input_field_padding',
		'selector'     => $input_classes,
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'input_field_padding_top',
			'padding-right'  => 'input_field_padding_right',
			'padding-bottom' => 'input_field_padding_bottom',
			'padding-left'   => 'input_field_padding_left',
		),
	)
);
// Input Margin
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'input_field_margin',
		'selector'     => $input_classes,
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'input_field_margin_top',
			'margin-bottom' => 'input_field_margin_bottom',
		),
	)
);
// Input Border - Settings
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'input_border',
		'selector'     => $input_classes,
	)
);
// Input Typography
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'input_typography',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-field input,
							.fl-node-$id .pp-rf-wrap .pp-rf-field select,
							.fl-node-$id .pp-rf-wrap .pp-rf-field textarea",
	)
);
?>

<?php
/*************************************
 * Input placeholder
*************************************/
?>
<?php if ( $settings->placeholder_color && 'yes' === $settings->show_placeholder ) { ?>
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input::-webkit-input-placeholder {
	color: <?php echo pp_get_color_value( $settings->placeholder_color ); ?>;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input:-moz-placeholder {
	color: <?php echo pp_get_color_value( $settings->placeholder_color ); ?>;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input::-moz-placeholder {
	color: <?php echo pp_get_color_value( $settings->placeholder_color ); ?>;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input:-ms-input-placeholder {
	color: <?php echo pp_get_color_value( $settings->placeholder_color ); ?>;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field textarea::-webkit-input-placeholder {
	color: <?php echo pp_get_color_value( $settings->placeholder_color ); ?>;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field textarea:-moz-placeholder {
	color: <?php echo pp_get_color_value( $settings->placeholder_color ); ?>;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field textarea::-moz-placeholder {
	color: <?php echo pp_get_color_value( $settings->placeholder_color ); ?>;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field textarea:-ms-input-placeholder {
	color: <?php echo pp_get_color_value( $settings->placeholder_color ); ?>;
}
<?php } ?>

<?php if ( 'no' === $settings->show_placeholder ) { ?>
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input::-webkit-input-placeholder {
	color: transparent;
	opacity: 0;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input:-moz-placeholder {
	color: transparent;
	opacity: 0;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input::-moz-placeholder {
	color: transparent;
	opacity: 0;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input:-ms-input-placeholder {
	color: transparent;
	opacity: 0;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field textarea::-webkit-input-placeholder {
	color: transparent;
	opacity: 0;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field textarea:-moz-placeholder {
	color: transparent;
	opacity: 0;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field textarea::-moz-placeholder {
	color: transparent;
	opacity: 0;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field textarea:-ms-input-placeholder {
	color: transparent;
	opacity: 0;
}
<?php } ?>

<?php
/*************************************
 * Radio & Checkbox
*************************************/
?>
<?php
if ( isset( $settings->radio_cb_custom_style ) && 'yes' == $settings->radio_cb_custom_style ) {

// Radio & Checkbox - Padding
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'radio_cb_padding',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-field input[type='radio'], .fl-node-$id .pp-rf-wrap .pp-rf-field input[type='checkbox']",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'radio_cb_padding_top',
			'padding-right'  => 'radio_cb_padding_right',
			'padding-bottom' => 'radio_cb_padding_bottom',
			'padding-left'   => 'radio_cb_padding_left',
		),
	)
);
// Radio & Checkbox - Border
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'radio_cb_border',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-field input[type='radio'], .fl-node-$id .pp-rf-wrap .pp-rf-field input[type='checkbox']",
	)
);
?>

.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input[type="radio"],
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input[type="checkbox"] {
	-webkit-appearance: none;
	outline: none;
	<?php if ( ! empty( $settings->radio_cb_size ) ) { ?>
	width: <?php echo $settings->radio_cb_size; ?>px;
	height: <?php echo $settings->radio_cb_size; ?>px;
	<?php } ?>
	<?php if ( ! empty( $settings->radio_cb_color ) ) { ?>
	background: <?php echo pp_get_color_value( $settings->radio_cb_color ); ?>;
	background-color: <?php echo pp_get_color_value( $settings->radio_cb_color ); ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input[type="radio"]:checked,
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input[type="checkbox"]:checked {
	<?php if ( ! empty( $settings->radio_cb_checked_color ) ) { ?>
	background: <?php echo pp_get_color_value( $settings->radio_cb_checked_color ); ?>;
	background-color: <?php echo pp_get_color_value( $settings->radio_cb_checked_color ); ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field[data-field-type="radio"] .pp-field-option,
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field[data-field-type="checkbox"] .pp-field-option {
	display: flex;
	align-items: center;
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field[data-field-type="radio"] .pp-field-option .pp-rf-field-label,
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field[data-field-type="checkbox"] .pp-field-option .pp-rf-field-label {
	margin-left: 8px;
	margin-bottom: 0;
}
<?php } // End if(). ?>

<?php
/*************************************
 * Label
*************************************/
?>
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field:not([data-field-type="consent"]) .pp-rf-field-label {
	<?php if ( 'hide' === $settings->display_labels ) { ?>
		display: none;
	<?php } ?>
	<?php if ( ! empty( $settings->label_color ) ) { ?>
		color: <?php echo pp_get_color_value( $settings->label_color ); ?>;
	<?php } ?>
	<?php if ( isset( $settings->label_spacing ) && $settings->label_spacing >= 0 ) { ?>
		padding-bottom: <?php echo $settings->label_spacing; ?>px;
	<?php } ?>
}
<?php
// Label Typography
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'label_typography',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-field .pp-rf-field-label",
	)
);
?>
<?php if ( 'hide' === $settings->required_mask ) { ?>
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field .pp-rf-field-label .pp-required-mask {
		display: none;
	}
<?php } ?>

<?php
// Success message Padding
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'message_padding',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-after-submit-action.pp-success-custom-message,
							.fl-node-$id .pp-rf-wrap .pp-rf-success-none",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'message_padding_top',
			'padding-right'  => 'message_padding_right',
			'padding-bottom' => 'message_padding_bottom',
			'padding-left'   => 'message_padding_left',
		),
	)
);
// Success message Border - Settings
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'message_border_group',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-after-submit-action.pp-success-custom-message,
							.fl-node-$id .pp-rf-wrap .pp-rf-success-none",
	)
);
// Success message Typography
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'message_typography',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-success-msg,
							.fl-node-$id .pp-rf-wrap .pp-rf-success-none",
	)
);
?>
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-success-msg,
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-success-none {
	<?php if ( ! empty( $settings->message_color ) ) { ?>
	color: <?php echo pp_get_color_value( $settings->message_color ); ?>;
	<?php } ?>
}
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-after-submit-action.pp-success-custom-message,
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-success-none {
	<?php if ( ! empty( $settings->message_bg_color ) ) { ?>
	background-color: <?php echo pp_get_color_value( $settings->message_bg_color ); ?>;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-error,
.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-failed-error,
.fl-node-<?php echo $id; ?> .pp-rf-register-error {
	<?php if ( ! empty( $settings->error_color ) ) { ?>
	color: <?php echo pp_get_color_value( $settings->error_color ); ?>;
	<?php } ?>
	<?php if ( ! empty( $settings->error_background_color ) ) { ?>
	background-color: <?php echo pp_get_color_value( $settings->error_background_color ); ?>;
	<?php } ?>
}
<?php
// Success message Padding
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'error_padding',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-error,
							.fl-node-$id .pp-rf-wrap .pp-rf-failed-error,
							.fl-node-$id .pp-rf-register-error",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'error_padding_top',
			'padding-right'  => 'error_padding_right',
			'padding-bottom' => 'error_padding_bottom',
			'padding-left'   => 'error_padding_left',
		),
	)
);
// Success message Border - Settings
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'error_border_group',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-error,
							.fl-node-$id .pp-rf-wrap .pp-rf-failed-error,
							.fl-node-$id .pp-rf-register-error",
	)
);
// Error message Typography
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'error_typography',
		'selector'     => ".fl-node-$id .pp-rf-wrap .pp-rf-error,
							.fl-node-$id .pp-rf-wrap .pp-rf-failed-error,
							.fl-node-$id .pp-rf-register-error",
	)
);
?>
@media only screen and ( max-width: <?php echo $global_settings->medium_breakpoint; ?>px ) {
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap {
		justify-content: <?php echo $settings->button_alignment_medium; ?>;
	}
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button {
		<?php if ( 'auto' === $settings->button_width ) { ?>
			width: auto;
		<?php } elseif ( 'full' === $settings->button_width ) { ?>
			width: 100%;
		<?php } else { ?>
			width: <?php echo isset( $settings->button_custom_width_medium ) && ! empty( $settings->button_custom_width_medium ) ? $settings->button_custom_width_medium . $settings->button_custom_width_unit : ''; ?>;
		<?php } ?>
	}
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button-icon {
		<?php if ( isset( $settings->button_icon_size_medium ) && ! empty( $settings->button_icon_size_medium ) ) { ?>
			font-size: <?php echo $settings->button_icon_size_medium; ?>px;
		<?php } ?>
	}
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button-icon.pp-button-icon-after {
		<?php if ( isset( $settings->button_icon_spacing_medium ) && ! empty( $settings->button_icon_spacing_medium ) ) { ?>
			margin-left: <?php echo $settings->button_icon_spacing_medium; ?>px;
		<?php } ?>
	}
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button-icon.pp-button-icon-before {
		<?php if ( isset( $settings->button_icon_spacing_medium ) && ! empty( $settings->button_icon_spacing_medium ) ) { ?>
			margin-right: <?php echo $settings->button_icon_spacing_medium; ?>px;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input,
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field select {
		height: <?php echo $settings->input_field_height_medium; ?>px;
	}

	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input[type="radio"],
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input[type="checkbox"] {
		-webkit-appearance: none;
		width: <?php echo $settings->radio_cb_size_medium; ?>px !important;
		height: <?php echo $settings->radio_cb_size_medium; ?>px !important;
		outline: none;
		display: inline-block;
	}

}

@media only screen and ( max-width: <?php echo $global_settings->responsive_breakpoint; ?>px ) {
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap {
		justify-content: <?php echo $settings->button_alignment_responsive; ?>;
	}
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button {
		<?php if ( 'auto' === $settings->button_width ) { ?>
			width: auto;
		<?php } elseif ( 'full' === $settings->button_width ) { ?>
			width: 100%;
		<?php } else { ?>
			width: <?php echo isset( $settings->button_custom_width_responsive ) && ! empty( $settings->button_custom_width_responsive ) ? $settings->button_custom_width_responsive . $settings->button_custom_width_unit : ''; ?>;
		<?php } ?>
	}
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button-icon {
		<?php if ( isset( $settings->button_icon_size_responsive ) && ! empty( $settings->button_icon_size_responsive ) ) { ?>
			font-size: <?php echo $settings->button_icon_size_responsive; ?>px;
		<?php } ?>
	}
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button-icon.pp-button-icon-after {
		<?php if ( isset( $settings->button_icon_spacing_responsive ) && ! empty( $settings->button_icon_spacing_responsive ) ) { ?>
			margin-left: <?php echo $settings->button_icon_spacing_responsive; ?>px;
		<?php } ?>
	}
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-button-wrap .pp-button-icon.pp-button-icon-before {
		<?php if ( isset( $settings->button_icon_spacing_responsive ) && ! empty( $settings->button_icon_spacing_responsive ) ) { ?>
			margin-right: <?php echo $settings->button_icon_spacing_responsive; ?>px;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input,
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field select {
		height: <?php echo $settings->input_field_height_responsive; ?>px;
	}

	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input[type="radio"],
	.fl-node-<?php echo $id; ?> .pp-rf-wrap .pp-rf-field input[type="checkbox"] {
		-webkit-appearance: none;
		width: <?php echo $settings->radio_cb_size_responsive; ?>px !important;
		height: <?php echo $settings->radio_cb_size_responsive; ?>px !important;
		outline: none;
		display: inline-block;
	}

}

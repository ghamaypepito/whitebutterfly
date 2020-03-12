<?php

class WPML_PP_Registration_Form extends WPML_Beaver_Builder_Module_With_Items {

	public function &get_items( $settings ) {
		return $settings->form_fields;
	}

	public function get_fields() {
		return array( 'field_label', 'placeholder', 'default_value', 'static_text', 'field_options', 'rows', 'min_value', 'max_value', 'css_class', 'validation_msg' );
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'field_label':
				return esc_html__( 'Registration Form - Label', 'bb-powerpack' );

			case 'placeholder':
				return esc_html__( 'Registration Form - Placeholder', 'bb-powerpack' );

			case 'default_value':
				return esc_html__( 'Registration Form - Default Value', 'bb-powerpack' );

			case 'static_text':
				return esc_html__( 'Registration Form - Static Text', 'bb-powerpack' );

			case 'field_options':
				return esc_html__( 'Registration Form - Options', 'bb-powerpack' );

			case 'rows':
				return esc_html__( 'Registration Form - Rows', 'bb-powerpack' );

			case 'min_value':
				return esc_html__( 'Registration Form - Minimum Value', 'bb-powerpack' );

			case 'max_value':
				return esc_html__( 'Registration Form - Maximum Value', 'bb-powerpack' );

			case 'css_class':
				return esc_html__( 'Registration Form - CSS Class', 'bb-powerpack' );

			case 'validation_msg':
				return esc_html__( 'Registration Form - Validation Message', 'bb-powerpack' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'field_label':
			case 'placeholder':
			case 'default_value':
			case 'rows':
			case 'min_value':
			case 'max_value':
			case 'css_class':
			case 'validation_msg':
				return 'LINE';

			case 'static_text':
				return 'VISUAL';

			case 'field_options':
				return 'AREA';

			default:
				return '';
		}
	}

}

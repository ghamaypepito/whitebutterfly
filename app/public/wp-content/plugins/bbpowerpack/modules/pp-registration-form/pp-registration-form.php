<?php
/**
 * PowerPack Registration Form module.
 * 
 * @since 2.7.10
 */
class PPRegistrationFormModule extends FLBuilderModule {
	/**
	 * Holds directory path of fields.
	 * 
	 * @since 2.7.10
	 * @var string $fields_dir
	 */
	public $fields_dir           = '';

	/**
	 * Holds reCAPTCHA Site Key.
	 *
	 * @since 2.7.10
	 * @var string $recaptcha_site_key
	 */
	public $recaptcha_site_key   = '';

	/**
	 * Holds reCAPTCHA Secret Key.
	 *
	 * @since 2.7.10
	 * @var string $recaptcha_secret_key
	 */
	public $recaptcha_secret_key = '';

	/**
	 * Holds reCAPTCHA V3 Site Key.
	 * 
	 * @since 2.7.10
	 * @var string $recaptcha_site_key
	 */
	public $recaptcha_v3_site_key   = '';

	/**
	 * Holds reCAPTCHA V3 Secret Key.
	 * 
	 * @since 2.7.10
	 * @var string $recaptcha_secret_key
	 */
	public $recaptcha_v3_secret_key = '';

	/**
	 * Holds site information like admin email, site title.
	 *
	 * @since 2.7.10
	 * @var array $site_info
	 */
	public static $site_info = array();

	/**
	 * Holds minimum length of password for the password field.
	 *
	 * @since 2.7.10
	 * @var int $password_length
	 */
	public $password_length = 8;

	/**
	 * Initialize the module class.
	 *
	 * @since 2.7.10
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'          => __( 'Registration Form', 'bb-powerpack' ),
				'description'   => __( 'A module for Registration Form.', 'bb-powerpack' ),
				'group'         => pp_get_modules_group(),
				'category'      => pp_get_modules_cat( 'creative' ),
				'dir'           => BB_POWERPACK_DIR . 'modules/pp-registration-form/',
				'url'           => BB_POWERPACK_URL . 'modules/pp-registration-form/',
				'editor_export' => true, // Defaults to true and can be omitted.
				'enabled'       => true, // Defaults to true and can be omitted.
			)
		);

		$this->init_recaptcha_keys();

		// Fields directory path.
		$this->fields_dir           = $this->dir . 'fields/';
	}

	/**
	 * Get reCAPTCHA keys from setting options.
	 *
	 * @since 2.7.10
	 *
	 * @return void
	 */
	public function init_recaptcha_keys() {
		// Get reCAPTCHA Site Key from PP admin settings.
		$this->recaptcha_site_key   = BB_PowerPack_Admin_Settings::get_option( 'bb_powerpack_recaptcha_site_key' );
		// Get reCAPTCHA Secret Key from PP admin settings.
		$this->recaptcha_secret_key = BB_PowerPack_Admin_Settings::get_option( 'bb_powerpack_recaptcha_secret_key' );
		// Get reCAPTCHA V3 Site Key from PP admin settings.
		$this->recaptcha_v3_site_key   = BB_PowerPack_Admin_Settings::get_option( 'bb_powerpack_recaptcha_v3_site_key' );
		// Get reCAPTCHA V3 Secret Key from PP admin settings.
		$this->recaptcha_v3_secret_key = BB_PowerPack_Admin_Settings::get_option( 'bb_powerpack_recaptcha_v3_secret_key' );
	}

	/**
	 * Enqueue required scripts.
	 *
	 * @since 2.7.10
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$is_builder_active = FLBuilderModel::is_builder_active();

		// Password Strength Meter.
		if ( isset( $this->settings->enable_pws_meter ) && 'yes' === $this->settings->enable_pws_meter ) {
			$this->add_js( 'password-strength-meter' );
		}

		// reCAPTCHA.
		if ( ! $is_builder_active ) {
			if ( ! isset( $this->settings->enable_recaptcha ) || 'no' == $this->settings->enable_recaptcha ) {
				return;
			}
		}

		if ( ! empty( $this->recaptcha_site_key ) || ! empty( $this->recaptcha_v3_site_key ) ) {

			$site_lang = substr( get_locale(), 0, 2 );

			$this->add_js(
				'g-recaptcha',
				'https://www.google.com/recaptcha/api.js?onload=onLoadPPReCaptcha&render=explicit&hl=' . $site_lang,
				array(),
				'2.0',
				true
			);
		}
	}

	/**
	 * Add attributes to the enqueued `g-recaptcha` script.
	 * 
	 * @since 2.7.10
	 *
	 * @param string $tag    Script tag.
	 * @param string $handle Registered script handle.
	 * @return string
	 */
	public function add_async_attribute( $tag, $handle ) {
		if ( ( 'g-recaptcha' !== $handle ) || ( 'g-recaptcha' === $handle && strpos( $tag, 'g-recaptcha-api' ) !== false ) ) {
			return $tag;
		}

		return str_replace( ' src', ' id="g-recaptcha-api" async="async" defer="defer" src', $tag );
	}

	/**
	 * Retrieve WP user roles.
	 *
	 * @since 2.7.10
	 *
	 * @global object $wp_roles
	 * @return array
	 */
	public static function get_user_roles() {
		global $wp_roles;

		$_wp_roles = $wp_roles;

		if ( ! isset( $wp_roles ) || empty( $_wp_roles ) ) {
			$_wp_roles = get_editable_roles();
		}

		$roles      = isset( $_wp_roles->roles ) ? $_wp_roles->roles : array();
		$user_roles = array(
			''	=> __( 'Default', 'bb-powerpack' ),
		);

		foreach ( $roles as $role_key => $role ) {
			$user_roles[ $role_key ] = $role['name'];
		}

		/**
		 * Filters the user roles.
		 *
		 * @since 2.7.10 
		 * @param array $user 	An array of user roles.
		 */
		return apply_filters( 'pp_rf_user_roles', $user_roles );
	}

	/**
	 * Get HTML attributes for form element.
	 *
	 * @since 2.7.10
	 *
	 * @param string 	$instance_id	Unique module ID.
	 * @param array 	$custom_attrs 	An array of custom attributes.
	 * @return string					String of attributes.
	 */
	public function get_form_attrs( $instance_id, $custom_attrs = array() ) {
		$attrs = array(
			'id'	=> 'pp-rf-' . $instance_id,
			'class' => 'pp-registration-form',
			'method' => 'post',
			'data-nonce' => wp_create_nonce( 'pp-registration-nonce' ),
		);

		$attrs = array_merge( $attrs, $custom_attrs );

		$attrs_str = '';

		foreach ( $attrs as $key => $value ) {
			$attrs_str .= ' ' . $key . '="' . $value . '"';
		}

		return trim( $attrs_str );
	}

	/**
	 * Build data for form fields.
	 *
	 * @since 2.7.10
	 *
	 * @param string $instance_id	Unique module ID.
	 * @param string $settings		Module settings.
	 * @return array				An array of fields data.
	 */
	public function get_form_fields( $instance_id, $settings = '' ) {
		$settings = empty( $settings ) ? $this->settings : $settings;
		$form_fields = $settings->form_fields;
		$fields = array();
		$count = 0;

		foreach ( $form_fields as $field ) {
			if ( ! is_object( $field ) ) {
				continue;
			}

			$field->id = 'field-' . $instance_id . '-' . $count;
			$field->name = $this->get_field_name( $field, $count );

			if ( 'user_pass' == $field->field_type && isset( $field->min_pass_length ) ) {
				$this->password_length = ! empty( absint( $field->min_pass_length ) ) ? absint( $field->min_pass_length ) : 8;
			} else {
				unset( $field->min_pass_length );
				if ( isset( $field->password_toggle ) ) {
					unset( $field->password_toggle );
				}
			}

			$fields[ $field->name ] = $field;

			$count++;
		}

		/**
		 * Hook to filter form fields.
		 *
		 * @since 2.7.10
		 *
		 * @param string $fields		An array of form fields.
		 * @param object $settings		Module settings.
		 * @param string $instance_id 	Unique module ID.
		 */
		$fields = apply_filters( 'pp_rf_fields', $fields, $settings, $instance_id );

		$filtered_fields = array();

		foreach ( $fields as $field ) {
			if ( is_array( $field ) ) {
				$field = (object) $field;
			}
			
			if ( ! isset( $field->id ) || empty( $field->id ) ) {
				$field->id = 'field-' . $instance_id . '-' . $count;
			}
			if ( ! isset( $field->name ) || empty( $field->name ) ) {
				$field->name = $this->get_field_name( $field, $count );
			}
			if ( ! isset( $field->col_width ) ) {
				$field->col_width = '100';
			}
			if ( ! isset( $field->field_type ) ) {
				if ( isset( $field->type ) ) {
					$field->field_type = sanitize_text_field( $field->type );
					unset( $field->type );
				} else {
					$field->field_type = 'text';
				}
			}
			if ( ! isset( $field->field_label ) && isset( $field->label ) ) {
				$field->field_label = sanitize_text_field( $field->label );
				unset( $field->label );
			}
			if ( ! isset( $field->required ) || ( is_bool( $field->required ) && ! $field->required ) ) {
				$field->required = 'no';
			}
			if ( ! isset( $field->placeholder ) ) {
				$field->placeholder = '';
			}
			if ( ! isset( $field->default_value ) ) {
				$field->default_value = '';
			}

			$filtered_fields[ $field->name ] = $field;

			$count++;
		}

		return $filtered_fields;
	}

	/**
	 * Get the name of input for HTML name attribute.
	 *
	 * @since 2.7.10
	 *
	 * @param object 	$field	Field data.
	 * @param null|int 	$count	Optional. Index of array iteration in loop.
	 * @return string			Name for the field input HTML attribute.
	 */
	public function get_field_name( $field, $count = null ) {
		$field_name = 'pp_fields[field_' . $count . ']';

		switch ( $field->field_type ) {
			case 'user_login':
			case 'first_name':
			case 'last_name':
			case 'user_email':
			case 'user_pass':
			case 'confirm_user_pass':
			case 'user_url':
			case 'consent':
				$field_name = $field->field_type;
			break;
			case 'static_text':
				$field_name = $field->field_type . '-' . $count;
			break;
			default:
			break;
		}

		return $field_name;
	}

	/**
	 * Get HTML attributes for the field wrapper.
	 *
	 * @since 2.7.10
	 *
	 * @param Object $field	Field data.
	 * @return string 		String of HTML attributes.
	 */
	public function get_field_wrap_attrs( $field ) {
		$field_wrap_class = array(
			'pp-rf-field',
			'pp-rf-col-' . $field->col_width,
		);
		if ( 'yes' === $field->required ) {
			$field_wrap_class[] = 'pp-rf-field-required';
		} else {
			if ( in_array( $field->field_type, array( 'user_email', 'user_pass', 'confirm_user_pass' ) ) ) {
				$field_wrap_class[] = 'pp-rf-field-required';
			}
		}

		if ( 'user_pass' == $field->field_type ) {
			if ( isset( $field->password_toggle ) && 'show' === $field->password_toggle ) {
				$field_wrap_class[] = 'pp-rf-field-pw-toggle';
			}
		}

		if ( ! empty( $field->css_class ) ) {
			$field_wrap_class[] = $field->css_class;
		}

		$attrs = array(
			'class'	=> implode( ' ', $field_wrap_class ),
			'data-field-type' => $field->field_type,
		);

		$attrs_str = '';

		foreach ( $attrs as $key => $value ) {
			$attrs_str .= ' ' . $key . '="' . $value . '"';
		}

		return trim( $attrs_str );
	}

	/**
	 * Renders the label HTML for field.
	 *
	 * @since 2.7.10
	 *
	 * @param object $field	Field data.
	 * @return void
	 */
	public function render_field_label( $field ) {
		if ( ! empty( $field->field_label ) && ! in_array( $field->field_type, array( 'hidden', 'consent', 'static_text' ) ) ) { ?>
			<label class="pp-rf-field-label" for="<?php echo $field->id; ?>">
				<?php echo $field->field_label; ?>
				<?php if ( 'yes' === $field->required ) { ?>
					<span class="pp-required-mask">*</span>
				<?php } ?>
			</label>
		<?php }
	}

	/**
	 * Renders the control HTML for field.
	 *
	 * @since 2.7.10
	 *
	 * @param object $field	Field data.
	 * @return void
	 */
	public function render_field_control( $field ) {
		$field_id = $field->id;
		$field_name = $field->name;

		switch ( $field->field_type ) {
			case 'user_login':
			case 'first_name':
			case 'last_name':
				include $this->fields_dir . 'text.php';
			break;
			case 'user_email':
				include $this->fields_dir . 'email.php';
			break;
			case 'user_pass':
			case 'confirm_user_pass':
				include $this->fields_dir . 'password.php';
			break;
			case 'user_url':
				include $this->fields_dir . 'url.php';
			break;
			case 'consent':
				include $this->fields_dir . 'consent.php';
			break;
			case 'static_text':
				include $this->fields_dir . 'static-text.php';
			break;
			default:
				if ( file_exists( $this->fields_dir . $field->field_type . '.php' ) ) {
					include $this->fields_dir . $field->field_type . '.php';	
				}
			break;
		}
	}

	/**
	 * Renders the validation message HTML for field.
	 *
	 * @since 2.7.10
	 *
	 * @param object $field	Object of field data.
	 * @return void
	 */
	public function render_validation_msg( $field ) {
		if ( in_array( $field->field_type, array( 'hidden', 'static_text' ) ) ) {
			return;
		}
		if ( ! empty( $field->validation_msg ) ) { ?>
			<span class="pp-rf-error pp-rf-error-custom"><?php echo $field->validation_msg; ?></span>
		<?php } else { ?>
			<span class="pp-rf-error"><?php echo $this->get_custom_messages( 'field_required' ); ?></span>
		<?php }
	}

	/**
	 * Renders reCAPTCHA field.
	 *
	 * @since 2.7.10
	 *
	 * @param string $instance_id	Unique module ID.
	 * @return void
	 */
	public function render_recaptcha_field( $instance_id ) {
		?>
		<div class="pp-rf-field pp-rf-field-required" data-field-type="recaptcha">
			<?php
			$id = $instance_id;
			$settings = $this->settings;
			$recaptcha_site_key = 'invisible_v3' == $settings->recaptcha_validate_type ? $this->recaptcha_v3_site_key : $this->recaptcha_site_key;
			$recaptcha_validate_type = 'invisible_v3' == $settings->recaptcha_validate_type ? 'invisible' : $settings->recaptcha_validate_type;
			$recaptcha_theme = $settings->recaptcha_theme;
			include $this->fields_dir . 'recaptcha.php';
			?>
			<span class="pp-rf-error"><?php _e( 'Please check the captcha to verify you are not a robot.', 'bb-powerpack' ); ?></span>
		</div>
		<?php
	}

	/**
	 * Renders form button element.
	 *
	 * @since 2.7.10
	 *
	 * @return void
	 */
	public function render_button() {
		$settings = $this->settings;
		/**
		 * Hook to add custom logic before rendering button.
		 *
		 * @since 2.7.10
		 *
		 * @param object $settings 	Module settings.
		 */
		do_action( 'pp_rf_before_button_wrap', $settings );
		?>
		<div class="pp-rf-field pp-rf-button-wrap">
			<button type="submit" class="pp-button" role="button">
				<?php if ( ! empty( $settings->btn_icon ) && ( ! isset( $settings->btn_icon_position ) || 'before' === $settings->btn_icon_position ) ) : ?>
					<i class="pp-button-icon pp-button-icon-before <?php echo $settings->btn_icon; ?>"></i>
				<?php endif; ?>
				<?php if ( ! empty( $settings->btn_text ) ) : ?>
					<span class="pp-button-text"><?php echo $settings->btn_text; ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $settings->btn_icon ) && ( isset( $settings->btn_icon_position ) && 'after' === $settings->btn_icon_position ) ) : ?>
					<i class="pp-button-icon pp-button-icon-after <?php echo $settings->btn_icon; ?>"></i>
				<?php endif; ?>
			</button>
		</div>
		<?php
		/**
		 * Hook to add custom logic after rendering button.
		 *
		 * @since 2.7.10
		 *
		 * @param object $settings 	Module settings.
		 */
		do_action( 'pp_rf_after_button_wrap', $settings );
	}

	/**
	 * Custom messages for form.
	 *
	 * @since 2.7.10
	 *
	 * @param $type	Optional. Type of message.
	 * @return string|array
	 */
	public function get_custom_messages( $type = '' ) {
		$messages = array(
			'no_message'		=> __( 'Registration successful!', 'bb-powerpack' ),
			'on_fail'			=> __( 'An error occurred. Please try again.', 'bb-powerpack' ),
			'field_required' 	=> __( 'This field is required.', 'bb-powerpack' ),
			'already_registered' => __( 'You are already registered.', 'bb-powerpack' ),
		);

		/**
		 * Filters the array of messages.
		 * 
		 * @since 2.7.10
		 * 
		 * @param array $messages An array of messages to be printend in form.
		 */
		$filtered_msgs = apply_filters( 'pp_rf_custom_messages', $messages );

		if ( ! empty( $type ) ) {
			if ( isset( $filtered_msgs[ $type ] ) ) {
				return $filtered_msgs[ $type ];
			} else if ( isset( $messages[ $type ] ) ) {
				return $messages[ $type ];
			} else {
				return '';
			}
		}

		return $filtered_msgs;
	}

	/**
	 * Get site information.
	 *
	 * @since 2.7.10
	 *
	 * @param string|bool $prop	Optional. Property to retrieve.
	 * @return string|array
	 */
	public static function get_site_info( $prop = false ) {
		if ( empty( self::$site_info ) ) {
			self::$site_info = array(
				'site_url'		=> site_url(),
				'admin_email'	=> get_option( 'admin_email' ),
				'blogname'		=> wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
			);
		}

		if ( ! empty( $prop ) && isset( self::$site_info[ $prop ] ) ) {
			return self::$site_info[ $prop ];
		}

		return self::$site_info;
	}
}

/**
 * Form handler class.
 */
require_once 'form-handler.php';

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'PPRegistrationFormModule',
	array(
		'form_fields'     => array(
			'title'    => __( 'Form', 'bb-powerpack' ),
			'sections' => array(
				'form_fields' => array(
					'title'     => __( 'Fields', 'bb-powerpack' ),
					'collapsed' => false,
					'fields'    => array(
						'form_fields' => array(
							'type'         => 'form',
							'label'        => __( 'Field', 'bb-powerpack' ),
							'form'         => 'pp_registration_form_fields',
							'preview_text' => 'field_label',
							'multiple'     => true,
							'default'		=> array(
								array(
									'field_type'	=> 'user_login',
									'field_label'	=> __( 'Username', 'bb-powerpack' ),
									'placeholder'	=> __( 'Username', 'bb-powerpack' ),
								),
								array(
									'field_type'	=> 'user_email',
									'field_label'	=> __( 'Email', 'bb-powerpack' ),
									'placeholder'	=> __( 'Email', 'bb-powerpack' ),
									'required'		=> 'yes',
								),
								array(
									'field_type'	=> 'user_pass',
									'field_label'	=> __( 'Password', 'bb-powerpack' ),
									'placeholder'	=> __( 'Password', 'bb-powerpack' ),
									'required'		=> 'yes',
								),
							),
						),
					),
				),
				'btn_general' => array(
					'title'     => __( 'Button', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'btn_text'          => array(
							'type'    => 'text',
							'label'   => __( 'Text', 'bb-powerpack' ),
							'default' => __( 'Register', 'bb-powerpack' ),
						),
						'btn_icon'          => array(
							'type'        => 'icon',
							'label'       => __( 'Icon', 'bb-powerpack' ),
							'show_remove' => true,
						),
						'btn_icon_position' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Icon Position', 'bb-powerpack' ),
							'default' => 'after',
							'options' => array(
								'before' => __( 'Before Text', 'bb-powerpack' ),
								'after'  => __( 'After Text', 'bb-powerpack' ),
							),
						),
					),
				),
				'recaptcha_settings' => array(
					'title'       => 'reCAPTCHA',
					'description' => pp_get_recaptcha_desc(),
					'collapsed'	=> true,
					'fields'      => array(
						'enable_recaptcha'        => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Enable reCAPTCHA', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'recaptcha_validate_type', 'recaptcha_theme' ),
								),
							),
							'preview'	=> array(
								'type'		=> 'none',
							),
						),
						'recaptcha_validate_type' => array(
							'type'    => 'select',
							'label'   => __( 'Validate Type', 'bb-powerpack' ),
							'default' => 'normal',
							'options' => array(
								'normal'    => __( '"I\'m not a robot" checkbox (V2)', 'bb-powerpack' ),
								'invisible' => __( 'Invisible (V2)', 'bb-powerpack' ),
								'invisible_v3' => __( 'Invisible (V3)', 'bb-powerpack' ),
							),
							'help'    => __( 'Validate users with checkbox or in the background.<br />Note: Checkbox and Invisible types use seperate API keys.', 'bb-powerpack' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'recaptcha_theme'         => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Theme', 'bb-powerpack' ),
							'default' => 'light',
							'options' => array(
								'light' => __( 'Light', 'bb-powerpack' ),
								'dark'  => __( 'Dark', 'bb-powerpack' ),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
				'pws_meter' => array(
					'title'		=> __( 'Password Strength Meter', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'enable_pws_meter' => array(
							'type'		=> 'pp-switch',
							'label'		=> __( 'Enable', 'bb-powerpack' ),
							'default' 	=> 'no',
							'toggle'	=> array(
								'yes'		=> array(
									'fields'	=> array( 'short_pw_color', 'weak_pw_color', 'good_pw_color', 'strong_pw_color' ),
								),
							),
						),
						'short_pw_color'	=> array(
							'type'			=> 'color',
							'label'			=> __( '"Short" Status Color', 'bb-powerpack' ),
							'default' 		=> '',
							'show_reset' 	=> true,
							'preview'		=> array(
								'type'			=> 'css',
								'selector'		=> '.pp-rf-field .pp-rf-pw-status.short',
								'property'		=> 'color',
							),
						),
						'weak_pw_color'	=> array(
							'type'			=> 'color',
							'label'			=> __( '"Weak" Status Color', 'bb-powerpack' ),
							'default' 		=> '',
							'show_reset' 	=> true,
							'preview'		=> array(
								'type'			=> 'css',
								'selector'		=> '.pp-rf-field .pp-rf-pw-status.bad',
								'property'		=> 'color',
							),
						),
						'good_pw_color'	=> array(
							'type'			=> 'color',
							'label'			=> __( '"Medium" Status Color', 'bb-powerpack' ),
							'default' 		=> '',
							'show_reset' 	=> true,
							'preview'		=> array(
								'type'			=> 'css',
								'selector'		=> '.pp-rf-field .pp-rf-pw-status.good',
								'property'		=> 'color',
							),
						),
						'strong_pw_color'	=> array(
							'type'			=> 'color',
							'label'			=> __( '"Strong" Status Color', 'bb-powerpack' ),
							'default' 		=> '',
							'show_reset' 	=> true,
							'preview'		=> array(
								'type'			=> 'css',
								'selector'		=> '.pp-rf-field .pp-rf-pw-status.strong',
								'property'		=> 'color',
							),
						),
					),
				),
			),
		),
		'action'          => array(
			'title'       => __( 'Action', 'bb-powerpack' ),
			'description' => __( 'Add actions that will be performed after a successful registration (e.g. send an email notification). Choosing an action will add its setting below.', 'bb-powerpack' ),
			'sections'    => array(
				'action'          => array(
					'title'  => '',
					'fields' => array(
						'send_email' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Email Notification', 'bb-powerpack' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'sections' => array( 'action_email', 'action_admin_email' ),
								),
							),
							'preview'	=> array(
								'type'	=> 'none',
							),
						),
						'redirect'   => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Redirect', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'sections' => array( 'action_redirect' ),
								),
								'no'  => array(
									'sections' => array( 'success_message' ),
								),
							),
							'preview'	=> array(
								'type'	=> 'none',
							),
						),
						'user_role'  => array(
							'type'    	=> 'select',
							'label'   => __( 'New User Role', 'bb-powerpack' ),
							'default' => '',
							'options' => PPRegistrationFormModule::get_user_roles(),
							'preview'	=> array(
								'type'		=> 'none',
							),
						),
						'auto_login'	=> array(
							'type'		=> 'pp-switch',
							'label'		=> __( 'Auto Login after Registration?', 'bb-powerpack' ),
							'default'	=> 'yes',
							'preview'	=> array(
								'type'		=> 'none',
							),
						),
					),
				),
				'action_email'    => array(
					'title'     => __( 'Email Notification - User', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'email_subject'   => array(
							'type'        => 'text',
							'label'       => __( 'Email Subject', 'bb-powerpack' ),
							// translators: %s for Email Subject.
							'default'     => sprintf( __( 'New message from "%s"', 'bb-powerpack' ), PPRegistrationFormModule::get_site_info( 'blogname' ) ),
							'connections' => array( 'string' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'email_content'   => array(
							'type'    => 'textarea',
							'label'   => __( 'Email Content', 'bb-powerpack' ),
							'default' => sprintf( __( "Thanks for signing up for %s. Please find your details below.\n\n ---- \n{{all-fields}}", 'bb-powerpack' ), PPRegistrationFormModule::get_site_info( 'blogname' ) ),
							'rows'    => '4',
							'connections' => array( 'string', 'html' ),
							'help'    => __( 'By default, all form fields are sent via tag: {{all-fields}}. Want to customize sent fields? Copy the tag that appears inside the field and paste it here. Additionally, you can include blog/site name by using {{blogname}}, site URL {{site_url}}, admin email {{admin_email}}', 'bb-powerpack' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'email_from'      => array(
							'type'        => 'text',
							'label'       => __( 'From Email', 'bb-powerpack' ),
							'default'     => PPRegistrationFormModule::get_site_info( 'admin_email' ),
							'help'		  => __( 'Please make sure to use an email from the same domain or using an authorized SMTP service, otherwise email will not be delivered or landed up in junk.', 'bb-powerpack' ),
							'connections' => array( 'string' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'email_from_name' => array(
							'type'        => 'text',
							'label'       => __( 'From Name', 'bb-powerpack' ),
							'default'     => PPRegistrationFormModule::get_site_info( 'blogname' ),
							'connections' => array( 'string' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						// 'email_reply_to'  => array(
						// 	'type'        => 'text',
						// 	'label'       => __( 'Reply-To', 'bb-powerpack' ),
						// 	'default'     => get_option( 'admin_email' ),
						// 	'placeholder' => get_option( 'admin_email' ),
						// 	'connections' => array( 'string' ),
						// 	'preview'     => array(
						// 		'type' => 'none',
						// 	),
						// ),
					),
				),
				'action_admin_email'	=> array(
					'title'			=> __( 'Email Notification - Admin' ),
					'collapsed' => true,
					'fields'		=> array(
						'enable_admin_email'	=> array(
							'type'		=> 'pp-switch',
							'label'		=> __( 'Enable Admin Notification', 'bb-powerpack' ),
							'default'	=> 'yes',
							'toggle'	=> array(
								'yes'		=> array(
									'fields'	=> array( 'admin_email_subject', 'admin_email_content', 'email_metadata' ),
								),
							),
							'preview'	=> array(
								'type'		=> 'none',
							),
						),
						'admin_email_to'        => array(
							'type'        => 'text',
							'label'       => __( 'Send To Email', 'bb-powerpack' ),
							'default'     => PPRegistrationFormModule::get_site_info( 'admin_email' ),
							'placeholder' => PPRegistrationFormModule::get_site_info( 'admin_email' ),
							'help'        => __( 'Notfication will be sent to this email. Defaults to the admin email.', 'bb-powerpack' ),
							'connections' => array( 'string' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'admin_email_subject'   => array(
							'type'        => 'text',
							'label'       => __( 'Email Subject', 'bb-powerpack' ),
							// translators: %s for Email Subject.
							'default'     => __( 'New User Registration', 'bb-powerpack' ),
							'connections' => array( 'string' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'admin_email_content'   => array(
							'type'    => 'textarea',
							'label'   => __( 'Email Content', 'bb-powerpack' ),
							'default' => sprintf( __( "The following user is registered on the site: \n\n ---- \n %s", 'bb-powerpack' ), '{{all-fields}}' ),
							'rows'    => '4',
							'connections' => array( 'string', 'html' ),
							'help'    => __( 'By default, all form fields are sent via tag: {{all-fields}}. Want to customize sent fields? Copy the tag that appears inside the field and paste it here. Additionally, you can include site name by using {{site_name}}, site URL {{site_url}}, admin email {{admin_email}}', 'bb-powerpack' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'email_metadata'  => array(
							'type'         => 'select',
							'label'        => __( 'Meta Data', 'bb-powerpack' ),
							'default'      => '',
							'options'      => array(
								'date'       => __( 'Date', 'bb-powerpack' ),
								'time'       => __( 'Time', 'bb-powerpack' ),
								'page_url'   => __( 'Page URL', 'bb-powerpack' ),
								'user_agent' => __( 'User Agent', 'bb-powerpack' ),
								'remote_ip'  => __( 'Remote IP', 'bb-powerpack' ),
							),
							'multi-select' => true,
							'help'         => __( 'Press <b>shift + click</b> to select multiple.', 'bb-powerpack' ),
							'preview'      => array(
								'type' => 'none',
							),
						),
					),
				),
				'action_redirect' => array(
					'title'     => __( 'Redirect', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'redirect_url' => array(
							'type'        => 'link',
							'label'       => __( 'Redirect To', 'bb-powerpack' ),
							'connections' => array( 'url' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),
				'success_message' => array(
					'title'     => __( 'Success Message', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'show_success_message' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Show Message', 'bb-powerpack' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'success_message' ),
								),
							),
						),
						'success_message'      => array(
							'type'        => 'text',
							'label'       => __( 'Message', 'bb-powerpack' ),
							'default'     => __( 'New user is registered successfully!', 'bb-powerpack' ),
							'connections' => array( 'string', 'url' ),
						),
					),
				),
			),
		),
		'form_style'      => array(
			'title'    => __( 'Style', 'bb-powerpack' ),
			'sections' => array(
				'label_style'	=> array(
					'title'		=> __( 'Label', 'bb-powerpack' ),
					'fields'	=> array(
						'display_labels' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Show Label', 'bb-powerpack' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Yes', 'bb-powerpack' ),
								'hide' => __( 'No', 'bb-powerpack' ),
							),
							'toggle'	=> array(
								'show'		=> array(
									'fields'	=> array( 'label_spacing', 'required_mask' ),
								),
							),
						),
						'label_spacing'	=> array(
							'type'		=> 'unit',
							'label'		=> __( 'Spacing', 'bb-powerpack' ),
							'default'	=> '',
							'units'		=> array( 'px' ),
							'slider'	=> true,
							'preview'	=> array(
								'type'		=> 'css',
								'selector'	=> '.pp-rf-wrap .pp-rf-field .pp-rf-field-label',
								'property'	=> 'padding-bottom',
								'unit'		=> 'px',
							),
						),
						'label_color'      => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field .pp-rf-field-label',
								'property' => 'color',
							),
						),
						'required_mask'  => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Show Required Mask', 'bb-powerpack' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Yes', 'bb-powerpack' ),
								'hide' => __( 'No', 'bb-powerpack' ),
							),
						),
					),
				),
				'form_bg_setting'     => array(
					'title'     => __( 'Form Background', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'form_bg_type'   => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Background Type', 'bb-powerpack' ),
							'default' => 'color',
							'options' => array(
								'color' => __( 'Color', 'bb-powerpack' ),
								'image' => __( 'Image', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'color' => array(
									'fields' => array( 'form_bg_color' ),
								),
								'image' => array(
									'fields' => array( 'form_bg_image', 'form_bg_size', 'form_bg_repeat' ),
								),
							),
						),
						'form_bg_color'  => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap',
								'property' => 'background-color',
							),
						),
						'form_bg_image'  => array(
							'type'    => 'photo',
							'label'   => __( 'Background Image', 'bb-powerpack' ),
							'default' => '',
							'connections'	=> array( 'photo' ),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-form',
								'property' => 'background-image',
							),
						),
						'form_bg_size'   => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Background Size', 'bb-powerpack' ),
							'default' => 'cover',
							'options' => array(
								'contain' => __( 'Contain', 'bb-powerpack' ),
								'cover'   => __( 'Cover', 'bb-powerpack' ),
							),
						),
						'form_bg_repeat' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Background Repeat', 'bb-powerpack' ),
							'default' => 'no-repeat',
							'options' => array(
								'repeat-x'  => __( 'Repeat X', 'bb-powerpack' ),
								'repeat-y'  => __( 'Repeat Y', 'bb-powerpack' ),
								'no-repeat' => __( 'No Repeat', 'bb-powerpack' ),
							),
						),
					),
				),
				'form_border_setting' => array(
					'title'     => __( 'Form Structure', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'col_spacing'	=> array(
							'type'	=> 'unit',
							'label'	=> __( 'Columns Spacing', 'bb-powerpack' ),
							'default' => '10',
							'units'	=> array( 'px' ),
						),
						'rows_spacing'	=> array(
							'type'	=> 'unit',
							'label'	=> __( 'Rows Spacing', 'bb-powerpack' ),
							'default' => '10',
							'units'	=> array( 'px' ),
							'preview'	=> array(
								'type'		=> 'css',
								'selector'	=> '.pp-rf-wrap .pp-rf-field',
								'property'	=> 'margin-bottom',
								'unit'		=> 'px',
							),
						),
						'form_border'  => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap',
								'property' => 'border',
							),
						),
						'form_padding' => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'default'    => '',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),
					),
				),
			),
		),
		'input_style'     => array(
			'title'    => __( 'Inputs', 'bb-powerpack' ),
			'sections' => array(
				'input_colors_setting' => array(
					'title'  => __( 'Colors', 'bb-powerpack' ),
					'fields' => array(
						'input_field_text_color' => array(
							'type'       => 'color',
							'label'      => __( 'Text Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field input, .pp-rf-wrap .pp-rf-field select, .pp-rf-wrap .pp-rf-field textarea',
								'property' => 'color',
							),
						),
						'input_field_bg_color'   => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field input, .pp-rf-wrap .pp-rf-field select, .pp-rf-wrap .pp-rf-field textarea',
								'property' => 'background-color',
							),
						),
					),
				),
				'input_general_style'  => array(
					'title'     => __( 'Structure', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'input_field_height'         => array(
							'type'       => 'unit',
							'label'      => __( 'Input Height', 'bb-powerpack' ),
							'default'    => '',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field input:not([type="checkbox"])',
								'property' => 'height',
								'unit'     => 'px',
							),
						),
						// 'input_textarea_height'      => array(
						// 	'type'       => 'unit',
						// 	'label'      => __( 'Textarea Height', 'bb-powerpack' ),
						// 	'default'    => '140',
						// 	'units'      => array( 'px' ),
						// 	'slider'     => true,
						// 	'responsive' => true,
						// 	'preview'    => array(
						// 		'type'     => 'css',
						// 		'selector' => '.pp-rf-wrap .pp-rf-field textarea',
						// 		'property' => 'height',
						// 		'unit'     => 'px',
						// 	),
						// ),
						'input_border'        => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field .pp-rf-control',
								'property' => 'border',
							),
						),
						'input_focus_border_color'	=> array(
							'type'		=> 'color',
							'label'		=> __( 'Focus Border Color', 'bb-powerpack' ),
							'default'	=> '',
							'show_reset'	=> true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field .pp-rf-control:focus',
								'property' => 'border-color',
							),
						),
						'input_error_border_color'	=> array(
							'type'		=> 'color',
							'label'		=> __( 'Error Border Color', 'bb-powerpack' ),
							'default'	=> '',
							'show_reset'	=> true,
							'preview'    => array(
								'type'     => 'none',
							),
						),
						'input_field_padding' => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'default'    => '',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field input:not([type="checkbox"]), .pp-rf-wrap .pp-rf-field textarea',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),
						'input_field_margin_top'  => array(
							'type'       => 'unit',
							'label'      => __( 'Margin Top', 'bb-powerpack' ),
							'default'    => '0',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field input:not([type="checkbox"]), .pp-rf-wrap .pp-rf-field textarea',
								'property' => 'margin-top',
								'unit'     => 'px',
							),
						),
						'input_field_margin_bottom'  => array(
							'type'       => 'unit',
							'label'      => __( 'Margin Bottom', 'bb-powerpack' ),
							'default'    => '0',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field input:not([type="checkbox"]), .pp-rf-wrap .pp-rf-field textarea',
								'property' => 'margin-bottom',
								'unit'     => 'px',
							),
						),
					),
				),
				'placeholder_style'    => array(
					'title'     => __( 'Placeholder', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'show_placeholder'  => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Show Placeholder', 'bb-powerpack' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'placeholder_color' ),
								),
							),
						),
						'placeholder_color' => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
						),
					),
				),
				'radio_cb_style'       => array(
					'title'     => __( 'Checkbox', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'radio_cb_custom_style'	=> array(
							'type'		=> 'pp-switch',
							'label'		=> __( 'Enable Custom Style', 'bb-powerpack' ),
							'default'	=> 'no',
							'toggle'	=> array(
								'yes'		=> array(
									'fields'	=> array( 'radio_cb_color', 'radio_cb_checked_color', 'radio_cb_size', 'radio_cb_border', 'radio_cb_padding' ),
								),
							),
						),
						'radio_cb_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field input[type="radio"], .pp-rf-wrap .pp-rf-field input[type="checkbox"]',
								'property' => 'background',
							),
						),
						'radio_cb_checked_color' => array(
							'type'       => 'color',
							'label'      => __( 'Checked Color', 'bb-powerpack' ),
							'default'    => 'cccccc',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field input[type="radio"]:checked, .pp-rf-wrap .pp-rf-field input[type="checkbox"]:checked',
								'property' => 'background',
							),
						),
						'radio_cb_size'          => array(
							'type'       => 'unit',
							'label'      => __( 'Size', 'bb-powerpack' ),
							'default'	 => 13,
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'rules'		=> array(
									array(
										'selector' => '.pp-rf-wrap .pp-rf-field input[type="radio"],.pp-rf-wrap .pp-rf-field input[type="checkbox"]',
										'property' => 'height',
										'unit'		=> 'px',
									),
									array(
										'selector' => '.pp-rf-wrap .pp-rf-field input[type="radio"],.pp-rf-wrap .pp-rf-field input[type="checkbox"]',
										'property' => 'width',
										'unit'		=> 'px',
									),
								),
							),
						),
						'radio_cb_border'        => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'default'	 => array(
								'style'		=> 'solid',
								'color'		=> 'ffffff',
								'width'		=> array(
									'top'		=> '2',
									'right'		=> '2',
									'bottom'	=> '2',
									'left'		=> '2',
								),
								'shadow'	=> array(
									'color'		=> 'cccccc',
									'horizontal' => '0',
									'vertical'	=> '0',
									'blur'		=> '0',
									'spread'	=> '1',
								),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field input[type="radio"],.pp-rf-wrap .pp-rf-field input[type="checkbox"]',
								'property' => 'border',
							),
						),
						'radio_cb_padding'       => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'default'    => '10',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field input[type="radio"],.pp-rf-wrap .pp-rf-field input[type="checkbox"]',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),
					),
				),
			),
		),
		'button_style'    => array(
			'title'    => __( 'Button', 'bb-powerpack' ),
			'sections' => array(
				'button_bg'         => array(
					'title'  => __( 'Colors', 'bb-powerpack' ),
					'fields' => array(
						'button_text_color'       => array(
							'type'        => 'color',
							'label'       => __( 'Text Color', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-button-wrap .pp-button',
								'property' => 'color',
							),
						),
						'button_hover_text_color' => array(
							'type'        => 'color',
							'label'       => __( 'Text Color Hover', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'button_bg_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Background Color', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-button-wrap .pp-button',
								'property' => 'background-color',
							),
						),
						'button_hover_bg_color'   => array(
							'type'        => 'color',
							'label'       => __( 'Background Color Hover', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),
				'button_border'     => array(
					'title'     => __( 'Border', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'button_border_group' => array(
							'type'       => 'border',
							'label'      => __( 'Border Style', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-button-wrap .pp-button',
							),
						),
						'button_border_hover' => array(
							'type'       => 'border',
							'label'      => __( 'Border Hover Style', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
					),
				),
				'button_settings'   => array(
					'title'     => __( 'Size & Alignment', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'button_width'        => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Full Width', 'bb-powerpack' ),
							'default' => 'auto',
							'options' => array(
								'auto'   => __( 'Auto', 'bb-powerpack' ),
								'full'   => __( 'Full', 'bb-powerpack' ),
								'custom' => __( 'Custom', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'auto'   => array(
									'fields' => array( 'button_alignment' ),
								),
								'custom' => array(
									'fields' => array( 'button_custom_width', 'button_alignment' ),
								),
							),
						),
						'button_custom_width' => array(
							'type'       => 'unit',
							'label'      => __( 'Custom Width', 'bb-powerpack' ),
							'default'    => '',
							'units'      => array( 'px', '%' ),
							'slider'     => true,
							'responsive' => true,
						),
						'button_alignment'    => array(
							'type'       => 'pp-switch',
							'label'      => __( 'Button Alignment', 'bb-powerpack' ),
							'default'    => 'flex-start',
							'responsive' => true,
							'options'    => array(
								'flex-start' => __( 'Left', 'bb-powerpack' ),
								'center'     => __( 'Center', 'bb-powerpack' ),
								'flex-end'   => __( 'Right', 'bb-powerpack' ),
							),
						),
						'button_padding'      => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-button-wrap .pp-button',
								'property' => 'padding',
								'unit'     => 'px',
							),
							'responsive' => true,
						),
						'button_margin'       => array(
							'type'       => 'dimension',
							'label'      => __( 'Margin', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-button-wrap .pp-button',
								'property' => 'margin',
								'unit'     => 'px',
							),
							'responsive' => true,
						),
					),
				),
				'button_icon_style' => array(
					'title'     => __( 'Icon', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'button_icon_color'       => array(
							'type'        => 'color',
							'label'       => __( 'Icon Color', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-button-wrap .pp-button-icon',
								'property' => 'color',
							),
						),
						'button_hover_icon_color' => array(
							'type'        => 'color',
							'label'       => __( 'Icon Color Hover', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'button_icon_size'        => array(
							'type'       => 'unit',
							'label'      => __( 'Size', 'bb-powerpack' ),
							'default'    => '15',
							'slider'     => true,
							'responsive' => true,
							'units'      => array( 'px' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-button-wrap .pp-button-icon',
								'property' => 'font-size',
							),
						),
						'button_icon_spacing'     => array(
							'type'       => 'unit',
							'label'      => __( 'Spacing', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'	=> array(
								'type'		=> 'css',
								'rules'		=> array(
									array(
										'selector'	=> '.pp-rf-wrap .pp-rf-button-wrap .pp-button-icon.pp-button-icon-after',
										'property'	=> 'margin-left',
										'unit'		=> 'px',
									),
									array(
										'selector'	=> '.pp-rf-wrap .pp-rf-button-wrap .pp-button-icon.pp-button-icon-before',
										'property'	=> 'margin-right',
										'unit'		=> 'px',
									),
								),
							),
						),
					),
				),
			),
		),
		'messages_style'  => array(
			'title'    => __( 'Messages', 'bb-powerpack' ),
			'sections' => array(
				'message_style' => array(
					'title'  => __( 'Success Message', 'bb-powerpack' ),
					'fields' => array(
						'message_bg_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'bb-powerpack' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
						'message_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Text Color', 'bb-powerpack' ),
							'show_reset' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
						'message_border_group' => array(
							'type'       => 'border',
							'label'      => __( 'Border Style', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
						'message_padding'      => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'slider'     => true,
							'units'      => array( 'px' ),
							'preview'    => array(
								'type' => 'none',
							),
							'responsive' => true,
						),
					),
				),
				'error_styling' => array(
					'title'     => __( 'Errors Message', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'error_color'            => array(
							'type'        => 'color',
							'label'       => __( 'Error Color', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'error_background_color' => array(
							'type'        => 'color',
							'label'       => __( 'Error Background Color', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'error_border_group'     => array(
							'type'       => 'border',
							'label'      => __( 'Border Style', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
						'error_padding'          => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'slider'     => true,
							'units'      => array( 'px' ),
							'preview'    => array(
								'type' => 'none',
							),
							'responsive' => true,
						),
					),
				),
			),
		),
		'form_typography' => array(
			'title'    => __( 'Typography', 'bb-powerpack' ),
			'sections' => array(
				// 'description_typography' => array(
				// 	'title'     => __( 'Description', 'bb-powerpack' ),
				// 	'collapsed' => true,
				// 	'fields'    => array(
				// 		'description_typography' => array(
				// 			'type'       => 'typography',
				// 			'label'      => __( 'Typography', 'bb-powerpack' ),
				// 			'responsive' => true,
				// 			'preview'    => array(
				// 				'type'     => 'css',
				// 				'selector' => '.pp-rf-wrap .pp-rf-description',
				// 			),
				// 		),
				// 	),
				// ),
				'label_typography'       => array(
					'title'     => __( 'Label', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'label_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field .pp-rf-field-label',
							),
						),
					),
				),
				'input_typography'       => array(
					'title'     => __( 'Input', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'input_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-field input:not([type="checkbox"]), .pp-rf-wrap .pp-rf-field select, .pp-rf-wrap .pp-rf-field textarea',
							),
						),
					),
				),
				'button_typography'      => array(
					'title'     => __( 'Button', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'button_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rf-wrap .pp-rf-button-wrap .pp-button',
							),
						),
					),
				),
				'message_typography'     => array(
					'title'     => __( 'Message', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'message_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Success Message Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
						'error_typography'   => array(
							'type'       => 'typography',
							'label'      => __( 'Error Message Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
					),
				),
			),
		),
	)
);
/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form(
	'pp_registration_form_fields',
	array(
		'title' => __( 'Add Field', 'bb-powerpack' ),
		'tabs'  => array(
			'general'           => array(
				'title'    => __( 'General', 'bb-powerpack' ),
				'sections' => array(
					'general' => array(
						'title'  => '',
						'fields' => array(
							'field_type'     => array(
								'type'    => 'select',
								'label'   => __( 'Type', 'bb-powerpack' ),
								'default' => '',
								'options' => array(
									''				=> __( 'Choose a field...', 'bb-powerpack' ),
									'user_login' 	=> __( 'Username', 'bb-powerpack' ),
									'user_pass' 	=> __( 'Password', 'bb-powerpack' ),
									'confirm_user_pass' => __( 'Confirm Password', 'bb-powerpack' ),
									'user_email'    => __( 'Email', 'bb-powerpack' ),
									'first_name'	=> __( 'First Name', 'bb-powerpack' ),
									'last_name'		=> __( 'Last Name', 'bb-powerpack' ),
									'user_url'      => __( 'Website', 'bb-powerpack' ),
									'consent'		=> __( 'Consent', 'bb-powerpack' ),
									'static_text'	=> __( 'Static Text', 'bb-powerpack' ),
									// 'text'     => __( 'Text', 'bb-powerpack' ),
									// 'textarea' => __( 'Textarea', 'bb-powerpack' ),
									// 'select'   => __( 'Select', 'bb-powerpack' ),
									// 'radio'    => __( 'Radio', 'bb-powerpack' ),
									// 'checkbox' => __( 'Checkbox', 'bb-powerpack' ),
									// 'number'   => __( 'Number', 'bb-powerpack' ),
									// 'tel'      => __( 'Tel', 'bb-powerpack' ),
									// 'hidden'   => __( 'Hidden', 'bb-powerpack' ),
								),
								'toggle'  => array(
									'user_login' => array(
										'fields' => array( 'default_value', 'placeholder', 'required', 'field_tag' ),
									),
									'user_pass' => array(
										'fields' => array( 'placeholder', 'min_pass_length', 'password_toggle', 'field_tag' ),
									),
									'confirm_user_pass' => array(
										'fields' => array( 'placeholder', 'field_tag' ),
									),
									'first_name'	=> array(
										'fields' => array( 'default_value', 'placeholder', 'required', 'field_tag' ),
									),
									'last_name'		=> array(
										'fields' => array( 'default_value', 'placeholder', 'required', 'field_tag' ),
									),
									'user_url'      => array(
										'fields' => array( 'default_value', 'placeholder', 'required', 'field_tag' ),
									),
									'user_email'    => array(
										'fields' => array( 'default_value', 'placeholder', 'required', 'field_tag' ),
									),
									'consent'	=> array(
										'fields'	=> array( 'default_checked' ),
									),
									'static_text'	=> array(
										'fields'		=> array( 'static_text' ),
									),
									'text'     => array(
										'fields' => array( 'default_value', 'placeholder' ),
									),
									'textarea' => array(
										'fields' => array( 'default_value', 'placeholder', 'rows' ),
									),
									'select'   => array(
										'fields' => array( 'field_options', 'allow_multiple' ),
									),
									'radio'    => array(
										'fields' => array( 'field_options', 'layout' ),
									),
									'checkbox' => array(
										'fields' => array( 'field_options', 'layout' ),
									),
									'number'   => array(
										'fields' => array( 'default_value', 'placeholder', 'min_value', 'max_value' ),
									),
									'tel'      => array(
										'fields' => array( 'default_value', 'placeholder' ),
									),
									'hidden'   => array(
										'fields' => array( 'default_value' ),
									),
								),
							),
							'field_label'    => array(
								'type'        => 'text',
								'label'       => __( 'Label', 'bb-powerpack' ),
								'connections' => array( 'string' ),
							),
							'placeholder'    => array(
								'type'        => 'text',
								'label'       => __( 'Placeholder', 'bb-powerpack' ),
								'default'     => '',
								'connections' => array( 'string' ),
							),
							'min_pass_length'	=> array(
								'type'		=> 'unit',
								'label'		=> __( 'Minimum Password Length', 'bb-powerpack' ),
								'default'	=> '8',
								'help'		=> __( 'Set minimum length of the password. Default is 8 characters.', 'bb-powerpack' ),
								'units'		=> array( __( 'characters', 'bb-powerpack' ) ),
							),
							'password_toggle'	=> array(
								'type'	=> 'pp-switch',
								'label'	=> __( 'Password Visibility Toggle', 'bb-powerpack' ),
								'default' => 'show',
								'options' => array(
									'show'	=> __( 'Show', 'bb-powerpack' ),
									'hide'	=> __( 'Hide', 'bb-powerpack' ),
								),
							),
							'default_value'  => array(
								'type'        => 'text',
								'label'       => __( 'Default Value', 'bb-powerpack' ),
								'default'     => '',
								'connections' => array( 'string' ),
							),
							'default_checked'	=> array(
								'type'		=> 'pp-switch',
								'label'		=> __( 'Default Checked?', 'bb-powerpack' ),
								'default'	=> 'yes'
							),
							'static_text'	=> array(
								'type'			=> 'editor',
								'label'			=> '',
								'default'		=> '',
								'connections'	=> array( 'string', 'html' ),
								'media_buttons'	=> false,
							),
							'field_options'  => array(
								'type'    => 'textarea',
								'label'   => __( 'Options', 'bb-powerpack' ),
								'default' => '',
								'rows'    => '6',
								'help'    => __( 'Enter each option in a separate line.', 'bb-powerpack' ),
							),
							'allow_multiple' => array(
								'type'    => 'pp-switch',
								'label'   => __( 'Multiple Selection', 'bb-powerpack' ),
								'default' => 'no',
								'options' => array(
									'yes' => __( 'Yes', 'bb-powerpack' ),
									'no'  => __( 'No', 'bb-powerpack' ),
								),
							),
							'layout'         => array(
								'type'    => 'pp-switch',
								'label'   => __( 'Layout', 'bb-powerpack' ),
								'default' => 'stacked',
								'options' => array(
									'stacked' => __( 'Stacked', 'bb-powerpack' ),
									'inline'  => __( 'Inline', 'bb-powerpack' ),
								),
							),
							'rows'           => array(
								'type'    => 'text',
								'label'   => __( 'Rows', 'bb-powerpack' ),
								'default' => '4',
								'size'    => '5',
							),
							'min_value'      => array(
								'type'    => 'text',
								'label'   => __( 'Minimum Value', 'bb-powerpack' ),
								'default' => '',
							),
							'max_value'      => array(
								'type'    => 'text',
								'label'   => __( 'Maximum Value', 'bb-powerpack' ),
								'default' => '',
							),
							'required'       => array(
								'type'    => 'pp-switch',
								'label'   => __( 'Required?', 'bb-powerpack' ),
								'default' => 'no',
								'options' => array(
									'yes' => __( 'Yes', 'bb-powerpack' ),
									'no'  => __( 'No', 'bb-powerpack' ),
								),
							),
							'col_width'      => array(
								'type'    => 'select',
								'label'   => __( 'Column Width', 'bb-powerpack' ),
								'default' => '100',
								'options' => array(
									'20'  => '20%',
									'25'  => '25%',
									'30'  => '30%',
									'33'  => '33%',
									'40'  => '40%',
									'50'  => '50%',
									'60'  => '60%',
									'66'  => '66%',
									'75'  => '75%',
									'80'  => '80%',
									'100' => '100%',
								),
							),
						),
					),
				),
			),
			'advanced_settings' => array(
				'title'    => __( 'Advanced', 'bb-powerpack' ),
				'sections' => array(
					'advanced_settings' => array(
						'title'  => '',
						'fields' => array(
							'field_tag' => array(
								'type'    => 'pp-css-class',
								'label'   => __( 'Merge Tag', 'bb-powerpack' ),
								'default' => '{{}}',
							),
							'css_class'        => array(
								'type'    => 'text',
								'label'   => __( 'Custom CSS Class', 'bb-powerpack' ),
								'default' => '',
								'connections' => array( 'string' ),
							),
							'validation_msg'   => array(
								'type'    => 'text',
								'label'   => __( 'Custom Validation Message', 'bb-powerpack' ),
								'default' => '',
								'help'		=> __( 'You can display your own validation message when the field is left emptied.', 'bb-powerpack' ),
								'connections' => array( 'string' ),
							),
						),
					),
				),
			),
		),
	)
);

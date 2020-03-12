<?php
/**
 * @class PPLoginFormModule
 */
class PPLoginFormModule extends FLBuilderModule {
	public $form_error = false;

    /**
     * @method __construct
     */
    public function __construct() {
        parent::__construct( array(
            'name'              => __('Login Form', 'bb-powerpack'),
            'description'       => __('A module for better login form.', 'bb-powerpack'),
            'group'             => pp_get_modules_group(),
            'category'		    => pp_get_modules_cat( 'creative' ),
            'dir'               => BB_POWERPACK_DIR . 'modules/pp-login-form/',
            'url'               => BB_POWERPACK_URL . 'modules/pp-login-form/',
            'editor_export'     => true,
            'enabled'           => true,
            'partial_refresh'   => true,
		) );

		add_action( 'wp_ajax_pp_lf_process_login', array( $this, 'process_login' ) );
		add_action( 'wp_ajax_nopriv_pp_lf_process_login', array( $this, 'process_login' ) );
		add_action( 'wp_ajax_pp_lf_process_lost_pass', array( $this, 'process_lost_password' ) );
		add_action( 'wp_ajax_nopriv_pp_lf_process_lost_pass', array( $this, 'process_lost_password' ) );
		add_action( 'wp_ajax_pp_lf_process_reset_pass', array( $this, 'process_reset_password' ) );
		add_action( 'wp_ajax_nopriv_pp_lf_process_reset_pass', array( $this, 'process_reset_password' ) );
	}

	/**
	 * Process the login form.
	 *
	 * @throws Exception On login error.
	 */
	public function process_login() {
		if (
			! isset( $_POST['pp-lf-login-nonce'] ) ||
			! wp_verify_nonce( wp_unslash( $_POST['pp-lf-login-nonce'] ), 'login_nonce' ) ) {
				wp_send_json_error( __( 'Invalid data.', 'bb-powerpack' ) );
		}

		if ( isset( $_POST['username'], $_POST['password'] ) ) {
			try {
				$creds = array(
					'user_login'    => trim( wp_unslash( $_POST['username'] ) ), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					'user_password' => $_POST['password'], // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
					'remember'      => isset( $_POST['remember'] ), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				);

				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'pp_login_form_process_login_errors', $validation_error, $creds['user_login'], $creds['user_password'] );

				if ( $validation_error->get_error_code() ) {
					throw new Exception( '<strong>' . __( 'Error:', 'bb-powerpack' ) . '</strong> ' . $validation_error->get_error_message() );
				}

				if ( empty( $creds['user_login'] ) ) {
					throw new Exception( '<strong>' . __( 'Error:', 'bb-powerpack' ) . '</strong> ' . __( 'Username is required.', 'bb-powerpack' ) );
				}

				// On multisite, ensure user exists on current site, if not add them before allowing login.
				if ( is_multisite() ) {
					$user_data = get_user_by( is_email( $creds['user_login'] ) ? 'email' : 'login', $creds['user_login'] );

					if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
						add_user_to_blog( get_current_blog_id(), $user_data->ID, $user_data->roles[0] );
					}
				}

				// Perform the login.
				$user = wp_signon( apply_filters( 'pp_login_form_credentials', $creds ), is_ssl() );

				if ( is_wp_error( $user ) ) {
					$message = $user->get_error_message();
					$message = preg_replace( '/<\/?a[^>].*>/', '', $message );
					throw new Exception( $message );
				} else {

					if ( ! empty( $_POST['redirect'] ) ) {
						$redirect = wp_unslash( $_POST['redirect'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					} elseif ( $this->get_raw_referer() ) {
						$redirect = $this->get_raw_referer();
					} else {
						$redirect = wp_unslash( $_POST['page_url'] );
					}

					wp_send_json_success( array(
						'redirect_url' => wp_validate_redirect( $redirect, wp_unslash( $_POST['page_url'] ) ),
					) );
				}
			} catch ( Exception $e ) {
				$this->form_error = apply_filters( 'login_errors', $e->getMessage() );
				wp_send_json_error( $this->form_error );
			}
		}
	}

	private function get_raw_referer() {
		if ( ! empty( $_REQUEST['_wp_http_referer'] ) ) { // WPCS: input var ok, CSRF ok.
			return wp_unslash( $_REQUEST['_wp_http_referer'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
		} elseif ( ! empty( $_SERVER['HTTP_REFERER'] ) ) { // WPCS: input var ok, CSRF ok.
			return wp_unslash( $_SERVER['HTTP_REFERER'] ); // WPCS: input var ok, CSRF ok, sanitization ok.
		}

		return false;
	}

	public function process_lost_password() {
		if (
			! isset( $_POST['pp-lf-lost-password-nonce'] ) ||
			! wp_verify_nonce( wp_unslash( $_POST['pp-lf-lost-password-nonce'] ), 'lost_password' ) ) {
				wp_send_json_error( __( 'Invalid data.', 'bb-powerpack' ) );
		}

		$success = $this->retrieve_password();

		if ( ! $success ) {
			wp_send_json_error( $this->form_error );
		}

		wp_send_json_success();
	}

	private function retrieve_password() {
		$login = isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ) ) : ''; // WPCS: input var ok, CSRF ok.

		if ( empty( $login ) ) {

			$this->form_error = __( 'Enter a username or email address.', 'bb-powerpack' );

			return false;

		} else {
			// Check on username first, as customers can use emails as usernames.
			$user_data = get_user_by( 'login', $login );
		}

		// If no user found, check if it login is email and lookup user based on email.
		if ( ! $user_data && is_email( $login ) ) {
			$user_data = get_user_by( 'email', $login );
		}

		$errors = new WP_Error();

		do_action( 'lostpassword_post', $errors );

		if ( $errors->get_error_code() ) {
			$this->form_error = $errors->get_error_message();

			return false;
		}

		if ( ! $user_data ) {
			$this->form_error = __( 'Invalid username or email.', 'bb-powerpack' );

			return false;
		}

		if ( is_multisite() && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
			$this->form_error = __( 'Invalid username or email.', 'bb-powerpack' );

			return false;
		}

		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;

		do_action( 'retrieve_password', $user_login );

		$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

		if ( ! $allow ) {

			$this->form_error = __( 'Password reset is not allowed for this user', 'bb-powerpack' );

			return false;

		} elseif ( is_wp_error( $allow ) ) {

			$this->form_error = $errors->get_error_message();

			return false;
		}

		// Get password reset key (function introduced in WordPress 4.4).
		$key = get_password_reset_key( $user_data );

		$page_url = esc_url_raw( $_POST['page_url'] );

		$reset_url = add_query_arg( array(
			'reset_pass' => 1,
			'key'	=> $key,
			'id'	=> $user_data->ID
		), $page_url );

		// Send email notification.
		$email_sent = $this->send_activation_email( $user_data, $reset_url );

		if ( $email_sent ) {
			$this->form_error = esc_html__( 'An error occurred sending email. Please try again.', 'bb-powerpack' );
		}

		return $email_sent;
	}

	private function send_activation_email( $user, $reset_url ) {
		$email = $user->data->user_email;
		$blogname = esc_html( wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) );
		$admin_email = get_option( 'admin_email' );
		$subject = sprintf( esc_html__( 'Password Reset Request for %s', 'bb-powerpack' ), $blogname );

		$content = '';
		/* translators: %s: Username */
		$content .= '<p>' . sprintf( esc_html__( 'Hi %s,', 'bb-powerpack' ), esc_html( $user->data->user_login ) ) . '</p>';
		/* translators: %s: Site name */
		$content .= '<p>' . sprintf( esc_html__( 'Someone has requested a new password for the following account on %s:', 'bb-powerpack' ), $blogname ) . '</p>';
		/* translators: %s Username */
		$content .= '<p>' . sprintf( esc_html__( 'Username: %s', 'bb-powerpack' ), esc_html( $user->data->user_login ) ) . '</p>';
		$content .= esc_html__( 'If you didn\'t make this request, just ignore this email. If you\'d like to proceed:', 'bb-powerpack' );
		$content .= '<p>';
		$content .= '<a class="link" href="' . esc_url( $reset_url ) . '">';
		$content .= esc_html__( 'Click here to reset your password', 'bb-powerpack' );
		$content .= '</a>';
		$content .= '</p>';

		// translators: %s: email_from_name
		$headers = sprintf( 'From: %s <%s>' . "\r\n", $blogname, get_option( 'admin_email' ) );
		// translators: %s: email_reply_to
		$headers .= sprintf( 'Reply-To: %s' . "\r\n", $admin_email );
		$headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

		// Send email to user.
		$email_sent = wp_mail( $email, $subject, $content, $headers );

		return $email_sent;
	}

	public function process_reset_password() {
		if (
			! isset( $_POST['pp-lf-reset-password-nonce'] ) ||
			! wp_verify_nonce( wp_unslash( $_POST['pp-lf-reset-password-nonce'] ), 'reset_password' ) ) {
				wp_send_json_error( __( 'Invalid data.', 'bb-powerpack' ) );
		}

		$posted_fields = array( 'password_1', 'password_2', 'reset_key', 'reset_login' );

		foreach ( $posted_fields as $field ) {
			if ( ! isset( $_POST[ $field ] ) ) {
				return;
			}

			if ( in_array( $field, array( 'password_1', 'password_2' ) ) ) {
				// Don't unslash password fields
				$posted_fields[ $field ] = $_POST[ $field ]; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			} else {
				$posted_fields[ $field ] = wp_unslash( $_POST[ $field ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			}
		}

		$user = $this->check_password_reset_key( $posted_fields['reset_key'], $posted_fields['reset_login'] );

		if ( $user instanceof WP_User ) {
			if ( empty( $posted_fields['password_1'] ) ) {
				$this->form_error = __( 'Please enter your password.', 'bb-powerpack' );
			}

			if ( $posted_fields['password_1'] !== $posted_fields['password_2'] ) {
				$this->form_error = __( 'Passwords do not match.', 'bb-powerpack' );
			}

			$errors = new WP_Error();

			do_action( 'validate_password_reset', $errors, $user );

			if ( is_wp_error( $errors ) && $errors->get_error_messages() ) {
				foreach ( $errors->get_error_messages() as $error ) {
					$this->form_error .= $error . "\r\n";
				}
			}

			if ( empty( $this->form_error ) ) {
				$this->reset_password( $user, $posted_fields['password_1'] );

				do_action( 'pp_login_form_user_reset_password', $user );

				wp_send_json_success();
			}
		}

		if ( ! empty( $this->form_error ) ) {
			wp_send_json_error( $this->form_error );
		}
	}

	public function check_password_reset_key( $key, $login ) {
		// Check for the password reset key.
		// Get user data or an error message in case of invalid or expired key.
		$user = check_password_reset_key( $key, $login );

		if ( is_wp_error( $user ) ) {
			$this->form_error = __( 'This key is invalid or has already been used. Please reset your password again if needed.', 'bb-powerpack' );
			return false;
		}

		return $user;
	}

	/**
	 * Handles resetting the user's password.
	 *
	 * @param object $user     The user.
	 * @param string $new_pass New password for the user in plaintext.
	 */
	private function reset_password( $user, $new_pass ) {
		do_action( 'password_reset', $user, $new_pass );

		wp_set_password( $new_pass, $user->ID );
		$this->set_reset_password_cookie();

		if ( ! apply_filters( 'pp_login_form_disable_password_change_notification', false ) ) {
			wp_password_change_notification( $user );
		}
	}

	/**
	 * Set or unset the cookie.
	 *
	 * @param string $value Cookie value.
	 */
	private function set_reset_password_cookie( $value = '' ) {
		$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
		$rp_path   = isset( $_POST['page_url'] ) ? current( explode( '?', wp_unslash( $_POST['page_url'] ) ) ) : ''; // WPCS: input var ok, sanitization ok.

		if ( $value ) {
			setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
		} else {
			setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
		}
	}

	public function get_registration_url() {
		$page_id = BB_PowerPack_Admin_Settings::get_option( 'bb_powerpack_register_page', true );

		if ( empty( $page_id ) ) {
			return wp_registration_url();
		}

		return get_permalink( $page_id );
	}

	public function get_error_message() {
		return $this->form_error;
	}
}

FLBuilder::register_module('PPLoginFormModule', array(
	'general'	=> array(
		'title'		=> __('General', 'bb-powerpack'),
		'sections'	=> array(
			'form_fields'	=> array(
				'title'			=> '',
				'fields'		=> array(
					'show_labels'	=> array(
						'type'			=> 'pp-switch',
						'label'			=> __('Label', 'bb-powerpack'),
						'default'		=> 'yes',
						'options'		=> array(
							'yes'			=> __('Show', 'bb-powerpack'),
							'no'			=> __('Hide', 'bb-powerpack')
						),
					)
				)
			),
			'fields_label'	=> array(
				'title'			=> __('Label & Placeholder', 'bb-powerpack'),
				'collapsed'		=> true,
				'fields'		=> array(
					'username_label'	=> array(
						'type'		=> 'text',
						'label'		=> __('Username Label', 'bb-powerpack'),
						'default'	=> __('Username or Email Address', 'bb-powerpack'),
						'connections'	=> array('string')
					),
					'username_placeholder'	=> array(
						'type'		=> 'text',
						'label'		=> __('Username Placeholder', 'bb-powerpack'),
						'default'	=> __('Username or Email Address', 'bb-powerpack'),
						'connections'	=> array('string')
					),
					'password_label'	=> array(
						'type'		=> 'text',
						'label'		=> __('Password Label', 'bb-powerpack'),
						'default'	=> __('Password', 'bb-powerpack'),
						'connections'	=> array('string')
					),
					'password_placeholder'	=> array(
						'type'		=> 'text',
						'label'		=> __('Password Placeholder', 'bb-powerpack'),
						'default'	=> __('Password', 'bb-powerpack'),
						'connections'	=> array('string')
					),
				)
			),
			'button'	=> array(
				'title'		=> __('Button', 'bb-powerpack'),
				'collapsed'	=> true,
				'fields'	=> array(
					'button_text'	=> array(
						'type'			=> 'text',
						'label'			=> __('Text', 'bb-powerpack'),
						'default'		=> __('Log In', 'bb-powerpack'),
						'connections'	=> array('string')
					),
					'button_align'	=> array(
						'type'			=> 'align',
						'label'			=> __('Alignment', 'bb-powerpack'),
						'default'		=> 'left',
						'responsive'	=> true,
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group.pp-field-type-submit, .pp-field-group.pp-field-type-link',
							'property'		=> 'text-align'
						)
					)
				)
			),
			'additional_options'	=> array(
				'title'		=> __('Additional Options', 'bb-powerpack'),
				'collapsed'	=> true,
				'fields'	=> array(
					'redirect_after_login'	=> array(
						'type'		=> 'pp-switch',
						'label'		=> __('Redirect After Login', 'bb-powerpack'),
						'default'	=> 'no',
						'options'	=> array(
							'yes'		=> __('Yes', 'bb-powerpack'),
							'no'		=> __('No', 'bb-powerpack')
						),
						'preview'	=> array(
							'type'		=> 'none',
						),
						'toggle'	=> array(
							'yes'		=> array(
								'fields'	=> array('redirect_url')
							)
						)
					),
					'redirect_url'	=> array(
						'type'			=> 'link',
						'label'			=> __('Redirect URL', 'bb-powerpack'),
						'description'	=> __('Note: Because of security reasons, you can ONLY use your current domain.', 'bb-powerpack'),
						'connections'	=> array('url', 'string'),
						'show_target'	=> false,
						'show_nofollow'	=> false,
					),
					'redirect_after_logout'	=> array(
						'type'		=> 'pp-switch',
						'label'		=> __('Redirect After Logout', 'bb-powerpack'),
						'default'	=> 'no',
						'options'	=> array(
							'yes'		=> __('Yes', 'bb-powerpack'),
							'no'		=> __('No', 'bb-powerpack')
						),
						'preview'	=> array(
							'type'		=> 'none',
						),
						'toggle'	=> array(
							'yes'		=> array(
								'fields'	=> array('redirect_logout_url')
							)
						)
					),
					'redirect_logout_url'	=> array(
						'type'			=> 'link',
						'label'			=> __('Redirect URL', 'bb-powerpack'),
						'description'	=> __('Note: Because of security reasons, you can ONLY use your current domain.', 'bb-powerpack'),
						'connections'	=> array('url', 'string'),
						'show_target'	=> false,
						'show_nofollow'	=> false,
					),
					'show_lost_password'	=> array(
						'type'			=> 'pp-switch',
						'label'			=> __('Show Password Reset Link', 'bb-powerpack'),
						'default'		=> 'yes',
						'options'		=> array(
							'yes'			=> __('Yes', 'bb-powerpack'),
							'no'			=> __('No', 'bb-powerpack')
						),
						'toggle'		=> array(
							'yes'			=> array(
								'fields'		=> array('lost_password_text')
							)
						)
					),
					'lost_password_text'	=> array(
						'type'		=> 'text',
						'label'		=> __('Text', 'bb-powerpack'),
						'default'	=> __('Lost your password?', 'bb-powerpack'),
						'preview'	=> array(
							'type'		=> 'text',
							'selector'	=> '.pp-field-group .pp-login-lost-password'
						),
						'connections'	=> array('string')
					),
					'show_register'		=> array(
						'type'			=> 'pp-switch',
						'label'			=> __('Show Register Link', 'bb-powerpack'),
						'help'			=> __('This option will only be available if the registration is enabled in WP admin general settings.', 'bb-powerpack'),
						'default'		=> 'yes',
						'options'		=> array(
							'yes'			=> __('Yes', 'bb-powerpack'),
							'no'			=> __('No', 'bb-powerpack')
						),
					),
					'show_remember_me'	=> array(
						'type'			=> 'pp-switch',
						'label'			=> __('Show Remember Me', 'bb-powerpack'),
						'default'		=> 'yes',
						'options'		=> array(
							'yes'			=> __('Yes', 'bb-powerpack'),
							'no'			=> __('No', 'bb-powerpack')
						),
						'toggle'		=> array(
							'yes'			=> array(
								'fields'		=> array('remember_me_text')
							)
						)
					),
					'remember_me_text'	=> array(
						'type'		=> 'text',
						'label'		=> __('Text', 'bb-powerpack'),
						'default'	=> __('Remember Me', 'bb-powerpack'),
						'preview'	=> array(
							'type'		=> 'text',
							'selector'	=> '.pp-field-group .pp-login-remember-me'
						),
						'connections'	=> array('string')
					),
					'show_logged_in_message'	=> array(
						'type'			=> 'pp-switch',
						'label'			=> __('Show Logged in Message', 'bb-powerpack'),
						'default'		=> 'yes',
						'options'		=> array(
							'yes'			=> __('Yes', 'bb-powerpack'),
							'no'			=> __('No', 'bb-powerpack')
						)
					)
				)
			)
		)
	),
	'style'		=> array(
		'title'		=> __('Style', 'bb-powerpack'),
		'sections'		=> array(
			'general_style'	=> array(
				'title'			=> __('General', 'bb-powerpack'),
				'fields'		=> array(
					'fields_spacing'	=> array(
						'type'			=> 'unit',
						'label'			=> __('Fields Spacing', 'bb-powerpack'),
						'default'		=> '',
						'units'			=> array('px'),
						'slider'		=> true,
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group',
							'property'		=> 'margin-bottom',
							'unit'			=> 'px'
						)
					),
					'links_color'	=> array(
						'type'			=> 'color',
						'label'			=> __('Links Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group > a',
							'property'		=> 'color',
						)
					),
					'links_hover_color'	=> array(
						'type'			=> 'color',
						'label'			=> __('Links Hover Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'none'
						)
					),
				)
			),
			'form_style'	=> array(
				'title'			=> __('Form', 'bb-powerpack'),
				'collapsed'		=> true,
				'fields'		=> array(
					'form_bg_color'		=> array(
						'type'			=> 'color',
						'label'			=> __('Background Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'show_alpha'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-login-form',
							'property'		=> 'background-color'
						)
					),
					'form_padding'	=> array(
						'type'			=> 'dimension',
						'label'			=> __('Padding', 'bb-powerpack'),
						'default'		=> '',
						'slider'		=> true,
						'units'			=> array('px'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-login-form',
							'property'		=> 'padding',
							'unit'			=> 'px'
						)
					),
					'form_border'	=> array(
						'type'			=> 'border',
						'label'			=> __('Border', 'bb-powerpack'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-login-form'
						)
					)
				)
			),
			'label_style'	=> array(
				'title'			=> __('Label', 'bb-powerpack'),
				'collapsed'		=> true,
				'fields'		=> array(
					'label_spacing'	=> array(
						'type'			=> 'unit',
						'label'			=> __('Spacing', 'bb-powerpack'),
						'default'		=> '',
						'units'			=> array('px'),
						'slider'		=> true,
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group > label',
							'property'		=> 'margin-bottom',
							'unit'			=> 'px'
						)
					),
					'label_color'	=> array(
						'type'			=> 'color',
						'label'			=> __('Text Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group > label',
							'property'		=> 'color',
						)
					)
				)
			),
			'fields_style'	=> array(
				'title'			=> __('Fields', 'bb-powerpack'),
				'collapsed'		=> true,
				'fields'		=> array(
					'field_text_color'	=> array(
						'type'			=> 'color',
						'label'			=> __('Text Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group .pp-login-form--input',
							'property'		=> 'color',
						)
					),
					'field_bg_color'	=> array(
						'type'			=> 'color',
						'label'			=> __('Background Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'show_alpha'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group .pp-login-form--input',
							'property'		=> 'background-color',
						)
					),
					'field_height'	=> array(
						'type'			=> 'unit',
						'label'			=> __('Height', 'bb-powerpack'),
						'default'		=> '',
						'slider'		=> true,
						'responsive'	=> true,
						'units'			=> array('px'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group .pp-login-form--input',
							'property'		=> 'height',
							'unit'			=> 'px'
						)
					),
					'field_padding'	=> array(
						'type'			=> 'dimension',
						'label'			=> __('Padding', 'bb-powerpack'),
						'default'		=> '',
						'slider'		=> true,
						'responsive'	=> true,
						'units'			=> array('px'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group .pp-login-form--input',
							'property'		=> 'padding',
							'unit'			=> 'px'
						)
					),
					'field_border'	=> array(
						'type'			=> 'border',
						'label'			=> __('Border', 'bb-powerpack'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group .pp-login-form--input',
						)
					),
					'field_border_focus_color'	=> array(
						'type'			=> 'color',
						'label'			=> __('Border Focus Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'none'
						)
					)
				)
			),
			'button_style'	=> array(
				'title'			=> __('Button', 'bb-powerpack'),
				'collapsed'		=> true,
				'fields'		=> array(
					'button_text_color'	=> array(
						'type'			=> 'color',
						'label'			=> __('Text Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group .pp-login-form--button',
							'property'		=> 'color',
						)
					),
					'button_text_hover_color'	=> array(
						'type'			=> 'color',
						'label'			=> __('Text Hover Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'none',
						)
					),
					'button_bg_color'	=> array(
						'type'			=> 'color',
						'label'			=> __('Background Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'show_alpha'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group .pp-login-form--button',
							'property'		=> 'background-color',
						)
					),
					'button_bg_hover_color'	=> array(
						'type'			=> 'color',
						'label'			=> __('Background Hover Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'show_alpha'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'none',
						)
					),
					'button_border'	=> array(
						'type'			=> 'border',
						'label'			=> __('Border', 'bb-powerpack'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group .pp-login-form--button',
						)
					),
					'button_border_hover_color'	=> array(
						'type'			=> 'color',
						'label'			=> __('Border Hover Color', 'bb-powerpack'),
						'default'		=> '',
						'show_reset'	=> true,
						'connections'	=> array('color'),
						'preview'		=> array(
							'type'			=> 'none',
						)
					),
					'button_padding'	=> array(
						'type'				=> 'dimension',
						'label'				=> __('Padding', 'bb-powerpack'),
						'default'			=> '',
						'slider'			=> true,
						'units'				=> array('px'),
						'preview'			=> array(
							'type'				=> 'css',
							'selector'			=> '.pp-field-group .pp-login-form--button',
							'property'			=> 'padding',
							'unit'				=> 'px'
						)
					),
					'button_width'	=> array(
						'type'			=> 'unit',
						'label'			=> __('Width', 'bb-powerpack'),
						'default'		=> '',
						'help'			=> __('Leave empty for default width.', 'bb-powerpack'),
						'slider'		=> true,
						'responsive'	=> true,
						'units'			=> array('px', '%'),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-field-group .pp-login-form--button',
							'property'		=> 'width',
						)
					)
				)
			)
		)
	),
	'typography'	=> array(
		'title'			=> __('Typography', 'bb-powerpack'),
		'sections'		=> array(
			'label_typography'	=> array(
				'title'				=> __('Label', 'bb-powerpack'),
				'fields'			=> array(
					'label_typography'	=> array(
						'type'				=> 'typography',
						'label'				=> __('Typography', 'bb-powerpack'),
						'responsive'		=> true,
						'preview'			=> array(
							'type'				=> 'css',
							'selector'			=> '.pp-field-group > label',
						)
					),
				)
			),
			'fields_typography'	=> array(
				'title'				=> __('Fields', 'bb-powerpack'),
				'collapsed'			=> true,
				'fields'			=> array(
					'fields_typography'	=> array(
						'type'				=> 'typography',
						'label'				=> __('Typography', 'bb-powerpack'),
						'responsive'		=> true,
						'preview'			=> array(
							'type'				=> 'css',
							'selector'			=> '.pp-field-group .pp-login-form--input',
						)
					),
				)
			),
			'button_typography'	=> array(
				'title'				=> __('Button', 'bb-powerpack'),
				'collapsed'			=> true,
				'fields'			=> array(
					'button_typography'	=> array(
						'type'				=> 'typography',
						'label'				=> __('Typography', 'bb-powerpack'),
						'responsive'		=> true,
						'preview'			=> array(
							'type'				=> 'css',
							'selector'			=> '.pp-field-group .pp-login-form--button',
						)
					),
				)
			),
		)
	)
) );
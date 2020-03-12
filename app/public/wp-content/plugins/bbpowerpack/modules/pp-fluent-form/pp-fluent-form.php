<?php

/**
 * @class PPFluentFormModule
 */
class PPFluentFormModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          => __( 'WP Fluent Forms', 'bb-powerpack' ),
			'description'   => __( 'A module for WP Fluent Forms.', 'bb-powerpack' ),
			'group'         => pp_get_modules_group(),
			'category'		=> pp_get_modules_cat( 'form_style' ),
			'dir'           => BB_POWERPACK_DIR . 'modules/pp-fluent-form/',
			'url'           => BB_POWERPACK_URL . 'modules/pp-fluent-form/',
			'editor_export' => true, // Defaults to true and can be omitted.
			'enabled'       => true, // Defaults to true and can be omitted.
		));
	}

	// Get all forms of WP Fluent Forms plugin
	public static function pp_get_fluent_forms() {
		$options = array();

		if ( function_exists( 'wpFluentForm' ) ) {
			global $wpdb;
			$result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fluentform_forms" );
			if ( $result ) {
				$options[0] = esc_html__( 'Select a form', 'bb-powerpack' );
				foreach ( $result as $form ) {
					$options[$form->id] = $form->title;
				}
			} else {
				$options[0] = esc_html__( 'No forms found!', 'bb-powerpack' );
			}
		}

		return $options;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'PPFluentFormModule',
	array(
		'form'            => array( // Tab
			'title'         => __( 'General', 'bb-powerpack' ), // Tab title
			'sections'      => array( // Tab Sections
				'select_form'       => array( // Section
					'title'         => '', // Section Title
					'fields'        => array( // Section Fields
						'select_form_field' => array(
							'type'          => 'select',
							'label'         => __( 'Select Form', 'bb-powerpack' ),
							'default'       => '',
							'options'       => PPFluentFormModule::pp_get_fluent_forms(),
						),
					),
				),
				'form_general_setting'  => array(
					'title' => __( 'Settings', 'bb-powerpack' ),
					'fields'    => array(
						'form_custom_title_desc'   => array(
							'type'          => 'pp-switch',
							'label'         => __( 'Custom Title & Description', 'bb-powerpack' ),
							'default'       => 'no',
							'options'       => array(
								'yes'      => __( 'Yes', 'bb-powerpack' ),
								'no'     => __( 'No', 'bb-powerpack' ),
							),
							'toggle' => array(
								'yes'      => array(
									'fields'  => array( 'custom_title', 'custom_description' ),
									'sections'  => array( 'title_style', 'description_style' ),
								),
							),
						),
						'custom_title'      => array(
							'type'          => 'text',
							'label'         => __( 'Custom Title', 'bb-powerpack' ),
							'default'       => '',
							'description'   => '',
							'connections'   => array( 'string' ),
							'preview'       => array(
								'type'      => 'text',
								'selector'  => '.pp-form-title',
							),
						),
						'custom_description'    => array(
							'type'              => 'textarea',
							'label'             => __( 'Custom Description', 'bb-powerpack' ),
							'default'           => '',
							'placeholder'       => '',
							'rows'              => '6',
							'connections'   => array( 'string', 'html' ),
							'preview'           => array(
								'type'          => 'text',
								'selector'      => '.pp-form-description',
							),
						),
					),
				),
			),
		),
		'style'           => array( // Tab
			'title'         => __( 'Style', 'bb-powerpack' ), // Tab title
			'sections'      => array( // Tab Sections
				'form_background'      => array( // Section
					'title'         => __( 'Form Background', 'bb-powerpack' ), // Section Title
					'fields'        => array( // Section Fields
						'form_bg_type'      => array(
							'type'          => 'pp-switch',
							'label'         => __( 'Background Type', 'bb-powerpack' ),
							'default'       => 'color',
							'options'       => array(
								'color'   => __( 'Color', 'bb-powerpack' ),
								'image'     => __( 'Image', 'bb-powerpack' ),
							),
							'toggle'    => array(
								'color' => array(
									'fields'    => array( 'form_bg_color' ),
								),
								'image' => array(
									'fields'    => array( 'form_bg_image', 'form_bg_size', 'form_bg_repeat', 'form_bg_overlay' ),
								),
							),
						),
						'form_bg_color'     => array(
							'type'          => 'color',
							'label'         => __( 'Background Color', 'bb-powerpack' ),
							'default'       => '',
							'show_reset'    => true,
							'show_alpha'	=> true,
							'connections'	=> array( 'color' ),
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-fluent-form-content',
								'property'  => 'background-color'
							),
						),
						'form_bg_image'     => array(
							'type'              => 'photo',
							'label'         => __( 'Background Image', 'bb-powerpack' ),
							'default'       => '',
							'show_remove'	=> true,
							'connections'   => array( 'photo' ),
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-fluent-form-content',
								'property'  => 'background-image'
							),
						),
						'form_bg_size'      => array(
							'type'          => 'pp-switch',
							'label'         => __( 'Background Size', 'bb-powerpack' ),
							'default'       => 'cover',
							'options'       => array(
								'contain'   => __( 'Contain', 'bb-powerpack' ),
								'cover'     => __( 'Cover', 'bb-powerpack' ),
							),
						),
						'form_bg_repeat'    => array(
							'type'          => 'pp-switch',
							'label'         => __( 'Background Repeat', 'bb-powerpack' ),
							'default'       => 'no-repeat',
							'options'       => array(
								'repeat-x'      => __( 'Repeat X', 'bb-powerpack' ),
								'repeat-y'      => __( 'Repeat Y', 'bb-powerpack' ),
								'no-repeat'     => __( 'No Repeat', 'bb-powerpack' ),
							),
						),
						'form_bg_overlay'     => array(
							'type'          => 'color',
							'label'         => __( 'Background Overlay Color', 'bb-powerpack' ),
							'default'       => '000000',
							'show_reset'    => true,
							'show_alpha'	=> true,
							'connections'	=> array( 'color' ),
						),
					),
				),
				'form_border_settings'      => array( // Section
					'title'         => __( 'Form Border', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'        => array( // Section Fields
						'form_border'	=> array(
							'type'          => 'border',
							'label'         => __( 'Border & Padding', 'bb-powerpack' ),
							'responsive'	=> true,
							'preview'   	=> array(
								'type'  		=> 'css',
								'selector'  	=> '.pp-fluent-form-content',
								'property'  	=> 'border',
							),
						),
						'form_padding'    => array(
							'type'				=> 'dimension',
							'label'				=> __( 'Padding', 'bb-powerpack' ),
							'default'			=> '15',
							'units'				=> array( 'px' ),
							'slider'			=> true,
							'responsive'		=> true,
							'preview'			=> array(
								'type'				=> 'css',
								'selector'			=> '.pp-fluent-form-content',
								'property'			=> 'padding',
								'unit'				=> 'px',
							),
						),
					),
				),
				'title_style' => array( // Section
					'title' => __( 'Title', 'bb-powerpack' ),
					'collapsed'		=> true,
					'fields'    => array(
						'title_color'       => array(
							'type'          => 'color',
							'label'         => __( 'Color', 'bb-powerpack' ),
							'default'       => '',
							'show_reset'    => true,
							'connections'	=> array( 'color' ),
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-fluent-form-content .pp-form-title',
								'property'  => 'color',
							),
						),
						'title_margin'	=> array(
							'type'				=> 'dimension',
							'label'				=> __( 'Margin', 'bb-powerpack' ),
							'default'			=> '10',
							'units'				=> array( 'px' ),
							'slider'			=> true,
							'responsive'		=> true,
							'preview'			=> array(
								'type'				=> 'css',
								'selector'			=> '.pp-fluent-form-content .pp-form-title',
								'property'			=> 'margin',
								'unit'				=> 'px',
							),
						),
					),
				),
				'description_style' => array( // Section
					'title' => __( 'Description', 'bb-powerpack' ),
					'collapsed'		=> true,
					'fields'    => array(
						'description_color' => array(
							'type'          => 'color',
							'label'         => __( 'Color', 'bb-powerpack' ),
							'default'       => '',
							'show_reset'    => true,
							'connections'	=> array( 'color' ),
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-fluent-form-content .pp-form-description',
								'property'  => 'color',
							),
						),
						'description_margin'	=> array(
							'type'				=> 'dimension',
							'label'				=> __( 'Margin', 'bb-powerpack' ),
							'default'			=> '10',
							'units'				=> array( 'px' ),
							'slider'			=> true,
							'responsive'		=> true,
							'preview'			=> array(
								'type'				=> 'css',
								'selector'			=> '.pp-fluent-form-content .pp-form-description',
								'property'			=> 'margin',
								'unit'				=> 'px',
							),
						),
					),
				),
				'label_style'	=> array(
					'title'	=> __( 'Labels', 'bb-powerpack' ),
					'collapsed'	=> true,
					'fields'	=> array(
						'display_labels'   => array(
							'type'         => 'pp-switch',
							'label'        => __( 'Labels', 'bb-powerpack' ),
							'default'      => 'inline-block',
							'options'      => array(
								'inline-block'    => __( 'Show', 'bb-powerpack' ),
								'none'     => __( 'Hide', 'bb-powerpack' ),
							),
						),
						'label_color'  => array(
							'type'          => 'color',
							'label'         => __( 'Color', 'bb-powerpack' ),
							'default'       => '',
							'show_reset'    => true,
							'connections'	=> array( 'color' ),
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-fluent-form-content .ff-el-input--label label, .pp-fluent-form-content .fluentform .ff-el-form-check-label',
								'property'  => 'color',
							),
						),
					),
				),
				'section_field_setting'	=> array( // Section
					'title' 	=> __( 'Section Field', 'bb-powerpack' ),
					'collapsed'	=> true,
					'fields'    => array(
						'section_field_bg_color'     => array(
							'type'          => 'color',
							'label'         => __( 'Background Color', 'bb-powerpack' ),
							'default'       => '',
							'show_reset'    => true,
							'show_alpha'    => true,
							'connections'	=> array( 'color' ),
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-fluent-form-content .ff-el-section-break',
								'property'  => 'background-color',
							),
						),
						'section_field_border'	=> array(
							'type'          => 'border',
							'label'         => __( 'Border', 'bb-powerpack' ),
							'responsive'	=> true,
							'preview'   	=> array(
								'type'  		=> 'css',
								'selector'  	=> '.pp-fluent-form-content .ff-el-section-break',
								'property'  	=> 'border',
							),
						),
						'section_field_margin'	=> array(
							'type'				=> 'dimension',
							'label'				=> __( 'Margin', 'bb-powerpack' ),
							'default'			=> '0',
							'units'				=> array( 'px' ),
							'slider'			=> true,
							'responsive'		=> true,
							'preview'			=> array(
								'type'				=> 'css',
								'selector'			=> '.pp-fluent-form-content .ff-el-section-break',
								'property'			=> 'margin',
								'unit'				=> 'px',
							),
						),
						'section_field_padding'	=> array(
							'type'				=> 'dimension',
							'label'				=> __( 'Padding', 'bb-powerpack' ),
							'default'			=> '0',
							'units'				=> array( 'px' ),
							'slider'			=> true,
							'responsive'		=> true,
							'preview'			=> array(
								'type'				=> 'css',
								'selector'			=> '.pp-fluent-form-content .ff-el-section-break',
								'property'			=> 'padding',
								'unit'				=> 'px',
							),
						),
					),
				),
			),
		),
		'input_style_t'   => array(
			'title' => __( 'Inputs', 'bb-powerpack' ),
			'sections'  => array(
				'input_field_colors'      => array( // Section
					'title'         => __( 'Colors', 'bb-powerpack' ), // Section Title
					'fields'        => array( // Section Fields
						'input_field_text_color'    => array(
							'type'                  => 'color',
							'label'                 => __( 'Text Color', 'bb-powerpack' ),
							'default'               => '',
							'show_reset'            => true,
							'connections'			=> array( 'color' ),
							'preview'               => array(
								'type'                  => 'css',
								'selector'              => '.pp-fluent-form-content .fluentform .ff-el-form-control',
								'property'              => 'color',
							),
						),
						'input_field_bg_color'      => array(
							'type'                  => 'color',
							'label'                 => __( 'Background Color', 'bb-powerpack' ),
							'default'               => '',
							'show_reset'            => true,
							'show_alpha'			=> true,
							'connections'			=> array( 'color' ),
							'preview'               => array(
								'type'              => 'css',
								'selector'          => '.pp-fluent-form-content .fluentform .ff-el-form-control',
								'property'          => 'background-color',
							),
						),
					),
				),
				'input_border_settings'      => array( // Section
					'title'         => __( 'Border', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'        => array( // Section Fields
						'input_border'	=> array(
							'type'			=> 'border',
							'label'			=> __( 'Border', 'bb-powerpack' ),
							'preview'		=> array(
								'type'			=> 'css',
								'selector'		=> '.pp-fluent-form-content .fluentform .ff-el-form-control',
							),
						),
						'input_field_focus_color'      => array(
							'type'                  => 'color',
							'label'                 => __( 'Focus Border Color', 'bb-powerpack' ),
							'default'               => '',
							'show_reset'            => true,
							'connections'			=> array( 'color' ),
							'preview'               => array(
								'type'              => 'css',
								'selector'          => '.pp-fluent-form-content .fluentform .ff-el-form-control:focus',
								'property'          => 'border-color',
							),
						),
					),
				),
				'input_size_alignment'      => array( // Section
					'title'         => __( 'Size & Alignment', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'        => array( // Section Fields
						'input_field_width'     => array(
							'type'              => 'pp-switch',
							'label'             => __( 'Full Width', 'bb-powerpack' ),
							'default'           => 'false',
							'options'           => array(
								'true'          => __( 'Yes', 'bb-powerpack' ),
								'false'         => __( 'No', 'bb-powerpack' ),
							),
						),
						'input_field_height'    => array(
							'type'                    => 'unit',
							'label'                   => __( 'Input Height', 'bb-powerpack' ),
							'default'                 => '',
							'units'					  => array( 'px' ),
							'slider'				  => true,
							'preview'                 => array(
								'type'                => 'css',
								'selector'            => '.pp-fluent-form-content .fluentform .ff-el-form-control',
								'property'            => 'height',
								'unit'                => 'px',
							),
						),
						'input_textarea_height'    => array(
							'type'                    => 'unit',
							'label'                   => __( 'Textarea Height', 'bb-powerpack' ),
							'default'                 => '',
							'units'					  => array( 'px' ),
							'slider'				  => true,
							'preview'                 => array(
								'type'                => 'css',
								'selector'            => '.pp-fluent-form-content .fluentform textarea.ff-el-form-control',
								'property'            => 'height',
								'unit'                => 'px',
							),
						),
					),
				),
				'input_general_style'      => array( // Section
					'title'         => __( 'General', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'        => array( // Section Fields
						'input_field_padding'    => array(
							'type'				=> 'dimension',
							'label'				=> __( 'Padding', 'bb-powerpack' ),
							'default'			=> '10',
							'units'				=> array( 'px' ),
							'slider'			=> true,
							'responsive'		=> true,
							'preview'			=> array(
								'type'				=> 'css',
								'selector'			=> '.pp-fluent-form-content .fluentform .ff-el-form-control',
								'property'			=> 'padding',
								'unit'				=> 'px',
							),
						),
						'input_field_margin'    => array(
							'type'              => 'unit',
							'label'             => __( 'Margin Bottom', 'bb-powerpack' ),
							'default'           => '10',
							'units'				=> array( 'px' ),
							'slider'			=> true,
							'preview'           => array(
								'type'          => 'css',
								'selector'      => '.pp-fluent-form-content .ff-field_container',
								'property'      => 'margin-bottom',
								'unit'          => 'px',
							),
						),
					),
				),
				'placeholder_style'      => array( // Section
					'title'         => __( 'Placeholder', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'        => array( // Section Fields
						'input_placeholder_display' 	=> array(
							'type'          => 'pp-switch',
							'label'         => __( 'Show Placeholder', 'bb-powerpack' ),
							'default'       => 'block',
							'options'		=> array(
								'block'	=> __( 'Yes', 'bb-powerpack' ),
								'none'	=> __( 'No', 'bb-powerpack' ),
							),
							'toggle' => array(
								'block' => array(
									'fields' => array( 'input_placeholder_color' ),
								),
							),
						),
						'input_placeholder_color'  => array(
							'type'                  => 'color',
							'label'                 => __( 'Color', 'bb-powerpack' ),
							'default'               => '',
							'show_reset'            => true,
							'connections'			=> array( 'color' ),
							'preview'               => array(
								'type'              => 'css',
								'selector'          => '.pp-fluent-form-content .fluentform .ff-el-form-control::-webkit-input-placeholder',
								'property'          => 'color',
							),
						),
					),
				),
				'radio_cb_style'    => array(
					'title'     => __( 'Radio & Checkbox', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'radio_cb_style'           => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Enable Custom Style', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'radio_cb_size', 'radio_cb_color', 'radio_cb_checked_color', 'radio_cb_border_width', 'radio_cb_border_color', 'radio_cb_radius', 'radio_cb_checkbox_radius' ),
								),
							),
						),
						'radio_cb_size'            => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'bb-powerpack' ),
							'default' => '15',
							'slider'  => true,
							'units'   => array( 'px' ),
							'class'   => '.pp-fluent-form-content .fluentform input[type=checkbox], .pp-fluent-form-content .fluentform input[type=radio]',
						),
						'radio_cb_color'           => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'default'     => 'dddddd',
							'show_reset'  => true,
							'connections' => array( 'color' ),
						),
						'radio_cb_checked_color'   => array(
							'type'        => 'color',
							'label'       => __( 'Checked Color', 'bb-powerpack' ),
							'default'     => '999999',
							'show_reset'  => true,
							'connections' => array( 'color' ),
						),
						'radio_cb_border_width'    => array(
							'type'    => 'unit',
							'label'   => __( 'Border Width', 'bb-powerpack' ),
							'default' => '1',
							'slider'  => true,
							'units'   => array( 'px' ),
						),
						'radio_cb_border_color'    => array(
							'type'        => 'color',
							'label'       => __( 'Border Color', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'connections' => array( 'color' ),
						),
						'radio_cb_radius'          => array(
							'type'    => 'unit',
							'label'   => __( 'Radio Round Corners', 'bb-powerpack' ),
							'default' => '50',
							'slider'  => true,
							'units'   => array( 'px' ),
						),
						'radio_cb_checkbox_radius' => array(
							'type'    => 'unit',
							'label'   => __( 'Checkbox Round Corners', 'bb-powerpack' ),
							'default' => '0',
							'slider'  => true,
							'units'   => array( 'px' ),
						),
					),
				),
			),
		),
		'button_style'    => array(
			'title'    => __( 'Button', 'bb-powerpack' ),
			'sections' => array(
				'button_colors'          => array(
					'title'  => __( 'Colors', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'button_text_color'             => array(
							'type'       	=> 'color',
							'label'     	=> __( 'Text Color', 'bb-powerpack' ),
							'default'    	=> '',
							'show_reset'	=> true,
							'connections'	=> array( 'color' ),
							'preview'	=> array(
								'type'		=> 'css',
								'selector'	=> '.pp-fluent-form-content .fluentform .ff_submit_btn_wrapper button',
								'property'	=> 'color',
							),
						),
						'button_text_color_hover'       => array(
							'type'       	=> 'color',
							'label'     	=> __( 'Text Hover Color', 'bb-powerpack' ),
							'default'    	=> '',
							'show_reset'	=> true,
							'connections'	=> array( 'color' ),
							'preview'	=> array(
								'type'		=> 'css',
								'selector'	=> '.pp-fluent-form-content .fluentform .ff_submit_btn_wrapper button:hover',
								'property'	=> 'color',
							),
						),
						'button_bg_color'               => array(
							'type'       	=> 'color',
							'label'     	=> __( 'Background Color', 'bb-powerpack' ),
							'default'    	=> '',
							'show_reset'	=> true,
							'show_alpha'	=> true,
							'connections'	=> array( 'color' ),
							'preview'	=> array(
								'type'		=> 'css',
								'selector'	=> '.pp-fluent-form-content .fluentform .ff_submit_btn_wrapper button',
								'property'	=> 'background-color',
							),
						),
						'button_background_color_hover' => array(
							'type'       	=> 'color',
							'label'     	=> __( 'Background Hover Color', 'bb-powerpack' ),
							'default'    	=> '',
							'show_reset'	=> true,
							'show_alpha'	=> true,
							'connections'	=> array( 'color' ),
							'preview'	=> array(
								'type'		=> 'css',
								'selector'	=> '.pp-fluent-form-content .fluentform .ff_submit_btn_wrapper button:hover',
								'property'	=> 'background-color',
							),
						),
					),
				),
				'button_border_settings' => array(
					'title'             => __( 'Border', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'            => array( // Section Fields
						'button_border'	=> array(
							'type'          => 'border',
							'label'         => __( 'Border', 'bb-powerpack' ),
							'responsive'	=> true,
							'preview'   	=> array(
								'type'  		=> 'css',
								'selector'  	=> '.pp-fluent-form-content .fluentform .ff_submit_btn_wrapper button',
								'property'  	=> 'border',
							),
						),
					),
				),
				'button_size_settings'   => array(
					'title'             => __( 'Size & Alignment', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'            => array( // Section Fields
						'button_width'  => array(
							'type'      => 'pp-switch',
							'label'     => __( 'Full Width', 'bb-powerpack' ),
							'default'   => 'false',
							'options'   => array(
								'true'  => __( 'Yes', 'bb-powerpack' ),
								'false' => __( 'No', 'bb-powerpack' ),
							),
							'toggle'    => array(
								'false' => array(
									'fields'    => array( 'button_alignment' ),
								),
							),
						),
						'button_alignment'  => array(
							'type'          => 'align',
							'label'         => __( 'Alignment', 'bb-powerpack' ),
							'default'       => 'left',
							'responsive'	=> true,
							'preview'            => array(
								'type'           => 'css',
								'selector'       => '.pp-fluent-form-content .fluentform .ff_submit_btn_wrapper button',
								'property'       => 'float',
							),
						),
					),
				),
				'button_corners_padding' => array( // Section
					'title'             => __( 'Padding', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'            => array( // Section Fields
						'button_padding'    => array(
							'type'				=> 'dimension',
							'label'				=> __( 'Padding', 'bb-powerpack' ),
							'default'			=> '',
							'units'				=> array( 'px' ),
							'slider'			=> true,
							'responsive'		=> true,
							'preview'			=> array(
								'type'				=> 'css',
								'selector'			=> '.pp-fluent-form-content .fluentform .ff_submit_btn_wrapper button',
								'property'			=> 'padding',
								'unit'				=> 'px',
							),
						),
					),
				),
			),
		),
		'Messages_style'  => array(
			'title' => __( 'Messages', 'bb-powerpack' ),
			'sections'  => array(
				'form_error_styling'    => array( // Section
					'title'             => __( 'Errors', 'bb-powerpack' ), // Section Title
					'fields'            => array( // Section Fields
						'error_message'   => array(
							'type'             => 'pp-switch',
							'label'            => __( 'Error Messages', 'bb-powerpack' ),
							'default'          => 'block',
							'options'          => array(
								'block'        => __( 'Show', 'bb-powerpack' ),
								'none'         => __( 'Hide', 'bb-powerpack' ),
							),
							'toggle'    => array(
								'block' => array(
									'fields'    => array( 'validation_message_color' ),
									'sections'  => array( 'errors_typography' ),
								),
							),
						),
						'error_message_color'    => array(
							'type'                    => 'color',
							'label'                   => __( 'Text Color', 'bb-powerpack' ),
							'default'                 => '',
							'connections'				=> array( 'color' ),
							'preview'                 => array(
								'type'                => 'css',
								'selector'            => '.pp-fluent-form-content .ff-el-is-error .error',
								'property'            => 'color',
							),
						),
						'error_input_field_border_color'    => array(
							'type'                    => 'color',
							'label'                   => __( 'Border Color', 'bb-powerpack' ),
							'default'                 => '',
							'connections'				=> array( 'color' ),
							'preview'                 => array(
								'type'                => 'css',
								'selector'            => '.pp-fluent-form-content .ff-el-is-error .ff-el-form-control',
								'property'            => 'color',
							),
						),
						'error_input_field_border_width'    => array(
							'type'				=> 'unit',
							'label'				=> __( 'Border Width', 'bb-powerpack' ),
							'default'			=> '1',
							'slider'			=> true,
							'preview'			=> array(
								'type'				=> 'css',
								'selector'			=> '.pp-fluent-form-content .ff-el-is-error .ff-el-form-control',
								'property'			=> 'border-width',
							),
						),
					),
				),
				'form_success_styling'    => array( // Section
					'title'             => __( 'Success Message', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'            => array( // Section Fields
						'success_message_bg_color'    => array(
							'type'                         => 'color',
							'label'                        => __( 'Background Color', 'bb-powerpack' ),
							'default'                      => '',
							'show_reset'                   => true,
							'show_alpha'				   => true,
							'connections'					=> array( 'color' ),
							'preview'                      => array(
								'type'                     => 'css',
								'selector'                 => '.pp-fluent-form-content .ff-message-success',
								'property'                 => 'background-color',
							),
						),
						'success_message_color'    => array(
							'type'                         => 'color',
							'label'                        => __( 'Color', 'bb-powerpack' ),
							'default'                      => '',
							'connections'					=> array( 'color' ),
							'preview'                      => array(
								'type'                     => 'css',
								'selector'                 => '.pp-fluent-form-content .ff-message-success',
								'property'                 => 'color',
							),
						),
						'success_message_border'	=> array(
							'type'          => 'border',
							'label'         => __( 'Border', 'bb-powerpack' ),
							'responsive'	=> true,
							'preview'   	=> array(
								'type'  		=> 'css',
								'selector'  	=> '.pp-fluent-form-content .ff-message-success',
								'property'  	=> 'border',
							),
						),
					),
				),
			),
		),
		'form_typography' => array( // Tab
			'title'         => __( 'Typography', 'bb-powerpack' ), // Tab title
			'sections'      => array( // Tab Sections
				'title_typography'         => array( // Section
					'title'         => __( 'Title', 'bb-powerpack' ), // Section Title
					'fields'        => array( // Section Fields
						'title_tag'    => array(
							'type'          => 'select',
							'label'         => __('Tag', 'bb-powerpack'),
							'default'       => 'h3',
							'options'       => array(
								'h1'            => 'H1',
								'h2'            => 'H2',
								'h3'            => 'H3',
								'h4'            => 'H4',
								'h5'            => 'H5',
								'h6'            => 'H6',
							)
						),
						'title_typography'	=> array(
							'type'			=> 'typography',
							'label'			=> __( 'Typography', 'bb-powerpack' ),
							'responsive'  	=> true,
							'preview'		=> array(
								'type'			=> 'css',
								'selector'		=> '.pp-fluent-form-content .pp-form-title',
							),
						),
					),
				),
				'description_typography'   => array(
					'title' => __( 'Description', 'bb-powerpack' ),
					'collapsed'	=> true,
					'fields'    => array(
						'description_typography'	=> array(
							'type'			=> 'typography',
							'label'			=> __( 'Typography', 'bb-powerpack' ),
							'responsive'  	=> true,
							'preview'		=> array(
								'type'			=> 'css',
								'selector'		=> '.pp-fluent-form-content .pp-form-description',
							),
						),
					),
				),
				'label_typography'         => array( // Section
					'title'         => __( 'Label', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'        => array( // Section Fields
						'label_typography'	=> array(
							'type'			=> 'typography',
							'label'			=> __( 'Typography', 'bb-powerpack' ),
							'responsive'  	=> true,
							'preview'		=> array(
								'type'			=> 'css',
								'selector'		=> '.pp-fluent-form-content .ff-el-input--label label',
							),
						),
					),
				),
				'radio_check_typography'   => array( // Section
					'title'     => __( 'Radio & Checkbox Label', 'bb-powerpack' ), // Section Title
					'collapsed' => true,
					'fields'    => array( // Section Fields
						'radio_check_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-fluent-form-content .fluentform .ff-el-form-check-label',
							),
						),
					),
				),
				'input_typography'         => array( // Section
					'title'         => __( 'Input', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'        => array( // Section Fields
						'input_typography'	=> array(
							'type'			=> 'typography',
							'label'			=> __( 'Typography', 'bb-powerpack' ),
							'responsive'  	=> true,
							'preview'		=> array(
								'type'			=> 'css',
								'selector'		=> '.pp-fluent-form-content .fluentform .ff-el-form-control',
							),
						),
					),
				),
				'button_typography'        => array( // Section
					'title'         => __( 'Button', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'        => array( // Section Fields
						'button_typography'	=> array(
							'type'			=> 'typography',
							'label'			=> __( 'Typography', 'bb-powerpack' ),
							'responsive'  	=> true,
							'preview'		=> array(
								'type'			=> 'css',
								'selector'		=> '.pp-fluent-form-content .fluentform .ff_submit_btn_wrapper button',
							),
						),
					),
				),
				'section_field_typography' => array( // Section
					'title'         => __( 'Section Field', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'        => array(
						'section_title_typography'	=> array(
							'type'        	   => 'typography',
							'label'       	   => __( 'Title Typography', 'bb-powerpack' ),
							'responsive'  	   => true,
							'preview'          => array(
								'type'         		=> 'css',
								'selector' 		    => '.pp-fluent-form-content .ff-el-section-break .ff-el-section-title',
							),
						),
						'section_title_color'  => array(
							'type'                  => 'color',
							'label'                 => __( 'Title Color', 'bb-powerpack' ),
							'default'               => '',
							'show_reset'            => true,
							'connections'			=> array( 'color' ),
							'preview'               => array(
								'type'              => 'css',
								'selector'          => '.pp-fluent-form-content .ff-el-section-break .ff-el-section-title',
								'property'          => 'color',
							),
						),
						'section_description_typography'	=> array(
							'type'        	   => 'typography',
							'label'       	   => __( 'Description Typography', 'bb-powerpack' ),
							'responsive'  	   => true,
							'preview'          => array(
								'type'         		=> 'css',
								'selector' 		    => '.pp-fluent-form-content .ff-el-section-break',
							),
						),
						'section_description_color'  => array(
							'type'                  => 'color',
							'label'                 => __( 'Description Color', 'bb-powerpack' ),
							'default'               => '',
							'show_reset'            => true,
							'connections'			=> array( 'color' ),
							'preview'               => array(
								'type'              => 'css',
								'selector'          => '.pp-fluent-form-content .ff-el-section-break',
								'property'          => 'color',
							),
						),
					),
				),
				'errors_typography'        => array( // Section
					'title'         => __( 'Error', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'        => array( // Section Fields
						'error_typography'	=> array(
							'type'        	   => 'typography',
							'label'       	   => __( 'Typography', 'bb-powerpack' ),
							'responsive'  	   => true,
							'preview'          => array(
								'type'         		=> 'css',
								'selector' 		    => '.pp-fluent-form-content .ff-el-section-break .ff-el-section-title',
							),
						),
					),
				),
				'form_success_styling'     => array( // Section
					'title'             => __( 'Success Message', 'bb-powerpack' ), // Section Title
					'collapsed'		=> true,
					'fields'            => array( // Section Fields
						'success_message_typography'	=> array(
							'type'        	   => 'typography',
							'label'       	   => __( 'Typography', 'bb-powerpack' ),
							'responsive'  	   => true,
							'preview'          => array(
								'type'         		=> 'css',
								'selector' 		    => '.pp-fluent-form-content .ff-message-success',
							),
						),
					),
				),
			),
		),
	)
);

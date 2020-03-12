<?php

/**
 * @class PPFileDownloadModule
 */
class PPFileDownloadModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'          	=> __( 'File Download', 'bb-powerpack' ),
				'description'   	=> __( 'A module for custom file download.', 'bb-powerpack' ),
				'group'         => pp_get_modules_group(),
				'category'		=> pp_get_modules_cat( 'content' ),
				'dir'           => BB_POWERPACK_DIR . 'modules/pp-file-download/',
				'url'           => BB_POWERPACK_URL . 'modules/pp-file-download/',
				'editor_export' => true, // Defaults to true and can be omitted.
				'enabled'       => true, // Defaults to true and can be omitted.
				'partial_refresh'   => true,
			)
		);
	}

	/**
	 * @method get_classname
	 */
	public function get_classname() {
		$classname = 'pp-button-wrap pp-file-download';

		if ( ! empty( $this->settings->width ) ) {
			$classname .= ' pp-button-width-' . $this->settings->width;
		}
		if ( ! empty( $this->settings->icon ) ) {
			$classname .= ' pp-button-has-icon';
		}
		if ( empty( $settings->text ) ) {
			$classname .= ' no-text';
		}

		return $classname;
	}

	/**
	 * Returns button link rel based on settings
	 * @since 2.6.8
	 */
	public function get_rel() {
		$rel = array();
		if ( '_blank' == $this->settings->link_target ) {
			$rel[] = 'noopener';
		}
		if ( isset( $this->settings->link_nofollow ) && 'yes' == $this->settings->link_nofollow ) {
			$rel[] = 'nofollow';
		}
		$rel = implode( ' ', $rel );
		if ( $rel ) {
			$rel = ' rel="' . $rel . '" ';
		}
		return $rel;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'PPFileDownloadModule', array(
	'general'       => array(
		'title'         => __( 'General', 'bb-powerpack' ),
		'sections'      => array(
			'style'         => array(
				'title'         => __( 'Button Type', 'bb-powerpack' ),
				'fields'        => array(
					'style'         => array(
						'type'          => 'pp-switch',
						'label'         => __( 'Type', 'bb-powerpack' ),
						'default'       => 'flat',
						'options'       => array(
							'flat'          => __( 'Flat', 'bb-powerpack' ),
							'gradient'      => __( 'Gradient', 'bb-powerpack' ),
						),
						'toggle'		=> array(
							'flat'		=> array(
								'fields'	=> array( 'bg_color', 'bg_hover_color' ),
								'sections'	=> array( 'effects' ),
							),
							'gradient'		=> array(
								'fields'	=> array( 'bg_color_primary', 'bg_color_secondary', 'gradient_hover' ),
							),
						),
					),
				),
			),
			'file'          => array(
				'title'         => __( 'File', 'bb-powerpack' ),
				'fields'        => array(
					'file'          => array(
						'type'          => 'pp-media-uploader',
						'label'         => '',
						'preview'       => array(
							'type'          => 'none',
						),
					),
				),
			),
			'general'       => array(
				'title'         => __( 'Content', 'bb-powerpack' ),
				'fields'        => array(
					'text'          => array(
						'type'          => 'text',
						'label'         => __( 'Text', 'bb-powerpack' ),
						'default'       => __( 'Download', 'bb-powerpack' ),
						'connections'   => array( 'string' ),
						'preview'         => array(
							'type'            => 'text',
							'selector'        => '.pp-button-text',
						),
					),
					'display_icon'	=> array(
						'type'		=> 'pp-switch',
						'label'		=> __( 'Display Icon', 'bb-powerpack' ),
						'default'	=> 'no',
						'options'	=> array(
							'yes'	=> __( 'Yes', 'bb-powerpack' ),
							'no'	=> __( 'No', 'bb-powerpack' ),
						),
						'toggle'	=> array(
							'yes'		=> array(
								'fields'	=> array( 'icon', 'icon_size', 'icon_position' )
							),
						),
						'preview'	=> array(
							'type'		=> 'none',
						),
					),
					'icon'          => array(
						'type'          => 'icon',
						'label'         => __( 'Icon', 'bb-powerpack' ),
						'show_remove'   => true,
					),
					'icon_size'		=> array(
						'type'          => 'unit',
						'label'         => __( 'Icon Size', 'bb-powerpack' ),
						'default'		=> 16,
						'units'			=> array( 'px' ),
						'slider'		=> true,
						'responsive'	=> true,
						'preview'		=> array(
							'type'		=> 'css',
							'selector'	=> '.pp-button .pp-button-icon',
							'property'	=> 'font-size',
							'unit'		=> 'px',
						),
					),
					'icon_position' => array(
						'type'          => 'pp-switch',
						'label'         => __( 'Icon Position', 'bb-powerpack' ),
						'default'       => 'before',
						'options'       => array(
							'before'        => __('Before Text', 'bb-powerpack'),
							'after'         => __('After Text', 'bb-powerpack')
						)
					),
					'icon_spacing' => array(
						'type'          => 'unit',
						'label'         => __('Spacing', 'bb-powerpack'),
						'default'		=> 10,
						'units'			=> array('px'),
						'slider'		=> true,
					)
				)
			),
			'effects'		=> array(
				'title'		=> __( 'Transition', 'bb-powerpack' ),
				'fields'	=> array(
					'button_effect'   => array(
						'type'  => 'select',
						'label' => __( 'Hover Transition', 'bb-powerpack' ),
						'default'   => 'fade',
						'options'   => array(
							'none'  => __( 'None', 'bb-powerpack' ),
							'fade'  => __( 'Fade', 'bb-powerpack' ),
							'sweep_top'  => __( 'Sweep To Top', 'bb-powerpack' ),
							'sweep_bottom'  => __( 'Sweep To Bottom', 'bb-powerpack' ),
							'sweep_left'  => __( 'Sweep To Left', 'bb-powerpack' ),
							'sweep_right'  => __( 'Sweep To Right', 'bb-powerpack' ),
							'bounce_top'  => __( 'Bounce To Top', 'bb-powerpack' ),
							'bounce_bottom'  => __( 'Bounce To Bottom', 'bb-powerpack' ),
							'bounce_left'  => __( 'Bounce To Left', 'bb-powerpack' ),
							'bounce_right'  => __( 'Bounce To Right', 'bb-powerpack' ),
							'radial_in'  => __( 'Radial In', 'bb-powerpack' ),
							'radial_out'  => __( 'Radial Out', 'bb-powerpack' ),
							'rectangle_in'  => __( 'Rectangle In', 'bb-powerpack' ),
							'rectangle_out'  => __( 'Rectangle Out', 'bb-powerpack' ),
							'shutter_in_horizontal'  => __( 'Shutter In Horizontal', 'bb-powerpack' ),
							'shutter_out_horizontal'  => __( 'Shutter Out Horizontal', 'bb-powerpack' ),
							'shutter_in_vertical'  => __( 'Shutter In Vertical', 'bb-powerpack' ),
							'shutter_out_vertical'  => __( 'Shutter Out Vertical', 'bb-powerpack' ),
							'shutter_in_diagonal'  => __( 'Shutter In Diagonal', 'bb-powerpack' ),
							'shutter_out_diagonal'  => __( 'Shutter Out Diagonal', 'bb-powerpack' ),
						),
					),
					'button_effect_duration'  => array(
						'type'  => 'text',
						'label' => __( 'Transition Duration', 'bb-powerpack' ),
						'size'  => 5,
						'maxlength' => 4,
						'default'   => 500,
						'description'   => __( 'ms', 'bb-powerpack' ),
					),
				),
			),
		),
	),
	'style'         => array(
		'title'         => __( 'Style', 'bb-powerpack' ),
		'sections'      => array(
			'colors'        => array(
				'title'         => __( 'Colors', 'bb-powerpack' ),
				'fields'        => array(
					'bg_color'      => array(
						'type'          => 'color',
						'label'         => __( 'Background Color', 'bb-powerpack' ),
						'default'		=> 'd6d6d6',
						'show_reset'    => true,
						'show_alpha'	=> true,
						'connections'	=> array( 'color' ),
						'preview'		=> array(
							'type'		=> 'css',
							'selector'	=> '.pp-button-wrap a.pp-button',
							'property'	=> 'background',
						),
					),
					'bg_hover_color'	=> array(
						'type'          => 'color',
						'label'         => __( 'Background Hover Color', 'bb-powerpack' ),
						'default'		=> '333333',
						'show_reset'    => true,
						'show_alpha'	=> true,
						'connections'	=> array( 'color' ),
						'preview'		=> array(
							'type'		=> 'none',
						),
					),
					'bg_color_primary'	=> array(
						'type'          => 'color',
						'label'         => __( 'Gradient Color Primary', 'bb-powerpack' ),
						'show_reset'    => true,
						'connections'	=> array( 'color' ),
					),
					'bg_color_secondary'	=> array(
						'type'          => 'color',
						'label'         => __( 'Gradient Color Secondary', 'bb-powerpack' ),
						'show_reset'    => true,
						'connections'	=> array( 'color' ),
					),
					'gradient_hover'	=> array(
						'type'			=> 'select',
						'label'			=> __( 'Hover Effect', 'bb-powerpack' ),
						'default'		=> 'reverse',
						'options'		=> array(
							'reverse'	=> __( 'Reverse', 'bb-powerpack' ),
							'primary'	=> __( 'Fill Primary', 'bb-powerpack' ),
							'secondary'	=> __( 'Fill Secondary', 'bb-powerpack' ),
						),
					),
					'text_color'    => array(
						'type'          => 'color',
						'label'         => __( 'Text Color', 'bb-powerpack' ),
						'default'		=> '000000',
						'show_reset'    => true,
						'connections'	=> array( 'color' ),
						'preview'		=> array(
							'type'		=> 'css',
							'selector'	=> '.pp-button-wrap a.pp-button span',
							'property'	=> 'background',
						),
					),
					'text_hover_color'    => array(
						'type'          => 'color',
						'label'         => __( 'Text Hover Color', 'bb-powerpack' ),
						'default'		=> 'ffffff',
						'show_reset'    => true,
						'connections'	=> array( 'color' ),
						'preview'		=> array(
							'type'			=> 'none',
						),
					),
				),
			),
			'formatting'    => array(
				'title'         => __( 'Structure', 'bb-powerpack' ),
				'fields'        => array(
					'width'         => array(
						'type'          => 'pp-switch',
						'label'         => __( 'Width', 'bb-powerpack' ),
						'default'       => 'auto',
						'options'       => array(
							'auto'          => _x( 'Auto', 'Width.', 'bb-powerpack' ),
							'full'          => __( 'Full Width', 'bb-powerpack' ),
							'custom'        => __( 'Custom', 'bb-powerpack' ),
						),
						'toggle'        => array(
							'auto'          => array(
								'fields'        => array( 'align' ),
							),
							'custom'        => array(
								'fields'        => array( 'align', 'custom_width' ),
							),
						),
					),
					'custom_width'  => array(
						'type'          => 'unit',
						'label'         => __( 'Custom Width', 'bb-powerpack' ),
						'default'       => '200',
						'responsive'	=> true,
						'slider'		=> array(
							'px'			=> array(
								'min'			=> 0,
								'max'			=> 1000,
								'step'			=> 10,
							),
						),
						'units'   		=> array( 'px', 'vw', '%' ),
						'preview'		=> array(
							'type'		=> 'css',
							'selector'	=> '.pp-button-wrap a.pp-button',
							'property'	=> 'width',
							'unit'		=> 'px',
						),
					),
					'align'         => array(
						'type'          => 'align',
						'label'         => __( 'Alignment', 'bb-powerpack' ),
						'default'       => 'left',
						'responsive'	=> true,
					),
					'padding'       => array(
						'type'          => 'dimension',
						'label'         => __( 'Padding', 'bb-powerpack' ),
						'default'		=> '10',
						'responsive'	=> true,
						'slider'		=> true,
						'units'   		=> array( 'px' ),
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-button-wrap a.pp-button',
							'property'		=> 'padding',
							'unit'			=> 'px',
						),
					),
				),
			),
			'border'       => array(
				'title'         => __( 'Border', 'bb-powerpack' ),
				'fields'        => array(
					'border' 		=> array(
						'type'          => 'border',
						'label'         => __( 'Border', 'bb-powerpack' ),
						'responsive'	=> true,
						'preview'       => array(
							'type'          => 'css',
							'selector'		=> '.pp-button-wrap a.pp-button',
							'important'		=> true,
						),
					),
					'border_hover_color' => array(
						'type'          => 'color',
						'label'         => __( 'Border Hover Color', 'bb-powerpack' ),
						'default'       => '',
						'show_reset'    => true,
						'show_alpha'    => true,
						'connections'	=> array( 'color' ),
						'preview'       => array(
							'type'          => 'none',
						),
					),
				),
			),
		),
	),
	'typography'	=> array(
		'title'		=> __( 'Typography', 'bb-powerpack' ),
		'sections'	=> array(
			'text_fonts'	=> array(
				'title'		=> '',
				'fields'	=> array(
					'typography'    => array(
						'type'        	=> 'typography',
						'label'       	=> __( 'Typography', 'bb-powerpack' ),
						'responsive'  	=> true,
						'preview'		=> array(
							'type'			=> 'css',
							'selector'		=> '.pp-button-wrap a.pp-button',
						),
					),
				),
			),
		),
	),
));

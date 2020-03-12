<?php

/**
 * @class PPSocialShareModule
 */
class PPSocialShareModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Social Share', 'bb-powerpack' ),
				'description'     => __( 'Display a group of linked social share.', 'bb-powerpack' ),
				'group'           => pp_get_modules_group(),
				'category'		  => pp_get_modules_cat( 'content' ),
				'dir'             => BB_POWERPACK_DIR . 'modules/pp-social-share/',
				'url'             => BB_POWERPACK_URL . 'modules/pp-social-share/',
				'editor_export'   => true,
				'partial_refresh' => true,
			)
		);

		$this->add_css( BB_POWERPACK()->fa_css );
	}

	public static function social_share_options() {

		$options = array(
			'facebook'    		=> __( 'Facebook', 'bb-powerpack' ),
			'twitter'     		=> __( 'Twitter', 'bb-powerpack' ),
			'linkedin'    		=> __( 'LinkedIn', 'bb-powerpack' ),
			'pinterest'   		=> __( 'Pinterest', 'bb-powerpack' ),
			'reddit'      		=> __( 'Reddit', 'bb-powerpack' ),
			'vk'       	  		=> __( 'VK', 'bb-powerpack' ),
			'odnoklassniki'		=> __( 'OK', 'bb-powerpack' ),
			'delicious'        	=> __( 'Delicious', 'bb-powerpack' ),
			'digg'        		=> __( 'Digg', 'bb-powerpack' ),
			'skype'				=> __( 'Skype', 'bb-powerpack' ),
			'stumbleupon' 		=> __( 'StumbleUpon (Mix)', 'bb-powerpack' ),
			'telegram'			=> __( 'Telegram', 'bb-powerpack' ),
			'pocket'			=> __( 'Pocket', 'bb-powerpack' ),
			'xing'				=> __( 'Xing', 'bb-powerpack' ),
			'whatsapp'			=> __( 'WhatsApp', 'bb-powerpack' ),
			'email'       		=> __( 'Email', 'bb-powerpack' ),
			'print'       		=> __( 'Print', 'bb-powerpack' ),
			'fb-messenger'      => __( 'Facebook Messenger', 'bb-powerpack' ),
			'buffer'      		=> __( 'Buffer', 'bb-powerpack' ),
		);

		ksort( $options );

		return $options;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'PPSocialShareModule',
	array(
		'icons'         => array(
			'title'         => __( 'Icons', 'bb-powerpack' ),
			'sections'      => array(
				'general'       => array(
					'title'         => '',
					'fields'        => array(
						'social_icons'         => array(
							'type'          => 'form',
							'label'         => __( 'Social Share', 'bb-powerpack' ),
							'form'          => 'pp_social_share_form', // ID from registered form below
							'preview_text'  => 'social_share_type', // Name of a field to use for the preview text
							'multiple'      => true,
						),
					),
				),
				'settings'	=> array(
					'title'		=> __( 'General', 'bb-powerpack' ),
					'fields'	=> array(
						'view'		=> array(
							'type'    => 'select',
							'label'   => __( 'View', 'bb-powerpack' ),
							'default' => 'icon-text',
							'options' => array(
								'icon-text'	=> __( 'Icon & Text', 'bb-powerpack' ),
								'icon' 		=> __( 'Icon', 'bb-powerpack' ),
								'text' 		=> __( 'Text', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'icon-text'  => array(
									'fields' => array( 'icon_size' ),
								),
								'icon'  => array(
									'fields' => array( 'icon_size' ),
								),
								'text'  => array(
									'fields' => array( 'text_padding' ),
								),
							),
						),
						'skin'		=> array(
							'type'    => 'select',
							'label'   => __( 'Skin', 'bb-powerpack' ),
							'default' => 'gradient',
							'options' => array(
								'gradient'		=> __( 'Gradient', 'bb-powerpack' ),
								'minimal' 		=> __( 'Minimal', 'bb-powerpack' ),
								'framed' 		=> __( 'Framed', 'bb-powerpack' ),
								'boxed' 		=> __( 'Boxed Icon', 'bb-powerpack' ),
								'flat' 			=> __( 'Flat', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'framed'  => array(
									'fields' => array( 'border_width' ),
								),
								'boxed'  => array(
									'fields' => array( 'border_width' ),
								),
							),
						),
						'shape'		=> array(
							'type'    => 'select',
							'label'   => __( 'Shape', 'bb-powerpack' ),
							'default' => 'icon-text',
							'options' => array(
								'square'		=> __( 'Square', 'bb-powerpack' ),
								'rounded' 		=> __( 'Rounded', 'bb-powerpack' ),
								'circle' 		=> __( 'Circle', 'bb-powerpack' ),
							),
						),
						'columns'		=> array(
							'type'    => 'select',
							'label'   => __( 'Columns', 'bb-powerpack' ),
							'default' => '0',
							'options' => array(
								'0'			=> __( 'Auto', 'bb-powerpack' ),
								'1' 		=> __( '1', 'bb-powerpack' ),
								'2' 		=> __( '2', 'bb-powerpack' ),
								'3' 		=> __( '3', 'bb-powerpack' ),
								'4' 		=> __( '4', 'bb-powerpack' ),
								'5' 		=> __( '5', 'bb-powerpack' ),
								'6' 		=> __( '6', 'bb-powerpack' ),
							),
							'responsive'	=> true,
						),
						'alignment'       => array(
							'type'    => 'select',
							'label'   => __( 'Alignment', 'bb-powerpack' ),
							'default' => 'left',
							'options' => array(
								'left'			=> __( 'Left', 'bb-powerpack' ),
								'center' 		=> __( 'Center', 'bb-powerpack' ),
								'right' 		=> __( 'Right', 'bb-powerpack' ),
								'justify' 		=> __( 'Justify', 'bb-powerpack' ),
							),
							'responsive' => true,
						),
						'share_url_type'		=> array(
							'type'    => 'select',
							'label'   => __( 'Target URL', 'bb-powerpack' ),
							'default' => 'icon-text',
							'options' => array(
								'current_page'		=> __( 'Current Page', 'bb-powerpack' ),
								'custom' 			=> __( 'Custom', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'custom'  => array(
									'fields' => array( 'share_url' ),
								),
							),
						),
						'share_url'			=> array(
							'type'          => 'link',
							'label'         => __( 'Link', 'bb-powerpack' ),
							'default'       => '',
							'placeholder'	=> __( 'https://your-link.com', 'bb-powerpack' ),
							'connections'   => array( 'url' ),
							'show_target'	=> true,
							'show_nofollow'	=> true,
						),
					),
				),
			),
		),
		'style'         => array( // Tab
			'title'         => __( 'Style', 'bb-powerpack' ), // Tab title
			'sections'      => array( // Tab Sections
				'structure'     => array( // Section
					'title'         => __( 'Structure', 'bb-powerpack' ), // Section Title
					'fields'        => array( // Section Fields
						'column_gap'          => array(
							'type'          => 'unit',
							'label'         => __( 'Column Spacing', 'bb-powerpack' ),
							'default'       => '10',
							'units'   		=> array( 'px' ),
							'slider'		=> true,
							'responsive'	=> true,
						),
						'row_gap'          => array(
							'type'          => 'unit',
							'label'         => __( 'Rows Spacing', 'bb-powerpack' ),
							'default'       => '10',
							'units'   		=> array( 'px' ),
							'slider'		=> true,
							'responsive'	=> true,
						),
						'icon_size'		=> array(
							'type'          => 'unit',
							'label'         => __( 'Icon Size', 'bb-powerpack' ),
							'default'       => '',
							'units'   		=> array( 'px' ),
							'slider'		=> array(
								'min'			=> '0',
								'max'			=> '100',
							),
							'responsive'	=> true,
						),
						'button_height'		=> array(
							'type'          => 'unit',
							'label'         => __( 'Button Height', 'bb-powerpack' ),
							'default'       => '',
							'units'   		=> array( 'px' ),
							'slider'		=> array(
								'min'			=> '0',
								'max'			=> '100',
							),
							'responsive'	=> true,
						),
						'border_width'	=> array(
							'type'          => 'unit',
							'label'         => __( 'Border Width', 'bb-powerpack' ),
							'default'       => '2',
							'units'   		=> array( 'px' ),
							'slider'		=> array(
								'min'			=> '1',
								'max'			=> '20',
							),
							'responsive'	=> true,
						),
						'color_source'		=> array(
							'type'    => 'select',
							'label'   => __( 'Color', 'bb-powerpack' ),
							'default' => 'official',
							'options' => array(
								'official'		=> __( 'Official', 'bb-powerpack' ),
								'custom' 		=> __( 'Custom', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'custom'  => array(
									'sections' => array( 'colors' ),
								),
							),
						),
					),
				),
				'colors'        => array( // Section
					'title'         => __( 'Colors', 'bb-powerpack' ), // Section Title
					'collapsed'			=> true,
					'fields'        => array( // Section Fields
						'primary_color'         => array(
							'type'          => 'color',
							'label'         => __( 'Primary Color', 'bb-powerpack' ),
							'show_reset'    => true,
							'connections'	=> array( 'color' ),
						),
						'secondary_color' => array(
							'type'          => 'color',
							'label'         => __( 'Secondary Color', 'bb-powerpack' ),
							'show_reset'    => true,
							'connections'	=> array( 'color' ),
							'preview'       => array(
								'type'          => 'none',
							),
						),
						'primary_hover_color'      => array(
							'type'          => 'color',
							'label'         => __( 'Primary Hover Color', 'bb-powerpack' ),
							'show_reset'    => true,
							'show_alpha'	=> true,
							'connections'	=> array( 'color' ),
						),
						'secondary_hover_color' => array(
							'type'          => 'color',
							'label'         => __( 'Secondary Hover Color', 'bb-powerpack' ),
							'show_reset'    => true,
							'show_alpha'	=> true,
							'connections'	=> array( 'color' ),
							'preview'       => array(
								'type'          => 'none',
							),
						),
					),
				),
				'title_settings'	=> array(
					'title'		=> __( 'Text', 'bb-powerpack' ),
					'collapsed'			=> true,
					'fields'	=> array(
						'title_typography'	=> array(
							'type'        	   => 'typography',
							'label'       	   => __( 'Typography', 'bb-powerpack' ),
							'responsive'  	   => true,
							'preview'          => array(
								'type'         		=> 'css',
								'selector' 		    => '.pp-share-button-title',
							),
						),
						'title_padding_left'          => array(
							'type'          => 'unit',
							'label'         => __( 'Padding Left', 'bb-powerpack' ),
							'default'       => '',
							'units'   		=> array( 'px' ),
							'slider'		=> true,
							'responsive'	=> true,
						),
						'title_padding_right'          => array(
							'type'          => 'unit',
							'label'         => __( 'Padding Right', 'bb-powerpack' ),
							'default'       => '',
							'units'   		=> array( 'px' ),
							'slider'		=> true,
							'responsive'	=> true,
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
	'pp_social_share_form',
	array(
		'title' => __( 'Add Social Share', 'bb-powerpack' ),
		'tabs'  => array(
			'general'       => array( // Tab
				'title'         => __( 'General', 'bb-powerpack' ), // Tab title
				'sections'      => array( // Tab Sections
					'general'       => array( // Section
						'title'         => '', // Section Title
						'fields'        => array( // Section Fields
							'social_share_type' => array(
								'type'        => 'select',
								'label'       => __( 'Social Share Type', 'bb-powerpack' ),
								'default'     => 'facebook',
								'options'     => PPSocialShareModule::social_share_options(),
								'description' => pp_get_fb_module_desc(),
								'toggle'      => array(
									'pinterest' => array(
										'fields' => array( 'fallback_image' ),
									),
								),
							),
							'fallback_image'	=> array(
								'type'			=> 'photo',
								'label'			=> __( 'Fallback Image', 'bb-powerpack' ),
								'connections'   => array( 'photo' ),
							),
							'text'			=> array(
								'type'          => 'text',
								'label'         => __( 'Custom Label', 'bb-powerpack' ),
								'default'       => '',
								'connections'   => array( 'string', 'html', 'url' ),
							),
							'custom_icon'	=> array(
								'type'			=> 'icon',
								'label'         => __( 'Custom Icon', 'bb-powerpack' ),
								'show_remove'   => true,
							),
						),
					),
				),
			),
		),
	)
);

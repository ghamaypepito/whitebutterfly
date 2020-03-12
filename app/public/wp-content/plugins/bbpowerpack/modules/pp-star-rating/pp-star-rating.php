<?php

/**
 * @class PPStarRatingModule
 */
class PPStarRatingModule extends FLBuilderModule {

	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Star Rating', 'bb-powerpack' ),
				'description'     => __( 'A module for Star Rating.', 'bb-powerpack' ),
				'group'           => pp_get_modules_group(),
				'category'        => pp_get_modules_cat( 'content' ),
				'dir'             => BB_POWERPACK_DIR . 'modules/pp-star-rating/',
				'url'             => BB_POWERPACK_URL . 'modules/pp-star-rating/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
			)
		);

		$this->add_css( BB_POWERPACK()->fa_css );
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'PPStarRatingModule',
	array(
		'star_rating_tab'  => array( // Tab
			'title'    => __( 'General', 'bb-powerpack' ), // Tab title
			'sections' => array( // Tab Sections
				'heading_section' => array(// Section
					'title'  => __( 'Star Rating', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'rating_title'  => array(
							'type'        => 'text',
							'label'       => __( 'Title', 'bb-powerpack' ),
							'class'       => '',
							'default'     => __( 'Awesome !!!', 'bb-powerpack' ),
							'connections' => array( 'string', 'html', 'url' ),
							'preview'     => array(
								'type'     => 'text',
								'selector' => '.pp-rating-title',
							),
						),
						'rating_scale'  => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Scale', 'bb-powerpack' ),
							'default' => '5',
							'options' => array(
								'5'  => __( '0-5 Stars', 'bb-powerpack' ),
								'10' => __( '0-10 Stars', 'bb-powerpack' ),
							),
						),
						'rating'        => array(
							'type'        => 'unit',
							'label'       => __( 'Rating', 'bb-powerpack' ),
							'default'     => '3',
							'connections' => array( 'string' ),
						),
						'star_style'    => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Style', 'bb-powerpack' ),
							'default' => 'solid',
							'options' => array(
								'solid'   => __( 'Solid', 'bb-powerpack' ),
								'outline' => __( 'Outline', 'bb-powerpack' ),
							),
						),
						'rating_layout' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Layout', 'bb-powerpack' ),
							'default' => 'default',
							'options' => array(
								'default' => __( 'Default', 'bb-powerpack' ),
								'inline'  => __( 'Inline', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'inline' => array(
									'fields' => array( 'title_spacing' ),
								),
							),
						),
						'star_position' => array(
							'type'    => 'select',
							'label'   => __( 'Position', 'bb-powerpack' ),
							'default' => 'bottom',
							'options' => array(
								'top'    => __( 'Star First', 'bb-powerpack' ),
								'bottom' => __( 'Title First', 'bb-powerpack' ),
							),
						),
					),
				),
			),
		),
		'title_style'      => array( // Tab
			'title'    => __( 'Style', 'bb-powerpack' ), // Tab title
			'sections' => array( // Tab Sections
				'title_style'  => array(
					'collapsed' => false,
					'title'     => __( 'Title', 'bb-powerpack' ),
					'fields'    => array(
						'title_color'   => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'default'     => '000000',
							'show_reset'  => true,
							'show_alpha'  => false,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-rating-content .pp-rating-title',
								'property' => 'color',
							),
						),
						'title_spacing' => array(
							'type'    => 'unit',
							'label'   => __( 'Spacing', 'bb-powerpack' ),
							'default' => '',
							'units'   => array( 'px' ),
							'slider'  => true,
						),
					),
				),
				'rating_style' => array(
					'collapsed' => false,
					'title'     => __( 'Rating', 'bb-powerpack' ),
					'fields'    => array(
						'rating_color'          => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'default'     => 'f0ad4e',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
						'rating_unmarked_color' => array(
							'type'        => 'color',
							'label'       => __( 'Unmarked Color', 'bb-powerpack' ),
							'default'     => 'efecdc',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
						'star_icon_size'        => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'bb-powerpack' ),
							'default' => '20',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-rating i',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'star_icon_spacing'     => array(
							'type'    => 'unit',
							'label'   => __( 'Spacing', 'bb-powerpack' ),
							'default' => '',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-rating-content .pp-rating > i',
								'property' => 'margin-right',
								'unit'     => 'px',
							),
						),
						'alignment'             => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Alignment', 'bb-powerpack' ),
							'default' => 'left',
							'options' => array(
								'left'    => __( 'Left', 'bb-powerpack' ),
								'center'  => __( 'Center', 'bb-powerpack' ),
								'right'   => __( 'Right', 'bb-powerpack' ),
								'justify' => __( 'Justify', 'bb-powerpack' ),
							),
						),
					),
				),
			),
		),
		'title_typography' => array( // Tab
			'title'    => __( 'Typography', 'bb-powerpack' ), // Tab title
			'sections' => array( // Tab Sections
				'title_style' => array(
					'collapsed' => false,
					'title'     => __( 'Title', 'bb-powerpack' ),
					'fields'    => array(
						'title_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Title', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rating-title',
							),
						),
					),
				),
			),
		),
	)
);

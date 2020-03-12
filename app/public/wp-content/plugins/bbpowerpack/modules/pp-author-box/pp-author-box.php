<?php

/**
 * @class PPAuthorBoxModule
 */
class PPAuthorBoxModule extends FLBuilderModule {

	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Author Box', 'bb-powerpack' ),
				'description'     => __( 'A module for author box.', 'bb-powerpack' ),
				'group'           => pp_get_modules_group(),
				'category'        => pp_get_modules_cat( 'content' ),
				'dir'             => BB_POWERPACK_DIR . 'modules/pp-author-box/',
				'url'             => BB_POWERPACK_URL . 'modules/pp-author-box/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
			)
		);
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'PPAuthorBoxModule',
	array(
		'general'              => array( // Tab
			'title'    => __( 'General', 'bb-powerpack' ), // Tab title
			'sections' => array( // Tab Sections
				'general'     => array(
					'title'  => '',
					'fields' => array(
						'source' => array(
							'type'    => 'select',
							'label'   => __( 'Source', 'bb-powerpack' ),
							'class'   => '',
							'default' => 'current_author',
							'options' => array(
								'current_author' => __( 'Current Author', 'bb-powerpack' ),
								'custom'         => __( 'Custom', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'custom'         => array(
									'sections' => array( 'custom_info' ),
									'fields'   => array( 'archive_url', 'link_url' ),
								),
								'current_author' => array(
									'sections' => array(''),
									'fields'   => array( 'link_to', 'link_to_target' ),
								),
							),
						),
					),
				),
				'custom_info' => array(
					'title'  => __( 'Custom Info', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'img_url'          => array(
							'type'  => 'photo',
							'label' => __( 'Picture', 'bb-powerpack' ),
							'connections'	=> array( 'photo' ),
						),

						'author_name_text' => array(
							'type'    => 'text',
							'label'   => __( 'Name', 'bb-powerpack' ),
							'default' => 'John Doe',
							'connections'	=> array( 'string' ),
							'preview' => array(
								'type'     => 'text',
								'selector' => '.pp-authorbox-author-name-span',
							),
						),
						'biography_text'   => array(
							'type'    => 'editor',
							'label'   => __( 'Biography', 'bb-powerpack' ),
							'default' => 'Excellent content writer specialized in writing for the topic like food, travelling, entertainment, movies, fun, games & sports.',
							'rows'    => '6',
							'media_buttons' => false,
							'connections'	=> array( 'string', 'html' ),
							'preview' => array(
								'type'     => 'text',
								'selector' => '.pp-authorbox-bio',
							),
						),
					),
				),
				'link_to'     => array(
					'title'  => __( 'Link', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'link_to'  => array(
							'type'    => 'select',
							'label'   => __( 'Link To', 'bb-powerpack' ),
							'help'    => __( 'Link for the Author Name & Image.', 'bb-powerpack' ),
							'default' => 'none',
							'options' => array(
								'none'          => __( 'None', 'bb-powerpack' ),
								'posts_archive' => __( 'Author Archive', 'bb-powerpack' ),
								'website'       => __( 'Website', 'bb-powerpack' ),
							),
						),
						'link_to_target' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Target Window', 'bb-powerpack' ),
							'default' => '_self',
							'options' => array(
								'_self'  => __( 'Same', 'bb-powerpack' ),
								'_blank' => __( 'New', 'bb-powerpack' ),
							),
						),
						'link_url' => array(
							'type'          => 'link',
							'label'         => __( 'URL', 'bb-powerpack' ),
							'help'          => __( 'Link for the Author Name & Image.', 'bb-powerpack' ),
							'default'       => '',
							'show_target'   => true,
							'show_nofollow' => true,
							'connections'	=> array( 'url' ),
						),
					),
				),
				'settings'    => array(
					'title'  => __( 'Appearance', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'layout'          => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Layout', 'bb-powerpack' ),
							'default' => 'left',
							'responsive' => true,
							'options' => array(
								'left'   => __( 'Left', 'bb-powerpack' ),
								'center' => __( 'Above', 'bb-powerpack' ),
								'right'  => __( 'Right', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'left'   => array(
									'fields' => array( 'img_position' ),
								),
								'center' => array(
									'fields' => array( '' ),
								),
								'right'  => array(
									'fields' => array( 'img_position' ),
								),
							),
						),
						'alignment'       => array(
							'type'       => 'align',
							'label'      => __( 'Alignment', 'bb-powerpack' ),
							'default'    => 'left',
							'responsive' => true,
						),
						'profile_picture' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Picture', 'bb-powerpack' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'bb-powerpack' ),
								'hide' => __( 'Hide', 'bb-powerpack' ),
							),
						),
						'author_name'     => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Author Name', 'bb-powerpack' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'bb-powerpack' ),
								'hide' => __( 'Hide', 'bb-powerpack' ),
							),
						),
						'biography'       => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Biography', 'bb-powerpack' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'bb-powerpack' ),
								'hide' => __( 'Hide', 'bb-powerpack' ),
							),
							'toggle'	=> array(
								'show'		=> array(
									'fields'	=> array( 'content_length' ),
								),
							),
						),
						'content_length'	=> array(
							'type'		=> 'unit',
							'label'		=> __( 'Content Length', 'bb-powerpack' ),
							'default'	=> '',
							'help'		=> __( 'Leave empty for full content.', 'bb-powerpack' ),
						),
						'archive_btn'     => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Archive Button', 'bb-powerpack' ),
							'default' => 'hide',
							'options' => array(
								'show' => __( 'Show', 'bb-powerpack' ),
								'hide' => __( 'Hide', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'show' => array(
									'sections' => array( 'archive' ),
								),
							),

						),
					),
				),
				'archive'     => array(
					'title'  => __( 'Archive Button', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'button_text' => array(
							'type'    => 'text',
							'label'   => __( 'Button Text', 'bb-powerpack' ),
							'default' => __( 'View Posts', 'bb-powerpack' ),
							'connections' => array( 'string' ),
						),
						'archive_url'      => array(
							'type'          => 'link',
							'label'         => __( 'Button URL', 'bb-powerpack' ),
							'help'          => __( 'Custom link for the button.', 'bb-powerpack' ),
							'default'       => '',
							'show_target'   => true,
							'show_nofollow' => true,
							'connections'	=> array( 'url' ),
						),
					),
				),
			),
		),
		'authorbox_style_tab'  => array( // Tab
			'title'    => __( 'Style', 'bb-powerpack' ), // Tab title
			'sections' => array( // Tab Sections
				'main_style'        => array( // Section
					'title'  => __( 'Box', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'authorbox_border'   => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-content',
							),
						),
						'authorbox_bg_color' => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'connections' => array( 'color' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-content',
								'property' => 'background-color',
							),
						),
						'box_padding'        => array(
							'type'        => 'dimension',
							'label'       => __( 'Padding', 'bb-powerpack' ),
							'default'	=> 10,
							'units' 	=> array( 'px' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-content',
								'property' => 'padding',
								'unit'     => 'px',
							),
							'responsive'  => true,
						),
					),
				),
				'img_style'         => array( // Section
					'title'  => __( 'Profile Picture', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'img_position' => array(
							'type'    => 'select',
							'label'   => __( 'Position', 'bb-powerpack' ),
							'default' => 'start',
							'options' => array(
								'start'  => __( 'Top', 'bb-powerpack' ),
								'center' => __( 'Middle', 'bb-powerpack' ),
							),

						),
						'img_size'     => array(
							'type'         => 'unit',
							'label'        => __( 'Size', 'bb-powerpack' ),
							'units'        => array( 'px', '%' ),
							'default_unit' => 'px', // Optional
							'default'      => '100',
							'responsive'   => true,
							'slider'       => array(
								'min'  => 0,
								'max'  => 1000,
								'step' => 1,
							),
						),
						'img_border'   => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-img img',
							),
						),
						'img_padding'     => array(
							'type'        => 'dimension',
							'label'       => __( 'Padding', 'bb-powerpack' ),
							'units' 	=> array( 'px' ),
							'default'	=> 10,
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-img',
								'property' => 'padding',
								'unit'     => 'px',
							),
							'responsive'  => true,
							'slider'      => true,
						),
						'img_margin'      => array(
							'type'        => 'dimension',
							'label'       => __( 'Margin', 'bb-powerpack' ),
							'units' 	=> array( 'px' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-img',
								'property' => 'margin',
								'unit'     => 'px',
							),
							'responsive'  => true,
							'slider'      => true,
						),
					),
				),
				'text_style'        => array( // Section
					'title'  => __( 'Text', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'name_text_color' => array(
							'type'       => 'color',
							'label'      => __( 'Name Color', 'bb-powerpack' ),
							'default'    => '000000',
							'show_reset' => true,
							'show_alpha' => true,
							'connections' => array( 'color' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-wrapper .pp-authorbox-author-name *',
								'property' => 'color',
							),
						),
						'bio_text_color'  => array(
							'type'       => 'color',
							'label'      => __( 'Biography Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'connections' => array( 'color' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-wrapper .pp-authorbox-bio',
								'property' => 'color',
							),
						),
						'bio_padding'     => array(
							'type'        => 'dimension',
							'label'       => __( 'Padding', 'bb-powerpack' ),
							'units' 	=> array( 'px' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-content .pp-authorbox-author',
								'property' => 'padding',
								'unit'     => 'px',
							),
							'responsive'  => true,
							'slider'      => true,
						),
						'bio_gap'         => array(
							'type'        => 'unit',
							'label'       => __( 'Spacing', 'bb-powerpack' ),
							'units' 	=> array( 'px' ),
							'default'     => '10',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-author .pp-authorbox-bio',
								'property' => 'margin-top',
								'unit'     => 'px',
							),
							'slider'      => true,
							'responsive'  => true,
						),
					),
				),
				'button_style' => array( // Section
					'title'  => __( 'Button', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'button_border'             => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-author-box-button .pp-author-archive-btn',
							),
						),
						'button_text_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => false,
							'connections' => array( 'color' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-author-box-button .pp-author-archive-btn',
								'property' => 'color',
							),
						),
						'button_bg_color'           => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'connections' => array( 'color' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-author-box-button .pp-author-archive-btn',
								'property' => 'background-color',
							),
						),
						'button_hover_text_color'   => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => false,
							'connections' => array( 'color' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-author-box-button .pp-author-archive-btn:hover',
								'property' => 'color',
							),
						),
						'button_hover_bg_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Hover Background Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'connections' => array( 'color' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-author-box-button .pp-author-archive-btn:hover',
								'property' => 'background-color',
							),
						),
						'button_hover_border_color' => array(
							'type'       => 'color',
							'label'      => __( 'Hover Border Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => false,
							'connections' => array( 'color' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-author-box-button .pp-author-archive-btn:hover',
								'property' => 'border-color',
							),
						),
						'button_padding'            => array(
							'type'        => 'dimension',
							'label'       => __( 'Padding', 'bb-powerpack' ),
							'units' 	=> array( 'px' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-author-box-button .pp-author-archive-btn',
								'property' => 'padding',
								'unit'     => 'px',
							),
							'responsive'  => true,
							'slider'      => true,
						),

						'button_margin'   => array(
							'type'        => 'dimension',
							'label'       => __( 'Margin', 'bb-powerpack' ),
							'units' 	=> array( 'px' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-author-box-button .pp-author-archive-btn',
								'property' => 'margin',
								'unit'     => 'px',
							),
							'responsive'  => true,
							'slider'      => true,
						),
						// 'archive_gap'                    => array(
						// 	'type'        => 'unit',
						// 	'label'       => __( 'Spacing', 'bb-powerpack' ),
						// 	'description' => 'px',
						// 	'default'     => '10',
						// 	'slider'      => true,
						// ),
					),
				),

			),
		),
		'authorbox_typography' => array( // Tab
			'title'    => __( 'Typography', 'bb-powerpack' ), // Tab title
			'sections' => array( // Tab Sections
				'name_typography'   => array(
					'collapsed' => true,
					'title'     => __( 'Name', 'bb-powerpack' ),
					'fields'    => array(
						'author_name_html_tag' => array(
							'type'    => 'select',
							'label'   => __( 'HTML Tag', 'bb-powerpack' ),
							'default' => 'h3',
							'options' => array(
								'h1'   => 'H1',
								'h2'   => 'H2',
								'h3'   => 'H3',
								'h4'   => 'H4',
								'h5'   => 'H5',
								'h6'   => 'H6',
								'div'  => 'div',
								'p'    => 'p',
								'span' => 'span',
							),
						),
						'name_typography'      => array(
							'type'       => 'typography',
							'label'      => __( 'Name', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-author-name',
							),
						),
					),
				),
				'bio_typography'    => array(
					'collapsed' => true,
					'title'     => __( 'Biography', 'bb-powerpack' ),
					'fields'    => array(
						'bio_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Biography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-authorbox-bio',
							),
						),
					),
				),
				'button_typography' => array(
					'collapsed' => true,
					'title'     => __( 'Button', 'bb-powerpack' ),
					'fields'    => array(
						'button_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Button', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-author-box-button .pp-author-archive-btn',
							),
						),
					),
				),
			),
		),

	)
);

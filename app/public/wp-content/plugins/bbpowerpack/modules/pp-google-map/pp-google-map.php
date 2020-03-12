<?php

/**
 * @class PPGoogleMapModule
 */
class PPGoogleMapModule extends FLBuilderModule {

	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'          => __( 'Google Map', 'bb-powerpack' ),
				'description'   => __( 'A module for Display Google Map.', 'bb-powerpack' ),
				'group'         => pp_get_modules_group(),
				'category'      => pp_get_modules_cat( 'creative' ),
				'dir'           => BB_POWERPACK_DIR . 'modules/pp-google-map/',
				'url'           => BB_POWERPACK_URL . 'modules/pp-google-map/',
				'editor_export' => true,
				'enabled'       => true,
			)
		);
	}

	public function enqueue_scripts() {
		$url = pp_get_google_api_url();
		if ( $url ) {
			$this->add_js(
				'pp-google-map',
				$url,
				array( 'jquery' ),
				'3.0',
				true
			);

			if ( isset( $this->settings->marker_clustering ) && 'yes' === $this->settings->marker_clustering ) {
				$this->add_js( 'pp-cluster' );
			}
		}
	}

	public function update( $settings ) {
		return $settings;
	}
	public static function get_general_fields() {
		$fields = array(
			'map_source'        => array(
				'type'    => 'select',
				'label'   => __( 'Source', 'bb-powerpack' ),
				'default' => 'manual',
				'options' => array(
					'manual' => __( 'Manual', 'bb-powerpack' ),
					'post'   => __( 'Post', 'bb-powerpack' ),
				),
				'toggle'  => array(
					'manual' => array(
						'fields' => array( 'pp_gmap_addresses' ),
					),
					'post'   => array(
						'fields'   => array( 'post_map_name', 'post_map_latitude', 'post_map_longitude', 'post_marker_point' ),
						'sections' => array( 'post_content' ),
					),
				),
			),
			'pp_gmap_addresses' => array(
				'type'         => 'form',
				'label'        => __( 'Location', 'bb-powerpack' ),
				'form'         => 'pp_google_map_addresses',
				'preview_text' => 'map_name',
				'multiple'     => true,
			),
		);
		if ( class_exists( 'acf' ) ) {
			$fields['map_source']['options']['acf']          = __( 'ACF Repeater Field', 'bb-powerpack' );
			$fields['map_source']['toggle']['acf']['fields'] = array( 'acf_repeater_name', 'acf_map_name', 'acf_map_latitude', 'acf_map_longitude', 'acf_marker_point', 'acf_marker_img', 'acf_enable_info' );

			$fields['acf_repeater_name']    = array(
				'type'        => 'text',
				'label'       => __( 'ACF Repeater Field Name', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
			$fields['acf_map_name']         = array(
				'type'        => 'text',
				'label'       => __( 'Location Name', 'bb-powerpack' ),
				'help'        => __( 'A browser based tooltip will be applied on marker.', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
			$fields['acf_map_latitude']     = array(
				'type'        => 'text',
				'label'       => __( 'Latitude', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
			$fields['acf_map_longitude']    = array(
				'type'        => 'text',
				'label'       => __( 'Longitude', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
			$fields['acf_marker_point']     = array(
				'type'    => 'pp-switch',
				'label'   => __( 'Marker Point Icon', 'bb-powerpack' ),
				'default' => 'default',
				'options' => array(
					'default' => __( 'Default', 'bb-powerpack' ),
					'custom'  => __( 'Custom', 'bb-powerpack' ),
				),
				'toggle'  => array(
					'custom' => array(
						'fields' => array( 'acf_marker_img' ),
					),
				),
			);
			$fields['acf_marker_img']       = array(
				'type'        => 'photo',
				'label'       => __( 'Custom Marker', 'bb-powerpack' ),
				'show_remove' => true,
				'connections' => array( 'photo' ),
			);
			$fields['acf_enable_info']      = array(
				'type'    => 'pp-switch',
				'label'   => __( 'Show Tooltip', 'bb-powerpack' ),
				'default' => 'no',
				'toggle'  => array(
					'yes' => array(
						'fields' => array( 'acf_info_window_text' ),
					),
				),
			);
			$fields['acf_info_window_text'] = array(
				'type'          => 'editor',
				'label'         => '',
				'default'       => __( 'IdeaBox Creations', 'bb-powerpack' ),
				'media_buttons' => false,
				'connections'   => array( 'string', 'html' ),
			);
		}
		if ( function_exists( 'acf_add_options_page' ) ) {
			$fields['map_source']['options']['acf_options_page']          = __( 'ACF Option Page', 'bb-powerpack' );
			$fields['map_source']['toggle']['acf_options_page']['fields'] = array( 'acf_options_page_repeater_name', 'acf_options_map_name', 'acf_options_map_latitude', 'acf_options_map_longitude', 'acf_options_marker_point', 'acf_options_marker_img', 'acf_options_enable_info' );
			$fields['map_source']['help']                                 = __( 'To use the "ACF Option Page" feature, you will need ACF PRO (ACF v5), or the options page add-on (ACF v4)', 'bb-powerpack' );

			$fields['acf_options_page_repeater_name'] = array(
				'type'        => 'text',
				'label'       => __( 'ACF Repeater Field Name', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);

			$fields['acf_options_map_name']         = array(
				'type'        => 'text',
				'label'       => __( 'Location Name', 'bb-powerpack' ),
				'help'        => __( 'Location Name to identify while editing', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
			$fields['acf_options_map_latitude']     = array(
				'type'        => 'text',
				'label'       => __( 'Latitude', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
			$fields['acf_options_map_longitude']    = array(
				'type'        => 'text',
				'label'       => __( 'Longitude', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
			$fields['acf_options_marker_point']     = array(
				'type'    => 'select',
				'label'   => __( 'Marker Point Icon', 'bb-powerpack' ),
				'default' => 'default',
				'options' => array(
					'default' => 'Default',
					'custom'  => 'Custom',
				),
				'toggle'  => array(
					'custom' => array(
						'fields' => array( 'acf_options_marker_img' ),
					),
				),
			);
			$fields['acf_options_marker_img']       = array(
				'type'        => 'photo',
				'label'       => __( 'Custom Marker', 'bb-powerpack' ),
				'show_remove' => true,
				'connections' => array( 'photo' ),
			);
			$fields['acf_options_enable_info']      = array(
				'type'    => 'select',
				'label'   => __( 'Show Tooltip', 'bb-powerpack' ),
				'default' => 'no',
				'options' => array(
					'yes' => __( 'Yes', 'bb-powerpack' ),
					'no'  => __( 'No', 'bb-powerpack' ),
				),
				'toggle'  => array(
					'yes' => array(
						'fields' => array( 'acf_options_info_window_text' ),
					),
				),
			);
			$fields['acf_options_info_window_text'] = array(
				'type'          => 'editor',
				'label'         => '',
				'default'       => __( 'IdeaBox Creations', 'bb-powerpack' ),
				'media_buttons' => false,
				'connections'   => array( 'string', 'html' ),
			);
		}

		return $fields;
	}
	public function get_cpt_data() {
		if ( ! isset( $this->settings->post_slug ) || empty( $this->settings->post_slug ) ) {
			return;
		}

		$data = array();
		$settings = $this->settings;

		$post_type = ! empty( $settings->post_slug ) ? $settings->post_slug : 'post';
		$post_count = ! empty( $settings->post_count ) || '-1' !== $settings->post_count ? $settings->post_count : '-1';

		$post_args = array(
			'post_type'   => $post_type,
			'post_status' => 'publish',
			'numberposts' => $post_count,
		);

		if ( is_tax() ) {
			$post_args['tax_query'] = array(
				array(
					'taxonomy' => get_queried_object()->taxonomy,
					'field'	=> 'slug',
					'terms' => get_queried_object()->slug,
				)
			);
		}

		$posts = get_posts( $post_args );

		global $post;

		foreach ( $posts as $post ) {
			setup_postdata( $post );
			$item                   = new stdClass;
			$item->map_name         = ! empty( $settings->post_map_name ) ? do_shortcode( $settings->post_map_name ) : get_the_title( $post->ID );
			$item->map_latitude     = ! empty( $settings->post_map_latitude ) ? do_shortcode( $settings->post_map_latitude ) : '';
			$item->map_longitude    = ! empty( $settings->post_map_longitude ) ? do_shortcode( $settings->post_map_longitude ) : '';
			$item->marker_point     = ! empty( $settings->post_marker_point ) ? $settings->post_marker_point : 'default';
			$item->marker_img       = isset( $settings->post_marker_img_src ) && ! empty( $settings->post_marker_img_src ) ? $settings->post_marker_img_src : '';
			$item->enable_info      = ! empty( $settings->post_enable_info ) ? $settings->post_enable_info : 'no';
			$item->info_window_text = ! empty( $settings->post_info_window_text ) ? do_shortcode( $settings->post_info_window_text ) : get_the_title( $post->ID );

			$data[] = $item;
		}
		wp_reset_postdata();

		return $data;
	}
	public function get_acf_data( $post_id = false ) {
		if ( ( ! isset( $this->settings->acf_repeater_name ) || empty( $this->settings->acf_repeater_name ) ) ) {
			return;
		}

		$data    = array();
		$post_id = apply_filters( 'pp_google_map_acf_post_id', $post_id );
		$settings = $this->settings;

		$repeater_name = $settings->acf_repeater_name;

		if ( empty( $repeater_name ) ) {
			return;
		}
		
		$repeater_rows = get_field( $repeater_name, $post_id );

		if ( ! $repeater_rows ) {
			return;
		}

		foreach ( $repeater_rows as $row ) {
			$item                   = new stdClass;
			$item->map_name         = ! empty( $settings->acf_map_name ) ? $settings->acf_map_name : '';
			$item->map_latitude     = ! empty( $settings->acf_map_latitude ) ? $settings->acf_map_latitude : '';
			$item->map_longitude    = ! empty( $settings->acf_map_longitude ) ? $settings->acf_map_longitude : '';
			$item->marker_point     = ! empty( $settings->acf_marker_point ) ? $settings->acf_marker_point : 'default';
			$item->marker_img       = ! empty( $settings->acf_marker_img_src ) ? $settings->acf_marker_img_src : '';
			$item->enable_info      = ! empty( $settings->acf_enable_info ) ? $settings->acf_enable_info : 'no';
			$item->info_window_text = ! empty( $settings->acf_info_window_text ) ? $settings->acf_info_window_text : '';

			$data[] = $item;
		}

		return $data;
	}
	public function get_acf_options_page_data( $post_id = false ) {
		if ( ! isset( $this->settings->acf_options_page_repeater_name ) || empty( $this->settings->acf_options_page_repeater_name ) ) {
			return;
		}

		$data    = array();
		$post_id = apply_filters( 'pp_google_map_acf_options_page_post_id', $post_id );

		$repeater_name    = $this->settings->acf_options_page_repeater_name;
		$map_name         = $this->settings->acf_options_map_name;
		$map_latitude     = $this->settings->acf_options_map_latitude;
		$map_longitude    = $this->settings->acf_options_map_longitude;
		$marker_point     = $this->settings->acf_options_marker_point;
		$marker_img       = $this->settings->acf_options_marker_img;
		$enable_info      = $this->settings->acf_options_enable_info;
		$info_window_text = $this->settings->acf_options_info_window_text;

		$repeater_rows = get_field( $repeater_name, 'option' );
		if ( ! $repeater_rows ) {
			return;
		}

		foreach ( $repeater_rows as $row ) {
			$item                   = new stdClass;
			$item->map_name         = ! empty( $row[ $map_name ] ) ? $row[ $map_name ] : '';
			$item->map_latitude     = ! empty( $row[ $map_latitude ] ) ? $row[ $map_latitude ] : '';
			$item->map_longitude    = ! empty( $row[ $map_longitude ] ) ? $row[ $map_longitude ] : '';
			$item->marker_point     = ! empty( $row[ $marker_point ] ) ? $row[ $marker_point ] : '';
			$item->marker_img       = ! empty( $row[ $marker_img ] ) ? $row[ $marker_img ] : '';
			$item->enable_info      = ! empty( $row[ $enable_info ] ) ? $row[ $enable_info ] : '';
			$item->info_window_text = ! empty( $row[ $info_window_text ] ) ? $row[ $info_window_text ] : '';

			$data[] = $item;
		}
		return $data;
	}

	public function get_map_data() {
		if ( ! isset( $this->settings->map_source ) || empty( $this->settings->map_source ) ) {
			return $this->settings->pp_gmap_addresses;
		}

		if ( 'acf' === $this->settings->map_source ) {
			return $this->get_acf_data();
		}

		if ( 'acf_options_page' === $this->settings->map_source ) {
			return $this->get_acf_options_page_data();
		}

		if ( 'post' === $this->settings->map_source ) {
			return $this->get_cpt_data();
		}

		return $this->settings->pp_gmap_addresses;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'PPGoogleMapModule',
	array(
		'form'      => array(
			'title'    => __( 'Locations', 'bb-powerpack' ),
			'sections' => array(
				'address_form' => array(
					'title'  => __( 'Locations', 'bb-powerpack' ),
					'fields' => PPGoogleMapModule::get_general_fields(),
				),
				'post_content' => array(
					'title' => __( 'Content', 'bb-powerpack' ),
					'file'  => BB_POWERPACK_DIR . 'modules/pp-google-map/includes/loop-settings.php',
				),
			),
		),
		'settings'  => array(
			'title'    => __( 'Settings', 'bb-powerpack' ),
			'sections' => array(
				'gen_control' => array(
					'title'  => '',
					'fields' => array(
						'zoom_type'        => array(
							'type'    => 'select',
							'label'   => __( 'Zoom Type', 'bb-powerpack' ),
							'default' => 'auto',
							'options' => array(
								'auto'   => 'Auto',
								'custom' => 'Custom',
							),
							'toggle'  => array(
								'custom' => array(
									'fields' => array( 'map_zoom' ),
								),
							),
						),
						'map_zoom'         => array(
							'type'    => 'select',
							'label'   => __( 'Map Zoom', 'bb-powerpack' ),
							'default' => '12',
							'options' => array(
								'1'  => '1',
								'2'  => '2',
								'3'  => '3',
								'4'  => '4',
								'5'  => '5',
								'6'  => '6',
								'7'  => '7',
								'8'  => '8',
								'9'  => '9',
								'10' => '10',
								'11' => '11',
								'12' => '12',
								'13' => '13',
								'14' => '14',
								'15' => '15',
								'16' => '16',
								'17' => '17',
								'18' => '18',
								'19' => '19',
								'20' => '20',
							),
						),
						'scroll_zoom'      => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Disable map zoom on Mouse Wheel Scroll', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'dragging'         => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Disable Dragging on Mobile', 'bb-powerpack' ),
							'default' => 'false',
							'options' => array(
								'false' => __( 'Yes', 'bb-powerpack' ),
								'true'  => __( 'No', 'bb-powerpack' ),
							),
						),
						'marker_animation' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Marker Animation', 'bb-powerpack' ),
							'default' => 'drop',
							'options' => array(
								''       => __( 'None', 'bb-powerpack' ),
								'drop'   => __( 'Drop', 'bb-powerpack' ),
								'bounce' => __( 'Bounce', 'bb-powerpack' ),
							),
						),
						'marker_clustering'	=> array(
							'type'	=> 'pp-switch',
							'label'	=> __( 'Marker Clustering', 'bb-powerpack' ),
							'default' => 'no',
							'help'	=> __( 'Use marker clustering to display a large number of markers on a map and prevent overlapping.', 'bb-powerpack' ),
						),
					),
				),
				'control'     => array(
					'title'     => __( 'Controls', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'street_view'        => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Street view control', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
						),
						'map_type_control'   => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Map type control', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
						),
						'zoom'               => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Zoom control', 'bb-powerpack' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
						),
						'fullscreen_control' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Full Screen control', 'bb-powerpack' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
						),
						'hide_tooltip'       => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Show Tooltips on Click', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
						),
					),
				),
			),
		),
		'map_style' => array(
			'title'    => __( 'Style', 'bb-powerpack' ),
			'sections' => array(
				'general'    => array(
					'title'  => '',
					'fields' => array(
						'map_width'      => array(
							'type'       => 'unit',
							'label'      => __( 'Width', 'bb-powerpack' ),
							'default'    => '100',
							'slider'     => array(
								'%'  => array(
									'min' => 0,
									'max' => 100,
								),
								'px' => array(
									'min' => 0,
									'max' => 1000,
								),
							),
							'units'      => array( '%', 'px' ),
							'responsive' => true,
						),
						'map_height'     => array(
							'type'       => 'unit',
							'label'      => __( 'Height', 'bb-powerpack' ),
							'default'    => '400',
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
							'units'      => array( 'px' ),
							'responsive' => true,
						),
						'map_type'       => array(
							'type'    => 'select',
							'label'   => __( 'Map View', 'bb-powerpack' ),
							'default' => 'roadmap',
							'options' => array(
								'roadmap'   => __( 'Roadmap', 'bb-powerpack' ),
								'satellite' => __( 'Satellite', 'bb-powerpack' ),
								'hybrid'    => __( 'Hybrid', 'bb-powerpack' ),
								'terrain'   => __( 'Terrain', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'roadmap' => array(
									'fields' => array( 'map_skin' ),
								),
								'hybrid'  => array(
									'fields' => array( 'map_skin' ),
								),
								'terrain' => array(
									'fields' => array( 'map_skin' ),
								),
							),
						),
						'map_skin'       => array(
							'type'    => 'select',
							'label'   => __( 'Map Skin', 'bb-powerpack' ),
							'default' => 'standard',
							'options' => array(
								'standard'     => __( 'Standard', 'bb-powerpack' ),
								'aqua'         => __( 'Aqua', 'bb-powerpack' ),
								'aubergine'    => __( 'Aubergine', 'bb-powerpack' ),
								'classic_blue' => __( 'Classic Blue', 'bb-powerpack' ),
								'dark'         => __( 'Dark', 'bb-powerpack' ),
								'earth'        => __( 'Earth', 'bb-powerpack' ),
								'magnesium'    => __( 'Magnesium', 'bb-powerpack' ),
								'night'        => __( 'Night', 'bb-powerpack' ),
								'silver'       => __( 'Silver', 'bb-powerpack' ),
								'retro'        => __( 'Retro', 'bb-powerpack' ),
								'custom'       => __( 'Custom Style', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'custom' => array(
									'fields' => array( 'map_style1', 'map_style_code' ),
								),
							),
						),
						'map_style1'     => array(
							'type'        => 'static',
							'description' => __( '<a target="_blank" rel="noopener" href="https://mapstyle.withgoogle.com/"><b>Click here</b></a> to get JSON style code to style your map.', 'bb-powerpack' ),
						),
						'map_style_code' => array(
							'type'          => 'editor',
							'label'         => '',
							'rows'          => 3,
							'media_buttons' => false,
							'connections'   => array( 'string', 'html' ),
						),
					),
				),
				'info_style' => array(
					'title'  => __( 'Marker Tooltip', 'bb-powerpack' ),
					'fields' => array(
						'info_width'   => array(
							'type'       => 'unit',
							'label'      => __( 'Marker Tooltip Max Width', 'bb-powerpack' ),
							'default'    => '200',
							'units'      => array( 'px' ),
							'slider'     => array(
								'px' => array(
									'min' => 0,
									'max' => 1000,
								),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.gm-style .pp-infowindow-content',
								'property' => 'max-width',
								'unit'     => 'px',
							),
							'responsive' => true,
						),
						'info_padding' => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'slider'     => true,
							'units'      => array( 'px' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.gm-style .pp-infowindow-content',
								'property' => 'padding',
								'unit'     => 'px',
							),
							'responsive' => true,
						),
					),
				),
			),
		),
	)
);

FLBuilder::register_settings_form(
	'pp_google_map_addresses',
	array(
		'title' => __( 'Add Location', 'bb-powerpack' ),
		'tabs'  => array(
			'addr_general' => array(
				'title'    => __( 'General', 'bb-powerpack' ),
				'sections' => array(
					'features' => array(
						'title'  => __( 'Location', 'bb-powerpack' ),
						'fields' => array(
							'map_name'      => array(
								'type'        => 'text',
								'label'       => __( 'Location Name', 'bb-powerpack' ),
								'default'     => 'IdeaBox Creations',
								'help'        => __( 'A browser based tooltip will be applied on marker.', 'bb-powerpack' ),
								'connections' => array( 'string' ),
							),
							'map_latitude'  => array(
								'type'        => 'text',
								'label'       => __( 'Latitude', 'bb-powerpack' ),
								'default'     => '24.553311',
								'description' => __( '<a href="https://www.latlong.net/" target="_blank" rel="noopener"><b>Click here</b></a> to find Latitude and Longitude of a location.', 'bb-powerpack' ),
								'connections' => array( 'string' ),
							),
							'map_longitude' => array(
								'type'        => 'text',
								'label'       => __( 'Longitude', 'bb-powerpack' ),
								'default'     => '73.694076',
								'description' => __( '<a href="https://www.latlong.net/" target="_blank" rel="noopener"><b>Click here</b></a> to find Latitude and Longitude of a location.', 'bb-powerpack' ),
								'connections' => array( 'string' ),
							),
							'marker_point'  => array(
								'type'    => 'select',
								'label'   => __( 'Marker Icon', 'bb-powerpack' ),
								'default' => 'default',
								'options' => array(
									'default' => __( 'Default', 'bb-powerpack' ),
									'custom'  => __( 'Custom', 'bb-powerpack' ),
								),
								'toggle'  => array(
									'custom' => array(
										'fields' => array( 'marker_img' ),
									),
								),
							),
							'marker_img'    => array(
								'type'        => 'photo',
								'label'       => __( 'Custom Marker', 'bb-powerpack' ),
								'show_remove' => true,
								'connections' => array( 'photo' ),
							),
						),
					),
				),
			),
			'info_window'  => array(
				'title'    => __( 'Marker Tooltip', 'bb-powerpack' ),
				'sections' => array(
					'title' => array(
						'title'  => '',
						'fields' => array(
							'enable_info'      => array(
								'type'    => 'select',
								'label'   => __( 'Show Tooltip', 'bb-powerpack' ),
								'default' => 'yes',
								'options' => array(
									'yes' => __( 'Yes', 'bb-powerpack' ),
									'no'  => __( 'No', 'bb-powerpack' ),
								),
								'toggle'  => array(
									'yes' => array(
										'fields' => array( 'info_window_text' ),
									),
								),
							),
							'info_window_text' => array(
								'type'          => 'editor',
								'label'         => '',
								'default'       => __( 'IdeaBox Creations', 'bb-powerpack' ),
								'media_buttons' => false,
								'connections'   => array( 'string', 'html' ),
							),
						),
					),
				),
			),
		),
	)
);

<?php

/**
 * @class PPImageCarouselModule
 */
class PPReviewsModule extends FLBuilderModule {
	/**
	 * Class constructor.
	 *
	 * @since 2.7.11
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Reviews', 'bb-powerpack' ),
				'description'     => __( 'A module for reviews.', 'bb-powerpack' ),
				'group'           => pp_get_modules_group(),
				'category'        => pp_get_modules_cat( 'content' ),
				'dir'             => BB_POWERPACK_DIR . 'modules/pp-reviews/',
				'url'             => BB_POWERPACK_URL . 'modules/pp-reviews/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
			)
		);

		$this->add_css( 'jquery-swiper' );
		$this->add_js( 'jquery-swiper' );
		$this->add_css( BB_POWERPACK()->fa_css );
	}

	/**
	 * Get reviews.
	 * 
	 * Retrieve reviews based on selected source.
	 *
	 * @since 2.7.11
	 *
	 * @return array|WP_Error
	 */
	public function get_reviews() {
		$source = $this->settings->review_source;
		$reviews = array();

		if ( 'google' === $source ) {
			$reviews = $this->get_google_reviews();
			$reviews = $this->parse_reviews( $reviews, $source );
		}
		if ( 'yelp' === $source ) {
			$reviews = $this->get_yelp_reviews();
			$reviews = $this->parse_reviews( $reviews, $source );
		}
		if ( 'all' === $source ) {
			$reviews = $this->get_google_reviews();
			$google_reviews = $this->parse_reviews( $reviews, 'google' );
			$reviews = $this->get_yelp_reviews();
			$yelp_reviews = $this->parse_reviews( $reviews, 'yelp' );

			$reviews = array();

			if ( is_wp_error( $google_reviews ) ) {
				return $google_reviews;
			}
			if ( is_wp_error( $yelp_reviews ) ) {
				return $yelp_reviews;
			}

			if ( ! empty( $google_reviews ) ) {
				for ( $i = 0; $i < count( $google_reviews ); $i++ ) {
					$reviews[] = $google_reviews[ $i ];
					if ( isset( $yelp_reviews[ $i ] ) ) {
						$reviews[] = $yelp_reviews[ $i ];
					}
				}
			}
			if ( empty( $reviews ) && ! empty( $yelp_reviews ) ) {
				$reviews = $yelp_reviews;
			}
		}

		if ( is_array( $reviews ) && ! empty( $reviews ) ) {
			if ( 'rating' == $this->settings->reviews_filter_by ) {
				usort( $reviews, array( $this, 'sort_by_rating' ) );
			} elseif ( 'date' == $this->settings->reviews_filter_by ) {
				usort( $reviews, array( $this, 'sort_by_time' ) );
			}

			$max_reviews	 = 8;
			$reviews_to_show = $max_reviews;

			if ( 'google' === $source ) {
				$max_reviews        = 5;
				$reviews_to_show 	= $this->settings->google_reviews_count;
			} elseif ( 'yelp' === $source ) {
				$max_reviews        = 3;
				$reviews_to_show 	= $this->settings->yelp_reviews_count;
			} elseif ( 'all' === $source ) {
				$max_reviews        = 8;
				$reviews_to_show 	= $this->settings->total_reviews_count;
			}

			$reviews_to_show = ( '' !== $reviews_to_show ) ? $reviews_to_show : $max_reviews;

			if ( $max_reviews !== $reviews_to_show ) {
				$display_number = (int) $reviews_to_show;
				$reviews        = array_slice( $reviews, 0, $display_number );
			}
		}

		return $reviews;
	}

	/**
	 * Parse reviews.
	 * 
	 * Build array of reviews based on provided raw data.
	 *
	 * @since 2.7.11
	 * @param array 	$reviews	Array of reviews data.
	 * @param string 	$source		Review source.
	 *
	 * @return array|WP_Error
	 */
	private function parse_reviews( $reviews, $source ) {
		if ( is_wp_error( $reviews['error'] ) ) {
			return $reviews['error'];
		}
		if ( empty( $reviews['data'] ) ) {
			return;
		}

		$parsed_reviews = array();
		$filter_by_min_rating = false;

		$data = $reviews['data'];

		if ( 'google' === $source ) {
			$data = $data['reviews'];
		}

		if ( '' !== $this->settings->reviews_min_rating ) {
			$filter_by_min_rating = true;
		}

		foreach ( $data as $review ) {
			$_review = array();

			if ( 'google' === $source ) {
				$review_url = explode( '/reviews', $review->author_url );
				$review_url = $review_url[0] . '/place/' . $this->settings->google_place_id;

				if ( isset( $reviews['data']['location'] ) && ! empty( $reviews['data']['location'] ) ) {
					$location = $reviews['data']['location'];
					$review_url = $review_url . '/' . $location->lat . ',' . $location->lng;
				}

				$_review['source']                    = 'google';
				$_review['author_name']               = $review->author_name;
				$_review['author_url']                = $review->author_url;
				$_review['profile_photo_url']         = $review->profile_photo_url;
				$_review['rating']                    = $review->rating;
				$_review['relative_time_description'] = $review->relative_time_description;
				$_review['text']                      = $review->text;
				$_review['time']                      = $review->time;
				$_review['title']              		  = $review->relative_time_description;
				$_review['review_url']                = $review_url;
			}

			if ( 'yelp' === $source ) {
				$_review['source']                    = 'yelp';
				$_review['author_name']               = $review->user->name;
				$_review['author_url']                = $review->user->profile_url;
				$_review['profile_photo_url']         = $review->user->image_url;
				$_review['rating']                    = $review->rating;
				$_review['relative_time_description'] = '';
				$_review['text']                      = $review->text;
				$_review['time']                      = $review->time_created;
				$_review['title']              		  = $this->get_readable_time( $_review['time'] );
				$_review['review_url']                = $review->url;
			}

			if ( $filter_by_min_rating ) {
				if ( $review->rating >= $this->settings->reviews_min_rating ) {
					$parsed_reviews[] = $_review;
				}
			} else {
				$parsed_reviews[] = $_review;
			}
		}

		return $parsed_reviews;
	}

	/**
	 * Get readable time.
	 *
	 * Convert time into human readable format.
	 *
	 * @since 2.7.11
	 *
	 * @uses human_time_diff to convert time in human readable format.
	 *
	 * @return string
	 */
	private function get_readable_time( $time ) {
		$time = human_time_diff( strtotime( $time ), current_time( 'timestamp' ) );

		return sprintf( __( '%s ago', 'bb-powerpack' ), $time );
	}

	/**
	 * Get API data.
	 * 
	 * Handles review source remote API calls.
	 *
	 * @since 2.7.11
	 * @param string 	$source		Review source.
	 *
	 * @return array	$response	API response.
	 */
	private function get_api_data( $source ) {
		$api_args = array(
			'method'      => 'POST',
			'timeout'     => 60,
			'httpversion' => '1.0',
			'sslverify'   => false,
		);

		if ( 'google' === $source ) {
			$api_key = pp_get_google_api_key();
			$place_id = $this->settings->google_place_id;

			if ( empty( $api_key ) ) {
				return new WP_Error( 'missing_api_key', __( 'To display Google Reviews, you need to setup API key.', 'bb-powerpack' ) );
			}
			if ( empty( $place_id ) ) {
				return new WP_Error( 'missing_place_id', __( 'To display Google Reviews, you need to provide valid Place ID.', 'bb-powerpack' ) );
			}

			$url = add_query_arg(
				array(
					'key'     => pp_get_google_api_key(),
					'placeid' => $this->settings->google_place_id,
				),
				'https://maps.googleapis.com/maps/api/place/details/json'
			);
		}

		if ( 'yelp' === $source ) {
			$business_id = $this->settings->yelp_business_id;

			if ( empty( $business_id ) ) {
				return new WP_Error( 'missing_business_id', __( 'To display Yelp Reviews, you need to provide valid Business ID.', 'bb-powerpack' ) );
			}

			$url = 'https://api.yelp.com/v3/businesses/' . $business_id . '/reviews';
			
			$api_args['method'] = 'GET';
			$api_args['user-agent'] = '';
			$api_args['headers'] = array(
				'Authorization' => 'Bearer ' . pp_get_yelp_api_key(),
			);
		}

		$response = wp_remote_post(
			esc_url_raw( $url ),
			$api_args
		);

		return $response;
	}

	/**
	 * Get google reviews.
	 * 
	 * Get reviews from Google Place API and store it in transient.
	 *
	 * @since 2.7.11
	 *
	 * @return array $response Reviews data.
	 */
	private function get_google_reviews() {
		$response = array(
			'data'	=> array(),
			'error' => false,
		);

		$transient_name = 'pp_reviews_' . $this->settings->google_place_id;

		$response['data'] = get_transient( $transient_name );
		
		if ( empty( $response['data'] ) ) {
			$api_data = $this->get_api_data( 'google' );

			if ( is_wp_error( $api_data ) ) {
				
				$response['error'] = $api_data;

			} else {
				if ( 200 === wp_remote_retrieve_response_code( $api_data ) ) {
					
					$data = json_decode( wp_remote_retrieve_body( $api_data ) );
					
					if ( 'OK' !== $data->status ) {
						$response['error'] = $data->error_message;
					} else {
						if ( isset( $data->result ) && isset( $data->result->reviews ) ) {
							$response['data'] = array(
								'reviews' => $data->result->reviews,
								'location' => array(),
							);

							if ( isset( $data->result->geometry->location ) ) {
								$response['data']['location'] = $data->result->geometry->location;
							}

							set_transient( $transient_name, $response['data'], $this->get_transient_time() );

							$response['error'] = false;
						} else {
							$response['error'] = __( 'This place doesn\'t have any reviews.', 'bb-powerpack' );
						}
					}
				}
			}	
		}

		return $response;
	}

	/**
	 * Get yelp reviews.
	 * 
	 * Get reviews from Yelp Business API and store it in transient.
	 *
	 * @since 2.7.11
	 *
	 * @return array $response Reviews data.
	 */
	private function get_yelp_reviews() {
		$response = array(
			'data'	=> array(),
			'error' => false,
		);

		$transient_name = 'pp_reviews_' . $this->settings->yelp_business_id;

		$response['data'] = get_transient( $transient_name );

		if ( empty( $response['data'] ) ) {
			$api_data = $this->get_api_data( 'yelp' );

			if ( is_wp_error( $api_data ) ) {
				
				$response['error'] = $api_data;

			} else {
				if ( 200 !== wp_remote_retrieve_response_code( $api_data ) ) {
					$data = json_decode( wp_remote_retrieve_body( $api_data ) );

					if ( isset( $data->error ) ) {
						if ( 'VALIDATION_ERROR' === $data->error->code ) {
							$response['error'] = __( 'Yelp Reviews Error: Invalid or empty API key.', 'bb-powerpack' );
						}
						if ( 'BUSINESS_NOT_FOUND' === $data->error->code ) {
							$response['error'] = __( 'Yelp Reviews Error: Incorrect or empty Business ID.', 'bb-powerpack' );
						}
						if ( 'INTERNAL_SERVER_ERROR' === $data->error->code ) {
							$response['error'] = __( 'Yelp Reviews Error: Something is wrong with Yelp.', 'bb-powerpack' );
						}
					} else {
						$response['error'] = __( 'Yelp Reviews Error: Unknown error occurred.', 'bb-powerpack' );
					}
				} else {
					$data = json_decode( wp_remote_retrieve_body( $api_data ) );

					if ( empty( $data ) || ! isset( $data->reviews ) || empty( $data->reviews ) ) {
						$response['error'] = __( 'This business doesn\'t have any reviews.', 'bb-powerpack' );
					} else {
						$response['data'] = $data->reviews;

						set_transient( $transient_name, $response['data'], $this->get_transient_time() );

						$response['error'] = false;
					}
				}
			}	
		}

		return $response;
	}

	/**
	 * Get transient time.
	 * 
	 * Get transient time from module settings.
	 *
	 * @since 2.7.11
	 *
	 * @return string Time in seconds.
	 */
	private function get_transient_time() {
		// 24 hours.
		$transient_time = 24;

		if ( isset( $this->settings->transient_time ) && ! empty( $this->settings->transient_time ) ) {
			$transient_time = $this->settings->transient_time;
		}
		
		return $transient_time * MINUTE_IN_SECONDS;
	}

	/**
	 * Sort by rating.
	 *
	 * Sort reviews by rating.
	 *
	 * @since 2.7.9
	 * @param array $reviews_1	A review array item.
	 * @param array $reviews_2	A review array item.
	 *
	 * @return boolean
	 */
	private function sort_by_rating( $review_1, $review_2 ) {
		return strcmp( $review_2['rating'], $review_1['rating'] );
	}

	/**
	 * Sort by time.
	 *
	 * Sort reviews by time.
	 *
	 * @since 2.7.9
	 * @param array $reviews_1	A review array item.
	 * @param array $reviews_2	A review array item.
	 *
	 * @return boolean
	 */
	private function sort_by_time( $review_1, $review_2 ) {
		return strcmp( $review_2['time'], $review_1['time'] );
	}

	/**
	 * Get source icon.
	 *
	 * Get icon based on review source.
	 *
	 * @since 2.7.9
	 * @param string $source Review source.
	 * 
	 * @return string|array
	 */
	public function get_source_icon( $source = false ) {
		$icon = apply_filters( 'pp_reviews_source_icon', array(
			'google' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="18px" height="18px"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>',
			'yelp'	=> 'fab fa-yelp',
		) );

		if ( ! empty( $source ) ) {
			if ( isset( $icon[ $source ] ) ) {
				$icon = $icon[ $source ];
			} else {
				$icon = '';
			}
		}

		return $icon;
	}

	/**
	 * Get review source notice.
	 *
	 * API keys notice to display in module setting
	 * based on review source.
	 *
	 * @since 2.7.9
	 *
	 * @return string
	 */
	static public function get_review_source_notice() {
		$notice = '';
		$setting_page = BB_PowerPack_Admin_Settings::get_form_action( '&tab=integration' );
		
		$notice .= sprintf(
			__( '<span class="google-notice">To display Google Places reviews, you must have a Google API key. <a href="%s" target="_blank">Click here</a> to setup your Google API key.</span> ', 'bb-powerpack' ),
			$setting_page
		);

		$notice .= sprintf(
			__( '<span class="yelp-notice">To display Yelp reviews, you must have a Yelp API key. <a href="%s" target="_blank">Click here</a> to setup your Yelp API key.</span> ', 'bb-powerpack' ),
			$setting_page
		);
		
		$notice .= sprintf(
			__( '<span class="all-notice">You need Google Places and Yelp API keys configured to display Google and Yelp reviews. <a href="%s" target="_blank">Click here</a> to setup API keys.</span>', 'bb-powerpack' ),
			$setting_page
		);

		return $notice;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'PPReviewsModule',
	array(
		'reviews'    => array( // Tab.
			'title'    => __( 'Reviews', 'bb-powerpack' ), // Tab title.
			'description'		=> PPReviewsModule::get_review_source_notice(),
			'sections' => array( // Tab Sections.
				'general'      => array( // Section.
					'title'       => '', // Section Title.
					'description' => '',
					'fields'      => array( // Section Fields.
						'review_source'    => array(
							'type'    => 'select',
							'label'   => __( 'Review Source', 'bb-powerpack' ),
							'default' => 'default',
							'options' => array(
								'default'  => __( 'Default', 'bb-powerpack' ),
								'google'   => __( 'Google', 'bb-powerpack' ),
								'yelp'     => __( 'Yelp', 'bb-powerpack' ),
								'all'      => __( 'Google + Yelp', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'default'  => array(
									'sections' => array( 'reviews_form' ),
								),
								'google'   => array(
									'sections' => array( 'filter' ),
									'fields'   => array( 'google_place_id', 'google_reviews_count', 'transient_time' ),
								),
								'yelp'     => array(
									'sections' => array( 'filter' ),
									'fields'   => array( 'yelp_business_id', 'yelp_reviews_count', 'transient_time' ),
								),
								'all'      => array(
									'sections' => array( 'filter' ),
									'fields'   => array( 'yelp_business_id', 'google_place_id', 'total_reviews_count', 'transient_time' ),
								),
							),
						),
						'google_place_id'  => array(
							'type'        => 'text',
							'label'       => __( 'Google Place ID', 'bb-powerpack' ),
							'default'     => '',
							'description' => sprintf( __( '<a href="%s" target="_blank"><em>Click here</em></a> to get your Google Place ID.', 'bb-powerpack' ), 'https://developers.google.com/places/place-id' ),
							'connections' => array( 'string' ),
						),
						'yelp_business_id' => array(
							'type'        => 'text',
							'label'       => __( 'Yelp Business ID', 'bb-powerpack' ),
							'default'     => '',
							'description' => sprintf( __( '<a href="%s" target="_blank"><em>Click here</em></a> to get your Yelp Business ID.', 'bb-powerpack' ), 'https://www.yelp-support.com/article/What-is-my-Yelp-Business-ID' ),
							'connections' => array( 'string' ),
						),
					),
				),
				'reviews_form' => array( // Section.
					'title'  => '', // Section Title.
					'fields' => array( // Section Fields.
						'reviews' => array(
							'type'         => 'form',
							'label'        => __( 'Review', 'bb-powerpack' ),
							'form'         => 'pp_reviews_form', // ID from registered form below.
							'preview_text' => 'name', // Name of a field to use for the preview text.
							'multiple'     => true,
							'default'		=> array(
								array(
									'name'	=> __( 'John Doe', 'bb-powerpack' ),
									'title'	=> __( 'Customer', 'bb-powerpack' ),
									'rating' => '5',
									'review' => __( 'Proin eget tortor risus. Nulla quis lorem ut libero malesuada feugiat. Proin eget tortor risus.', 'bb-powerpack' ),
								),
								array(
									'name'	=> __( 'John Doe', 'bb-powerpack' ),
									'title'	=> __( 'Customer', 'bb-powerpack' ),
									'rating' => '4',
									'review' => __( 'Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Proin eget tortor risus.', 'bb-powerpack' ),
								),
								array(
									'name'	=> __( 'John Doe', 'bb-powerpack' ),
									'title'	=> __( 'Customer', 'bb-powerpack' ),
									'rating' => '5',
									'review' => __( 'Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Nulla porttitor accumsan tincidunt.', 'bb-powerpack' ),
								),
							),
						),
					),
				),
			),
		),
		'settings'   => array(
			'title'    => __( 'Settings', 'bb-powerpack' ),
			'sections' => array(
				'general'        => array( // Section.
					'title'  => '',
					'fields' => array( // Section Fields.
						'google_reviews_count' => array(
							'type'        => 'unit',
							'label'       => __( 'Number of Reviews', 'bb-powerpack' ),
							'default'     => '3',
							'help' => __( 'Google only serves 5 reviews total over their API.', 'bb-powerpack' ),
							'slider'      => array(
								'min'  => 1,
								'step' => 1,
								'max'  => 5,
							),
						),
						'yelp_reviews_count'  => array(
							'type'        => 'unit',
							'label'       => __( 'Number of Reviews', 'bb-powerpack' ),
							'default'     => '3',
							'help' => __( 'Google only serves 3 reviews total over their API.', 'bb-powerpack' ),
							'slider'      => array(
								'min'  => 1,
								'step' => 1,
								'max'  => 3,
							),
						),
						'total_reviews_count' => array(
							'type'        => 'unit',
							'label'       => __( 'Number of Reviews', 'bb-powerpack' ),
							'default'     => '5',
							'help' => __( 'Google only serves 5 reviews & Yelp only serves 3 reviews total over their API.', 'bb-powerpack' ),
							'slider'      => array(
								'min'  => 1,
								'step' => 1,
								'max'  => 8,
							),
						),
						'transient_time'   => array(
							'type'    	=> 'unit',
							'label'   	=> __( 'Refresh Reviews after', 'bb-powerpack' ),
							'default' 	=> '24',
							'units'		=> array( 'hrs' ),
						),
					),
				),
				'filter'         => array( // Section.
					'title'     => __( 'Filters', 'bb-powerpack' ), // Section Title.
					'collapsed'	=> true,
					'fields'    => array( // Section Fields.
						'reviews_filter_by'  => array(
							'type'    => 'select',
							'label'   => __( 'Filter By', 'bb-powerpack' ),
							'default' => 'rating',
							'options' => array(
								'default' => __( 'None', 'bb-powerpack' ),
								'rating'  => __( 'Minimum Rating', 'bb-powerpack' ),
								'date'    => __( 'Review Date', 'bb-powerpack' ),
							),
						),
						'reviews_min_rating' => array(
							'type'        => 'select',
							'label'       => __( 'Minimum Rating', 'bb-powerpack' ),
							'default'     => '',
							'help' 		=> __( 'Display reviews of ratings greater than or equal to this.', 'bb-powerpack' ),
							'options'     => array(
								'' 	 => __( 'No Minimum Rating', 'bb-powerpack' ),
								'2'  => __( '2 stars', 'bb-powerpack' ),
								'3'  => __( '3 stars', 'bb-powerpack' ),
								'4'  => __( '4 stars', 'bb-powerpack' ),
								'5'  => __( '5 stars', 'bb-powerpack' ),
							),
						),
					),
				),
				'carousel'	=> array(
					'title'		=> __( 'Carousel', 'bb-powerpack' ),
					'collapsed'	=> true,
					'fields'	=> array(
						'carousel_type'        => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'bb-powerpack' ),
							'default' => 'carousel',
							'options' => array(
								'carousel'  => __( 'Carousel', 'bb-powerpack' ),
								'coverflow' => __( 'Coverflow', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'carousel'  => array(
									'fields' => array( 'pagination_type', 'effect' ),
								),
								'coverflow' => array(
									'fields' => array( 'pagination_type', 'columns' ),
								),
							),
						),
						'effect'               => array(
							'type'    => 'select',
							'label'   => __( 'Effect', 'bb-powerpack' ),
							'default' => 'slide',
							'options' => array(
								'slide' => __( 'Slide', 'bb-powerpack' ),
								'fade'  => __( 'Fade', 'bb-powerpack' ),
								'cube'  => __( 'Cube', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'slide' => array(
									'fields' => array( 'columns' ),
								),
							),
						),
						'columns'              => array(
							'type'       => 'unit',
							'label'      => __( 'Slides Per View', 'bb-powerpack' ),
							'default'    => 3,
							'slide'      => true,
							'responsive' => array(
								'placeholder' => array(
									'default'    => '3',
									'medium'     => '2',
									'responsive' => '1',
								),
							),
						),
						'slides_to_scroll'     => array(
							'type'       => 'unit',
							'label'      => __( 'Slides to Scroll', 'bb-powerpack' ),
							'default'    => 1,
							'slide'      => true,
							'responsive' => array(
								'placeholder' => array(
									'default'    => '1',
									'medium'     => '1',
									'responsive' => '1',
								),
							),
							'help'       => __( 'Set numbers of slides to move at a time.', 'bb-powerpack' ),
						),
						'spacing'              => array(
							'type'       => 'unit',
							'label'      => __( 'Spacing', 'bb-powerpack' ),
							'default'    => 20,
							'units'      => array( 'px' ),
							'slide'      => true,
							'responsive' => array(
								'placeholder' => array(
									'default'    => '20',
									'medium'     => '20',
									'responsive' => '20',
								),
							),
						),
						'carousel_height'      => array(
							'type'       => 'unit',
							'label'      => __( 'Height', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slide'      => true,
							'responsive' => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review',
								'property' => 'height',
								'unit'     => 'px',
							),
						),
					),
				),
				'slide_settings' => array(
					'title'     => __( 'Slide Settings', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'transition_speed'     => array(
							'type'        => 'text',
							'label'       => __( 'Transition Speed', 'bb-powerpack' ),
							'default'     => '1000',
							'size'        => '5',
							'description' => _x( 'ms', 'Value unit for form field of time in mili seconds. Such as: "500 ms"', 'bb-powerpack' ),
						),
						'autoplay'             => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Auto Play', 'bb-powerpack' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'autoplay_speed' ),
								),
							),
						),
						'autoplay_speed'       => array(
							'type'        => 'text',
							'label'       => __( 'Auto Play Speed', 'bb-powerpack' ),
							'default'     => '5000',
							'size'        => '5',
							'description' => _x( 'ms', 'Value unit for form field of time in mili seconds. Such as: "500 ms"', 'bb-powerpack' ),
						),
						'pause_on_interaction' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Pause on Interaction', 'bb-powerpack' ),
							'default' => 'yes',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
						),
					),
				),
				'navigation'     => array( // Section.
					'title'     => __( 'Navigation', 'bb-powerpack' ), // Section Title.
					'collapsed' => true,
					'fields'    => array( // Section Fields.
						'slider_navigation' => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Show Navigation Arrows?', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'sections' => array( 'arrow_style' ),
								),
							),
						),
						'pagination_type'   => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Pagination Type', 'bb-powerpack' ),
							'default' => 'bullets',
							'options' => array(
								'none'     => __( 'None', 'bb-powerpack' ),
								'bullets'  => __( 'Dots', 'bb-powerpack' ),
								'fraction' => __( 'Fraction', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'bullets'  => array(
									'sections' => array( 'pagination_style' ),
									'fields'   => array( 'bullets_width', 'bullets_border_radius' ),
								),
								'fraction' => array(
									'sections' => array( 'pagination_style' ),
								),
							),
						),
					),
				),
			),
		),
		'style'      => array(
			'title'    => __( 'Style', 'bb-powerpack' ),
			'sections' => array(
				'general'          => array(
					'title'     => __( 'Slide', 'bb-powerpack' ),
					'fields'    => array(
						'slide_border'           => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review',
								'property' => 'border',
							),
						),
						'slide_padding'          => array(
							'type'       => 'unit',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),
						'separator'              => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Separator', 'bb-powerpack' ),
							'default' => 'show',
							'options' => array(
								'show' => __( 'Show', 'bb-powerpack' ),
								'hide' => __( 'Hide', 'bb-powerpack' ),
							),
						),
						'slide_background'       => array(
							'type'        => 'color',
							'label'       => __( 'Background color', 'bb-powerpack' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review',
								'property' => 'background-color',
							),
							'show_alpha'  => true,
							'show_reset'  => true,
							'connections' => array( 'color' ),
						),
						'slide_background_hover' => array(
							'type'        => 'color',
							'label'       => __( 'Background Hover color', 'bb-powerpack' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review:hover',
								'property' => 'background-color',
							),
							'show_alpha'  => true,
							'show_reset'  => true,
							'connections' => array( 'color' ),
						),
						'separator_color'        => array(
							'type'        => 'color',
							'label'       => __( 'Separator Color', 'bb-powerpack' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-header',
								'property' => 'border-bottom-color',
							),
						),
						'separator_color_hover'  => array(
							'type'        => 'color',
							'label'       => __( 'Separator Hover Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
					),
				),
				'name_style' => array(
					'title'	 => __( 'Name', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'name_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-name',
								'property' => 'color',
							),
						),
						'name_margin_top'    => array(
							'type'    => 'unit',
							'label'   => __( 'Top Margin', 'bb-powerpack' ),
							'units'   => array( 'px' ),
							'default' => '',
							'slider'	=> true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-name',
								'property' => 'margin-top',
								'unit'     => 'px',
							),
						),
						'name_margin_bottom' => array(
							'type'    => 'unit',
							'label'   => __( 'Bottom Margin', 'bb-powerpack' ),
							'units'   => array( 'px' ),
							'default' => '',
							'slider'	=> true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-name',
								'property' => 'margin-bottom',
								'unit'     => 'px',
							),
						),
					),
				),
				'title_style' => array(
					'title'		=> __( 'Title', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'	=> array(
						'title_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-title',
								'property' => 'color',
							),
						),
						'title_margin_top'    => array(
							'type'    => 'unit',
							'label'   => __( 'Top Margin', 'bb-powerpack' ),
							'units'   => array( 'px' ),
							'default' => '',
							'slider'	=> true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-title',
								'property' => 'margin-top',
								'unit'     => 'px',
							),
						),
						'title_margin_bottom' => array(
							'type'    => 'unit',
							'label'   => __( 'Bottom Margin', 'bb-powerpack' ),
							'units'   => array( 'px' ),
							'default' => '',
							'slider'	=> true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-title',
								'property' => 'margin-bottom',
								'unit'     => 'px',
							),
						),
					),
				),
				'review_style'     => array(
					'title'		=> __( 'Review', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'content_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-review-content',
								'property' => 'color',
							),
						),
						'content_margin_top'    => array(
							'type'    => 'unit',
							'label'   => __( 'Top Margin', 'bb-powerpack' ),
							'units'   => array( 'px' ),
							'default' => '',
							'slider'	=> true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-content',
								'property' => 'margin-top',
								'unit'     => 'px',
							),
						),
						'content_margin_bottom' => array(
							'type'    => 'unit',
							'label'   => __( 'Bottom Margin', 'bb-powerpack' ),
							'units'   => array( 'px' ),
							'default' => '',
							'slider'	=> true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-content',
								'property' => 'margin-bottom',
								'unit'     => 'px',
							),
						),
					),
				),
				'image'      => array(
					'title'  => __( 'Image', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'image_size'    => array(
							'type'       => 'unit',
							'label'      => __( 'Size', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'default'    => 36,
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.pp-review-image img',
										'property' => 'height',
										'unit'     => 'px',
									),
									array(
										'selector' => '.pp-review-image img',
										'property' => 'width',
										'unit'     => 'px',
									),
								),
							),
						),
						'image_border'  => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-image img',
								'property' => 'border',
							),
						),
						'image_vertical_alignment'	=> array(
							'type'    => 'select',
							'label'   => __( 'Vertical Alignment', 'bb-powerpack' ),
							'default' => 'top',
							'options' => array(
								'flex-start'   	=> __( 'Top', 'bb-powerpack' ),
								'center' 	=> __( 'Middle', 'bb-powerpack' ),
								'flex-end' 	=> __( 'Bottom', 'bb-powerpack' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-image',
								'property' => 'align-self',
							),
						),
						'image_spacing' => array(
							'type'       => 'unit',
							'label'      => __( 'Spacing', 'bb-powerpack' ),
							'default'	=> '10',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-cite',
								'property' => 'margin-left',
								'unit'     => 'px',
							),
						),
					),
				),
				'icon'       => array(
					'title'  => __( 'Icon', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'icon_size' => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'bb-powerpack' ),
							'default' => '',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-icon i, .pp-review-icon i:before',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'icon_spacing' => array(
							'type'       => 'unit',
							'label'      => __( 'Spacing', 'bb-powerpack' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-icon',
								'property' => 'margin-left',
								'unit'     => 'px',
							),
						),
						'icon_vertical_alignment'	=> array(
							'type'    => 'select',
							'label'   => __( 'Vertical Alignment', 'bb-powerpack' ),
							'default' => 'top',
							'options' => array(
								'flex-start'   	=> __( 'Top', 'bb-powerpack' ),
								'center' 	=> __( 'Middle', 'bb-powerpack' ),
								'flex-end' 	=> __( 'Bottom', 'bb-powerpack' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-review-icon i',
								'property' => 'vertical-align',
							),
						),
						'icon_color'          => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-icon i',
								'property' => 'color',

							),
							'connections' => array( 'color' ),
						),
						'icon_color_hover'          => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'bb-powerpack' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review:hover .pp-review-icon i',
								'property' => 'color',

							),
							'connections' => array( 'color' ),
						),
					),
				),
				'rating_section'     => array(
					'title'  => __( 'Rating', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'star_style'          => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Style', 'bb-powerpack' ),
							'default' => 'solid',
							'options' => array(
								'solid'   => __( 'Solid', 'bb-powerpack' ),
								'outline' => __( 'Outline', 'bb-powerpack' ),
							),
						),
						'star_size'           => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'bb-powerpack' ),
							'default' => '',
							'responsive'  => 'true',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-rating i',
								'property' => 'font-size',
								'unit'     => 'px',
							),
							'slider'  => true,
						),
						'star_color'          => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'bb-powerpack' ),
							'default'    => 'f0ad4e',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rating > i:before',
								'property' => 'color',

							),
						),
						'star_unmarked_color' => array(
							'type'       => 'color',
							'label'      => __( 'Unmarked Color', 'bb-powerpack' ),
							'default'    => 'efecdc',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-rating > i',
								'property' => 'color',

							),
						),
						'star_spacing'        => array(
							'type'        => 'unit',
							'label'       => __( 'Spacing', 'bb-powerpack' ),
							'default'     => '',
							'units' 	=> array( 'px' ),
							'slider'      => 'true',
							'responsive'  => 'true',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-rating > i',
								'property' => 'margin-right',
								'unit'     => 'px',
							),
						),
						'star_alignment'      => array(
							'type'    => 'align',
							'label'   => __( 'Alignment', 'bb-powerpack' ),
							'default' => 'left',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-rating',
								'property' => 'text-align',
							),
						),
					),
				),
				'arrow_style'      => array( // Section.
					'title'  => __( 'Arrow', 'bb-powerpack' ), // Section Title.
					'collapsed' => true,
					'fields' => array( // Section Fields.
						'arrow_font_size'          => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'bb-powerpack' ),
							'units'   => array( 'px' ),
							'slider'  => true,
							'default' => '24',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-swiper-button',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'arrow_bg_color'           => array(
							'type'        => 'color',
							'label'       => __( 'Background Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'default'     => '',
							'connections' => array( 'color' ),
						),
						'arrow_bg_hover'           => array(
							'type'        => 'color',
							'label'       => __( 'Background Hover Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'default'     => '',
							'connections' => array( 'color' ),
						),
						'arrow_color'              => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'default'     => '000000',
							'connections' => array( 'color' ),
						),
						'arrow_color_hover'        => array(
							'type'        => 'color',
							'label'       => __( 'Hover Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'default'     => '',
							'connections' => array( 'color' ),
						),
						'arrow_border'             => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-swiper-button',
								'property' => 'border',
							),
						),
						'arrow_border_hover'       => array(
							'type'        => 'color',
							'label'       => __( 'Border Hover Color', 'bb-powerpack' ),
							'show_reset'  => true,
							'default'     => '',
							'connections' => array( 'color' ),
						),
						'arrow_horizontal_padding' => array(
							'type'    => 'unit',
							'label'   => __( 'Horizontal Padding', 'bb-powerpack' ),
							'default' => '13',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.pp-swiper-button',
										'property' => 'padding-left',
										'unit'     => 'px',
									),
									array(
										'selector' => '.pp-swiper-button',
										'property' => 'padding-right',
										'unit'     => 'px',
									),
								),
							),
						),
						'arrow_vertical_padding'   => array(
							'type'    => 'unit',
							'label'   => __( 'Vertical Padding', 'bb-powerpack' ),
							'default' => '5',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.pp-swiper-button',
										'property' => 'padding-top',
										'unit'     => 'px',
									),
									array(
										'selector' => '.pp-swiper-button',
										'property' => 'padding-bottom',
										'unit'     => 'px',
									),
								),
							),
						),
						'arrow_spacing'            => array(
							'type'    => 'unit',
							'label'   => __( 'Spacing', 'bb-powerpack' ),
							'default' => '',
							'slider'  => true,
						),
						'arrow_opacity'            => array(
							'type'    => 'unit',
							'label'   => __( 'Opacity', 'bb-powerpack' ),
							'default' => '1',
							'slider'  => array(
								'min'  => 0,
								'max'  => 1,
								'step' => .1,
							),
						),
					),
				),
				'pagination_style' => array(
					'title'  => __( 'Pagination', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'pagination_bg_color'   => array(
							'type'        => 'color',
							'label'       => __( 'Background Color', 'bb-powerpack' ),
							'default'     => '999999',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-reviews-swiper .swiper-pagination-bullet',
								'property' => 'background-color',
							),
						),
						'pagination_bg_hover'   => array(
							'type'        => 'color',
							'label'       => __( 'Active Background Color', 'bb-powerpack' ),
							'default'     => '000000',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-reviews-swiper .swiper-pagination-bullet:hover, .pp-reviews-swiper .swiper-pagination-bullet-active',
								'property' => 'background',
							),
						),
						'bullets_width'         => array(
							'type'    => 'unit',
							'label'   => __( 'Size', 'bb-powerpack' ),
							'default' => '10',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.pp-reviews-swiper .swiper-pagination-bullet',
										'property' => 'width',
										'unit'     => 'px',
									),
									array(
										'selector' => '.pp-reviews-swiper .swiper-pagination-bullet',
										'property' => 'height',
										'unit'     => 'px',
									),
								),
							),
						),
						'bullets_border_radius' => array(
							'type'    => 'unit',
							'label'   => __( 'Border Radius', 'bb-powerpack' ),
							'default' => '100',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-reviews-swiper .swiper-pagination-bullet',
								'property' => 'border-radius',
								'unit'     => 'px',
							),
						),
						'bullets_spacing'      => array(
							'type'       => 'unit',
							'label'      => __( 'Spacing', 'bb-powerpack' ),
							'default'    => '5',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.swiper-pagination-bullets .swiper-pagination-bullet',
										'property' => 'margin-left',
										'unit'     => 'px !important',
									),
									array(
										'selector' => '.swiper-pagination-bullets .swiper-pagination-bullet',
										'property' => 'margin-right',
										'unit'     => 'px !important',
									),
								),
							),
						),
						'bullets_top_margin' => array(
							'type'       => 'unit',
							'label'      => __( 'Top Margin', 'bb-powerpack' ),
							'default'    => '5',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.swiper-pagination',
								'property' => 'margin-top',
								'unit'     => 'px',
							),
						),
					),
				),
			),
		),
		'typography' => array(
			'title'    => __( 'Typography', 'bb-powerpack' ),
			'sections' => array(
				'name_fonts'   => array(
					'title'  => __( 'Name', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'name_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-name',
							),
						),
					),
				),
				'title_fonts'  => array(
					'title'  => __( 'Title', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'title_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-title',
							),
						),
					),
				),
				'review_fonts' => array(
					'title'     => __( 'Review', 'bb-powerpack' ),
					'collapsed' => true,
					'fields'    => array(
						'content_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-review-text',
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
	'pp_reviews_form',
	array(
		'title' => __( 'Add Review', 'bb-powerpack' ),
		'tabs'  => array(
			'general'     => array( // Tab.
				'title'    => __( 'General', 'bb-powerpack' ), // Tab title.
				'sections' => array( // Tab Section.
					'review_fields' => array(
						'title'  => '',
						'fields' => array(
							'image'  => array(
								'type'        => 'photo',
								'label'       => __( 'Image', 'bb-powerpack' ),
								'show_remove' => true,
								'connections' => array( 'photo' ),
							),
							'name'   => array(
								'type'        => 'text',
								'label'       => __( 'Name', 'bb-powerpack' ),
								'connections' => array( 'string', 'html', 'url' ),
							),
							'title'  => array(
								'type'        => 'text',
								'label'       => __( 'Title', 'bb-powerpack' ),
								'connections' => array( 'string', 'html', 'url' ),
							),
							'rating' => array(
								'type'    => 'unit',
								'label'   => __( 'Rating', 'bb-powerpack' ),
								'default' => '',
							),
							'icon'   => array(
								'type'        => 'icon',
								'label'       => __( 'Icon', 'bb-powerpack' ),
								'show_remove' => true,
								'default'	=> 'fas fa-quote-right'
							),
							'review' => array(
								'type'        => 'editor',
								'label'       => __( 'Review', 'bb-powerpack' ),
								'connections' => array( 'string', 'html', 'url' ),
							),

						),
					),
				),
			),
			'review_styles' => array(
				'title'    => __( 'Style', 'bb-powerpack' ),
				'sections' => array(
					'styles'    => array(
						'title'  => '',
						'fields' => array(
							'slide_background'       => array(
								'type'        => 'color',
								'label'       => __( 'Background color', 'bb-powerpack' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review',
									'property' => 'background-color',
								),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
							'slide_background_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Background Hover Color', 'bb-powerpack' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review:hover',
									'property' => 'background-color',
								),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
					'separator' => array(
						'title'  => 'Separator',
						'fields' => array(
							'separator_color'       => array(
								'type'        => 'color',
								'label'       => __( 'Color', 'bb-powerpack' ),
								'default'     => '',
								'show_reset'  => true,
								'show_alpha'  => true,
								'connections' => array( 'color' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review-header',
									'property' => 'border-bottom-color',
								),
							),
							'separator_color_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Hover Color', 'bb-powerpack' ),
								'show_reset'  => true,
								'show_alpha'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
					'icon'      => array(
						'title'  => 'Icon',
						'fields' => array(
							'icon_color'       => array(
								'type'        => 'color',
								'label'       => __( 'Color', 'bb-powerpack' ),
								'default'     => '',
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review-icon',
									'property' => 'color',
								),
							),
							'icon_color_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Hover Color', 'bb-powerpack' ),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
					'name'      => array(
						'title'  => 'Name',
						'fields' => array(
							'name_color'       => array(
								'type'        => 'color',
								'label'       => __( 'Name Color', 'bb-powerpack' ),
								'default'     => '',
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review-name',
									'property' => 'color',
								),
							),
							'name_color_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Name Hover Color', 'bb-powerpack' ),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
					'title'     => array(
						'title'  => 'Title',
						'fields' => array(
							'title_color'       => array(
								'type'        => 'color',
								'label'       => __( 'Color', 'bb-powerpack' ),
								'default'     => '',
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review-title',
									'property' => 'color',
								),
							),
							'title_color_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Hover Color', 'bb-powerpack' ),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
					'content'   => array(
						'title'  => __( 'Content', 'bb-powerpack' ),
						'fields' => array(
							'content_color'       => array(
								'type'        => 'color',
								'label'       => __( 'Color', 'bb-powerpack' ),
								'default'     => '',
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.pp-review-title',
									'property' => 'color',
								),
							),
							'content_color_hover' => array(
								'type'        => 'color',
								'label'       => __( 'Hover Color', 'bb-powerpack' ),
								'show_alpha'  => true,
								'show_reset'  => true,
								'connections' => array( 'color' ),
							),
						),
					),
				),
			),
		),
	)
);

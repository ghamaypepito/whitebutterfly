(function($){

	FLBuilder.registerModuleHelper('pp-registration-form', {
		form_fields: '',
		form_fields_count: 1,
		form_fields_ids: [],
		form_field_last_id: '',
		active_field_data: {},

		parentForm: '',
		fieldsData: {},

		rules: {
            'form_border_width': {
                number: true
            },
            'form_border_radius': {
                number: true,
            },
            'form_shadow_h': {
                number: true
            },
            'form_shadow_v': {
                number: true
            },
            'form_shadow_blur': {
                number: true
            },
            'form_shadow_spread': {
                number: true
            },
            'form_shadow_opacity': {
                number: true
            },
            'form_padding': {
                number: true
            },
            'title_margin': {
                number: true
            },
            'description_margin': {
                number: true
            },
            'input_field_height': {
                number: true
            },
            'input_textarea_height': {
                number: true
            },
            'input_field_background_opacity': {
                number: true
            },
            'input_field_border_width': {
                number: true
            },
            'input_field_border_radius': {
                number: true
            },
            'input_field_padding': {
                number: true
            },
            'input_field_margin': {
                number: true
            },
            'button_width_size': {
                number: true
            },
            'button_background_opacity': {
                number: true
            },
            'button_border_width': {
                number: true
            },
            'button_border_radius': {
                number: true
            },
            'title_font_size': {
                number: true
            },
            'title_line_height': {
                number: true
            },
            'description_font_size': {
                number: true
            },
            'description_line_height': {
                number: true
            },
            'label_font_size': {
                number: true
            },
            'input_font_size': {
                number: true
            },
            'button_font_size': {
                number: true
            },
            'validation_error_font_size': {
                number: true
            },
            'success_message_font_size': {
                number: true
			},
        },


		init: function()
		{
			var form      	= $( '.fl-builder-settings' ),
				//action    = form.find( 'select[name=success_action]' ),
				fieldType = form.find( 'select[name=field_type]' );

			if ( $( '.fl-builder-settings[data-type="pp-registration-form"]' ).length > 0 ) {
				this.parentForm = $( '.fl-builder-settings[data-type="pp-registration-form"]' );
			}

			if ( form.find('#fl-field-form_fields').length > 0 ) {
				this.form_fields = form.find('#fl-field-form_fields .fl-builder-field-multiple');
			}

			this._fieldTypeChanged();
			this._actionChanged();

			fieldType.on('change', this._fieldTypeChanged );
			
            // Toggle reCAPTCHA display
            $('input[name=enable_recaptcha]').on('change', $.proxy(this._toggleReCaptcha, this));
            $('select[name=field_type]').on('change', $.proxy(this._toggleReCaptcha, this));
            $('select[name=recaptcha_validate_type]').on('change', $.proxy(this._toggleReCaptcha, this));
            $('input[name=recaptcha_theme]').on('change', $.proxy(this._toggleReCaptcha, this));

            // Render reCAPTCHA after layout rendered via AJAX
            if (window.onLoadPPReCaptcha) {
                $(FLBuilder._contentClass).on('fl-builder.layout-rendered', onLoadPPReCaptcha);
            }
		},

		submit: function()
		{
			return true;
		},

		_fieldTypeChanged: function()
		{
			var form 		= $( '.fl-builder-settings' ),
				fieldType 	= form.find( 'select[name=field_type]' );

			if ( 'recaptcha' === fieldType.val() ) {
				form.find('#fl-field-required').hide();
			} else {
				form.find('#fl-field-required').show();
			}
		},

		_actionChanged: function()
		{
			var form      = $( '.fl-builder-settings' ),
				redirect  = form.find( 'input[name=redirect]' ).val(),
				url       = form.find( 'input[name=redirect_url]' );

			url.rules('remove');

			if ( 'yes' == redirect ) {
				url.rules( 'add', { required: true } );
			}
		},

        /**
		 * Custom preview method for reCAPTCHA settings
		 *
		 * @param  object event  The event type of where this method been called
		 * @since 2.8
		 */
        _toggleReCaptcha: function (event) {
            var form = $('.fl-builder-settings'),
                nodeId = form.attr('data-node'),
                enabled = form.find('input[name=enable_recaptcha]'),
                captchaKey = pp_recaptcha.site_key,
                captType = form.find('select[name=recaptcha_validate_type]').val(),
                theme = form.find('input[name=recaptcha_theme]').val(),
                reCaptcha = $('.fl-node-' + nodeId).find('.pp-grecaptcha'),
                reCaptchaId = nodeId + '-pp-grecaptcha',
                target = typeof event !== 'undefined' ? $(event.currentTarget) : null,
                selectEvent = target != null && typeof target.attr('name') !== typeof undefined && target.attr('name') === 'field_type',
                typeEvent = target != null && typeof target.attr('name') !== typeof undefined && target.attr('name') === 'recaptcha_validate_type',
                themeEvent = target != null && typeof target.attr('name') !== typeof undefined && target.attr('name') === 'recaptcha_theme',
                scriptTag = $('<script>'),
				isRender = false;
				
			if ( 'invisible_v3' === captType ) {
				captType = 'invisible';
				captchaKey = pp_recaptcha.v3_site_key;
			}

            // Add library if not exists
            if (0 === $('script#g-recaptcha-api').length) {
                scriptTag
                    .attr('src', 'https://www.google.com/recaptcha/api.js?onload=onLoadPPReCaptcha&render=explicit')
                    .attr('type', 'text/javascript')
                    .attr('id', 'g-recaptcha-api')
                    .attr('async', 'async')
                    .attr('defer', 'defer')
                    .appendTo('body');
            }

            if ('yes' === enabled.val() && captchaKey.length) {

                // reCAPTCHA is not yet exists
                if (0 === reCaptcha.length) {
                    isRender = true;
                }
                // If reCAPTCHA element exists, then reset reCAPTCHA if existing key does not matched with the input value
                else if ((selectEvent || typeEvent || themeEvent) && (reCaptcha.data('sitekey') != captchaKey || reCaptcha.data('validate') != captType || reCaptcha.data('theme') != theme)
                ) {
                    reCaptcha.parent().remove();
                    isRender = true;
                }
                else {
                    reCaptcha.parent().show();
                }

                if (isRender) {
                    this._renderReCaptcha(nodeId, reCaptchaId, captchaKey, captType, theme);
                }
            }
            else if ('yes' === enabled.val() && captchaKey.length === 0 && reCaptcha.length > 0) {
                reCaptcha.parent().remove();
            }
            else if ('yes' !== enabled.val() && reCaptcha.length > 0) {
                reCaptcha.parent().hide();
            }
        },

		/**
		 * Render Google reCAPTCHA
		 *
		 * @param  string nodeId  		The current node ID
		 * @param  string reCaptchaId  	The element ID to render reCAPTCHA
		 * @param  string reCaptchaKey  The reCAPTCHA Key
		 * @param  string reCaptType  	Checkbox or invisible
		 * @param  string theme         Light or dark
		 * @since 2.8
		 */
        _renderReCaptcha: function (nodeId, reCaptchaId, reCaptchaKey, reCaptType, theme) {
            var captchaField = $('<div class="pp-rf-field pp-rf-field-required" data-field-type="recaptcha">'),
                captchaElement = $('<div id="' + reCaptchaId + '" class="pp-grecaptcha">'),
                widgetID;

            captchaElement.attr('data-sitekey', reCaptchaKey);
            captchaElement.attr('data-validate', reCaptType);
            captchaElement.attr('data-theme', theme);

            // Append recaptcha element to an appended element
            captchaField
                .html(captchaElement)
                .insertBefore($('.fl-node-' + nodeId).find('.pp-registration-form .pp-rf-fields-wrap > .pp-rf-button-wrap'));

            widgetID = grecaptcha.render(reCaptchaId, {
                sitekey: reCaptchaKey,
                size: reCaptType,
                theme: theme
            });
            captchaElement.attr('data-widgetid', widgetID);
        }
	});

	FLBuilder.registerModuleHelper('pp_registration_form_fields', {
		init: function() {
			var form = $('.fl-builder-settings'),
				fieldTag = form.find( '#fl-field-field_tag input' ),
				fieldType = form.find( 'select[name="field_type"]' );

			fieldType.rules( 'remove' );
			fieldType.rules( 'add', { required: true } );

			fieldTag.val( '{{' + fieldType.val() + '}}' );

			fieldType.on( 'change', function() {
				fieldTag.val( '{{' + $(this).val() + '}}' );
			} );

			var formFields = FLBuilderSettingsForms.config.nodeSettings.form_fields;

			if ( formFields.length <= 0 ) {
				return;
			}

			formFields.forEach(function(item) {
				if ( 'undefined' !== typeof item ) {
					var field = JSON.parse(item);
					if ( '' !== field.field_type ) {
						fieldType.find( 'option[value="'+ field.field_type +'"]' ).attr( 'disabled', 'disabled' );
					}
				}
			});

			fieldType.find( 'option[value="'+ fieldType[0].value +'"]' ).removeAttr( 'disabled' );
			fieldType.find( 'option[value="static_text"]' ).removeAttr( 'disabled' );
		},

		_submit: function() {
			var formFields = FLBuilderSettingsForms.config.nodeSettings.form_fields;
			var addedFields = [];

			if ( formFields.length <= 0 ) {
				return true;
			}

			formFields.forEach(function(item) {
				if ( 'undefined' !== typeof item ) {
					var field = JSON.parse(item);
					addedFields.push( field.field_type );
				}
			});

			var form = $('.fl-builder-settings');
			var fieldType = form.find( 'select[name="field_type"]' );

			if ( $.inArray( fieldType.val(), addedFields ) < 0 ) {
				FLBuilder.alert( fieldType.find('option[value="' + fieldType.val() + '"]').html() + ' has already added.' );
				return false;
			}

			return true;
		}
	} );

})(jQuery);

;(function($) {

	PPLoginForm = function( settings ) {
		this.id			= settings.id;
		this.node 		= $('.fl-node-' + this.id);
		this.messages	= settings.messages;
		this.settings 	= settings;

		this._init();
	};

	PPLoginForm.prototype = {
		settings: {},

		_init: function() {
			if ( this.node.find( '.pp-login-form' ).length > 0 ) {
				this.node.find( '.pp-login-form' ).on( 'submit', $.proxy( this._loginFormSubmit, this ) );
			}

			if ( this.node.find( '.pp-login-form--lost-pass' ).length > 0 ) {
				this.node.find( '.pp-login-form--lost-pass' ).on( 'submit', $.proxy( this._lostPassFormSubmit, this ) );
			}

			if ( this.node.find( '.pp-login-form--reset-pass' ).length > 0 ) {
				this.node.find( '.pp-login-form--reset-pass' ).on( 'submit', $.proxy( this._resetPassFormSubmit, this ) );
			}
		},

		_loginFormSubmit: function(e) {
			e.preventDefault();

			var theForm 	= $(e.target),
				username 	= theForm.find( 'input[name="log"]' ),
				password 	= theForm.find( 'input[name="pwd"]' ),
				remember 	= theForm.find( 'input[name="rememberme"]' ),
				redirect 	= theForm.find( 'input[name="redirect_to"]' );
		
			username.parent().find( '.pp-lf-error' ).remove();
			password.parent().find( '.pp-lf-error' ).remove();

			if ( '' === username.val().trim() ) {
				$('<span class="pp-lf-error">').insertAfter( username ).html( this.messages.empty_username );
				return;
			}

			if ( '' === password.val() ) {
				$('<span class="pp-lf-error">').insertAfter( password ).html( this.messages.empty_password );
				return;
			}

			var formData = new FormData( theForm[0] );

			formData.append( 'action', 'pp_lf_process_login' );
			formData.append( 'page_url', this.settings.page_url );
			formData.append( 'username', username.val() );
			formData.append( 'password', password.val() );

			if ( redirect.length > 0 && '' !== redirect.val() ) {
				formData.append( 'redirect', redirect.val() );
			}

			if ( remember.length > 0 && remember.is(':checked') ) {
				formData.append( 'remember', '1' );
			}

			theForm.addClass( 'pp-event-disabled' );

			this._ajax( formData, function( response ) {
				if ( ! response.success ) {
					theForm.removeClass( 'pp-event-disabled' );
					theForm.find( '.pp-lf-error' ).remove();
					$('<span class="pp-lf-error">').appendTo( theForm ).html( response.data );
				} else {
					if ( response.data.redirect_url ) {
						window.location.href = response.data.redirect_url;
					}
				}
			} );
		},

		_lostPassFormSubmit: function(e) {
			e.preventDefault();

			var theForm = $(e.target),
				username = theForm.find( 'input[name="user_login"]' ),
				self = this;

			username.parent().find( '.pp-lf-error' ).remove();

			if ( '' === username.val().trim() ) {
				$('<span class="pp-lf-error">').insertAfter( username ).html( this.messages.empty_username );
				return;
			}

			var formData = new FormData( theForm[0] );

			formData.append( 'action', 'pp_lf_process_lost_pass' );
			formData.append( 'page_url', this.settings.page_url );

			theForm.addClass( 'pp-event-disabled' );

			this._ajax( formData, function( response ) {
				theForm.removeClass( 'pp-event-disabled' );
				if ( ! response.success ) {
					username.parent().find( '.pp-lf-error' ).remove();
					$('<span class="pp-lf-error">').insertAfter( username ).html( response.data );
				} else {
					$('<p class="pp-lf-success">').insertAfter( theForm ).html( self.messages.email_sent );
					theForm.hide();
				}
			} );
		},

		_resetPassFormSubmit: function(e) {
			e.preventDefault();

			var theForm = $(e.target),
				password_1 = theForm.find( 'input[name="password_1"]' ),
				password_2 = theForm.find( 'input[name="password_2"]' ),
				self	= this;

			password_1.parent().find( '.pp-lf-error' ).remove();
			password_2.parent().find( '.pp-lf-error' ).remove();

			if ( '' === password_1.val() ) {
				$('<span class="pp-lf-error">').insertAfter( password_1 ).html( this.messages.empty_password_1 );
				return;
			}

			if ( '' === password_2.val() ) {
				$('<span class="pp-lf-error">').insertAfter( password_2 ).html( this.messages.empty_password_2 );
				return;
			}

			var formData = new FormData( theForm[0] );

			formData.append( 'action', 'pp_lf_process_reset_pass' );
			formData.append( 'page_url', this.settings.page_url );

			theForm.addClass( 'pp-event-disabled' );

			this._ajax( formData, function( response ) {
				theForm.removeClass( 'pp-event-disabled' );
				if ( ! response.success ) {
					theForm.find( '.pp-lf-error' ).remove();
					$('<span class="pp-lf-error">').appendTo( theForm ).html( response.data );
				} else {
					$('<p class="pp-lf-success">').insertAfter( theForm ).html( self.messages.reset_success );
					theForm.hide();
				}
			} );
		},

		_ajax: function( data, callback ) {
			$.ajax({
				type: 'POST',
				url: FLBuilderLayoutConfig.paths.wpAjaxUrl,
				data: data,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function( response ) {
					if ( 'function' === typeof callback ) {
						callback( response );
					}
				},
			});
		},
	};

})(jQuery);
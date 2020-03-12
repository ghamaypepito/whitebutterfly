;(function($) {

	PPSearchForm = function(settings) {
		this.id 	= settings.id;
		this.node 	= $('.fl-node-' + this.id);
		this.form	= this.node.find('.pp-search-form');

		this._init();
	};

	PPSearchForm.prototype = {
		id: '',
		node: '',
		form: '',

		_init: function() {
			this.form.find('.pp-search-form__input').on('focus', $.proxy(function() {
				this.form.addClass('pp-search-form--focus');
			}, this));
			this.form.find('.pp-search-form__input').on('blur', $.proxy(function() {
				this.form.removeClass('pp-search-form--focus');
			}, this));

			this.form.find('.pp-search-form__toggle').on('click', $.proxy(function() {
				this.form.find('.pp-search-form__container').addClass('pp-search-form--lightbox').find('.pp-search-form__input').focus();
				this._focus( this.form );
			}, this));

			this.form.find('.pp-search-form--lightbox-close').on('click', $.proxy(function() {
				this.form.find('.pp-search-form__container').removeClass('pp-search-form--lightbox');
			}, this));

			var self = this;

			// close modal box on Esc key press.
			$(document).keyup(function(e) {
                if ( 27 == e.which && self.form.find('.pp-search-form--lightbox').length > 0 ) {
                    self.form.find('.pp-search-form__container').removeClass('pp-search-form--lightbox');
                }
			});
		},

		_focus: function( form ) {
			var $el = form.find('.pp-search-form__input');

			// If this function exists... (IE 9+)
			if ( $el[0].setSelectionRange ) {
				// Double the length because Opera is inconsistent about whether a carriage return is one character or two.
				var len = $el.val().length * 2;

				// Timeout seems to be required for Blink
				setTimeout(function() {
					$el[0].setSelectionRange( len, len );
				}, 1);
			} else {
				// As a fallback, replace the contents with itself
				// Doesn't work in Chrome, but Chrome supports setSelectionRange
				$el.val( $el.val() );
			}
		}
	};

})(jQuery);
(function($){

	FLBuilder.registerModuleHelper('pp-social-share', {
		init: function() {
			var form = $('.fl-builder-settings');
			form.find('[name="columns"]').on('change', $.proxy(this.showAlignField, this));
			this.showAlignField();
		},

		showAlignField: function() {
			var form = $('.fl-builder-settings');
			if ( '0' == form.find('select[name="columns"]').val() ) {
				form.find('#fl-field-alignment').show();
			} else {
				form.find('#fl-field-alignment').hide();
			}
		}
	} );

	FLBuilder.registerModuleHelper('pp_social_share_form', {
		
		rules: {
			size: {
				number: true,
				required: true
			}
		},

		_getField: function (name) {
			var form = $('.fl-builder-settings');
			var field = form.find('[name="' + name + '"]');

			return field;
		},

		init: function () {
			this._getField('social_share_type').on('change', $.proxy(this.hideFields, this));
			this.hideFields();
		},

		hideFields: function () {
			var type = this._getField('social_share_type').val();

			if ('fb-messenger' === type) {
				$('#fl-field-social_share_type .fl-field-description').show();
			} else {
				$('#fl-field-social_share_type .fl-field-description').hide();
			}
		},

	});

})(jQuery);
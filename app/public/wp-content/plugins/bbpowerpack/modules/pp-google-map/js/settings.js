;(function($) {
	
	FLBuilder.registerModuleHelper( 'pp-google-map', {
		init: function() {
			var form = $('.fl-builder-settings');
			var self = this;
			
			self._toggleOverlayFields();

			form.find('#fl-field-map_source').on('DOMSubtreeModified', function() {
				self._toggleOverlayFields();
			});
		},

		_toggleOverlayFields: function() {
			var form  = $('.fl-builder-settings');
			var field = form.find('select[name="map_source"]');

			if ( ( '' === field.val() || 'acf' !== field.val() ) || 'no' === form.find('select[name="acf_enable_info"]').val() ) {
				form.find('#fl-field-acf_info_window_text').hide();
			} else {
				form.find('#fl-field-acf_info_window_text').show();
			}

			if ( ( '' === field.val() || 'acf_options_page' !== field.val() ) || 'no' === form.find('select[name="acf_options_enable_info"]').val() ) {
				form.find('#fl-field-acf_options_info_window_text').hide();
			} else {
				form.find('#fl-field-acf_options_info_window_text').show();
			}
		}
	});
})(jQuery);
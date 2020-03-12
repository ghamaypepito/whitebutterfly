;(function($) {
	FLBuilder.registerModuleHelper('pp-reviews', {
		init: function()
		{
			var self = this;
			var $form = $('.fl-builder-settings'),
				$type = $form.find('select[name="carousel_type"]'),
				$effect = $form.find('select[name="effect"]');
				$review_source = $form.find('select[name=review_source]');
				

			this._toggleFields( $form, $type, $effect );
			this._changeReviewsType();

			$type.on('change', function() {
				self._toggleFields( $form, $type, $effect );
			});

			$effect.on('change', function() {
				self._toggleFields( $form, $type, $effect );
			});

			$review_source.on('change', this._changeReviewsType);
		},

		_toggleFields: function( $form, $type, $effect )
		{
			if ( 'carousel' === $type.val() ) {
				$form.find('#fl-field-effect').show();

				if ( 'slide' === $effect.val() ) {
					$form.find('#fl-field-columns').show();
					$form.find('#fl-field-slides_to_scroll').show();
				}
				if ( 'fade' === $effect.val() || 'cube' === $effect.val() ) {
					$form.find('#fl-field-columns').hide();
					$form.find('#fl-field-slides_to_scroll').hide();
				}
			}
			if ( 'coverflow' === $type.val() ) {
				$form.find('#fl-field-effect').hide();
				$form.find('#fl-field-columns').show();
				$form.find('#fl-field-slides_to_scroll').show();
			}
		},

		_changeReviewsType: function() {
			var form = $('.fl-builder-settings');
			form.find('.all-notice').hide();
			form.find('.google-notice').hide();
			form.find('.yelp-notice').hide();

			if ( 'default' !== form.find('select[name=review_source]').val() ) {
				form.find( '#fl-builder-settings-tab-reviews .fl-builder-settings-tab-description' ).show();
			} else {
				form.find( '#fl-builder-settings-tab-reviews .fl-builder-settings-tab-description' ).hide();
			}

			if (form.find('select[name=review_source]').val() === 'google') {

				form.find('.google-notice').show();
				form.find('.all-notice').hide();
				form.find('.yelp-notice').hide();

			}
			if (form.find('select[name=review_source]').val() === 'all') {

				form.find('.all-notice').show();
				form.find('.google-notice').hide();
				form.find('.yelp-notice').hide();

			}
			if (form.find('select[name=review_source]').val() === 'yelp') {

				form.find('.all-notice').hide();
				form.find('.google-notice').hide();
				form.find('.yelp-notice').show();

			}
		},

	} );
})(jQuery);
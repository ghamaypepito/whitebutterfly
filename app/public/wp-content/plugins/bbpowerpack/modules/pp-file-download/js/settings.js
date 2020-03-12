(function($){
	FLBuilder.registerModuleHelper('pp-file-download', {

		rules: {
			file: {
				required: true
			}
		},

		init: function () {

			$('input[name=bg_color]').on('change', this._bgColorChange);
			this._bgColorChange();
		},

		_bgColorChange: function()
		{
			var bgColor = $( 'input[name=bg_color]' ),
				style   = $( '#pp-builder-settings-section-style' );

			if ( '' == bgColor.val() ) {
				style.hide();
			}
			else {
				style.show();
			}
		}
	});

})(jQuery);
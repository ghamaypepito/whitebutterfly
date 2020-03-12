;(function($) {
	$('.fl-node-<?php echo $id; ?> .pp-social-share-content .pp-share-button:not(.pp-share-button-print) .pp-share-button-link').on( 'click', function (e) {
		e.preventDefault();
		window.open($(this).attr('href'), '', 'height=450, width=550, top=' + ($(window).height() / 2 - 275) + ', left=' + ($(window).width() / 2 - 225) + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=1');
		return false;
	});
})(jQuery);

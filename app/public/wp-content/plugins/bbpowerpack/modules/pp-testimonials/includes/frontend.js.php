(function($) {
<?php if ( count( $settings->testimonials ) > 1 && 'slider' == $settings->layout ) : ?>
	// Clear the controls in case they were already created.
	$('.fl-node-<?php echo $id; ?> .pp-arrow-wrapper .pp-slider-next').empty();
	$('.fl-node-<?php echo $id; ?> .pp-arrow-wrapper .pp-slider-prev').empty();

	if( $(window).width() > 767 ) {
		<?php $responsive = 0; ?>
	}
	else {
		<?php $responsive = 1; ?>
	}

	// Create the slider.
	var sliderOptions = {
		auto : true,
		autoStart : <?php echo $settings->autoplay; ?>,
		autoHover : <?php echo $settings->hover_pause; ?>,
		<?php echo ( 'no' == $settings->adaptive_height ) ? 'adaptiveHeight: true' : 'adaptiveHeight: false'; ?>,
		pause : <?php echo $settings->pause * 1000; ?>,
		mode : '<?php echo $settings->transition; ?>',
		speed : <?php echo $settings->speed * 1000; ?>,
		infiniteLoop : <?php echo $settings->loop; ?>,
		pager : <?php echo $settings->dots; ?>,
		nextSelector : '.fl-node-<?php echo $id; ?> .pp-arrow-wrapper .pp-slider-next',
		prevSelector : '.fl-node-<?php echo $id; ?> .pp-arrow-wrapper .pp-slider-prev',
		nextText: '<i class="fa fa-chevron-circle-right"></i>',
		prevText: '<i class="fa fa-chevron-circle-left"></i>',
		controls : <?php echo $settings->arrows; ?>,
		onSliderLoad: function() {
			$('.fl-node-<?php echo $id; ?> .pp-testimonials').addClass('pp-testimonials-loaded');
		}
	};
	var carouselOptions = {
		minSlides : <?php echo ( 1 == $settings->carousel ) ? $settings->min_slides : 1; ?>,
		maxSlides : <?php echo ( 1 == $settings->carousel ) ? $settings->max_slides : 1; ?>,
		moveSlides : <?php echo ( 1 == $settings->carousel ) ? $settings->move_slides : 1; ?>,
		<?php if ( 1 == $settings->carousel ) { ?>
			<?php if ( ! empty( $settings->slide_width ) ) { ?>
				slideWidth : <?php echo $settings->slide_width; ?>,
			<?php } else { ?>
				slideWidth : 0,
			<?php } ?>
		<?php } else { ?>
			slideWidth : 0,
		<?php } ?>
		slideMargin : <?php echo ( 1 == $settings->carousel ) ? $settings->slide_margin : 0; ?>,
	};
	if($(window).width() <= <?php echo $global_settings->medium_breakpoint; ?>) {
		var carouselOptions = {
			minSlides : <?php echo ( 1 == $settings->carousel && ! empty( $settings->min_slides_medium ) ) ? $settings->min_slides_medium : 1; ?>,
			maxSlides : <?php echo ( 1 == $settings->carousel && ! empty( $settings->max_slides_medium ) ) ? $settings->max_slides_medium : 1; ?>,
			moveSlides : <?php echo ( 1 == $settings->carousel && ! empty( $settings->move_slides_medium ) ) ? $settings->move_slides_medium : 1; ?>,
			<?php if ( 1 == $settings->carousel ) { ?>
			<?php if ( ! empty( $settings->slide_width_medium ) ) { ?>
				slideWidth : <?php echo $settings->slide_width_medium; ?>,
			<?php } else { ?>
				slideWidth : 0,
			<?php } ?>
			<?php } else { ?>
				slideWidth : 0,
			<?php } ?>
			slideMargin : <?php echo ( 1 == $settings->carousel && ! empty( $settings->slide_margin_medium ) ) ? $settings->slide_margin_medium : 0; ?>,
		};
	}
	if($(window).width() <= <?php echo $global_settings->responsive_breakpoint; ?>) {
		var carouselOptions = {
			minSlides : <?php echo ( 1 == $settings->carousel && ! empty( $settings->min_slides_responsive ) ) ? $settings->min_slides_responsive : 1; ?>,
			maxSlides : <?php echo ( 1 == $settings->carousel && ! empty( $settings->max_slides_responsive ) ) ? $settings->max_slides_responsive : 1; ?>,
			moveSlides : <?php echo ( 1 == $settings->carousel && ! empty( $settings->move_slides_responsive ) ) ? $settings->move_slides_responsive : 1; ?>,
			<?php if ( 1 == $settings->carousel ) { ?>
			<?php if ( ! empty( $settings->slide_width_responsive ) ) { ?>
				slideWidth : <?php echo $settings->slide_width_responsive; ?>,
			<?php } else { ?>
				slideWidth : 0,
			<?php } ?>
			<?php } else { ?>
				slideWidth : 0,
			<?php } ?>
			slideMargin : <?php echo ( 1 == $settings->carousel && ! empty( $settings->slide_margin_responsive ) ) ? $settings->slide_margin_responsive : 0; ?>,
		};
	}

	$(window).on('load', function() {
		$('.fl-node-<?php echo $id; ?> .pp-testimonials-slider .pp-testimonials').bxSlider($.extend({}, sliderOptions, carouselOptions));
	});
<?php endif; ?>

<?php if ( 'grid' == $settings->layout && 'yes' == $settings->adaptive_height ) { ?>
	function equalheight() {
		var maxHeight = 0;
		$('.fl-node-<?php echo $id; ?> .pp-testimonials-grid .pp-testimonial .pp-content-wrapper').each(function(index) {
			if(($(this).outerHeight()) > maxHeight) {
				maxHeight = $(this).outerHeight();
			}
		});
		$('.fl-node-<?php echo $id; ?> .pp-testimonials-grid .pp-testimonial .pp-content-wrapper').css('height', maxHeight + 'px');
	}
	$(document).ready(function () {
		equalheight();
	});
	$(window).bind("resize", equalheight);
<?php } ?>

})(jQuery);

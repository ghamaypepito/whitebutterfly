<div class="pp-rating">
	<?php
	$icon = '&#9733;';

	if ( 'outline' === $settings->star_style ) {
		$icon = '&#9734;';
	}

	$floored_rating = (int) $rating;
	$stars_html     = '';

	for ( $stars = 1; $stars <= 5; $stars++ ) {
		if ( $stars <= $floored_rating ) {
			$stars_html .= '<i class="pp-star-full">' . $icon . '</i>';
		} elseif ( $floored_rating + 1 === $stars && $rating !== $floored_rating ) {
			$stars_html .= '<i class="pp-star-' . ( $rating - $floored_rating ) * 10 . '">' . $icon . '</i>';
		} else {
			$stars_html .= '<i class="pp-star-empty">' . $icon . '</i>';
		}
	}

	echo $stars_html;
	?>
</div>
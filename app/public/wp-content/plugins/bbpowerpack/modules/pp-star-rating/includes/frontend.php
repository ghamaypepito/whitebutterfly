<?php
$title  = $settings->rating_title;
$rating = $settings->rating;
$layout = 0;
?>
<div class="pp-rating-content">

<?php
if ( 'bottom' === $settings->star_position ) {
	?>
	<div class="pp-rating-title"><?php echo $title; ?></div>
	<?php
}
?>
	<div class="pp-rating">
	<?php
	$icon = '&#9733;';

	if ( 'outline' === $settings->star_style ) {
		$icon = '&#9734;';
	}

	$rating         = (float) $rating > $settings->rating_scale ? $settings->rating_scale : $rating;
	$floored_rating = (int) $rating;
	$stars_html     = '';

	for ( $stars = 1; $stars <= $settings->rating_scale; $stars++ ) {
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

<?php
if ( 'top' === $settings->star_position ) {
	?>
	<div class="pp-rating-title"><?php echo $title; ?></div>
	<?php
}
?>
</div>

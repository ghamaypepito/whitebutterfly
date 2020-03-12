<?php
$is_social_reviews = isset( $settings->review_source ) && 'default' !== $settings->review_source;

$reviews = $module->get_reviews();

if ( $is_social_reviews && is_wp_error( $reviews ) ) {
	if ( pp_is_builder_active() ) {
		echo $reviews->get_error_message();
	}
	return;
}
?>

<div class="pp-reviews-wrapper pp-reviews-<?php echo $settings->review_source; ?>">
	<div class="pp-reviews-swiper swiper-container">
		<!-- Slides wrapper -->
		<?php if ( $is_social_reviews ) { ?>
		<div class="swiper-wrapper">
			<!-- Slides -->
			<?php
			foreach ( $reviews as $key => $review ) {
				?>
					<div class="pp-review-item swiper-slide">
						<div class="pp-review">
							<div class="pp-review-header">
								<div class="pp-review-image">
									<img src="<?php echo $review['profile_photo_url']; ?>" alt="<?php echo $review['author_name']; ?>" />
								</div>
								<cite class="pp-review-cite">
									<span class="pp-review-name"><?php echo $review['author_name']; ?></span>
									<?php
									$rating = (float) $review['rating'] > 5 ? 5 : $review['rating'];
									if ( $rating > 0 ) {
										include $module->dir . 'includes/rating-html.php';
									}
									?>
									<span class="pp-review-title"><?php echo $review['title']; ?></span>
								</cite>
								<div class="pp-review-icon">
									<?php if ( 'yelp' === $review['source'] && ! empty( $module->get_source_icon( 'yelp' ) ) ) { ?>
									<i class="<?php echo $module->get_source_icon( 'yelp' ); ?>" aria-hidden="true"></i>
									<?php } else {
										echo $module->get_source_icon( 'google' );
									}
									?>
								</div>
							</div>
							<div class="pp-review-content">
								<div class="pp-review-text">
									<?php echo $review['text']; ?>
								</div>
							</div>
						</div>
					</div>
				<?php
			}
			?>
		</div>
		<?php } else { ?>
			<div class="swiper-wrapper">
			<!-- Slides -->
			<?php
			for ( $i = 0; $i < count( $settings->reviews ); $i++ ) {
				$review = $settings->reviews[ $i ];
				if ( ! is_object( $review ) ) {
					continue;
				}
				?>
			<div class="pp-review-item pp-review-item-<?php echo $i; ?> swiper-slide">
				<div class="pp-review">
					<div class="pp-review-header">
						<div class="pp-review-image">
							<?php
							$img_src = empty( $review->image_src ) ? BB_POWERPACK_URL . 'images/user.png' : $review->image_src;
							?>
							<img src="<?php echo $img_src; ?>" alt="<?php echo $review->name; ?>" />
						</div>
						<cite class="pp-review-cite">
							<span class="pp-review-name"><?php echo $review->name; ?></span>
							<?php
							$rating = (float) $review->rating > 5 ? 5 : $review->rating;
							if ( $rating > 0 ) {
								include $module->dir . 'includes/rating-html.php';
							}
							?>
							<span class="pp-review-title"><?php echo $review->title; ?></span>
						</cite>
						<div class="pp-review-icon"><i class="<?php echo $review->icon; ?>" aria-hidden="true"></i></div>
					</div>
					<div class="pp-review-content">
						<div class="pp-review-text">
							<?php echo $review->review; ?>
						</div>
					</div>
				</div>
			</div>
				<?php
			}
			?>
		</div>
		<?php } ?>
		<!-- Pagination, if required -->
		<div class="swiper-pagination"></div>

	</div>
	<?php if ( 'yes' === $settings->slider_navigation ) { ?>
		<div class="pp-swiper-button pp-swiper-button-prev"><span class="fa fa-angle-left"></span></div>
		<div class="pp-swiper-button pp-swiper-button-next"><span class="fa fa-angle-right"></span></div>
	<?php } ?>
</div>

<?php
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'slide_border',
			'selector'     => ".fl-node-$id .pp-review",
		)
	);

	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'arrow_border',
			'selector'     => ".fl-node-$id .pp-swiper-button",
		)
	);

	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'image_border',
			'selector'     => ".fl-node-$id .pp-review-image img",
		)
	);

	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'name_typography',
			'selector'     => ".fl-node-$id .pp-review-name",
		)
	);

	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'title_typography',
			'selector'     => ".fl-node-$id .pp-review-title",
		)
	);

	FLBuilderCSS::typography_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'content_typography',
			'selector'     => ".fl-node-$id .pp-review-text",
		)
	);

	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'carousel_height',
			'selector'     => ".fl-node-$id .pp-review",
			'prop'         => 'height',
			'unit'         => 'px',
		)
	);


	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'slide_padding',
			'selector'     => ".fl-node-$id .pp-review",
			'prop'         => 'padding',
			'unit'         => 'px',
		)
	);

	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'star_size',
			'selector'     => ".fl-node-$id .pp-rating i",
			'prop'         => 'font-size',
			'unit'         => 'px',
		)
	);

	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'image_size',
			'selector'     => ".fl-node-$id .pp-review-image img",
			'prop'         => 'height',
			'unit'         => 'px',
		)
	);

	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'star_spacing',
			'selector'     => ".fl-node-$id .pp-rating > i",
			'prop'         => 'margin-right',
			'unit'         => 'px',
		)
	);

	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'image_spacing',
			'selector'     => ".fl-node-$id .pp-review-cite",
			'prop'         => 'margin-left',
			'unit'         => 'px',
		)
	);

	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'bullets_spacing',
			'selector'     => ".fl-node-$id .swiper-container-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet",
			'prop'         => 'margin-left',
			'unit'         => 'px',
		)
	);

	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'bullets_spacing',
			'selector'     => ".fl-node-$id .swiper-container-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet",
			'prop'         => 'margin-right',
			'unit'         => 'px',
		)
	);

	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'bullets_top_margin',
			'selector'     => ".fl-node-$id .swiper-pagination",
			'prop'         => 'margin-top',
			'unit'         => 'px',
		)
	);

	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'icon_spacing',
			'selector'     => ".fl-node-$id .pp-review-icon",
			'prop'         => 'margin-left',
			'unit'         => 'px',
		)
	);

	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'icon_size',
			'selector'     => ".fl-node-$id .pp-review-icon i:before",
			'prop'         => 'font-size',
			'unit'         => 'px',
		)
	);
	?>

.fl-node-<?php echo $id; ?> .pp-reviews-wrapper {
	<?php
	if ( 'no' === $settings->slider_navigation ) {
		?>
			width: 100%;
		<?php
	}
	?>
}

.fl-node-<?php echo $id; ?> .pp-review {
	<?php
	if ( '' !== $settings->slide_padding ) {
		?>
			padding: <?php echo $settings->slide_padding; ?>px;
			<?php
	}

	if ( '' !== $settings->carousel_height ) {
		?>
			height: <?php echo $settings->carousel_height; ?>px;
			<?php
	}
	?>
	<?php
	if ( '' !== $settings->slide_background ) {
		?>
			background-color: <?php echo pp_get_color_value( $settings->slide_background ); ?>;
			<?php
	}
	?>
}
.fl-node-<?php echo $id; ?> .pp-review:hover {
	<?php

	if ( '' !== $settings->slide_background_hover ) {
		?>
		background-color: <?php echo pp_get_color_value( $settings->slide_background_hover ); ?>;
		<?php
	}
	?>
}

.fl-node-<?php echo $id; ?> .pp-review-header {
	<?php

	if ( 'show' === $settings->separator ) {
		?>
		border-bottom: 1px solid #e1e8ed;
		<?php
	}

	if ( '' !== $settings->separator_color ) {
		?>
		border-bottom-color: <?php echo pp_get_color_value( $settings->separator_color ); ?>;
		<?php
	}
	?>
}

.fl-node-<?php echo $id; ?> .pp-review-name {
	<?php
	if ( '' !== $settings->name_color ) {
		?>
		color: <?php echo pp_get_color_value( $settings->name_color ); ?>;
		<?php
	}

	if ( '' !== $settings->name_margin_top ) {
		?>
		margin-top: <?php echo $settings->name_margin_top; ?>px;
		<?php
	}

	if ( '' !== $settings->name_margin_bottom ) {
		?>
		margin-bottom: <?php echo $settings->name_margin_bottom; ?>px;
		<?php
	}

	?>
}

.fl-node-<?php echo $id; ?> .pp-review-title {
	<?php
	if ( '' !== $settings->title_color ) {
		?>
		color: <?php echo pp_get_color_value( $settings->title_color ); ?>;
		<?php
	}

	if ( '' !== $settings->title_margin_top ) {
		?>
		margin-top: <?php echo $settings->title_margin_top; ?>px;
		<?php
	}

	if ( '' !== $settings->title_margin_bottom ) {
		?>
		margin-bottom: <?php echo $settings->title_margin_bottom; ?>px;
		<?php
	}

	?>
}

.fl-node-<?php echo $id; ?> .pp-review-content {
	<?php
	if ( '' !== $settings->content_color ) {
		?>
		color: <?php echo pp_get_color_value( $settings->content_color ); ?>;
		<?php
	}

	if ( '' !== $settings->content_margin_top ) {
		?>
		margin-top: <?php echo $settings->content_margin_top; ?>px;
		<?php
	}

	if ( '' !== $settings->content_margin_bottom ) {
		?>
		margin-bottom: <?php echo $settings->content_margin_bottom; ?>px;
		<?php
	}

	?>
}

.fl-node-<?php echo $id; ?> .pp-review:hover .pp-review-header {
	<?php if ( isset( $settings->separator_color_hover ) && ! empty( $settings->separator_color_hover ) ) { ?>
	border-bottom-color: <?php echo pp_get_color_value( $settings->separator_color_hover ); ?>;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-review-image {
	<?php if ( isset( $settings->image_vertical_alignment ) && ! empty( $settings->image_vertical_alignment ) ) { ?>
		align-self: <?php echo $settings->image_vertical_alignment; ?>;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-review-image img {
	<?php if ( isset( $settings->image_size ) && ! empty( $settings->image_size ) ) { ?>
		width: <?php echo $settings->image_size; ?>px;
		height: <?php echo $settings->image_size; ?>px;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-review-cite {
	<?php if ( isset( $settings->image_spacing ) && ! empty( $settings->image_spacing ) ) { ?>
		margin-left: <?php echo $settings->image_spacing; ?>px;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-review-icon {
<?php if ( isset( $settings->icon_size ) && ! empty( $settings->icon_size ) ) { ?>
	font-size: <?php echo $settings->icon_size; ?>px;
<?php } if ( isset( $settings->icon_vertical_alignment ) && '' !== $settings->icon_vertical_alignment ) { ?>
	align-self: <?php echo $settings->icon_vertical_alignment; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-review-icon i {
<?php if ( isset( $settings->icon_vertical_alignment ) && '' !== $settings->icon_vertical_alignment ) { ?>
	vertical-align: <?php echo $settings->icon_vertical_alignment; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> swiper-pagination-bullet {
	<?php if ( isset( $settings->pagination_bg_color ) && ! empty( $settings->pagination_bg_color ) ) { ?>
	background: <?php echo pp_get_color_value( $settings->pagination_bg_color ); ?>;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .swiper-pagination-bullet-active{
	<?php if ( isset( $settings->pagination_bg_hover ) && ! empty( $settings->pagination_bg_hover ) ) { ?>
	background: <?php echo pp_get_color_value( $settings->pagination_bg_hover ); ?>;
	<?php } ?>
}


.fl-node-<?php echo $id; ?> .pp-swiper-button {
	<?php if ( isset( $settings->arrow_font_size ) && ! empty( $settings->arrow_font_size ) ) { ?>
	font-size: <?php echo $settings->arrow_font_size; ?>px;
		<?php
	}

	if ( isset( $settings->arrow_bg_color ) && ! empty( $settings->arrow_bg_color ) ) {
		?>
	background-color: <?php echo pp_get_color_value( $settings->arrow_bg_color ); ?>;
		<?php
	}

	if ( isset( $settings->arrow_color ) && ! empty( $settings->arrow_color ) ) {
		?>
	color: <?php echo pp_get_color_value( $settings->arrow_color ); ?>;
		<?php
	}

	if ( isset( $settings->arrow_horizontal_padding ) && ! empty( $settings->arrow_horizontal_padding ) ) {
		?>
	padding-left: <?php echo $settings->arrow_horizontal_padding; ?>px;
	padding-right: <?php echo $settings->arrow_horizontal_padding; ?>px;
		<?php
	}

	if ( isset( $settings->arrow_vertical_padding ) && ! empty( $settings->arrow_vertical_padding ) ) {
		?>
	padding-bottom: <?php echo $settings->arrow_vertical_padding; ?>px;
	padding-top: <?php echo $settings->arrow_vertical_padding; ?>px;
		<?php
	}

	if ( isset( $settings->arrow_opacity ) && ! empty( $settings->arrow_opacity ) ) {
		?>
	opacity: <?php echo $settings->arrow_opacity; ?>;
		<?php
	}
	?>
}

.fl-node-<?php echo $id; ?> .pp-swiper-button:hover {
	<?php
	if ( isset( $settings->arrow_bg_hover ) && ! empty( $settings->arrow_bg_hover ) ) {
		?>
	background-color: <?php echo pp_get_color_value( $settings->arrow_bg_hover ); ?>;
		<?php
	}

	if ( isset( $settings->arrow_color_hover ) && ! empty( $settings->arrow_color_hover ) ) {
		?>
	color: <?php echo pp_get_color_value( $settings->arrow_color_hover ); ?>;
		<?php
	}

	if ( isset( $settings->arrow_border_hover ) && ! empty( $settings->arrow_border_hover ) ) {
		?>
	border-color: <?php echo pp_get_color_value( $settings->arrow_border_hover ); ?>;
		<?php
	}
	?>
}

.fl-node-<?php echo $id; ?> .pp-swiper-button-prev {
<?php
if ( '' !== $settings->arrow_spacing ) {
	?>
	left: <?php echo $settings->arrow_spacing; ?>px;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-swiper-button-next {
<?php
if ( '' !== $settings->arrow_spacing ) {
	?>
	right: <?php echo $settings->arrow_spacing; ?>px;
	<?php } ?>
}


.fl-node-<?php echo $id; ?> .swiper-pagination-bullet {
<?php
if ( '' !== $settings->bullets_width ) {
	?>
		width: <?php echo $settings->bullets_width; ?>px;
		height: <?php echo $settings->bullets_width; ?>px;
	<?php
}

if ( '' !== $settings->bullets_border_radius ) {
	?>
		border-radius: <?php echo $settings->bullets_border_radius; ?>px;
	<?php
}

?>
}


.fl-node-<?php echo $id; ?> .pp-review .pp-review-icon i {
	<?php if ( isset( $settings->icon_color ) && ! empty( $settings->icon_color ) ) { ?>
	color: <?php echo pp_get_color_value( $settings->icon_color ); ?>;
	<?php } ?>
}

.fl-node-<?php echo $id; ?> .pp-review:hover .pp-review-icon i {
	<?php if ( isset( $settings->icon_color_hover ) && ! empty( $settings->icon_color_hover ) ) { ?>
	color: <?php echo pp_get_color_value( $settings->icon_color_hover ); ?>;
	<?php } ?>
}


<?php
$number_reviews = count( $settings->reviews );
for ( $i = 0; $i < $number_reviews; $i++ ) {
	$review = $settings->reviews[ $i ];
	?>
	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review {
		<?php if ( isset( $review->slide_background ) && ! empty( $review->slide_background ) ) { ?>
		background-color: <?php echo pp_get_color_value( $review->slide_background ); ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review:hover {
		<?php if ( isset( $review->slide_background_hover ) && ! empty( $review->slide_background_hover ) ) { ?>
		background-color: <?php echo pp_get_color_value( $review->slide_background_hover ); ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review:hover .pp-review-header {
		<?php if ( isset( $review->separator_color_hover ) && ! empty( $review->separator_color_hover ) ) { ?>
		border-bottom-color: <?php echo pp_get_color_value( $review->separator_color_hover ); ?>;
		<?php } ?>

	}

	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review .pp-review-icon i {
		<?php if ( isset( $review->icon_color ) && ! empty( $review->icon_color ) ) { ?>
		color: <?php echo pp_get_color_value( $review->icon_color ); ?>;
		<?php } ?>

	}

	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review:hover .pp-review-icon i {

		<?php if ( isset( $review->icon_color_hover ) && ! empty( $review->icon_color_hover ) ) { ?>
		color: <?php echo pp_get_color_value( $review->icon_color_hover ); ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review-header {
	<?php if ( isset( $review->separator_color ) && ! empty( $review->separator_color ) ) { ?>
		border-bottom-color: <?php echo pp_get_color_value( $review->separator_color ); ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review-name {
	<?php if ( isset( $review->name_color ) && ! empty( $review->name_color ) ) { ?>
		color: <?php echo pp_get_color_value( $review->name_color ); ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review:hover .pp-review-name {
	<?php if ( isset( $review->name_color_hover ) && ! empty( $review->name_color_hover ) ) { ?>
		color: <?php echo pp_get_color_value( $review->name_color_hover ); ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review-title {
	<?php if ( isset( $review->title_color ) && ! empty( $review->title_color ) ) { ?>
		color: <?php echo pp_get_color_value( $review->title_color ); ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review:hover .pp-review-title {
	<?php if ( isset( $review->title_color_hover ) && ! empty( $review->title_color_hover ) ) { ?>
		color: <?php echo pp_get_color_value( $review->title_color_hover ); ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review-content {
	<?php if ( isset( $review->content_color ) && ! empty( $review->content_color ) ) { ?>
		color: <?php echo pp_get_color_value( $review->content_color ); ?>;
		<?php } ?>
	}

	.fl-node-<?php echo $id; ?> .pp-review-item-<?php echo $i; ?> .pp-review:hover .pp-review-content {
	<?php if ( isset( $review->content_color_hover ) && ! empty( $review->content_color_hover ) ) { ?>
		color: <?php echo pp_get_color_value( $review->content_color_hover ); ?>;
		<?php } ?>
	}

	<?php
}

?>

.fl-node-<?php echo $id; ?> .pp-rating {
<?php
if ( isset( $settings->star_alignment ) && '' !== $settings->star_alignment ) {
	?>
		text-align: <?php echo $settings->star_alignment; ?>;
		<?php
}
?>
}

.fl-node-<?php echo $id; ?> .pp-rating i {
	<?php
	if ( isset( $settings->star_size ) && '' !== $settings->star_size ) {
		?>
		font-size: <?php echo $settings->star_size; ?>px;
		<?php
	}

	if ( isset( $settings->star_unmarked_color ) && '' !== $settings->star_unmarked_color ) {
		?>
		color: <?php echo pp_get_color_value( $settings->star_unmarked_color ); ?>;
		<?php
	}

	?>
}
.fl-node-<?php echo $id; ?> .pp-rating i:before {
	<?php
	if ( isset( $settings->star_color ) && '' !== $settings->star_color ) {
		?>
		color: <?php echo pp_get_color_value( $settings->star_color ); ?>;
		<?php
	}
	?>
}

.fl-node-<?php echo $id; ?> .pp-rating i:not(.pp-star-empty):before {
	content: "\002605";
}

<?php
if ( '' !== $settings->star_spacing ) {
	?>
	.fl-node-<?php echo $id; ?> .pp-rating i:not(:last-of-type) {
		margin-right: <?php echo $settings->star_spacing; ?>px;
	}
	<?php
}
?>

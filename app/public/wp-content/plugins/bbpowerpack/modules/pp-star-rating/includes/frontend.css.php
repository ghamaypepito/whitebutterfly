<?php
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .pp-rating-title",
	)
);
?>

.fl-module-pp-star-rating.fl-node-<?php echo $id; ?> .pp-rating i {
	font-size: <?php echo $settings->star_icon_size . 'px'; ?>;
	color: <?php echo pp_get_color_value( $settings->rating_unmarked_color ); ?>;
}


.fl-module-pp-star-rating.fl-node-<?php echo $id; ?> .pp-rating i:not(.pp-star-empty):before {
	content: "\002605";
}

<?php
if ( '' !== $settings->star_icon_spacing ) {
	?>
	.fl-module-pp-star-rating.fl-node-<?php echo $id; ?> .pp-rating i:not(:last-of-type) {
		margin-right: <?php echo $settings->star_icon_spacing . 'px'; ?>;
	}
	<?php
}
?>

.fl-module-pp-star-rating.fl-node-<?php echo $id; ?> .pp-rating i:before {
	color: <?php echo pp_get_color_value( $settings->rating_color ); ?>;
}

.fl-module-pp-star-rating.fl-node-<?php echo $id; ?> .pp-rating-content {

	<?php
	if ( 'inline' === $settings->rating_layout && 'justify' === $settings->alignment ) {
		?>
		display: -webkit-box;
		display: -webkit-flex;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-align: center;
		-webkit-align-items: center;
		-ms-flex-align: center;
		align-items: center;
		flex-direction: row;
		<?php
	} elseif ( 'inline' === $settings->rating_layout && 'justify' !== $settings->alignment ) {
		?>
		display: block;
		<?php
	}
	?>
}

<?php
if ( 'justify' !== $settings->alignment ) {
	?>
	.fl-module-pp-star-rating.fl-node-<?php echo $id; ?> .pp-rating-content {
		text-align: <?php echo $settings->alignment; ?>;
	}
	<?php
}
?>
.fl-module-pp-star-rating.fl-node-<?php echo $id; ?> .pp-rating-content .pp-rating-title {
	color: <?php echo pp_get_color_value( $settings->title_color ); ?>;

	<?php
	if ( 'inline' === $settings->rating_layout ) {
		if ( 'justify' === $settings->alignment ) {
			?>
		margin-right: auto;
			<?php
		} else {

			if ( 'top' === $settings->star_position ) {
				?>
				margin-left: <?php echo $settings->title_spacing . 'px'; ?>;
				<?php
			} else {
				?>
				margin-right: <?php echo $settings->title_spacing . 'px'; ?>;
				<?php
			}
		}
	}
	?>
}

<?php
if ( 'inline' === $settings->rating_layout && 'justify' !== $settings->alignment ) {
	?>
	.fl-module-pp-star-rating.fl-node-<?php echo $id; ?> .pp-rating-content > div {
		display: inline-block;
	}
	<?php
}
?>

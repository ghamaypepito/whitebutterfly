<?php
$image_alt = get_post_meta( $settings->image, '_wp_attachment_image_alt', true );

$currency_iso_code = '';
$step_img_position = ' pp-step-img-' . $settings->step_img_position;

if ( isset( $settings->total_time ) && ! empty( $settings->total_time ) ) {
	$settings->time_minutes = $settings->total_time;
	unset( $settings->total_time );
}

$years   = $settings->time_years;
$months  = $settings->time_months;
$days    = $settings->time_days;
$hours   = $settings->time_hours;
$minutes = $settings->time_minutes;
$time    = '';

$total_time = array(
	// translators: %s for time duration.
	'year'   => ! empty( $years ) ? sprintf( _n( '%s year', '%s years', $years, 'bb-powerpack' ), number_format_i18n( $years ) ) : '',
	// translators: %s for time duration.
	'month'  => ! empty( $months ) ? sprintf( _n( '%s month', '%s months', $months, 'bb-powerpack' ), number_format_i18n( $months ) ) : '',
	// translators: %s for time duration.
	'day'    => ! empty( $days ) ? sprintf( _n( '%s day', '%s days', $days, 'bb-powerpack' ), number_format_i18n( $days ) ) : '',
	// translators: %s for time duration.
	'hour'   => ! empty( $hours ) ? sprintf( _n( '%s hour', '%s hours', $hours, 'bb-powerpack' ), number_format_i18n( $hours ) ) : '',
	// translators: %s for time duration.
	'minute' => ! empty( $minutes ) ? sprintf( _n( '%s minute', '%s minutes', $minutes, 'bb-powerpack' ), number_format_i18n( $minutes ) ) : '',
);
foreach ( $total_time as $time_key => $duration ) {
	if ( empty( $duration ) ) {
		unset( $total_time[ $time_key ] );
	}
}

if ( ! empty( $total_time ) ) {

	$time = implode( ', ', $total_time );

	if ( ! empty( $settings->total_time_text ) ) {
		$time_text = $settings->total_time_text . ' ' . $time;
	} else {
		$time_text = $time;
	}
}

?>

<div class="pp-how-to-wrap pp-clearfix">
	<?php
	if ( 'yes' === $settings->enable_schema ) {
		$how_to_title       = $settings->title;
		$how_to_description = json_encode( $settings->description );
		$how_to_image       = $settings->image_src;
		$show_advanced      = $settings->show_advanced;
		$years              = ( '' !== $settings->time_years ) ? $settings->time_years : '0';
		$months             = ( '' !== $settings->time_months ) ? $settings->time_months : '0';
		$days               = ( '' !== $settings->time_days ) ? $settings->time_days : '0';
		$hours              = ( '' !== $settings->time_hours ) ? $settings->time_hours : '0';
		$minutes            = ( '' !== $settings->time_minutes ) ? $settings->time_minutes : '0';
		$y                  = ( 525600 * $years );
		$m                  = ( 43200 * $months );
		$d                  = ( 1440 * $days );
		$h                  = ( 60 * $hours );
		$schema_time        = $y + $m + $d + $h + $minutes;
		$estimated_cost     = $settings->estimated_cost;
		$currency_iso_code  = $settings->currency_iso_code;
		$add_supply         = $settings->add_supply;
		$supplies           = $settings->pp_supply;
		$add_tools          = $settings->add_tool;
		$tools              = $settings->pp_tool;
		$steps_form         = $settings->step_data;

		// @codingStandardsIgnoreStart.
		?>
		<script type="application/ld+json">
			{
				"@context":    "http://schema.org",
				"@type":       "HowTo",
				"name":        "<?php echo $how_to_title; ?>",
				"description": <?php echo $how_to_description; ?>,
				"image":       "<?php echo $how_to_image; ?>",

				<?php if ( 'yes' === $show_advanced ) { ?>
					<?php if ( '' !== $estimated_cost ) { ?>
					"estimatedCost": {
						"@type": "MonetaryAmount",
						"currency": "<?php echo $currency_iso_code; ?>",
						"value": "<?php echo $estimated_cost; ?>"
					},
					<?php } ?>
					<?php if ( 0 !== $schema_time ) { ?>
					"totalTime": "PT<?php echo $schema_time; ?>M",
					<?php } ?>

					<?php
					if ( 'yes' === $add_supply && isset( $supplies ) ) {
						?>
						"supply": [
							<?php foreach ( $supplies as $key => $supply ) { ?>
								{
									"@type": "HowToSupply",
									"name": "<?php echo $supply; ?>"
								}<?php echo ( ( $key + 1 ) !== sizeof( $supplies ) ) ? ',' : ''; ?>
							<?php } ?>
						],
						<?php
					}
					if ( 'yes' === $add_tools && isset( $tools ) ) {
						?>
						"tool": [
							<?php foreach ( $tools as $key => $tool ) { ?>
								{
									"@type": "HowToTool",
									"name": "<?php echo $tool; ?>"
								}<?php echo ( ( $key + 1 ) !== sizeof( $tools ) ) ? ',' : ''; ?>
							<?php } ?>
						],
						<?php
					}
				}
				if ( isset( $steps_form ) ) {
					?>
				"step": [
					<?php
					foreach ( $steps_form as $key => $step ) {
						$step_id      = 'step-' . $id . '-' . ( $key + 1 );
						$step_image   = $step->step_image;
						$step_img_url = '';

						if ( ! empty( $step_image ) ) {
							$step_img_url = $step->step_image_src;
						}
						if ( isset( $step->step_link ) && ! empty( $step->step_link ) ) {
							$meta_link = $step->step_link;
						} else {
							$meta_link = get_permalink() . '#' . $step_id;
						}
						?>
						{
							"@type": "HowToStep",
							"name": "<?php echo $step->step_title; ?>",
							"text": <?php echo json_encode( $step->step_description ); ?>,
							"image": "<?php echo $step_img_url; ?>",
							"url": "<?php echo $meta_link; ?>"
						}<?php echo ( ( $key + 1 ) !== sizeof( $steps_form ) ) ? ',' : ''; ?>
					<?php } ?>
				] 
				<?php } ?>
			}
		</script>
	<?php } ?>
	<div class="pp-how-to-container pp-clearfix">
		<<?php echo $settings->title_tag; ?> class="pp-how-to-title"><?php echo $settings->title; ?></<?php echo $settings->title_tag; ?>>
		<div class="pp-how-to-description"><?php echo $settings->description; ?></div>
		<?php if ( isset( $settings->image_src ) && ! empty( $settings->image_src ) ) { ?>
		<div class="pp-how-to-image">
			<img src="<?php echo $settings->image_src; ?>" alt="<?php echo $image_alt; ?>"/>
		</div>
		<?php } ?>
		<?php if ( 'yes' === $settings->show_advanced ) { ?>
			<div class="pp-how-to-slug">
				<?php if ( isset( $time_text ) && ! empty( $time_text ) ) { ?>
					<p class="pp-how-to-total-time">
						<?php echo $time_text; ?>
					</p>
				<?php } ?>
				<?php if ( isset( $settings->estimated_cost ) && ! empty( $settings->estimated_cost ) ) { ?>
					<p class="pp-how-to-estimated-cost">
						<?php echo ! empty( $settings->estimated_cost_text ) ? $settings->estimated_cost_text : ''; ?>
						<?php if ( isset( $settings->currency_iso_code ) && ! empty( $settings->currency_iso_code ) ) { ?>
							<span><?php echo $settings->currency_iso_code . $settings->estimated_cost; ?></span>
						<?php } ?>

					</p>
				<?php } ?>
			</div>

			<?php if ( 'yes' === $settings->add_supply ) { ?>
				<div class="pp-how-to-supply">
					<?php if ( isset( $settings->supply_title ) && ! empty( $settings->supply_title ) ) { ?>
						<<?php echo $settings->supply_title_tag; ?> class="pp-how-to-supply-title"><?php echo $settings->supply_title; ?></<?php echo $settings->supply_title_tag; ?>>
					<?php } ?>
					<?php
					if ( isset( $settings->pp_supply ) ) {
						foreach ( $settings->pp_supply as $key => $supply ) {
							?>
							<div class="pp-supply pp-supply-<?php echo $key + 1; ?>">
								<?php if ( isset( $settings->supply_icon ) && ! empty( $settings->supply_icon ) ) { ?>
									<i class="pp-supply-icon <?php echo $settings->supply_icon; ?>"></i>
								<?php } ?>
								<span><?php echo $supply; ?></span>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>
			<?php
			if ( 'yes' === $settings->add_tool ) {
				?>
				<div class="pp-how-to-tool">
					<?php if ( isset( $settings->tool_title ) && ! empty( $settings->tool_title ) ) { ?>
						<<?php echo $settings->tool_title_tag; ?> class="pp-how-to-tool-title"><?php echo $settings->tool_title; ?></<?php echo $settings->tool_title_tag; ?>>
					<?php } ?>
					<?php
					if ( isset( $settings->pp_tool ) ) {
						foreach ( $settings->pp_tool as $key => $tool ) {
							?>
							<div class="pp-tool pp-tool-<?php echo $key + 1; ?>">
								<?php if ( isset( $settings->tool_icon ) && ! empty( $settings->tool_icon ) ) { ?>
									<i class="pp-tool-icon <?php echo $settings->tool_icon; ?>"></i>
								<?php } ?>
								<span><?php echo $tool; ?></span>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>
		<?php } // End if(). ?>
		<?php if ( isset( $settings->step_data ) ) { ?>
			<div class="pp-how-to-steps" id="step-<?php echo $id; ?>">
				<?php
				if ( isset( $settings->step_section_title ) && ! empty( $settings->step_section_title ) ) {
					?>
					<<?php echo $settings->step_section_title_tag; ?> class="pp-how-to-step-section-title"><?php echo $settings->step_section_title; ?></<?php echo $settings->step_section_title_tag; ?>>
				<?php } ?>
				<?php
				foreach ( $settings->step_data as $key => $step ) {
					$target   = isset( $step->step_link_target ) ? ' target="' . $step->step_link_target . '"' : '';
					$nofollow = isset( $step->step_link_nofollow ) && 'yes' === $step->step_link_nofollow ? ' rel="nofollow"' : '';
					$step_id  = 'step-' . $id . '-' . ( $key + 1 );
					?>
					<div id="<?php echo $step_id; ?>" class="pp-how-to-step<?php echo $step_img_position; ?><?php echo isset( $step->step_image ) && ! empty( $step->step_image ) ? ' pp-has-img' : ' pp-no-img'; ?>">
						<div class="pp-how-to-step-content">
						<?php if ( isset( $step->step_title ) && ! empty( $step->step_title ) ) { ?>
							<?php if ( isset( $step->step_link ) && ! empty( $step->step_link ) ) { ?>
								<a href="<?php echo $step->step_link; ?>"<?php echo $target; ?><?php echo $nofollow; ?>>
							<?php } ?>
								<div class="pp-how-to-step-title"><?php echo $step->step_title; ?></div>
							<?php if ( isset( $step->step_link ) && ! empty( $step->step_link ) ) { ?>
								</a>
							<?php } ?>

							<?php if ( isset( $step->step_description ) && ! empty( $step->step_description ) ) { ?>
								<div class="pp-how-to-step-description"><?php echo $step->step_description; ?></div>
							<?php } ?>

						<?php } else { ?>
							<?php if ( isset( $step->step_link ) && ! empty( $step->step_link ) ) { ?>
								<a href="<?php echo $step->step_link; ?>"<?php echo $target; ?><?php echo $nofollow; ?>>
							<?php } ?>
								<?php if ( isset( $step->step_description ) && ! empty( $step->step_description ) ) { ?>
									<div class="pp-how-to-step-description"><?php echo $step->step_description; ?></div>
								<?php } ?>

							<?php if ( isset( $step->step_link ) && ! empty( $step->step_link ) ) { ?>
								</a>
							<?php } ?>

						<?php } ?>
						</div>
						<?php
						if ( isset( $step->step_image ) && ! empty( $step->step_image ) ) {
							$step_image_alt = get_post_meta( $step->step_image, '_wp_attachment_image_alt', true );
							?>
							<div class="pp-how-to-step-image">
								<img src="<?php echo $step->step_image_src; ?>" alt="<?php echo $step_image_alt; ?>" />
							</div>
						<?php } ?>
					</div>
				<?php } // End foreach(). ?>
			</div>
		<?php } // End if(). ?>

	</div>
</div>

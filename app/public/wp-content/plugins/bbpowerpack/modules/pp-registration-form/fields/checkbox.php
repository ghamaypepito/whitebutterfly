<div class="pp-field-subgroup pp-field-subgroup-<?php echo $field->layout; ?>">
<?php
	if ( ! empty( $field->field_options ) ) {
		$options_array = explode( "\n", $field->field_options );

		if ( is_array( $options_array ) ) {
			for ( $i = 0; $i < count( $options_array ); $i++ ) {
				if ( empty( trim( $options_array[ $i ] ) ) ) {
					continue;
				}
				
				$value = explode( '*', trim( $options_array[ $i ] ) );
				?>
				<span class="pp-field-option">
				<?php
				if ( 2 == count( $value ) ) { ?>
					<input type="checkbox" class="pp-rf-control" name="<?php echo $field_name; ?>[]" id="<?php echo $field_id; ?>-<?php echo $i; ?>" value="<?php echo $value[1]; ?>" checked="checked" />
					<label class="pp-rf-field-label" for="<?php echo $field_id; ?>-<?php echo $i; ?>"><?php echo $value[1]; ?></label>
				<?php } else { ?>
					<input type="checkbox" class="pp-rf-control" name="<?php echo $field_name; ?>[]" id="<?php echo $field_id; ?>-<?php echo $i; ?>" value="<?php echo $value[0]; ?>" checked="checked" />
					<label class="pp-rf-field-label" for="<?php echo $field_id; ?>-<?php echo $i; ?>"><?php echo $value[0]; ?></label>
				<?php } ?>
				</span>
				<?php
			}
		}
	}
?>
</div>
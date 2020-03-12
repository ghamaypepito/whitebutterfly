<?php
	$options = '';

	if ( ! empty( $field->field_options ) ) {
		$options_array = explode( "\n", $field->field_options );

		if ( is_array( $options_array ) ) {
			for( $i = 0; $i < count( $options_array ); $i++ ) {
				if ( empty( trim( $options_array[ $i ] ) ) ) {
					continue;
				}
				
				$value = explode( ':', trim( $options_array[ $i ] ) );
				
				if ( 2 == count( $value ) ) {
					$options .= '<option value="' . $value[1] . '" selected="selected">' . $value[1] . '</option>';
				} else {
					$options .= '<option value="' . $value[0] . '">' . $value[0] . '</option>';
				}
			}
		}
	}
?>
<select class="pp-rf-control" name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>">
	<?php echo $options; ?>
</select>
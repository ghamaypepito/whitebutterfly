<?php
	$value = ! empty( $field->default_value ) ? $field->default_value : '';
	$min = ! empty( $field->min_value ) ? $field->min_value : '';
	$max = ! empty( $field->max_value ) ? $field->max_value : '';
?>
<input type="number" class="pp-rf-control" name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>" value="<?php echo $value; ?>" placeholder="<?php echo $field->placeholder; ?>" min="<?php echo $min; ?>" max="<?php echo $max; ?>" />
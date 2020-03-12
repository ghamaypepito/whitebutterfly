<?php
	$value = ! empty( $field->default_value ) ? $field->default_value : '';
?>
<input type="text" class="pp-rf-control" name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>" value="<?php echo $value; ?>" placeholder="<?php echo $field->placeholder; ?>"<?php echo ( 'yes' == $field->required ) ? ' required="required" aria-required="true"' : ''; ?> />
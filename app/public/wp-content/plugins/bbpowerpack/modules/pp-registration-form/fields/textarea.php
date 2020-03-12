<?php
	$value = ! empty( $field->default_value ) ? $field->default_value : '';
	$rows = ! empty( $field->rows ) && absint( $field->rows ) ? $field->rows : 4;
?>
<textarea class="pp-rf-control" name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>" placeholder="<?php echo $field->placeholder; ?>" rows="<?php echo $rows; ?>">
<?php echo $value; ?>
</textarea>
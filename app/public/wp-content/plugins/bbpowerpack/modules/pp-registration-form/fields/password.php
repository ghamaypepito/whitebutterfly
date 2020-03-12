<div class="pp-rf-field-inner">
	<input
		type="password" 
		class="pp-rf-control" 
		name="<?php echo $field_name; ?>" 
		id="<?php echo $field_id; ?>" 
		value="" 
		placeholder="<?php echo $field->placeholder; ?>" 
		autocomplete="off" 
		autocorrect="off" 
		autocapitalize="off" 
		spellcheck="false" 
		aria-required="true" 
		aria-describedby="login_error" 
	/>
	<button type="button" class="pp-rf-toggle-pw hide-if-no-js" aria-label="<?php _e( 'Show password', 'bb-powerpack' ); ?>">
		<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
	</button>
</div>
<div class="pp-rf-pws-status"></div>
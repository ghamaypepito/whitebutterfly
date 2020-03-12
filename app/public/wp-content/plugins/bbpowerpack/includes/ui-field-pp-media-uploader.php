<#

var field = data.field,
	name = data.name,
	value = '' !== data.value ? data.value : '',
	upload_hidden = '' !== data.value ? ' hidden' : '',
	remove_hidden = '' == data.value ? ' hidden' : '',

	accept = '';
	button_text = '<?php echo __( 'Upload', 'bb-powerpack' ); ?> ';
	remove_button_text = '<?php echo __( 'Remove', 'bb-powerpack' ); ?> ';

	if ( typeof field.accept !== 'undefined' ) {
		accept = ' accept=' + field.accept;
	}
	if ( typeof field.button_text !== 'undefined' ) {
		button_text = field.button_text;
	}
	if ( typeof field.remove_button_text !== 'undefined' ) {
		remove_button_text = field.remove_button_text;
	}
	if ( '' !== value ) { var hidden = 'hidden' };
#>

<style>
	.pp-field-media-wrapper .hidden {
		display: none;
	}

	.pp-field-media-wrapper .fl-builder-button {
		padding: 10px;
		vertical-align: top;
	}
</style>
<div class="pp-field-media-wrapper">

	<div class="pp-media-input">
		<!--<input type="hidden" name="{{name}}" class="pp-field-media-file" value="{{value}}" />-->
		<div class="pp-media-action">
			<!-- Add & remove image buttons -->
			<p class="hide-if-no-js">
				<input
					type="text"
					name="{{name}}"
					value="{{value}}"
					class="pp-field-media-msg text"
					aria-invalid="false"
					style="width: 70%;"
					placeholder="<?php _e( 'Enter file URL or upload', 'bb-powerpack' ); ?>"
				/>
				<a class="fl-builder-button pp-field-media-upload{{upload_hidden}}"
					href="javascript:void();">
					{{button_text}}
				</a>
				<a class="fl-builder-button pp-field-media-remove{{remove_hidden}}"
					href="#" style="color: red;">
					{{remove_button_text}}
				</a>
			</p>
		</div>
	</div>
</div>

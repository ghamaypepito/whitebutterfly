<#

var field = data.field,
    name = data.name,
    value = '' !== data.value ? JSON.stringify( data.value ) : '';
	accept = '';
	button_text = '<?php echo __( 'Upload', 'bb-powerpack' ); ?> ';

	if ( typeof field.accept !== 'undefined' ) {
		accept = ' accept=' + field.accept;
	}
	if ( typeof field.button_text !== 'undefined' ) {
		button_text = field.button_text;
	}
#>
<input type="file" class="pp-field-file" name="{{name}}_file"{{accept}} />
<input type="hidden" class="pp-field-file-name" name="{{name}}" value="{{value}}" />
<input type="hidden" class="pp-field-file-nonce" name="{{name}}_nonce" value="<?php echo wp_create_nonce( 'pp_table_csv' ); ?>" />
<a href="javascript:void(0)" class="pp-field-file-upload">{{{button_text}}}</a>
<# if ( '' !== data.value ) { #>
<div class="pp-field-file-msg">
	<?php
	// translators: %s is for filename.
	echo sprintf( __(' Previous uploaded file: %s', 'bb-powerpack' ), '<strong>{{data.value.filename}}</strong>' );
	?>
</div>
<# } #>
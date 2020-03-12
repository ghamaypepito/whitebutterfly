<?php
$filename = '';
$filepath = '';
if ( isset( $settings->file ) ) {
	$filepath = $settings->file;
	$ext      = pathinfo( $filepath, PATHINFO_EXTENSION ); // to get extension
	$name     = pathinfo( $filepath, PATHINFO_FILENAME ); //file name without extension
	$filename = $name . '.' . $ext;
}

FLBuilder::render_module_html( 'pp-smart-button', array(
	'style'			=> $settings->style,
	'text'			=> $settings->text,
	'icon'			=> $settings->icon,
	'icon_position'	=> $settings->icon_position,
	'display_icon'	=> $settings->display_icon,
	'link'			=> $filepath,
	'download' 		=> $filename,
	'button_effect'	=> $settings->button_effect,
	'width'			=> $settings->width,
), $module );
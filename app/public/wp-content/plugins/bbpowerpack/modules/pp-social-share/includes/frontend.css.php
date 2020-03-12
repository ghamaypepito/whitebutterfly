<?php
FLBuilderCSS::responsive_rule( array(
	'settings'	=> $settings,
	'setting_name'	=> 'column_gap',
	'selector'	=> ".fl-node-$id .pp-social-share-content:not(.pp-social-share-col-0) .pp-social-share-inner",
	'prop'		=> 'grid-column-gap',
	'unit'		=> 'px',
) );

FLBuilderCSS::responsive_rule( array(
	'settings'	=> $settings,
	'setting_name'	=> 'row_gap',
	'selector'	=> ".fl-node-$id .pp-social-share-content:not(.pp-social-share-col-0) .pp-social-share-inner",
	'prop'		=> 'grid-row-gap',
	'unit'		=> 'px',
) );
?>

<?php if ( '' != $settings->column_gap ) { ?>
.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-social-share-col-0 .pp-social-share-inner {
	margin-left: calc( -<?php echo $settings->column_gap; ?>px / 2 );
	margin-right: calc( -<?php echo $settings->column_gap; ?>px / 2 );
}
<?php } ?>

<?php
FLBuilderCSS::responsive_rule( array(
	'settings'	=> $settings,
	'setting_name'	=> 'button_height',
	'selector'	=> ".fl-node-$id .pp-social-share-content .pp-share-button",
	'prop'		=> 'height',
	'unit'		=> 'px',
) );

FLBuilderCSS::responsive_rule( array(
	'settings'	=> $settings,
	'setting_name'	=> 'border_width',
	'selector'	=> ".fl-node-$id .pp-social-share-content .pp-share-button a",
	'prop'		=> 'border-width',
	'unit'		=> 'px',
) );
?>

<?php if ( '' != $settings->column_gap ) { ?>
.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-social-share-col-0 .pp-share-button {
	margin-left: calc( <?php echo $settings->column_gap; ?>px / 2 );
	margin-right: calc( <?php echo $settings->column_gap; ?>px / 2 );
}
<?php } ?>

<?php
FLBuilderCSS::responsive_rule( array(
	'settings'	=> $settings,
	'setting_name'	=> 'row_gap',
	'selector'	=> ".fl-node-$id .pp-social-share-content.pp-social-share-col-0 .pp-share-button",
	'prop'		=> 'margin-bottom',
	'unit'		=> 'px',
) );
?>

<?php if ( '' != $settings->icon_size ) { ?>
.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content .pp-share-button-icon i {
	font-size: <?php echo $settings->icon_size; ?>px;
}
<?php } ?>

<?php
// Text Typography
FLBuilderCSS::typography_field_rule( array(
	'settings'		=> $settings,
	'setting_name' 	=> 'title_typography',
	'selector' 		=> ".fl-node-$id .pp-social-share-content .pp-share-button-title",
) );

// Text Padding Left
FLBuilderCSS::responsive_rule( array(
	'settings'	=> $settings,
	'setting_name'	=> 'title_padding_left',
	'selector'	=> ".fl-node-$id .pp-social-share-content .pp-share-button .pp-share-button-text",
	'prop'		=> 'padding-left',
	'unit'		=> 'px',
) );

// Text Padding Right
FLBuilderCSS::responsive_rule( array(
	'settings'	=> $settings,
	'setting_name'	=> 'title_padding_right',
	'selector'	=> ".fl-node-$id .pp-social-share-content .pp-share-button .pp-share-button-text",
	'prop'		=> 'padding-right',
	'unit'		=> 'px',
) );
?>

<?php if ( 'custom' == $settings->color_source ) { ?>
	<?php if ( ! empty( $settings->primary_color ) ) { ?>
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-flat .pp-share-button a,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-gradient .pp-share-button a,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-boxed .pp-share-button .pp-share-button-icon,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-minimal .pp-share-button .pp-share-button-icon {
		background-color: <?php echo pp_get_color_value( $settings->primary_color ); ?>;
	}
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-framed .pp-share-button a,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-minimal .pp-share-button a,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-boxed .pp-share-button a,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-framed .pp-share-button a *,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-minimal .pp-share-button a *,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-boxed .pp-share-button a * {
		color: <?php echo pp_get_color_value( $settings->primary_color ); ?>;
	}
	<?php } ?>

	<?php if ( ! empty( $settings->secondary_color ) ) { ?>
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-flat .pp-share-button a *,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-gradient .pp-share-button a *,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-boxed .pp-share-button .pp-share-button-icon *,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-minimal .pp-share-button .pp-share-button-icon * {
		color: <?php echo pp_get_color_value( $settings->secondary_color ); ?>;
	}
	<?php } ?>

	<?php if ( ! empty( $settings->primary_hover_color ) ) { ?>
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-flat .pp-share-button:hover a,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-gradient .pp-share-button:hover a,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-boxed .pp-share-button:hover .pp-share-button-icon,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-minimal .pp-share-button:hover .pp-share-button-icon {
		background-color: <?php echo pp_get_color_value( $settings->primary_hover_color ); ?>;
	}

	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-framed .pp-share-button:hover a,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-minimal .pp-share-button:hover a,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-boxed .pp-share-button:hover a,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-framed .pp-share-button:hover a *,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-minimal .pp-share-button:hover a *,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-boxed .pp-share-button:hover a * {
		color: <?php echo pp_get_color_value( $settings->primary_hover_color ); ?>;
	}
	<?php } ?>

	<?php if ( ! empty( $settings->secondary_hover_color ) ) { ?>
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-flat .pp-share-button:hover a *,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-gradient .pp-share-button:hover a *,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-boxed .pp-share-button:hover .pp-share-button-icon *,
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-share-buttons-skin-minimal .pp-share-button:hover .pp-share-button-icon * {
		color: <?php echo pp_get_color_value( $settings->secondary_hover_color ); ?>;
	}
	<?php } ?>
<?php } ?>


@media only screen and ( max-width: <?php echo $global_settings->medium_breakpoint; ?>px ) {

	.pp-social-share-col-md-1 .pp-social-share-inner {
		grid-template-columns: repeat(1,1fr);
		display: grid;
	}

	.pp-social-share-col-md-2 .pp-social-share-inner {
		grid-template-columns: repeat(2,1fr);
		display: grid;
	}

	.pp-social-share-col-md-3 .pp-social-share-inner {
		grid-template-columns: repeat(3,1fr);
		display: grid;
	}

	.pp-social-share-col-md-4 .pp-social-share-inner {
		grid-template-columns: repeat(4,1fr);
		display: grid;
	}

	.pp-social-share-col-md-5 .pp-social-share-inner {
		grid-template-columns: repeat(5,1fr);
		display: grid;
	}

	.pp-social-share-col-md-6 .pp-social-share-inner {
		grid-template-columns: repeat(6,1fr);
		display: grid;
	}

	.pp-share-buttons-md-align-right .pp-social-share-inner {
		-webkit-box-pack: end;
			-ms-flex-pack: end;
				justify-content: flex-end;
	}

	.pp-share-buttons-md-align-left .pp-social-share-inner {
		-webkit-box-pack: start;
			-ms-flex-pack: start;
				justify-content: flex-start;
	}

	.pp-share-buttons-md-align-center .pp-social-share-inner {
		-webkit-box-pack: center;
			-ms-flex-pack: center;
				justify-content: center;
	}

	.pp-share-buttons-md-align-justify .pp-social-share-inner {
		-webkit-box-pack: justify;
			-ms-flex-pack: justify;
				justify-content: space-between;
	}

	<?php if ( '' != $settings->column_gap_medium ) { ?>
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-social-share-col-0 .pp-social-share-inner {
		margin-left: calc( -<?php echo $settings->column_gap_medium; ?>px / 2 );
		margin-right: calc( -<?php echo $settings->column_gap_medium; ?>px / 2 );
	}
	<?php } ?>

	<?php if ( '' != $settings->column_gap_medium ) { ?>
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-social-share-col-0 .pp-share-button {
		margin-left: calc( <?php echo $settings->column_gap_medium; ?>px / 2 );
		margin-right: calc( <?php echo $settings->column_gap_medium; ?>px / 2 );
	}
	<?php } ?>

	<?php if ( '' != $settings->icon_size_medium ) { ?>
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content .pp-share-button-icon i {
		font-size: <?php echo $settings->icon_size_medium; ?>px;
	}
	<?php } ?>
}

@media only screen and ( max-width: <?php echo $global_settings->responsive_breakpoint; ?>px ) {

	.pp-social-share-col-sm-1 .pp-social-share-inner {
		grid-template-columns: repeat(1,1fr);
		display: grid;
	}

	.pp-social-share-col-sm-2 .pp-social-share-inner {
		grid-template-columns: repeat(2,1fr);
		display: grid;
	}

	.pp-social-share-col-sm-3 .pp-social-share-inner {
		grid-template-columns: repeat(3,1fr);
		display: grid;
	}

	.pp-social-share-col-sm-4 .pp-social-share-inner {
		grid-template-columns: repeat(4,1fr);
		display: grid;
	}

	.pp-social-share-col-sm-5 .pp-social-share-inner {
		grid-template-columns: repeat(5,1fr);
		display: grid;
	}

	.pp-social-share-col-sm-6 .pp-social-share-inner {
		grid-template-columns: repeat(6,1fr);
		display: grid;
	}

	.pp-share-buttons-sm-align-right .pp-social-share-inner {
		-webkit-box-pack: end;
			-ms-flex-pack: end;
				justify-content: flex-end;
	}

	.pp-share-buttons-sm-align-left .pp-social-share-inner {
		-webkit-box-pack: start;
			-ms-flex-pack: start;
				justify-content: flex-start;
	}

	.pp-share-buttons-sm-align-center .pp-social-share-inner {
		-webkit-box-pack: center;
			-ms-flex-pack: center;
				justify-content: center;
	}

	.pp-share-buttons-sm-align-justify .pp-social-share-inner {
		-webkit-box-pack: justify;
			-ms-flex-pack: justify;
				justify-content: space-between;
	}

	<?php if ( '' != $settings->column_gap_responsive ) { ?>
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-social-share-col-0 .pp-social-share-inner {
		margin-left: calc( -<?php echo $settings->column_gap_responsive; ?>px / 2 );
		margin-right: calc( -<?php echo $settings->column_gap_responsive; ?>px / 2 );
	}
	<?php } ?>

	<?php if ( '' != $settings->column_gap_responsive ) { ?>
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content.pp-social-share-col-0 .pp-share-button {
		margin-left: calc( <?php echo $settings->column_gap_responsive; ?>px / 2 );
		margin-right: calc( <?php echo $settings->column_gap_responsive; ?>px / 2 );
	}
	<?php } ?>

	<?php if ( '' != $settings->icon_size_responsive ) { ?>
	.fl-builder-content .fl-node-<?php echo $id; ?> .pp-social-share-content .pp-share-button-icon i {
		font-size: <?php echo $settings->icon_size_responsive; ?>px;
	}
	<?php } ?>
}

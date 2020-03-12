<?php
/**
 * PowerPack admin settings page.
 *
 * @since 1.0.0
 * @package bb-powerpack
 */

?>

<?php
$license 	 = self::get_option( 'bb_powerpack_license_key' );
$current_tab = self::get_current_tab();
?>

<div class="wrap pp-admin-settings-wrap">
	<div class="pp-admin-settings-header">
		<div>
			<h3>
			<?php
				$admin_label = pp_get_admin_label();
				// translators: %s is either PowerPack or text added in white label setting.
				echo sprintf( esc_html__( '%s Settings', 'bb-powerpack' ), $admin_label );
			?>
			</h3>
			<?php self::render_top_nav(); ?>
		</div>
		<div class="pp-admin-settings-tabs">
			<?php self::render_tabs( $current_tab ); ?>
		</div>
	</div>

	<div class="pp-admin-settings-content pp-admin-settings-<?php echo $current_tab; ?>">
		<h2 class="pp-notices-target"></h2>
		<?php self::render_update_message(); ?>

		<form method="post" id="pp-settings-form" action="<?php echo self::get_form_action( '&tab=' . $current_tab ); ?>">
			<?php

			self::render_setting_page();

			do_action( 'pp_admin_settings_forms', $current_tab );

			?>
		</form>
	</div>
</div>
<style>
#wpcontent {
	padding-left: 0;
}
</style>

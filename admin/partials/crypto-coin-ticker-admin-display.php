<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link        https://zwaply.com
 * @since       1.0.1
 * @package     Crypto_Coin_Ticker
 * @subpackage  Crypto_Coin_Ticker/admin/partials
 * @author      Zwaply <info@zwaply.com>
 */
?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<form action="options.php" method="post">
		<?php
			settings_fields( $this->plugin_name );
			do_settings_sections( $this->plugin_name );
			submit_button();
		?>
	</form>
</div>

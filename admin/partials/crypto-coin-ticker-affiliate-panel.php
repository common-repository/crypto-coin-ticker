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
	<iframe src="https://zwaply.com/affiliate-login?username=<?php echo esc_attr( get_option( 'crypto_coin_ticker_zwaply_affiliate_id' ) ); ?>" frameborder="0" style="width: 100%;height: 100%;" scrolling="<?php echo isset($scrolling) && 'yes' === $scrolling ? 'yes' : 'no'; ?>"></iframe>
</div>

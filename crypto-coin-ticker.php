<?php
/**
 * @link              https://zwaply.com
 * @package           Crypto_Coin_Ticker
 *
 * @wordpress-plugin
 * Plugin Name:     Crypto Coin Ticker
 * Description:     Display a list of prices for all your favorite cryptocurrencies! Use the shortcode: 'ccticker'. Reads the API from CoinMarketCap.com and caches the results for 2 minutes on the server, to avoid unnecessary bandwidth usage.
 * Version:         1.0.7
 * Author:          Zwaply
 * Author URI: 	    https://zwaply.com
 * Text Domain:	    crypto_coin_ticker_domain
 * License:	        GPL-2.0+
 * License URI:	    http://www.gnu.org/licenses/gpl-2.0.txt
 */

// don't load directly
if (! defined('ABSPATH')) {
    exit;
}

// Define plugin directory
if (! defined('CCTICKER_PLUGIN_DIR')) {
    define('CCTICKER_PLUGIN_DIR', plugin_dir_url(__FILE__));
}

// Used for referring to the plugin file or basename
if (! defined('CCTICKER_PLUGIN_FILE')) {
    define('CCTICKER_PLUGIN_FILE', plugin_basename(__FILE__));
}

/**
 * The core plugin class that defines admin hooks and public hooks
 */
require_once plugin_dir_path(__FILE__) . 'inc/class-crypto-coin-ticker.php';

new Crypto_Coin_Ticker();

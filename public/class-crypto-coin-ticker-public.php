<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link        https://zwaply.com
 * @since       1.0.1
 * @package     Crypto_Coin_Ticker
 * @subpackage  Crypto_Coin_Ticker/admin/partials
 * @author      Zwaply <info@zwaply.com>
 */

class Crypto_Coin_Ticker_Public {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

    add_shortcode( 'ccticker', array( $this, 'cryptocointicker_shortcode_output' ) );
    add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ));
  }
  
  public function enqueue_scripts() {
    wp_enqueue_style( $this->plugin_name . '-frontend', plugin_dir_url( __FILE__ ) . 'css/crypto-coin-ticker-frontend.css', array(), $this->version, 'all' );
    

    $coinlist_transient = get_transient( 'crypto-coin-ticker' );
    $expireTime         = 60 * 2; // expire after 2 minutes
    if ( empty( $coinlist_transient ) ) {
      $coinlist_transient = get_coins();
      set_transient( 'crypto-coin-ticker', $coinlist_transient, $expireTime );
    }

    wp_register_script( $this->plugin_name . '-frontend', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ), $this->version, 'all' );
    wp_localize_script( $this->plugin_name . '-frontend', 'crypto_coin_ticker', array(
      'zwaply_affiliate_id' => get_option( 'crypto_coin_ticker_zwaply_affiliate_id' ),
      'pctchangeinterval' => get_option( 'crypto_coin_ticker_pctchangeinterval' ),
      'currency' => get_option( 'crypto_coin_ticker_currency' ),
      'show_trade_button' => get_option( 'crypto_coin_ticker_show_trade_button' ),
      'trade_button_text' => get_option( 'crypto_coin_ticker_trade_button_text' ),
      'coins_list' => $coinlist_transient,
    ) );
    wp_enqueue_script( $this->plugin_name . '-frontend' );
    
    wp_enqueue_script( 'autocomplete', plugin_dir_url( __FILE__ ) . 'js/jquery.autocomplete.min.js', array( 'jquery' ), $this->version, 'all' );

    wp_enqueue_style( 'loading-css', plugin_dir_url( __FILE__ ) . 'css/loading/loading.css' );
  	wp_enqueue_style( 'loading-btn-css', plugin_dir_url( __FILE__ ) . 'css/loading/loading-btn.css' );

  }

	/**
	 * Function for displaying everything
	 *
	 * @param array  $atts - the attributes of shortcode
	 * @param string $content - the content between the shortcodes tags
	 */
	public function cryptocointicker_shortcode_output( $atts, $content ) {

		// * Get setting for Currency
		$currencySetting = get_option( 'crypto_coin_ticker_currency' );

		$atts = shortcode_atts(
			array(
				'pluginurl' => plugins_url(),
			),
			$atts,
			'ccticker'
		);

		// * Coin Logos URL
		// $iconurl = 'https://files.coinmarketcap.com/static/img/coins/32x32/';
		$iconurl = CCTICKER_PLUGIN_DIR . 'icons/';  // self-hosted icons coming soon!

		// * Reset output var
		$output = '';

		$output .= '<div class="widget widget_crypto-coin-ticker">';
		$output .= '<div class="widget-wrap">';

		// * Get setting for Color Scheme
		$colorSchemeSetting = get_option( 'crypto_coin_ticker_colorscheme' );

		// * Create Container for Feed Output
		$output .= '<div class="crypto-coin-ticker-container ' . $colorSchemeSetting . '">';

		// * Get setting for what coins to display
		$coinsListSetting = get_option( 'crypto_coin_ticker_coinslist' );

		// * Array of currency symbols
		$currencySymbolArray = array(
			'USD'     => '$',
			'AUD'     => 'A$',
			'BRL'     => 'R$',
			'CAD'     => 'C$',
			'CHF'     => 'Fr',
			'CLP'     => '$',
			'CNY'     => '¥',
			'CZK'     => 'Kč',
			'DKK'     => 'kr',
			'EUR'     => '€',
			'GBP'     => '£',
			'HKD'     => '$',
			'HUF'     => 'Ft',
			'IDR'     => 'Rp',
			'ILS'     => '₪',
			'INR'     => '₹',
			'JPY'     => '¥',
			'KRW'     => '₩',
			'MXN'     => '$',
			'MYR'     => 'RM',
			'NOK'     => 'kr',
			'NZD'     => '$',
			'PHP'     => '₱',
			'PKR'     => '₨',
			'PLN'     => 'zł',
			'RUB'     => '₽',
			'SEK'     => 'kr',
			'SGD'     => '$',
			'THB'     => '฿',
			'TRY'     => 'Kr',
			'TWD'     => 'NT$',
			'ZAR'     => 'R',
			'Satoshi' => 's',
		);

		// * Determine which currency symbol to use
		$currencyChar = '';
		if ( array_key_exists( $currencySetting, $currencySymbolArray ) ) {
			$currencyChar = $currencySymbolArray[ $currencySetting ];
		}

		// * If coin list is NOT empty, show stuff
		if ( ! empty( $coinsListSetting ) ) {

			// * Use WP Transients API to cache JSON calls
			$coinlist_transient = get_transient( 'crypto-coin-ticker' );
			$expireTime         = 60 * 2; // expire after 2 minutes
			if ( empty( $coinlist_transient ) ) {
				$coinlist_transient = get_coins();
				set_transient( 'crypto-coin-ticker', $coinlist_transient, $expireTime );
			}

			// * Remove any spaces
			$coinsListSetting = str_replace( ' ', '', $coinsListSetting );

			// * Turn list into array
			$coinsToDisplay = explode( ',', $coinsListSetting );

			foreach ( $coinlist_transient as $coin ) {
				$coinSymbol = $coin->symbol;

				if ( in_array( $coinSymbol, $coinsToDisplay ) ) {
					$coinName      = $coin->name;
					$coinID        = $coin->id;
					$coinNameLower = strtolower( $coinName );

					$coinPriceData = '';
					if ( $currencySetting == 'USD' ) {
						$coinPriceData = $coin->price_usd;
						$priceNum      = (float) $coinPriceData;
						if ( $priceNum > 1.0 ) {
							// Round to 2 decimal places after zero
							$priceNum = bcdiv( $priceNum, 1, 2 );
						} else {
							// Round to 5 decimal places after zero
							$priceNum = bcdiv( $priceNum, 1, 5 );
						}
						$coinPriceData = $priceNum;
					} elseif ( $currencySetting == 'Satoshi' ) {

						// 1 Bitcoin = 100m Satoshi
						$coinPriceData = $coin->price_btc;
						$priceNum      = (float) $coinPriceData;
						$priceNum      = $priceNum * 100000000;
						if ( $priceNum > 1.0 ) {
							// Round to 2 decimal places after zero
							$priceNum = bcdiv( $priceNum, 1, 2 );
						} else {
							// Round to 5 decimal places after zero
							$priceNum = number_format( $priceNum, 5 );
						}
						$coinPriceData = $priceNum;
					} else {
						$lcCurrency   = strtolower( $currencySetting );
						$coinPriceKey = 'price_' . $lcCurrency;   // should be: price_eur
						$coinArray    = (array) $coin;

						foreach ( $coinArray as $key => $value ) {
							if ( strpos( $key, $coinPriceKey ) !== false ) {
								$priceNum = (float) $value;
								if ( $priceNum > 1.0 ) {
									// Round to 2 decimal places after zero
									$priceNum = bcdiv( $priceNum, 1, 2 );
								} else {
									// Round to 5 decimal places after zero
									$priceNum = bcdiv( $priceNum, 1, 5 );
								}
								$coinPriceData = $priceNum;
							}
						}
					}

					$coinPrice = $currencyChar . $coinPriceData;

					// * Get setting for PercentChangeInterval
					$pctChangeSetting = get_option( 'crypto_coin_ticker_pctchangeinterval' );
					switch ( $pctChangeSetting ) {
						case 'percent_change_1h':
							$coinPctChange = $coin->percent_change_1h . '%';
							break;
						case 'percent_change_24h':
							$coinPctChange = $coin->percent_change_24h . '%';
							break;
						case 'percent_change_7d':
							$coinPctChange = $coin->percent_change_7d . '%';
							break;
						default:
							$coinPctChange = $coin->percent_change_1h . '%';
							break;
					}

					$changeSign    = '+';
					$changeClass   = 'positive';
					$negStr        = '-';
					$coinLink      = 'https://coinmarketcap.com/currencies/' . $coinID;
					$coinTradeLink = "https://zwaply.com/exchange/?source_coin=$coinSymbol";

					$affiliate_id = get_option( 'crypto_coin_ticker_zwaply_affiliate_id' );
					if ( ! empty( $affiliate_id ) ) {
						$coinTradeLink .= "&affiliate_id=$affiliate_id";
					}

					// if negative change
					if ( strpos( $coinPctChange, $negStr ) !== false ) {
						$changeSign  = '';
						$changeClass = 'negative';
					}

					$output .= '<div class="coin ' . $coinSymbol . '"><a href="' . $coinLink . '" target="_blank" rel="nofollow">';

					// * Get setting for Show Icons
					$showiconsSetting = get_option( 'crypto_coin_ticker_showicons' );

					// //* If setting is not 'No' then add the icon div
					// if ( $showiconsSetting !== 'No' ) {
					// $output .= '<div class="icon"><img src="' . $iconurl . strtolower( $coinSymbol ) . '.png" alt="'.$coinName.'" title="'.$coinName.'" /></div>';
					// }
					$output .= '<div class="name">' . $coinName . ' <span class="symbol">(' . $coinSymbol . ')</span></div>';
					$output .= '<div class="price">';
					$output .= $coinPrice . '<div class="changepct ' . $changeClass . '">' . $changeSign . $coinPctChange . '</div>';

					$showTradeButton = get_option( 'crypto_coin_ticker_show_trade_button' );
					if ( $showTradeButton !== 'No' ) {
						$tradeButtonText = esc_attr( get_option( 'crypto_coin_ticker_trade_button_text' ) );
						$tradeButtonText = ! empty( $tradeButtonText ) ? $tradeButtonText : 'Trade';
						$output         .= '<button class="trade" onclick="event.stopPropagation(); window.open(\'' . $coinTradeLink . '\'); return false;">' . $tradeButtonText . '</button>';
					}

					$output .= '</div>';
					$output .= '</a></div>' . "\n";
				}
      }
      
      $output .= '<div class="crypto-coin-ticker-footer">';

      $output .= '<div class="search loading ld-ext-right"><input type="search" id="search-coin" placeholder="' . __( 'Search coins' ) . '" /><div class="ld ld-ring ld-spin"></div></div>' . "\n";

			$showTradeButton = get_option( 'crypto_coin_ticker_show_powered_by' );
			if ( $showTradeButton !== 'No' ) {
				$output .= '<div class="powered-by"><a target="_blank" rel="nofollow" href="https://wordpress.org/plugins/crypto-coin-ticker/">By Zwaply.com</a></div>' . "\n";
      }

      $output .= '<div class="search-result">';
      $output .= '</div>';
      
      $output .= '</div>';

		} else {
			// * Coin list is empty, show Settings message
			$settingsPageURL  = get_bloginfo( 'url' ) . '/wp-admin/options-general.php?page=crypto-coin-ticker';
			$settingsLinkText = __( 'Settings Page' );
			$current_user     = wp_get_current_user();
			if ( user_can( $current_user, 'administrator' ) ) {
				$settingsLinkText = '<a href="' . $settingsPageURL . '">' . __( 'Settings Page' ) . '</a>';
			}
			$output .= '<div style="text-align:center;">' . __( 'You haven\'t saved any coins into the Crypto Coin Ticker yet. Please check the ' ) . $settingsLinkText . '.</div>';
		}

		$output .= '</div><!-- // .crypto-coin-ticker-container // -->';

		$output .= '</div><!-- // .widget-wrap // -->';
		$output .= '</div><!-- // .widget_crypto-coin-ticker // -->';


		// * Get setting for Custom CSS
		$customCSSSetting = get_option( 'crypto_coin_ticker_customstyles' );
		$output          .= '<style>' . "\n";
		$output          .= $customCSSSetting;
		$output          .= '</style>' . "\n";

		return $output;
	}
}



if ( ! function_exists( 'get_coins' ) ) {
  function get_coins() {

    // * Get setting for Currency
    $currencySetting = get_option( 'crypto_coin_ticker_currency' );

    // * Create string for feed URL
    $feedurl = 'https://api.coinmarketcap.com/v1/ticker/'; // path to JSON API

    /*
    Optional parameters:
    (int) start - return results from rank [start] and above
    (int) limit - return a maximum of [limit] results (default is 100, use 0 to return all results)
    (string) convert - return price, 24h volume, and market cap in terms of another currency. Valid values are:
    "AUD", "BRL", "CAD", "CHF", "CLP", "CNY", "CZK", "DKK", "EUR", "GBP", "HKD", "HUF", "IDR", "ILS", "INR", "JPY", "KRW", "MXN", "MYR", "NOK", "NZD", "PHP", "PKR", "PLN", "RUB", "SEK", "SGD", "THB", "TRY", "TWD", "ZAR"
    Example: https://api.coinmarketcap.com/v1/ticker/
    Example: https://api.coinmarketcap.com/v1/ticker/?limit=10
    Example: https://api.coinmarketcap.com/v1/ticker/?start=100&limit=10
    Example: https://api.coinmarketcap.com/v1/ticker/?convert=EUR&limit=10
    */
    $feedurlParams = '?';

    $feedLimit = 'limit=0'; // Get all items

    if ( $currencySetting == 'USD' ) {
      // use default link
      $feedurlParams .= $feedLimit;
    } elseif ( $currencySetting == 'Satoshi' ) {
      // use default link
      $feedurlParams .= $feedLimit;
    } else {
      // convert to different currency
      $feedurlParams .= 'convert=' . $currencySetting . '&' . $feedLimit;
    }

    $feedurl = $feedurl . $feedurlParams;

    $data     = file_get_contents( $feedurl ); // put the contents of the file into a variable
    $coinlist = json_decode( $data ); // decode the JSON feed

    return $coinlist;
  }
}
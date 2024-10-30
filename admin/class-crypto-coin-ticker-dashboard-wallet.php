<?php

/**
 * Add the Crypto Wallet Calculator widget to the dashboard.
 */
add_action( 'wp_dashboard_setup', 'ccticker_add_dashwallet_widget' );
function ccticker_add_dashwallet_widget() {
	wp_add_dashboard_widget(
		'ccticker_dashwallet_widget', // Widget slug.
		'Crypto Wallet Calculator', // Title.
		'ccticker_dashwallet_widget_function' // Display function.
	);
}

/**
 * Output the contents of our Crypto Wallet widget.
 */
function ccticker_dashwallet_widget_function() {

	// * Get setting for Dashboard Widget Coin Info
	$dashWidgetCoinInfo = get_option( 'crypto_coin_ticker_dashwidgetcoininfo' );

	if ( ! empty( $dashWidgetCoinInfo ) ) {
		$dashWidgetCoinInfo = explode( "\n", str_replace( "\r", '', $dashWidgetCoinInfo ) );

		// * Get setting for Currency
		$currencySetting = get_option( 'crypto_coin_ticker_currency' );

		// * Setup arrays
		$coinsToDisplay     = array();
		$coinAmounts        = array();
		$allCoinsTotalValue = array();

		$coinWidgetArr = array();
		array_walk(
			$dashWidgetCoinInfo, function ( $val, $key ) use ( &$coinWidgetArr ) {
				list($key, $value)     = explode( '-', $val );
				$coinWidgetArr[ $key ] = $value;
			}
		);

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

		// * Get coin info from API
		if ( ! function_exists( 'get_coins' ) ) {
			function get_coins() {

				// * Get setting for Currency
				$currencySetting = get_option( 'crypto_coin_ticker_currency' );

				// * Create string for feed URL
				$feedurl       = 'https://api.coinmarketcap.com/v1/ticker/'; // path to JSON API
				$feedurlParams = '?';
				$feedLimit     = 'limit=0'; // Get all items

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

		// * Get/Set Transient Cache
		$coinlist_transient = get_transient( 'crypto-coin-ticker' );
		$expireTime         = 60 * 2; // expire after 2 minutes
		if ( empty( $coinlist_transient ) ) {
			$coinlist_transient = get_coins();
			set_transient( 'crypto-coin-ticker', $coinlist_transient, $expireTime );
		}

		// print_r($coinlist_transient);
		// * Start widget content
		echo '<div class="widget-header">';
		echo 'Your current Crypto Coin Wallet Value:';
		echo '</div>';
		echo '<div class="ccticker-dashboard-widget-data">';

		echo '<div class="data-header">';
		echo '<div class="title">Coin</div>';
		echo '<div class="title">Amount</div>';
		echo '<div class="title">Mkt Value</div>';
		echo '<div class="title">Total</div>';
		echo '</div>';

		$coinRowID = 1;
		$i         = 0;

		// * Start comparing API data to coin list and outputting content
		foreach ( $coinlist_transient as $coin ) {
			$coinSymbol = $coin->symbol;

			// * If this coin is in our coin list...
			if ( array_key_exists( $coinSymbol, $coinWidgetArr ) ) {

				// Vars
				$thisCoinAmt = 0;
				foreach ( $coinWidgetArr as $key => $value ) {
					if ( $key == $coinSymbol ) {
						$thisCoinAmt = $value;
					}
				}

				$output           = '';
				$coinPriceData    = '';
				$priceNum         = '';
				$thisCoinTotalVal = '';

				// Start Row DIV
				$output .= '<div class="coin-row">';

				// Row Numeric ID
				// $output .= '<div class="row-id">' . $coinRowID . '</div>';
				// Coin Market Symbol
				$output .= '<div class="coin-symbol">' . $coinSymbol . '</div>';

				// Amount of Coins
				$output .= '<div class="coin-amt">' . $thisCoinAmt . '</div>';

				// Get Price Value API element based on currency selection
				$output .= '<div class="coin-price">';
				if ( $currencySetting == 'USD' ) {
					$coinPriceData = $coin->price_usd;
					$priceNum      = $coinPriceData;
					// Coin Price
					if ( $priceNum >= 1.0 ) {
						// Round to 2 decimal places after zero
						$priceNum = bcdiv( $priceNum, 1, 2 );
						$output  .= $currencyChar . number_format( $priceNum, 2, '.', ',' );
					} else {
						// Round to 5 decimal places after zero
						$priceNum = bcdiv( $priceNum, 1, 5 );
						$output  .= $currencyChar . $priceNum;
					}
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
					$output       .= $currencyChar . $priceNum;
				} else {
					$lcCurrency   = strtolower( $currencySetting );
					$coinPriceKey = 'price_' . $lcCurrency;   // should be: price_eur
					$coinArray    = (array) $coin;

					foreach ( $coinArray as $key => $value ) {
						if ( strpos( $key, $coinPriceKey ) !== false ) {
							$priceNum = (float) $value;
							// Coin Price
							if ( $priceNum >= 1.0 ) {
								// Round to 2 decimal places after zero
								$priceNum = bcdiv( $priceNum, 1, 2 );
								$output  .= $currencyChar . number_format( $priceNum, 2, '.', ',' );
							} else {
								// Round to 5 decimal places after zero
								$priceNum = bcdiv( $priceNum, 1, 5 );
								$output  .= $currencyChar . $priceNum;
							}
						}
					}
				}
				$output .= '</div>';

				// Calculate Total Value
				$thisCoinTotalVal = (float) ( $priceNum * $thisCoinAmt );
				if ( $thisCoinTotalVal >= 1.0 ) {

					// Round to 2 decimal places after zero
					$thisCoinTotalVal = bcdiv( $thisCoinTotalVal, 1, 2 );
					$output          .= '<div class="coin-totalVal">' . $currencyChar . number_format( $thisCoinTotalVal, 2, '.', ',' ) . '</div>';
				} else {

					// Round to 5 decimal places after zero
					$thisCoinTotalVal = bcdiv( $thisCoinTotalVal, 1, 5 );
					$output          .= '<div class="coin-totalVal">' . $currencyChar . $thisCoinTotalVal . '</div>';
				}
				$allCoinsTotalValue[] = $thisCoinTotalVal;

				// End Row DIV
				$output .= '</div>';

				echo $output;

				$coinRowID++;
				$i++;
			}
		}

		echo '<div class="grandTotal">';
		echo '<div></div>';
		echo '<div></div>';
		echo '<div class="title">Total:</div>';
		echo '<div class="number">';
		// Round to 2 decimal places after zero
		echo $currencyChar . number_format( array_sum( $allCoinsTotalValue ), 2, '.', ',' );
		echo '</div>';
		echo '</div>';

		echo '</div>';
	} else {
		// * Coin list is empty, show Settings message
		$settingsPageURL  = get_bloginfo( 'url' ) . '/wp-admin/options-general.php?page=crypto-coin-ticker';
		$settingsLinkText = __( 'Settings Page' );
		$current_user     = wp_get_current_user();
		if ( user_can( $current_user, 'administrator' ) ) {
			$settingsLinkText = '<a href="' . $settingsPageURL . '">' . __( 'Settings Page' ) . '</a>';
		}
		echo '<div class="msg-error"><b>' . __( 'ERROR: No Coin info found.' ) . '</b><br>' . __( 'Please enter your coins and amounts into the "Dashboard Widget Settings" section on the ' ) . $settingsLinkText . '.</div>';
	}
}

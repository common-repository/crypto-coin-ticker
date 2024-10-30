<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link        https://zwaply.com
 * @since       1.0.1
 * @package     Crypto_Coin_Ticker
 * @subpackage  Crypto_Coin_Ticker/admin/partials
 * @author      Zwaply <info@zwaply.com>
 */

class Crypto_Coin_Ticker_Admin {


	/**
	 * The options name to be used in this plugin
	 *
	 * @since   1.0.1
	 * @access  private
	 * @var     string      $option_name    Option name of this plugin
	 */
	private $option_name = 'crypto_coin_ticker';

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
	 * The default Custom CSS of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $defaultCustomCSS;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$defaultCustomCSS  = '';
		$defaultCustomCSS .= '.crypto-coin-ticker-container { max-width: 450px; margin: 20px auto; padding: 20px; border: 1px solid; }
.coin a { display: flex; align-items: center; padding: 10px; border-top: 1px solid; }
.coin:first-child a { margin-bottom: 0; border-top: none; }
.coin .icon { margin-right: 14px; width: 20px; height: 20px; }
.coin .icon img { display: block; max-width: 20px; height: auto; }
.coin .name { width: 100%; text-align: left; font-size: 17px; font-weight: bold; margin-right: 20px; }
.coin .price { width: 100%; text-align: right; font-size: 15px; white-space: nowrap; }
.coin .price .trade { line-height: 1; padding: 0.3rem 0.6rem 0.31rem; font-weight: normal; text-transform: uppercase; font-size: 0.7rem; margin-left: 0.6rem; }
.coin .changepct { display: inline-block; margin-left: 10px; text-align: center; font-size: 12px; }
.coin .changepct.positive { color: #3cb878 }
.coin .changepct.negative { color: #ff0000 }
.crypto-coin-ticker-container.light, .crypto-coin-ticker-container.light .coin a { border-color: #ddd; }
.crypto-coin-ticker-container.light .coin a:hover { background: rgba(0,0,0,0.05); }
.crypto-coin-ticker-container.light .coin .name { color: #999; }
.crypto-coin-ticker-container.light .coin .name .symbol { color: #aaa; }
.crypto-coin-ticker-container.light .price { color: #aaa; }
.crypto-coin-ticker-container.dark .coin a:hover { background: rgba(255,255,255,0.05); }
.crypto-coin-ticker-container.dark, .crypto-coin-ticker-container.dark .coin a { border-color: #444; }
.crypto-coin-ticker-container.dark .coin .name { color: #999; }
.crypto-coin-ticker-container.dark .coin .name .symbol { color: #666; }
.crypto-coin-ticker-container.dark .price { color: #666; }
@media screen and (max-width: 600px){
    .coin .name { font-size: 14px; }
    .coin .price { font-size: 12px; }
    .coin .changepct { font-size: 9px; }
}
@media screen and (max-width: 500px){
    .coin .name { text-align: center; }
    .coin .icon { display: none; }
}';

		$this->defaultCustomCSS = $defaultCustomCSS;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Social_Proof_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Social_Proof_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/crypto-coin-ticker-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Adds a link to the plugin settings page
	 *
	 * @since       1.0.1
	 * @param       array $links      The current array of links
	 * @return      array                   The modified array of links
	 */
	public function add_settings_link( $links ) {
		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'options-general.php?page=crypto-coin-ticker' ) ), esc_html__( 'Settings', 'crypto-coin-ticker' ) );
		return $links;
	}

	/**
	 * Add an options page for CCTicker under the Settings submenu
	 *
	 * @since  1.0.1
	 */
	public function add_admin_pages() {
		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'Crypto Coin Ticker Settings', 'crypto-coin-ticker' ),
			__( 'Crypto Coin Ticker', 'crypto-coin-ticker' ),
			'manage_options',
			$this->plugin_name,
      array( $this, 'display_ccticker_options_page' ),
      'dashicons-money'
    );
    add_submenu_page(
      $this->plugin_name,
			__( 'Crypto Coin Ticker Settings', 'crypto-coin-ticker' ),
			__( 'Settings', 'crypto-coin-ticker' ),
			'manage_options',
			$this->plugin_name,
      array( $this, 'display_ccticker_options_page' )
		);
    add_submenu_page(
      $this->plugin_name,
			__( 'Zwaply Affiliate Panel', 'crypto-coin-ticker' ),
			__( 'Affiliate Panel', 'crypto-coin-ticker' ),
			'manage_options',
			$this->plugin_name . '-affiliate-panel',
      array( $this, 'display_affiliate_panel_page' )
		);
	}

	public function admin_notices() {
		if ( get_option( 'cct-hide-update-notice-1.0.7' ) != true ) {
			?>
	<div class="notice notice-info is-dismissible">
		<p><?php _e( 'Thanks for updating Crypto Coin Ticker. With this new version, you can now EARN CRYPTO! Rush to <a target="_blank" href="https://zwaply.com/register/">create your Zwaply username</a> and start earning crypto. <a target="_blank" href="https://zwaply.com/zwaply-for-web/">Learn more about Zwaply</a>.', 'crypto-coin-ticker' ); ?></p>
		<p style="margin-bottom:10px;font-size: 8pt;"><a href="<?php echo admin_url( '?action=cct-dismiss-update-notice' ); ?>"><?php _e( 'Dismiss notice', 'crypto-coin-ticker' ); ?></a></p>
	</div>
			<?php
		}
	}

	public function dismiss_notices() {
		if ( isset( $_GET['action'] ) && 'cct-dismiss-update-notice' === $_GET['action'] ) {
			update_option( 'cct-hide-update-notice-1.0.7', true );
		}
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.1
	 */
	public function display_ccticker_options_page() {
		include_once 'partials/crypto-coin-ticker-admin-display.php';
	}

	/**
	 * Render the affiliate page for plugin
	 *
	 * @since  1.0.1
	 */
	public function display_affiliate_panel_page() {
		include_once 'partials/crypto-coin-ticker-affiliate-panel.php';
	}

	/**
	 * Render the text for the Affiliate Settings section
	 *
	 * @since  1.0.1
	 */
	public function crypto_coin_ticker_affiliate_settings_section_cb() {
		echo '<p>' . __( 'Configure your Zwaply user with the plugin.', 'crypto-coin-ticker' ) . '</p>';
	}

	/**
	 * Render the text for the Appearance Settings section
	 *
	 * @since  1.0.1
	 */
	public function crypto_coin_ticker_general_settings_section_cb() {
		echo '<p>' . __( 'Customize the appearance of the Crypto Coin Ticker.', 'crypto-coin-ticker' ) . '</p>';
	}

	/**
	 * Render the text for the Dashboard Wallet section
	 *
	 * @since  1.0.4
	 */
	public function crypto_coin_ticker_dashwidget_settings_section_cb() {
		echo '<p>' . __( 'Customize the appearance of the Dashboard Wallet Calculator widget.', 'crypto-coin-ticker' ) . '</p>';
	}


	/* ==================== FORM FIELDS ==================== */

	/**
	 * Render the select input field for 'zwaply_affiliate_id' option
	 *
	 * @param      array $args           The arguments for the field
	 * @return     string                       The HTML field
	 * @since      1.0.3
	 */
	public function crypto_coin_ticker_zwaply_affiliate_id_cb( array $args ) {
		$zwaply_affiliate_id = get_option( $this->option_name . '_zwaply_affiliate_id' );
		?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="text"
			value="<?php echo $zwaply_affiliate_id; ?>" />
		</label>
		<p><span class="description"><?php echo $args['description']; ?></span></p>
		<?php
	}

	/**
	 * Render the select input field for 'colorscheme' option
	 *
	 * @param      array $args           The arguments for the field
	 * @return     string                       The HTML field
	 * @since      1.0.1
	 */
	public function crypto_coin_ticker_colorscheme_cb( array $args ) {
		$colorscheme = get_option( $this->option_name . '_colorscheme' );
		?>
		<select name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>">
			<option value="light" <?php echo $colorscheme == 'light' ? 'selected="selected"' : ''; ?> >Light</option>
			<option value="dark" <?php echo $colorscheme == 'dark' ? 'selected="selected"' : ''; ?> >Dark</option>
		</select>
		<p><span class="description"><?php esc_html_e( $args['description'], 'crypto-coin-ticker' ); ?></span></p>
		<?php
	}

	/**
	 * Render the select input field for 'currency' option
	 *
	 * @param      array $args           The arguments for the field
	 * @return     string                       The HTML field
	 * @since      1.0.3
	 */
	public function crypto_coin_ticker_currency_cb( array $args ) {
		$currency = get_option( $this->option_name . '_currency' );
		?>
		<select name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>">
			<option value="USD" <?php echo $currency == 'USD' ? 'selected="selected"' : ''; ?> >USD</option>
			<option value="AUD" <?php echo $currency == 'AUD' ? 'selected="selected"' : ''; ?> >AUD</option>
			<option value="BRL" <?php echo $currency == 'BRL' ? 'selected="selected"' : ''; ?> >BRL</option>
			<option value="CAD" <?php echo $currency == 'CAD' ? 'selected="selected"' : ''; ?> >CAD</option>
			<option value="CHF" <?php echo $currency == 'CHF' ? 'selected="selected"' : ''; ?> >CHF</option>
			<option value="CLP" <?php echo $currency == 'CLP' ? 'selected="selected"' : ''; ?> >CLP</option>
			<option value="CNY" <?php echo $currency == 'CNY' ? 'selected="selected"' : ''; ?> >CNY</option>
			<option value="CZK" <?php echo $currency == 'CZK' ? 'selected="selected"' : ''; ?> >CZK</option>
			<option value="DKK" <?php echo $currency == 'DKK' ? 'selected="selected"' : ''; ?> >DKK</option>
			<option value="EUR" <?php echo $currency == 'EUR' ? 'selected="selected"' : ''; ?> >EUR</option>
			<option value="GBP" <?php echo $currency == 'GBP' ? 'selected="selected"' : ''; ?> >GBP</option>
			<option value="HKD" <?php echo $currency == 'HKD' ? 'selected="selected"' : ''; ?> >HKD</option>
			<option value="HUF" <?php echo $currency == 'HUF' ? 'selected="selected"' : ''; ?> >HUF</option>
			<option value="IDR" <?php echo $currency == 'IDR' ? 'selected="selected"' : ''; ?> >IDR</option>
			<option value="ILS" <?php echo $currency == 'ILS' ? 'selected="selected"' : ''; ?> >ILS</option>
			<option value="INR" <?php echo $currency == 'INR' ? 'selected="selected"' : ''; ?> >INR</option>
			<option value="JPY" <?php echo $currency == 'JPY' ? 'selected="selected"' : ''; ?> >JPY</option>
			<option value="KRW" <?php echo $currency == 'KRW' ? 'selected="selected"' : ''; ?> >KRW</option>
			<option value="MXN" <?php echo $currency == 'MXN' ? 'selected="selected"' : ''; ?> >MXN</option>
			<option value="MYR" <?php echo $currency == 'MYR' ? 'selected="selected"' : ''; ?> >MYR</option>
			<option value="NOK" <?php echo $currency == 'NOK' ? 'selected="selected"' : ''; ?> >NOK</option>
			<option value="NZD" <?php echo $currency == 'NZD' ? 'selected="selected"' : ''; ?> >NZD</option>
			<option value="PHP" <?php echo $currency == 'PHP' ? 'selected="selected"' : ''; ?> >PHP</option>
			<option value="PKR" <?php echo $currency == 'PKR' ? 'selected="selected"' : ''; ?> >PKR</option>
			<option value="PLN" <?php echo $currency == 'PLN' ? 'selected="selected"' : ''; ?> >PLN</option>
			<option value="RUB" <?php echo $currency == 'RUB' ? 'selected="selected"' : ''; ?> >RUB</option>
			<option value="SEK" <?php echo $currency == 'SEK' ? 'selected="selected"' : ''; ?> >SEK</option>
			<option value="SGD" <?php echo $currency == 'SGD' ? 'selected="selected"' : ''; ?> >SGD</option>
			<option value="THB" <?php echo $currency == 'THB' ? 'selected="selected"' : ''; ?> >THB</option>
			<option value="TRY" <?php echo $currency == 'TRY' ? 'selected="selected"' : ''; ?> >TRY</option>
			<option value="TWD" <?php echo $currency == 'TWD' ? 'selected="selected"' : ''; ?> >TWD</option>
			<option value="ZAR" <?php echo $currency == 'ZAR' ? 'selected="selected"' : ''; ?> >ZAR</option>
			<option value="Satoshi" <?php echo $currency == 'Satoshi' ? 'selected="selected"' : ''; ?> >Satoshi</option>
		</select>
		<p><span class="description"><?php esc_html_e( $args['description'], 'crypto-coin-ticker' ); ?></span></p>
		<?php
	}

	/**
	 * Render the textbox for 'coinslist' option
	 *
	 * @param   array $args           The arguments for the field
	 * @return  string                      The HTML field
	 * @since  1.0.1
	 */
	public function crypto_coin_ticker_coinslist_cb( array $args ) {
		$coinslist = get_option( $this->option_name . '_coinslist' );
		?>
		<div class="coinslist">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="text"
			value="<?php echo $coinslist; ?>" />
		</label>
		<p><span class="description"><?php esc_html_e( $args['description'], 'crypto-coin-ticker' ); ?></span></p>
		</div>
		<?php
	}

	/**
	 * Render the select input field for 'showicons' option
	 *
	 * @param      array $args           The arguments for the field
	 * @return     string                       The HTML field
	 * @since      1.0.3
	 */
	public function crypto_coin_ticker_showicons_cb( array $args ) {
		$showicons = get_option( $this->option_name . '_showicons' );
		/*
		<select name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>">
			<option value="Yes" <?php echo $showicons == 'Yes' ? 'selected="selected"' : ''; ?> >Yes</option>
			<option value="No" <?php echo $showicons == 'No' ? 'selected="selected"' : ''; ?> >No</option>
		</select>
		*/
		?>
		<!-- // DISABLED FOR NOW // -->
		<select disabled name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>">
			<option value="Yes">Yes</option>
			<option value="No" selected="selected">No</option>
		</select>
		<p><span class="description"><?php esc_html_e( $args['description'], 'crypto-coin-ticker' ); ?></span></p>
		<?php
	}

	/**
	 * Render the select input field for 'show_trade_button' option
	 *
	 * @param      array $args           The arguments for the field
	 * @return     string                       The HTML field
	 * @since      1.0.3
	 */
	public function crypto_coin_ticker_show_trade_button_cb( array $args ) {
		$show_trade_button = 'No' !== get_option( $this->option_name . '_show_trade_button' );
		?>
		<select name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>">
			<option value="Yes" 
			<?php
			if ( $show_trade_button ) {
				echo 'selected="selected"';
			}
			?>
			>Yes</option>
			<option value="No" 
			<?php
			if ( ! $show_trade_button ) {
				echo 'selected="selected"';
			}
			?>
			>No</option>
		</select>
		<p><span class="description"><?php esc_html_e( $args['description'], 'crypto-coin-ticker' ); ?></span></p>
		<?php
	}

	/**
	 * Render the select input field for 'trade_button_text' option
	 *
	 * @param      array $args           The arguments for the field
	 * @return     string                       The HTML field
	 * @since      1.0.3
	 */
	public function crypto_coin_ticker_trade_button_text_cb( array $args ) {
		$trade_button_text = get_option( $this->option_name . '_trade_button_text' );
		$trade_button_text = empty( $trade_button_text ) ? 'Trade' : $trade_button_text;
		?>
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<input
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"
			type="text"
			value="<?php echo $trade_button_text; ?>" />
		</label>
		<p><span class="description"><?php echo $args['description']; ?></span></p>
		<?php
	}

	/**
	 * Render the select input field for 'show_powered_by' option
	 *
	 * @param      array $args           The arguments for the field
	 * @return     string                       The HTML field
	 * @since      1.0.3
	 */
	public function crypto_coin_ticker_show_powered_by_cb( array $args ) {
		$show_powered_by = 'No' !== get_option( $this->option_name . '_show_powered_by' );
		?>
		<select name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>">
			<option value="Yes" 
			<?php
			if ( $show_powered_by ) {
				echo 'selected="selected"';
			}
			?>
			>Yes</option>
			<option value="No" 
			<?php
			if ( ! $show_powered_by ) {
				echo 'selected="selected"';
			}
			?>
			>No</option>
		</select>
		<p><span class="description"><?php esc_html_e( $args['description'], 'crypto-coin-ticker' ); ?></span></p>
		<?php
	}

	/**
	 * Render the select input field for 'pctchangeinterval' option
	 *
	 * @param      array $args           The arguments for the field
	 * @return     string                       The HTML field
	 * @since      1.0.3
	 */
	public function crypto_coin_ticker_pctchangeinterval_cb( array $args ) {
		$pctchangeinterval = get_option( $this->option_name . '_pctchangeinterval' );
		?>
		<select name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>">
			<option value="percent_change_1h" <?php echo $pctchangeinterval == 'percent_change_1h' ? 'selected="selected"' : ''; ?> >1 Hour</option>
			<option value="percent_change_24h" <?php echo $pctchangeinterval == 'percent_change_24h' ? 'selected="selected"' : ''; ?> >24 Hours</option>
			<option value="percent_change_7d" <?php echo $pctchangeinterval == 'percent_change_7d' ? 'selected="selected"' : ''; ?> >7 Days</option>
		</select>
		<p><span class="description"><?php esc_html_e( $args['description'], 'crypto-coin-ticker' ); ?></span></p>
		<?php
	}

	/**
	 * Render the textbox for 'customstyles' option
	 *
	 * @param   array $args           The arguments for the field
	 * @return  string                      The HTML field
	 * @since  1.0.1
	 */
	public function crypto_coin_ticker_customstyles_cb( array $args ) {
		$customstyles = get_option( $this->option_name . '_customstyles' );
		if ( empty( $customstyles ) ) {
			$customstyles = $this->defaultCustomCSS;
		}
		?>
		<div class="customstyles">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<textarea
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"><?php echo $customstyles; ?></textarea><br>
			<span class="description"><?php esc_html_e( $args['description'], 'crypto-coin-ticker' ); ?></span>
		</label>
		</div>
		<?php
	}

	// * Dashboard Wallet Settings

	/**
	 * Render the select input field for 'showdashwidget' option
	 *
	 * @param      array $args           The arguments for the field
	 * @return     string                       The HTML field
	 * @since      1.0.4
	 */
	public function crypto_coin_ticker_showdashwidget_cb( array $args ) {
		$showdashwidget = get_option( $this->option_name . '_showdashwidget' );
		?>
		<select name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>">
			<option value="Yes" <?php echo $showdashwidget == 'Yes' ? 'selected="selected"' : ''; ?> >Yes</option>
			<option value="No" <?php echo $showdashwidget == 'No' ? 'selected="selected"' : ''; ?> >No</option>
		</select>
		<p><span class="description"><?php esc_html_e( $args['description'], 'crypto-coin-ticker' ); ?></span></p>
		<?php
	}

	/**
	 * Render the textbox for 'dashwidgetcoininfo' option
	 *
	 * @param   array $args           The arguments for the field
	 * @return  string                      The HTML field
	 * @since  1.0.4
	 */
	public function crypto_coin_ticker_dashwidgetcoininfo_cb( array $args ) {
		$dashwidgetcoininfo = get_option( $this->option_name . '_dashwidgetcoininfo' );
		if ( empty( $dashwidgetcoininfo ) ) {
			$dashwidgetcoininfo = 'BTC-0.00054321';
		}
		?>
		<div class="customstyles">
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<textarea
			class="<?php echo esc_attr( $args['id'] ); ?>"
			id="<?php echo esc_attr( $args['id'] ); ?>"
			name="<?php echo esc_attr( $args['id'] ); ?>"><?php echo $dashwidgetcoininfo; ?></textarea><br>
			<span class="description"><?php esc_html_e( $args['description'], 'crypto-coin-ticker' ); ?></span>
			<p><span class="description">To calculate coin prices, enter each line in the following format:<br>
				<span class="code">(coin symbol)-(amount of coins)<br>
					Example: BTC-0.00054321</span></span></p>
		</label>
		</div>
		<?php
	}



	/* ==================== SANITIZE FIELDS ==================== */

	/**
	 * Sanitize the text 'zwaply_affiliate_id' value before being saved to database
	 *
	 * @param  string $zwaply_affiliate_id $_POST value
	 * @since  1.0.3
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_zwaply_affiliate_id_field( $zwaply_affiliate_id ) {
		$zwaply_affiliate_id = sanitize_text_field( $zwaply_affiliate_id );
		return $zwaply_affiliate_id;
	}

	/**
	 * Sanitize the text 'colorscheme' value before being saved to database
	 *
	 * @param  string $colorscheme $_POST value
	 * @since  1.0.1
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_colorscheme_field( $colorscheme ) {
		if ( in_array( $colorscheme, array( 'light', 'dark' ), true ) ) {
			return $colorscheme;
		}
	}

	/**
	 * Sanitize the text 'currency' value before being saved to database
	 *
	 * @param  string $currency $_POST value
	 * @since  1.0.3
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_currency_field( $currency ) {
		$currenciesArray = array(
			'USD',
			'AUD',
			'BRL',
			'CAD',
			'CHF',
			'CLP',
			'CNY',
			'CZK',
			'DKK',
			'EUR',
			'GBP',
			'HKD',
			'HUF',
			'IDR',
			'ILS',
			'INR',
			'JPY',
			'KRW',
			'MXN',
			'MYR',
			'NOK',
			'NZD',
			'PHP',
			'PKR',
			'PLN',
			'RUB',
			'SEK',
			'SGD',
			'THB',
			'TRY',
			'TWD',
			'ZAR',
			'Satoshi',
		);
		if ( in_array( $currency, $currenciesArray, true ) ) {
			return $currency;
		}
	}

	/**
	 * Sanitize the text 'coinslist' value before being saved to database
	 *
	 * @param  string $coinslist $_POST value
	 * @since  1.0.1
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_coinslist_field( $coinslist ) {
		$coinslist = sanitize_text_field( $coinslist );
		return $coinslist;
	}

	/**
	 * Sanitize the text 'showicons' value before being saved to database
	 *
	 * @param  string $showicons $_POST value
	 * @since  1.0.3
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_showicons_field( $showicons ) {
		if ( in_array( $showicons, array( 'Yes', 'No' ), true ) ) {
			return $showicons;
		}
	}

	/**
	 * Sanitize the text 'show_trade_button' value before being saved to database
	 *
	 * @param  string $show_trade_button $_POST value
	 * @since  1.0.3
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_show_trade_button_field( $show_trade_button ) {
		if ( in_array( $show_trade_button, array( 'Yes', 'No' ), true ) ) {
			return $show_trade_button;
		}
	}

	/**
	 * Sanitize the text 'trade_button_text' value before being saved to database
	 *
	 * @param  string $trade_button_text $_POST value
	 * @since  1.0.3
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_trade_button_text_field( $trade_button_text ) {
		$trade_button_text = sanitize_text_field( $trade_button_text );
		return $trade_button_text;
	}

	/**
	 * Sanitize the text 'show_powered_by' value before being saved to database
	 *
	 * @param  string $show_powered_by $_POST value
	 * @since  1.0.3
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_show_powered_by_field( $show_powered_by ) {
		if ( in_array( $show_powered_by, array( 'Yes', 'No' ), true ) ) {
			return $show_powered_by;
		}
	}

	/**
	 * Sanitize the text 'pctchangeinterval' value before being saved to database
	 *
	 * @param  string $pctchangeinterval $_POST value
	 * @since  1.0.3
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_pctchangeinterval_field( $pctchangeinterval ) {
		if ( in_array( $pctchangeinterval, array( 'percent_change_1h', 'percent_change_24h', 'percent_change_7d' ), true ) ) {
			return $pctchangeinterval;
		}
	}

	/**
	 * Sanitize the text 'customstyles' value before being saved to database
	 *
	 * @param  string $customstyles $_POST value
	 * @since  1.0.1
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_customstyles_field( $customstyles ) {
		$customstyles = esc_textarea( $customstyles );
		return $customstyles;
	}

	/**
	 * Sanitize the text 'showdashwidget' value before being saved to database
	 *
	 * @param  string $showdashwidget $_POST value
	 * @since  1.0.4
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_showdashwidget_field( $showdashwidget ) {
		if ( in_array( $showdashwidget, array( 'Yes', 'No' ), true ) ) {
			return $showdashwidget;
		}
	}

	/**
	 * Sanitize the text 'dashwidgetcoininfo' value before being saved to database
	 *
	 * @param  string $dashwidgetcoininfo $_POST value
	 * @since  1.0.4
	 * @return string           Sanitized value
	 */
	public function crypto_coin_ticker_sanitize_dashwidgetcoininfo_field( $dashwidgetcoininfo ) {
		$dashwidgetcoininfo = esc_textarea( $dashwidgetcoininfo );
		return $dashwidgetcoininfo;
	}

	/* ==================== REGISTER FIELDS ==================== */

	/**
	 * Register settings area, fields, and individual settings
	 *
	 * @since 1.0.1
	 */
	public function register_settings_page() {

		// Add the 'Affiliate Settings' section
		add_settings_section(
			$this->option_name . '_affiliate',
			__( 'Affiliate Settings', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_affiliate_settings_section_cb' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->option_name . '_zwaply_affiliate_id',
			__( 'Your Zwaply.com username', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_zwaply_affiliate_id_cb' ),
			$this->plugin_name,
			$this->option_name . '_affiliate',
			array(
				'description' => '<a href="https://zwaply.com/register/" target="_blank">Get yours here to start earning crypto</a>',
				'id'          => $this->option_name . '_zwaply_affiliate_id',
				'value'       => '',
			)
		);

		// Add the 'Appearance Settings' section
		add_settings_section(
			$this->option_name . '_general',
			__( 'Appearance Settings', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_general_settings_section_cb' ),
			$this->plugin_name
		);

		// Add fields to the 'Appearance Settings' section
		add_settings_field(
			$this->option_name . '_colorscheme',
			__( 'Color Scheme', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_colorscheme_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' => 'Choose a light or dark color scheme.',
				'id'          => $this->option_name . '_colorscheme',
				'value'       => 'light',
			)
		);

		add_settings_field(
			$this->option_name . '_currency',
			__( 'Currency', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_currency_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' => 'Choose the currency of the coin prices.',
				'id'          => $this->option_name . '_currency',
				'value'       => 'USD',
			)
		);

		add_settings_field(
			$this->option_name . '_coinslist',
			__( 'Coins to Track', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_coinslist_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' => 'Enter the Index Symbol of each coin you want to track, in a comma-separated list.',
				'id'          => $this->option_name . '_coinslist',
				'value'       => 'BTC,ETH,LTC',
			)
		);

		add_settings_field(
			$this->option_name . '_showicons',
			__( 'Show Coin Icons', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_showicons_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' => 'Show the icons next to each coin',
				'id'          => $this->option_name . '_showicons',
				'value'       => 'Yes',
			)
		);

		add_settings_field(
			$this->option_name . '_pctchangeinterval',
			__( 'Percent Change Interval', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_pctchangeinterval_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' => 'Time range of coin price changes',
				'id'          => $this->option_name . '_pctchangeinterval',
				'value'       => 'percent_change_24h',
			)
		);

		add_settings_field(
			$this->option_name . '_show_trade_button',
			__( 'Show Trade button', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_show_trade_button_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' => 'Show a trade button for coin exchange',
				'id'          => $this->option_name . '_show_trade_button',
				'value'       => 'Yes',
			)
		);

		add_settings_field(
			$this->option_name . '_trade_button_text',
			__( 'Trade button text', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_trade_button_text_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' => 'Change the label of the Trade button',
				'id'          => $this->option_name . '_trade_button_text',
				'value'       => 'Trade',
			)
		);

		add_settings_field(
			$this->option_name . '_show_powered_by',
			__( 'Show Powered By', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_show_powered_by_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' => 'Display "By Zwaply.com" in the widget footer',
				'id'          => $this->option_name . '_show_powered_by',
				'value'       => 'Yes',
			)
		);

		add_settings_field(
			$this->option_name . '_customstyles',
			__( 'Custom CSS', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_customstyles_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array(
				'description' => 'Enter your custom CSS styles here.',
				'id'          => $this->option_name . '_customstyles',
				'value'       => $this->defaultCustomCSS,
			)
		);

		// Add the 'Dashboard Widget Settings' section
		add_settings_section(
			$this->option_name . '_dashwidget',
			__( 'Dashboard Widget Settings', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_dashwidget_settings_section_cb' ),
			$this->plugin_name
		);

		// Add fields to the 'Dashboard Widget Settings' section
		add_settings_field(
			$this->option_name . '_showdashwidget',
			__( 'Show "Crypto Wallet Calculator" on WP Dashboard', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_showdashwidget_cb' ),
			$this->plugin_name,
			$this->option_name . '_dashwidget',
			array(
				'description' => 'Choose whether to show the Crypto Wallet Calculator on the WP Dashboard.',
				'id'          => $this->option_name . '_showdashwidget',
				'value'       => 'Yes',
			)
		);

		$dashWidgetStr  = 'To calculate coin prices, enter each line in the following format: (coin symbol)-(amount of coins). One coin per line. ';
		$dashWidgetStr .= "\r\n";
		$dashWidgetStr .= 'Example: BTC-0.00054321';

		add_settings_field(
			$this->option_name . '_dashwidgetcoininfo',
			__( 'Dashboard Widget Coin Info', 'crypto-coin-ticker' ),
			array( $this, $this->option_name . '_dashwidgetcoininfo_cb' ),
			$this->plugin_name,
			$this->option_name . '_dashwidget',
			array(
				'description' => '',
				'id'          => $this->option_name . '_dashwidgetcoininfo',
				'value'       => 'BTC-0.00054321',
			)
		);

		// Register and Sanitize the fields
		// * Affiliate Settings
		register_setting( $this->plugin_name, $this->option_name . '_zwaply_affiliate_id', array( $this, $this->option_name . '_sanitize_zwaply_affiliate_id_field' ) );

		// * Appearance Settings
		register_setting( $this->plugin_name, $this->option_name . '_colorscheme', array( $this, $this->option_name . '_sanitize_colorscheme_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_currency', array( $this, $this->option_name . '_sanitize_currency_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_coinslist', array( $this, $this->option_name . '_sanitize_coinslist_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_showicons', array( $this, $this->option_name . '_sanitize_showicons_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_show_trade_button', array( $this, $this->option_name . '_sanitize_show_trade_button_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_trade_button_text', array( $this, $this->option_name . '_sanitize_trade_button_text_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_show_powered_by', array( $this, $this->option_name . '_sanitize_show_powered_by_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_pctchangeinterval', array( $this, $this->option_name . '_sanitize_pctchangeinterval_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_customstyles', array( $this, $this->option_name . '_sanitize_customstyles_field' ) );

		// * Dashboard Widget Settings
		register_setting( $this->plugin_name, $this->option_name . '_showdashwidget', array( $this, $this->option_name . '_sanitize_showdashwidget_field' ) );
		register_setting( $this->plugin_name, $this->option_name . '_dashwidgetcoininfo', array( $this, $this->option_name . '_sanitize_dashwidgetcoininfo_field' ) );
	}
}

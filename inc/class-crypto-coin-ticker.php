<?php
/**
 * The core plugin class that defines admin hooks and public hooks
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link        https://zwaply.com
 * @since       1.0.1
 * @package     Crypto_Coin_Ticker
 * @subpackage  Crypto_Coin_Ticker/admin/partials
 * @author      Zwaply <info@zwaply.com>
 */
class Crypto_Coin_Ticker {


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.1
	 */
	public function __construct() {
		$this->plugin_name = 'crypto-coin-ticker';
		$this->version     = '1.0.7';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Crypto_Coin_Ticker_Admin. Defines all hooks for the admin area.
	 * - Crypto_Coin_Ticker_Public. Defines all hooks for the public side of the site.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-crypto-coin-ticker-admin.php';

		$showdashwidget = get_option( 'crypto_coin_ticker_showdashwidget' );
		if ( $showdashwidget !== 'No' ) {
			/**
			 * The class responsible for defining all actions that occur in the admin Dashboard Wallet widget area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-crypto-coin-ticker-dashboard-wallet.php';
    }
    
    /** Affiliate dashboard widget */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-crypto-coin-ticker-dashboard-affiliate.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-crypto-coin-ticker-public.php';
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Crypto_Coin_Ticker_Admin( $this->get_plugin_name(), $this->get_version() );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_filter( 'plugin_action_links_' . CCTICKER_PLUGIN_FILE, array( $plugin_admin, 'add_settings_link' ) );
		add_action( 'admin_menu', array( $plugin_admin, 'add_admin_pages' ) );
		add_action( 'admin_init', array( $plugin_admin, 'register_settings_page' ) );
		add_action( 'admin_init', array( $plugin_admin, 'dismiss_notices' ) );
		add_action( 'admin_notices', array( $plugin_admin, 'admin_notices' ) );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Crypto_Coin_Ticker_Public( $this->get_plugin_name(), $this->get_version() );

		// do stuff with $plugin_public
	}
}

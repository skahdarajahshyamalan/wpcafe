<?php
/**
 * Plugin Name:        WP Cafe Pro
 * Plugin URI:         https://product.themewinter.com/wpcafe
 * Description:        WordPress Restaurant solution plugin to launch Restaurant Websites.
 * Version:            2.2.14
 * Author:             Themewinter
 * Author URI:         https://themewinter.com/
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:        wpcafe-pro
 * Domain Path:       /languages
 */

/**
 *  @package wpcafe-pro
 */
defined( "ABSPATH" ) || exit;

final class Wpcafe_Pro {

	/**
	 * Plugin Version
	 *
	 * @since 1.3.9
	 *
	 * @var string The plugin version.
	 */
	public static function version() {
		return '2.2.14';
	}

	/**
	 * Instance of self
	 *
	 * @since 1.3.9
	 *
	 * @var Wpcafe_Pro
	 */
	private static $instance = null;

	/**
	 * Initializes the Wpcafe_Pro() class
	 *
	 * Checks for an existing Wpcafe_Pro() instance
	 * and if it doesn't find one, creates it.
	 */
	public static function init() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instance of Wpcafe
	 */
	private function __construct() {

		$this->define_constants();

		// Load translation.
		add_action( 'init', [$this, 'i18n'] );

		// Instantiate Base Class after plugins loaded
		add_action( 'plugins_loaded', [$this, 'initialize_modules'], 9999 );
	}

	/**
	 * Define Required Constants
	 *
	 * @return void
	 */
	public function define_constants(){
		// handle demo site features
		define( 'WPC_DEMO_SITE', false );
		if( WPC_DEMO_SITE === true ){
				define('WPC_VARIATION_TEMPLATE_ONE_ID', '3422');
				define('WPC_VARIATION_TEMPLATE_TWO_ID', '1339');
		}
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.3.9
	 * @access public
	 */
	public function i18n() {
		// load text domain.
		load_plugin_textdomain( 'wpcafe-pro', false, dirname( self::plugins_basename( ) ) . '/languages/' );
	}


	/**
	 * Initialize Modules
	 * @since 1.3.9
	 */
	public function initialize_modules() {

		do_action( 'wpcafe_pro/before_load' );
		require_once self::plugin_dir() . '/bootstrap.php';

		// action plugin instance class
		\WpCafe_Pro\Bootstrap::instance()->init();

		do_action( 'wpcafe_pro/after_load' );
	}

	/**
	 * Assets Directory Url
	 *
	 * @return void
	 */
	public static function assets_url() {
		return trailingslashit( self::plugin_url() . 'assets' );
	}

	/**
	 * Assets Folder Directory Path
	 * @since 1.3.9
	 *
	 * @return void
	 */
	public static function assets_dir() {
		return trailingslashit( self::plugin_dir() . 'assets' );
	}

	/**
	 * Plugin Core File Directory Url
	 * @since 1.3.9
	 *
	 * @return void
	 */
	public static function core_url(){
			return trailingslashit( self::plugin_url() . 'core' );
	}

	/**
	 * Plugin Core File Directory Path
	 * @since 1.3.9
	 *
	 * @return void
	 */
	public static function core_dir(){
			return trailingslashit( self::plugin_dir() . 'core' );
	}

	/**
	 * Plugin Url
	 * @since 1.3.9
	 *
	 * @return void
	 */
	public static function plugin_url(){
		return trailingslashit( plugin_dir_url( self::plugin_file() ) );
	}

	/**
	 * Plugin Directory Path.
	 * @since 1.3.9
	 *
	 * @return void
	 */
	public static function plugin_dir() {
		return trailingslashit( plugin_dir_path( self::plugin_file() ) );
	}

	/**
	 * Plugins Basename
	 * @since 1.3.9
	 */
	public static function plugins_basename() {
		return plugin_basename( self::plugin_file() );
	}

	/**
	 * Plugin File
	 *
	 * @since 1.3.9
	 *
	 * @return void
	 */
	public static function plugin_file() {
		return __FILE__;
	}
}

	/**
	 * Load Wpcafe Addon when all plugins are loaded
	 *
	 * @return Wpcafe
	 */
	function wpcafe_pro() {
		return Wpcafe_Pro::init();
	}

// Let's Go...
wpcafe_pro();



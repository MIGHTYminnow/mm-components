<?php
/**
 * Plugin Name: MIGHTYminnow Add-ons
 * Plugin URI: http://mightyminnow.com
 * Description: Custom components and functionality for WordPress.
 * Version: 1.0.0
 * Author: MIGHTYminnow Web Studio
 * Author URI: http://mightyminnow.com
 * License: GPL2+
 * Text Domain: mm-add-ons
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The main plugin class.
 */
class Mm_Components {

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @var    string  $version  The current version of this plugin.
	 */
	public $version;

	/**
	 * Plugin slug.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	public $plugin_slug;

	/**
	 * Plugin display name.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	public $plugin_display_name;

	/**
	 * Plugin name.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	public $plugin_name;

	/**
	 * Plugin directory URL.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	public $plugin_url;

	/**
	 * Plugin assets URL.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	public $plugin_assets_url;

	/**
	 * Plugin directory path.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	public $plugin_path;

	/**
	 * The Constructor.
	 *
	 * @since  1.0.0
	 * @access  private
	 */
	private function __construct() {

		// Set up the reference vars.
		$this->plugin_slug         = 'mm-components';
		$this->plugin_display_name = __( 'MIGHTYminnow Components', 'mm-components' );
		$this->plugin_name         = 'mm_components';
		$this->version             = '1.0.0';
		$this->plugin_url          = plugin_dir_url( __FILE__ );
		$this->plugin_assets_url   = $this->plugin_url . 'assets/';
		$this->plugin_path         = plugin_dir_path( __FILE__ );

		// Load the plugin text domain.
		add_action( 'init', array( $this, 'load_text_domain' ) );

		// Register the public scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_scripts_and_styles') );

		// Include the components.
		$this->include_components();

		// Maybe include Visual Composer integration.
		$this->maybe_include_vc();

	}

	/**
	 * Load plugin text domain.
	 *
	 * @since  1.0.0
	 */
	public function load_text_domain() {

		load_plugin_textdomain( $this->plugin_slug, false, $this->plugin_path . 'languages' );
	}

	/**
	 * Enqueue front-end scripts and styles.
	 *
	 * @since  1.0.0
	 */
	public function register_public_scripts_and_styles() {

		// Public styles.
		wp_register_style(
			$this->plugin_slug,
			$this->plugin_url . 'css/mm-components-public.css',
			array(),
			$this->version
		);

		// Public scripts.
		wp_register_script(
			$this->plugin_slug,
			$this->plugin_url . 'js/mm-components-public.js',
			array( 'jquery' ),
			$this->version,
			true
		);

	}

	/**
	 * Include the components.
	 *
	 * @since  1.0.0
	 */
	public function include_components() {

		require_once $this->plugin_path . 'components/blockquote.php';
		require_once $this->plugin_path . 'components/button.php';
		require_once $this->plugin_path . 'components/countdown/countdown.php';
		require_once $this->plugin_path . 'components/custom-heading.php';
		require_once $this->plugin_path . 'components/hero-banner.php';
		require_once $this->plugin_path . 'components/highlight-box.php';
		require_once $this->plugin_path . 'components/icon-box.php';
		require_once $this->plugin_path . 'components/image-grid.php';
		require_once $this->plugin_path . 'components/logo-strip.php';
		require_once $this->plugin_path . 'components/polaroid.php';
		require_once $this->plugin_path . 'components/polaroid-2.php';
		require_once $this->plugin_path . 'components/twitter-feed.php';
	}

	/**
	 * Maybe include Visual Composer integration.
	 */
	public function maybe_include_vc() {

		// Only if VC is activated.
		if ( self::is_vc_active() ) {
			require_once $this->plugin_path . 'vc-functions.php';
		}
	}

	/**
	 * Check if Visual Composer is active.
	 */
	public static function is_vc_active() {

		if ( defined( 'WPB_VC_VERSION' ) ) {
			return true;
		} else {
			return false;
		}

	}

}

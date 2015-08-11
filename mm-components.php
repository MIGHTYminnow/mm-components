<?php
/**
 * Plugin Name: MIGHTYminnow Components
 * Plugin URI: http://mightyminnow.com
 * Description: Custom components and functionality for WordPress.
 * Version: 1.0.0
 * Author: MIGHTYminnow Web Studio
 * Author URI: http://mightyminnow.com
 * License: GPL2+
 * Text Domain: mm-components
 * Domain Path: /languages
 */

define( 'MM_PLUG_PATH', plugin_dir_path( __FILE__ ) );
define( 'MM_PLUG_INCLUDES_PATH', MM_PLUG_PATH . 'includes/' );
define( 'MM_PLUG_ASSETS_URL', plugins_url( 'assets/', __FILE__ ) );

add_action( 'plugins_loaded', 'mm_components_startup' );
/**
 * Start the plugin.
 *
 * @since    1.0.0
 */
function mm_components_startup() {

	// Set up text domain.
	load_plugin_textdomain( 'mm-components', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	// Include general functionality.
	require_once MM_PLUG_PATH . 'functions.php';

	// Include the components.
	require_once MM_PLUG_PATH . 'components/blockquote.php';
	require_once MM_PLUG_PATH . 'components/button.php';
	require_once MM_PLUG_PATH . 'components/countdown/countdown.php';
	require_once MM_PLUG_PATH . 'components/custom-heading.php';
	require_once MM_PLUG_PATH . 'components/expandable-content.php';
	require_once MM_PLUG_PATH . 'components/hero-banner.php';
	require_once MM_PLUG_PATH . 'components/highlight-box.php';
	require_once MM_PLUG_PATH . 'components/icon-box.php';
	require_once MM_PLUG_PATH . 'components/image-grid.php';
	require_once MM_PLUG_PATH . 'components/logo-strip.php';
	require_once MM_PLUG_PATH . 'components/polaroid.php';
	require_once MM_PLUG_PATH . 'components/polaroid-2.php';
	require_once MM_PLUG_PATH . 'components/twitter-feed.php';

	// Include Visual Composer Integration if VC is activated.
	if ( defined( 'WPB_VC_VERSION' ) ) {
		require_once MM_PLUG_PATH . 'vc-functions.php';
	}

	// Load front-end scripts and styles.
	add_action( 'wp_enqueue_scripts', 'mm_components_scripts_and_styles');

}

/**
 * Enqueue front-end scripts and styles.
 *
 * @since  1.0.0
 */
function mm_components_scripts_and_styles() {

	// General styles.
	wp_enqueue_style( 'mm-components', plugins_url( '/css/mm-components-public.css', __FILE__ ) );

	// General scripts.
	wp_enqueue_script( 'mm-components', plugins_url( '/js/mm-components-public.js', __FILE__ ) );

}

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

define( 'MM_COMPONENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'MM_COMPONENTS_URL', plugin_dir_url( __FILE__ ) );
define( 'MM_COMPONENTS_ASSETS_URL', MM_COMPONENTS_URL . 'assets/' );

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
	require_once MM_COMPONENTS_PATH . 'functions.php';

	// Include the components.
	require_once MM_COMPONENTS_PATH . 'components/blockquote.php';
	require_once MM_COMPONENTS_PATH . 'components/button.php';
	require_once MM_COMPONENTS_PATH . 'components/countdown/countdown.php';
	require_once MM_COMPONENTS_PATH . 'components/custom-heading.php';
	require_once MM_COMPONENTS_PATH . 'components/expandable-content.php';
	require_once MM_COMPONENTS_PATH . 'components/hero-banner.php';
	require_once MM_COMPONENTS_PATH . 'components/highlight-box.php';
	require_once MM_COMPONENTS_PATH . 'components/icon-box.php';
	require_once MM_COMPONENTS_PATH . 'components/image-grid.php';
	require_once MM_COMPONENTS_PATH . 'components/logo-strip.php';
	require_once MM_COMPONENTS_PATH . 'components/polaroid.php';
	require_once MM_COMPONENTS_PATH . 'components/polaroid-2.php';
	require_once MM_COMPONENTS_PATH . 'components/twitter-feed.php';

	// Include Visual Composer Integration if VC is activated.
	if ( defined( 'WPB_VC_VERSION' ) ) {
		require_once MM_COMPONENTS_PATH . 'integrations/visual-composer/vc-functions.php';
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
	wp_enqueue_style( 'mm-components', MM_COMPONENTS_URL . 'css/mm-components-public.css' );

	// General scripts.
	wp_enqueue_script( 'mm-components', MM_COMPONENTS_URL . 'js/mm-components-public.js' );
}

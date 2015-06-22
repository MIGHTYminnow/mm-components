<?php
/**
 * Plugin Name: MIGHTYminnow: Visual Composer Add-ons
 * Plugin URI: http://mightyminnow.com
 * Description: Custom add-ons for visual composer.
 * Version: 1.0.0
 * Author: MIGHTYminnow Web Studio
 * Author URI: http://mightyminnow.com
 * License: GPL2+
 * Text Domain: mm-visual-composer-add-ons
 * Domain Path: /languages
 */

define( 'MM_PLUG_PATH', plugin_dir_path( __FILE__ ) );
define( 'MM_PLUG_INCLUDES_PATH', MM_PLUG_PATH . 'includes/' );
define( 'MM_PLUG_ASSETS_URL', plugins_url( 'assets/', __FILE__ ) );

add_action( 'plugins_loaded', 'mm_vcao_startup' );
/**
 * Check if Visual Composer is active, and startup plugin.
 *
 * @since    1.0.0
 */
function mm_vcao_startup() {

	// Don't do anything if VC isn't installed/active.
	if ( ! defined( 'WPB_VC_VERSION' ) ) {
		return;
	}

	// Set up text domain.
	load_plugin_textdomain( 'mm-visual-composer-add-ons', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	// Load custom functionality.
	require_once MM_PLUG_PATH . 'functions.php';

	// Load custom VC components.
	require_once MM_PLUG_PATH . 'components/blockquote.php';
	require_once MM_PLUG_PATH . 'components/countdown/countdown.php';
	require_once MM_PLUG_PATH . 'components/highlight-box.php';
	require_once MM_PLUG_PATH . 'components/icon-box.php';

	// Load front-end scripts and styles (priority 20 to load after Trestle).
	add_action( 'wp_enqueue_scripts', 'mm_vcao_scripts_and_styles', 20 );

}

/**
 * Enqueue front-end scripts and styles.
 *
 * @since  1.0.0
 */
function mm_vcao_scripts_and_styles() {

	// Visual Composer - general styles
	wp_enqueue_style( 'mm-visual-composer-add-ons', plugins_url( '/css/style.css', __FILE__ ) );

	// Visual Composer - general scripts
	wp_enqueue_script( 'mm-visual-composer-add-ons', plugins_url( '/js/scripts.js', __FILE__ ) );

}

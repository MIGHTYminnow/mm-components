<?php
/**
 * Mm Components Admin Class
 *
 * @since  1.0.0
 */

class Mm_Components_Admin {

	/**
	 * The constructor.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		// Set up our admin hooks.
		$this->initialize();
	}

	/**
	 * Set up our admin hooks.
	 *
	 * @since  1.0.0
	 */
	public function initialize() {

		// Include our JS and CSS.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @since  1.0.0
	 */
	function admin_enqueue( $hook ) {

		// Alpha Color Picker CSS.
		wp_register_style(
			'alpha-color-picker',
			MM_COMPONENTS_URL . 'lib/alpha-color-picker/alpha-color-picker.css',
			array( 'wp-color-picker' ),
			MM_COMPONENTS_VERSION
		);

		// Alpha Color Picker JS.
		wp_register_script(
			'alpha-color-picker',
			MM_COMPONENTS_URL . 'lib/alpha-color-picker/alpha-color-picker.js',
			array( 'jquery', 'wp-color-picker' ),
			MM_COMPONENTS_VERSION,
			true
		);

		// Mm Components Admin CSS.
		wp_register_style(
			'mm-components-admin',
			MM_COMPONENTS_URL . 'css/mm-components-admin.css',
			array(),
			MM_COMPONENTS_VERSION
		);

		// Mm Components Admin JS.
		wp_register_script(
			'mm-components-admin',
			MM_COMPONENTS_URL . 'js/mm-components-admin.js',
			array(),
			MM_COMPONENTS_VERSION,
			true
		);

		// Only enqueue on specific admin pages.
		if ( 'widgets.php' === $hook ) {
			wp_enqueue_media();
			wp_enqueue_style( 'alpha-color-picker' );
			wp_enqueue_script( 'alpha-color-picker' );
			wp_enqueue_style( 'mm-components-admin' );
			wp_enqueue_script( 'mm-components-admin' );
		}
	}

}
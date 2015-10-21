<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Countdown
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Countdown component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_countdown( $args ) {

	$component = 'mm-countdown';

	// Set our defaults and use them as needed.
	$defaults = array(
		'date'     => '',
		'time'     => '',
		'timezone' => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$date     = $args['date'];
	$time     = $args['time'];
	$timezone = $args['timezone'];

	// Enqueue pre-registerd 3rd party countdown script.
	wp_enqueue_script( 'mm-countdown' );

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	// Create new date object.
	$date_obj = new DateTime( $date . ' ' . $time . ' ' . $timezone );
	$utc_date_ojb  = new DateTime( $date . ' ' . $time );

	// Get timezone offset.
	$timezone_offset = $date_obj->getTimezone()->getOffset( $utc_date_ojb ) / 3600;

	// Pass date as comma-separated list.
	$year   = $date_obj->format( 'Y' );
	$month  = $date_obj->format( 'n' );
	$day    = $date_obj->format( 'j' );
	$hour   = $date_obj->format( 'G' );
	$minute = $date_obj->format( 'i' );
	$second = $date_obj->format( 's' );

	$output = sprintf( '<div class="%s" data-year="%s" data-month="%s" data-day="%s" data-hour="%s" data-minute="%s" data-second="%s" data-timezone-offset="%s"></div>',
		$mm_classes,
		$year,
		$month,
		$day,
		$hour,
		$minute,
		$second,
		$timezone_offset
	);

	return $output;
}

add_shortcode( 'mm_countdown', 'mm_countdown_shortcode' );
/**
 * Output Countdown.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_countdown_shortcode( $atts ) {

	return mm_countdown( $atts );
}

add_action( 'vc_before_init', 'mm_vc_countdown' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_countdown() {

	// Add a custom param for selecting the date
	add_shortcode_param( 'date', 'mm_vc_date_param' );

	// Add a custom param for selecting the TimeZone
	add_shortcode_param( 'timezone', 'mm_vc_timezone_param' );

	vc_map( array(
		'name'     => __( 'Countdown', 'mm-components' ),
		'base'     => 'mm_countdown',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'        => 'date',
				'heading'     => __( 'Date', 'mm-components' ),
				'param_name'  => 'date',
				'admin_label' => true,
				'value'       => '',
				'description' => __( 'Must be in the format MM/DD/YYYY. Example: 12/25/2015 would be Christmas of 2015.', 'mm-components' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Time', 'mm-components' ),
				'param_name'  => 'time',
				'value'       => '',
				'description' => __( 'Must be in the format HH:MM:SS. Example: 18:30:00 would be 6:30 PM.', 'mm-components' ),
			),
			array(
				'type'       => 'timezone',
				'heading'    => __( 'Time Zone', 'mm-components' ),
				'param_name' => 'timezone',
				'value'      => '',
			),
		)
	) );
}

add_action( 'wp_enqueue_scripts', 'mm_countdown_enqueue_scripts' );
/**
 * Enqueue the countdown script.
 */
function mm_countdown_enqueue_scripts() {

	/**
	 * 3rd party countdown script.
	 *
	 * @see  http://hilios.github.io/jQuery.countdown/
	 */
	wp_register_script( 'mm-countdown', plugins_url( '/js/jquery.countdown.js', __FILE__ ), array( 'jquery' ), null, true );
}

<?php
/**
 * Mm Components Visual Composer Custom Param Types.
 *
 * @since 1.0.0
 *
 * @package mm-components
 */

/**
 * Visual Composer Custom Date Param.
 *
 * @since  1.0.0
 *
 * @param   array   $settings  The param settings.
 * @param   string  $value     The param value.
 *
 * @return  string             The param HTML.
 */
function mm_vc_date_param( $settings, $value ) {

	$output = sprintf( '<input type="date" class="countdown-date wpb_vc_param_value" value="%s" name="%s" />',
		$value,
		esc_attr( $settings['param_name'] )
	);

	return $output;
}
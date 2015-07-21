<?php
/**
 * Mm Custom Add-ons General Functionality.
 *
 * @since 1.2.0
 *
 * @package mm-add-ons
 */

add_filter( 'mm_shortcode_custom_classes', 'mm_shortcode_custom_classes', 10, 3 );
/**
 * Add custom shortcode classes.
 *
 * @since   1.2.0
 *
 * @param   string  $classes  Initial classes.
 * @param   string  $tag      Shortcode tag.
 * @param   array   $atts     Shortcoder atts.
 *
 * @return  string            Modified classes.
 */
function mm_shortcode_custom_classes( $classes, $tag, $atts ) {

	/**
	 * Add classes for custom shortcode attributes
	 *
	 * These classes are defined, and identifiable, with the following format:
	 * mm_class_*
	 */

	// Set up classes array.
	$class_array = explode( ' ', $classes );

	// Loop through each att and add class as needed.
	foreach ( $atts as $key => $value ) {

		// Add class in the following format: $key-$class
		// Exclude custom class att as this needs to be unprefixed.
		if ( false !== strpos( $key, 'mm_class_' ) ) {
			$class_array[] = $key . '-' . str_replace( ',', ' ', $value );
		}

	}

	// Add mm_custom_class att as unprefixed class.
	if ( ! empty ( $atts['mm_custom_class'] ) ) {
		$class_array[] = $atts['mm_custom_class'];
	}

	// Add custom classes to existing classes.
	$classes = implode( ' ', $class_array );

	// Apply Visual Composer filter.
	$classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $classes, $tag, $atts );

	return $classes;

}

/**
 * Parse args with defaults, allowing for unexpected args.
 *
 * @since 1.2.0
 *
 * @param array $defaults Default values.
 * @param array $atts     Atts to be parsed.
 *
* @return array Updated atts
 */
function mm_shortcode_atts( $defaults = array(), $atts = array() ) {
	return wp_parse_args( $atts, $defaults );
}

/**
 * Return custom VC image upload description.
 *
 * @since     1.2.0
 *
 * @param     string    $image_size    Image size slug.
 *
 * @return    string    Image upload description.
 */
function mm_custom_image_field_description( $image_size = '' ) {

	$default_message = __( 'Upload an image that is large enough to be output without stretching.', 'mm-add-ons' );

	// Do default image message if no specific image_size is passed.
	if ( ! $image_size ) {
		return $default_message;
	}

	// Get dimensions of image.
	$image_dimensions = mm_get_image_size_dimensions( $image_size );

	// Do default message if the specified image size doesn't exists.
	if ( ! $image_dimensions ) {
		return $default_message;
	}

	$width = $image_dimensions['width'] * 2;
	$height = $image_dimensions['height'] * 2;

	return sprintf( __( 'Upload an image that is at least <b>%dpx</b> × <b>%dpx</b> to ensure that it is not stretched.', 'mm-add-ons' ), $width, $height );

}

/**
 * Get the dimensions of WP default and add-on image sizes.
 *
 * @since     1.2.0
 *
 * @param     string    $image_size          Image size slug.
 *
 * @return    array     $image_dimensions    Array of image width/height.
 */
function mm_get_image_size_dimensions( $image_size = '' ) {

	global $_wp_additional_image_sizes;

	if ( in_array( $image_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

		$image_dimensions['width'] = get_option( $image_size . '_size_w' );
		$image_dimensions['height'] = get_option( $image_size . '_size_h' );

	} elseif ( isset( $_wp_additional_image_sizes[ $image_size ] ) ) {

		$image_dimensions = array(
			'width' => $_wp_additional_image_sizes[ $image_size ]['width'],
			'height' => $_wp_additional_image_sizes[ $image_size ]['height'],
		);

	} else {
		return false;
	}

	return $image_dimensions;

}

/**
 * Possibly wrap content in a link.
 *
 * @since 1.0.0
 *
 * @param mixed $content Content to go in link.
 * @param array  $link_array Array of link data: url|title|target
 *
 * @return string HTML output.
 */
function mm_maybe_wrap_in_link( $content, $link_array = array() ) {

	if ( empty( $link_array['url'] ) ) {
		return $content;
	}

	return sprintf( '<a href="%s" title="%s" target="%s">%s</a>',
		$link_array['url'],
		$link_array['title'],
		$link_array['target'],
		$content
	);
}
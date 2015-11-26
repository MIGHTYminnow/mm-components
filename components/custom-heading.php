<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Custom Heading
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Custom Heading component.
 *
 * @since 1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_custom_heading( $args ) {

	$component = 'mm-custom-heading';

	//Set our defaults and use them as needed.
	$defaults = array(
		'heading_text'  => '',
		'heading'       => 'h2',
		'font_family'   => '',
		'size'          => '',
		'weight'        => '',
		'transform'     => '',
		'alignment'     => 'left',
		'color'         => '',
		'margin_bottom' => '',
		'link'          => '',
		'link_title'    => '',
		'link_target'   => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	//Handle a raw link or VC link array.
	$link_url    = '';
	$link_title  = '';
	$link_target = '';

	if ( ! empty( $args['link'] ) ) {

		if ( 'url' === substr( $args['link'], 0, 3 ) ) {

			if ( function_exists( 'vc_build_link' ) ) {

				$link_array  = vc_build_link( $args['link'] );
				$link_url    = $link_array['url'];
				$link_title  = $link_array['title'];
				$link_target = $link_array['target'];
			}
		} else {

			$link_url    = $args['link'];
			$link_title  = $args['link_title'];
			$link_target = $args['link_target'];
		}
	}

	// Wrap the heading in a link if one was passed in.
	if ( ! empty( $link_url ) ) {
		$content = sprintf(
			'<a href="%s" title="%s" target="%s">%s</a>',
			esc_url( $link_url ),
			esc_attr( $link_title ),
			esc_attr( $link_target ),
			wp_kses_post( $content )
		);
	}

	// Set up custom heading classes.
	$classes = array();
	if ( ! empty( $args['font_family'] ) ) {
		$classes[] = $args['font_family'];
	}
	if ( ! empty( $args['weight'] ) ) {
		$classes[] = $args['weight'];
	}
	if ( ! empty( $args['transform'] ) ) {
		$classes[] = 'mm-text-transform-' . $args['transform'];
	}
	if ( ! empty( $args['alignment'] ) ) {
		$classes[] = 'mm-text-align-' . $args['alignment'];
	}
	if ( ! empty( $args['color'] ) ) {
		$classes[] = 'mm-text-color-' . $args['color'];
	}

	$classes = implode( ' ', $classes );

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	// Set up our styles array.
	$styles = array();
	if ( ! empty( $args['margin_bottom'] ) ) {
		$styles[] = 'margin-bottom: ' . (int)$args['margin_bottom'] . 'px;';
	}
	if ( ! empty( $args['size'] ) ) {
		$styles[] = 'font-size: ' . (int)$args['size'] . 'px;';
	}

	// Build our string of styles.
	$styles = implode( ' ', $styles );
	$style = ( '' !== $styles ) ? 'style="' . $styles . '"' : '';

	// Set up the heading.
	$heading = ( '' !== $args['heading'] ) ? (string)$args['heading'] : 'h2';

	// Do something with the heading text.
	$heading_text = sanitize_text_field( $args['heading_text'] );

	// Generate the output.
	$output = sprintf( '<%s class="%s" %s>%s</%s>',
		$heading,
		$mm_classes . ' ' . $classes . ' ' $alignment,
		$style,
		$heading_text,
		$heading
	);

	return $output;
}

add_shortcode( 'mm_custom_heading', 'mm_custom_heading_shortcode' );
/**
 * Custom Heading shortcode.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_custom_heading_shortcode( $atts = array(), $content = null ) {

	if ( $content ) {
		$atts['heading_text'] = $content;
	}

	return mm_custom_heading( $atts );
}

add_action( 'vc_before_init', 'mm_vc_custom_heading' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_custom_heading() {

	$heading_levels = mm_get_heading_levels_for_vc( 'mm-custom-heading' );
	$font_options = mm_get_font_options_for_vc( 'mm-custom-heading' );
	$colors = mm_get_available_colors_for_vc( 'mm-custom-heading' );
	$text_alignment = mm_get_text_alignment_for_vc( 'mm-custom-heading' );


	vc_map( array(
		'name'     => __( 'Custom Heading', 'mm-components' ),
		'base'     => 'mm_custom_heading',
		'class'    => '',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Heading Text', 'mm-components' ),
				'param_name'  => 'content',
				'admin_label' => true,
				'value'       => '',
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Heading Level', 'mm-components' ),
				'param_name'  => 'heading',
				'std'         => 'h2', // Default
				'value'       => $heading_levels,
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Font', 'mm-components' ),
				'param_name'  => 'font_family',
				'value'       => $font_options,
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Font Size', 'mm-components' ),
				'param_name'  => 'size',
				'value'       => '',
				'description' => __( 'Leave blank for default heading size, or use a numeric value (number of pixels, e.g. 16)', 'mm-components' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Font Weight', 'mm-components' ),
				'param_name' => 'weight',
				'value'      => array(
					__( 'Normal', 'mm-components' ) => 'normal',
					__( 'Thin', 'mm-components' )   => 'thin',
					__( 'Bold', 'mm-components' )   => 'bold',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Text Transform', 'mm-components' ),
				'param_name' => 'text_transform',
				'value'      => array(
					__( 'None', 'mm-components ') => 'none',
					__( 'Uppercase', 'mm-components ') => 'uppercase',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Text Align', 'mm-components' ),
				'param_name' => 'alignment',
				'value'      => $text_alignment,
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Color', 'mm-components' ),
				'param_name' => 'color',
				'value'      => $colors,
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Margin Bottom', 'mm-components' ),
				'param_name'  => 'margin_bottom',
				'value'       => '',
				'description' => __( 'Leave blank for default margin, or use a numeric value (number of pixels, e.g. 16).', 'mm-components' ),
			),
			array(
				'type'       => 'vc_link',
				'heading'    => __( 'Heading Link', 'mm-components' ),
				'param_name' => 'link',
				'value'      => '',
			),
		),
	) );
}

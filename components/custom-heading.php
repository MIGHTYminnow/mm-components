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
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_custom_heading( $args ) {

	$component = 'mm-custom-heading';

	// Set our defaults and use them as needed.
	$defaults = array(
		'heading_text'   => '',
		'heading'        => 'h2',
		'font_family'    => '',
		'size'           => '',
		'weight'         => '',
		'text_transform' => '',
		'alignment'      => 'left',
		'color'          => '',
		'margin_bottom'  => '',
		'link'           => '',
		'link_title'     => '',
		'link_target'    => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$heading_text   = $args['heading_text'];
	$heading        = $args['heading'];
	$font_family    = $args['font_family'];
	$size           = $args['size'];
	$weight         = $args['weight'];
	$text_transform = $args['text_transform'];
	$alignment      = $args['alignment'];
	$color          = $args['color'];
	$margin_bottom  = $args['margin_bottom'];
	$link           = $args['link'];
	$link_title     = $args['link_title'];
	$link_target    = $args['link_target'];

	// Only allow valid headings.
	if ( ! in_array( $heading, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ) {
		$heading = 'h2';
	}

	// Handle a VC link.
	if ( ! empty( $link ) ) {
		if ( 'url' === substr( $link, 0, 3 ) && function_exists( 'vc_build_link' ) ) {
			$link_array  = vc_build_link( $link );
			$link        = $link_array['url'];
			$link_title  = $link_array['title'];
			$link_target = $link_array['target'];
		}
	}

	// Set up our classes.
	$classes = array();
	if ( ! empty( $font_family ) ) {
		$classes[] = 'mm-font-family-' . $font_family;
	}
	if ( ! empty( $weight ) ) {
		$classes[] = 'mm-font-weight-' . $weight;
	}
	if ( ! empty( $text_transform ) ) {
		$classes[] = 'mm-text-transform-' . $text_transform;
	}
	if ( ! empty( $alignment ) ) {
		$classes[] = 'mm-text-align-' . $alignment;
	}
	if ( ! empty( $color ) ) {
		$classes[] = 'mm-text-color-' . $color;
	}
	$classes = implode( ' ', $classes );

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $component, $args );

	// Set up our inline styles.
	$styles = array();
	if ( 1 < (int)$margin_bottom ) {
		$styles[] = 'margin-bottom: ' . (int)$margin_bottom . 'px;';
	}
	if ( 1 < (int)$size ) {
		$styles[] = 'font-size: ' . (int)$size . 'px;';
	}
	$styles = implode( ' ', $styles );

	$style_attr = ( ! empty( $styles ) ) ? ' style="' . esc_attr( $styles ) . '"' : '';

	// Escape and maybe wrap the heading text in a link.
	if ( ! empty( $link ) ) {
		$heading_text = sprintf(
			'<a href="%s" title="%s" target="%s">%s</a>',
			esc_url( $link ),
			esc_attr( $link_title ),
			esc_attr( $link_target ),
			esc_html( $heading_text )
		);
	} else {
		$heading_text = esc_html( $heading_text );
	}

	// Generate the heading HTML.
	$output = sprintf( '<%s class="%s"%s>%s</%s>',
		$heading,
		esc_attr( $mm_classes . ' ' . $classes ),
		$style_attr,
		$heading_text,
		$heading
	);

	return $output;
}

add_shortcode( 'mm_custom_heading', 'mm_custom_heading_shortcode' );
/**
 * Custom Heading shortcode.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_custom_heading_shortcode( $atts = array() ) {

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
	$fonts          = mm_get_fonts_for_vc( 'mm-custom-heading' );
	$font_weights   = mm_get_font_weights_for_vc( 'mm-custom-heading' );
	$colors         = mm_get_available_colors_for_vc( 'mm-custom-heading' );
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
				'param_name'  => 'heading_text',
				'admin_label' => true,
				'value'       => '',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Heading Level', 'mm-components' ),
				'param_name' => 'heading',
				'std'        => 'h2', // Default
				'value'      => $heading_levels,
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Font', 'mm-components' ),
				'param_name' => 'font_family',
				'value'      => $fonts,
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Font Size', 'mm-components' ),
				'param_name'  => 'size',
				'value'       => '',
				'description' => __( 'Leave blank to use default heading size, or specify a number of pixels. Example: 16', 'mm-components' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Font Weight', 'mm-components' ),
				'param_name' => 'weight',
				'value'      => $font_weights,
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Text Transform', 'mm-components' ),
				'param_name' => 'text_transform',
				'value'      => array(
					__( 'None', 'mm-components ')      => '',
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
				'description' => __( 'Leave blank to use default margin, or specify a number of pixels. Example: 16', 'mm-components' ),
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

<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Custom Content
 *
 * @package mm-components
 * @since   1.0.0
 */

/**
 * Build and return the Custom Content component.
 *
 * @since   1.0.0
 *
 * @param   array  $args  The args.
 *
 * @return  string        The HTML.
 */
function mm_custom_content( $args ) {

	$component = 'mm-custom-content';

	// Set our defaults and use them as needed.
	$defaults = array(
		'content_text'   => '',
		'element'        => 'p',
		'font_family'    => '',
		'size'           => '',
		'weight'         => '',
		'text_transform' => '',
		'alignment'      => 'left',
		'color'          => '',
		'margin_bottom'  => '',
	);
	$args = wp_parse_args( (array)$args, $defaults );

	// Get clean param values.
	$content_text   = $args['content_text'];
	$element        = $args['element'];
	$font_family    = $args['font_family'];
	$size           = $args['size'];
	$weight         = $args['weight'];
	$text_transform = $args['text_transform'];
	$alignment      = $args['alignment'];
	$color          = $args['color'];
	$margin_bottom  = $args['margin_bottom'];

	// Only allow valid elements.
	if ( ! in_array( $element, array( 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ) {
		$element = 'p';
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

	$content_text = esc_html( $content_text );

	// Generate the element HTML.
	$output = sprintf( '<%s class="%s"%s>%s</%s>',
		$element,
		esc_attr( $mm_classes . ' ' . $classes ),
		$style_attr,
		$content_text,
		$element
	);

	return $output;
}

add_shortcode( 'mm_custom_content', 'mm_custom_content_shortcode' );
/**
 * Custom Content shortcode.
 *
 * @since   1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_custom_content_shortcode( $atts = array() ) {

	return mm_custom_content( $atts );
}

add_action( 'vc_before_init', 'mm_vc_custom_content' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_custom_content() {

	$elements        = mm_get_wrapper_elements_for_vc( 'mm-custom-content' );
	$fonts           = mm_get_fonts_for_vc( 'mm-custom-content' );
	$font_weights    = mm_get_font_weights_for_vc( 'mm-custom-content' );
	$colors          = mm_get_colors_for_vc( 'mm-custom-content' );
	$text_alignments = mm_get_text_alignment_for_vc( 'mm-custom-content' );

	vc_map( array(
		'name'     => __( 'Custom Content', 'mm-components' ),
		'base'     => 'mm_custom_content',
		'class'    => '',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
			array(
				'type'        => 'textarea',
				'heading'     => __( 'Content', 'mm-components' ),
				'param_name'  => 'content_text',
				'admin_label' => true,
				'value'       => '',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Wrapper Element', 'mm-components' ),
				'param_name' => 'element',
				'std'        => 'p', // Default
				'value'      => $elements,
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
				'description' => __( 'Leave blank to use default size, or specify a number of pixels. Example: 16', 'mm-components' ),
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
				'value'      => $text_alignments,
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
		),
	) );
}

add_action( 'register_shortcode_ui', 'mm_components_mm_custom_content_shortcode_ui' );
/**
 * Register UI for Shortcake.
 *
 * @since  1.0.0
 */
function mm_components_mm_custom_content_shortcode_ui() {

	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}

	$elements        = mm_get_wrapper_elements( 'mm-custom-content' );
	$fonts           = mm_get_fonts( 'mm-custom-content' );
	$font_weights    = mm_get_font_weights( 'mm-custom-content' );
	$colors          = mm_get_colors( 'mm-custom-content' );
	$text_alignments = mm_get_text_alignment( 'mm-custom-content' );
	$link_targets    = mm_get_link_targets( 'mm-custom-content' );

	shortcode_ui_register_for_shortcode(
		'mm_custom_content',
		array(
			'label'         => esc_html__( 'Mm Custom Content', 'mm-components' ),
			'listItemImage' => MM_COMPONENTS_ASSETS_URL . 'component-icon.png',
			'attrs'         => array(
				array(
					'label' => esc_html__( 'Content', 'mm-components' ),
					'attr'  => 'content_text',
					'type'  => 'textarea',
				),
				array(
					'label'   => esc_html__( 'Wrapper Element', 'mm-components' ),
					'attr'    => 'element',
					'type'    => 'select',
					'options' => $elements,
				),
				array(
					'label'   => esc_html__( 'Font', 'mm-components' ),
					'attr'    => 'font_family',
					'type'    => 'select',
					'options' => $fonts,
				),
				array(
					'label'       => esc_html__( 'Font Size', 'mm-components' ),
					'description' => esc_html__( 'Leave blank to use default size, or specify a number of pixels. Example: 16', 'mm-components' ),
					'attr'        => 'size',
					'type'        => 'text',
				),
				array(
					'label'   => esc_html__( 'Font Weight', 'mm-components' ),
					'attr'    => 'weight',
					'type'    => 'select',
					'options' => $font_weights,
				),
				array(
					'label'   => esc_html__( 'Text Transform', 'mm-components' ),
					'attr'    => 'text_transform',
					'type'    => 'select',
					'options' => array(
						''          => esc_html__( 'None', 'mm-components '),
						'uppercase' => esc_html__( 'Uppercase', 'mm-components '),
					),
				),
				array(
					'label'   => esc_html__( 'Text Align', 'mm-components' ),
					'attr'    => 'alignment',
					'type'    => 'select',
					'options' => $text_alignments,
				),
				array(
					'heading' => esc_html__( 'Color', 'mm-components' ),
					'attr'    => 'color',
					'type'    => 'select',
					'options' => $colors,
				),
				array(
					'label'       => esc_html__( 'Margin Bottom', 'mm-components' ),
					'description' => esc_html__( 'Leave blank to use default margin, or specify a number of pixels. Example: 16', 'mm-components' ),
					'attr'        => 'margin_bottom',
					'type'        => 'text',
				),
			),
		)
	);
}
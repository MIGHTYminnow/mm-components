<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Custom Heading
 *
 * @package mm-components
 * @since   1.0.0
 */

add_shortcode( 'mm_custom_heading', 'mm_custom_heading_shortcode' );
/**
 * Output Custom Heading.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_custom_heading_shortcode( $atts, $content = null, $tag ) {

	extract( mm_shortcode_atts( array(
		'heading'			=> 'h2',
		'font_family'		=> '',
		'font_size'			=> '',
		'margin_bottom'		=> '',
		'color'				=> '',
		'text_transform'	=> '',
		'text_align'		=> '',
		'link'				=> '',
	), $atts ) );

	// Clean up content - this is necessary
	//$content = wpb_js_remove_wpautop( $content, true );

	// Process margin_bottom into a px value, if it was set
	if ( '' !== $margin_bottom ) {
		$margin_bottom = 'margin-bottom: ' . (int)$margin_bottom . 'px;';
	}

	// Get link array [url, title, target]
	$link_array = vc_build_link( $link );

	if ( isset( $link_array['url'] ) && ! empty( $link_array['url'] ) ) {
		$content = '<a href="' . $link_array['url'] . '" title="' . $link_array['title'] . '">' . $content . '</a>';
	}

	// Get Mm classes
	$mm_classes = str_replace( '_', '-', $tag );
	$mm_classes = apply_filters( 'mm_shortcode_custom_classes', $mm_classes, $tag, $atts );

	// Create heading classes
	$class = implode( ' ', array(
		'font-family-' . $font_family,
		'font-size-' . $font_size,
		'color-' . $color,
		'text-transform-' . $text_transform,
		'text-align-' . $text_align,
	) );

	$output = sprintf( '<%s class="%s" style="%s">%s</%s>',
		$heading,
		$mm_classes . ' ' . $class,
		$margin_bottom,
		$content,
		$heading
	);

	return $output;

}

add_action( 'vc_before_init', 'mm_vc_custom_heading' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_custom_heading() {

	vc_map( array(
		'name' => __( 'Custom Heading', 'mm-components' ),
		'base' => 'mm_custom_heading',
		'class' => '',
		'icon' => MM_COMPONENTS_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Heading Text', 'mm-components' ),
				'param_name' => 'content',
				'admin_label' => true,
				'value' => '',
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Heading Level', 'mm-components' ),
				'param_name' => 'heading',
				'std' => 'h2', // Default
				'value' => array(
					__( 'h1', 'mm-components ') => 'h1',
					__( 'h2', 'mm-components ') => 'h2',
					__( 'h3', 'mm-components ') => 'h3',
					__( 'h4', 'mm-components ') => 'h4',
					__( 'h5', 'mm-components ') => 'h5',
					__( 'h6', 'mm-components ') => 'h6',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Margin Bottom', 'mm-components' ),
				'param_name' => 'margin_bottom',
				'value' => '',
				'description' => __( 'Leave blank for default or use a number value (number of pixels). Example: 16', 'mm-components' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Font Family', 'mm-components' ),
				'param_name' => 'font_family',
				'value' => array(
					__( 'Default', 'mm-components ') => 'default',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Font Size', 'mm-components' ),
				'param_name' => 'font_size',
				'value' => array(
					__( 'Default', 'mm-components ') => 'default',
					__( '50px', 'mm-components ') => '50px',
					__( '48px', 'mm-components ') => '48px',
					__( '40px', 'mm-components ') => '40px',
					__( '36px', 'mm-components ') => '36px',
					__( '30px', 'mm-components ') => '30px',
					__( '24px', 'mm-components ') => '24px',
					__( '16px', 'mm-components ') => '16px',
					__( '14px', 'mm-components ') => '14px',
					__( '12px', 'mm-components ') => '12px',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Color', 'mm-components' ),
				'param_name' => 'color',
				'value' => array(
					__( 'Default', 'mm-components ') => 'default',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Text Transform', 'mm-components' ),
				'param_name' => 'text_transform',
				'value' => array(
					__( 'None', 'mm-components ') => 'none',
					__( 'Uppercase', 'mm-components ') => 'uppercase',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Text Align', 'mm-components' ),
				'param_name' => 'text_align',
				'value' => array(
					__( 'Default', 'mm-components ') => 'default',
					__( 'Left', 'mm-components ') => 'left',
					__( 'Center', 'mm-components ') => 'center',
					__( 'Right ', 'mm-components ') => 'right',
				),
			),
			array(
				'type' => 'vc_link',
				'heading' => __( 'Heading Link', 'mm-components' ),
				'param_name' => 'link',
				'value' => '',
			),
		),
	) );
}

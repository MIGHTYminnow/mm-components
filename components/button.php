<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Mm Button
 *
 * @package mm-components
 * @since   1.0.0
 */

add_shortcode( 'mm_button', 'mm_button_shortcode' );
/**
 * Output Mm Button.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_button_shortcode( $atts, $content = null, $tag ) {

	$atts = mm_shortcode_atts( array(
		'link'         => '',
		'class'        => '',
		'style'        => 'default',
		'border_style' => '',
		'color'        => '',
		'size'         => '',
		'alignment'    => 'left',
	), $atts );

	// Get link array [url, title, target].
	$link_array = vc_build_link( $atts['link'] );

	// Build the alignment class.
	$alignment = 'text-align-' . $atts['alignment'];

	// Setup button classes.
	$class = 'button';
	$class .= ' ' . $atts['class'];
	$class .= ' ' . $atts['style'];
	$class .= ' ' . $atts['border_style'];
	$class .= ' ' . $atts['color'];
	$class .= ' ' . $atts['size'];

	// Get Mm classes.
	$mm_classes = 'button-container';
	$mm_classes = apply_filters( 'mm_shortcode_custom_classes', $mm_classes, $tag, $atts );

	// Build the output.
	ob_start(); ?>

	<div class="<?php echo esc_attr( $mm_classes . ' ' . $alignment ); ?>">
		<a class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $link_array['url'] ) ?>" title="<?php echo esc_attr( $link_array['title'] ); ?>" target="<?php echo esc_attr( $link_array['target'] ); ?>"><?php echo do_shortcode( $content ) ?></a>
	</div>

	<?php

	$output = ob_get_clean();

	return do_shortcode( $output );
}

add_action( 'vc_before_init', 'mm_vc_button' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_button() {

	vc_map( array(
		'name' => __( 'Button', 'mm-components' ),
		'base' => 'mm_button',
		'class' => '',
		'icon' => MM_PLUG_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params' => array(
			array(
				'type'       => 'vc_link',
				'heading'    => __( 'Button URL', 'mm-components' ),
				'param_name' => 'link',
				'value'      => '',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Button Style', 'mm-components' ),
				'param_name' => 'style',
				'value'      => array(
					__( 'Default', 'mm-components ') => 'default',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Border Style', 'mm-components' ),
				'param_name' => 'border_style',
				'value'      => array(
					__( 'None', 'mm-components' )  => 'none',
					__( 'Thin', 'mm-components ')  => 'thin',
					__( 'Thick', 'mm-components ') => 'thick',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Color', 'mm-components' ),
				'param_name' => 'color',
				'value'      => array(
					__( 'Gray', 'mm-components ') => 'gray',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Button Size', 'mm-components' ),
				'param_name' => 'size',
				'value'      => array(
					__( 'Normal', 'mm-components ') => 'normal-size',
					__( 'Large', 'mm-components ')  => 'large',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Button Alignment', 'mm-components' ),
				'param_name' => 'alignment',
				'value'      => array(
					__( 'Default', 'mm-components ') => 'default',
					__( 'Left', 'mm-components ')    => 'left',
					__( 'Center', 'mm-components ')  => 'center',
					__( 'Right ', 'mm-components ')  => 'right',
				),
			),
			array(
				'type'        => 'textarea_html',
				'heading'     => __( 'Button Text', 'mm-components' ),
				'param_name'  => 'content',
				'admin_label' => true,
				'value'       => '',
			),
		)
	) );
}
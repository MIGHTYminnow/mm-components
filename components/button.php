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
		'link_title'   => '',
		'link_target'  => '',
		'class'        => '',
		'style'        => 'default',
		'corner_style' => 'pointed',
		'border_style' => '',
		'color'        => '',
		'size'         => '',
		'full_width'   => '',
		'alignment'    => 'left',
	), $atts );

	// Handle a raw link or a VC link array.
	if ( ! empty( $atts['link'] ) ) {

		if ( 'url' === substr( $atts['link'], 0, 3 ) ) {

			if ( function_exists( 'vc_build_link' ) ) {

				$link_array  = vc_build_link( $atts['link'] );
				$link_url    = $link_array['url'];
				$link_title  = $link_array['title'];
				$link_target = $link_array['target'];

			} else {

				$link_url    = '';
				$link_title  = '';
				$link_target = '';
			}

		} else {

			$link_url    = $atts['link'];
			$link_title  = $atts['link_title'];
			$link_target = $atts['link_target'];
		}

	} else {

		$link_url    = '';
		$link_title  = '';
		$link_target = '';
	}

	// Build the alignment class.
	$alignment = 'mm-text-align-' . $atts['alignment'];

	// Setup button classes.
	$classes = array();
	$classes[] = 'mm-button';
	$classes[] = $atts['class'];
	$classes[] = $atts['style'];
	$classes[] = $atts['corner_style'];
	$classes[] = $atts['border_style'];
	$classes[] = $atts['color'];
	$classes[] = $atts['size'];
	$classes[] = $atts['full_width'];

	$classes = implode( ' ', $classes );

	// Remove any paragraphs and extra whitespace in the button text.
	$content = wp_kses( trim( $content ), '<p>' );

	// Get Mm classes.
	$mm_classes = apply_filters( 'mm_components_custom_classes', '', $tag, $atts );

	// Build the output.
	ob_start(); ?>

	<div class="<?php echo esc_attr( $mm_classes.'-wrapper ' . $alignment ); ?>">
		<a class="<?php echo esc_attr( $classes ); ?>" href="<?php echo esc_url( $link_url ) ?>" title="<?php echo esc_attr( $link_title ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo do_shortcode( $content ) ?></a>
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
		'name'     => __( 'Button', 'mm-components' ),
		'base'     => 'mm_button',
		'class'    => '',
		'icon'     => MM_COMPONENTS_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params'   => array(
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
					__( 'Default', 'mm-components' )        => 'default',
					__( 'Ghost', 'mm-components' )          => 'ghost',
					__( 'Solid to Ghost', 'mm-components' ) => 'solid_to_ghost',
					__( '3D', 'mm-components' )             => 'three_d',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Corner Style', 'mm-components' ),
				'param_name' => 'corner_style',
				'value'      => array(
					__( 'Pointed', 'mm-components' ) => 'pointed',
					__( 'Rounded', 'mm-components' ) => 'rounded',
					__( 'Pill', 'mm-components' )    => 'pill',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Border Weight', 'mm-components' ),
				'param_name' => 'border_style',
				'value'      => array(
					__( 'None', 'mm-components' )   => 'none',
					__( 'Thin', 'mm-components' )   => 'thin',
					__( 'Medium', 'mm-components' ) => 'medium',
					__( 'Thick', 'mm-components' )  => 'thick',
				),
				'dependency' => array(
					'element' => 'style',
					'value'   => array(
						'ghost',
						'solid_to_ghost',
					)
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Color', 'mm-components' ),
				'param_name' => 'color',
				'value'      => array(
					__( 'Gray', 'mm-components' ) => 'gray',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Button Size', 'mm-components' ),
				'param_name' => 'size',
				'value'      => array(
					__( 'Normal', 'mm-components' ) => 'normal-size',
					__( 'Large', 'mm-components' )  => 'large',
				),
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __( 'Full Width Button', 'mm-components' ),
				'param_name' => 'full_width',
				'description' => __( 'Choosing full-width will make the button take up the width of its container.', 'mm-components' ),
				'value'      => array(
					__( 'Yes', 'mm-components' ) => 'full-width',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Button Alignment', 'mm-components' ),
				'param_name' => 'alignment',
				'value'      => array(
					__( 'Default', 'mm-components' ) => 'default',
					__( 'Left', 'mm-components' )    => 'left',
					__( 'Center', 'mm-components' )  => 'center',
					__( 'Right ', 'mm-components' )  => 'right',
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

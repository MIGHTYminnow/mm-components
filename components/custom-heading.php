<?php

/**
 * Visual Composer Add-ons.
 *
 * Component: Custom Heading
 *
 * @package Mm Custom Visual Composer Add-ons
 * @since   1.0.0
 */

add_shortcode( 'custom-heading', 'mm_custom_heading_shortcode' );
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

	extract( shortcode_atts( array(
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
	$mm_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $mm_classes, $tag, $atts );

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
		'name' => __( 'Custom Heading', 'mm-visual-composer-add-ons' ),
		'base' => 'custom-heading',
		'class' => '',
		'icon' => MM_PLUG_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-visual-composer-add-ons' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Heading Text', 'mm-visual-composer-add-ons' ),
				'param_name' => 'content',
				'admin_label' => true,
				'value' => '',
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Heading Level', 'mm-visual-composer-add-ons' ),
				'param_name' => 'heading',
				'std' => 'h2', // Default
				'value' => array(
					__( 'h1', 'mm-visual-composer-add-ons ') => 'h1',
					__( 'h2', 'mm-visual-composer-add-ons ') => 'h2',
					__( 'h3', 'mm-visual-composer-add-ons ') => 'h3',
					__( 'h4', 'mm-visual-composer-add-ons ') => 'h4',
					__( 'h5', 'mm-visual-composer-add-ons ') => 'h5',
					__( 'h6', 'mm-visual-composer-add-ons ') => 'h6',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Margin Bottom', 'mm-visual-composer-add-ons' ),
				'param_name' => 'margin_bottom',
				'value' => '',
				'description' => __( 'Leave blank for default or use a number value (number of pixels). Example: 16', 'mm-visual-composer-add-ons' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Font Family', 'mm-visual-composer-add-ons' ),
				'param_name' => 'font_family',
				'value' => array(
					__( 'Default', 'mm-visual-composer-add-ons ') => 'default',
					__( 'Antenna Condensed Bold', 'mm-visual-composer-add-ons ') => 'antenna-condensed',
					__( 'Antenna Condensed Black', 'mm-visual-composer-add-ons ') => 'antenna-condensed-black',
					__( 'Benton Sans Black', 'mm-visual-composer-add-ons ') => 'benton-sans-black',
					__( 'Benton Sans Bold', 'mm-visual-composer-add-ons ') => 'benton-sans-bold',
					__( 'Benton Sans Medium', 'mm-visual-composer-add-ons ') => 'benton-sans-medium',
					__( 'Benton Sans Regular', 'mm-visual-composer-add-ons ') => 'benton-sans',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Font Size', 'mm-visual-composer-add-ons' ),
				'param_name' => 'font_size',
				'value' => array(
					__( 'Default', 'mm-visual-composer-add-ons ') => 'default',
					__( '50px', 'mm-visual-composer-add-ons ') => '50px',
					__( '48px', 'mm-visual-composer-add-ons ') => '48px',
					__( '40px', 'mm-visual-composer-add-ons ') => '40px',
					__( '36px', 'mm-visual-composer-add-ons ') => '36px',
					__( '30px', 'mm-visual-composer-add-ons ') => '30px',
					__( '24px', 'mm-visual-composer-add-ons ') => '24px',
					__( '16px', 'mm-visual-composer-add-ons ') => '16px',
					__( '14px', 'mm-visual-composer-add-ons ') => '14px',
					__( '12px', 'mm-visual-composer-add-ons ') => '12px',
				),
			//'description' => __( 'See documentation for more details.', 'mm-visual-composer-add-ons' )
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Color', 'mm-visual-composer-add-ons' ),
				'param_name' => 'color',
				'value' => array(
					__( 'Default', 'mm-visual-composer-add-ons ') => 'default',
					__( '-- COLOR --', 'mm-visual-composer-add-ons ') => '',
					__( 'gogenta', 'mm-visual-composer-add-ons ') => 'gogenta',
					__( 'gogenta-lite', 'mm-visual-composer-add-ons ') => 'gogenta-lite',
					__( 'aquaman', 'mm-visual-composer-add-ons ') => 'aquaman',
					__( 'tomato-splatter', 'mm-visual-composer-add-ons ') => 'tomato-splatter',
					__( 'danger', 'mm-visual-composer-add-ons ') => 'danger',
					__( 'orange-dribble', 'mm-visual-composer-add-ons ') => 'orange-dribble',
					__( 'sizzurp', 'mm-visual-composer-add-ons ') => 'sizzurp',
					__( 'ninja-turtle', 'mm-visual-composer-add-ons ') => 'ninja-turtle',
					__( 'icon-sizzurp', 'mm-visual-composer-add-ons ') => 'icon-sizzurp',
					__( '-- GRAYSCALE --', 'mm-visual-composer-add-ons ') => '',
					__( 'white', 'mm-visual-composer-add-ons ') => 'white',
					__( 'filter-grey', 'mm-visual-composer-add-ons ') => 'filter-grey',
					__( 'background-sizzurp', 'mm-visual-composer-add-ons ') => 'background-sizzurp',
					__( 'background-warm', 'mm-visual-composer-add-ons ') => 'background-warm',
					__( 'reload-grey', 'mm-visual-composer-add-ons ') => 'reload-grey',
					__( 'background-cool', 'mm-visual-composer-add-ons ') => 'background-cool',
					__( 'line-grey', 'mm-visual-composer-add-ons ') => 'line-grey',
					__( 'audi-grey', 'mm-visual-composer-add-ons ') => 'audi-grey',
					__( 'alt-text-grey', 'mm-visual-composer-add-ons ') => 'alt-text-grey',
					__( 'soft-titanium', 'mm-visual-composer-add-ons ') => 'soft-titanium',
					__( 'batman-grey', 'mm-visual-composer-add-ons ') => 'batman-grey',
					__( 'black', 'mm-visual-composer-add-ons ') => 'black',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Text Transform', 'mm-visual-composer-add-ons' ),
				'param_name' => 'text_transform',
				'value' => array(
					__( 'None', 'mm-visual-composer-add-ons ') => 'none',
					__( 'Uppercase', 'mm-visual-composer-add-ons ') => 'uppercase',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Text Align', 'mm-visual-composer-add-ons' ),
				'param_name' => 'text_align',
				'value' => array(
					__( 'Default', 'mm-visual-composer-add-ons ') => 'default',
					__( 'Left', 'mm-visual-composer-add-ons ') => 'left',
					__( 'Center', 'mm-visual-composer-add-ons ') => 'center',
					__( 'Right ', 'mm-visual-composer-add-ons ') => 'right',
				),
			),
			array(
				'type' => 'vc_link',
				'heading' => __( 'Heading Link', 'mm-visual-composer-add-ons' ),
				'param_name' => 'link',
				'value' => '',
			),
		),
	));
}

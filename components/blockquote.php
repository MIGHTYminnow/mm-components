<?php
/**
 * Visual Composer Add-ons.
 *
 * Component: Blockquote
 *
 * @package Mm Custom Visual Composer Add-ons
 * @since   1.0.0
 */

add_shortcode( 'mm_blockquote', 'mm_blockquote_shortcode' );
/**
 * Output Blockquote.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_blockquote_shortcode( $atts, $content = null, $tag ) {

	$atts = shortcode_atts( array(
		'quote'    => '',
		'citation' => '',
	), $atts );

	// Get Mm classes
	$mm_classes = str_replace( '_', '-', $tag );
	$mm_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $mm_classes, $tag, $atts );

	// Get param values.
	$quote = ! empty( $atts['quote'] ) ? '<p>' . $atts['quote'] . '</p>' : '';
	$citation = ! empty( $atts['citation'] ) ? $atts['citation'] : '';

	return do_shortcode(
		sprintf( '[blockquote citation="%s"]%s[/blockquote]',
			$citation,
			$quote
		)
	);

}

add_action( 'vc_before_init', 'mm_vc_blockquote' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_blockquote() {
	vc_map( array(
		'name' => __( 'Blockquote', 'mm-visual-composer-add-ons' ),
		'base' => 'mm_blockquote',
		'class' => '',
		'icon' => MM_PLUG_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-visual-composer-add-ons' ),
		'params' => array(
			array(
				'type' => 'textarea',
				'heading' => __( 'Quote', 'mm-visual-composer-add-ons' ),
				'param_name' => 'quote',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Citation', 'mm-visual-composer-add-ons' ),
				'param_name' => 'citation',
			),
		)
	) );
}

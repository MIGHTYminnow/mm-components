<?php
/**
 * Visual Composer Add-ons.
 *
 * Component: Phone Number Box
 *
 * @package Mm Custom Visual Composer Add-ons
 * @since   1.0.0
 */

add_shortcode( 'mm_phone_number_box', 'mm_phone_number_box_shortcode' );
/**
 * Output Phone Number Box.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_phone_number_box_shortcode( $atts, $content = null, $tag ) {

	$atts = shortcode_atts( array(
		'heading_text'   => '',
		'paragraph_text' => '',
		'link_text'      => '',
		'link'           => '',
	), $atts );

	// Clean up content - this is necessary
	$content = wpb_js_remove_wpautop( $content, true );

	// Get link array [url, title, target]
	if ( ! empty( $atts['link_text'] ) && ! empty( $atts['link'] ) ) {
		$link_array = vc_build_link( $atts['link'] );
	}

	// Get Mm classes
	$mm_classes = str_replace( '_', '-', $tag );
	$mm_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $mm_classes, $tag, $atts );

	ob_start(); ?>

	<div class="<?php echo $mm_classes; ?>">
		<div class="col-wrap">
			<div class="col one-half first">
				<h4><?php esc_html_e( 'Crisis Support Services of Alameda County, CA - 24 Hour Crisis Line', 'mm-visual-composer-add-ons' ); ?>:</h4>
				<a href="tel:1-800-309-2131">1-800-309-2131</a>
			</div>
			<div class="col one-half">
				<h4><?php esc_html_e( 'National Suicide Prevention Lifeline', 'mm-visual-composer-add-ons' ); ?>:</h4>
				<a href="tel:1-800-273-8255">1-800-273-8255</a>
			</div>
		</div>
	</div>

	<?php

	$output = ob_get_clean();

	return $output;
}

add_action( 'vc_before_init', 'mm_vc_phone_number_box' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_phone_number_box() {
	vc_map( array(
		'name' => __( 'Phone Number Box', 'mm-visual-composer-add-ons' ),
		'base' => 'mm_phone_number_box',
		'icon' => MM_PLUG_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-visual-composer-add-ons' ),
		'show_settings_on_create' => false,
		'params' => array(),
	) );
}

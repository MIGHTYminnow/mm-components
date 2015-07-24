<?php
/**
 * MIGHTYminnow Add-ons
 *
 * Component: Expandable Content
 *
 * @package mm-add-ons
 * @since   1.0.0
 */

add_shortcode( 'mm_expandable_content', 'mm_expandable_content_shortcode' );
/**
 * Output Expandable Content.
 *
 * @since  1.0.0
 *
 * @param   array    $atts     Shortcode attributes.
 * @param   string   $content  Shortcode content.
 * @param   string   $tag      Shortcode tag.
 *
 * @return  string             Shortcode output.
 */
function mm_expandable_content_shortcode( $atts = array(), $content = null, $tag = '' ) {

	$atts = mm_shortcode_atts( array(
		'link_style'     => '',
		'link_text'      => '',
		'link_alignment' => '',
		'class'          => '',
	), $atts );

	// Clean up content - this is necessary.
	$content = wpb_js_remove_wpautop( $content, true );

	// Get Mm classes.
	$mm_classes = str_replace( '_', '-', $tag );
	$mm_classes = apply_filters( 'mm_shortcode_custom_classes', $mm_classes, $tag, $atts );

	// Add our extra classes.
	$mm_classes .= esc_attr( $atts['class'] );

	$link_alignment = ( 'left' == $atts['link_alignment'] || 'center' == $atts['link_alignment'] || 'right' == $atts['link_alignment'] ) ? 'mm-text-align-' . $atts['link_alignment'] : '';

	$link_style = ( 'button' == $atts['link_style'] || 'link' == $atts['link_style'] ) ? $atts['link_style'] : '';

	ob_start(); ?>

	<div class="<?php echo $mm_classes; ?>">
		<div class="mm-expandable-content-trigger <?php echo $link_alignment; ?>">
			<a class="mm-expandable-content-trigger-link <?php echo $link_style; ?>" title="<?php echo esc_attr( $atts['link_text'] ); ?>"><?php echo esc_html( $atts['link_text'] ); ?></a>
		</div>
		<div class="mm-expandable-content-target">
			<?php echo do_shortcode( $content ); ?>
		</div>
	</div>

	<?php

	$output = ob_get_clean();

	return $output;

}

add_action( 'vc_before_init', 'mm_vc_expandable_content' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_expandable_content() {

	/**
	 * Expandable Content.
	 */
	vc_map( array(
		'name' => __( 'Expandable Content', 'mm-add-ons' ),
		'base' => 'mm_expandable_content',
		'icon' => MM_PLUG_ASSETS_URL . 'component_icon.png',
		'as_parent' => array( 'except' => '' ),
		'is_container' => true,
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => __( 'Button or Link?', 'mm-add-ons' ),
				'param_name' => 'link_style',
				'description' => __( 'Should the trigger be a button or a link?', 'mm-add-ons' ),
				'value' => array(
					'Select Button or Link', // This is here to avoid a bug in VC 4.6 where the first value doesn't get added to the shortcode.
					__( 'Button', 'mm-add-ons' ) => 'button',
					__( 'Link', 'mm-add-ons' ) => 'link',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Button/Link Text', 'mm-add-ons' ),
				'param_name' => 'link_text',
				'description' => __( 'The text for the button/link', 'mm-add-ons' ),
				'default' => '',
				'value' => '',
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Button/Link Alignment', 'mm-add-ons' ),
				'param_name' => 'link_alignment',
				'value' => array(
					__( 'Select Left, Center, or Right', 'mm-add-ons' ), // This is here to avoid a bug in VC 4.6 where the first value doesn't get added to the shortcode.
					__( 'Left', 'mm-add-ons' ) => 'left',
					__( 'Center', 'mm-add-ons' ) => 'center',
					__( 'Right', 'mm-add-ons' ) => 'right',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'mm-add-ons' ),
				'param_name' => 'class',
				'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'mm-add-ons' )
			),
		),
		'js_view' => 'VcColumnView'
	) );

}

// This is necessary to make our elements that wrap other elements work.
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_MM_Expandable_Content extends WPBakeryShortCodesContainer {
    }
}
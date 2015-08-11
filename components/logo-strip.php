<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Logo Strip
 *
 * @package mm-components
 * @since   1.0.0
 */

add_shortcode( 'logo_strip', 'mm_logo_strip_shortcode' );
/**
 * Output Logo Strip.
 *
 * @since  1.0.0
 *
 * @param   array  $atts  Shortcode attributes.
 *
 * @return  string        Shortcode output.
 */
function mm_logo_strip_shortcode( $atts, $content = null, $tag ) {

	extract( mm_shortcode_atts( array(
		'title'      => '',
		'images'      => '',
	), $atts ) );

	// Clean up content - this is necessary
	$content = wpb_js_remove_wpautop( $content, true );

	// Quit if no images are specified
	if ( ! $images ) {
		return;
	}

	// Create array from comma-separated image list
	$images = explode( ',', ltrim( $images ) );

	// Get Mm classes
	$mm_classes = str_replace( '_', '-', $tag );
	$mm_classes = apply_filters( 'mm_shortcode_custom_classes', $mm_classes, $tag, $atts );

	ob_start(); ?>

	<div class="<?php echo $mm_classes; ?>">

		<?php if ( $title ) : ?>
			<h4><?php echo $title; ?></h4>
		<?php endif; ?>

		<?php
			foreach ( $images as $image ) {
				echo wp_get_attachment_image( $image, 'full' );
			}
		?>

	</div>

	<?php

	$output = ob_get_clean();

	return $output;
}

add_action( 'vc_before_init', 'mm_vc_logo_strip' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_logo_strip() {

	vc_map( array(
		'name' => __( 'Logo Strip', 'mm-components' ),
		'base' => 'logo_strip',
		'class' => '',
		'icon' => MM_PLUG_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Title', 'mm-components' ),
				'param_name' => 'title',
				'admin_label' => true,
				'value' => '',
			),
			array(
				'type' => 'attach_images',
				'heading' => __( 'Logos', 'mm-components' ),
				'param_name' => 'images',
				'value' => '',
			),
		)
	) );
}

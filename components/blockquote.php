<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Blockquote
 *
 * @package mm-components
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

	$atts = mm_shortcode_atts( array(
		'image_id' => '',
		'quote'    => '',
		'citation' => '',
	), $atts );

	// Get Mm classes
	$mm_classes = str_replace( '_', '-', $tag );
	$mm_classes = apply_filters( 'mm_shortcode_custom_classes', $mm_classes, $tag, $atts );

	// Get param values.
	$quote = ! empty( $atts['quote'] ) ? '<p>' . $atts['quote'] . '</p>' : '';
	$citation = ! empty( $atts['citation'] ) ? $atts['citation'] : '';

	ob_start() ?>

	<blockquote class="<?php echo $mm_classes; ?>">

		<?php if ( $atts[ 'image_id'] ) : ?>
			<?php echo wp_get_attachment_image( $atts['image_id'], 'thumbnail' ); ?>
		<?php endif; ?>

		<?php echo $quote; ?>

		<?php if ( $citation ) : ?>
			<cite><?php echo $citation; ?></cite>
		<?php endif; ?>

	</blockquote>

	<?php

	$output = ob_get_clean();

	return $output;
}

add_action( 'vc_before_init', 'mm_vc_blockquote' );
/**
 * Visual Composer add-on.
 *
 * @since  1.0.0
 */
function mm_vc_blockquote() {

	vc_map( array(
		'name' => __( 'Blockquote', 'mm-components' ),
		'base' => 'mm_blockquote',
		'class' => '',
		'icon' => MM_COMPONENTS_ASSETS_URL . 'component_icon.png',
		'category' => __( 'Content', 'mm-components' ),
		'params' => array(
			array(
				'type' => 'attach_image',
				'heading' => __( 'Image', 'mm-components' ),
				'param_name' => 'image_id',
				'description' => __( 'Select an image from the library.', 'mm-components' ),
			),
			array(
				'type' => 'textarea',
				'heading' => __( 'Quote', 'mm-components' ),
				'param_name' => 'quote',
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Citation', 'mm-components' ),
				'param_name' => 'citation',
			),
		)
	) );
}

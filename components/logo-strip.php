<?php
/**
 * MIGHTYminnow Components
 *
 * Component: Logo Strip
 *
 * @package mm-components
 * @since   1.0.0
 */

add_shortcode( 'mm_logo_strip', 'mm_logo_strip_shortcode' );
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

	$atts = mm_shortcode_atts( array(
		'title'           => '',
		'title_alignment' => '',
		'images'          => '',
		'image_size'      => '',
	), $atts );

	$title = $atts['title'];
	$title_alignment = $atts['title_alignment'];
	$images = $atts['images'];
	$image_size = ( '' !== $atts['image_size'] ) ? (string)$atts['image_size'] : 'full';

	// Clean up content - this is necessary.
	$content = wpb_js_remove_wpautop( $content, true );

	// Quit if no images are specified.
	if ( ! $images ) {
		return;
	}

	// Create array from comma-separated image list.
	$images = explode( ',', ltrim( $images ) );

	// Count how many images we have.
	$image_count = count( $images );
	$image_count = 'logo-count-' . (int)$image_count;

	// Get Mm classes.
	$mm_classes = str_replace( '_', '-', $tag );
	$mm_classes = apply_filters( 'mm_shortcode_custom_classes', $mm_classes, $tag, $atts );

	// Set up the title alignment.
	if ( '' === $title_alignment || 'center' === $title_alignment ) {
		$title_class = 'mm-text-align-center';
	} elseif( 'right' === $title_alignment ) {
		$title_class = 'mm-text-align-right';
	} else {
		$title_class = 'mm-text-align-left';
	}

	ob_start(); ?>

	<div class="<?php echo $mm_classes; ?> <?php echo $image_count ?>">

		<?php if ( $title ) : ?>
			<h4 class="<?php echo $title_class; ?>"><?php echo $title; ?></h4>
		<?php endif; ?>

		<?php
			foreach ( $images as $image ) {
				printf(
					'<div class="logo">%s</div>',
					wp_get_attachment_image( $image, $image_size )
				);
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

	$image_sizes = mm_get_image_sizes_for_vc();

	vc_map( array(
		'name' => __( 'Logo Strip', 'mm-components' ),
		'base' => 'mm_logo_strip',
		'class' => '',
		'icon' => MM_COMPONENTS_ASSETS_URL . 'component_icon.png',
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
				'type' => 'dropdown',
				'heading' => __( 'Title Alignment', 'mm-components' ),
				'param_name' => 'title_alignment',
				'value' => array(
					__( 'Select a Title Alignment', 'mm-components' ) => '',
					__( 'Left', 'mm-components' ) => 'left',
					__( 'Center', 'mm-components' ) => 'center',
					__( 'Right', 'mm-components' ) => 'right',
				),
			),
			array(
				'type' => 'attach_images',
				'heading' => __( 'Logos', 'mm-components' ),
				'param_name' => 'images',
				'description' => __( 'The bigger the image size, the better', 'mm-components' ),
				'value' => '',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Logo Image Size', 'mm-components' ),
				'param_name' => 'image_size',
				'value' => $image_sizes,
			),
		)
	) );
}

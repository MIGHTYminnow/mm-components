<?php
/**
 * Image Left Template for Mm Blockquote.
 *
 * @since  1.0.0
 */
add_filter( 'mm_blockquote_templates', 'mm_blockquote_image_left_template', 0 );

/**
 * Register this template with Mm Posts.
 */
function mm_blockquote_image_left_template( $templates ) {
	$templates['image-left'] = __( 'Image Left', 'mm-components' );
	return $templates;
}
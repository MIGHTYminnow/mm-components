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

add_action( 'mm_blockquote_register_hooks', 'mm_blockquote_image_content_hooks', 9, 1 );
/**
 * Modify the default hooks.
 */
function mm_blockquote_image_content_hooks( $args ) {
	// Only affect the output if this template is being used.
	if ( 'image-left' != $args['template'] ) {
		return;
	}
	// Turn off all default output.
	remove_all_actions( 'mm_blockquote_content' );

	add_action( 'mm_blockquote_content', 'mm_blockquote_output_image');
	add_action( 'mm_blockquote_content', 'mm_blockquote_output_content');

}

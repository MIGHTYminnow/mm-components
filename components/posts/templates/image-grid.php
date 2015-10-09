<?php
/**
 * Image Grid template for Mm Posts.
 *
 * @since  1.0.0
 */

add_filter( 'mm_posts_templates', 'mm_posts_image_grid_template', 0 );
/**
 * Register this template with Mm Posts.
 */
function mm_posts_image_grid_template( $templates ) {

	$template_name = __( 'Image Grid', 'mm-components' );

	$templates[ $template_name ] = 'image-grid';

	return $templates;
}

add_action( 'mm_posts_register_hooks', 'mm_posts_image_grid_hooks', 10, 2 );
/**
 * Modify the default hooks.
 */
function mm_posts_image_grid_hooks( $context, $atts ) {

	// Only affect the output if this template is being used.
	if ( 'image-grid' != $atts['template'] ) {
		return;
	}

	// Turn off all default output.
	remove_action( 'mm_posts_header', 'mm_posts_output_post_header', 10 );
	remove_action( 'mm_posts_content', 'mm_posts_output_post_image', 8 );
	remove_action( 'mm_posts_content', 'mm_posts_output_post_content', 10 );
	remove_action( 'mm_posts_footer', 'mm_posts_output_post_meta', 10 );

	// Include our custom output.
	add_action( 'mm_posts_content', 'mm_posts_output_post_image', 8, 3 );
	add_action( 'mm_posts_content', 'mm_posts_output_post_title', 10, 3 );
}

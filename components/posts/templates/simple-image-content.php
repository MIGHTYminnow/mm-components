<?php
/**
 * Simple Image & Content template for Mm Posts.
 *
 * @since  1.0.0
 */

add_filter( 'mm_posts_templates', 'mm_posts_simple_image_content_template', 0 );
/**
 * Register this template with Mm Posts.
 */
function mm_posts_simple_image_content_template( $templates ) {

	$templates['simple-image-content'] = __( 'Simple Image & Content', 'mm-components' );

	return $templates;
}

add_action( 'mm_posts_register_hooks', 'mm_posts_simple_image_content_hooks', 10, 2 );
/**
 * Modify the default hooks.
 */
function mm_posts_simple_image_content_hooks( $context, $args ) {

	// Only affect the output if this template is being used.
	if ( 'simple-image-content' != $args['template'] ) {
		return;
	}

	// Turn off all default output.
	remove_action( 'mm_posts_header', 'mm_posts_output_post_header', 10 );
	remove_action( 'mm_posts_content', 'mm_posts_output_post_image', 8 );
	remove_action( 'mm_posts_content', 'mm_posts_output_post_content', 10 );
	remove_action( 'mm_posts_footer', 'mm_posts_output_post_meta', 10 );

	// Include our custom output.
	if ( mm_true_or_false( $args['show_featured_image'] ) ) {
		add_action( 'mm_posts_content', 'mm_posts_output_post_image', 8, 3 );
	}

	add_action( 'mm_posts_content', 'mm_posts_output_post_title', 10, 3 );

	if ( mm_true_or_false( $args['show_post_meta'] ) ) {
		add_action( 'mm_posts_content', 'mm_posts_output_post_meta', 11, 3 );
	}

	add_action( 'mm_posts_content', 'mm_posts_output_post_content', 12, 3 );
}
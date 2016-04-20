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
		add_action( 'mm_posts_content', 'mm_posts_output_custom_post_image_simple_image_content', 8, 3 );
	}

	add_action( 'mm_posts_content', 'mm_posts_output_post_title', 10, 3 );

	if ( mm_true_or_false( $args['show_post_meta'] ) ) {
		add_action( 'mm_posts_content', 'mm_posts_output_post_meta', 11, 3 );
	}

	add_action( 'mm_posts_content', 'mm_posts_output_post_content', 12, 3 );
}

/**
 * Custom Image Output for Simple Image & Content template.
 */
function mm_posts_output_custom_post_image_simple_image_content( $post, $context, $args ) {

	$custom_output = apply_filters( 'mm_posts_post_image', '', $post, $context, $args );

	if ( '' !== $custom_output ) {
		echo $custom_output;
		return;
	}

	// Default to using the 'post-thumbnail' size.
	if ( '' !== $args['featured_image_size'] ) {
		$image_size = esc_attr( $args['featured_image_size'] );
	} else {
		$image_size = 'post-thumbnail';
	}

	// Check for existing featured image
	if ( has_post_thumbnail( $post->ID ) ) {

		$image_tag = get_the_post_thumbnail( $post->ID, $image_size );

	} else {

		$fallback_image = $args['fallback_image'];

		// Support the fallback image
		if ( is_numeric( $fallback_image ) ) {
			$image_tag = wp_get_attachment_image( $fallback_image, $image_size );
		}
	}

	// Output image with/without link
	if ( mm_true_or_false( $args['link_title'] ) ) {
		printf(
			'<div class="entry-image"><a href="%s">%s</a></div>',
			get_permalink( $post->ID ),
			$image_tag
		);
	} else {
		printf(
			'<div class="entry-image">%s</div>',
			$image_tag
		);
	}
}

add_action( 'mm_posts_register_extra_vc_params', 'mm_posts_simple_image_content_extra_vc_params' );
/**
 * Add extra params for this template to VC element.
*/
function mm_posts_simple_image_content_extra_vc_params() {

	$simple_image_content_params = array();

	$simple_image_content_params[] = array(
		'type'       => 'attach_image',
		'heading'    => __( 'Fallback Image', 'mm-components' ),
		'param_name' => 'fallback_image',
		'description' => __( 'Image to display when the post has no featured image.', 'mm-components' ),
		'dependency' => array(
			'element' => 'template',
			'value'   => 'simple-image-content',
		),
	);

	vc_add_params( 'mm_posts', $simple_image_content_params );
}